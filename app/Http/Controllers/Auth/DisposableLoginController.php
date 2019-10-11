<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redis;

class DisposableLoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | OneTimeLoginController  —  使用一次性token登录
    |--------------------------------------------------------------------------
    |
    | 1. 验证用户提交的token是否存在Redis中
    | 2. 设置用户信息
    |
    */

    public function authenticate(Request $request) {
        // Step 1: 验证用户提交的token是否在redis中
        $app_name = config('app.name');
        if (!$request->has('token'))
            return response('Missing argument.', 400);

        $request_token = $request->token;
        if (!Redis::exists($app_name.':token:' . $request_token))
            return response(array('message' => 'Authentication failed. Token not found.'), 403);
        
        // Step 2: 设置用户信息，将用户信息存在session中
        $id = Redis::get($app_name.':token:' . $request_token);
        $user = User::find($id);
        if (!$user) return response(array('message' => 'This user is not exist.'), 500);
        $request->session()->put('user', $user->toArray());

        // Step 3: 跳转
        if ($request->has('redirect')) {
            return redirect($request->redirect);
        } else {
            return redirect(env('HOME_PAGE', '/'));
        }
    }

    public function logout(Request $request) {
        $request->session()->flush();
        return response(array('message' => 'ok'));
    }
}
