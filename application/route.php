<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006~2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------

return [
    '__pattern__' => [
        'name' => '\w+',
    ],
    '[hello]'     => [
        ':id'   => ['index/hello', ['method' => 'get'], ['id' => '\d+']],
        ':name' => ['index/hello', ['method' => 'post']],
    ],

    'index' => ['index/Index/index',['method' => 'post|get|put'],],
    'aboutus' => ['index/Aboutus/index',['method' => 'post|get|put'],],
    'product' => ['index/Product/index',['method' => 'post|get|put'],],
    'productdetail' => ['index/Product/detail',['method' => 'post|get|put'],],
    'cases' => ['index/Cases/index',['method' => 'post|get|put'],],
    'casesdetail' => ['index/Cases/detail',['method' => 'post|get|put'],],
    'news' => ['index/News/index',['method' => 'post|get|put'],],
    'newsdetail' => ['index/News/detail',['method' => 'post|get|put'],],
    'history' => ['index/History/index',['method' => 'post|get|put'],],
    'proud' => ['index/Proud/index',['method' => 'post|get|put'],],
    'service' => ['index/Service/index',['method' => 'post|get|put'],],
    'ajaxhistory' => ['index/History/history',['method' => 'post|get|put'],],
    'productLst' => ['index/Product/productLst',['method' => 'post|get|put'],],
    'caseLst' => ['index/Cases/caseLst',['method' => 'post|get|put'],],
    'newsLst' => ['index/News/newsLst',['method' => 'post|get|put'],],
    'proudLst' => ['index/Proud/proudLst',['method' => 'post|get|put'],],
    'system' => ['index/Contact/system',['method' => 'post|get|put'],],
    'message' => ['index/Contact/message',['method' => 'post|get|put'],],
    'ajaxIndex' => ['index/Index/ajaxIndex',['method' => 'post|get|put'],],
    'detailLst' => ['index/Detail/detailLst',['method' => 'post|get|put'],],
    'detail' => ['index/Detail/detail',['method' => 'post|get|put'],],
    'price' => ['index/Price/price',['method' => 'post|get|put'],],
    'pricedetail' => ['index/Price/priceDetail',['method' => 'post|get|put'],],

];
