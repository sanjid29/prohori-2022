<?php
/*
|--------------------------------------------------------------------------
| DefaultProject Auth routes
|--------------------------------------------------------------------------
|
| These routes are manually added instead of calling Auth::routes();
| This gives much flexibility to write these routes as necessary
| for the architectural implementation
|
*/

Route::get('login', 'Auth\LoginController@showLoginForm')->name('login');
Route::post('login', 'Auth\LoginController@login');
Route::post('logout', 'Auth\LoginController@logout')->name('logout');

// Registration Routes...
Route::get('register/{groupName?}', 'Auth\RegisterController@showRegistrationForm')->name('register');
Route::post('register/{groupName?}', 'Auth\RegisterController@register');

// Password Reset Routes...
Route::get('password/reset', 'Auth\ForgotPasswordController@showLinkRequestForm')->name('password.request');
Route::post('password/email', 'Auth\ForgotPasswordController@sendResetLinkEmail')->name('password.email');
Route::get('password/reset/{token}', 'Auth\ResetPasswordController@showResetForm')->name('password.reset');
Route::post('password/reset', 'Auth\ResetPasswordController@reset')->name('password.update');

// Password Confirmation Routes...
Route::get('password/confirm', 'Auth\ConfirmPasswordController@showConfirmForm')->name('password.confirm');
Route::post('password/confirm', 'Auth\ConfirmPasswordController@confirm');

// Email Verification Routes...
Route::get('email/verify', 'Auth\VerificationController@show')->name('verification.notice');
Route::get('email/verify/{id}/{hash}', 'Auth\VerificationController@verify')->name('verification.verify');
Route::post('email/resend', 'Auth\VerificationController@resend')->name('verification.resend');

// Log out
Route::get('logout', 'Auth\LoginController@logout')->name('get.logout');

/*
|--------------------------------------------------------------------------
| Additional project specific routes
|--------------------------------------------------------------------------
|
*/
// Tenant Registration Routes...
Route::get('register-tenant', 'Auth\RegisterTenantController@showRegistrationForm')->name('register.tenant');
Route::post('register-tenant', 'Auth\RegisterTenantController@register');
// Tenant Registration Routes...
// Route::get('reseller/register', 'Auth\RegisterResellerController@showRegistrationForm')->name('register.reseller');
// Route::post('reseller/register', 'Auth\RegisterResellerController@register');


