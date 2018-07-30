<?php
/**
 * Created by LemonPHP制作委员会.
 * 入口控制器
 * User: 萧俊介
 * Date: 2018/1/30
 * Time: 16:46
 */

namespace app\index\controller;

use app\tool\controller\JsSdkTool;

class IndexController extends SessionBase
{

    /*
     * 主要操作
     */
    public function index()
    {
        return 'index/index/index';
    }

    /*
     * 通过config接口注入权限验证配置操作
     */
    public function config()
    {
        $jsSdkTool = new JsSdkTool();
        $data = $jsSdkTool->Config();
        return saReturn(0, 'OK', $data);
    }

}
