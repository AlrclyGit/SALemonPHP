<?php
/**
 * Created by LemonPHP制作委员会.
 * Token基本控制器
 * User: 萧俊介
 * Date: 2018/7/30
 * Time: 下午2:23
 */

namespace app\index\controller;


use app\lib\exception\TokenException;
use app\model\User;
use think\Controller;

class TokenBase extends Controller
{

    protected $openId;

    /*
     * 用户身验证方法
     */
    function __construct()
    {
        parent::__construct();
        // 根据Token来获取openId
        $this->openId = TokenTool::getCurrentOpenID();
        // 根据openId来查找用户数据，判断用户是否存在，如果不存在抛出异常
        $userM = new User();
        $user = $userM->where('open_id', $this->openId)->find();
        if (!$user) {
            throw new TokenException([
                'code' => 13001,
                'msg' => '缓存有数据，但用户不存在数据表',
            ]);
        }
    }
}