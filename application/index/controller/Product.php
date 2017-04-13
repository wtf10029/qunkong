<?php
namespace app\index\controller;

use think\Controller;
use think\Db;
use app\index\model\Product as productModel;

class Product extends Common
{
    public function index()
    {
        return view('product/product');
    }

    public function detail()
    {
        return view('product/productdetail');
    }

    public function productLst()
    {
        $system = productModel::get(function($query){
            $query->where('classify',15)->field('id,title,tag,thumbnail,description');
        });

        $product = productModel::all(function($query){
            $query->where('classify',16)->field('id,title,createtime,thumbnail,description');
        });
        //控制标题和描述的长度
        foreach ($product as $key => $val) {
            $product[$key]['title'] = mb_substr($val['title'],0,15,'utf-8');
            $product[$key]['description'] = mb_substr($val['description'],0,40,'utf-8');
        }

        $func = productModel::all(function($query){
            $query->where('classify',17)->field('id,title,thumbnail,description');
        });
        //控制标题和描述的长度
        foreach ($func as $key => $val) {
            $func[$key]['title'] = mb_substr($val['title'],0,12,'utf-8');
            $func[$key]['description'] = mb_substr($val['description'],0,120,'utf-8');
        }

        echo json_encode(['system' => $system,'product' => $product,'func' => $func]);
    }
}