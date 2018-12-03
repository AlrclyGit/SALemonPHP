<?php
/**
 * Name: 人脸融合控制器
 * User: 萧俊介
 * Date: 2018/8/7
 * Time: 上午10:36
 */

namespace app\tool\controller;


class FaceTool extends BaseTool
{

    // 声明变量
    private $appKye;
    private $appId;


    /*
     * 构造函数
     */
    public function __construct()
    {
        parent::__construct();
        // 设置变量
        $this->appKye = Config("config.face_app_key");
        $this->appId = Config("config.face_app_id");
    }


    /*
     * 接收Base64图片，并进行人脸融合
     * 接收参数img(Base64图片)、model（模版号)
     * 直接返回腾讯的返回结果
     */
    public function getFace()
    {
        // 接收参数
        $param = input('param.');
        // 设置时间戳和随机数
        $time_stamp = time();
        $nonce_str = rand(10000, 99999);
        // 处理基本信息
        $image = ltrim(strstr($param['img'], ','), ',');
        // 拼接字符串，并签名
        $string = 'app_id=' . $this->appId . '&image=' . urlencode($image) . '&model=' . $param['model'] . '&nonce_str=' . $nonce_str . '&time_stamp=' . $time_stamp . '&app_key=' . $this->appKye;
        $sign = strtoupper(md5($string));
        // 构建请求的数组
        $post_data = array(
            'app_id' => $this->appId,
            'time_stamp' => $time_stamp,
            'nonce_str' => $nonce_str,
            'sign' => $sign,
            'model' => $param['model'],
            'image' => $image
        );
        // 调用人脸整合的接口
        return $this->send_post('https://api.ai.qq.com/fcgi-bin/ptu/ptu_facemerge', $post_data);
    }


    /*
     * Post方法
     */
    private function send_post($url, $post_data)
    {
        //发送post请求
        $postData = http_build_query($post_data);
        $options = array(
            'http' => array(
                'method' => 'POST',
                'header' => 'Content-type:application/x-www-form-urlencoded',
                'content' => $postData,
                'timeout' => 15 * 60
            )
        );
        $context = stream_context_create($options);
        $result = file_get_contents($url, false, $context);
        return $result;
    }

}