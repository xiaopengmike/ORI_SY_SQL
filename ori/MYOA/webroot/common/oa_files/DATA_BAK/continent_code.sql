/*
Navicat MySQL Data Transfer

Source Server         : localhost_3336
Source Server Version : 50525
Source Host           : localhost:3336
Source Database       : TD_OA

Target Server Type    : MYSQL
Target Server Version : 50525
File Encoding         : 65001

Date: 2020-09-30 18:21:42
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for continent_code
-- ----------------------------
DROP TABLE IF EXISTS `continent_code`;
CREATE TABLE `continent_code` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `iso_two` varchar(255) DEFAULT NULL,
  `en_name` varchar(255) DEFAULT NULL,
  `zh_name` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=gbk;

-- ----------------------------
-- Records of continent_code
-- ----------------------------
INSERT INTO `continent_code` VALUES ('1', 'AF', 'Africa', '非洲');
INSERT INTO `continent_code` VALUES ('2', 'AS', 'Asia', '亚洲');
INSERT INTO `continent_code` VALUES ('3', 'EU', 'Europe', '欧洲');
INSERT INTO `continent_code` VALUES ('4', 'NA', 'North America', '北美洲');
INSERT INTO `continent_code` VALUES ('5', 'OC', 'Oceania', '大洋洲');
INSERT INTO `continent_code` VALUES ('6', 'SA', 'South America', '南美洲');
INSERT INTO `continent_code` VALUES ('7', 'AN', 'Antarctica', '南极洲');
