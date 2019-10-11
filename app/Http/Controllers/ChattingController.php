<?php

namespace App\Http\Controllers;

use App\Models\Message;
use App\Models\Qiniu;
use Illuminate\Http\Request;

class ChattingController extends Controller
{
    /**
     * 聊天室首页获取列表
	 * @param 
	 * @param 
     * @return string 
	 */
    public function index(){
        session()->put('user', array('openid'=>1));
        //session('user', array('openid'=>1));
        $user = session('user');
        var_dump($user);
        $chattings = Message::where([
            ['last', '=', 1]
            ])->orderBy('id')->get();
        $result = array();
        foreach($chattings as $key => $last){
            
            $user1 = $last['user1'];
            $user2 = $last['user2'];
            $result[$key]['last'] = $last->toArray();
            //$result[$key]['target'] = ($user['openid'] == $user1) ? $last->target1: $last->target2;
            $result[$key]['target'] = ($user['openid'] == $user1) ? $last->target1->toArray(): $last->target2->toArray();
            $result[$key]['number'] = Message::getIsreadNumByUser($user1, $user2);

        }
        //return response()->json($result, 200);
        var_dump($result);
        
    }

    public function chattingRoom(Request $request){
        if (!$request->has('user1') || !$request->has('user2')) {
            return response()->json(['message' => '数据类型错误'], 403);
        }
        
        $user = session('user');
        $user1 = $request->user1;
        $user2 = $request->user2;
        //将未读消息更新为已读消息
        Message::readMessage($user1, $user2, $user['openid']);
        //获取的消息必须id从$last_id开始，即消息id大于$last_id，默认为0
        $last_id = ($request->has('last_id')) ? $request->last_id : 0 ;
        $messages = Message::getChattingMessages($user1, $user2, $last_id);
        foreach($messages as $key => $message){
            //$messages[$key]['target'] = ($message['from'] == $message['user1']) ? $message->target1: $message->target2;
            $messages[$key]['target'] = ($message['from'] == $message['user1']) ? $message->target1->toArray(): $message->target2->toArray();
            unset($message['user1']);
            unset($message['user2']);
            unset($message['target1']);
            unset($message['target2']);
            $messages[$key] = $message;
        }
        $messages = $messages->toArray();
        var_dump($messages);
        //return response()->json($messages, 200);
    }

    public function sendMessage(Request $request){
        if (!$request->has('user1') || !$request->has('user2')|| !$request->has('from')|| !$request->has('content')) {
            return response()->json(['message' => '数据类型错误'], 403);
        }
        $user = session('user');
        $user1 = $request->user1;
        $user2 = $request->user2;
        if($user1 == $user2 || $user1 > $user2){
            return response()->json(['message' => '两个用户的id有误'], 403);
        }

        if($user['openid'] != $user1 && $user['openid'] != $user2){
            return response()->json(['message' => '发送人必须是其中一个用户'], 403);
        }
        $from = $request->from;
        $content = $request->content;
        $type = 1;
        date_default_timezone_set('PRC');
        $time = date("Y-m-d H:i:s");
        Message::storeMessage($type, $content, $user1, $user2, $from, $time);
    }
}
