<?php
/**
 * 基本控制器
 * User: 萧俊介
 * Date: 2018/1/25
 * Time: 18:30
 */

namespace app\index\controller;

use app\tool\controller\LoginTool;
use app\model\UserInfo;
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
//        session('open_id', 'omhRa1ausF1sy1KI8VMVh2h8JTqo');
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