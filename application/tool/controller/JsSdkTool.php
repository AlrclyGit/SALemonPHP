<?php
/**
 * JsSDK控制器
 * User: 萧俊介
 * Date: 2017/10/11
 * Time: 10:49
 */

namespace app\tool\controller;

use app\lib\exception\TokenException;
use app\model\Access;
use think\Request;

class JsSdkTool extends BaseTool
{

    /*
     * 变量声明
     */
    private $appId;
    private $secret;

    /*
     * 构造函数
     */
    function __construct(Request $request = null)
    {
        parent::__construct($request);
        $this->appId = Config("config.appId");
        $this->secret = Config("config.secret");
    }


    /*
     * 通过config接口注入权限验证配置
     */
    function Config()
    {
        // 获取 ApiTicket
        $access = $this->getAccess();
        $jsApiTicket = $access['js_api_ticket'];
        // 生成16位的随机字符串
        $nonceStr = $this->createNonceStr();
        // 时间戳
        $timestamp = time();
        // Url
        $url = input('server.HTTP_REFERER');
        // 签名排序（这里参数的顺序要按照 key 值 ASCII 码升序排序）
        $string = "jsapi_ticket=$jsApiTicket&noncestr=$nonceStr&timestamp=$timestamp&url=$url";
        // 签名加密
        $signature = sha1($string);
        //拼成返回值
        $signPackage = [
            "appId" => $this->appId,
            "nonceStr" => $nonceStr, // 随机字符串
            "timestamp" => $timestamp, //时间戳
            "signature" => $signature, //签名
        ];
        return $signPackage;
    }


    /*
     * 微信卡券
     */
    public function getCard($card_id)
    {
        // 时间戳
        $timestamp = time();
        // 获取ApiTicket
        $access = $this->getAccess();
        $api_ticket = $access['api_ticket'];
        // 16位的随机字符串
        $nonce_str = $this->createNonceStr();
        // 字符串排序
        $stringArr = [$timestamp, $api_ticket, $nonce_str, $card_id];
        // 签名加密
        $signature = $this->getCardSign($stringArr);
        $getSignature = [
            'timestamp' => $timestamp, //时间戳
            'nonce_str' => $nonce_str, //字符串排序
            'card_id' => $card_id, //cardId
            'signature' => $signature, // 签名加密
        ];
        return $getSignature;
    }

    /*
     * 生成16位的随机字符串
     */
    private function createNonceStr($length = 16)
    {
        $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
        $str = "";
        for ($i = 0; $i < $length; $i++) {
            $str .= substr($chars, mt_rand(0, strlen($chars) - 1), 1);
        }
        return $str;
    }

    /*
     * 进行排序和sha1加密
     */
    function getCardSign($card)
    {
        sort($card, SORT_STRING);
        $sign = sha1(implode($card));
        if (empty($sign)) {
            return false;
        }
        return $sign;
    }

    /*
     * 获取Access
     */
    public function getAccess()
    {
        // 从数据库获取
        $accessM = new Access();
        $accessDb = $accessM->where('app_id', $this->appId)->find();
        //判断是否过期
        if ($accessDb['valid_time'] + $accessDb['expires_in'] > time()) {
            return $accessDb;
        } else {
            $tokenUrl = curl_get("https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid={$this->appId}&secret={$this->secret}");
            $token = json_decode($tokenUrl, true);
            if (isset($token['access_token'])) {
                $jsApiUrl = file_get_contents("https://api.weixin.qq.com/cgi-bin/ticket/getticket?access_token={$token['access_token']}&type=jsapi");
                $jsApi = json_decode($jsApiUrl, true);
                $apiUrl = file_get_contents("https://api.weixin.qq.com/cgi-bin/ticket/getticket?access_token={$token['access_token']}&type=wx_card");
                $api = json_decode($apiUrl, true);
                if ($jsApi['errcode'] == 0 && $api['errcode'] == 0) {
                    $accessData = [
                        'app_id' => $this->appId,
                        'access_token' => $token['access_token'],
                        'js_api_ticket' => $jsApi['ticket'],
                        'api_ticket' => $api['ticket'],
                        'valid_time' => time(),
                        'expires_in' => $token['expires_in']
                    ];
                    if ($accessDb) {
                        $accessM->save($accessData, ['app_id' => $this->appId]);
                        return $accessData;
                    } else {
                        $accessM->save($accessData);
                        return $accessData;
                    }
                } else {
                    throw new TokenException([
                        'msg' => '获取js_api_ticket或api_ticket失败'
                    ]);
                }
            } else {
                throw new TokenException([
                    'msg' => '获取access_token失败',
                    'data' => $token
                ]);
            }
        }
    }

}