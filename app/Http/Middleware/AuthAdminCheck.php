<?php

namespace App\Http\Middleware;

use Closure;

use App\Libraries\Auth;
// use App\Services\SettingService;

class AuthAdminCheck
{
    public function __construct()
    {
        
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $settings = ''; //resolve(SettingService::Class)->getSettings();
        $emailTemplateStyle = ''; //resolve(SettingService::Class)->emailTemplateStyle;
        $getUserInfo = resolve(Auth::Class)->getUserInfo();
        if(!$getUserInfo)
        {
            return redirect( route('admin.login') );
        }
        $getUserInfo = encryptMulti($getUserInfo, ['id']);

        $request->attributes->add(['settings'=> $settings, 'currentUser'=> $getUserInfo]);

        view()->share('currentUser', $getUserInfo);
        view()->share('settings', $settings);
        view()->share('emailTemplateStyle', $emailTemplateStyle);
        
        return $next($request);
    }
}
