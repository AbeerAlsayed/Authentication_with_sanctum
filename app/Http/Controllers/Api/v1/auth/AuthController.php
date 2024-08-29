<?php

namespace App\Http\Controllers\Api\v1\auth;

use App\Enums\TokenAbility;
use App\Events\UserEvent;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Http\Requests\Auth\UpdateRequest;
use App\Http\Services\Auth\CreateUserService;
use App\Http\Services\Auth\SendCodeToEmail;
use App\Http\Services\Auth\UpdateUserService;
use App\Models\User;
use App\Notifications\TwoFactorCode;
use App\Traits\ApiResponse;
use App\Traits\UploadedFile;
use App\Traits\UploadedFileStorage;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    use ApiResponse;
    public CreateUserService $createUser;
    public UpdateUserService $updateService;
    public SendCodeToEmail $emailCode;
    public function __construct(CreateUserService $createUser,UpdateUserService $updateService,SendCodeToEmail $emailCode){
        $this->createUser=$createUser;
        $this->updateService=$updateService;
        $this->emailCode=$emailCode;
    }

    public function signup(RegisterRequest $request){
        $user=$this->createUser->storeUser($request);
        $user= $this->emailCode->sendCode($request);
        $accessToken = $user->createToken('access_token', [TokenAbility::ACCESS_API->value], Carbon::now()->addMinutes(config('sanctum.ac_expiration')));
        $refreshToken = $user->createToken('refresh_token', [TokenAbility::ISSUE_ACCESS_TOKEN->value], Carbon::now()->addMinutes(config('sanctum.rt_expiration')));
        return $this->success(['token' => $accessToken->plainTextToken, 'refresh_token' => $refreshToken->plainTextToken,'verify_code'=>$user->verify_code],'User Created Successfully.', 200);
    }

    public function login(LoginRequest $request){
        if(!Auth::attempt($request->only(['email', 'password','phone']))){
            throw new AuthenticationException('Username or password is invalid.');
        }
        $user=$this->emailCode->sendCode($request);
        return $this->success(['two_factory_code'=>$user->verify_code] ,"Enter Two Factor From Email For Login Success", 200);
    }

    public function getProfile(Request $request){
        $user_id=$request->user()->id;
        $user=User::find($user_id);
        return $this->success($user,'User Profile',200);
    }

    public function updateProfile(UpdateRequest $request){
        $user=$this->updateService->updateUser($request);
        return $this->success($user,'update user',200);
    }

    public function logout(Request $request){
        $request->user()->currentAccessToken()->delete();
        return $this->success([],'Logout Successful',200);
    }

    public function refreshToken(Request $request){
        $accessToken = $request->user()->createToken('access_token', [TokenAbility::ACCESS_API->value], Carbon::now()->addMinutes(config('sanctum.ac_expiration')));
        return $this->success($accessToken->plainTextToken,"Token generate",200);
    }

    public function confirmCode(Request $request){
        $user=auth()->user();
        $inputCode = $request->input('verify_code'); // Get the input code
        if ($inputCode === $user->verify_code) {
            $user->resetTwoFactorCode();
            Auth::guard('web')->login($user);
            $token=$user->createToken('postman')->plainTextToken;
            $user->token=$token;
            return $this->success($user ,"User Logged In Successfully", 200);
        }
        return $this->error('the two factor is error',401);
    }


    public function resendCode(Request $request){
        $user = User::where('email', $request->email)->first();
//        dd($user->verify_code);
        if($user->verify_code) {
            $user=$this->emailCode->sendCode($request);
            return $this->success(['verify_code'=>$user->verify_code] ,'the verification Email resend Successfully.', 200);
        }
        return $this->error('It seems that your account is already verified.',400);
    }
}
