<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Qiniu;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Storage;

class RecordController extends Controller
{
    //测试用的
    public function index()
    {
        $urls = array('http://pyh4sumxp.bkt.clouddn.com/test01.spx','http://pyh4sumxp.bkt.clouddn.com/test02.spx');
        $name = 'test';
        $job = Qiniu::recordJob($urls, $name);
        return $job;
    }

}
