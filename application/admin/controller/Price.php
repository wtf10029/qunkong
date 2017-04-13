<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/3/18
 * Time: 14:51
 */

namespace app\admin\controller;

use app\admin\model\Price as priceModel;

/**
 * Class 价格控制器
 * @package app\admin\controller
 */
class Price extends Base
{
    public function price()
    {
        $price = new priceModel();

        //如果是post发送过来的数据,我们就进行修改操作
        if (request()->isPost()) {
            $data = input('post.');

            $num = $price->save($data,['id' => 1]);  //修改价格

            if ($num >= 0) {
                $this->success('修改价格成功',url('Price/price'),'',2);
            } else {
                $this->error('修改价格失败','','',2);
            }
        }

        $res = $price->get();  //查询价格数据

        $this->assign(array(  //发送至模板
            'price' => $res,
        ));
        return view();
    }
}