<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Jobs\AvatarProcessor;
use Illuminate\Support\Facades\Storage;

class Qiniu extends OriginModel
{
    /**
     * 录音的下载、转码、拼接、上传一条龙，返回上传到七牛的URL
	 * @param $urls 地址数组 
	 * @param $name 歌曲名称
     * @return string 上传到七牛后的URL
	 */
    public static function recordJob($urls, $name){
        $final_name = md5(uniqid(md5(microtime(true)),true));
        $domain = config('qiniu.domain');
        $res = RecordProcessor::dispatch($name, $urls, $final_name)->onQueue('convert');
        return $domain.$final_name.'.mp3';
    }

    /**
     * 将用户头像上传七牛，返回上传到七牛的URL
	 * @param $avatar 前端传过来的头像文件 
     * @return string 上传到七牛后的URL
	 */
    public static function avatarJob($avatar){
        
        $extension = $avatar->getClientOriginalExtension(); //获取上传图片的后缀名
        if (!in_array(strtolower($extension), ['pdf', 'jpg', 'jpeg', 'png'])) {
            return false;
        }

        $avatarName = md5(time()) . random_int(5,5) . "." . $extension; //5、重新命名上传文件名字
        $realPath = $avatar->getRealPath();   //临时文件的绝对路径
        
        //Storage::put('tmp/' . $avatarName, $realPath);
        // $avatar->move(storage_path('app/tmp/'), $avatarName);
        // $url = storage_path('app/tmp/').$avatarName;

        //此处直接将临时地址传上七牛
        $domain = config('qiniu.domain');
        $res = AvatarProcessor::dispatch($realPath,$avatarName)->onQueue('avatar');
        if($res)    return $domain.$avatarName;
        else    return false;
    }
    
}
