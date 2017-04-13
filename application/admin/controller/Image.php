<?php
namespace app\admin\controller;

use think\Db;

/**
* 图片管理控制器
*/
class Image extends Base
{
    //图片展示
    public function show()
    {
        //查询图片表
        $res = db('image')->select();

        //查询图片分类表
        $re = db('category')->where('pid','4')->select();

        $this->assign('classify',$re);
        $this->assign('image',$res);
        return view('image/lst');
    }

    //图片编辑
    public function edit()
    {
        //判断是否为post
        if (request()->isPost()) {
            //获得图片id
            $iid = input('post.id');
            //查询图片表,得到图片名
            $r = Db::name('image')->where('id',$iid)->field('name')->find();
            $old_name = $r['name'];

            $im = request()->file('image');
            //图片处理
            if (!empty($im)) {
                $image = \think\Image::open(request()->file('image'));
                //获取图片后缀
                $type = $image->type();
                //获取图片尺寸
                $width = $image->width();
                $height = $image->height();
                $size = $width.'x'.$height;
                //生成图片名称
                $rand = mt_rand(0,999999);
                $str = md5(sha1(time()).$rand);
                $saveName = substr($str,-5);
                //拼接图片名
                $name = $saveName.'.'.$type;
                //存储原图
                $image->save('./uploads/images/master/'.$saveName.'.'.$type);
                //生成原图路径
                $url = 'uploads/images/master/'.$saveName.'.'.$type;
                //存储缩略图
                $image->thumb(215,160,\think\Image::THUMB_CENTER)->save('./uploads/images/thumb/'.$saveName.'.'.$type);
                //生成缩略图路径
                $thumb = 'uploads/images/thumb/'.$saveName.'.'.$type;
                //存储列表图
                $image->thumb(234,200,\think\Image::THUMB_CENTER)->save('./uploads/images/list/'.$saveName.'.'.$type);
                //生成列表图片路径
                $lst = 'uploads/images/list/'.$saveName.'.'.$type;
                //生成存储数组
                $data = [
                    'name' => $name,
                    'title' => input('post.title'),
                    'description' => input('post.description'),
                    'url' => $url,
                    'type' => $type,
                    'size' => $size,
                    'lst_img' => $lst,
                    'thumb_img' => $thumb,
                    'createtime' => time()
                ];
                //删除图片
                unlink("./uploads/images/list/".$old_name);
                unlink("./uploads/images/master/".$old_name);
                unlink("./uploads/images/thumb/".$old_name);
            }else{
                $data = [
                    'title' => input('post.title'),
                    'description' => input('post.description'),
                    'createtime' => time()
                ];
            }

            //更新图片信息
            $up = db('image')->where('id',$iid)->update($data);

            //判断是否更新成功
            if ($up >= 0) {
                $this->success('修改成功!',url('Image/show'));
            } else {
                $this->error('修改失败!');
            }
            
            return;
        }

        $id = input('get.id');

        //查询图片表
        $res = db('image')->where('id',$id)->find();
        $cid = $res['classify'];
        $classify = db('category')->where('id',$cid)->find();
        $res['classify'] = $classify['catename'];
        return ['txt' => $res];
    }

    //添加图片
    public function add()
    {
        //判断是否为post数据
        if (request()->isPost()) {

            $im = request()->file('image');

            if (!empty($im)) {
                //图片处理
                $image = \think\Image::open(request()->file('image'));
                //获取图片后缀
                $type = $image->type();
                //获取图片尺寸
                $width = $image->width();
                $height = $image->height();
                $size = $width.'x'.$height;
                //生成图片名称
                $rand = mt_rand(0,999999);
                $str = md5(sha1(time()).$rand);
                $saveName = substr($str,-5);
                //拼接图片名
                $name = $saveName.'.'.$type;
                //存储原图
                $image->save('./uploads/images/master/'.$saveName.'.'.$type); 
                //生成原图路径
                $url = 'uploads/images/master/'.$saveName.'.'.$type;
                //存储缩略图
                $image->thumb(215,160,\think\Image::THUMB_CENTER)->save('./uploads/images/thumb/'.$saveName.'.'.$type);
                //生成缩略图路径
                $thumb = 'uploads/images/thumb/'.$saveName.'.'.$type;
                //存储列表图
                $image->thumb(234,200,\think\Image::THUMB_CENTER)->save('./uploads/images/list/'.$saveName.'.'.$type);
                //生成列表图片路径
                $lst = 'uploads/images/list/'.$saveName.'.'.$type;
                //生成存储数组
                $data = [
                    'name' => $name,
                    'classify' => input('post.classify'),
                    'title' => input('post.title'),
                    'description' => input('post.description'),
                    'url' => $url,
                    'type' => $type,
                    'size' => $size,
                    'lst_img' => $lst,
                    'thumb_img' => $thumb,
                    'createtime' => time()
                ];

                //将图片信息存入数据库
                $num = db('image')->insert($data);
            } else {
                $this->error('请添加图片!');
            }

            //判断是否插入成功
            if ($num) {
                $this->success('添加图片成功!',url('Image/show'));
            } else {
                $this->error('添加图片失败!');
            }
            
            return;
        }
        //查询图片分类表
        $res = db('category')->where('pid','4')->select();

        $this->assign('classify',$res);
        return view('image/add');
    }

    //图片删除
    public function del($id)
    {
        //获取要删除图片信息
        $res = db('image')->where('id',$id)->find();
        //删除结果集中的id
        unset($res['id']);

        //将图片信息放入回收站表
        $rec = db('recycle_image')->insert($res);

        //判断是否添加成功
        if ($rec) {
            //删除图片表中的数据
            db('image')->delete($id);

            $this->success('删除图片成功!');
        } else {
            $this->error('删除图片失败!');
        }
        
    }

    //图片分类方法
    public function classify($cl)
    {
        //查询图片表
        $res = db('image')->where('classify',$cl)->select();

        //查询当前分类
        $rec = db('category')->where('id',$cl)->field('catename')->find();
        $c_name = $rec['catename'];

        //查询广告位分类
        $re = Db::name('category')->where('pid','4')->select();

        $this->assign('classify',$re);
        $this->assign('c_name',$c_name);
        $this->assign('image',$res);
        return view('image/lst_img');
    }
}