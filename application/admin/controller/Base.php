<?php
namespace app\admin\controller;

use think\Controller;

//基础继承类
class Base extends Controller
{
    //初始化方法
    public function _initialize()
    {
        //判断用户是否登录
        if (empty(session('admin_user'))) {
            $this->redirect('Pub/login');
        }

        //更新用户名
        $userid = session('admin_user.userid');
        $this->assign('username',$userid);

        //查询用户权限
        $aid = session('admin_user.id');
        $res = db('admin_role')->where('aid',$aid)->field('rid')->find();
        $rid = $res['rid'];
        $rec = db('role_node')->where('rid',$rid)->field('nid')->select();
        $nid = '';
        foreach ($rec as $key => $val) {
            $nid[] = $val['nid'];
        }
        $this->assign('nid',$nid);
    }

    //空操作
    public function _empty()
    {
        $this->redirect('Index/index');
    }
}