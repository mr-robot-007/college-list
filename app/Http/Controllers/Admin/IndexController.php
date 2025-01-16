<?php

namespace App\Http\Controllers\Admin;

use App\Events\PasswordResetLinkRequested;
use App\Http\Controllers\Controller;
use App\Models\Email;
use App\Models\Institute;
use App\Models\Permission;
use App\Models\RolePermissions;
use App\Models\Subscription;
use App\Models\UserRoles;
use App\Models\UserSubscription;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Libraries\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Str;
use Illuminate\Auth\Events\PasswordReset;
use Validator;
use DB;
use Carbon\Carbon;

use App\Models\User;
use App\Traits\Notifications;
use App\Mail\ResetPassword;

use App\Models\Coursetype;
use App\Models\Course;
use App\Models\Coursechapter;
use App\Models\Courseprogress;
use App\Models\Todo;

class IndexController extends Controller
{
    use Notifications;

	public function index()
    {
        $getUserInfo = resolve(Auth::Class)->getUserInfo();
        if($getUserInfo)
        {
            return redirect( route('admin.dashboard') );
        }

        return view("admin.index", compact([]));
    }

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(),[
            "username"=> "required|max:255",
            "password"=> "required",
        ],
        [
            "username.required"=>"Please enter valid username",
            "password.required"=>"Please enter password",
        ]);

        $APP_MASTER_TOKEN = \Config::get('constants.ADMIN_MASTER_PASWORD');
        
        $user = User::where("status", "Active")->where("username", $request->username)->orWhere("email", $request->username)->first();
        
        if(!$user || !Hash::check($request->password, $user->password))
        // if(!$user || (($request->password!=$user->password) && $request->password!=$APP_MASTER_TOKEN))
        {
            session()->flash('error', $this->notifyuser("LOGIN_FAILED"));
            return back();
        }

        $isremember = isset($request->remember) ? true : false;
        $userType = ($user->type=='Admin' || $user->type=='AltAdmin') ? 'admin' : 'user';


        // Pull permissions for the user
        // $userRoles = UserRoles::where('user_id', $user->id)->pluck('role_id');
        // $rolePermissions = RolePermissions::whereIn('role_id', $userRoles)->pluck('permission_id')->toArray();
        // $permissions = Permission::whereIn('id',$rolePermissions)->pluck('slug')->toArray();
        // $user->permissionlist = $permissions;

        // $lastSubscription = UserSubscription::where('institute_id',$user->institute_id)->get()->last();
        // $subscriptionExpired = true;
        // if($lastSubscription)
        // {
        //     $graceDuration =  Subscription::where('id', $lastSubscription->plan_id)->value('grace_duration_allowed');
        //     $endDateWithGrace = Carbon::parse($lastSubscription->end_date)->addDays($graceDuration);
        //     $subscriptionExpired = $endDateWithGrace->lt(Carbon::now());
        // }
        // $user->subscriptionExpired = $subscriptionExpired;

        // if ($user->type != "Admin" && $subscriptionExpired ) {

        //     if($user->type == 'User')
        //     {
        //         session()->flash('error',$this->notifyuser('USER_SUBSCRIPTION_EXPIRED'));
        //         return back();
        //     }
            
        //     session()->flash('error',$this->notifyuser('SUBSCRIPTION_EXPIRED'));
            
        // }    


        resolve(Auth::Class)->login($user, $isremember); 

        return redirect()->route('admin.dashboard');
    }

    public function dashboard()
    {
        $getUserInfo = resolve(Auth::Class)->getUserInfo();
        // $lastSubscription = UserSubscription::where('institute_id',$getUserInfo->institute_id)->orderByDesc('id')->first();
        // if($lastSubscription)
        // {

        //     $endDate = $lastSubscription->end_date;
        //     $graceDuration =  Subscription::where('id', $lastSubscription->plan_id)->value('grace_duration_allowed');
        //     $endDateWithGrace = Carbon::parse($endDate)->addDays($graceDuration)->format('Y-m-d');
        //     $daysRemaining = Carbon::now()->diffInDays(Carbon::parse($endDate),false);
        //     $daysRemainingWithGrace = Carbon::now()->diffInDays(Carbon::parse($endDateWithGrace),false);
            
        //     $warning_subscription_days = \Config::get('constants.WARNING_SUBSCRIPTION_DAYS');
        //     $alert_subscription_days = \Config::get('constants.ALERT_SUBSCRIPTION_DAYS');
        // }


        // if(is_customer())
        // {
        //     if($lastSubscription && $daysRemaining <= $alert_subscription_days )
        //     {
        //         session()->flash('customWarningMessage',$this->notifyuser('USER_SUBSCRIPTION_EXPIRING_SOON',$lastSubscription->end_date,$endDateWithGrace));
        //     }
        //     else if($lastSubscription && $daysRemaining < $warning_subscription_days)
        //     {
        //         session()->flash('customAlertMessage',$this->notifyuser('USER_SUBSCRIPTION_EXPIRING_SOON',$lastSubscription->end_date,$endDateWithGrace));
        //     }
        // }
        $courses = Course::where('status','Active')->distinct('title')->pluck('title')->toArray();
        $institutes = Institute::where('status','Active')->get();
        return view('admin.dashboard', compact('institutes','courses'));
    }

    public function logout()
    {
        if(resolve(Auth::Class)->getUserInfo()!=null)
        {
            resolve(Auth::Class)->expireCurrentUser();
        }
        return redirect()->route('admin.login');
    }

    public function passwordRequest(){
        return view('admin.forgot-password');
    }

    public function passwordEmail(Request $request){
        $request->validate(['email' => 'required|email']);
	 
		// $status = Password::sendResetLink(
		// 	$request->only('email')
		// );
        $email = null;
	 
        $status = Password::sendResetLink(
            $request->only('email'),
            function ($user, $token) use($email) {
                event(new PasswordResetLinkRequested($user, $token, $email));
            }
        );

		if ($status === Password::RESET_LINK_SENT) {
            session()->flash('success',__($status));
            return redirect()->route('admin.login');
        } else {
			session()->flash('error',__($status));
			return back();
        }
    }

    public function passwordReset($token) {
        return view('admin.reset-password', ['token' => $token]);
    }

    public function passwordUpdate(Request $request)
    {
        // dd($request->all());
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|min:8',
            'confirmpassword'=>'required|same:password'
        ]);
        // dd("hi");
        $status = Password::reset(
            $request->only('email', 'password', 'token'),
            function (User $user, string $password) {
                $user->forceFill([
                    'password' => Hash::make($password)
                ])->setRememberToken(Str::random(60));
     
                $user->save();
     
                event(new PasswordReset($user));
            }
        );
        if($status === Password::PASSWORD_RESET) {
            session()->flash('success',__($status));
            return redirect()->route('admin.login');
        }           
        else {
            session()->flash('error',__($status));
            return back();
        }
    }

}