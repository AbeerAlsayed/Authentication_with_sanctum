<?php

namespace App\Http\Services\Auth;
use App\Enums\TokenAbility;
use App\Events\VerificationCodeEvent;
use App\Http\Requests\Auth\RegisterRequest;
use App\Models\User;
use App\Traits\ApiResponse;
use App\Traits\UploadedFile;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;

class SendCodeToEmail{
    use  ApiResponse;
    public function sendCode(Request $request)
    {
        $user = User::where('email', $request->email)->first();
        if (is_null($user)) {
            return $this->error('Unauthenticated user', 401);
        }
        $code=$user->generateCode();
        $user->verify_code=$code;
        Cache::remember(request()->ip(), 60*2, function () use ($code,$user) {
            return [
                'email'=>$user->email,
                'verify_code'=>$code,
            ];
        });
        event(new VerificationCodeEvent($user, $code));
        return $user;
    }
}
