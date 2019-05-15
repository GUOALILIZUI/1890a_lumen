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

$router->get('/', function () use ($router) {
    return $router->app->version();
});
// $app->post('info', [
//     'as' => 'info', 'info' => 'Controller@info'
// ]);
$router->post('info','OneController@info' );
$router->post('int','OneController@int' );
$router->post('rsaTest','OneController@rsaTest' );
$router->post('sign','OneController@sign' );


//5.13周考
//$router->post('regInfo','RegController@regInfo' );
$router->get('loginIndex','RegController@loginIndex' );
//$router->get('getLoginUserToken','RegController@getLoginUserToken' );
//$router->post('logInfo','RegController@logInfo' );
//$router->get('b','RegController@b' );


//5.14

$router->get('center', ['middleware' => 'token', function () {
    'CenterController@center';
}]);

//$router->get('center','CenterController@center');
$router->post('regInfo','RegController@regInfo');
$router->post('logInfo','RegController@logInfo');


