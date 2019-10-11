<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Http\Request;

class UserController extends BaseController
{
    public function get_info(Request $request) {
        $user = $request->session()->get('user');
        return response($user);
    }

    //个人信息更新
    public function updateInfo(Request $request){
        $userData = $request->all();
        //获取自己的ID及其他信息
        $user = $request->session()->get('user');
        $userId = $user['id'];
        $userInfo = User::find($userId);

        //检查传入数据是否为User.php中允许修改的数据
        //更新
        foreach ($userData as $k => $v) {
            if (array_search($k, User::$userCanModify) === false) {
                return response()->json(['message' => '修改失败'], 403);
            }else {
                $userInfo->$k = $v;
            }
        }
        //保存更新
        $userInfo->save();
        return response()->json(['message' => '修改成功'], 200);
    }


}
