<?php
namespace app\admin\controller;

/**
* 系统管理控制器
*/
class System extends Base
{
    //缓存清理跳转方法
    public function cache()
    {
        $dir = 'E:\phpstudy\WWW\qunkong\runtime';
        $size = directory_size($dir);
        $si = getsize($size);

        $this->assign('size',$si);
        return view('system/cache');
    }

    //缓存清理
    public function cachedo()
    {
        $dir = 'E:\phpstudy\WWW\qunkong\runtime';
        $res = delCatalogue($dir);
        $this->redirect('System/cache');
    }

    //站点设置
    public function site()
    {
        if (request()->isPost()) {
            $data = input('post.');
            $data['modify_time'] = time();
            $data['user'] = session('admin_user.userid');

            //修改站点信息
            $res = db('system')->insert($data);

            if ($res) {
                $this->success('修改成功!');
            } else {
                $this->error('修改失败!');
            }

            return;
        }

        $re = db('system')->order('id','desc')->limit(1)->select();

        if (!empty($re)) {
            $this->assign('site',$re[0]);
        }

        return view('system/site');
    }
}