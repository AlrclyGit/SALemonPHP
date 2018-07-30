<?php
/**
 * Created by LemonPHP制作委员会.
 * ToKen控制器.
 * User: 萧俊介
 * Date: 2018/4/23
 * Time: 10:36
 */

namespace app\index\controller;


use app\lib\exception\TokenException;
use app\model\User;
use app\tool\validate\ToKenV;
use think\Cache;
use think\Request;

class TokenTool
{

    /*
     * 声明变量
     */
    protected $code;
    protected $wxAppID;
    protected $wxAppSecret;
    protected $wxLoginUrl;


    /*
     * 调用微信接口，并获取ToKen。
     */
    public function getToken()
    {
        // 参数验证
        $validate = new ToKenV();
        $validate->goCheck();
        // 设置常用变量
        $code = input('param.code');
        $this->code = $code;
        $this->wxAppID = config('config.appId');
        $this->wxAppSecret = config('config.secret');
        $loginUrl = "https://api.weixin.qq.com/sns/jscode2session?" . "appid=%s&secret=%s&js_code=%s&grant_type=authorization_code";
        $this->wxLoginUrl = sprintf($loginUrl, $this->wxAppID, $this->wxAppSecret, $this->code);
        // 调用微信接口
        $result = curl_get($this->wxLoginUrl);
        // 将返回值转成数组
        $wxResult = json_decode($result, true);
        // 是否有正确的返回值
        if (empty($wxResult)) {
            throw new TokenException([
                'code' => 11001,
                'msg' => '微信接口调用失败'
            ]);
        } else {
            $loginFail = array_key_exists('errcode', $wxResult);
            if ($loginFail) {
                throw new TokenException([
                    'code' => 11002,
                    'msg' => '微信接口调用成功，但传参不正确',
                    'data' => $wxResult
                ]);
            } else {
                $data = $this->grantToken($wxResult);
                return saReturn(0, 'OK', ['token' => $data]);
            }
        }
    }


    /*
     * 解密用户的详细信息
     */
    public function getUserInfo()
    {
        // 获取用户信息
        $openId = $this->getCurrentOpenID();
        $sessionKey = $this->getCurrentTokenVar('session_key');
        $rawData = input('param.rawData');
        $signature = input('param.signature');
        // 验证用户信息
        $sha1 = sha1($rawData . $sessionKey);
        if ($sha1 == $signature) {
            $rawDataArray = json_decode($rawData, true);
        } else {
            throw new TokenException([
                'msg' => '解码失败'
            ]);
        }
        // 写入用户信息
        $data = [
            'nick_name' => $rawDataArray['nickName'],
            'avatar_url' => $rawDataArray['avatarUrl'],
            'gender' => $rawDataArray['gender'],
            'province' => $rawDataArray['province'],
            'city' => $rawDataArray['city'],
            'country' => $rawDataArray['country'],
        ];
        $userM = new User();
        $flag = $userM->save($data, ['open_id' => $openId]);
        // 更新用户信息缓存
        $cachedKey = Request::instance()->header('token');;
        $cachedValue = $userM->where('open_id', $openId)->find();
        $this->saveToCache($cachedKey, $cachedValue);
        // 返回
        if ($flag) {
            return saReturn(0, '正确写入用户信息', $flag);
        } else {
            return saReturn(1, 'openId不存在', $flag);
        }
    }


    /*
     * 通过Token获取用户openId
     */
    public static function getCurrentOpenID()
    {
        $uid = self::getCurrentTokenVar('openid');
        return $uid;
    }


    /*
     * 用户是否存在，用openid生成Token
     */
    private function grantToken($wxResult)
    {
        // 获取返回的openId
        $openId = $wxResult['openid'];
        // 数据库是否有用户数据
        $userM = new User();
        $user = $userM->where('open_id', $openId)->find();
        if ($user) {
            $userM->save(['session_key' => $wxResult['session_key']], ['open_id' => $openId]);
        } else {
            $data = [
                'open_id' => $openId,
                'session_key' => $wxResult['session_key']
            ];
            $userM->save($data);
        }
        // 生成ToKen的Value
        $cachedValue = $this->prepareCachedValue($wxResult);
        // 生成ToKen的key
        $cachedKey = $this->generateToKen();
        // 生成ToKen
        $token = $this->saveToCache($cachedKey, $cachedValue);
        // 返回ToKen
        return $token;
    }


    /*
     * 生成ToKen的Value
     */
    private function prepareCachedValue($wxResult)
    {
        $cachedValue = $wxResult;
        return json_encode($cachedValue);
    }

    /*
     * 生成ToKen的key
     */
    private function generateToKen()
    {
        // 32个字符组成一组随机字符串
        $randChars = createRandomString(32);
        // 当时时间戳
        $timestamp = time();
        // salt 盐
        $salt = config('config.token_salt');
        // 进行md5加密
        return md5($randChars . $timestamp . $salt);
    }

    /*
     * 生成ToKen
     */
    private function saveToCache($cachedKey, $cachedValue)
    {
        $expire_in = config('config.token_expire_in');
        $request = cache($cachedKey, $cachedValue, $expire_in);
        if (!$request) {
            exception('缓存错误', 5566);
            throw new TokenException([
                'code' => 12001,
                'msg' => 'Token缓存失败',
            ]);
        }
        return $cachedKey;
    }


    /*
     * 通过Token获取用户某个信息
     */
    public static function getCurrentTokenVar($key)
    {
        $token = Request::instance()->header('token');
        $vars = Cache::get($token);
        if (!$vars) {
            throw new TokenException([
                'code' => 12002,
                'msg' => '无效的Token',
            ]);
        } else {
            if (!is_array($vars)) {
                $vars = json_decode($vars, true);
            }
            if (array_key_exists($key, $vars)) {
                return $vars[$key];
            } else {
                throw new TokenException([
                    'code' => 12003,
                    'msg' => 'Token缓存中没有你要的字段',
                ]);
            }
        }
    }

}