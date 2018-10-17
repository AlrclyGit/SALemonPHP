<?php
/**
 * Name: 基本验证器.
 * Author: 七月
 * Date: 2017/4/18
 * Time: 5:15
 */

namespace app\tool\validate;

use app\tool\exception\ServerException;
use think\Request;
use think\Validate;

class BaseValidate extends Validate
{

    /*
     * 验证的基础方法
     */
    public function goCheck()
    {
        //获取所有的请求
        $request = Request::instance(); //静态初始化
        $params = $request->param();    //获取获取当前请求的参数

        //过滤请求。当被子类调用时，会使用子类的$rule过滤规则
        $result = $this->batch()->check($params);

        //判断并处理错误
        if (!$result) {
            throw new ServerException([
                'code' => 100003,
                'msg' => $this->error,  //利用构造函数传入错误信息
            ]);
        } else {
            return true;  //返回true(通过验证）
        }
    }

    /*
     * 参数过滤效验
     */
    public function getDataRule($arrays)
    {
        if (array_key_exists('open_id', $arrays)) {
            throw new ServerException([
                'code' => 100003,
                'msg' => '参数中包含有非法的参数名open_id'
            ]);
        } else {
            $newArray = [];
            foreach ($this->rule as $key => $value) {
                $newArray[$key] = $arrays[$key];
            }
            return $newArray;
        }
    }

    /*
     * 验证的参数必须是正整数
     *
     */
    protected function isPositiveInteger($value, $rule = '', $data = '', $field = '')
    {
        if (is_numeric($value) && is_int($value + 0) && ($value + 0) > 0) {
            return true;
        } else {
            return false;
        }
    }

    /*
     * 验证的参数是否为空
     */
    protected function isNotEmpty($value, $rule = '', $data = '', $field = '')
    {
        if (empty($value)) {
            return false;
        } else {
            return true;
        }
    }

    /*
     * 手机号码验证
     */
    protected function isMobile($value, $rule = '', $data = '', $field = '')
    {
        $rule = '^1(3|4|5|7|8)[0-9]\d{8}$^';
        $result = preg_match($rule, $value);
        if ($result) {
            return true;
        } else {
            return false;
        }
    }

}