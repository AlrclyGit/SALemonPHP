<?php

/**
 * name: 工具层错误.
 * User: 萧俊介
 * Date: 2018/12/3
 * Time: 11:25 AM
 * Created by LemonPHP制作委员会.
 */

namespace app\tool\exception;

class ToolException extends BaseException
{
    public $code = 10000;
    public $msg = '工具级错误';
}