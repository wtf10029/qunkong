<?php
namespace app\admin\controller;

use think\Db;

//后台首页控制器
class Index extends Base
{
    //首页跳转
    public function index()
    {
        //获取系统信息
        $php_version = PHP_VERSION;
        $sys = PHP_OS;
        $server = $_SERVER ['SERVER_SOFTWARE'];
        $time = get_cfg_var("max_execution_time")."秒";
        $memory = get_cfg_var ("memory_limit")?get_cfg_var("memory_limit"):"无";

        //将系统信息存入数组
        $system = [
            'php版本:' => $php_version,
            '运行系统:' => $sys,
            '服务器:' => $server,
            '最大执行时间:' => $time,
            '占用最大内存:' => $memory
        ];

        //查询新闻表
        $news = Db::name('news')->limit(5)->order('createtime','desc')->select();


        //查询用户数量
        $admin_num = db('admin')->count('id');

        //查询新闻数量
        $news_num = db('news')->count('id');

        //查询留言数量
        $product_num = Db::name('message')->count('id');

        //查询图片数量
        $image_num = db('image')->count('id');

        //查询新闻分类表
        $cate = Db::name('category')->where('pid','2')->field('id,catename')->select();
        //重写些分类数组
        $category = '';
        foreach ($cate as $key => $val) {
            $category[$val['id']] = $val['catename'];
        }

        $ti = time();  //获取当前时间戳

        $this->assign('catename',$category);
        $this->assign('news',$news);
        $this->assign('admin_num',$admin_num);
        $this->assign('news_num',$news_num);
        $this->assign('product_num',$product_num);
        $this->assign('image_num',$image_num);
        $this->assign('system',$system);
        $this->assign('time',$ti);
        return view('index/index');
    }
}