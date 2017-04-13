<?php
namespace app\index\controller;

use think\Controller;
use think\Db;
use app\index\model\Cases as caseModel;

class Cases extends Common
{
    public function index()
    {
        return view('cases/cases');
    }

    public function detail()
    {
        return view('cases/casesdetail');
    }

    public function caseLst()
    {
        $page = input('post.page') ? input('post.page') : 1;

        $pagesize = input('post.pagesize');

        $focus = caseModel::get(function($query){
            $query->where('classify',18)->where('status','1')->field('id,title,thumbnail,description,createtime');
        });

        $cases = Db::name('news')->where('classify',19)->where('status','1')->order('createtime','desc')->field('id,title,createtime,description,thumbnail')->page($page,$pagesize)->select();
        //控制标题和描述的长度
        foreach ($cases as $key => $val) {
            $cases[$key]['title'] = mb_substr($val['title'],0,15,'utf-8');
            $cases[$key]['description'] = mb_substr($val['description'],0,40,'utf-8');
        }

        $count = Db::name('news')->where('classify',19)->where('status','1')->count();

        $pagenum = ceil($count/$pagesize);

        echo json_encode(['focus' => $focus,'cases' => $cases,'conut' => $count,'pagenum' => $pagenum]);
    }
}