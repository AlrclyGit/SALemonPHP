<?php
/**
 * 工具模块异常处理类.
 * User: 萧俊介
 * Date: 2018/5/29
 * Time: 11:56
 */

namespace app\lib\exception;


class ToolException extends BaseException
{
    public $code = 30000;
    public $msg = '工具模块相关错误';
}