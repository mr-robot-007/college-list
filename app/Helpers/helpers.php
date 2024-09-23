<?php
use App\Models\Quiz;
use App\Models\QuizAssignedTo;
use Carbon\Carbon;
//use App\Libraries\Auth;
//use App\Traits\Access;

function getRequestAttributes($attribute)
{
	if($attribute) return request()->attributes->get($attribute);
	else return null;
}

function getConfig($type, $name)
{
	if($type!='' && $name!='') return \Config::get($type.'.'.$name);
	else null;
}

function slug($string)
{
	$slug = strtolower(preg_replace('/[^A-Za-z0-9-]+/', '-', $string));
	return $slug;
}

function encryptString($string)
{
	// you may change these values to your own
	$secret_key = 'my_simple_secret_key';
	$secret_iv = 'my_simple_secret_iv';

	$output = false;
	$encrypt_method = "AES-256-CBC";
	$key = hash('sha256', $secret_key);
	$iv = substr(hash('sha256', $secret_iv), 0, 16);
	return base64_encode( openssl_encrypt( $string, $encrypt_method, $key, 0, $iv ) );
}

function decryptString($string)
{
	// you may change these values to your own
	$secret_key = 'my_simple_secret_key';
	$secret_iv = 'my_simple_secret_iv';

	$output = false;
	$encrypt_method = "AES-256-CBC";
	$key = hash('sha256', $secret_key);
	$iv = substr(hash('sha256', $secret_iv), 0, 16);
	return openssl_decrypt( base64_decode( $string ), $encrypt_method, $key, 0, $iv );
}

function displayString($string, $type='N', $inParagraph='')
{
	switch($type)
	{
		case 'UP':
			$string = strtoupper($string);
		break;
		case 'L':
			$string = strtolower($string);
		break;
		case 'UCF':
			$string = ucfirst(strtolower($string));
		break;
		case 'UCW':
			$string = ucwords(displayString(str_replace(",", ", ", $string), 'L'));
		break;
	}

	if($inParagraph!='' && $inParagraph=='array') $string = explode(PHP_EOL, $string);
	if($inParagraph!='' && $inParagraph=='string') $string = nl2br($string);

	return $string;
}

function displayNumber($number, $decimal='2', $round='', $withoutcomma=false)
{
	if($decimal>0) $format = number_format($number, $decimal);
	else  $format = number_format($number);
	if($withoutcomma)
	{
		return str_replace(",", "", parsePriceFormat($format, $round, $decimal));
	}
	return parsePriceFormat($format, $round, $decimal);
}

function truncateText($text, $limit, $postfix='...')
{
	if(str_word_count($text, 0) > $limit)
	{
		$words = str_word_count($text, 2);
		$pos = array_keys($words);
		$text = substr($text, 0, $pos[$limit]) . $postfix;
    }
    return $text;
}

function parsePriceFormat($number, $round, $decimal='')
{
	switch($round)
	{
		case 'R':
			return round($number, $decimal);
		break;
		case 'C':
			return ceil($number);
		break;
		case 'F':
			return floor($number);
		break;
		default:
			return $number;
		break;
	}
}

function parseDate($year, $month, $day)
{
	$date = date("Y-m-d", strtotime($year.'-'.$month.'-'.$day));

	return $date;
}

function fDate($dateTime, $format='M d, Y')
{
	return date($format, strtotime($dateTime));
}

function dateDifference($fdate, $tdate)
{
	$days = '';
	if($fdate!=='' && $tdate!=='')
	{
		$datetime1 = new DateTime($fdate);
		$datetime2 = new DateTime($tdate);
		$interval = $datetime1->diff($datetime2);
		// $days = $interval->format('%a')+1;

		$allEndInEndays = $interval->format('%a %H:%i:%s');
        $endInDays = $interval->format('%a');
        $endInHours = $interval->format('%H');
        $endInMins = $interval->format('%i');
        $endInSecs = $interval->format('%s');
        if($endInDays>1) { $endInDays = $endInDays.' Days '; } else if($endInDays==1) { $endInDays = $endInDays.' Day '; } else { $endInDays = ''; }
        if($endInDays<1 && $endInHours>1) { $endInHours = $endInHours.' Hours '; } else if($endInHours==1) { $endInHours = $endInHours.' Hour '; } else { $endInHours = ''; }
        if($endInDays<1 && $endInMins>1) { $endInMins = $endInMins.' Minutes '; } else if($endInMins==1) { $endInMins = $endInMins.' Minute '; } else { $endInMins = ''; }
        if($endInDays<1 && $endInSecs>1) { $endInSecs = $endInSecs.' Seconds '; } else if($endInSecs==1) { $endInSecs = $endInSecs.' Second '; } else { $endInSecs = ''; }

        $days = $endInDays.$endInHours.$endInMins.$endInSecs;
	}

	return $days;
}

function encryptMulti($array, $field, $returnType='object')
{
	$result = json_decode(json_encode($array),true);
	array_walk_recursive($result, function(&$value, $key, $field)
	{
		//dd($value . ' <> ' . $key . ' <> ' . $field);
		if(is_array($field) && count($field)>0 && !is_numeric($key) && in_array($key, $field) && $value!='')
		{
			$value = encryptString($value);
			//return $value;
		} else if($field==$key && $value!='')
	    {
	    	$value = encryptString($value);
	    	//return $value;
	    } else {
	    	$value;
	    	//return $value;
	    }
	}, $field);

	if(is_object($array) && $returnType=='object')
	{
		return json_decode(json_encode($result));
	}
	
	return $result;
}

function getUploadPath($type, $display=false, $filename='')
{
	$publicPath = '/uploads/';
	
	if($type!='') $PATH_TYPE = getConfig('constants', $type);
	else $PATH_TYPE = '';
	
	$publicFilePath = $publicPath.$PATH_TYPE.'/';

	if(!$display) $publicFilePath = public_path( $publicFilePath );
	
	if($filename!='') $publicFilePath = $publicFilePath.$filename; 
	return $publicFilePath;
}

function is_admin()
{
	$currentUser = getRequestAttributes('currentUser');
	if(isset($currentUser->type) && ($currentUser->type=='Admin' || $currentUser->type=='AltAdmin'))
	{
		return true;
	}
	return false;
}

function is_customer()
{
	$currentUser = getRequestAttributes('currentUser');
	if(isset($currentUser->type) && $currentUser->type=='Customer')
	{
		return true;
	}

	return false;
}
function is_student()
{
	$currentUser = getRequestAttributes('currentUser');
	if(isset($currentUser->type) && $currentUser->type=='Student')
	{
		return true;
	}

	return false;
}



function hasPermission($permissionSlug = '')
{
    // Resolve the current user using the Auth class
    $user = getRequestAttributes('currentUser');
    
    // By default, set isAllowed to false
    $isAllowed = false;
    
    // Check if the user is admin or superadmin
    if ($user->type == 'Admin' || $user->type == 'Altadmin' || $user->type == 'Customer') {
        $isAllowed = true;
    }
    
    
    // Check if user has a permission list and the permission slug exists in it
    if (!$isAllowed && $permissionSlug != '' && isset($user->permissionlist) && in_array($permissionSlug,$user->permissionlist)) {
        $isAllowed = true;
    }
    
    return $isAllowed;
}

function unauthorizedRedirect($redirectUrl = ''){
	if($redirectUrl!=''){
		redirect($redirectUrl)->send();
	}
	else {
		redirect(route('admin.dashboard'))->send();
	}
}

function getReplacedTemplate($slug, $replacements,$template)
{
	// $template = Email::where('slug', $slug)->first()

	$content = $template->content;

	foreach ($replacements as $key => $value) {
		$content = str_replace('{{' . $key . '}}', $value, $content);
	}

	return $content;
}

