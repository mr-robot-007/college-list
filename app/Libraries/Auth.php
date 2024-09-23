<?php
namespace App\Libraries;

use Cookie;
use \Cache;
use Carbon\Carbon;

class Auth
{
	public $apiTokenPlaceholder = 'token';
	public $currentUserPlaceholder = 'user';

	public $userTypes = ["admin"=> "admin", "altadmin"=> "altadmin", "company"=> "company", "user"=> "user"];

	public function __construct()
	{
		
	}

	public function isAuthAdmin()
	{
		return !!$this->currentUser($this->userTypes["admin"]);
	}

	public function isAuthUser()
	{
		return !!$this->currentUser($this->userTypes["user"]);
	}
	
	public function getUserInfo()
	{
		return $this->currentUser();
	}

	public function currentUser()
	{
		$apiCookieToken = Cookie::get($this->currentUserPlaceholder);
		$apiSessionToken = session($this->currentUserPlaceholder);
		
		if($apiCookieToken!='')
		{
			if(Cache::has($this->currentUserPlaceholder.$apiCookieToken))
			{
				return json_decode( Cache::get($this->currentUserPlaceholder.$apiCookieToken) );
			} else return null;
		} else if($apiSessionToken!='')
		{
			if(Cache::has($this->currentUserPlaceholder.$apiSessionToken))
			{
				return json_decode( Cache::get($this->currentUserPlaceholder.$apiSessionToken) );
			}
		}
	}

	public function setCurrentUser($user, $isremember=false)
	{
		$nextExpiryTime = 60*60*24*365;
		$expiresAt = Carbon::now()->addMinutes($nextExpiryTime);
		if($isremember)
		{
			Cookie::queue($this->currentUserPlaceholder, encryptString($user->id), time()+$nextExpiryTime);
			Cache::remember($this->currentUserPlaceholder.encryptString($user->id), $expiresAt, function() use($user)
            {
            	return $user;
            });
		} else
		{
			session([$this->currentUserPlaceholder => encryptString($user->id)]);
			Cache::remember($this->currentUserPlaceholder.encryptString($user->id), $expiresAt, function() use($user)
            {
            	return $user;
            });
		}
	}


	public function refreshUserCache($user)
	{
		$nextExpiryTime = 60*60*24*365;
		$expiresAt = Carbon::now()->addMinutes($nextExpiryTime);
		Cache::forget($this->currentUserPlaceholder.encryptString($user->id));
		Cache::remember($this->currentUserPlaceholder.encryptString($user->id), $expiresAt, function() use($user)
        {
        	return $user;
        });
	}

	public function expireCurrentUser()
	{
		$currentUser = $this->currentUser($this->currentUserPlaceholder);
		Cache::forget($this->currentUserPlaceholder.encryptString($currentUser->id));
		Cookie::queue(Cookie::forget($this->currentUserPlaceholder));
		session()->forget([$this->currentUserPlaceholder]);
		session()->flush();
	}

	public function forgotUser($userType='', $hashID)
	{
		Cache::forget($this->currentUserPlaceholder.$hashID);
	}

	public function login($user, $isremember=false)
	{
		$this->setCurrentUser($user, $isremember);
	}
}