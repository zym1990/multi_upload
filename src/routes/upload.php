<?php
/**
 * Created by PhpStorm.
 * User: zhangyamin
 * Date: 17-10-31
 * Time: 上午10:15
 */
Route::group(['middleware'=>['web'],'prefix' => config('multi_upload.upload_prefix'), 'namespace'=>'xiaomi\multiUpload\Controller'], function () {
    //上传页面
    Route::get('add', 'UploadController@add');
    Route::post('upload', 'UploadController@upload');
});
