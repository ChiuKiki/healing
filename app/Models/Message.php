<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Message extends OriginModel
{
    protected $table = 'messages';

    protected $hidden = [
        'last', 'isread', 'created_at', 'updated_at'
    ];

    public function target1()
	{
		return $this->belongsTo('App\Models\User', 'user1');
	}

	public function target2()
	{
		return $this->belongsTo('App\Models\User', 'user2');
    }
    
    public static function storeMessage($type, $content, $user1, $user2, $from, $time)
	{
        //消息类型 1:普通消息 2:录音 3:晚推 4:点赞 5:系统消息
		$message = new Message();
		$message->type = $type;
		$message->content = json_encode($content);
		$message->user1 = $user1;
		$message->user2 = $user2;
        $message->from = $from;
        $message->time = $time;
        $message->isread = 0;
        $message->last = 1;
        //如果为系统消息，不会变更为最后一条消息
        if($type != 5){
            $lastMessage = Message::where([
                ['user1', '=', $user1],
                ['user2', '=', $user2],
                ['last', '=', 1]
                ])->orderBy('id')->first();
            if ($lastMessage) {
                $lastMessage->last = 0;
                $lastMessage->save();
            }
        }else{
            $message->last = 0; 
        }
		$message->save();
    }
    
    public static function getChattingMessages($user1, $user2, $last_id){
        $messages = Message::where([
            ['user1', '=', $user1],
            ['user2', '=', $user2],
            ['id', '>', $last_id]
            ])->orderBy('id')->get();

        return $messages;
    }

    /**
     * 获取未读消息的数量
	 * @param $user1 聊天室的用户其中之一 
     * @param $user2 聊天室的用户其中之一 
     * @param $userId 当前用户的id 
     * @return integer 未读消息的数量
	 */
    public static function getIsreadNumByUser($user1, $user2, $userId){
        $messages = Message::where([
            ['user1', '=', $user1],
            ['user2', '=', $user2],
            ['from', '!=', $userId],
            ['last', '!=', 5],
            ['isread', '=', 0]          //isread  是否已读 0:未读  1:已读
            ])->orderBy('id', 'desc')->get();
        $number = $messages->count();
        return $number;
    }

    /**
     * 将未读消息更新为已读消息
	 * @param $user1 聊天室的用户其中之一 
     * @param $user2 聊天室的用户其中之一 
     * @param $userId 当前用户的id 
	 */
    public static function readMessage($user1, $user2, $userId){
        $messages = Message::where([
            ['user1', '=', $user1],
            ['user2', '=', $user2],
            ['from', '!=', $userId],
            ['isread', '=', 0]
            ])->get();
        foreach($messages as $key => $message){
            $message->isread = 1;
            $message->save();
        }    
    
    }
        
}
