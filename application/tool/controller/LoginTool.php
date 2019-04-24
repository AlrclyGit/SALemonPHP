<?php
/**
 * Name: 获取用户信息工具类
 * User: 萧俊介
 * Date: 2017/9/26
 * Time: 17:14
 */

namespace app\tool\controller;


use app\tool\exception\ToolException;
use app\tool\model\UserInfo;


class LoginTool extends BaseTool
{

    // 声明变量
    private $appId;
    private $appSecret;
    private $isOnlyOpenId;
    private $isHttps;

    /*
     * 构造函数
     */
    public function __construct()
    {
        parent::__construct();
        $this->appId = config("config.app_id");
        $this->appSecret = config("config.app_secret");
        $this->isOnlyOpenId = config("config.is_only_open_id");
        $this->isHttps = config("config.isHttps");
    }


    /*
     * 获取用户信息并写入数据库
     */
    public function getWeChatUserInfo()
    {
        $code = input('param.code');
        if (empty($code)) {
            return $this->getCode();
        } else {
            $userInfo = $this->getUserInfo($code);
            if ($userInfo['code'] == 0) {
                // 完整授权
                $this->processUserInfo($userInfo['data']);
                return null;
            } elseif ($userInfo['code'] == 1) {
                // 静默授权
                $this->processOpenId($userInfo['data']);
                return null;
            } else {
                throw new ToolException([
                    'mgs' => '获取用户信息并写入数据库,出现了一个不可能的错误'
                ]);
            }
        }
    }

    /*
     * 重定向获取微信用户code
     */
    private function getCode()
    {
        $url = "https://open.weixin.qq.com/connect/oauth2/authorize?";
        $url .= "appid={$this->appId}";
        $http = $this->isHttps ? 'https://' : 'http://';
        $url .= '&redirect_uri=' . urlencode($http . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']);
        $url .= '&response_type=code';
        $url .= $this->isOnlyOpenId ? '&scope=snsapi_base' : '&scope=snsapi_userinfo';
        $url .= '&state=STATE#wechat_redirect';
        header('location:' . $url);
        exit();
    }

    /*
     *  通过Code获取用户信息
     */
    private function getUserInfo($code)
    {
        // 通过 code 换取网页授权 access_token
        $accessTokenUrl = saRequest("https://api.weixin.qq.com/sns/oauth2/access_token?appid={$this->appId}&secret={$this->appSecret}&code={$code}&grant_type=authorization_code");
        $accessTokenArr = json_decode($accessTokenUrl, true);
        // 通过 access_token 换取用户信息
        if (empty($accessTokenArr['errcode'])) {
            // 是否为静默授权
            if ($this->isOnlyOpenId) {
                return [
                    'code' => 1,
                    'data' => $accessTokenArr['openid']
                ];
            } else {
                //拉取用户信息
                $userInfoUrl = file_get_contents("https://api.weixin.qq.com/sns/userinfo?access_token={$accessTokenArr['access_token']}&openid={$accessTokenArr['openid']}&lang=zh_CN");
                $userInfoArr = json_decode($userInfoUrl, true);
                if (empty($accessTokenArr['errcode'])) {
                    return [
                        'code' => 0,
                        'data' => $userInfoArr
                    ];
                } else {
                    throw new ToolException([
                        'msg' => '获取用户信息失败'
                    ]);
                }
            }
        } else {
            throw new ToolException([
                'msg' => '通过Code获取用户信息失败'
            ]);
        }
    }

    /*
    * 将用户信息写入数据库
    */
    private function processUserInfo($userInfo)
    {
        // 打包数组
        $userInfoArray = [
            'open_id' => $userInfo ['openid'], // openId
            'nick_name' => $userInfo['nickname'], // 昵称
            'head_img_url' => $userInfo ['headimgurl'], // 头像地址
            'sex' => $userInfo ['sex'], // 性别
            'province' => $userInfo ['province'], // 用户个人资料填写的省份
            'city' => $userInfo ['city'], // 普通用户个人资料填写的城市
            'country' => $userInfo ['country'] // 国家，如中国为CN
        ];
        $userInfo = UserInfo::get($userInfo['openid']);
        if ($userInfo) {
            $userInfo->save($userInfoArray);
            session('open_id', $userInfo ['open_id']);
        } else {
            $userInfo = UserInfo::create($userInfoArray);
            session('open_id', $userInfo ['open_id']);
        }
    }

    /*
     * 将openId写入数据库
     */
    private function processOpenId($openId)
    {
        $userInfo = UserInfo::get($openId);
        if (!$userInfo) {
            UserInfo::create(['open_id' => $openId]);
        }
        session('open_id', $openId);
    }

}