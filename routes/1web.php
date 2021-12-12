<?php

use App\Mail\RegistrationSuccessfull;
use Illuminate\Mail\Markdown;


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

Route::get('/', function () {
    // $markdown = new markdown(view(), config('mail.markdown'));

    // return $markdown->render('RegistrationSuccessfull');
   
    return view('welcome');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');


//Admin  Routes...

Route::get('admin/dashboard', 'Admin\AdminController@index')->name('admin.dashboard');
Route::get('admin', 'Admin\LoginController@showLoginForm')->name('admin.login');
Route::post('admin', 'Admin\LoginController@login');
Route::post('admin/logout', 'Admin\LoginController@logout')->name('admin.logout');
//Route::get('admin/users', 'Admin\UserController@index')->name('admin.users');
//Route::get('admin/users', 'Admin\UserController@index')->name('admin.users');


Route::resource("admin/users","Admin\UserController");


Route::any("admin/user_search","Admin\UserController@user_search")->name("user_search");
Route::get("admin/users_search_result","Admin\UserController@index")->name("users_search_result");
Route::post("admin/set-gender","Admin\UserController@setGender")->name("set-gender");
Route::post("admin/users/edit","Admin\UserController@edit")->name("user.edit"); 
Route::get("admin/users_requests","Admin\UserController@userInterests")->name("users_requests"); 
Route::post("admin/users/set_status","Admin\UserController@setStatus"); 
Route::post("admin/users/set_request_status","Admin\UserController@setRequestStatus"); 
Route::post("admin/users/change-password","Admin\UserController@changePassword")->middleware('super_admin_role'); 
Route::get("admin/news","Admin\News@index")->name('admin.news'); 
Route::post("admin/add-news","Admin\News@addNews")->name('admin.add-news'); 
Route::get("admin/news-delete/{id}","Admin\News@deleteNews"); 
// Password Reset Routes...
        // Route::get('admin-password/reset', 'Admin\ForgotPasswordController@showLinkRequestForm')->name('admin.password.request');
        // Route::post('admin-password/email', 'Admin\ForgotPasswordController@sendResetLinkEmail')->name('admin.password.email');
        // Route::get('admin-password/reset/{token}', 'Admin\ResetPasswordController@showResetForm')->name('admin.password.reset');
        // Route::post('admin-password/reset', 'Admin\ResetPasswordController@reset');
        Route::get('activate', 'UserAccountActivation\ActivateAccount@activate');
        
        
        // privacy policy
        Route::view('privacy-policy', 'privacy-policy');
        
        // About US
        Route::get('about-us', function(){

            echo '<center><h3>About Us</h3></center>';
        });

        // Terms
        Route::get('terms-conditions', function(){

            echo '<center><h3>Our terms and conditions</h3></center>';
        });
        //support
        Route::get('support', function(){

            echo '<center><h3>Support</h3></center>';
        });

