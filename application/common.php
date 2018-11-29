<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: 流年 <liu21st@gmail.com>
// +----------------------------------------------------------------------

// 应用公共文件

/**
 * Get方式的CURL请求
 */
function saRequestGet($url)
{
    // 初始化
    $ch = curl_init(); // 创建一个CURL资源
    // 设置变量
    curl_setopt($ch, CURLOPT_URL, $url); // 设置URL
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); //不输出获取的结果
    // 执行并获取结果
    $output = curl_exec($ch);
    // 释放CURL
    curl_close($ch);
    // 返回结果
    return $output;
}

/**
 * POST方式请求的CURL
 */
function saRequestPost($url, $data)
{
    // 初始化
    $ch = curl_init(); // 创建一个CURL资源
    // 设置变量
    curl_setopt($ch, CURLOPT_URL, $url);                    // 要访问的地址
    curl_setopt($ch, CURLOPT_POST, 1);                // 发送一个常规的POST请求
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);            // POST提交的数据包
    // 执行并获取结果
    $tmpInfo = curl_exec($ch);
    // 释放CURL
    curl_close($ch);
    // 返回结果
    return $tmpInfo;
}

/**
 * xml转数组
 */
function saXmlToArray($xml)
{
    libxml_disable_entity_loader(true);  //禁止引用外部xml实体
    $xmlString = simplexml_load_string($xml, 'SimpleXMLElement', LIBXML_NOCDATA);
    $array = json_decode(json_encode($xmlString), true);
    return $array;
}

/**
 * 生成N位的随机字符串
 */
function saRandomString($length = 16)
{
    $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
    $str = "";
    for ($i = 0; $i < $length; $i++) {
        $str .= substr($chars, mt_rand(0, strlen($chars) - 1), 1);
    }
    return $str;
}


/**
 * 将参数组装成json数据
 */
function saReturn($code, $msg, $data = NULL)
{
    return json([
        'code' => $code,
        'msg' => $msg,
        'data' => $data
    ]);
}


