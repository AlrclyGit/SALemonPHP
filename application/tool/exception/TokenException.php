<?php
/**
 * 授权与权限异常处理类.
 * User: 萧俊介
 * Date: 2018/4/23
 * Time: 17:13
 */

namespace app\tool\exception;

class TokenException extends BaseException
{
    public $code = 10000;
    public $msg = '用户授权与权限相关错误';
}