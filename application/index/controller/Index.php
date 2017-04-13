<?php
namespace app\index\controller;

use think\Controller;
use think\Db;

class Index extends Common
{
    public function index()
    {
        return view();
    }

    public function ajaxIndex()
    {
        $banner = Db::name('banner')->where('classify',28)->order('rank desc')->where('status',1)->field('title,path')->select();

        $product = Db::name('news')->where('classify',17)->where('status',1)->field('id,thumbnail,title,description')->select();
        foreach ($product as $key => $val) {
            $product[$key]['description'] = mb_substr($val['description'],0,49,'utf-8');
        }

        $cases = Db::name('news')->where(array('classify' => 19,'status' => 1))->field('id,thumbnail,title,description')->select();

        $news = Db::name('news')->where(array('classify' => 20,'status' => 1))->order('id desc')->limit(15)->field('id,createtime,title')->select();

        $client = Db::name('image')->where('classify',29)->field('id,url')->select();

        echo json_encode(['banner' => $banner,'product' => $product,'cases' => $cases,'news' => $news,'client' => $client]);
    }
}
