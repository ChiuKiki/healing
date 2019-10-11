<?php

namespace App\Http\Controllers;

use Illuminate\Routing\Controller as BaseController;
use Illuminate\Http\Request;
use App\Models\Heal as Heal;

class HealController extends BaseController
{
    public function create(Request $request) {
        $expect = ['name', 'note', 'lang'];
        if (!$request->has($expect)) return response(['message' => '参数错误！'], 400);
        $user = $request->session()->get('user');
        $create_res = Heal::create($user['id'], $request->name, $request->note, $request->lang);
        return $create_res[0]? response(''): response(['message' => $create_res[1]], 500);
    }
}
