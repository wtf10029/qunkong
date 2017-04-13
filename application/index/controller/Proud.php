<?php
namespace app\index\controller;

use think\Controller;
use think\Db;
use app\index\model\Proud as proudModel;

class Proud extends Common
{
    public function index()
    {
        return view('proud/proud');
    }

    public function proudLst()
    {
        $cate = Db::name('category')->where('pid',21)->field('id,catename')->select();

        $arr = array();
        foreach ($cate as $key => $val) {
            $proud = Db::name('news')->where('classify',$val['id'])->field('id,title,thumbnail,description')->select();
            //控制标题和描述的长度
            foreach ($proud as $k => $v) {
                $proud[$k]['title'] = mb_substr($v['title'],0,12,'utf-8');
                $proud[$k]['description'] = mb_substr($v['description'],0,12,'utf-8');
            }
            $arr[$val['catename']] = $proud;
        }

        $client = Db::name('image')->where('classify',29)->field('id,url')->select();

        echo json_encode(['history' =>$arr,'client' => $client]);
    }
}