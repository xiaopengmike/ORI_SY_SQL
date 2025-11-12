/*
Navicat MySQL Data Transfer

Source Server         : localhost_3336
Source Server Version : 50525
Source Host           : localhost:3336
Source Database       : TD_OA

Target Server Type    : MYSQL
Target Server Version : 50525
File Encoding         : 65001

Date: 2020-08-06 20:07:07
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for inhe_customer_info
-- ----------------------------
DROP TABLE IF EXISTS `inhe_customer_info`;
CREATE TABLE `inhe_customer_info` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `form_id` varchar(20) DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  `customer_id` varchar(20) DEFAULT NULL,
  `country` varchar(255) DEFAULT NULL,
  `contact` varchar(255) DEFAULT NULL,
  `phone` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `introduction` text,
  `type` varchar(255) DEFAULT NULL COMMENT '1代理商；2招标方；3买方',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=gbk;

-- ----------------------------
-- Records of inhe_customer_info
-- ----------------------------
