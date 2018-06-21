<?php
/**
 * 重写ThinkPHP5的全局异常类.
 * User: 萧俊介
 * Date: 2018/4/23
 * Time: 17:08
 */

namespace app\lib\exception;


use think\exception\Handle;
use think\Log;
use think\Request;

class ExceptionHandler extends Handle
{

    // 声明变量
    private $code;
    private $msg;
    private $data = null;

    // 重写框架的全局异常处理方法
    public function render(\Exception $e)
    {
        if ($e instanceof BaseException) { // 自定义的异常
            $this->code = $e->code;
            $this->msg = $e->msg;
            $this->data = $e->data;
        } else { // 后端异常
            if (config('app_debug')) {
                return parent::render($e);
            } else {
                $this->code = 233;
                $this->msg = "没错，就是后端的锅，快去找俊介。";
                $this->recordErrorLog($e);
            }
        }
        // 设置返回的内容
        $request = Request::instance();
        $result = [
            'code' => $this->code,
            'msg' => $this->msg,
            'data' => $this->data,
            'request_url' => $request->url()
        ];
        return json($result);
    }

    /*
     * 异常写入日志
     */
    private function recordErrorLog(\Exception $e)
    {
        Log::init([
            'type' => 'File',
            'path' => LOG_PATH,
            'level' => ['error']
        ]);
        Log::record($e->getMessage(), 'error');
    }
}