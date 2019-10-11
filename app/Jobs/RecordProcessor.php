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

class RecordProcessor implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $urls, $log;
    private $voice_origin_urls, $voice_name, $final_name;
    private $spx_path, $wav_path, $tmp_path, $mp3_path;

    public function __construct($voice_name, $voice_origin_urls, $final_name)
    {
        //录音原本存储的路径
        $this->voice_origin_urls = $voice_origin_urls;
        $this->voice_name = $voice_name;

        $this->spx_path = storage_path('app/spx');
        $this->wav_path = storage_path('app/wav');
        $this->mp3_path = storage_path('app/mp3');
        $this->tmp_path = storage_path('app/tmp');

        $this->log = '';
        $this->final_name = $final_name;
        // 根据语音素材id算md5作为语音的文件名
        foreach ($voice_origin_urls as $key => $value) {
            $this->origin_urls[$key] = $value;
        }

        $this->handle();
    }

    private function log($l) {
        var_dump($l);
        $this->log .= "\n$l";
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        // 下载语音到本地
        $this->log('Step 1');
        $this->fetchToLocal();
        // speex -> wav
        $this->log('Step 2');
        $this->speexToWav();
        // wav -> mp3
        $this->log('Step 3');
        $this->wavToMp3();
        // // 上传到七牛
        $this->log('Step 4');
        $this->updateToQiQiu();

        // 删除临时文件
        $this->log('Step 5');
        $this->deleteTemp();
        // 销毁任务
        $this->log('Step 6');
        $this->delete();

        
    }

    private function fetchToLocal()
    {
        // 下载speex录音到本地
        foreach ($this->voice_origin_urls as $key => $url) {
            $name = '';
            $name .= $this->voice_name.$key;
            $name = md5(uniqid($name,true));
            
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_TIMEOUT, 3);
            $rec = curl_exec($ch);
            curl_close($ch);

            //微信speex下载错误处理
            $json = json_decode($rec);
            if (isset($json->errcode)) {
                if ($json->errcode == 42001 || $json->errcode == 40001) {
                    // 42001是过期的errcode，40001为无效的ak
                    // 自动重试
                    Redis::del('wechat.access_token');
                    return false;
                } else {
                    // 抛出异常，laravel自动fail
                    $this->fail();
                    throw new Exception($rec);
                }
            }
            Storage::put('spx/' . $name. '.spx', $rec);
            $this->log('Saved as ' . 'spx/' . $name .'.spx', $url);
            $this->names[$key] = $name;

        }
    }

    // private function getAk()
    // {
    //     if (!Redis::exists('wechat.access_token')) {
    //         // redis中没有access_token，重新获取
    //         $token = $this->app->access_token->getToken(true);
    //         $this->ak = $token['access_token'];
    //         Redis::set('wechat.access_token', $this->ak, 'EX', 3600);
    //     }
    //     $this->ak = Redis::get('wechat.access_token');
    //     return $this->ak;
    // }

    private function speexToWav()
    {
        foreach ($this->names as $value) {
            $this->log("speex_decode {$this->spx_path}/$value.spx {$this->wav_path}/$value.wav 2>&1");
            $a = shell_exec("speex_decode {$this->spx_path}/$value.spx {$this->wav_path}/$value.wav 2>&1");
            $this->log($a);
            if ($a) {
                // 正常情况下speex_decode不返回任何输出
                $this->fail();
                throw new Exception('speex_decode failed: ' . $a);
            }
            
        }

        $l = '';
        foreach ($this->names as $value) {
            $l .= "file '{$this->wav_path}/$value.wav'\n";
        }
        
        Storage::put('tmp/' . $this->final_name . '.txt', $l);
        $this->log($l);
        // 执行ffmpeg
        // ffmpeg -f concat -i test.txt -c copy test.mp3
        $this->log("cd {$this->tmp_path}/ & ffmpeg -safe 0 -f concat -i {$this->final_name}.txt -c copy {$this->wav_path}/{$this->final_name}.wav");
        $f = shell_exec("cd {$this->tmp_path}/ & ffmpeg -safe 0 -f concat -i {$this->final_name}.txt -c copy {$this->wav_path}/{$this->final_name}.wav");
        
        // 如果没有生成对应mp3，说明错误
        if (!Storage::exists('wav/' . $this->final_name . '.wav')) {
            $this->fail();
            throw new Exception('Failed to execute ffmpeg to concat.');
        }
    }

    private function wavToMp3()
    {
        // 执行ffmpeg

        $this->log("cd {$this->wav_path}/ & ffmpeg -i {$this->final_name}.wav {$this->mp3_path}/{$this->final_name}.mp3");
        $f = shell_exec("cd {$this->wav_path}/ & ffmpeg -i {$this->final_name}.wav {$this->mp3_path}/{$this->final_name}.mp3");
        // 如果没有生成对应mp3，说明错误
        if (!Storage::exists('mp3/' . $this->final_name . '.mp3')) {
            $this->fail();
            throw new Exception('Failed to execute ffmpeg to transform.');
        }
    }

    private function updateToQiQiu()
    {
        $accessKey = config('qiniu.access_key');
        $secretKey = config('qiniu.secret_key');
        $bucket = config('qiniu.bucket');

        // 构建鉴权对象
        $auth = new Auth($accessKey, $secretKey);

        // 生成上传 Token
        $token = $auth->uploadToken($bucket);
        // 要上传文件的本地路径
        $filePath = "{$this->mp3_path}/{$this->final_name}.mp3";

        // 上传到七牛后保存的文件名
        $key = "{$this->final_name}.mp3";

        // 初始化 UploadManager 对象并进行文件的上传。
        $uploadMgr = new UploadManager();

        // 调用 UploadManager 的 putFile 方法进行文件的上传。
        $uploadMgr->putFile($token, $key, $filePath);

    }

    private function deleteTemp() {
        // 删除speex,wav和mp3临时文件
        foreach ($this->names as $value) {

            Storage::delete('spx/' . $value .'.spx');

            Storage::delete('wav/' . $value .'.wav');
            
        }

        Storage::delete('wav/' . $this->final_name. '.wav');

        Storage::delete('mp3/' . $this->final_name. '.mp3');
        // 删除ffmpeg队列文件

        Storage::delete('tmp/' . $this->final_name . '.txt');
    }
}
