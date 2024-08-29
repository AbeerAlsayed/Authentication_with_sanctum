<?php

namespace App\Http\Services\Auth;
use App\Http\Requests\Auth\RegisterRequest;
use App\Models\User;
use App\Traits\ApiResponse;
use App\Traits\UploadedFile;
use Illuminate\Support\Facades\Hash;

class CreateUserService{
    use UploadedFile , ApiResponse;
    public function storeUser(RegisterRequest $request)
    {


        $profile_picture = $this->uploadFile($request, 'images', 'profile_picture');
        $certificate = $this->uploadFile($request, 'files', 'certificate');
            $user = User::create([

                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'phone' => $request->phone,
                'profile_picture' => $profile_picture,
                'certificate' => $certificate,
            ]);
            return $user;
    }
}
