<?php
/**
 * 入口控制器
 * User: 萧俊介
 * Date: 2018/1/30
 * Time: 16:46
 * Created by LemonPHP制作委员会.
 */

namespace app\api\controller;


class IndexController extends BaseController
{

    /*
     * 入口方法
     */
    public function index()
    {
        return 'index/index/index';
    }

}
