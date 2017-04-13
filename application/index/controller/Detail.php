<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/3/10
 * Time: 16:07
 */
namespace app\index\controller;

use think\Db;
use think\Controller;
use app\index\model\Detail as detailModel;

class Detail extends Common
{
    public function detail()
    {
        return view('detail/detail');
    }

    public function detailLst()
    {
        $id = input('post.id');
        $ty = Db::name('news')->where('id',$id)->field('classify')->find();
        $type = $ty['classify'];

        $detail = Db::name('news')->where('id',$id)->field('title,createtime,content')->find();

        $n = new detailModel();
        $upDown = $n->getId($id,$type);

        echo json_encode([
            'detail' => $detail,
            'upDown' => $upDown,
        ]);
    }
}