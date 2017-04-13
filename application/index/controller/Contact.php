<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/3/9
 * Time: 12:38
 */
namespace app\index\controller;

use think\Controller;
use think\Db;

class Contact extends Common
{
    public function system()
    {
        $system = Db::name('system')->order('id desc')->find();  //查找系统信息

        $ewm = Db::name('image')->where('classify',26)->order('createtime desc')->field('title,url')->find();  //查询二维码

        echo json_encode(['system' => $system,'ewm' => $ewm]);
    }

    public function message()
    {
        $data = input('post.') ? input('post.') : '';

        if ($data) {
            $data['create_time'] = date('Y-m-d H:i:s');
            $num = Db::name('message')->insert($data);

            if ($num) {
                echo json_encode(['status' => 'success']);
            } else {
                echo json_encode(['status' => 'error']);
            }
        } else {
            echo json_encode(['status' => '无内容']);
        }
    }
}