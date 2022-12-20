<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "Api" middleware group. Enjoy building your API!
|
*/

Route::group(['prefix' => '/auth'], function () {
    Route::post('register', [\App\Http\Controllers\api\v1\Auth\AuthController::class, 'register'])
        ->name('auth.register');
    Route::post('login', [\App\Http\Controllers\api\v1\Auth\AuthController::class, 'login'])
        ->name('auth.login');
});

Route::group(['middleware' => ['auth:sanctum']], function() {
    Route::get('/auth/user', function(\Illuminate\Http\Request $request) {
        return $request->user();
    })->name('auth.user');
});
