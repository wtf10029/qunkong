<?php
namespace app\admin\controller;

use think\Db;

/**
* 友情链接
*/
class Link extends Base
{
    //列表页
    public function lst()
    {
        $res = Db::name('link')->order('sort','desc')->select();  //查询所有友情链接

        $this->assign(array(
            'res' => $res
        ));
        return view();
    }

    //排序
    public function sort()
    {
        if (request()->isPost()) {
            $data = input('post.');  //获取表单数据
            unset($data['dynamic-table_length']);  //删除无用字段

            foreach ($data as $key => $val) {
                $up = ['sort' => $val];
                $num = Db::name('link')->where('id',$key)->update($up);
            }

            $this->success('排序成功',url('Link/lst'));
        }
    }

    //是否显示
    public function show()
    {
        $id = input('post.id');

        //查询新闻表
        $res = Db::name('link')->where('id',$id)->field('status')->find();
        $status = $res['status'];

        if ($status == '1') {
            $st['status'] = '2';
            $num = Db::name('link')->where('id',$id)->update($st);
            if ($num == '1') {
                return ['txt' => 'blank'];
            }
        } elseif($status == '2') {
            $st['status'] = '1';
            $num = Db::name('link')->where('id',$id)->update($st);
            if ($num == '1') {
                return ['txt' => 'show'];
            }
        }
    }

    //添加链接
    public function add()
    {
        if (request()->isPost()) {
            $data = input('post.');  //接收表单数据
            $data['createtime'] = time();

            $num = Db::name('link')->insert($data);  //添加链接数据

            if ($num) {
                $this->success('添加链接成功',url('Link/lst'));
            } else {
                $this->error('添加链接失败');
            }

            return;
        }
        return view();
    }

    //修改链接
    public function edit($id)
    {
        if (request()->isPost()) {
            $data = input('post.');  //接收表单数据

            $num = Db::name('link')->where('id',$id)->update($data);  //更新链接数据

            if ($num >= 0) {
                $this->success('修改链接成功',url('Link/lst'));
            } else {
                $this->error('修改链接失败');
            }

        }

        $res = Db::name('link')->where('id',$id)->find();  //查询修改链接数据

        $this->assign(array(
            'link' => $res
        ));
        return view();
    }

    //删除链接
    public function del($id)
    {
        $num = Db::name('link')->delete($id);

        if ($num) {
            $this->success('删除链接成功',url('Link/lst'));
        } else {
            $this->error('删除链接失败');
        }

    }


}