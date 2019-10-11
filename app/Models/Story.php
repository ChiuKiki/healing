<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Story extends Model
{
    protected $table = 'story';

    protected $fillable = [
        'user_id', 'content'
    ];

    protected $hidden = [
        'updated_at', 'created_at'
    ];

    /*
     * create
     * 创建故事治愈
     * 
     * @param  $user_id     创建者的用户id
     * @param  $content     故事文本
     * @param  $pics        数组，储存图片的url
     * @return [$status, $message]      创建状态，错误信息
     */
    public static function create($user_id, $content, $pics) {
        $retry = 0;
        $status = false;
        $message = '';
        DB::beginTransaction();
        while ($retry < 5) {
            try{
                $story = new Story;
                $story->user_id = $user_id;
                $story->content = $content;
                $story->save();
                foreach ($pics as $pic) {
                    $storypic = new StoryPicture;
                    $storypic->story_id = $story->id;
                    $storypic->url      = $pic;
                    $storypic->save();
                }
                DB::commit();
                $retry = 99;
                $status = true;
            } catch (\Exception $e) {
                DB::rollBack();
                $message = $e->getMessage();
                $retry++;
            }
        }
        return [$status, $message];
    }

    public function creator() {
        return $this->belongsTo('App\Models\User', 'user_id')
            ->select('id', 'name', 'avatar', 'phone');
    }

    public function pics() {
        $arr = $this->hasMany('App\Models\StoryPicture')->get()->toArray();
        $res = [];
        foreach($arr as $pic) $res[] = $pic['url'];
        return $res;
    }
}
