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


Route::post('signup',[UserController::class,'signup']);
Route::post('login',[UserController::class,'login']);
//Route::post('confirm-code', 'confirmCode')->middleware('TwoFactor');


//Route::post('/confirm-code',[UserController::class,'confirmVerifyCode'])->name('confirmVerifyCode');
//Route::post('/resend-code',[UserController::class,'resendVerifyCode'])->name('resendVerifyCode')->middleware('throttle:3,1');

Route::group(['middleware'=>'auth:sanctum','ability:' . TokenAbility::ISSUE_ACCESS_TOKEN->value],function (){
    // get all info about user
    Route::get('get-profile',[UserController::class,'getProfile']);
    // update user and store image in Storge we need add token to gave info
    Route::post('update-profile',[UserController::class,'updateProfile']);
    Route::post('logout',[UserController::class,'logout']);
    Route::get('send-email',[EmailController::class,'send']);
    Route::get('/refresh-token', [UserController::class, 'refreshToken']);

});

require __DIR__.'/api_v1.php';
