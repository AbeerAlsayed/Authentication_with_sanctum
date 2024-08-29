<?php

namespace App\Traits;

//use http\Env\Request;

use Illuminate\Http\Request;

trait UploadedFile
{
    public function uploadFile(Request $request,$folderName,$attr,$disk = 'public'): bool|string
    {
            $file_name=time().'.'.$request->file($attr)->getClientOriginalName();
            $path=$request->file($attr)->storeAs($folderName,$file_name,$disk);
            return $path;
    }
}
