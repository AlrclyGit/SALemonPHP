/*
Navicat MySQL Data Transfer

Source Server         : 第二数据库
Source Server Version : 50638
Source Host           : www.h5gf.cn:3306
Source Database       : alrcly

Target Server Type    : MYSQL
Target Server Version : 50638
File Encoding         : 65001

Date: 2018-06-21 11:41:26
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for access
-- ----------------------------
DROP TABLE IF EXISTS `access`;
CREATE TABLE `access` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `app_id` varchar(32) DEFAULT '',
  `access_token` varchar(264) DEFAULT '',
  `js_api_ticket` varchar(128) DEFAULT '',
  `api_ticket` varchar(128) DEFAULT '',
  `valid_time` int(11) DEFAULT NULL,
  `expires_in` int(11) DEFAULT NULL,
  `create_time` datetime DEFAULT NULL,
  `update_time` datetime DEFAULT NULL,
  `delete_time` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for user_info
-- ----------------------------
DROP TABLE IF EXISTS `user_info`;
CREATE TABLE `user_info` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `open_id` char(32) NOT NULL,
  `nickname` varchar(64) NOT NULL,
  `head_img_url` varchar(255) NOT NULL,
  `sex` tinyint(1) DEFAULT NULL,
  `province` varchar(32) DEFAULT NULL,
  `city` varchar(32) DEFAULT NULL,
  `country` varchar(32) DEFAULT NULL,
  `create_time` datetime DEFAULT NULL,
  `update_time` datetime DEFAULT NULL,
  `delete_time` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `open_id` (`open_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
