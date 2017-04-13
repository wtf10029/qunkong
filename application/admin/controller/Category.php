<?php
namespace app\admin\controller;

use think\Db;

/**
* 栏目管理
*/
class Category extends Base
{
    //栏目列表
    public function lst()
    {
        $category = Db::name('category')->select();  //查询所有分类

        $cate = $this->sort($category);  //调用排序方法

        $this->assign(array(
            'cate' => $cate,
        ));
        return view('category/lst');
    }

    //添加栏目
    public function add()
    {
        if (request()->isPost()) {
            $data = input('post.');

            //插入栏目表
            $res = Db::name('category')->insert($data);

            if ($res) {
                $this->success('添加栏目成功!',url('Category/lst'));
            } else {
                $this->error('添加失败!');
            }

            return;
        }

        $category = Db::name('category')->select();  //查询所有分类

        $cate = $this->sort($category);  //调用排序方法

        $this->assign(array(
            'category' => $cate,
        ));
        return view('category/add');
    }

    //栏目排序方法
    public function sort($data,$pid=0,$level=0)
    {
        static $arr = array();  //定义静态空数组

        foreach ($data as $k => $v) {
            if ($v['pid'] == $pid) {
                $v['level'] = $level;
                $arr[] = $v;
                $this->sort($data,$v['id'],$level+1);  //递归调用排序方法,遍历出本栏目的下级栏目
            }
        }
        return $arr;
    }

    //编辑栏目
    public function edit($id)
    {
        if (request()->isPost()) {
            //获取post数据
            $data = input('post.');

            //更新栏目信息
            $num = Db::name('category')->where('id',$id)->update($data);

            //判断是否更新成功
            if ($num >= 0) {
                $this->success('编辑栏目成功!',url('Category/lst'));
            } else {
                $this->error('编辑栏目失败!');
            }

            return;
        }

        $category = Db::name('category')->select();  //查询所有栏目信息

        $cate = $this->sort($category);  //调用排序方法

        $cateres = Db::name('category')->where('id',$id)->find();  //查询当前栏目信息

        $this->assign(array(
            'category' => $cate,
            'cateres' => $cateres,
        ));
        return view('category/edit');
    }

    //删除栏目
    public function del($id)
    {
        $cateres = Db::name('category')->select();  //查询所有的分类信息

        $sonid = $this->getChildId($cateres,$id);  //调用getChildId方法,递归查找子分类id

        if (!empty($sonid)) {
            $num = Db::name('category')->delete($sonid);  //首先删除子分类
        } else {
            $res = Db::name('category')->delete($id);  //直接删除分类
            if ($res > 0) {
                $this->success('删除栏目成功!',url('Category/lst'));
            } else {
                $this->error('网络原因,删除栏目失败!');
            }
        }

        if (isset($num) && $num > 0) {
            $res = Db::name('category')->delete($id);  //直接删除分类
            if ($res > 0) {
                $this->success('删除栏目成功!',url('Category/lst'));
            } else {
                $this->error('网络原因,删除栏目失败!');
            }
        }
    }

    //删除子栏目
    public function getChildId($cateres,$cateid)
    {
        static $arr = array();  //定义一个空的静态数组

        foreach ($cateres as $key => $val) {
            if ($val['pid'] == $cateid) {
                $arr[] = $val['id'];
                $this->getChildId($cateres,$val['id']);  //递归查找子分类id
            }
        }
        return $arr;
    }

}