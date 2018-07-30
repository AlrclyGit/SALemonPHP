<?php
/**
 * Created by LemonPHP制作委员会.
 * User: 萧俊介
 * Date: 2018/7/30
 * Time: 上午11:33
 */

namespace app\tool\validate;


class ToKenV extends BaseValidate
{

    // 参数是否正确
    protected $rule = [
        'code' => 'require|isNotEmpty'
    ];

    // 抛出的错误内容
    protected $message = [
        'code'=>'没有Code还想获取ToKen，Tan90°'
    ];

}