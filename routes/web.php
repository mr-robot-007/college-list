<?php

use App\Http\Controllers\Admin\AdmissionInfoController;
use App\Http\Controllers\Admin\CourseController;
use App\Http\Controllers\Admin\IndexController;
use App\Http\Controllers\Admin\InstituteController;
use App\Http\Controllers\Admin\UsersController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/


$ADMIN_PREFIX = \Config::get('constants.ADMIN_PREFIX');
Route::prefix($ADMIN_PREFIX)->group(function() // 
{
	Route::get("/", [IndexController::class, "index"]);
	Route::get('/login', [IndexController::class, "index"])->name("admin.login");
	Route::post('/login', [IndexController::class, "login"])->name("admin.login.proceess");
	Route::get('/logout', [IndexController::class, "logout"])->name("admin.logout");
	


	Route::get('/forgot-password', [IndexController::class,"passwordRequest"])->middleware('guest')->name('password.request');
	Route::post('/forgot-password', [IndexController::class,"passwordEmail"])->middleware('guest')->name('password.email');
	Route::get('/reset-password/{token}',[IndexController::class,"passwordReset"])->name('password.reset');
	Route::post('/reset-password',[IndexController::class,"passwordUpdate"])->name('password.update');
});

Route::Group(["middleware"=> ["authadmin"]], function()
	{
		Route::get("/dashboard", [IndexController::class, "dashboard"])->name("admin.dashboard");
		Route::get('/myprofile', [UsersController::class, "profile"])->name('admin.profile');
		Route::post('/myprofile', [UsersController::class, "profile_update"])->name('admin.profile.update');

		Route::get("/users/filter", [UsersController::class, "filter"])->name("admin.users.filter");
		Route::get("/editpassword", [UsersController::class, "edit_password"])->name("users.edit.password");
		Route::post("/updatepassword", [UsersController::class, "update_password"])->name("users.update.password");
        Route::get('/users/{id}/toggleBlock',[UsersController::class,"toggle_block"])->name("admin.users.toggleBlock");
		Route::resource('/users', UsersController::class, [
		    'names' => [
		    	'index' => 'admin.users',
		        'create' => 'admin.user.new',
		        'store' => 'admin.user.store',
		        'show' => 'admin.user.detail',
		        'edit' => 'admin.user.edit',
		        'update' => 'admin.user.update',
		        'destroy' => 'admin.user.delete',
		    ]
		]);

		Route::get("/institutes/filter", [InstituteController::class, "filter"])->name("admin.institutes.filter");


		Route::resource('institutes', InstituteController::class,[
			'names'=> [
				'index' => 'admin.institutes',
				'create' => 'admin.institute.new',
		        'store' => 'admin.institute.store',
		        'show' => 'admin.institute.detail',
		        'edit' => 'admin.institute.edit',
		        'update' => 'admin.institute.update',
		        'destroy' => 'admin.institute.delete',
			]
		]);



		Route::get("/courses/filter", [CourseController::class, "filter"])->name("admin.courses.filter");
		Route::get("/courses/filterdashboard", [CourseController::class, "filterdashboard"])->name("admin.courses.filterdashboard");
		Route::get("/visits/filter", [CourseController::class, "filtervisits"])->name("admin.visits.filter");
		Route::get('/coursevisits',[CourseController::class,'coursevisits'])->name('admin.visits');
		Route::get('/downloadcsv',[CourseController::class,'download_csv'])->name('admin.courses.downloadcsv');
		Route::resource('courses', CourseController::class,[
			'names'=> [
				'index' => 'admin.courses',
				'create' => 'admin.course.new',
		        'store' => 'admin.course.store',
		        'show' => 'admin.course.detail',
		        'edit' => 'admin.course.edit',
		        'update' => 'admin.course.update',
		        'destroy' => 'admin.course.delete',
				]
			]);
		Route::get("/admissions/filter", [AdmissionInfoController::class, "filter"])->name("admin.admissions.filter");
		Route::get("/university/courses/{id}", [AdmissionInfoController::class, "get_university_courses"])->name("admin.university.courses");
		Route::resource('admissions', AdmissionInfoController::class,[
			'names'=> [
				'index' => 'admin.admissions',
				'create' => 'admin.admission.new',
		        'store' => 'admin.admission.store',
		        'show' => 'admin.admission.detail',
		        'edit' => 'admin.admission.edit',
		        'update' => 'admin.admission.update',
		        'destroy' => 'admin.admission.delete',
			]
		]);
    }
);