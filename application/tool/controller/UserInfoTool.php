<?php
/**
 * 用户信息ID、OpenId工具类.
 * User: 萧俊介
 * Date: 2018/6/21
 * Time: 10:15
 */

namespace app\tool\server;


use app\api\model\UserInfo;
use app\tool\controller\BaseTool;


class UserInfoTool extends BaseTool
{

    /*
     * 用OpenId获取用户ID
     */
    function retrieveUIdByOpenId($openId)
    {
        $userInfoM = new UserInfo();
        return $userInfoM->where('open_id', $openId)->value('id');
    }

    /*
     * 用户ID获取OpenId
     */
    function retrieveOpenIdByUId($Id)
    {
        $userInfoM = new UserInfo();
        return $userInfoM->where('id', $Id)->value('open_id');
    }

}