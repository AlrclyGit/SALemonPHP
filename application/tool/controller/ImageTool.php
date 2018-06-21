<?php
/**
 * 图片处理控制器.
 * User: 萧俊介
 * Date: 2018/5/29
 * Time: 11:37
 */

namespace app\tool\controller;

use app\lib\enum\PathEnum;
use app\lib\exception\ToolException;
use app\tool\model\Image;

class ImageTool extends BaseTool
{

    /*
     * 接收普通图片
     * @image 接收的参数名,默认为iamge
     * @return 0成功并返回图片ID和Url 1失败
     */
    public function setFileImage($image = 'image')
    {
        // 获取表单上传文件 例如上传了001.jpg
        $file = request()->file($image);
        // 移动到框架应用根目录/public/uploads/ 目录下
        if ($file) {
            $info = $file->move(ROOT_PATH . 'public' . DS . 'images');
            if ($info) {
                // 图片名
                $SaveName = DS . $info->getSaveName();
                // 完整图片地址
                $imageUrl = Config("setting.imagePath") . DS . $info->getSaveName();
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
                    'msg' => '保存文件图片失败',
                    'data' => $file->getError()
                ]);
            }
        } else {
            return saReturn(1, 'NO');
        }
    }

    /*
     * 处理Base64图片
     * @base64Image 需要处理的Base64图片（必选）
     * @path 图片的保存地址，public文件夹为根目录，为空时使用config.image_path的配置（非必选）
     */
    public static function setBase64($base64Image, $path = null)
    {
        if ($path == null) {
            $path = config('config.image_path');
        }
        if (preg_match('/^(data:\s*image\/(\w+);base64,)/', $base64Image, $result)) {
            $imageType = $result[2];
            $newFile = $path . DS . date('Ymd', time()) . DS;
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