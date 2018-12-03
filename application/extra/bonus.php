<?php
/**
 * name: 红包配置
 * User: 萧俊介
 * Date: 2018/12/3
 * Time: 12:05 PM
 * Created by LemonPHP制作委员会.
 */

return [

    'key' => 'youaremyonlywxyouaremyonlywxlove', // key为商户平台设置的密钥key
    'mch_id' => '1498398992', // 商户号
    'url' => 'https://api.mch.weixin.qq.com/mmpaymkttransfers/sendredpack', //URL
    'total_num' => 1, // 红包发放总人数
    'scene_id' => 'PRODUCT_2', //红包场景
    'send_name' => '', // 商户名称
    'wishing' => '', // 红包祝福语
    'act_name' => '', // 活动名称
    'remark' => '' //备注

];