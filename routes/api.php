<?php

use Dingo\Api\Routing\Router;

$api_version = 'v1';

/**
 * NAMESPACE
 * ---
 * Adds namespace with current version to route destination
 * @param string $r
 * @author MS
 * @return string
 */
$namespace = function ($r) use ($api_version) {
    return '\\App\\Api\\' . strtoupper($api_version) . '\\Controllers\\' . $r;
};

/** @var Router $api */
$api = app(Router::class);

$api->version($api_version, function (Router $api) use ($namespace) {

    /***************************************************************************
     * JWT AUTHENTICATION ENDPOINTS
     */

    // USER ACCOUNT ENDPOINTS
    $api->group(['prefix' => 'auth'], function(Router $api) use ($namespace) {
        $api->post('signup', $namespace('SignUpController@signUp'));
        $api->post('login', $namespace('LoginController@login'));
        $api->post('recovery', $namespace('ForgotPasswordController@sendResetEmail'));
        $api->post('reset', $namespace('ResetPasswordController@resetPassword'));
    });


    /***************************************************************************
     * USER ONLY ENDPOINTS: REQUIRED JWT AUTH & MODIFIER KEY
     */

    // JWT AUTH MIDDLEWARE GROUP
    $api->group(['middleware' => 'jwt.auth'], function(Router $api) use ($namespace) {

        // USER MUST BE LOGGED IN...

    });


    /***************************************************************************
     * GUEST & USER ENDPOINTS: REQUIRED MODIFIER KEY, OPTIONAL JWT AUTH
     */

    // IMAGE ARCHIVE ENDPOINTS
    $api->resource('images', $namespace('ImageArchiveController'), ['only' => [
        'index', 'store', 'destroy'
    ]]);
    $api->get('images/delete-all', $namespace('ImageArchiveController@deleteAll'));
    $api->get('images/download', $namespace('ImageArchiveController@downloadAllImages'));
    $api->get('images/download/{filename}', $namespace('ImageArchiveController@downloadImage'));


    /***************************************************************************
     * ANYBODY ACCESS
     */

    // GET GUEST ACCESS MODIFIER TOKEN
    $api->get('modifier', function() {
        $session = new \stdClass();
        $session->modifier = session()->getId();
        return response()->json();
    });
});
