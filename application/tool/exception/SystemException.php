<?php

/**
 * Name: 系统级错误.
 * User: 萧俊介
 * Date: 2018/10/12
 * Time: 11:13 AM
 */

namespace app\tool\exception;


class SystemException extends BaseException
{
    public $code = 100000;
    public $msg = '系统级错误';
}