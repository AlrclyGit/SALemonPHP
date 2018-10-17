<?php
/**
 * Name: 基本控制器
 * User: 萧俊介
 * Date: 2018/1/25
 * Time: 18:30
 * Created by LemonPHP制作委员会.
 */

namespace app\api\controller;

use app\api\model\UserInfo;
use app\tool\controller\LoginTool;
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