/*
 Navicat MySQL Data Transfer

 Source Server         : 第二服务器
 Source Server Type    : MySQL
 Source Server Version : 50638
 Source Host           : 47.92.24.109:3306
 Source Schema         : alrcly

 Target Server Type    : MySQL
 Target Server Version : 50638
 File Encoding         : 65001

 Date: 10/07/2018 11:48:56
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for access
-- ----------------------------
DROP TABLE IF EXISTS `access`;
CREATE TABLE `access` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `app_id` varchar(32) CHARACTER SET utf8 DEFAULT '',
  `access_token` varchar(264) CHARACTER SET utf8 DEFAULT '',
  `js_api_ticket` varchar(128) CHARACTER SET utf8 DEFAULT '',
  `api_ticket` varchar(128) CHARACTER SET utf8 DEFAULT '',
  `valid_time` int(11) DEFAULT NULL,
  `expires_in` int(11) DEFAULT NULL,
  `create_time` datetime DEFAULT NULL,
  `update_time` datetime DEFAULT NULL,
  `delete_time` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Table structure for autograph
-- ----------------------------
DROP TABLE IF EXISTS `autograph`;
CREATE TABLE `autograph` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `sign_str` varchar(255) DEFAULT NULL,
  `flag` tinyint(4) DEFAULT NULL,
  `term` varchar(255) DEFAULT NULL,
  `create_time` datetime DEFAULT NULL,
  `update_time` datetime DEFAULT NULL,
  `delete_time` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=240001 DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Table structure for image
-- ----------------------------
DROP TABLE IF EXISTS `image`;
CREATE TABLE `image` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `url` varchar(255) DEFAULT NULL,
  `from` varchar(255) DEFAULT NULL,
  `create_time` datetime DEFAULT NULL,
  `update_time` datetime DEFAULT NULL,
  `delete_time` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Table structure for user_info
-- ----------------------------
DROP TABLE IF EXISTS `user_info`;
CREATE TABLE `user_info` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `open_id` char(32) CHARACTER SET utf8 NOT NULL,
  `nickname` varchar(64) CHARACTER SET utf8 NOT NULL,
  `head_img_url` varchar(255) CHARACTER SET utf8 NOT NULL,
  `sex` tinyint(1) DEFAULT NULL,
  `province` varchar(32) CHARACTER SET utf8 DEFAULT NULL,
  `city` varchar(32) CHARACTER SET utf8 DEFAULT NULL,
  `country` varchar(32) CHARACTER SET utf8 DEFAULT NULL,
  `create_time` datetime DEFAULT NULL,
  `update_time` datetime DEFAULT NULL,
  `delete_time` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `open_id` (`open_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

SET FOREIGN_KEY_CHECKS = 1;
