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
function saRequestGet($url, &$httpCode = 0)
{
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); //不做证书校验
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
    $file_contents = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    return $file_contents;
}

/**
 * POST方式请求的CURL
 */
function saRequestPost($url, $data)
{
    $data = http_build_query($data);
    $ch = curl_init();                                            // 启动一个CURL会话
    curl_setopt($ch, CURLOPT_URL, $url);                    // 要访问的地址
    curl_setopt($ch, CURLOPT_POST, 1);                // 发送一个常规的POST请求
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);            // POST提交的数据包
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);      // POST的返回方式
    $tmpInfo = curl_exec($ch);                                    // 执行操作
    if (curl_errno($ch)) {                                        // 判断是否有错误
        throw  new \app\tool\exception\ServerException([
            'code' => 60058,
            'msg' => 'POST请求失败',
            'data' => curl_errno($ch)
        ]);
    }
    curl_close($ch);                                                // 关闭会话
    return $tmpInfo;                                                // 返回数据
}

/**
 * xml转数组
 */
function saXmlToArray($xml)
{
    libxml_disable_entity_loader(true);  //禁止引用外部xml实体
    $xmlString = simplexml_load_string($xml, 'SimpleXMLElement', LIBXML_NOCDATA);
    $val = json_decode(json_encode($xmlString), true);
    return $val;
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
function saReturn($errorCode, $reason, $result = [])
{
    return json([
        'code' => $errorCode,
        'msg' => $reason,
        'data' => $result
    ]);
}


