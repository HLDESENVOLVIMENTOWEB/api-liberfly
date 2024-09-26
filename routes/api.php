<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\UserController;
use App\Http\Controllers\API\AuthController;;

Route::post('login', [AuthController::class, 'login']);
Route::post('register', [AuthController::class, 'register']);

Route::get('docs', '\L5Swagger\Http\Controllers\SwaggerController@api')->name('l5swagger.api');

Route::group(['middleware' => ['auth.jwt']], function () {
    Route::resource('users', UserController::class);
});

