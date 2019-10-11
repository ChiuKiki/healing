<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redis;

class OAuthController extends Controller
{
    /*
     *----------------------------------------------
     * OAuthController - 使用APIv2的OAuth进行登录
     *----------------------------------------------
     *
     * 1.接收APIv2传过来的用户数据并进行解密
     * 2.检验用户是否存在， 若不存在则创建用户
     * 3.生成token，将它和用户id一起存入Redis中
     * 4.生成一次性登录地址返回给APIv2
     *
     */                                 

    public function process(Request $request) {
        // STEP1 解密从apiv2接收的数据
        $app_name = config('app.name');
        $data = openssl_decrypt(
            $request->instance()->getContent(),
            config('wechat.crypt'),
            config('wechat.aes_key'),
            0,
            config('wechat.iv_key')
        );
        $data = json_decode($data);
        if (!$data) return response('Decrypt failed.', 403);
        if (!isset($data->openid)) {
            var_dump($data);
            return response('Missing user openid', 400);
        }

        //STEP2 从数据库中取用户信息并更新，若没有该用户则创建一个
        $user = User::where('openid', $data->openid)->first();
        if (!$user) {
            $user = new User;
            $user->openid = $data->openid;
            $user->name   = $data->nickname;
        }
        $user->sex = $data->sex;
        if ($data->headimgurl != $user->avatar) {
            $user->avatar      = $data->headimgurl;
            $user->avatar_hash = md5($user->avatar);
        }
        $user->save();

        //STEP3 生成token并构造一次性登录地址
        $token = base64_encode(random_bytes(16));
        Redis::set($app_name.':token:'.$token, $user->id, 'EX', 30);
        if ($request->has('redirect')) {
            return action(
                'Auth\DisposableLoginController@authenticate',
                array('token' => $token, 'redirect' => $request->redirect)
            );
        }
        return action(
            'Auth\DisposableLoginController@authenticate',
            array('token' => $token)
        );
    }


    /*
     *
     * 用于测试，跳过微信认证，使用第一条用户记录登录
     *
     */
    public function fake(Request $request, $id) {
        if (!config('app.debug')) return response('Permission denied.', 403);
        $app_name = config('app.name');
        $user = User::findOrFail($id);
        $token = base64_encode(random_bytes(16));
        Redis::set($app_name.':token:'.$token, $user->id, 'EX', 30);
        if ($request->has('redirect')) {
            return redirect()->action(
                'Auth\DisposableLoginController@authenticate',
                array('token' => $token, 'redirect' => $request->redirect)
            );
        }
        return redirect()->action(
            'Auth\DisposableLoginController@authenticate',
            array('token' => $token)
        );
    }


    /*
     *  该函数会跳转到微信认证网页
     *
     */
    public function jump(Request $request) {
        $apiv2 = env('APIV2_ROOT', 'https://apiv2.100steps.top');
        $appid = config('wechat.test_account')? config('wechat.test_app_id'): config('wechat.app_id');
        if ($request->has('redirect')) {
            $url2b64 = base64_encode(action(
                'Auth\OAuthController@process', 
                array('redirect' => $request->redirect)
            ));
        } else {
            $url2b64 = base64_encode(action('Auth\OAuthController@process'));
        }
        // 测试号，在callback_uri后加test=true
        if (config('wechat.test_account')) {
            $request = urlencode($apiv2."/wx_oauth/$url2b64?test=true");
        } else {
            $request = urlencode($apiv2."/wx_oauth/$url2b64");
        }
        return redirect("https://open.weixin.qq.com/connect/oauth2/authorize?appid=$appid&redirect_uri=$request&response_type=code&scope=snsapi_userinfo#wechat_redirect");
    }

}
