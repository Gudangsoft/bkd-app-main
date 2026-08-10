<?php

namespace App\Services;

use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Exception;

class ImageServices {
    public static function Crop($dataImage){

        $image            = $dataImage['file'];
        $settingSize      = $dataImage['setting'];
        $destinationPath  = $dataImage['path'];
        $imageName        = time().'.'.$image->getClientOriginalExtension();
        // dd($imageName);
        self::cekFolder2($destinationPath);


        // $path_big       = $destinationPath.'big';
        // $path_mid       = $destinationPath.'mid';
        // $path_thumb     = $destinationPath.'thumb';

        $path_big       = $destinationPath.'big';

        $path_mid       = $destinationPath.'mid';

        $path_thumb     = $destinationPath.'thumb';

        // self::cekFolder2($path_big);
        // self::cekFolder2($path_mid);
        // self::cekFolder2($path_thumb);

        $width_16_9     = ceil($dataImage['data']['skala169']['width']);
        $height_16_9    = ceil($dataImage['data']['skala169']['height']);
        $x_16_9         = ceil($dataImage['data']['skala169']['x']);
        $y_16_9         = ceil($dataImage['data']['skala169']['y']);

        $width_4_3      = ceil($dataImage['data']['skala43']['width']);
        $height_4_3     = ceil($dataImage['data']['skala43']['height']);
        $x_4_3          = ceil($dataImage['data']['skala43']['x']);
        $y_4_3          = ceil($dataImage['data']['skala43']['y']);

        $width_1_1      = ceil($dataImage['data']['skala11']['width']);
        $height_1_1     = ceil($dataImage['data']['skala11']['height']);
        $x_1_1          = ceil($dataImage['data']['skala11']['x']);
        $y_1_1          = ceil($dataImage['data']['skala11']['y']);

        try{
            $img = Image::make($image->getRealPath());
            $img->crop($width_16_9, $height_16_9, $x_16_9, $y_16_9)->save($path_big.'/'.$imageName)->destroy();

            $img = Image::make($image->getRealPath());
            $img->crop($width_4_3, $height_4_3, $x_4_3, $y_4_3)->save($path_mid.'/'.$imageName)->destroy();

            $img = Image::make($image->getRealPath());
            $img->crop($width_1_1, $height_1_1, $x_1_1, $y_1_1)->save($path_thumb.'/'.$imageName)->destroy();

            Image::make($path_big.'/'.$imageName)->resize($settingSize['mid_width'], null, function ($constraint) { $constraint->aspectRatio(); })->save($path_big.'/'.$imageName)->destroy();

            Image::make($path_mid.'/'.$imageName)->resize($settingSize['mid_width'], null, function ($constraint) { $constraint->aspectRatio(); })->save($path_mid.'/'.$imageName)->destroy();

            Image::make($path_thumb.'/'.$imageName)->resize($settingSize['thumb_width'], null, function ($constraint) { $constraint->aspectRatio(); })->save($path_thumb.'/'.$imageName)->destroy();

            return ['status'=>true,'name'=>$imageName];
        }catch(Exception $e){
            dd($e->getMessage());
            return ['status'=>$e->getMessage(),'name'=>''];
        }
    }

    public static function uploadImage($dataFile){
        $file = $dataFile['file'];
        $imageExtension = $file->getClientOriginalExtension();
        $destinationPath = $dataFile['path'];
        $modul = $dataFile['modul'];

        $fileName = time().'.'.$imageExtension;
        $file->move($destinationPath,$fileName);

        return ['status'=>true,'name'=>$fileName];
    }

    public static function uploadArchive($dataFile){
        $file = $dataFile['file'];
        $imageExtension = $file->getClientOriginalExtension();
        $destinationPath = $dataFile['path'];
        $modul = $dataFile['modul'];
        $fileName = time().'.'.$imageExtension;

        self::cekFolderArchive($destinationPath);
        $file->move($destinationPath,$fileName);

        chmod($destinationPath.$fileName, 0755);

        return ['status'=>true,'namaFile'=>$fileName];
    }
    public static function imageDimensi($dataImage){

        $image = $dataImage['file'];
        $imageExtension = $image->getClientOriginalExtension();
        $destinationPath = $dataImage['path'];
        $pathBig = $destinationPath.'big';
        $pathMid = $destinationPath.'mid';
        $pathThumb = $destinationPath.'thumb';
        $settingSize = $dataImage['setting'];
        $modul = $dataImage['modul'];
        $imageName = time().'.'.$image->getClientOriginalExtension();

        $watermark = $dataImage['watermark']['status'];
        $txt_watermark = $dataImage['watermark']['text'];
        $font_watermark = $dataImage['watermark']['font'];
        //echo $dataImage['watermark']['font'];

        self::cekFolder2($destinationPath);

        $img = Image::make($image->getRealPath());

        try{
            $img->resize($settingSize['ori_width'], $settingSize['ori_height'], function ($constraint) {
                $constraint->aspectRatio();
            })->save($pathBig.'/'.$imageName);

            $img->resize($settingSize['mid_width'], $settingSize['mid_height'], function ($constraint) {
                    $constraint->aspectRatio();
                })->save($pathMid.'/'.$imageName);

            $img->resize($settingSize['thumb_width'], $settingSize['thumb_height'], function ($constraint) {
                    $constraint->aspectRatio();
                })->save($pathThumb.'/'.$imageName);

            /*if($watermark == 1){
                $img_big = Image::make($pathBig.'/'.$imageName);
                //$img_big->text('The quick brown fox jumps over the lazy dog.');
                $img_big->text($txt_watermark, 0, 0, function($font) {
                    //$font->file('fonts/glyphicons-halflings-regular.ttf');
                    $font->size('40');
                    $font->color('#ffffff');
                    $font->align('left');
                    $font->valign('bottom');
                    $font->angle(45);
                });
                $img_big->destroy();

                $img_mid = Image::make($pathMid.'/'.$imageName);
                $img_mid->text($txt_watermark, 0, 0, function($font) {
                    //$font->file('fonts/glyphicons-halflings-regular.ttf');
                    $font->size('40');
                    $font->color('#ffffff');
                    $font->align('left');
                    $font->valign('bottom');
                    $font->angle(45);
                });
                $img_mid->destroy();

                $img_thumb = Image::make($pathThumb.'/'.$imageName);
                $img_thumb->text($txt_watermark, 0, 0, function($font) {
                    //$font->file('fonts/glyphicons-halflings-regular.ttf');
                    $font->size('40');
                    $font->color('#ffffff');
                    $font->align('left');
                    $font->valign('bottom');
                    $font->angle(45);
                });
                $img_thumb->destroy();
            }*/
            $img->destroy();

            chmod($pathBig.'/'.$imageName, 0755);
            chmod($pathMid.'/'.$imageName, 0755);
            chmod($pathThumb.'/'.$imageName, 0755);

            self::uploadToStorage($modul,$imageName);
            return ['status'=>true,'namaImage'=>$imageName];
        }catch(Exception $e){
            return ['status'=>false,'namaImage'=>''];
        }
    }

    public static function imageUser($dataImage){

        $image = $dataImage['file'];
        $imageExtension     = $image->getClientOriginalExtension();
        $destinationPath    = $dataImage['path'];
        $path_big           = $destinationPath.'big';
        $path_mid           = $destinationPath.'mid';
        $path_thumb         = $destinationPath.'thumb';
        $settingSize        = $dataImage['setting'];
        // dd($settingSize);
        $modul              = $dataImage['modul'];
        $imageName          = time().'.'.$image->getClientOriginalExtension();

        // $watermark          = $dataImage['watermark']['status'];
        // $txt_watermark      = $dataImage['watermark']['text'];
        // $font_watermark     = $dataImage['watermark']['font'];
        //echo $dataImage['watermark']['font'];

        $width_1_1      = ceil($dataImage['data']['skala11']['width']);
        $height_1_1     = ceil($dataImage['data']['skala11']['height']);
        $x_1_1          = ceil($dataImage['data']['skala11']['x']);
        $y_1_1          = ceil($dataImage['data']['skala11']['y']);

        self::cekFolder2($destinationPath);

        $img = Image::make($image->getRealPath());

        try{
            $img = Image::make($image->getRealPath());
            $img->crop($width_1_1, $height_1_1, $x_1_1, $y_1_1)->save($path_big.'/'.$imageName)->destroy();

            $img = Image::make($image->getRealPath());
            $img->crop($width_1_1, $height_1_1, $x_1_1, $y_1_1)->save($path_mid.'/'.$imageName)->destroy();

            $img = Image::make($image->getRealPath());
            $img->crop($width_1_1, $height_1_1, $x_1_1, $y_1_1)->save($path_thumb.'/'.$imageName)->destroy();

            Image::make($path_big.'/'.$imageName)->resize($settingSize['mid_width'], null, function ($constraint) { $constraint->aspectRatio(); })->save($path_big.'/'.$imageName)->destroy();

            Image::make($path_mid.'/'.$imageName)->resize($settingSize['mid_width'], null, function ($constraint) { $constraint->aspectRatio(); })->save($path_mid.'/'.$imageName)->destroy();

            Image::make($path_thumb.'/'.$imageName)->resize($settingSize['thumb_width'], null, function ($constraint) { $constraint->aspectRatio(); })->save($path_thumb.'/'.$imageName)->destroy();

            $img->destroy();

            chmod($path_big.'/'.$imageName, 0755);
            chmod($path_mid.'/'.$imageName, 0755);
            chmod($path_thumb.'/'.$imageName, 0755);

            self::uploadToStorage($modul,$imageName);
            return ['status'=>true,'namaImage'=>$imageName];
        }catch(Exception $e){
            dd($e->getMessage());
            return ['status'=>false,'namaImage'=>''];
        }
    }

    public static function cekFolder2($parentPath){
        if(!file_exists(public_path('storage'))){
            mkdir(public_path('storage'), 0755);
        }

        if(!file_exists(public_path('storage/pictures'))){
            mkdir(public_path('storage/pictures'), 0755);
        }
        if(!file_exists($parentPath)){
            mkdir($parentPath, 0755);
        }
        if(!file_exists($parentPath.'big')){
            mkdir($parentPath.'big', 0755);
        }
        if(!file_exists($parentPath.'mid')){
            mkdir($parentPath.'mid', 0755);
        }
        if(!file_exists($parentPath.'thumb')){
            mkdir($parentPath.'thumb', 0755);
        }
    }
    public static function cekFolder($parentPath){
        if(!file_exists(public_path('storage'))){
            mkdir(public_path('storage'), 0755);
        }
        if(!file_exists(public_path('storage/pictures'))){
            mkdir(public_path('storage/pictures'), 0755);
        }
        if(!file_exists(public_path('storage/pictures'))){
            mkdir(public_path('storage/pictures'), 0755);
        }
        // if(!file_exists($parentPath)){
        //     mkdir($parentPath, 0755);
        // }
        // if(!file_exists($parentPath.'16_9')){
        //     mkdir($parentPath.'16_9', 0755);
        // }
        // if(!file_exists($parentPath.'4_3')){
        //     mkdir($parentPath.'4_3', 0755);
        // }
        // if(!file_exists($parentPath.'1_1')){
        //     mkdir($parentPath.'1_1', 0755);
        // }

        // if(!file_exists($parentPath.'16_9/big')){
        //     mkdir($parentPath.'16_9/big', 0755);
        // }
        // if(!file_exists($parentPath.'16_9/mid')){
        //     mkdir($parentPath.'16_9/mid', 0755);
        // }
        // if(!file_exists($parentPath.'16_9/thumb')){
        //     mkdir($parentPath.'16_9/thumb', 0755);
        // }

        // if(!file_exists($parentPath.'4_3/big')){
        //     mkdir($parentPath.'4_3/big', 0755);
        // }
        // if(!file_exists($parentPath.'4_3/mid')){
        //     mkdir($parentPath.'4_3/mid', 0755);
        // }
        // if(!file_exists($parentPath.'4_3/thumb')){
        //     mkdir($parentPath.'4_3/thumb', 0755);
        // }

        // if(!file_exists($parentPath.'1_1/big')){
        //     mkdir($parentPath.'1_1/big', 0755);
        // }
        // if(!file_exists($parentPath.'1_1/mid')){
        //     mkdir($parentPath.'1_1/mid', 0755);
        // }
        // if(!file_exists($parentPath.'1_1/thumb')){
        //     mkdir($parentPath.'1_1/thumb', 0755);
        // }
    }
    public static function cekFolderArchive($parentPath){
        if(!file_exists(public_path('storage'))){
            mkdir(public_path('storage'), 0755);
        }
        if(!file_exists($parentPath)){
            mkdir($parentPath, 0755);
        }
    }
    public static function uploadToStorage($modul,$filename){
        switch($modul){
            case 'posts':
                $path_big_1 = File::get(public_path('storage/pictures').'/posts/1_1/big/'.$filename);
                Storage::disk('ftp')->put($path_ftp_big_1, $path_big_1);
                unlink(public_path('storage/pictures').'/posts/1_1/big/'.$filename);

                $path_mid_1 = File::get(public_path('storage/pictures').'/posts/1_1/mid/'.$filename);
                Storage::disk('ftp')->put($path_ftp_mid_1, $path_mid_1);
                unlink(public_path('storage/pictures').'/posts/1_1/mid/'.$filename);

                $path_thumb_1 = File::get(public_path('storage/pictures').'/posts/1_1/thumb/'.$filename);
                Storage::disk('ftp')->put($path_ftp_thumb_1, $path_thumb_1);
                unlink(public_path('storage/pictures').'/posts/1_1/thumb/'.$filename);

                $path_big_4 = File::get(public_path('storage/pictures').'/posts/4_3/big/'.$filename);
                Storage::disk('ftp')->put($path_ftp_big_4, $path_big_4);
                unlink(public_path('storage/pictures').'/posts/4_3/big/'.$filename);

                $path_mid_4 = File::get(public_path('storage/pictures').'/posts/4_3/mid/'.$filename);
                Storage::disk('ftp')->put($path_ftp_mid_4, $path_mid_4);
                unlink(public_path('storage/pictures').'/posts/4_3/mid/'.$filename);

                $path_thumb_4 = File::get(public_path('storage/pictures').'/posts/4_3/thumb/'.$filename);
                Storage::disk('ftp')->put($path_ftp_thumb_4, $path_thumb_4);
                unlink(public_path('storage/pictures').'/posts/4_3/thumb/'.$filename);

                $path_big_16 = File::get(public_path('storage/pictures').'/posts/16_9/big/'.$filename);
                Storage::disk('ftp')->put($path_ftp_big_16, $path_big_16);
                unlink(public_path('storage/pictures').'/posts/16_9/big/'.$filename);

                $path_mid_16 = File::get(public_path('storage/pictures').'/posts/16_9/mid/'.$filename);
                Storage::disk('ftp')->put($path_ftp_mid_16, $path_mid_16);
                unlink(public_path('storage/pictures').'/posts/16_9/mid/'.$filename);

                $path_thumb_16 = File::get(public_path('storage/pictures').'/posts/16_9/thumb/'.$filename);
                Storage::disk('ftp')->put($path_ftp_thumb_16, $path_thumb_16);
                unlink(public_path('storage/pictures').'/posts/16_9/thumb/'.$filename);
            break;
            case 'gallery':
                $path_big_1 = File::get(public_path('storage/pictures').'/gallery/1_1/big/'.$filename);
                Storage::disk('ftp')->put($path_ftp_big_1, $path_big_1);
                unlink(public_path('storage/pictures').'/gallery/1_1/big/'.$filename);

                $path_mid_1 = File::get(public_path('storage/pictures').'/gallery/1_1/mid/'.$filename);
                Storage::disk('ftp')->put($path_ftp_mid_1, $path_mid_1);
                unlink(public_path('storage/pictures').'/gallery/1_1/mid/'.$filename);

                $path_thumb_1 = File::get(public_path('storage/pictures').'/gallery/1_1/thumb/'.$filename);
                Storage::disk('ftp')->put($path_ftp_thumb_1, $path_thumb_1);
                unlink(public_path('storage/pictures').'/gallery/1_1/thumb/'.$filename);

                $path_big_4 = File::get(public_path('storage/pictures').'/gallery/4_3/big/'.$filename);
                Storage::disk('ftp')->put($path_ftp_big_4, $path_big_4);
                unlink(public_path('storage/pictures').'/gallery/4_3/big/'.$filename);

                $path_mid_4 = File::get(public_path('storage/pictures').'/gallery/4_3/mid/'.$filename);
                Storage::disk('ftp')->put($path_ftp_mid_4, $path_mid_4);
                unlink(public_path('storage/pictures').'/gallery/4_3/mid/'.$filename);

                $path_thumb_4 = File::get(public_path('storage/pictures').'/gallery/4_3/thumb/'.$filename);
                Storage::disk('ftp')->put($path_ftp_thumb_4, $path_thumb_4);
                unlink(public_path('storage/pictures').'/gallery/4_3/thumb/'.$filename);

                $path_big_16 = File::get(public_path('storage/pictures').'/gallery/16_9/big/'.$filename);
                Storage::disk('ftp')->put($path_ftp_big_16, $path_big_16);
                unlink(public_path('storage/pictures').'/gallery/16_9/big/'.$filename);

                $path_mid_16 = File::get(public_path('storage/pictures').'/gallery/16_9/mid/'.$filename);
                Storage::disk('ftp')->put($path_ftp_mid_16, $path_mid_16);
                unlink(public_path('storage/pictures').'/gallery/16_9/mid/'.$filename);

                $path_thumb_16 = File::get(public_path('storage/pictures').'/gallery/16_9/thumb/'.$filename);
                Storage::disk('ftp')->put($path_ftp_thumb_16, $path_thumb_16);
                unlink(public_path('storage/pictures').'/gallery/16_9/thumb/'.$filename);
            break;
            case 'archives':
                $path_local = File::get(public_path('storage/archives/').$filename);
                Storage::disk('ftp')->put($path_ftp, $path_local);
                unlink(public_path('storage/archives/').$filename);
            break;
            case 'services':
                $path_local = File::get(public_path('storage/pictures').'/services/'.$filename);
                Storage::disk('ftp')->put($path_ftp, $path_local);
                unlink(public_path('storage/pictures').'/services/'.$filename);
            break;
            case 'banners':
                $path_local = File::get(public_path('storage/pictures').'/banners/'.$filename);
                Storage::disk('ftp')->put($path_ftp, $path_local);
                unlink(public_path('storage/pictures').'/banners/'.$filename);
            break;
            case 'authors':
                $path_big_1 = File::get(public_path('storage/pictures').'/authors/1_1/big/'.$filename);
                // Storage::disk('ftp')->put($path_ftp_big_1, $path_big_1);
                unlink(public_path('storage/pictures').'/authors/1_1/big/'.$filename);

                $path_mid_1 = File::get(public_path('storage/pictures').'/authors/1_1/mid/'.$filename);
                // Storage::disk('ftp')->put($path_ftp_mid_1, $path_mid_1);
                unlink(public_path('storage/pictures').'/authors/1_1/mid/'.$filename);

                $path_thumb_1 = File::get(public_path('storage/pictures').'/authors/1_1/thumb/'.$filename);
                // Storage::disk('ftp')->put($path_ftp_thumb_1, $path_thumb_1);
                unlink(public_path('storage/pictures').'/authors/1_1/thumb/'.$filename);

                $path_big_4 = File::get(public_path('storage/pictures').'/authors/4_3/big/'.$filename);
                // Storage::disk('ftp')->put($path_ftp_big_4, $path_big_4);
                unlink(public_path('storage/pictures').'/authors/4_3/big/'.$filename);

                $path_mid_4 = File::get(public_path('storage/pictures').'/authors/4_3/mid/'.$filename);
                // Storage::disk('ftp')->put($path_ftp_mid_4, $path_mid_4);
                unlink(public_path('storage/pictures').'/authors/4_3/mid/'.$filename);

                $path_thumb_4 = File::get(public_path('storage/pictures').'/authors/4_3/thumb/'.$filename);
                // Storage::disk('ftp')->put($path_ftp_thumb_4, $path_thumb_4);
                unlink(public_path('storage/pictures').'/authors/4_3/thumb/'.$filename);

                $path_big_16 = File::get(public_path('storage/pictures').'/authors/16_9/big/'.$filename);
                // Storage::disk('ftp')->put($path_ftp_big_16, $path_big_16);
                unlink(public_path('storage/pictures').'/authors/16_9/big/'.$filename);

                $path_mid_16 = File::get(public_path('storage/pictures').'/authors/16_9/mid/'.$filename);
                // Storage::disk('ftp')->put($path_ftp_mid_16, $path_mid_16);
                unlink(public_path('storage/pictures').'/authors/16_9/mid/'.$filename);

                $path_thumb_16 = File::get(public_path('storage/pictures').'/authors/16_9/thumb/'.$filename);
                // Storage::disk('ftp')->put($path_ftp_thumb_16, $path_thumb_16);
                unlink(public_path('storage/pictures').'/authors/16_9/thumb/'.$filename);
            break;
            case 'users':
                $path_big       = File::get(public_path('storage/pictures').'/users/big/'.$filename);
                // Storage::disk('ftp')->put($path_ftp_big, $path_big);
                unlink(public_path('storage/pictures').'/users/big/'.$filename);

                $path_mid       = File::get(public_path('storage/pictures').'/users/mid/'.$filename);
                // Storage::disk('ftp')->put($path_ftp_mid, $path_mid);
                unlink(public_path('storage/pictures').'/users/mid/'.$filename);

                $path_thumb       = File::get(public_path('storage/pictures').'/users/thumb/'.$filename);
                // Storage::disk('ftp')->put($path_ftp_thumb, $path_thumb);
                unlink(public_path('storage/pictures').'/users/thumb/'.$filename);
            break;
        }
    }
    public static function deleteToStorage($modul ,$filename){
        switch($modul){
            case 'posts':
                $path_ftp_big_1 = env('STORAGE_CDN_POSTS').'1_1/big/'.$filename;
                $path_ftp_mid_1 = env('STORAGE_CDN_POSTS').'1_1/mid/'.$filename;
                $path_ftp_thumb_1 = env('STORAGE_CDN_POSTS').'1_1/thumb/'.$filename;
                $path_ftp_big_4 = env('STORAGE_CDN_POSTS').'4_3/big/'.$filename;
                $path_ftp_mid_4 = env('STORAGE_CDN_POSTS').'4_3/mid/'.$filename;
                $path_ftp_thumb_4 = env('STORAGE_CDN_POSTS').'4_3/thumb/'.$filename;
                $path_ftp_big_16 = env('STORAGE_CDN_POSTS').'16_9/big/'.$filename;
                $path_ftp_mid_16 = env('STORAGE_CDN_POSTS').'16_9/mid/'.$filename;
                $path_ftp_thumb_16 = env('STORAGE_CDN_POSTS').'16_9/thumb/'.$filename;

                $path_ftp_big = env('STORAGE_CDN_POSTS').'big/'.$filename;
                $path_ftp_mid = env('STORAGE_CDN_POSTS').'mid/'.$filename;
                $path_ftp_thumb = env('STORAGE_CDN_POSTS').'thumb/'.$filename;

                if(self::ftpCheckFile($path_ftp_big_1) == true){
                    Storage::disk('ftp')->delete($path_ftp_big_1);
                    Storage::disk('ftp')->delete($path_ftp_mid_1);
                    Storage::disk('ftp')->delete($path_ftp_thumb_1);
                    Storage::disk('ftp')->delete($path_ftp_big_4);
                    Storage::disk('ftp')->delete($path_ftp_mid_4);
                    Storage::disk('ftp')->delete($path_ftp_thumb_4);
                    Storage::disk('ftp')->delete($path_ftp_big_16);
                    Storage::disk('ftp')->delete($path_ftp_mid_16);
                    Storage::disk('ftp')->delete($path_ftp_thumb_16);
                }else{
                    Storage::disk('ftp')->delete($path_ftp_big);
                    Storage::disk('ftp')->delete($path_ftp_mid);
                    Storage::disk('ftp')->delete($path_ftp_thumb);
                }
            break;
            case 'gallery':
                $path_ftp_big_1 = env('STORAGE_CDN_GALLERY').'1_1/big/'.$filename;
                $path_ftp_mid_1 = env('STORAGE_CDN_GALLERY').'1_1/mid/'.$filename;
                $path_ftp_thumb_1 = env('STORAGE_CDN_GALLERY').'1_1/thumb/'.$filename;
                $path_ftp_big_4 = env('STORAGE_CDN_GALLERY').'4_3/big/'.$filename;
                $path_ftp_mid_4 = env('STORAGE_CDN_GALLERY').'4_3/mid/'.$filename;
                $path_ftp_thumb_4 = env('STORAGE_CDN_GALLERY').'4_3/thumb/'.$filename;
                $path_ftp_big_16 = env('STORAGE_CDN_GALLERY').'16_9/big/'.$filename;
                $path_ftp_mid_16 = env('STORAGE_CDN_GALLERY').'16_9/mid/'.$filename;
                $path_ftp_thumb_16 = env('STORAGE_CDN_GALLERY').'16_9/thumb/'.$filename;

                $path_ftp_big = env('STORAGE_CDN_GALLERY').'big/'.$filename;
                $path_ftp_mid = env('STORAGE_CDN_GALLERY').'mid/'.$filename;
                $path_ftp_thumb = env('STORAGE_CDN_GALLERY').'thumb/'.$filename;

                if(self::ftpCheckFile($path_ftp_big_1) == true){
                    Storage::disk('ftp')->delete($path_ftp_big_1);
                    Storage::disk('ftp')->delete($path_ftp_mid_1);
                    Storage::disk('ftp')->delete($path_ftp_thumb_1);
                    Storage::disk('ftp')->delete($path_ftp_big_4);
                    Storage::disk('ftp')->delete($path_ftp_mid_4);
                    Storage::disk('ftp')->delete($path_ftp_thumb_4);
                    Storage::disk('ftp')->delete($path_ftp_big_16);
                    Storage::disk('ftp')->delete($path_ftp_mid_16);
                    Storage::disk('ftp')->delete($path_ftp_thumb_16);
                }else{
                    Storage::disk('ftp')->delete($path_ftp_big);
                    Storage::disk('ftp')->delete($path_ftp_mid);
                    Storage::disk('ftp')->delete($path_ftp_thumb);
                }
            break;
            case 'archives':
                $path_ftp = env('STORAGE_CDN_ARCHIVES').$filename;
                Storage::disk('ftp')->delete($path_ftp);
            break;
            case 'services':
                $path_ftp = env('STORAGE_CDN_SERVICES').$filename;

                Storage::disk('ftp')->delete($path_ftp);
            break;
            case 'banners':
                // $path_ftp = env('STORAGE_CDN_BANNERS').$filename;
                Storage::disk('ftp')->delete($path_ftp);
            break;
            case 'authors':
                unlink(public_path('storage/pictures').'/authors/1_1/big/'.$filename);

                unlink(public_path('storage/pictures').'/authors/1_1/mid/'.$filename);

                unlink(public_path('storage/pictures').'/authors/1_1/thumb/'.$filename);

                unlink(public_path('storage/pictures').'/authors/4_3/big/'.$filename);

                unlink(public_path('storage/pictures').'/authors/4_3/mid/'.$filename);

                unlink(public_path('storage/pictures').'/authors/4_3/thumb/'.$filename);

                unlink(public_path('storage/pictures').'/authors/16_9/big/'.$filename);

                unlink(public_path('storage/pictures').'/authors/16_9/mid/'.$filename);

                unlink(public_path('storage/pictures').'/authors/16_9/thumb/'.$filename);
            break;
            case 'users':
                $path_ftp_big = env('STORAGE_CDN_USERS').'big/'.$filename;
                $path_ftp_mid = env('STORAGE_CDN_USERS').'mid/'.$filename;
                $path_ftp_thumb = env('STORAGE_CDN_USERS').'thumb/'.$filename;

                    // Storage::disk('ftp')->delete($path_ftp_big);
                    // Storage::disk('ftp')->delete($path_ftp_mid);
                    // Storage::disk('ftp')->delete($path_ftp_thumb);
            break;
        }
    }
}
