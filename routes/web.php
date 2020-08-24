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
Route::group(['middleware' => ['cors']], function () {
    Route::get('{uuid}/widget', 'WidgetController@show');
    Route::get('{uuid}/default', 'WidgetController@show');
    Route::post('widget/data', 'WidgetDataController@store');
});

Route::get("/test_mail", function(Request $r){
    $destinatario = "oscarescalando@gmail.com";
    // Armar correo y pasarle datos para el constructor
    $correo = new \App\Mail\TestMail();
    # ¡Enviarlo!
    \Mail::to($destinatario)->send($correo);
});
