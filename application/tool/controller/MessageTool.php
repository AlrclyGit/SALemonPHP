<?php
/**
 * name: 发送短信工具类
 * User: 萧俊介
 * Date: 2019/1/3
 * Time: 3:31 PM
 * Created by LemonPHP制作委员会.
 */

namespace app\tool\controller;


class MessageTool extends BaseTool
{

    /*
     * 发送消息方法
     */
    function send($phone)
    {
        $sendUrl = 'http://v.juhe.cn/sms/send'; //短信接口的URL
        $smsConf = array(
            'key' => Config("config.message_key"), //您申请的AppKey
            'mobile' => $phone, //接受短信的用户手机号码
            'tpl_id' => Config("config.message_tpl_id"), //您申请的短信模板ID
            'tpl_value' => Config("config.message_tpl_value") //您设置的模板变量
        );
        $content = $this->juHeCurl($sendUrl, $smsConf, 1); //请求发送短信
        if ($content) {
            $result = json_decode($content, true);
            if ($result['error_code'] == 0) {
                return saReturn(0,'短信发送成功');
            } else {
                return saReturn(1,'请求发送短信失败',$result);
            }
        } else {
            return saReturn(2,'请求发送短信失败');
        }
    }

    /**
     * 请求接口返回内容
     * @param  string $url [请求的URL地址]
     * @param  string $params [请求的参数]
     * @param  int $ipost [是否采用POST形式]
     * @return  string
     */
    function juHeCurl($url, $params = false, $ispost = 0)
    {
        $httpInfo = array();
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
        curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 5.1) AppleWebKit/537.22 (KHTML, like Gecko) Chrome/25.0.1364.172 Safari/537.22');
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        if ($ispost) {
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
            curl_setopt($ch, CURLOPT_URL, $url);
        } else {
            if ($params) {
                curl_setopt($ch, CURLOPT_URL, $url . '?' . $params);
            } else {
                curl_setopt($ch, CURLOPT_URL, $url);
            }
        }
        $response = curl_exec($ch);
        if ($response === FALSE) {
            return false;
        }
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $httpInfo = array_merge($httpInfo, curl_getinfo($ch));
        curl_close($ch);
        return $response;
    }


}