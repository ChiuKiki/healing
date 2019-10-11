<?php

namespace App\Http\Controllers;

use App\Models\Feedback;
use Illuminate\Http\Request;

class FeedbackController extends Controller
{
    //意见反馈
    public function suggestion(Request $request){
        $user = $request->session()->get('user');
        $userId = $user['id'];
        $phoneType = $request->get('phone_type');
        $problem = $request->get('problem');
        $picUrl = $request->get('pic_url');

        if(!$problem){
            //“遇到问题”必填
            return response()->json(['message' => '反馈失败'], 403);
        }else {
            Feedback::create([
                'user_id' => $userId,
                'phone_type' => $phoneType,
                'problem' => $problem,
                'pic_url' => $picUrl
            ]);
            return response()->json(['message' => '反馈成功'], 200);
        }
    }

    //海螺举报
    public function report(Request $request){
        $user = $request->session()->get('user');
        $userId = $user['id'];
        $seasnailId = $request->get('seasnail_id');
        $reason = $request->get('reason');

        if(!$seasnailId || !$reason){
            //海螺号和举报原因必填
            return response()->json(['message' => '举报失败'], 403);
        }else {
            Feedback::create([
                'user_id' => $userId,
                'seasnail_id' => $seasnailId,
                'reason' => $reason
            ]);
            return response()->json(['message' => '举报成功'], 200);
        }
    }
}
