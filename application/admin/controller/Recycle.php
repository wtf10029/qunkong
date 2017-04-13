<?php
namespace app\admin\controller;

use think\Db;

/**
* 回收站控制器
*/
class Recycle extends Base
{
    //用户回收站跳转
    public function user()
    {
        //查询回收站用户表
        $res = db('recycle_admin')->select();

        //查询角色表
        $role = db('role')->select();
        //重写$role数组
        $ro = '';
        foreach ($role as $key => $val) {
            $ro[$val['id']] = $val['name'];
        }

        $this->assign('role',$ro);
        $this->assign('res',$res);
        return view('recycle/user');
    }

    //恢复用户
    public function recoverUser($id)
    {
        //查询回收站用户信息表
        $res = db('recycle_admin')->where('id',$id)->find();

        //将要恢复的信息写入用户表
        $data = [
            'userid' => $res['userid'],
            'password' => $res['password'],
            'email' => $res['email'],
            'loginip' => $res['loginip'],
            'logintime' => $res['logintime'],
            'status' => $res['status']
        ];
        $admin = db('admin')->insert($data);

        if ($admin) {
            //查询用户表,得到用户id
            $rs = db('admin')->where('userid',$res['userid'])->field('id')->find();

            //写入用户角色
            $da = ['aid' => $rs['id'],'rid' => $res['rid']];
            db('admin_role')->insert($da);

            //删除回收站中的数据
            db('recycle_admin')->delete($id);

            $this->success('恢复成功!',url('Recycle/user'),2);
        }else{
            $this->error('网络原因,恢复失败!');
        }
    }

    //彻底删除用户
    public function thoroughDel()
    {
        $id = input('post.id');

        //删除数据
        $res = db('recycle_admin')->delete($id);

        if ($res) {
            return ['txt' => 'success'];
        }else{
            return ['txt' => 'error'];
        }

    }

    //图片回收站列表
    public function imgLst()
    {
        //查询回收站图片表
        $res = db('recycle_image')->select();

        //查询图片分类表
        $rec = db('category')->where('pid','4')->select();
        //重写分类数组
        $data = '';
        foreach ($rec as $key => $val) {
            $data[$val['id']] = $val['catename'];
        }

        $this->assign('class',$data);
        $this->assign('image',$res);
        return view('recycle/image');
    }

    //恢复图片方法
    public function recoverImg()
    {
        //判断是否为post数据
        if (request()->isPost()) {
            $riid = input('post.id');

            //查询回收站图片表
            $re = db('recycle_image')->where('id',$riid)->find();
            //删除id
            unset($re['id']);
            //图片数据插入图片表
            $imr = db('image')->insert($re);

            if ($imr) {
                //删除回收站图片信息
                db('recycle_image')->delete($riid);

                $this->success('恢复图片成功!');
            } else {
                $this->error('恢复图片失败!');
            }

            return;
        }

        $id = input('get.id');

        //查询图片表
        $res = db('recycle_image')->where('id',$id)->find();
        $cid = $res['classify'];
        $classify = db('category')->where('id',$cid)->find();
        $res['classify'] = $classify['catename'];
        return ['txt' => $res];
    }

    //彻底删除回收站图片
    public function delImg($id)
    {
        //得到图片路径
        $path = db('recycle_image')->where('id',$id)->field('name')->find();
        //删除数据表中的数据
        $res = db('recycle_image')->delete($id);

        if ($res) {
            //删除文件夹中的图片
            unlink("./uploads/images/master/".$path['name']);
            unlink("./uploads/images/list/".$path['name']);
            unlink("./uploads/images/thumb/".$path['name']);

            $this->success('删除成功!');
        } else {
            $this->error('删除失败!');
        }

    }

    //新闻中心回收站列表
    public function newsLst()
    {
        //查询回收站新闻表
        $res = Db::name('recycle_news')->select();

        //查询分类表
        $class = db('category')->select();
        //重写分类数组
        $classify = '';
        foreach ($class as $key => $val) {
            $classify[$val['id']] = $val['catename'];
        }

        $this->assign('classify',$classify);
        $this->assign('res',$res);
        return view('recycle/news');
    }

    //回收站新闻恢复
    public function recoverNews($id)
    {
        //查询回收站文章表
        $res = db('recycle_news')->where('id',$id)->find();

        //删除id
        unset($res['id']);

        //回收站数据插入新闻表
        $rec = db('news')->insert($res);

        //判断是否插入成功
        if ($rec) {
            //删除回收站新闻数据
            db('recycle_news')->delete($id);

            $this->success('恢复新闻成功!');
        } else {
            $this->error('网络原因,恢复失败!');
        }
        
    }

    //彻底删除新闻
    public function delNews()
    {
        $id = input('get.id');
        //得到图片路径
        $name = db('recycle_news')->where('id',$id)->field('thumb_name')->find();
        //删除数据表中的数据
        $res = db('recycle_news')->delete($id);

        if ($res) {
            //删除文件夹中的图片
            @unlink("./uploads/thumb/".$name['thumb_name']);

            $this->success('删除新闻成功!',url('Recycle/newsLst'),'',2);
        } else {
            $this->error('删除新闻失败!','','',2);
        }
    }

    //轮播图回收站列表
    public function bannerLst()
    {
        //查询回收站轮播表
        $res = Db::name('recycle_banner')->select();

        //查询分类表
        $class = db('category')->select();
        //重写分类数组
        $classify = '';
        foreach ($class as $key => $val) {
            $classify[$val['id']] = $val['catename'];
        }

        $this->assign('classify',$classify);
        $this->assign('res',$res);
        return view('recycle/banner');
    }

    //回收站轮播恢复
    public function recoverBanner($id)
    {
        //查询回收站轮播表
        $res = db('recycle_banner')->where('id',$id)->find();

        //删除id
        unset($res['id']);

        //回收站数据插入轮播表
        $rec = db('banner')->insert($res);

        //判断是否插入成功
        if ($rec) {
            //删除回收站轮播数据
            db('recycle_banner')->delete($id);

            $this->success('恢复轮播成功!');
        } else {
            $this->error('网络原因,恢复失败!');
        }
        
    }

    //彻底删除轮播
    public function delBanner($id)
    {
        //得到图片路径
        $name = db('recycle_banner')->where('id',$id)->field('imagename')->find();
        //删除数据表中的数据
        $res = db('recycle_banner')->delete($id);

        if ($res) {
            //删除文件夹中的图片
            unlink("./uploads/banner/".$name['imagename']);

            return ['txt' => 'success'];
        } else {
            return ['txt' => 'error'];
        }
    }

    //产品回收站列表
    public function productLst()
    {
        //查询回收站产品表
        $res = Db::name('recycle_product')->select();

        //查询分类表
        $class = db('category')->select();
        //重写分类数组
        $classify = '';
        foreach ($class as $key => $val) {
            $classify[$val['id']] = $val['catename'];
        }

        $this->assign('classify',$classify);
        $this->assign('res',$res);
        return view('recycle/product');
    }

    //回收站产品恢复
    public function recoverProduct($id)
    {
        //查询回收站产品表
        $res = db('recycle_product')->where('id',$id)->find();

        //删除id
        unset($res['id']);

        //回收站数据插入产品表
        $rec = db('product')->insert($res);

        //判断是否插入成功
        if ($rec) {
            //删除回收站产品数据
            db('recycle_product')->delete($id);

            $this->success('恢复产品成功!');
        } else {
            $this->error('网络原因,恢复失败!');
        }
        
    }

    //彻底删除产品
    public function delProduct()
    {
        //得到id
        $id = input('post.id');
        //得到图片路径
        $name = db('recycle_product')->where('id',$id)->field('thumb_name')->find();
        //删除数据表中的数据
        $res = db('recycle_product')->delete($id);

        if ($res) {
            //删除文件夹中的图片
            unlink("./uploads/thumb/".$name['thumb_name']);

            return ['txt' => 'success'];
        } else {
            return ['txt' => 'error'];
        }
    }

}

