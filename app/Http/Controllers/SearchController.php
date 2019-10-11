<?php

namespace App\Http\Controllers;

use Illuminate\Routing\Controller as BaseController;
use Illuminate\Http\Request;
use App\Models\Heal as Heal;
use App\Models\OvertRecording as Overt;
use App\Models\User as User;

class SearchController extends BaseController
{
    public function process(Request $request) {
        if (!$request->has('keyword')) return response(['message' => '参数错误！'], 400);
        $user    = $request->session()->get('user');
        $keyword = $request->keyword;
        $res     = [];
        $res['heals']  = $this->search_heal($keyword);
        $res['overts'] = $this->search_overt($keyword);
        $res['users']  = $this->search_user($keyword);
        return response($res);
    }

    public function search_heal($keyword) {
        $search = Heal::where('name', 'like', '%'.$keyword.'%')->with('creator')->limit(100)->get()->toArray();
        return $search;
    }

    public function search_overt($keyword) {
        $search = Overt::where('name', 'like', '%'.$keyword.'%')->with('creator')->limit(100)->get()->toArray();
        return $search;
    }

    public function search_user($keyword) {
        $search = User::where(['phonesearch' => 1, 'phone' => $keyword])
            ->orWhere(['realnamesearch' => 1, 'realname' => $keyword])
            ->select('id', 'avatar', 'name', 'signature')
            ->get()->toArray();
        return $search;
    }
}
