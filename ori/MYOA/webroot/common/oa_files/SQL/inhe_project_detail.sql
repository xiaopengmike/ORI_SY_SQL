/*
Navicat MySQL Data Transfer

Source Server         : oa
Source Server Version : 50525
Source Host           : localhost:3336
Source Database       : TD_OA

Target Server Type    : MYSQL
Target Server Version : 50525
File Encoding         : 65001

Date: 2020-08-03 17:15:13
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for inhe_project_detail
-- ----------------------------
DROP TABLE IF EXISTS `inhe_project_detail`;
CREATE TABLE `inhe_project_detail` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `form_id` varchar(20) DEFAULT NULL,
  `product` varchar(255) DEFAULT NULL,
  `price` decimal(10,2) DEFAULT NULL,
  `first_price` decimal(10,2) DEFAULT NULL,
  `standard` varchar(255) DEFAULT NULL,
  `number` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=gbk;

-- ----------------------------
-- Records of inhe_project_detail
-- ----------------------------
