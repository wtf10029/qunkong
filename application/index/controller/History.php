<?php
namespace app\index\controller;

use think\Controller;
use think\Db;
use app\index\model\History as historyModel;

class History extends Common
{
    public function index()
    {
        return view('history/history');
    }

    public function history()
    {
        $cate = Db::name('category')->where('pid',5)->field('id,catename')->select();

        $arr = array();
        foreach ($cate as $key => $val) {
            $history = Db::name('news')->where('classify',$val['id'])->field('id,title,thumbnail,description')->select();
            $arr[$val['catename']] = $history;
        }

        echo json_encode(['history' =>$arr]);
    }
}