<?php
namespace app\admin\controller;

/**
* 空控制器
*/
class Error extends Base
{
    public function index()
    {
        $this->error('非法操作!控制器不存在!',url('Index/index'));
    }
}
