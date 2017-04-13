<?php
namespace app\admin\controller;

use think\Db;

//后台用户控制器
class User extends Base
{
    //用户列表
    public function lst()
    {
        //查询用户表
        $res = db('admin')->order('id', 'asc')->select();

        //查询角色用户关联表
        $admin_role = db('admin_role')->select();
        //重写$admin_role数组
        $ar = '';
        foreach ($admin_role as $key => $val) {
            $ar[$val['aid']] = $val['rid'];
        }
        
        //查询角色表
        $role = db('role')->field('id,name')->select();
        //重写$role数组
        $ro = '';
        foreach ($role as $key => $va) {
            $ro[$va['id']] = $va['name'];
        }

        $this->assign('ar',$ar);
        $this->assign('ro',$ro);
        $this->assign('res',$res);
        return view('user/lst');
    }

    //账号停用
    public function stop()
    {
        $id = input('post.id');

        //查询会员表
        $status = db('admin')->where('id',$id)->field('status')->find();

        if ($status['status'] == 1) {
            $data['status'] = 2;
            db('admin')->where('id',$id)->update($data);
            return ['txt'=>'已停用'];
        } elseif($status['status'] == 2) {
            $data['status'] = 1;
            db('admin')->where('id',$id)->update($data);
            return ['txt'=>'已启用'];
        }
        
    }

    //添加后台用户
    public function add()
    {
        //判断是否为post传参
        if (request()->isPost()) {
            $name = input('post.name');
            $email = input('post.email');
            $password = input('post.password');
            $pwd = input('post.pwd');
            $role = input('post.role');

            //查询用户表
            $res = db('admin')->where('userid',$name)->field('id')->find();
            //判断用户名是否存在
            if (!empty($res)) {
                $this->error('用户名存在!');
            }
            //判断两次密码是否一致
            if ($password != $pwd) {
                $this->error('两次密码不一致!');
            }

            $data = ['userid' => $name,'email' => $email,'password' => md5($password),'status' => '1'];
            $admin = db('admin')->insert($data);

            if ($admin) {
                $aid = db('admin')->where('userid',$name)->field('id')->find();
                $admin_role = ['aid' => $aid['id'],'rid' => $role];
                db('admin_role')->insert($admin_role);
                $this->success('添加成功!',url('User/lst'),2);
            }else{
                $this->error('网络原因,添加失败!');
            }
        }

        //如果不是post传过来的数据,跳转添加页面
        $role = db('role')->field('id,name')->select();
        $this->assign('role',$role);
        return view('User/add');
    }

    //编辑后台用户
    public function edit($id=1)
    {
        //查询用户表
        $res = db('admin')->where('id',$id)->field('id,userid,password,email')->find();
        
        //判断是否为post传输的数据
        if (request()->isPost()) {
            $uid = input('post.id');
            $userid = input('post.name');
            $password = input('post.password');
            $email = input('post.email');
            $role = input('post.role');

            //判断用户名是否存在
            $data = db('admin')->where('id',$uid)->find();
            if ($data['userid'] != $userid) {
                $da = db('admin')->where('userid',$userid)->find();
                if (!empty($da)) {
                    $this->error('用户名已存在!');
                }
            }

            //判断密码是否为空
            if (empty($password)) {
                $password = $data['password'];
            }else{
                $password = md5($password);
            }

            $admin = ['userid' => $userid,'password' => $password,'email' => $email];
            $rel = db('admin')->where('id',$uid)->update($admin);

            if ($rel >= 0) {
                $admin_role = ['rid' => $role];
                db('admin_role')->where('aid',$uid)->update($admin_role);
                $this->success('修改成功',url('User/lst'),2);
            }else{
                $this->error('网络原因,修改失败!');
            }
        }

        $role = db('role')->field('id,name')->select();
        $admin_role = db('admin_role')->where('aid',$id)->field('rid')->find();
        $this->assign('role',$role);
        $this->assign('rid',$admin_role['rid']);
        $this->assign('res',$res);
        return view('user/edit');
    }

    //删除后台用户
    public function del()
    {
        if (request()->isPost()) {
            $id = input('post.id');

            //查询用户表
            $admin = db('admin')->where('id',$id)->find();
            //查询用户角色表
            $admin_role = db('admin_role')->where('aid',$id)->find();

            //将用户信息存入回收站
            $data = [
                'userid' => $admin['userid'],
                'password' => $admin['password'],
                'email' => $admin['email'],
                'loginip' => $admin['loginip'],
                'logintime' => $admin['logintime'],
                'status' => $admin['status'],
                'rid' => $admin_role['rid']
            ];
            db('recycle_admin')->insert($data);

            //删除用户表和角色关联表数据
            $res = db('admin')->delete($id);
            db('admin_role')->where('aid',$id)->delete();
            if ($res) {
                return ['txt' => 'success'];
            }else{
                return ['txt' => 'error'];
            }
        }
    }

    //修改密码
    public function modify ()
    {
        if (request()->isPost()) {
            //得到post表单数据
            $old = md5(input('post.old'));
            $password = md5(input('post.password'));
            $pwd = md5(input('post.pwd'));

            //得到用户id
            $id = session('admin_user.id');

            //查询用户表
            $res = Db::name('admin')->where('id',$id)->field('password')->find();

            //判断旧密码是否填写正确
            if ($old != $res['password']) {
                $this->error('旧密码不正确!');
            }

            //判断新密码是否与旧密码一致
            if ($old == $password) {
                $this->error('新密码与旧密码一致,不可以更改!');
            }

            //判断两次新密码是否一样
            if ($password != $pwd) {
                $this->error('两次密码不一致!');
            }

            //填写更新数组
            $data['password'] = $password;

            //更新密码
            $num = Db::name('admin')->where('id',$id)->update($data);
            if ($num > 0) {
                $this->success('修改密码成功!',url('Index/index'));
            } else {
                $this->error('网络原因,修改失败!');
            }

            return;
        }
        return view('user/modify');
    }
}