<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Libraries\Auth;
use App\Models\Branch;
use App\Models\Email;
use App\Models\Institute;
use App\Models\InstituteSettings;
use App\Models\User;
use App\Notifications\UserRegistered;
use App\Traits\Notifications;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class InstituteController extends Controller
{
    use Notifications;
    public function index()
    {
        if(!is_admin()) {
            unauthorizedRedirect();
        }
        return view('admin.institutes.list', compact([]));
    }

    public function filter(Request $request)
    {
        $LISTING_PER_PAGE_LIMIT = getConfig('constants', 'LISTING_PER_PAGE_LIMIT');
        $data["draw"] = $request->input("draw");
        $data["recordsTotal"] = 0;
        $data["recordsFiltered"] = 0;
        $allInstitutes = array();
        $data["data"] = $allInstitutes;
        $getUserInfo = getRequestAttributes('currentUser');
        
        $ordableColumn = array("0"=> "i.university_name", "1"=> "i.approved_by", "2"=> "i.university_website","3"=>"i.verification","4"=>"i.status");
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

        $tblinstitutes = resolve(Institute::Class)->tablename();

        $totalSQL = DB::table($tblinstitutes.' as i')->select('i.id');
        $totalSQL->where("deleted_at", NULL);
        if($searchedText!='')
        {
            $totalSQL->where(function($searchedItems) use($searchedText) {
                $searchedItems->orWhere("i.university_name", "like", "%".$searchedText."%");
                $searchedItems->orWhere("i.approved_by", "like", "%".$searchedText."%");
                $searchedItems->orWhere("i.university_website", "like", "%".$searchedText."%");
                $searchedItems->orWhere("i.verification", "like", "%".$searchedText."%");
            });
        }
        for($col=0;$col<count($ordableColumn); $col++)
        {
            if(isset($columns[$col]) && isset($columns[$col]["search"]) && isset($columns[$col]["search"]["value"]) && $columns[$col]["search"]["value"]!='')
            {
                $totalSQL->where($ordableColumn[$col], "like", "%".$columns[$col]["search"]["value"]."%");
            }
        }
        $total = $totalSQL->count();
        
        $institutesSQL = DB::table($tblinstitutes.' as i')->select('i.id', 'i.university_name', 'i.approved_by',  'i.university_website', 'i.verification', 'i.status');
        $institutesSQL->where('deleted_at',NULL);
        if($searchedText!='')
        {
            $institutesSQL->where(function($searchedItems) use($searchedText) {
                $searchedItems->orWhere("i.university_name", "like", "%".$searchedText."%");
                $searchedItems->orWhere("i.approved_by", "like", "%".$searchedText."%");
                $searchedItems->orWhere("i.university_website", "like", "%".$searchedText."%");
                $searchedItems->orWhere("i.verification", "like", "%".$searchedText."%");
                $searchedItems->orWhere("i.address", "like", "%".$searchedText."%");
            });
        }
        for($col=0;$col<count($ordableColumn); $col++)
        {
            if(isset($columns[$col]) && isset($columns[$col]["search"]) && isset($columns[$col]["search"]["value"]) && $columns[$col]["search"]["value"]!='')
            {
                $institutesSQL->where($ordableColumn[$col], "like", "%".$columns[$col]["search"]["value"]."%");
            }
        }
        $institutes = $institutesSQL->orderBy($ordableColumn[$orderedColumn], $orderedPref)->paginate($defaultPerPage, ['*'], 'page', $pageNumber);

        $data["draw"] = $request->input("draw");
        $data["recordsTotal"] = $total;
        $data["recordsFiltered"] = $total;

        if($institutes)
        {
            foreach($institutes as $institute)
            {
                $allInstitutes[] = array(
                    "hash"=> encryptString($institute->id),
                    "university_name"=> $institute->university_name,
                    "approved_by"=> $institute->approved_by,
                    "university_website"=> $institute->university_website,
                    "verification"=> $institute->verification,
                    "status"=> $institute->status,
                );
            }
        }

        $data["data"] = $allInstitutes;

        return response()->json($data);
    }

    public function create()
    {
        if(!is_admin()) {
            unauthorizedRedirect();
        }
        $mode = 'Add';
        return view('admin.institutes.form', compact('mode'));
    }

    public function store(Request $request)
    {
        if(!is_admin()) {
            unauthorizedRedirect();
        }
        $authUser = getRequestAttributes('currentUser');
        // dd($authUser);
        $validateFields = [
            'university_name' => 'required|max:255',
            'approved_by' => 'required|max:255',
            'university_website' => 'required|max:255',
            'verification'=>'required|max:255',
            
        ];

        $validateFieldsMessages = [
            'university_name.required' => 'University name is required.',
            'university_name.max' => 'University name should be maximum of 255 characters.',
            'approved_by.required' => 'Approved by is required.',
            'approved_by.max' => 'Approved by should be maximum of 255 characters.',
            'university_website.required'=>'University website is required',
            'university_website.max'=>'University website should be of maximum of 255 characters.',
            'verification.required'=>'Verification is required',
            'verification.max'=>'Verification should be of maximum of 255 characters.',
        ];

        // dd($request->all());


        $validator = $request->validate($validateFields, $validateFieldsMessages);

        $institute = new Institute();
        $institute->university_name = $request->university_name;
        $institute->approved_by = $request->approved_by;
        $institute->university_website = $request->university_website;
        $institute->verification = $request->verification;
        $institute->status = 'Active';
        $institute->created_by = decryptString($authUser->id);
        if($institute->save())
        {
            session()->flash('success', $this->notifyuser("INSTITUTE_ADDED"));
    
            return redirect()->route('admin.institutes');
        }
        else
        {
            return back();
        }
    }
    public function show($id)
    {
        if(!is_admin()) {
            unauthorizedRedirect();
        }
        $instituteID = decryptString($id);
        $institute = Institute::find($instituteID);

        return view("admin.institutes.detail", compact(["institute"]));
    }

    public function edit($id)
    {
        if(!is_admin()) {
            unauthorizedRedirect();
        }
        $instituteid = decryptString($id);
        $mode = 'Edit';
        $institute = Institute::find($instituteid);

        return view("admin.institutes.form", compact(["institute", "mode"]));
    }

    public function update(Request $request, $id)
    {
        if(!is_admin()) {
            unauthorizedRedirect();
        }
        $instituteid = decryptString($id);
        $authUser = getRequestAttributes('currentUser');
        $validateFields = [
            'university_name' => 'required|max:255',
            'approved_by' => 'required|max:255',
            'university_website' => 'required|max:255',
            'verification'=>'required|max:255',
            'status'=>'required|max:255',
        ];

        $validateFieldsMessages = [
            'university_name.required' => 'University name is required.',
            'university_name.max' => 'University name should be maximum of 255 characters.',
            'approved_by.required' => 'Approved by is required.',
            'approved_by.max' => 'Approved by should be maximum of 255 characters.',
            'university_website.required'=>'University website is required',
            'university_website.max'=>'University website should be of maximum of 255 characters.',
            'verification.required'=>'Verification is required',
            'verification.max'=>'Verification should be of maximum of 255 characters.',
            'status.required'=>'Status is required',
            'status.max'=>'Status should be of maximum of 255 characters.',
        ];

        $validator = $request->validate($validateFields, $validateFieldsMessages);
        $userType = 'User';
        if(is_admin()) { $userType = $request->type;}
        $institute = Institute::find($instituteid);

        $institute->university_name = $request->university_name;
        $institute->approved_by = $request->approved_by;
        $institute->university_website = $request->university_website;
        $institute->verification = $request->verification;
        $institute->status = $request->status;
        $institute->updated_by = decryptString($authUser->id);
        $institute->updated_by = decryptString($authUser->id);
        
        if($institute->save())
        {
            session()->flash('success', $this->notifyuser("INSTITUTE_UPDATED"));
            return redirect()->route('admin.institutes');
        }
        else
        {
            return back();
        }


    }

    public function destroy($id)
    {
        if(!is_admin()) {
            unauthorizedRedirect();
        }
        $authUser = getRequestAttributes('currentUser');
        $instituteID = decryptString($id);
        $institute = Institute::find($instituteID);
        $institute->status = 'Deleted';
        $institute->deleted_by = decryptString($authUser->id);
        $institute->save();

        User::where('institute_id', $instituteID)
        ->where('status', 'Active')->update(['status' => 'Deleted', 'deleted_by_system' => '1','deleted_by'=>decryptString($authUser->id)]);

        User::where('institute_id', $instituteID)
        ->where('status', 'Active')->delete(); 

        $institute->delete();
        session()->flash('success', $this->notifyuser("INSTITUTE_DELETED"));

        return redirect()->route('admin.institutes');
    }
}