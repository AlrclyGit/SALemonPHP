<?php
/**
 * 商户信息、用户信息控制器
 * User: 萧俊介
 * Date: 2017/9/26
 * Time: 17:14
 */

namespace app\tool\controller;

use app\tool\model\UserInfo;
use app\lib\exception\TokenException;

class LoginTool extends BaseTool
{

    // 声明变量
    private $appId;
    private $secret;
    private $isOnlyOpenId;

    /*
     * 构造函数
     */
    public function __construct()
    {
        parent::__construct();
        $this->appId = Config("config.appId");
        $this->secret = Config("config.secret");
        $this->isOnlyOpenId = Config("config.isOnlyOpenId");
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
                $this->upUserOpenInfo($userInfo['data']);
                return null;
            } else {
                $this->redirect('index/index/index');
                return null;
            }
        }
    }

    /*
     * 重定向获取微信用户code
     */
    public function getCode()
    {
        $url = "https://open.weixin.qq.com/connect/oauth2/authorize?";
        $url .= "appid={$this->appId}";
        $url .= '&redirect_uri=' . urlencode('http://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']);
        $url .= '&response_type=code';
        if ($this->isOnlyOpenId) {
            $url .= '&scope=snsapi_base';
        } else {
            $url .= '&scope=snsapi_userinfo';
        }
        $url .= '&state=STATE#wechat_redirect';
        header('location:' . $url);
        exit();
    }

    /*
     *  通过Code获取用户信息
     */
    public function getUserInfo($code)
    {
        // 通过 code 换取网页授权 access_token
        $accessTokenUrl = curl_get("https://api.weixin.qq.com/sns/oauth2/access_token?appid={$this->appId}&secret={$this->secret}&code={$code}&grant_type=authorization_code");
        $accessTokenArr = json_decode($accessTokenUrl, true);
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
                    throw new TokenException([
                        'msg' => '获取用户信息失败'
                    ]);
                }
            }
        } else {
            throw new TokenException([
                'msg' => '获取OpenID失败'
            ]);
        }
    }

    /*
    * 将用户信息写入数据库
    */
    public function processUserInfo($userInfo)
    {
        // 将openID写入session
        session('open_id', $userInfo['openid']);
        // 处理用户名称
        $nickName = $userInfo['nickname'];
        $nickName = $this->filterEmoji($nickName);
        if (empty($nickName)) {
            $nickName = '昵称为空';
        }
        // 处理用户头像
        if (empty($userInfo ['headimgurl'])) {
            $userInfo ['headimgurl'] = 'http://game.h5gf.cn/shuixingwuyu/img.jpg';
        }
        // 打包数组
        $userInfoArray = [
            'nickname' => $nickName, // 昵称
            'open_id' => $userInfo ['openid'], // openId
            'head_img_url' => $userInfo ['headimgurl'], // 头像地址
            'sex' => $userInfo ['sex'], // 性别
            'province' => $userInfo ['province'], // 用户个人资料填写的省份
            'city' => $userInfo ['city'], // 普通用户个人资料填写的城市
            'country' => $userInfo ['country'] // 国家，如中国为CN
        ];
        $userInfoM = new UserInfo();
        $flag = $userInfoM->save($userInfoArray, ['open_id' => $userInfo['openid']]);
        if (!$flag) {
            $userInfoM = new UserInfo();
            $userInfoM->save($userInfoArray);
        }
    }

    /*
     * 将openId写入数据库
     */
    public function upUserOpenInfo($openId)
    {
        session('open_id', $openId);
        $userInfoM = new UserInfo();
        $userInfoDb = $userInfoM->where('open_id', $openId)->find();
        if (!$userInfoDb) {
            $userInfoM->save(['open_id' => $openId]);
        }
    }

    /*
    * 过滤Emoji表情
    */
    private function filterEmoji($nickname)
    {
        $pattern = '/([0-9|#][\x{20E3}])|[\x{00ae}|\x{00a9}|\x{203C}|\x{2047}|\x{2048}|\x{2049}|\x{3030}|\x{303D}|\x{2139}|\x{2122}|\x{3297}|\x{3299}][\x{FE00}-\x{FEFF}]?|[\x{2190}-\x{21FF}][\x{FE00}-\x{FEFF}]?|[\x{2300}-\x{23FF}][\x{FE00}-\x{FEFF}]?|[\x{2460}-\x{24FF}][\x{FE00}-\x{FEFF}]?|[\x{25A0}-\x{25FF}][\x{FE00}-\x{FEFF}]?|[\x{2600}-\x{27BF}][\x{FE00}-\x{FEFF}]?|[\x{2600}-\x{27BF}][\x{1F000}-\x{1FEFF}]?|[\x{2900}-\x{297F}][\x{FE00}-\x{FEFF}]?|[\x{2B00}-\x{2BF0}][\x{FE00}-\x{FEFF}]?|[\x{1F000}-\x{1F9FF}][\x{FE00}-\x{FEFF}]?|[\x{1F000}-\x{1F9FF}][\x{1F000}-\x{1FEFF}]?/u';
        $filter_str = preg_replace($pattern, "", $nickname);
        return $filter_str;
    }


}