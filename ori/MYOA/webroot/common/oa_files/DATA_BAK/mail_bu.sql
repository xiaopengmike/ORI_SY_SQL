/*
Navicat MySQL Data Transfer

Source Server         : localhost_3336
Source Server Version : 50525
Source Host           : localhost:3336
Source Database       : TD_OA

Target Server Type    : MYSQL
Target Server Version : 50525
File Encoding         : 65001

Date: 2020-11-03 12:39:21
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for mail_bu
-- ----------------------------
DROP TABLE IF EXISTS `mail_bu`;
CREATE TABLE `mail_bu` (
  `FROM_ID` varchar(20) DEFAULT NULL,
  `USER_ID` varchar(40) DEFAULT NULL,
  `MAIL_ID` int(10) unsigned DEFAULT NULL,
  `SUBJECT` varchar(250) DEFAULT NULL,
  `SEND_TO` text,
  `CC_TO` text,
  `FW_TO` text,
  `SEND_TIME` int(10) unsigned DEFAULT NULL,
  `RR_ID` text,
  `REPLY_TIME` int(10) DEFAULT NULL,
  `M_COLLECTION` text,
  `PM_ID` varchar(12) DEFAULT NULL,
  `T_type` varchar(5) DEFAULT NULL,
  `USER_BU` varchar(55) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=gbk;

-- ----------------------------
-- Records of mail_bu
-- ----------------------------
