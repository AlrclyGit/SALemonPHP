<?php
/**
 * Name: 用户信息ID、OpenId工具类.
 * User: 萧俊介
 * Date: 2018/6/21
 * Time: 10:15
 */

namespace app\tool\controller;


use app\tool\model\UserInfo;

class UserInfoTool extends BaseTool
{

    /*
     * 用OpenId获取用户ID
     */
    static public function retrieveUIdByOpenId($openId)
    {
        return UserInfo::where('open_id', $openId)->value('id');
    }

    /*
     * 用户ID获取OpenId
     */
    static function retrieveOpenIdByUId($uID)
    {
        return UserInfo::where('id', $uID)->value('open_id');
    }

}