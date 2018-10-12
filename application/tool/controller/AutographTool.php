<?php
/**
 * 签名生成工具类.
 * User: 萧俊介
 * Date: 2018/5/17
 * Time: 15:32
 */

namespace app\tool\controller;

use think\Request;

class AutographTool extends BaseTool
{

    /*
     * 变量声明
     */
    private $autographKey;

    /*
     * 构造函数
     */
    function __construct(Request $request = null)
    {
        parent::__construct($request);
        $this->autographKey = Config("config.autograph_secret");
    }

    /*
     * 生成签名
     * @return 生成的签名（返回值）
     */
    public function sign()
    {
        // 生成8位的随机字符串
        $randomString = createRandomString(8);
        // 生成签名
        $sign = md5(md5($randomString) . $this->autographKey);
        // 取签名部分的前8位
        $sign = substr($sign, 0, 8);
        // 将明文部分和签名部分合并
        $string = $randomString . $sign;
        // 返回字符串
        return $string;
    }

    /*
     * 验证签名
     * @string 需要验证的签名（必选）
     * @return 签名是否有效 True有效 False非法 （返回值）
     */
    public function isSign($string)
    {
        // 取字符串部分的前8位
        $noStr = substr($string, 0, 8);
        // 生成签名
        $sign = md5(md5($noStr) . $this->autographKey);
        // 取签名部分的前8位
        $sign = substr($sign, 0, 8);
        // 将明文部分和签名部分合并
        $sign = $noStr . $sign;
        // 判断签名是否有效果，并返回
        if ($string == $sign) {
            return true;
        } else {
            return false;
        }
    }

}