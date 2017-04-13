<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/3/6
 * Time: 15:08
 */
namespace app\admin\controller;

use think\Db;

class Message extends Base
{
    public function lst()
    {
        $message = Db::name('message')->order('id desc')->select();

        $this->assign(array(
            'message' => $message,
        ));
        return view();
    }

    public function getCon()
    {
        $id = input('post.id');

        $con = Db::name('message')->where('id',$id)->find();

        return ['txt' => $con['content']];
    }
}