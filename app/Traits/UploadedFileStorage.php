<?php

namespace App\Traits;

//use http\Env\Request;

use Illuminate\Http\Request;

trait UploadedFileStorage
{
    public function uploadFileStorage(Request $request,$folderName,$attr,$disk = 'public'){
        $file_name=time().'.'.$request->file($attr)->getClientOriginalName();
        $path= $request->file($attr)->storeAs($folderName,$file_name,$disk);
        return "/storage/".$path;
    }
}
