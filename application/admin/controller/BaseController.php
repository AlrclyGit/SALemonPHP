<?php
/**
 * 后台公共控制器
 * User: 萧俊介
 * Date: 2018/1/4
 * Time: 16:37
 */

namespace app\admin\controller;

use think\Controller;
use think\Request;

class BaseController extends Controller
{

    /*
     * 构造函数
     */
    function __construct(Request $request = null)
    {
        parent::__construct($request);
        $this->isLogin();
    }

    /*
     * 身份验证方法
     */
    function isLogin()
    {
        if (!session('login')) {
            $this->error('你没有权限,请登录', 'admin/Login/index');
        }
    }


}