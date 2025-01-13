<?php

namespace App\Traits;

trait Notifications
{
	public function notifyuser($key,$endDate = null,$endDateWithGrace = null)
	{
		$allNotifications = [
			"SERVER_BUSY"=> "Server seems busy right now. Please try again later",
			"SERVER_ERROR"=> "Something happend Wrong! Please contact support team and try again later.",
			"UNAUTHORIZE_ACCESS"=> "Please login to access the details.",
			"NON_PERMISSIBLE_ACCESS"=> "You are not authorize to access the details.",
			"INVALID_REQUEST"=> "Invalid Request.",
			"CONTACT_ADMIN"=> "Facing some issue to retrieve your information. Please contact Server admin to recover your Password.",
			"FILE_UPLOADED"=> "File uploaded successfully!",
			"FILE_UPDATED"=> "File updated successfully!",
			"FILE_REMOVED"=> "File removed successfully!",
			
			"LOGIN_FAILED"=> "Invalid login credential!",

			"SETTINGS_SAVED"=> "Settings saved successfully.",
			"CANNOT_SAVE_SETTING"=> "Can not save settings right now. Please try again later.",

			"PROFILE_UPDATE"=> "Profile successfully updated!",

			"TODO_LIST_ADDED"=> "Todo list successfully added!",
			"TODO_LIST_UPDATED"=> "Todo list successfully updated!",
			"TODO_LIST_REMOVED"=> "Todo list successfully removed!",

			"EVENT_LIST_ADDED"=> "Event list successfully added!",
			"EVENT_LIST_UPDATED"=> "Event list successfully updated!",
			"EVENT_LIST_REMOVED"=> "Event list successfully removed!",

			"COMPANY_ADDED"=> "Company successfully added!",
			"COMPANY_UPDATED"=> "Company successfully updated!",
			"COMPANY_REMOVED"=> "Company successfully removed!",

			"USER_ADDED"=> "User successfully added!",
			"USER_UPDATED"=> "User successfully updated!",
			"USER_REMOVED"=> "User successfully removed!",
			"PASSWORD_UPDATED"=>"Password successfully updated!",

			"INSTITUTE_ADDED"=>	"Institute succcessfully added!",
			"INSTITUTE_UPDATED"=> "Institute succcessfully updated!",
			"INSTITUTE_DELETED"=> "Institute succcessfully deleted!",

			"BRANCH_ADDED"=>	"Branch succcessfully added!",
			"BRANCH_UPDATED"=> "Branch succcessfully updated!",
			"BRANCH_DELETED"=> "Branch succcessfully deleted!",

			"COURSE_ADDED"=> "Course succcessfully added!",
			"COURSE_UPDATED"=> "Course succcessfully updated!",
			"COURSE_DELETED"=> "Course succcessfully deleted!",

			"PERMISSION_ADDED"=> "Permission succcessfully added!",
			"PERMISSION_UPDATED"=> "Permission succcessfully updated!",
			"PERMISSION_DELETED"=> "Permission succcessfully deleted!",

			"ROLE_ADDED"=> "Role succcessfully added!",
			"ROLE_UPDATED"=> "Role succcessfully updated!",
			"ROLE_DELETED"=> "Role succcessfully deleted!",

			"PLAN_ADDED"=> "Plan succcessfully added!",
			"PLAN_UPDATED"=> "Plan succcessfully updated!",
			"PLAN_DELETED"=> "Plan succcessfully deleted!",

			"BATCH_ADDED"=> "Batch succcessfully added!",
			"BATCH_UPDATED"=> "Batch succcessfully updated!",
			"BATCH_DELETED"=> "Batch succcessfully deleted!",

			"QUIZ_ADDED"=> "Quiz succcessfully added!",
			"QUIZ_UPDATED"=> "Quiz succcessfully updated!",
			"QUIZ_DELETED"=> "Quiz succcessfully deleted!",

			"USER_ROLES_UPDATED"=> "User Roles succcessfully updated!",
			"ROLE_PERMISSIONS_UPDATED"=> "Role Permissions succcessfully updated!",

			
			"USERSUBSCRIPTION_ADDED"=> "User Subscription succcessfully added!",
			"USERSUBSCRIPTION_DELETED"=> "User Subscription succcessfully deleted!",
			"USERSUBSCRIPTION_RENEWED"=> "User Subscription succcessfully renewed!",

			"USER_SUBSCRIPTION_EXPIRING_SOON" => "Your Subscription is expiring on ". $endDate. ". To resume your service please renew before ".$endDateWithGrace." !",
			"SUBSCRIPTION_EXPIRED" => "Subscription has been expired! Please renew it.",
			"USER_SUBSCRIPTION_EXPIRED" => "Failed to login. Please contact the administrator.",
			"USER_SUBSCRIPTION_EXPIRED_FOR_USER" => "Failed to login. Please contact the administrator.",

			"SETTINGS_UPDATED"=> "Settings succcessfully updated!",

			"QUESTION_CREATED" => " Question  created successfully",

			"EMAILS_SENT_SUCCESSFULLY" => "Mail has been sent to all students.",

			"EMAIL_ADDED"=>"Email successfully added!",
			"EMAIL_UPDATED"=>"Email successfully updated!",
			"EMAIL_DELETED"=>"Email successfully deleted!",

			"ADMISSION_ADDED"=>"Admission Info successfully added!",
			"ADMISSION_UPDATED"=>"Admission Info successfully updated!",
			"ADMISSION_DELETED"=>"Admission Info successfully deleted!",



		];

		return isset($allNotifications[$key]) ? $allNotifications[$key] : '';
	}
}