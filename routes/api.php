<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::prefix("auth")->group(function () {
    Route::post("login",           "AuthController@login");
    Route::post("password_forgot", "AuthController@requestPasswordReset");
    Route::post("password_reset",  "AuthController@resetPassword");
});

Route::middleware("auth:api")->group(function () {
    Route::prefix("filesystem")->group(function () {
        Route::post("/register",   "FilesystemController@register");
        Route::post("/unregister", "FilesystemController@unregister");
    });
});

