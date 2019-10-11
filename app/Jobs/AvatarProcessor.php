<?php

namespace App\Jobs;

use \Exception;
use Qiniu\Auth;
use Qiniu\Storage\UploadManager;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Storage;

class AvatarProcessor implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct($avatar_url,$avatar_name)
    {
        //录音原本存储的路径
        $this->avatar_url = $avatar_url;
        $this->avatar_name = $avatar_name;
        $this->handle();
    }


    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $accessKey = config('qiniu.access_key');
        $secretKey = config('qiniu.secret_key');
        $bucket = config('qiniu.bucket');

        // 构建鉴权对象
        $auth = new Auth($accessKey, $secretKey);

        // 生成上传 Token
        $token = $auth->uploadToken($bucket);
        // 要上传文件的本地路径
        $filePath = $this->avatar_url;

        // 上传到七牛后保存的文件名
        $key = $this->avatar_name;

        // 初始化 UploadManager 对象并进行文件的上传。
        $uploadMgr = new UploadManager();

        // 调用 UploadManager 的 putFile 方法进行文件的上传。
        $uploadMgr->putFile($token, $key, $filePath);
        return true;
    }

}
