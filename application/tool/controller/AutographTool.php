<?php
/**
 * 签名生成控制器.
 * User: 萧俊介
 * Date: 2018/5/17
 * Time: 15:32
 */

namespace app\tool\controller;

use think\Db;

class AutographTool extends BaseTool
{

    /*
     * 产生签名
     * @table 写入的数据的数据表（必选）
     * @number 产生的签名条数 （必选）
     * @time 写入的签名时间（非必选）
     */
    public function setSign($table, $number, $time)
    {
        $data = [];
        for ($i = 0; $i < $number; $i++) {
            $bb = [
                'sign_string' => $this->sign(),
                'flag' => 0,
                'term' => $time
            ];
            array_push($data, $bb);
        }
        Db::name($table)->insertAll($data);
    }

    /*
     * 验证签名
     * @string 签名的签名（必选）
     * @return 签名是否有效 0有效 1非法 （返回值）
     */
    public function isSign($string)
    {
        // 取字符串部分的前8位
        $noStr = substr($string, 0, 8);
        // 生成签名
        $sign = md5(md5($noStr) . 'YouAreMyOnlyLove');
        // 取签名部分的前8位
        $sign = substr($sign, 0, 8);
        // 将明文部分和签名部分合并
        $sign = $noStr . $sign;
        // 判断签名是否有效果，并返回
        if ($string == $sign) {
            return 0;
        } else {
            return 1;
        }
    }

    /*
     * 生成签名
     */
    public function sign()
    {
        // 生成8位的随机字符串
        $randomString = createRandomString(8);
        // 生成签名
        $sign = md5(md5($randomString) . 'YouAreMyOnlyLoveWX');
        // 取签名部分的前8位
        $sign = substr($sign, 0, 8);
        // 将明文部分和签名部分合并
        $string = $randomString . $sign;
        // 返回字符串
        return $string;
    }

}