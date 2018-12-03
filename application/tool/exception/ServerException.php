<?php

/**
 * Name: 服务层错误.
 * User: 萧俊介
 * Date: 2018/4/23
 * Time: 17:13
 */

namespace app\tool\exception;

class ServerException extends BaseException
{
    public $code = 20000;
    public $msg = '服务级错误';
}