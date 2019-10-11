<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Heal extends Model
{
    protected $table = 'heal';

    protected $fillable = [
        'name', 'note', 'lang', 'user_id'
    ];

    protected $hidden = [
        'updated_at', 'created_at'
    ];

    /*
     * create
     * 创建经典治愈
     * 
     * @param  $user_id     创建者的用户id
     * @param  $name        歌曲名称
     * @param  $note        备注
     * @param  $lang        语种
     * @return [$status, $message]      创建状态，错误信息
     */
    public static function create($user_id, $name, $note, $lang) {
        $retry = 0;
        $status = false;
        $message = '';
        DB::beginTransaction();
        while ($retry < 5) {
            try{
                $heal = new Heal;
                $heal->user_id = $user_id;
                $heal->name    = $name;
                $heal->note    = $note;
                $heal->lang    = $lang;
                $heal->save();
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
