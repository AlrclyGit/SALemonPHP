<?php
/**
 * 图片处理工具类.
 * User: 萧俊介
 * Date: 2018/5/29
 * Time: 11:37
 */

namespace app\tool\controller;

use app\lib\enum\PathEnum;
use app\lib\exception\ToolException;
use app\model\Image;

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
            // 移动到框架指定目录
            if ($path == null) {
                $path = config('config.file_image_path');
            }
            $info = $file->move(ROOT_PATH . 'public' . DS . $path);
            if ($info) {
                // 图片名
                $SaveName = $info->getSaveName();
                // 完整图片地址
                $imageUrl = config('config.root_path') . $path . $SaveName;
                // 图片写入数据库
                $imageM = new Image();
                $data = [
                    'url' => $SaveName,
                    'from' => PathEnum::imagePath
                ];
                $imageM->save($data);
                $imageId = $imageM->id;
                // 返回给前端的数据
                $data = [
                    'image_id' => $imageId,
                    'image_url' => $imageUrl
                ];
                return saReturn(0, 'OK', $data);
            } else {
                // 上传失败获取错误信息
                throw new ToolException([
                    'msg' => '移动图片到框架指定目录失败',
                    'data' => $file->getError()
                ]);
            }
        } else {
            throw new ToolException([
                'msg' => '获取表单上传文件失败，建议检测参数名',
            ]);
        }
    }

    /*
     * 处理Base64图片
     * @base64Image 需要处理的Base64图片（必选）
     * @path 图片的保存地址，public文件夹为根目录，为空时使用config.base64_image_path的配置（非必选）
     */
    public static function setBase64($base64Image, $path = null)
    {
        if ($path == null) {
            $path = config('config.base64_image_path');
        }
        if (preg_match('/^(data:\s*image\/(\w+);base64,)/', $base64Image, $result)) {
            $imageType = $result[2];
            $newFile = $path . date('Ymd', time()) . DS;
            if (!file_exists($newFile)) {
                mkdir($newFile, 0777, true);
            }
            $newImage = $newFile . time() . ".{$imageType}";
            if (file_put_contents($newImage, base64_decode(str_replace($result[1], '', $base64Image)))) {
                // 图片写入数据库
                $imageM = new Image();
                $data = [
                    'url' => $newImage,
                    'from' => PathEnum::imagePath
                ];
                $imageM->save($data);
                $imageId = $imageM->id;
                // 返回给前端的数据
                $data = [
                    'image_id' => $imageId,
                    'image_url' => $newImage
                ];
                return saReturn(0, 'OK', $data);
            } else {
                throw new ToolException([
                    'msg' => '保存Base64图片失败'
                ]);
            }
        } else {
            throw new ToolException([
                'msg' => '格式不为Base64'
            ]);
        }
    }

}