<?php
namespace app\admin\controller;

use think\Db;

/**
* 新闻中心
*/
class News extends Base
{
    //新闻中心列表
    public function lst()
    {
        //查询新闻表
        $res = Db::name('news')->select();

        //查询分类表
        $class = Db::name('category')->select();
        //重写分类数组
        $classify = '';
        foreach ($class as $key => $val) {
            $classify[$val['id']] = $val['catename'];
        }

        $this->assign('classify',$classify);
        $this->assign('res',$res);
        return view('news/lst');
    }

    //添加新闻
    public function add()
    {
        if (request()->isPost()) {
            //对缩略图进行处理,并存入数据库

            //得到添加数组
            $data = [
                'title' => input('post.title'),
                'classify' => input('post.classify'),
                'tag' => input('post.tag'),
                'source' => input('post.source'),
                'bro_num' => input('post.bro_num'),
                'keywords' => input('post.keywords'),
                'description' => input('post.description'),
                'content' => input('post.editorValue'),
                'createtime' => time(),
                'user' => session('admin_user.userid'),
                'status' => '1'
            ];
            if (empty($data['content'])) {
                $data['content'] = '暂无数据';
            }

            //获取图片对象
            $im = request()->file('image');

            //如果图片对象存在,进行图片处理
            if ($im) {

                //打开图片文件
                $image = \think\Image::open($im);
                //得到图片后缀
                $type = $image->type();
                //拼写图片路径
                $path = "uploads/thumb/";
                //干扰数字
                $num = mt_rand(0,99);
                //生成图片存储名称
                $saveName = time().$num;
                //图片名加后缀
                $sname = $saveName.'.'.$type;
                //得到最终路径+名称+后缀
                $imagePath = $path.$saveName.'.'.$type;
                //存储至路径中
                $image->save('./uploads/thumb/'.$saveName.'.'.$type);

                //数组$data补充图片信息
                $data['thumbnail'] = $imagePath;
                $data['thumb_name'] = $sname;
            }

            //添加新闻内容
            $res = Db::name('news')->insert($data);
            //判断新闻添加是否成功
            if ($res) {
                $this->success('添加新闻成功!',url('News/lst'));
            } else {
                $this->error('添加新闻失败!');
            }

            return;
        }

        //查询新闻分类
        $category = Db::name('category')->select();  //查询所有分类

        $cate = $this->sort($category);  //调用排序方法

        $this->assign(array(
            'category' => $cate,
        ));
        return view('news/add');
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
            //查询新闻表中的数据
            $data = Db::name('news')->where('id',$val)->find();
            //删除id
            unset($data['id']);

            //删除缩略图
            @unlink('./'.$data['thumbnail']);

            //将数据存入回收站
            $res = Db::name('recycle_news')->insert($data);

            //删除新闻表中的数据
            $re = Db::name('news')->delete($val);
        }

        $this->success("删除新闻成功!");
    }

    //单个删除
    public function del($id)
    {
        //查询新闻表中的数据
        $data = Db::name('news')->where('id',$id)->find();
        //删除id
        unset($data['id']);

        //将数据存入回收站
        $res = Db::name('recycle_news')->insert($data);

        if ($res) {
            //删除新闻表中的数据
            $re = Db::name('news')->delete($id);

            //删除缩略图
            @unlink('./'.$data['thumbnail']);

            $this->success('删除新闻成功!');
        } else {
            $this->error('删除新闻失败!');
        }
        
    }

    //编辑新闻
    public function edit($id)
    {
        if (request()->isPost()) {
            //得到需要更新的数组
            $data = input('post.');
            //更新时间写入数组
            $data['createtime'] = time();
            //删除新闻内容
            unset($data['editorValue']);
            //新闻内容以新的key值重新写入数组
            $data['content'] = input('post.editorValue');

            if (empty($data['content'])) {
                $data['content'] = '暂无数据';
            }

            //获取图片对象
            $im = request()->file('image');

            //如果图片对象存在,进行图片处理
            if ($im) {
                //打开图片文件
                $image = \think\Image::open($im);
                //编辑缩略图
                //得到图片后缀
                $type = $image->type();
                //拼写图片路径
                $path = "uploads/thumb/";
                //干扰数字
                $num = mt_rand(0,99);
                //生成图片存储名称
                $saveName = time().$num;
                //得到最终路径+名称+后缀
                $imagePath = $path.$saveName.'.'.$type;
                //图片名加后缀
                $sname = $saveName.'.'.$type;
                //存储至路径中
                $image->save('./uploads/thumb/'.$saveName.'.'.$type);
                //图片路径写入数组
                $data['thumbnail'] = $imagePath;
                //存入图片名称
                $data['thumb_name'] = $sname;
                //删除原缩略图
                $th = Db::name('news')->where('id',$id)->field('thumb_name')->find();
                $thumb_name = $th['thumb_name'];
                if ($thumb_name) {
                    @unlink("./uploads/thumb/".$thumb_name);
                }
            }

            //更新新闻信息
            $re = db('news')->where('id',$id)->update($data);
            if ($re >= 0) {
                $this->success('修改新闻成功!',url('News/lst'),'',2);
            }else{
                $this->error('修改新闻失败!');
            }

            return;
        }

        //查询新闻表
        $res = Db::name('news')->where('id',$id)->find();

        //查询新闻分类
        $category = Db::name('category')->select();  //查询所有分类

        $cate = $this->sort($category);  //调用排序方法

        $this->assign(array(
            'category' => $cate,
            'res' => $res,
        ));
        return view('news/edit');
    }

    //是否显示
    public function show()
    {
        $id = input('post.id');

        //查询新闻表
        $res = Db::name('news')->where('id',$id)->field('status')->find();
        $status = $res['status'];

        if ($status == '1') {
            $st['status'] = '2';
            $num = Db::name('news')->where('id',$id)->update($st);
            if ($num == '1') {
                return ['txt' => 'blank'];
            }
        } elseif($status == '2') {
            $st['status'] = '1';
            $num = Db::name('news')->where('id',$id)->update($st);
            if ($num == '1') {
                return ['txt' => 'show'];
            }
        }
    }

    //栏目排序方法
    public function sort($data,$pid=2,$level=0)
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


}