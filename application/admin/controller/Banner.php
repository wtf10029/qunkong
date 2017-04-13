<?php
namespace app\admin\controller;

use think\Db;

/**
* 轮播图
*/
class Banner extends Base
{
    //轮播图列表
    public function lst()
    {
        //查询轮播表
        $res = Db::name('banner')->select();

        //查询分类表
        $class = Db::name('category')->select();
        //重写分类数组
        $classify = '';
        foreach ($class as $key => $val) {
            $classify[$val['id']] = $val['catename'];
        }

        $this->assign('classify',$classify);
        $this->assign('res',$res);
        return view('banner/lst');
    }

    //添加轮播
    public function add()
    {
        if (request()->isPost()) {
            //对缩略图进行处理,并存入数据库

            //得到添加数组
            $data = [
                'title' => input('post.title'),
                'classify' => input('post.classify'),
                'link' => input('post.link'),
                'rank' => input('post.rank'),
                'description' => input('post.description'),
                'createtime' => time(),
                'user' => session('admin_user.userid'),
            ];

            //获取图片对象
            $im = request()->file('image');

            //如果图片对象存在,进行图片处理
            if (!empty($im)) {

                //打开图片文件
                $image = \think\Image::open(request()->file('image'));
                //得到图片后缀
                $type = $image->type();
                //得到图片的宽
                $width = $image->width();
                //得到图片的高
                $height = $image->height();
                //拼写图片路径
                $path = "uploads/banner/";
                //干扰数字
                $num = mt_rand(0,99);
                //生成图片存储名称
                $saveName = time().$num;
                //图片名加后缀
                $sname = $saveName.'.'.$type;
                //得到最终路径+名称+后缀
                $imagePath = $path.$saveName.'.'.$type;
                //存储至路径中
                $image->save('./uploads/banner/'.$saveName.'.'.$type);

                //数组$data补充图片信息
                $data['path'] = $imagePath;
                $data['imagename'] = $sname;
                $data['size'] = $width.'*'.$height;
            } else {
                $this->error('图片不可以为空!');die;
            }

            //添加轮播内容
            $res = Db::name('banner')->insert($data);
            //判断轮播添加是否成功
            if ($res) {
                $this->success('添加轮播成功!',url('banner/lst'));
            } else {
                $this->error('添加轮播失败!');
            }

            return;
        }

        //查询轮播分类
        $classify = Db::name('category')->where('pid','3')->select();

        //查询最大轮播排序
        $maxRank = Db::name('banner')->max('rank');

        $this->assign('maxRank',$maxRank);
        $this->assign('classify',$classify);
        return view('banner/add');
    }

    //全选删除
    public function delAll()
    {
        //计算数组元素数量
        $num = count(input('post.'));

        if ($num == 1) {
            $this->error('至少选择一行!');
        }

        //选择check数组
        $pid = input('post.')['check'];

        foreach ($pid as $key => $val) {
            //查询轮播表中的数据
            $data = Db::name('banner')->where('id',$val)->find();
            //删除id
            unset($data['id']);

            //将数据存入回收站
            $res = Db::name('recycle_banner')->insert($data);

            //删除轮播表中的数据
            $re = Db::name('banner')->delete($val);
        }

        $this->success("删除轮播成功!");
    }

    //单个删除
    public function del($id)
    {
        //查询轮播表中的数据
        $data = Db::name('banner')->where('id',$id)->find();
        //删除id
        unset($data['id']);

        //将数据存入回收站
        $res = Db::name('recycle_banner')->insert($data);

        if ($res) {
            //删除轮播表中的数据
            $re = Db::name('banner')->delete($id);

            $this->success('删除轮播成功!');
        } else {
            $this->error('删除轮播失败!');
        }
        
    }

    //编辑轮播
    public function edit($id)
    {
        if (request()->isPost()) {
            //得到需要更新的数组
            $data = input('post.');
            //更新时间写入数组
            $data['createtime'] = time();

            //获取图片对象
            $im = request()->file('image');

            //如果图片对象存在,进行图片处理
            if (!empty($im)) {
                //打开图片文件
                $image = \think\Image::open(request()->file('image'));
                //编辑缩略图
                //得到图片后缀
                $type = $image->type();
                //得到图片的宽
                $width = $image->width();
                //得到图片的高
                $height = $image->height();
                //拼写图片路径
                $path = "uploads/banner/";
                //干扰数字
                $num = mt_rand(0,99);
                //生成图片存储名称
                $saveName = time().$num;
                //得到最终路径+名称+后缀
                $imagePath = $path.$saveName.'.'.$type;
                //图片名加后缀
                $sname = $saveName.'.'.$type;
                //存储至路径中
                $image->save('./uploads/banner/'.$saveName.'.'.$type);
                //图片路径写入数组
                $data['path'] = $imagePath;
                //存入图片名称
                $data['imagename'] = $sname;
                //存入图片尺寸
                $data['size'] = $width.'*'.$height;
                //删除原缩略图
                $th = Db::name('banner')->where('id',$id)->field('imagename')->find();
                $imagename = $th['imagename'];
                if (!empty($imagename)) {
                    unlink("./uploads/banner/".$imagename);
                }
            }

            //更新轮播信息
            $re = db('banner')->where('id',$id)->update($data);
            if ($re >= 0) {
                $this->success('修改轮播成功!',url('banner/lst'),2);
            }else{
                $this->error('修改轮播失败!');
            }

            return;
        }

        //查询轮播表
        $res = Db::name('banner')->where('id',$id)->find();

        //查询轮播分类
        $classify = Db::name('category')->where('pid','3')->select();

        //查询最大轮播排序
        $maxRank = Db::name('banner')->max('rank');

        $this->assign('maxRank',$maxRank);

        $this->assign('classify',$classify);
        $this->assign('res',$res);
        return view('banner/edit');
    }

    //是否显示
    public function show()
    {
        $id = input('post.id');

        //查询轮播表
        $res = Db::name('banner')->where('id',$id)->field('status')->find();
        $status = $res['status'];

        if ($status == '1') {
            $st['status'] = '2';
            $num = Db::name('banner')->where('id',$id)->update($st);
            if ($num == '1') {
                return ['txt' => 'blank'];
            }
        } elseif($status == '2') {
            $st['status'] = '1';
            $num = Db::name('banner')->where('id',$id)->update($st);
            if ($num == '1') {
                return ['txt' => 'show'];
            }
        }
    }


}