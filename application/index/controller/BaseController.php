<?php
/**
 * name: 基本控制器
 * User: 萧俊介
 * Date: 2019/3/15
 * Time: 4:37 PM
 * Created by LemonPHP制作委员会.
 */

namespace app\index\controller;


use app\tool\controller\LoginTool;
use app\tool\model\UserInfo;
use think\Controller;

class BaseController extends Controller
{

    /*
 * 构造函数
 */
    public function __construct()
    {
        parent::__construct();
        // 判断用户权限
        if (session('open_id')) {
            $userInfoM = new UserInfo();
            $userInfo = $userInfoM->where('open_id', session('open_id'))->find();
            if (!$userInfo) {
                (new LoginTool())->getWeChatUserInfo();
            }
        } else {
            (new LoginTool())->getWeChatUserInfo();
        }
    }

}