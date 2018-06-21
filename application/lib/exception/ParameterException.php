<?php
/**
 * 参数错误处理类.
 * User: 萧俊介
 * Date: 2018/4/25
 * Time: 11:15
 */

namespace app\lib\exception;


class ParameterException extends BaseException
{
    public $code = 20000;
    public $msg = "参数请求相关错误";
}