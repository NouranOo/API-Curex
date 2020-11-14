    <?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
 */
Route::get('/updateapp', function () {
    exec('composer dump-autoload');
    echo 'dump-autoload complete';
});

$router->get('/', function () use ($router) {
    return 'Hello in Curex Apies';
});

/**
 * UserAuth
 */
 
$router->group(['prefix' => 'Api/User', 'middleware' => ['cors2', 'cors']], function () use ($router) {
    $router->post('/Register', 'UserController@Register');
    $router->post('/Login', 'UserController@Login');
    $router->post('/ForgetPasswordSendRecoveryCode', 'UserController@ForgetPasswordSendRecoveryCode');
    $router->post('/CheckRecoveryCode' , 'UserController@CheckRecoveryCode');
    $router->post('/SetNewPassword' , 'UserController@SetNewPassword');
 
});
$router->group(['prefix' => 'Api/User', 'middleware' => ['cors2', 'cors', 'UserAuth']], function () use ($router) {
    $router->post('/getHome', 'UserController@getHome');
    $router->post('/scanMyQr', 'UserController@scanMyQr');
    $router->post('/showMyPin', 'UserController@showMyPin');
    $router->post('/showAllPres', 'UserController@showAllPres');
    $router->post('/getAllNotifications', 'UserController@getAllNotifications');
    $router->post('/makeNotificationRead', 'UserController@makeNotificationRead');
    $router->post('/unReadNotifcation', 'UserController@unReadNotifcation');
    $router->post('/updatePhoto', 'UserController@updatePhoto');
    $router->post('/updateMobile', 'UserController@updateMobile');
    $router->post('/updatePassword', 'UserController@updatePassword');
    $router->post('/updateEmail', 'UserController@updateEmail');
    $router->post('/updateGender', 'UserController@updateGender');
    $router->post('/updateBirthday', 'UserController@updateBirthday');
    $router->post('/updateAddress', 'UserController@updateAddress');
    $router->post('/DeleteMyAccount', 'UserController@DeleteMyAccount');
    $router->post('/contactUs', 'UserController@contactUs');
 

    
});
