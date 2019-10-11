<?php

namespace App\Http\Controllers;

use Illuminate\Routing\Controller as BaseController;
use Illuminate\Http\Request;
use App\Models\Story as Story;

class StoryController extends BaseController
{
    /*
     * create
     * 创建故事治愈
     * 
     * @param  $request     注入请求
     * @return response     响应前端
     */
    public function create(Request $request) {
        $expect = ['content'];
        if (!$request->has($expect)) return response(['message' => '参数错误！'], 400);
        $user = $request->session()->get('user');
        $create_res = Story::create($user['id'], $request->content, ['1.jpg', '2.jpg']);
        return $create_res[0]? response(''): response(['message' => $create_res[1]], 500);
    }

    /*
     * get_info
     * 获取故事治愈详情
     * 
     * @param  $request     注入请求
     * @param  $storyID     故事治愈的id
     * @return response     响应前端
     */
    public function get_info(Request $request, $storyID) {
        $story = Story::with('creator')->find($storyID);
        $pics  = $story->pics();
        $story->toArray();
        $story['pics'] = $pics;
        return response($story);
    }
}
