<?php
/**
 * Name: 图片处理工具类.
 * User: 萧俊介
 * Date: 2018/5/29
 * Time: 11:37
 */

namespace app\tool\controller;


use app\tool\exception\ToolException;

class ImageTool extends BaseTool
{

    /*
       * 接收普通图片
       * @image 接收的参数名,默认为image
       * @path 图片的保存地址，项目文件夹为根目录，为空时使用config.file_image_path的配置（非必选）
       * @return 0成功 并返回图片ID和Url 1失败
       */
    public function setFileImage($image = 'image', $path = null)
    {
        // 获取表单上传文件
        $file = request()->file($image);
        if ($file) {
            // 获取普通图片保存地址
            $path = $path ? $path : config('config.file_image_path');
            // 将图片移动到指定文件夹（细节TP5自动完成）
            $info = $file->move(ROOT_PATH . 'public' . DS . $path);
            // 判断图片移动是否成功
            if ($info) {
                // 图片名
                $SaveName = $info->getSaveName();
                // 返回一个图片的相对地址
                return $path . $SaveName;
            } else {
                // 上传失败获取错误信息
                throw new ToolException([
                    'code' => 101001,
                    'msg' => '移动图片到框架指定目录失败',
                    'data' => $file->getError()
                ]);
            }
        } else {
            throw new ToolException([
                'code' => 101002,
                'msg' => '获取表单上传文件失败，建议检测参数名',
            ]);
        }
    }

    /*
     * 处理Base64图片
     * @$imageName 需要处理的Base64图片名（必选）
     * @path 图片的保存地址，public文件夹为根目录，为空时使用config.base64_image_path的配置（非必选）
     * @return 返回一个图片的相对地址
     */
    public static function setBase64($imageName = 'image', $imageCatalogPath = null)
    {
        // 获取Base64图片的数据
        $base64Image = input("param.$imageName");
        // 获取Base64图片保存地址
        $imageCatalogPath = $imageCatalogPath ? $imageCatalogPath : config('config.base64_image_path');
        // 判断是否为Base64图片
        if (preg_match('/^(data:\s*image\/(\w+);base64,)/', $base64Image, $result)) {
            // 获取图片类型
            $imageType = $result[2];
            // 创建图片日期地址，如果不存在则创建
            $imageTimePath = $imageCatalogPath . date('Ymd', time()) . DS;
            if (!file_exists($imageTimePath)) {
                mkdir($imageTimePath, 0777, true);
            }
            // 拼接完整的图片保存地址
            $imagePath = $imageTimePath . time() . ".{$imageType}";
            // 保存Base64图片，并判断是否成功
            if (file_put_contents($imagePath, base64_decode(str_replace($result[1], '', $base64Image)))) {
                // 返回一个图片的相对地址
                return $imagePath;
            } else {
                throw new ToolException([
                    'code' => 101003,
                    'msg' => '保存Base64图片失败'
                ]);
            }
        } else {
            throw new ToolException([
                'code' => 101004,
                'msg' => '格式不为Base64'
            ]);
        }
    }

}