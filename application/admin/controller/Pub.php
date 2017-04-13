<?php
namespace app\admin\controller;

use think\Controller;

//公共控制器
class Pub extends Controller
{
    //登录跳转方法
    public function login()
    {
        return view('Pub/login');
    }

    //登录处理方法
    public function logindo()
    {
        $name = input('post.name');
        $password = MD5(input('post.password'));

        //查询后台用户表
        $res = db('admin')->where('userid',$name)->field('id,userid,password,status')->find();

        //判断用户名是否存在
        if (empty($res)) {
            $this->error('用户名不存在!');
        }

        //判断密码是否正确
        if ($password != $res['password']) {
            $this->error('密码错误!');
        }

        //判断用户是否被禁用
        if ($res['status'] == 2) {
            $this->error('该用户已经被禁用!');
        }

        //刪除$res中的密码
        unset($res['password']);
        //将用户信息存入session
        session('admin_user',$res);

        //更新登录时间和登录ip
        $ip = getIp();
        $time = time();
        $data = [
                    'logintime' => $time,
                    'loginip' => $ip
                ];
        db('admin')->where('id',$res['id'])->update($data);

        $this->redirect('admin/Index/index');
    }

    //注销登录
    public function loginout()
    {
        //删除session
        session('admin_user',null);
        //跳转登录页
        $this->redirect('Pub/login');
    }
}