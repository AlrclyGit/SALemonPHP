<?php

/**
 * Name: 基本错误类.
 * User: 萧俊介
 * Date: 2018/4/23
 * Time: 17:09
 */

namespace app\tool\exception;

use think\Exception;

class BaseException extends Exception
{
    // 错误码
    public $code = 100000;
    // 错误具体信息
    public $msg = null;
    // 附带的参数
    public $data = null;

    /*
     * 通过构造方法设置默认值
     */
    public function __construct($params = [])
    {
        parent::__construct();
        if (is_array($params)) {
            if (array_key_exists('code', $params)) {
                $this->code = $params['code'];
            }
            if (array_key_exists('msg', $params)) {
                $this->msg = $params['msg'];
            }
            if (array_key_exists('data', $params)) {
                $this->data = $params['data'];
            }
        }
    }

}