<?php

namespace xiaomi\multiUpload\Controller;

use Illuminate\Routing\Controller;
use xiaomi\multiUpload\libraries\UploadHandler;

class UploadController extends Controller
{
    /**
     * 上传文件页面
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function add()
    {
        $original = [
            'driver' => 'local',
            'root' => storage_path('original'),
            'url' => env('APP_URL').'/storage',
            'visibility' => 'public',
        ];
        config('filesystems.disk')['original'] = $original;

        return view('upload::upload');
    }

    /**
     * 上传文件
     */
    public function upload()
    {
        $option = [
            'upload_dir'=> config('multi_upload.original_dir').'/',
            'upload_url'=>config('multi_upload.original_dir').'/',
//            'script_url' => substr($request->fullUrl(),0, strrpos($request->fullUrl(), '/')).'/file/del',
            'image_versions' =>[
                '' =>['auto_orient' => true]
            ],
        ];
        new UploadHandler($option);
    }
}