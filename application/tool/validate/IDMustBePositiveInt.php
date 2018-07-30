<?php
/**
 * Created by LemonPHP制作委员会.
 * ID为正整数验证器.
 * User: 萧俊介
 * Date: 2018/5/2
 * Time: 9:52
 */

namespace app\tool\validate;

class IDMustBePositiveInt extends BaseValidate
{

    //检查数字是否为正整数
    protected $rule = [
        'id' => 'require|isPositiveInteger'
    ];

    //抛出的错误内容
    protected $message = [
        'id' => 'id必须是正整数'
    ];

}
