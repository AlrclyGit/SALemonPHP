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

 Date: 24/04/2019 16:52:17
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for access
-- ----------------------------
DROP TABLE IF EXISTS `access`;
CREATE TABLE `access` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT 'id',
  `app_id` varchar(32) CHARACTER SET utf8 DEFAULT '' COMMENT '微信app_id',
  `access_token` varchar(264) CHARACTER SET utf8 DEFAULT '' COMMENT 'access_token凭证',
  `js_api_ticket` varchar(128) CHARACTER SET utf8 DEFAULT '' COMMENT '卡券api_ticket',
  `api_ticket` varchar(128) CHARACTER SET utf8 DEFAULT '' COMMENT '微信api_ticket',
  `valid_time` int(11) DEFAULT NULL COMMENT '插入时间',
  `expires_in` int(11) DEFAULT NULL COMMENT '有效期',
  `create_time` datetime NOT NULL COMMENT '添加时间',
  `update_time` datetime DEFAULT NULL COMMENT '更新时间',
  `delete_time` datetime DEFAULT NULL COMMENT '删除时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='凭证表';

-- ----------------------------
-- Table structure for bonus
-- ----------------------------
DROP TABLE IF EXISTS `bonus`;
CREATE TABLE `bonus` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `open_id` varchar(32) NOT NULL COMMENT '用户openid',
  `return_code` varchar(255) DEFAULT NULL COMMENT '返回状态码',
  `return_msg` varchar(255) DEFAULT NULL COMMENT '消返回信息',
  `sign` varchar(255) DEFAULT NULL COMMENT '签名',
  `result_code` varchar(255) DEFAULT NULL COMMENT '业务结果',
  `err_code` varchar(255) DEFAULT NULL COMMENT '错误代码',
  `err_code_des` varchar(255) DEFAULT NULL COMMENT '错误代码描述',
  `mch_bill_no` varchar(255) DEFAULT NULL COMMENT '商户订单号',
  `mch_id` varchar(32) DEFAULT NULL COMMENT '商户号',
  `wx_app_id` varchar(32) DEFAULT NULL COMMENT '公众账号appid',
  `total_amount` int(11) DEFAULT NULL COMMENT '付款金额',
  `send_list_id` varchar(32) DEFAULT NULL COMMENT '微信单号	',
  `create_time` datetime NOT NULL COMMENT '添加时间',
  `update_time` datetime DEFAULT NULL COMMENT '更新时间',
  `delete_time` datetime DEFAULT NULL COMMENT '删除时间',
  PRIMARY KEY (`id`) USING BTREE,
  KEY `create_time` (`create_time`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='发放红包成功表';

-- ----------------------------
-- Table structure for bonus_error
-- ----------------------------
DROP TABLE IF EXISTS `bonus_error`;
CREATE TABLE `bonus_error` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `open_id` varchar(32) NOT NULL COMMENT '用户openid',
  `return_code` varchar(255) DEFAULT NULL COMMENT '返回状态码',
  `return_msg` varchar(255) DEFAULT NULL COMMENT '消返回信息',
  `sign` varchar(255) DEFAULT NULL COMMENT '签名',
  `result_code` varchar(255) DEFAULT NULL COMMENT '业务结果',
  `err_code` varchar(255) DEFAULT NULL COMMENT '错误代码',
  `err_code_des` varchar(255) DEFAULT NULL COMMENT '错误代码描述',
  `mch_bill_no` varchar(255) DEFAULT NULL COMMENT '商户订单号',
  `mch_id` varchar(32) DEFAULT NULL COMMENT '商户号',
  `wx_app_id` varchar(32) DEFAULT NULL COMMENT '公众账号appid',
  `total_amount` int(11) DEFAULT NULL COMMENT '付款金额',
  `send_list_id` varchar(32) DEFAULT NULL COMMENT '微信单号	',
  `create_time` datetime NOT NULL COMMENT '添加时间',
  `update_time` datetime DEFAULT NULL COMMENT '更新时间',
  `delete_time` datetime DEFAULT NULL COMMENT '删除时间',
  PRIMARY KEY (`id`) USING BTREE,
  KEY `openid` (`open_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='发放红包失败表';

-- ----------------------------
-- Table structure for lottery
-- ----------------------------
DROP TABLE IF EXISTS `lottery`;
CREATE TABLE `lottery` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `open_id` char(32) NOT NULL COMMENT 'Open_id',
  `prize_id` int(11) NOT NULL COMMENT '奖品ID',
  `prize_name` varchar(255) NOT NULL COMMENT '奖品名',
  `prize_type` int(11) NOT NULL COMMENT '奖品类型',
  `key` varchar(255) DEFAULT NULL COMMENT '兑换码',
  `money` varchar(255) DEFAULT NULL COMMENT '红包',
  `create_time` datetime NOT NULL COMMENT '添加时间',
  `update_time` datetime DEFAULT NULL COMMENT '更新时间',
  `deleta_time` datetime DEFAULT NULL COMMENT '删除时间',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='中奖记录表';

-- ----------------------------
-- Table structure for prize
-- ----------------------------
DROP TABLE IF EXISTS `prize`;
CREATE TABLE `prize` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT 'id（从0开始）',
  `name` varchar(255) NOT NULL COMMENT '奖品名',
  `number` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '数量',
  `chance` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '概率',
  `state` int(11) unsigned NOT NULL DEFAULT '1' COMMENT '是否开启（0未开启，1开启）',
  `money` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '金额（单位：分）',
  `type` int(11) unsigned NOT NULL COMMENT '类型（0谢谢参与，1线下实物，2红包）',
  `create_time` datetime NOT NULL COMMENT '添加时间',
  `update_time` datetime DEFAULT NULL COMMENT '更新时间',
  `delete_time` datetime DEFAULT NULL COMMENT '删除时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COMMENT='奖品表';

-- ----------------------------
-- Table structure for ranking
-- ----------------------------
DROP TABLE IF EXISTS `ranking`;
CREATE TABLE `ranking` (
  `open_id` char(32) NOT NULL COMMENT 'open_id',
  `grade` int(11) NOT NULL COMMENT '分数',
  `create_time` datetime NOT NULL COMMENT '添加时间',
  `update_time` datetime DEFAULT NULL COMMENT '更新时间',
  `delete_time` datetime DEFAULT NULL COMMENT '删除时间',
  `id` int(11) NOT NULL COMMENT 'ID',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='积分排行表';

-- ----------------------------
-- Table structure for user_info
-- ----------------------------
DROP TABLE IF EXISTS `user_info`;
CREATE TABLE `user_info` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `open_id` char(32) CHARACTER SET utf8 NOT NULL COMMENT 'open_id',
  `nick_name` varchar(64) CHARACTER SET utf8 NOT NULL COMMENT '昵称',
  `head_img_url` varchar(255) CHARACTER SET utf8 NOT NULL COMMENT '头像',
  `sex` tinyint(1) NOT NULL COMMENT '性别',
  `city` varchar(32) CHARACTER SET utf8 NOT NULL COMMENT '城市',
  `province` varchar(32) CHARACTER SET utf8 NOT NULL COMMENT '省份',
  `country` varchar(32) CHARACTER SET utf8 NOT NULL COMMENT '国家',
  `create_time` datetime NOT NULL COMMENT '添加时间',
  `update_time` datetime DEFAULT NULL COMMENT '更新时间',
  `delete_time` datetime DEFAULT NULL COMMENT '删除时间',
  PRIMARY KEY (`id`),
  KEY `open_id` (`open_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='用户信息表';

SET FOREIGN_KEY_CHECKS = 1;
