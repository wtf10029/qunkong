<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/3/18
 * Time: 14:21
 */

namespace app\index\controller;


use think\Controller;
use app\index\model\Price as priceModel;

class Price extends Common
{
    public function price()
    {
        return view();
    }

    public function priceDetail()
    {
        //实例化模型
        $price = new priceModel();

        $res = $price->get(1);  //获得价格信息
        unset($res['id']);  //删除获取的id字段

        echo json_encode(['price' => $res]);
    }
}