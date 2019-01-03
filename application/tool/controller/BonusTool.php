<?php
/**
 * Name: 发放红包工具
 * User: 萧俊介
 * Date: 2018/12/3
 * Time: 12:01 PM
 * Created by LemonPHP制作委员会.
 */

namespace app\tool\controller;


use app\tool\exception\ToolException;
use app\tool\model\Lottery;
use app\tool\model\LotteryError;

class BonusTool extends BaseTool
{

    /*
     * 红包参数
     */
    private function getObj($totalAmount, $openId)
    {
        $obj = array();
        $obj['wxappid'] = Config("config.appId"); // 公众账号appId
        $obj['mch_id'] = Config("bonus.mch_id"); // 商户号
        $obj['mch_billno'] = Config("bonus.mch_id") . date("YmdHis") . rand(1000, 9999); // 商户订单号
        $obj['client_ip'] = $_SERVER['REMOTE_ADDR']; //Ip地址
        $obj['re_openid'] = $openId; // 用户openid
        $obj['total_amount'] = $totalAmount; // 付款金额
        $obj['total_num'] = Config("bonus.total_num"); // 红包发放总人数
        $obj['scene_id'] = Config("bonus.scene_id");
        $obj['send_name'] = Config("bonus.total_num"); // 商户名称
        $obj['wishing'] = Config("bonus.send_name"); // 红包祝福语
        $obj['act_name'] = Config("bonus.act_name"); // 活动名称
        $obj['remark'] = Config("bonus.remark"); //备注
        return $obj;
    }

    /*
     * 红包发放（主要的）
     */
    public function grant($totalAmount)
    {
        // 发放红包
        $configArray = $this->getObj($totalAmount, session('open_id'));
        $bonus = $this->pay($configArray);
        $bonus = json_decode($bonus, true);
        //
        if (empty($bonus['return_code'])) {
            $bonus['return_code'] = null;
        }
        if (empty($bonus['return_msg'])) {
            $bonus['return_msg'] = null;
        }
        if (empty($bonus['sign'])) {
            $bonus['sign'] = null;
        }
        if (empty($bonus['result_code'])) {
            $bonus['result_code'] = null;
        }
        if (empty($bonus['err_code'])) {
            $bonus['err_code'] = null;
        }
        if (empty($bonus['err_code_des'])) {
            $bonus['err_code_des'] = null;
        }
        if (empty($bonus['mch_billno'])) {
            $bonus['mch_billno'] = null;
        }
        if (empty($bonus['mch_id'])) {
            $bonus['mch_id'] = null;
        }
        if (empty($bonus['wxappid'])) {
            $bonus['wxappid'] = null;
        }
        if (empty($bonus['re_openid'])) {
            $bonus['re_openid'] = null;
        }
        if (empty($bonus['total_amount'])) {
            $bonus['total_amount'] = null;
        }
        if (empty($bonus['send_listid'])) {
            $bonus['send_listid'] = null;
        }
        // 发放结果入库
        if ($bonus['return_code'] == "SUCCESS" && $bonus['result_code'] == "SUCCESS") {
            $data = [
                'return_code' => $bonus['return_code'],     // 返回状态码
                'return_msg' => $bonus['return_msg'],       // 消返回信息
                'sign' => $bonus['sign'],                   // 签名
                'result_code' => $bonus['result_code'],     // 业务结果
                'err_code' => $bonus['err_code'],           // 错误代码
                'err_code_des' => $bonus['err_code_des'],   // 错误代码描述
                'mch_bill_no' => $bonus['mch_billno'],      // 商户订单号
                'mch_id' => $bonus['mch_id'],               // 商户号
                'wx_app_id' => $bonus['wxappid'],           // 公众账号appid
                'open_id' => $bonus['re_openid'],            // 用户openid
                'total_amount' => $bonus['total_amount'],   // 付款金额
                'send_list_id' => $bonus['send_listid'],    // 微信单号
            ];
            $lottery = new Lottery();
            $lottery->save($data);
            return [
                'code' => 0,
                'data' => [
                    'money' => $bonus['total_amount'] / 100
                ]
            ];
        } else {
            $data = [
                'return_code' => $bonus['return_code'],     // 返回状态码
                'return_msg' => $bonus['return_msg'],       // 消返回信息
                'sign' => $bonus['sign'],                   // 签名
                'result_code' => $bonus['result_code'],     // 业务结果
                'err_code' => $bonus['err_code'],           // 错误代码
                'err_code_des' => $bonus['err_code_des'],   // 错误代码描述
                'mch_bill_no' => $bonus['mch_billno'],      // 商户订单号
                'mch_id' => $bonus['mch_id'],               // 商户号
                'wx_app_id' => $bonus['wxappid'],           // 公众账号appid
                'open_id' => $bonus['re_openid'],           // 用户openid
                'total_amount' => $bonus['total_amount'],   // 付款金额
                'send_list_id' => $bonus['send_listid'],    // 微信单号
            ];
            $lotteryError = new LotteryError();
            $lotteryError->save($data);
            if ($bonus['err_code'] == 'NOTENOUGH') {
                return [
                    'code' => 1,
                    'data' => '账户余额不足'
                ];
            } else {
                throw new ToolException([
                    'code' => 2,
                    'data' => [
                        'return_code' => $bonus['err_code'],     // 返回状态码
                        'return_msg' => $bonus['return_msg'],    // 消返回信息
                    ]
                ]);
            }
        }
    }


    /*
     * 支付
     */
    private function pay($obj)
    {
        $obj['nonce_str'] = $this->create_noncestr();
        $stringA = $this->formatQueryParaMap($obj, false);
        $stringSingTemp = $stringA . "&key=" . Config("bonus.key");; //添加key
        $sign = strtoupper(md5($stringSingTemp)); // 签名
        $obj['sign'] = $sign;
        $postXml = $this->arrayToXml($obj);
        $responseXml = $this->curl_post_ssl(Config("bonus.url"), $postXml);
        $pay = $this->xml_to_json($responseXml);
        return $pay;
    }

    /*
    * XML转JSON
    */
    private function xml_to_json($source)
    {
        if (is_file($source)) { //传的是文件，还是xml的string的判断
            $xml_array = simplexml_load_file($source, 'SimpleXMLElement', LIBXML_NOCDATA);
        } else {
            $xml_array = simplexml_load_string($source, 'SimpleXMLElement', LIBXML_NOCDATA);
        }
        return json_encode($xml_array); //php5，以及以上，如果是更早版本，请查看JSON.php

    }

    /*
     * 获取随机字符串
     */
    private function create_noncestr($length = 32)
    {
        $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
        $str = "";
        for ($i = 0; $i < $length; $i++) {
            $str .= substr($chars, mt_rand(0, strlen($chars) - 1), 1);
        }
        return $str;
    }

    /*
     * 排序
     */
    private function formatQueryParaMap($paraMap, $urLenCode)
    {
        $buff = "";
        ksort($paraMap); //排序
        foreach ($paraMap as $k => $v) {
            if ($v != null && $v != "null" && $k != "sign") {
                if ($urLenCode) {
                    $v = urlencode($v);
                }
                $buff .= $k . "=" . $v . "&";
            }
        }
        if (strlen($buff)) {
            $reqPar = substr($buff, 0, strlen($buff) - 1);
        }
        return $reqPar;
    }

    /*
     * 数组转Xml
     */
    private function arrayToXml($arr)
    {
        $xml = "<xml>";
        foreach ($arr as $key => $val) {
            if (!($val == "null")) {
                $xml .= "<" . $key . ">" . $val . "</" . $key . ">";
            }
        }
        $xml .= "</xml>";
        return $xml;
    }

    /**
     * @name ssl Curl Post数据
     * @param string $url 接收数据的api
     * @param string $vars 提交的数据
     * @param int $second 要求程序必须在$second秒内完成,负责到$second秒后放到后台执行
     * @return string or boolean 成功且对方有返回值则返回
     */
    private function curl_post_ssl($url, $vars, $second = 30, $aHeader = array())
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_TIMEOUT, $second); // 超时时间
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); //是否要求返回数据
        curl_setopt($ch, CURLOPT_URL, $url); // 请求的地址
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // 是否检测服务器的证书是否由正规浏览器认证过的授权CA颁发的
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false); // 是否检测服务器的域名与证书上的是否一致
        curl_setopt($ch, CURLOPT_SSLCERTTYPE, 'PEM'); // 证书类型，"PEM" (default), "DER", and"ENG".!
        curl_setopt($ch, CURLOPT_SSLCERT, APP_PATH . '/cert/apiclient_cert.pem'); // 证书存放路径!!!
        curl_setopt($ch, CURLOPT_SSLCERTPASSWD, '1234'); // 证书密码!!!
        curl_setopt($ch, CURLOPT_SSLKEYTYPE, 'PEM'); // 私钥类型，"PEM" (default), "DER", and"ENG".!
        curl_setopt($ch, CURLOPT_SSLKEY, APP_PATH . '/cert/apiclient_key.pem'); //私钥存放路径!!!
        if (count($aHeader) >= 1) {
            curl_setopt($ch, CURLOPT_HTTPHEADER, $aHeader); // 一个用来设置HTTP头字段的数组。
        }
        curl_setopt($ch, CURLOPT_POST, 1); // 启用时会发送一个常规的POST请求，类型为：application/x-www-form-urlencoded，就像表单提交的一样。
        curl_setopt($ch, CURLOPT_POSTFIELDS, $vars); // 全部数据使用HTTP协议中的"POST"操作来发送。
        $data = curl_exec($ch);
        curl_close($ch);
        if ($data)
            return $data;
        else
            return false;
    }
}