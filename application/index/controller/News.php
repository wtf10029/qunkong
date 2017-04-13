<?php
namespace app\index\controller;

use think\Controller;
use think\Db;
use app\index\model\News as newsModel;

class News extends Common
{
    public function index()
    {
        return view('news/news');
    }

    public function detail()
    {
        return view('news/newsdetail');
    }

    public function newsLst()
    {
        $page = input('post.page') ? input('post.page') : 1;

        $pagesize = input('post.pagesize');

        $focus = newsModel::get(function($query){
            $query->where('classify',18)->where('status','1')->field('id,title,thumbnail,description,createtime');
        });

        $news = Db::name('news')->where('classify',20)->where('status','1')->order('createtime','desc')->field('id,title,createtime,description,thumbnail')->page($page,$pagesize)->select();
        //控制标题和描述的长度
        foreach ($news as $key => $val) {
            $news[$key]['title'] = mb_substr($val['title'],0,15,'utf-8');
            $news[$key]['description'] = mb_substr($val['description'],0,40,'utf-8');
        }

        //新闻总条数
        $count = Db::name('news')->where('classify',20)->where('status','1')->count();

        //新闻总页数
        $pagenum = ceil($count/$pagesize);

        echo json_encode(['focus' => $focus,'news' => $news,'conut' => $count,'pagenum' => $pagenum]);
    }
}