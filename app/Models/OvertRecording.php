<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OvertRecording extends Model
{
    protected $table = 'overtrecording';

    protected $fillable = [
        'user_id', 'overt_id', 'url', 'lang', 'name'
    ];

    protected $hidden = [
        'updated_at', 'created_at'
    ];

    /*
     * create
     * 创建公共录音
     * 
     * @param  $user_id     创建者的用户id
     * @param  $name        歌曲名称
     * @param  $lang        语种
     * @param  $url         七牛的url
     * @return [$status, $message]      创建状态，错误信息
     */
    public static function create($user_id, $name, $lang, $url) {
        $retry = 0;
        $status = false;
        $message = '';
        DB::beginTransaction();
        while ($retry < 5) {
            try{
                $overt = new OvertRecording;
                $overt->user_id = $user_id;
                $overt->name    = $name;
                $overt->note    = $note;
                $overt->lang    = $lang;
                $overt->save();
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
            ->select('id', 'name', 'sex', 'avatar', 'school', 'phone', 'signature');
    }
}
