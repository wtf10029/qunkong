<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/3/31
 * Time: 14:03
 */

namespace app\index\controller;

use think\Controller;
use think\Db;

class Common extends Controller
{
    //初始化方法
    public function _initialize()
    {
        $system = Db::name('system')->order('id','desc')->find();  //查询系统设置
        $this->assign(array(
            'system' => $system,
        ));
    }
}