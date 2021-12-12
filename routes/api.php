<?php

use App\Http\Controllers\Api\V1\ApiController;

Route::post('/sendotp','Api\UserApiController@sendOtp');
Route::post('/verifyotp','Api\UserApiController@verifyOtp');

Route::group(['middleware' => 'api',], function ($router) {
    Route::post('login', 'Api\AuthController@login');
    Route::post('logout', 'Api\AuthController@logout');
    Route::post('register', 'Api\AuthController@register');
    Route::any('get_states', 'Api\StateDistrict@get_states');
    Route::any('get_caste', 'Api\Caste@get_caste');
    Route::any('get_marital_status', 'Api\MaritalStatus@get_marital_status');
    Route::any('get_district', 'Api\StateDistrict@get_district');
    Route::post('forget-password', 'Api\AuthController@forgetPassword');
    Route::any('search', 'Api\UserApiController@search');
    Route::any('getAllPackages', 'Api\UserApiController@getAllPackages');
    Route::any('get_my_subscription', 'Api\UserApiController@get_my_subscription');
    Route::any('subscription', 'Api\UserApiController@subscription');
    Route::any('get_my_subscription_history', 'Api\UserApiController@get_my_subscription_history');
    Route::group(['middleware' => 'auth:api'], function ($router) {
        Route::post('refresh', 'Api\AuthController@refresh');
        Route::post('me', 'Api\AuthController@me');
        Route::any('users', 'Api\UserApiController@users');
        Route::post('update', 'Api\UserApiController@update');
        Route::post('just-join', 'Api\UserApiController@justJoin');
        Route::post('images', 'Api\UserApiController@userImages');
        Route::post('set-avatar', 'Api\UserApiController@setAvatar');
        Route::post('set-shortlisted', 'Api\UserApiController@setShortlisted');
        Route::post('delete-image', 'Api\UserApiController@deleteImage');
        Route::post('shortlisted', 'Api\UserApiController@shortListed');
        Route::post('remove-shortlisted', 'Api\UserApiController@removeShortListed');
        Route::post('get-gallary', 'Api\UserApiController@getGallary');
        Route::post('set-visitor', 'Api\UserApiController@setVisitor');
        Route::post('get-visitors', 'Api\UserApiController@getVisitors');
        Route::any('get-events', 'Api\EventsController@getEvents');
        Route::post('send-interest', 'Api\InterestsController@sendInterest');
        Route::post('cancel-interest', 'Api\InterestsController@cancelInterest');
        Route::post('get-interest', 'Api\InterestsController@getInterest');
        Route::post('update-interest', 'Api\InterestsController@updateInterestStatus');
        Route::post('password-reset', 'Api\AuthController@passwordReset');
        Route::post('get-message-history', 'Api\ChatController@getMessageHistory');
        Route::post('set-message', 'Api\ChatController@setMessage');
        Route::post('get-message', 'Api\ChatController@getMessage');
    });
    
});
