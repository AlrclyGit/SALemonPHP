<?php
/**
 * 配置文件
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
    'root_path' => 'http://game.h5gf.cn' . DS . 'jinhuijt' . DS . 'public' . DS,         // 根目录（拼接图片时会用到）
    'file_image_path' => 'fileImage' . DS,                                               // 图片文件保存地址
    'base64_image_path' => 'base64Image' . DS,                                           // 图片Base64保存地址


    // token
    'token_salt' => 'Your32', // token的盐
    'token_expire_in' => 604800, // token的有效时间


    // 其他
    'project_name' => '彩色夏天',  // 项目名




];