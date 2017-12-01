<?php

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
    return view('welcome');
});

Route::get('/test/', 'TestController@index');


if (!isset($_SERVER['argv'])) {
    $act = explode('?', $_SERVER['REQUEST_URI'])[0]; //请求

    if ($act != '/') {
        $method = strtolower($_SERVER['REQUEST_METHOD']); //方法
        $path = explode('/', trim($act, '/'));
        if(count($path)==3){
            Route::$method($act, ucfirst($path[0]).'\\' . ucfirst($path[1]) . 'Controller@' . $path[2]);
        }

    }
}
