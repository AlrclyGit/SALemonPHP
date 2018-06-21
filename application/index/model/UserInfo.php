<?php
/**
 * 用户信息模型.
 * User: 萧俊介
 * Date: 2018/5/30
 * Time: 11:27
 */

namespace app\index\model;


class UserInfo extends BaseModel
{

    protected $hidden = ['create_time', 'delete_time', 'update_time'];

}