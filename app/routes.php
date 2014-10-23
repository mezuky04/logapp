<?php
/**
 * Routes for homepage
 */
Route::get('', 'HomeController@index');
Route::get('home', 'HomeController@showHomepage');


/**
 * Routes for login page
 */
Route::get('login', 'LoginController@showLoginPage');
Route::post('login', 'LoginController@processLogin');


/**
 * Routes for two factor auth verification page
 */
Route::get('verification-code', 'VerificationCodeController@index');
Route::post('verification-code', 'VerificationCodeController@processVerificationCode');


/**
 * Routes for logout page
 */
Route::get('logout', 'LoginController@logout');


/**
 * Routes for register page
 */
Route::get('register', 'RegisterController@index');
Route::post('register', 'RegisterController@processRegistration');


/**
 * Routes for recover page
 */
Route::get('recover', 'RecoverController@index');
Route::post('recover', 'RecoverController@recover');


/**
 * Routes for plans page
 */
Route::get('plans', 'PlansController@index');


/**
 * Routes for create-new-logger page
 */
Route::get('create-new-logger', 'LoggerController@showCreateNewLoggerPage');


/**
 * Routes for account settings page
 */
Route::get('settings', 'SettingsController@index');
// Add a group for settings actions
Route::group(array('prefix' => 'settings'), function() {
    // Edit email
    Route::post('editEmail', 'SettingsController@editEmail');
    // Edit phone number
    Route::post('editPhoneNumber', 'SettingsController@editPhoneNumber');
    // Edit password
    Route::post('editPassword', 'SettingsController@editPassword');
    // Edit two factor auth
    Route::post('editTwoFactorAuth', 'SettingsController@editTwoFactorAuth');
});


/**
 * Routes for contact page
 */
Route::get('contact', 'ContactController@showContactPage');
Route::post('contact', 'ContactController@sendContactMessage');


/**
 * Routes for get started page
 */
Route::get('get-started', 'GetStartedController@showGetStartedPage');


/**
 * Routes for applications page
 */
Route::get('applications', 'ApplicationsController@showApplicationsPage');
Route::post('applications', 'ApplicationsController@addNewApp');
Route::get('applications/{applicationId}', 'ApplicationsController@getAppDetails');


/**
 * Routes for application page
 */
Route::get('application/{applicationId}', 'ApplicationController@showApplicationPage');
Route::post('application/{applicationId}', 'ApplicationController@editApplication');


/**
 * Routes for documentation pages
 */
Route::group(array('prefix' => 'docs'), function() {
    // SDKs page
    Route::get('sdks', 'DocsController@showSDKsPage');
    // Logger API page
    // Error codes page
    Route::get('error-codes', 'DocsController@showErrorCodesPage');
});


/**
 * Routes for control panel
 */
Route::get('control-panel', 'ControlPanelController@index');

Route::group(array('prefix' => 'control-panel'), function() {
    // Control panel index page
    Route::get('/', 'ControlPanelController@index');
    // Users page
    Route::get('users', 'ControlPanelController@users');
    // Subscriptions page
    Route::get('subscriptions', 'ControlPanelController@subscriptions');
    // Settings page
    Route::get('settings', 'ControlPanelController@settings');
    // Statistics page
    Route::get('statistics', 'ControlPanelController@statistics');
});

/**
 * Routes to check if verification code is required
 */
Route::filter('isVerificationCodeRequired', function() {
    $twoFactorAuthVerificationCodesModel = new TwoFactorAuthVerificationCodesModel();
//    $isVerificationCodeRequired = Session::get('verificationCodeRequired');
    if ($twoFactorAuthVerificationCodesModel->checkIfVerificationCodeIsRequired()) {
        return Redirect::to('verification-code');
    }
});


/**
 * Routes for loggedAPI
 */
Route::post('loggerAPI/log', 'APIController@log');