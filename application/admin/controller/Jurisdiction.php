<?php
namespace app\admin\controller;

/**
* 权限管理控制器
*/
class Jurisdiction extends Base
{
    //权限跳转页面
    public function index()
    {
        //查询用户表
        $res = db('admin')->select();

        //查询角色用户关联表
        $admin_role = db('admin_role')->select();
        //重写$admin_role数组
        $ar = '';
        foreach ($admin_role as $key => $val) {
            $ar[$val['aid']] = $val['rid'];
        }
        
        //查询角色表
        $role = db('role')->select();
        //重写$role数组
        $ro = '';
        foreach ($role as $key => $va) {
            $ro[$va['id']] = $va['name'];
        }

        $this->assign('ar',$ar);
        $this->assign('ro',$ro);
        $this->assign('res',$res);
        return view('Jurisdiction/role');
    }

    //修改权限
    public function editrole($id)
    {
        //查询用户表
        $res = db('admin')->where('id',$id)->field('id,userid')->find();

        //查询用户角色关联表
        $admin_role = db('admin_role')->where('aid',$id)->field('rid')->find();

        //查询角色表
        $role = db('role')->field('id,name')->select();

        $this->assign('rid',$admin_role['rid']);
        $this->assign('res',$res);
        $this->assign('role',$role);
        return view('jurisdiction/edit');
    }

    //修改权限操作
    public function roledo()
    {
        if (request()->isPost()) {
            $aid = input('post.id');
            $rid = input('post.role');

            //更新用户角色表
            $data = ['rid' => $rid];
            $res = db('admin_role')->where('aid',$aid)->update($data);
            if ($res >= 0) {
                $this->success('更改角色成功',url('Jurisdiction/index'),2);
            }else{
                $this->error('网络原因,修改权限失败!','',2);
            }
        }
    }

    //用户组管理
    public function node()
    {
        //查询角色表
        $role = db('role')->select();

        //查询角色节点关联表
        $rn = db('role_node')->select();
        //重写关联数组
        $role_node = '';
        foreach ($rn as $key => $val) {
            $role_node[$val['rid']][] = $val['nid'];
        }

        //查询节点表
        $no = db('node')->select();
        //重写节点数组
        $node = '';
        foreach ($no as $key => $val) {
            $node[$val['id']] = $val['name'];
        }

        $this->assign('node',$node);
        $this->assign('role_node',$role_node);
        $this->assign('role',$role);
        return view('jurisdiction/node');
    }

    //修改用户组
    public function modify($id)
    {
        //判断是否post数据
        if (request()->isPost()) {
            //接受post传过来的数据
            $data = input('post.');

            //判断post数据是否为空
            if (empty($data)) {
                $this->error('角色权限不能为空!');
            }

            //删除角色节点关联表中要更改的数据
            $num = db('role_node')->where('rid',$id)->delete();

            if ($num) {
                if (!empty($data)) {
                    //添加新的节点数据
                    foreach ($data['nid'] as $key => $val) {
                        $da['rid'] = $id;
                        $da['nid'] = $val;
                        db('role_node')->insert($da);
                    }
                }

                $this->success('修改用户组成功!',url('Jurisdiction/node'));
            } else {
                $this->error('修改用户组失败!');
            }
            
            return;
        }
        //查询角色表
        $role = db('role')->where('id',$id)->find();

        //查询角色节点关联表
        $rn = db('role_node')->where('rid',$id)->select();
        //重写关联数组
        $rno = '';
        foreach ($rn as $key => $val) {
            $rno[] = $val['nid'];
        }

        //查询节点表
        $no = db('node')->select();

        $this->assign('no',$no);
        $this->assign('rno',$rno);
        $this->assign('role',$role);
        return view('jurisdiction/m_node');
    }
}