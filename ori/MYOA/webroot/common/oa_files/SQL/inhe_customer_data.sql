/*
Navicat MySQL Data Transfer

Source Server         : oa
Source Server Version : 50525
Source Host           : localhost:3336
Source Database       : TD_OA

Target Server Type    : MYSQL
Target Server Version : 50525
File Encoding         : 65001

Date: 2020-07-21 15:01:18
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for inhe_customer_data
-- ----------------------------
DROP TABLE IF EXISTS `inhe_customer_data`;
CREATE TABLE `inhe_customer_data` (
  `id` int(11) NOT NULL COMMENT '主键ID',
  `form_id` varchar(20) DEFAULT NULL COMMENT '登记单号',
  `name` varchar(100) DEFAULT NULL COMMENT '客户名称',
  `short_name` varchar(50) DEFAULT NULL COMMENT '简称',
  `continent` varchar(20) DEFAULT NULL COMMENT '大洲',
  `country` varchar(50) DEFAULT NULL COMMENT '国家',
  `website` varchar(100) DEFAULT NULL COMMENT '网址',
  `address` varchar(255) DEFAULT NULL COMMENT '地址',
  `summarize` text COMMENT '客户概述',
  `dept_id` int(5) DEFAULT NULL COMMENT '所属部门',
  `create_user` varchar(20) DEFAULT NULL COMMENT '登记人',
  `create_time` datetime DEFAULT NULL COMMENT '登记时间',
  `update_user` varchar(20) DEFAULT NULL,
  `update_time` datetime DEFAULT NULL,
  `approve_user` varchar(20) DEFAULT NULL,
  `approve_time` datetime DEFAULT NULL,
  `user_bu` varchar(10) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=gbk;

-- ----------------------------
-- Records of inhe_customer_data
-- ----------------------------
