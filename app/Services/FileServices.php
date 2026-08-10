<?php

namespace App\Services;

class FileServices {

    public static function uploadFile($dataFile){
        $file = $dataFile['file'];
        $imageExtension = $file->getClientOriginalExtension();
        $destinationPath = $dataFile['path'];
        $modul = $dataFile['modul'];
        $fileName = time().'.'.$imageExtension;

        if(!file_exists(public_path('storage'))){
            mkdir(public_path('storage'), 0755);
        }
        if(!file_exists($destinationPath )){
            mkdir($destinationPath, 0755);
        }

        $file->move($destinationPath,$fileName);

        chmod($destinationPath.$fileName, 0755);
        // dd($fileName);
        return ['status'=>true, 'name'=>$fileName];
    }
}
