<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Batch;
use App\Models\Branch;
use App\Models\Email;
use App\Models\Institute;
use App\Models\Role;
use App\Models\UserRoles;
use App\Notifications\UserRegistered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Validator;
use DB;

use Carbon\Carbon;
use App\Traits\Notifications;
use App\Libraries\Auth;

use App\Models\User;

class UsersController extends Controller
{
    use Notifications;

    public function index()
    {
        if(!is_admin()) {
            unauthorizedRedirect();
        }
        $authUser = getRequestAttributes('currentUser');
        return view("admin.users.list" );
    }

    public function filter(Request $request)
    {
        $LISTING_PER_PAGE_LIMIT = getConfig('constants', 'LISTING_PER_PAGE_LIMIT');
        $data["draw"] = $request->input("draw");
        $data["recordsTotal"] = 0;
        $data["recordsFiltered"] = 0;
        $allUsers = array();
        $data["data"] = $allUsers;
        $getUserInfo = getRequestAttributes('currentUser');
        
        $ordableColumn = array("0"=> "u.first_name", "1"=> "u.email", "2"=> "u.phone");
        $start = $request->input("start");
        $length = $request->input("length");
        $order = $request->input("order");
        $columns = $request->input("columns");
        $orderedColumn = (isset($order[0]) && isset($order[0]["column"])) ? $order[0]["column"] : '0';
        $orderedPref = (isset($order[0]) && isset($order[0]["dir"])) ? $order[0]["dir"] : 'asc';

        $search = $request->input("search");
        $searchedText = (isset($search["value"]) && $search["value"]!='') ? $search["value"] : '';

        $defaultPerPage = $request->input("length", $LISTING_PER_PAGE_LIMIT);
        $pageNumber = (($start/$defaultPerPage) + '1');

        $tblusers = resolve(User::Class)->tablename();

        $totalSQL = DB::table($tblusers.' as u')->select('u.id');
        $totalSQL->where("deleted_at", NULL);
        if($searchedText!='')
        {
            $totalSQL->where(function($searchedItems) use($searchedText) {
                $searchedItems->orWhere("u.first_name", "like", "%".$searchedText."%");
                $searchedItems->orWhere("u.last_name", "like", "%".$searchedText."%");
                $searchedItems->orWhere("u.username", "like", "%".$searchedText."%");
                $searchedItems->orWhere("u.email", "like", "%".$searchedText."%");
            });
        }
        for($col=0;$col<count($ordableColumn); $col++)
        {
            if(isset($columns[$col]) && isset($columns[$col]["search"]) && isset($columns[$col]["search"]["value"]) && $columns[$col]["search"]["value"]!='')
            {
                $totalSQL->where($ordableColumn[$col], "like", "%".$columns[$col]["search"]["value"]."%");
                if($col=='0') { $totalSQL->orWhere("u.last_name", "like", "%".$columns[$col]["search"]["value"]."%"); }
            }
        }
        $total = $totalSQL->count();
        
        $usersSQL = DB::table($tblusers.' as u')->select('u.id', 'u.first_name', 'u.last_name', DB::raw('concat(first_name, " ", last_name) as fullname'), 'u.email',  'u.status', 'u.type');
        $usersSQL->where('deleted_at',NULL);
        if($searchedText!='')
        {
            $usersSQL->where(function($searchedItems) use($searchedText) {
                $searchedItems->orWhere("u.first_name", "like", "%".$searchedText."%");
                $searchedItems->orWhere("u.last_name", "like", "%".$searchedText."%");
                $searchedItems->orWhere("u.username", "like", "%".$searchedText."%");
                $searchedItems->orWhere("u.email", "like", "%".$searchedText."%");
            });
        }
        for($col=0;$col<count($ordableColumn); $col++)
        {
            if(isset($columns[$col]) && isset($columns[$col]["search"]) && isset($columns[$col]["search"]["value"]) && $columns[$col]["search"]["value"]!='')
            {
                $usersSQL->where($ordableColumn[$col], "like", "%".$columns[$col]["search"]["value"]."%");
                if($col=='0') { $usersSQL->orWhere("u.last_name", "like", "%".$columns[$col]["search"]["value"]."%"); }
            }
        }
        
        if(!is_admin()) {
            $usersSQL->where('institute_id',$getUserInfo->institute_id);
        }
        // if ($instituteId) {
        //     $usersSQL->where("u.institute_id", $instituteId);
        // }
        // if ($branchId) {
        //     $usersSQL->where("u.branch_id", $branchId);
        // }
        $users = $usersSQL->orderBy($ordableColumn[$orderedColumn], $orderedPref)->paginate($defaultPerPage, ['*'], 'page', $pageNumber);

        $data["draw"] = $request->input("draw");
        $data["recordsTotal"] = $total;
        $data["recordsFiltered"] = $total;

        if($users)
        {
            foreach($users as $user)
            {
                $allUsers[] = array(
                    "hash"=> encryptString($user->id),
                    "name"=> $user->fullname,
                    "email"=> $user->email,
                    "phone"=> $user->phone,
                    "status"=> $user->status,
                    "type"=> $user->type,
                );
            }
        }

        $data["data"] = $allUsers;

        return response()->json($data);
    }

    public function create()
    {
        if(!hasPermission('quiz-report') && !is_customer()) {
            unauthorizedRedirect();
        }
        $user = null;
        $mode = 'Add';
        $authUser = getRequestAttributes('currentUser');


        return view("admin.users.form", compact(["user", "mode"]));
    }

    public function store(Request $request)
    {
        if(!hasPermission('add-user') && !is_customer()) {
            unauthorizedRedirect();
        }

        $authUser = getRequestAttributes('currentUser');
        $validateFields = [
            'first_name'=> 'required|max:100',
            'last_name'=> 'required|max:100',
            'email'=> 'required|max:250|email|unique:users,email,NULL,id',
            'username'=> 'required|max:100|unique:users,username,NULL,id',
            'type'=> 'required',
            'phone'=> 'required|min:10|max:10',
            'password'=> 'required',
            'confirm_password'=> 'required|same:password',
        ];

        $validateFieldsMessages = [
            'first_name.required'=> 'First name is required.',
            'first_name.max'=> 'First name should be maximum of 100.',
            'last_name.required'=> 'Last name is required.',
            'last_name.max'=> 'Last name should be maximum of 100.',
            'email.required'=> 'Email is required.',
            'email.email'=> 'Please provide valid Email.',
            'email.max'=> 'Email should be maximum of 250.',
            'username.required'=> 'Username is required.',
            'username.unique'=> 'Username is already registered.',
            'username.max'=> 'Username should be maximum of 100.',
            'type.required'=> 'Please select user type.',
            'phone.required'=> 'Please enter phone number.',
            'phone.max'=> 'Phone number should be maximum of 100.',
            'phone.min'=> 'Phone number should be minimum of 10.',
            'password.required'=> 'Please enter password.',
            'confirm_password.required'=> 'Please enter confirm password.',
            'confirm_password.same'=> 'Confirm password must be same as password.',
        ];

        $validator = $request->validate($validateFields, $validateFieldsMessages);

        $user = new User();
        $user->first_name = $request->first_name;
        $user->last_name = $request->last_name;
        $user->email = $request->email;
        $user->username = $request->username;
        if($request->password!='') $user->password = Hash::make($request->password);
        $user->phone = $request->phone;
        $user->type = $request->type;
        $user->save();


        session()->flash('success', $this->notifyuser("USER_ADDED"));
        return redirect()->route('admin.users');
    }

    public function edit($id)
    {
        if(!hasPermission('edit-user') && !is_customer()) {
            unauthorizedRedirect();
        }
        $authUser = getRequestAttributes('currentUser');
        $userid = decryptString($id);
        $mode = 'Edit';
        $user = User::find($userid);
        return view("admin.users.form", compact(["user", "mode"]));
    }

    public function update(Request $request, $id)
    {
        if(!hasPermission('edit-user') && !is_customer()) {
            unauthorizedRedirect();
        }
        $userid = decryptString($id);
        $authUser = getRequestAttributes('currentUser');
        $validateFields = [
            'first_name'=> 'required|max:100',
            'last_name'=> 'required|max:100',
            'email'=> 'required|max:250|email|unique:users,email,'.$userid.',id',
            'username'=> 'required|max:100|unique:users,username,'.$userid.',id',
            // 'password'=> 'required',
        ];

        $validateFieldsMessages = [
            'first_name.required'=> 'First name is required.',
            'first_name.max'=> 'First name should be maximum of 100.',
            'last_name.required'=> 'Last name is required.',
            'last_name.max'=> 'Last name should be maximum of 100.',
            'email.required'=> 'Email is required.',
            'email.email'=> 'Please provide valid Email.',
            'email.max'=> 'Email should be maximum of 250.',
            'username.required'=> 'Username is required.',
            'username.unique'=> 'Username is already registered.',
            'username.max'=> 'Username should be maximum of 100.',
            // 'password.required'=> 'Password is required.',
        ];

        $validator = $request->validate($validateFields, $validateFieldsMessages);


        $user = User::find($userid);
        $user->first_name = $request->first_name;
        $user->last_name = $request->last_name;
        $user->email = $request->email;
        $user->username = $request->username;
        $user->type = $request->type;
        if(($user->type != 'Admin' || $user->type == 'AltAdmin') && isset($request->branch_name))
        {
            $user->branch_id = decryptString($request->branch_name);
        }
        if($request->is_instructor == "on") {
            $user->is_instructor = true;
        }
        if($user->save())
        {
            if($authUser->id==$userid){
                resolve(Auth::Class)->refreshUserCache($user);
            }
        }
        else {
            return back();
        }


        session()->flash('success', $this->notifyuser("USER_UPDATED"));
        return redirect()->route('admin.users');
    }

    public function show($id)
    {
        if(!hasPermission('view-users')) {
            unauthorizedRedirect();
        }
        $userID = decryptString($id);
        $user = User::find($userID);

        return view("admin.users.detail", compact(["user"]));
    }

    public function destroy($id)
    {
        if(!hasPermission('delete-user')) {
            unauthorizedRedirect();
        }
        $userID = decryptString($id);
        $user = User::find($userID);
        $user->status = 'Deleted';
        $user->save();
        $user->delete();

        return view("admin.users.list", compact([]));
    }

    public function profile()
    {
        $user = getRequestAttributes('currentUser');
        $isMyProfile = true;
        return view("admin.users.form", compact(["isMyProfile", "user"]));
    }

    public function profile_update(Request $request)
    {
        $authUser = getRequestAttributes('currentUser');
        $userId = decryptString($authUser->id);
        $validateFields = [
            'first_name'=> 'required|max:100',
            'last_name'=> 'required|max:100',
            'email'=> 'required|max:250|email|unique:users,email,'.$userId.',id',
            'username'=> 'required|max:100|unique:users,username,'.$userId.',id',
        ];

        $validateFieldsMessages = [
            'first_name.required'=> 'First name is required.',
            'first_name.max'=> 'First name should be maximum of 100.',
            'last_name.required'=> 'Last name is required.',
            'last_name.max'=> 'Last name should be maximum of 100.',
            'email.required'=> 'Email is required.',
            'email.email'=> 'Please provide valid Email.',
            'email.max'=> 'Email should be maximum of 250.',
            'username.required'=> 'Username is required.',
            'username.unique'=> 'Username is already registered.',
            'username.max'=> 'Username should be maximum of 100.',
        ];

        $validator = $request->validate($validateFields, $validateFieldsMessages);

        $user = User::findOrFail(decryptString($authUser->id));
        $user->first_name = $request->first_name;
        $user->last_name = $request->last_name;
        $user->email = $request->email;
        $user->username = $request->username;
        if($request->password!='') $user->password = $request->password;
        $user->phone = $request->phone;
        $user->phone = $request->phone;
        if($user->save())
        {
            resolve(Auth::Class)->refreshUserCache($user);
        }
        session()->flash('success', $this->notifyuser("PROFILE_UPDATE"));

        return redirect()->route('admin.profile');
    }

    public function edit_password() {

        $user = getRequestAttributes('currentUser');
        // dd($authUser);
        $mode = "EditPassword";
        return view('admin.users.form',compact(["mode","user"]));

    }

    public function toggle_block($id) {
        // dd($id);
        $userid = decryptString($id);
        $user = User::findOrFail($userid); // Fetch the User model instance using the ID
        // dd($user);
        if($user->status =='Blocked') {
            $user->status = 'Active';
        }
        else {
            $user->status='Blocked';
        }
        $user->save();
        return redirect()->route('admin.users');
    }

    public function update_password(Request $request){
        $authUser =  getRequestAttributes('currentUser');
        $validateFields = [
            'current_password'=>'required',
            'new_password'=>'required|min:8',
            'confirm_new_password'=>'required|same:new_password'
        ];

        $validateFieldsMessages = [
            'current_password.required'=> 'Current Password is required.',
            'new_password.required'=> 'New Password is required.',
            'new_password.min'=>'Password should be alteast 8 characters',
            'confirm_new_password.same'=>'Confirm New Password must be same as new password'
        ];
        $validator = $request->validate($validateFields, $validateFieldsMessages);
        $user = User::findOrFail(decryptString($authUser->id));
        if (!Hash::check($request->current_password, $user->password)) {
            return redirect()->back()->withErrors(['current_password' => 'The current password is incorrect.'])->withInput();
        }
        $user->password = Hash::make($request->new_password);
        $user->save();
        session()->flash('success', $this->notifyuser("PASSWORD_UPDATED"));
        return redirect()->route('admin.profile');

    }


}