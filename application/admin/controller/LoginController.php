<?php
/**
 * 登录控制器
 * User: 萧俊介
 * Date: 2018/7/23
 * Time: 下午5:49
 */

namespace app\admin\controller;


use think\Controller;

class LoginController extends Controller
{

    /*
     * 身份验证
     */
    function index()
    {
        $param = input('param.');
        if (!empty($param['type'])) {
            if ($param['name'] == 'admin' && $param['password'] == "8576") {
                session('login', 'login');
                $this->success('登录成功', 'admin/index/index');
            } else {
                $this->error('登录失败', 'admin/Login/index');
            }
        }
        $this->assign('name',config('config.project_name'));
        return $this->fetch();
    }


    /*
     * 退出
     */
    function out()
    {
        session(null);
        $this->success('退出成功', 'admin/Login/index');
    }


}