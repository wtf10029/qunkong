<?php
namespace app\index\controller;

use think\Controller;
use think\Db;

class Service extends Common
{
    public function index()
    {
        return view('service/service');
    }
}