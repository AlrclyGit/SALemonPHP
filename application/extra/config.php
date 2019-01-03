<?php
/**
 * Name: 配置文件
 * User: 萧俊介
 * Date: 2018/2/6
 * Time: 11:13
 */

return [

    // 基本配置
    'appId' => '',                                  // 微信appId
    'secret' => '',                                 // 微信secret
    'isOnlyOpenId' => false,                        // 是否静默授权

    // 文件路径
    'file_image_path' => 'fileImage' . DS,         // 图片文件保存地址
    'base64_image_path' => 'base64Image' . DS,     // 图片Base64保存地址

    // 项目名称配置
    'project_english_name' => '',    // 项目英文名
    'project_china_name' => '',      // 项目中文名

    // Tool
    'autograph_secret' => '',        // 签名工具类使用的Secret

    'face_app_key' => '',            // 人脸融合类使用的app_key
    'face_app_id' => '',             // 人脸融合类使用的app_id

    'message_key' => '',            // 消息的AppKey
    'message_tpl_id' => '',         // 短信模板ID
    'message_tpl_value' => ''       // 短信模板变量

];