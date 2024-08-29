<?php

namespace App\Http\Services\Auth;
use App\Http\Requests\Auth\UpdateRequest;
use App\Models\User;
use App\Traits\UploadedFileStorage;
use Illuminate\Support\Facades\Hash;

class UpdateUserService{
    use UploadedFileStorage;
    public function updateUser(UpdateRequest $request){
        $user=User::find($request->user()->id);
        $profile_picture=$this->uploadFileStorage($request,'images','profile_picture');
        $certificate=$this->uploadFileStorage($request,'files','certificate');
        $user = User::update([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'c_password' => Hash::make($request->c_password),
            'phone' => $request->phone,
            'profile_picture' =>$profile_picture,
            'certificate' => $certificate,
        ]);
        return $user;
    }
}
