<?php
/**
 * name: 主要控制器
 * User: 萧俊介
 * Date: 2019/3/15
 * Time: 6:46 PM
 * Created by LemonPHP制作委员会.
 */

namespace app\index\controller;


class IndexController extends BaseController
{

    /**
     * 入口方法
     */
    public function index()
    {
        return $this->fetch();
    }

}