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
 * POST方式的CURL请求
 */
function saRequestPost($url, $data)
{
    // 初始化
    $ch = curl_init(); // 创建一个CURL资源
    // 设置变量
    curl_setopt($ch, CURLOPT_URL, $url); // 要访问的地址
    curl_setopt($ch, CURLOPT_POST, 1); // 发送一个常规的POST请求
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data); // 设置Post参数
    // 执行并获取结果
    $tmpInfo = curl_exec($ch);
    // 释放CURL
    curl_close($ch);
    // 返回结果
    return $tmpInfo;
}

/**
 * XML转数组
 */
function saXmlToArray($xml)
{
    // 转成urlEncode
    $xml = urlencode($xml);
    // 替换掉'%EF%BB%BF'，这个神奇的BOM
    $xml = str_replace('%EF%BB%BF', '', $xml);
    // 转成XML字符串
    $xml = urldecode($xml);
    // 替换掉所有的控制字符
    $xml = preg_replace('/[\x00-\x1F]|\x7F/', '', $xml);
    // 禁止引用外部XML实体
    libxml_disable_entity_loader(true);
    // 转成XML
    $xmlString = simplexml_load_string($xml, 'SimpleXMLElement', LIBXML_NOCDATA);
    // 转数组
    $array = json_decode(json_encode($xmlString), true);
    // 返回
    return $array;
}

/**
 * 生成N位的随机字符串，默认16位
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
 * 将参数组装成JSON数据
 */
function saReturn($code, $msg, $data = NULL)
{
    return json([
        'code' => $code,
        'msg' => $msg,
        'data' => $data
    ]);
}