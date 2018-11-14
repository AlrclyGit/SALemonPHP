<?php
/**
 * 后台管理控制器
 * User: 萧俊介
 * Date: 2018/1/4
 * Time: 16:37
 */

namespace app\admin\controller;


class IndexController extends BaseController
{


    /*
     * 框架
     */
    function index()
    {
        $this->assign('name', config('config.project_china_name'));
        return $this->fetch();
    }


    /*
     * 欢迎页
     */
    function welcome()
    {
        $this->assign('name', config('config.project_china_name'));
        $this->assign('server', $_SERVER);
        return $this->fetch();
    }

}