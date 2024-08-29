<?php

use App\Enums\TokenAbility;
use App\Http\Controllers\Api\EmailController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\v1\auth\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/
Route::controller(AuthController::class)
    ->prefix('/v1/auth')
    ->group(function() {
        Route::post('/signup',  'signup');
        Route::post('login',  'login');
        Route::group(['middleware' => 'auth:sanctum', 'ability:' . TokenAbility::ISSUE_ACCESS_TOKEN->value], function () {
            // get all info about user
            Route::get('get-profile',  'getProfile');
            // update user and store image in Storge we need add token to gave info
            Route::post('update-profile', 'updateProfile');
            Route::post('logout', 'logout');
            Route::post('confirmCode', 'confirmCode');
            Route::post('resendCode', 'resendCode');
//            Route::get('send-email',  'send');
            Route::get('/refresh-token', 'refreshToken');

            Route::post('confirm-code', 'confirmCode');
        });
    });
