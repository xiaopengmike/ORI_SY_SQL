/*
Navicat MySQL Data Transfer

Source Server         : localhost_3336
Source Server Version : 50525
Source Host           : localhost:3336
Source Database       : TD_OA

Target Server Type    : MYSQL
Target Server Version : 50525
File Encoding         : 65001

Date: 2020-11-03 12:39:09
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for mail
-- ----------------------------
DROP TABLE IF EXISTS `mail`;
CREATE TABLE `mail` (
  `MAIL_ID` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `SUBJECT` varchar(250) DEFAULT NULL,
  `FROM_ID` varchar(20) DEFAULT NULL,
  `SEND_TO` text,
  `CC_TO` text,
  `FW_TO` text,
  `SEND_TIME` int(10) unsigned DEFAULT '0',
  `RR_ID` text,
  `REPLY_TIME` int(10) DEFAULT '0',
  `M_COLLECTION` text,
  `PM_ID` varchar(12) DEFAULT NULL,
  `T_type` varchar(5) DEFAULT NULL,
  `M_view` varchar(1) DEFAULT NULL,
  PRIMARY KEY (`MAIL_ID`),
  KEY `FROM_ID` (`FROM_ID`),
  KEY `SEND_TIME` (`SEND_TIME`),
  KEY `REPLY_TIME` (`REPLY_TIME`)
) ENGINE=InnoDB AUTO_INCREMENT=82 DEFAULT CHARSET=gbk;

-- ----------------------------
-- Records of mail
-- ----------------------------
INSERT INTO `mail` VALUES ('10', '郭林鹏调薪通知', 'HR02', 'INHE-0450,', 'INHE-0692,IM-0015,INHE-0450,', null, '1595922882', null, '1595922882', null, null, null, null);
INSERT INTO `mail` VALUES ('11', '梁永青调薪通知', 'HR02', 'INHE-0622,', 'INHE-0692,IM-0015,INHE-0450,', null, '1595922882', null, '1595922882', null, null, null, null);
INSERT INTO `mail` VALUES ('12', '易双荣调薪通知', 'HR02', 'INHE-0900,', 'INHE-0692,IM-0015,INHE-0450,', null, '1595922882', null, '1595922882', null, null, null, null);
INSERT INTO `mail` VALUES ('13', '郭林鹏调薪通知', 'HR02', 'INHE-0450,', 'INHE-0692,IM-0015,INHE-0450,', null, '1595925113', null, '1595925113', null, null, null, null);
INSERT INTO `mail` VALUES ('14', '梁永青调薪通知', 'HR02', 'INHE-0622,', 'INHE-0692,IM-0015,INHE-0450,', null, '1595925113', null, '1595925113', null, null, null, null);
INSERT INTO `mail` VALUES ('15', '易双荣调薪通知', 'HR02', 'INHE-0900,', 'INHE-0692,IM-0015,INHE-0450,', null, '1595925113', null, '1595925113', null, null, null, null);
INSERT INTO `mail` VALUES ('16', '郭林鹏调薪通知', 'HR02', 'INHE-0450,', 'INHE-0692,IM-0015,INHE-0450,', null, '1595925279', null, '1595925279', null, null, null, null);
INSERT INTO `mail` VALUES ('17', '梁永青调薪通知', 'HR02', 'INHE-0622,', 'INHE-0692,IM-0015,INHE-0450,', null, '1595925279', null, '1595925279', null, null, null, null);
INSERT INTO `mail` VALUES ('18', '易双荣调薪通知', 'HR02', 'INHE-0900,', 'INHE-0692,IM-0015,INHE-0450,', null, '1595925279', null, '1595925279', null, null, null, null);
INSERT INTO `mail` VALUES ('19', '郭林鹏调薪通知', 'HR02', 'INHE-0450,', 'INHE-0692,IM-0015,INHE-0450,', null, '1595930881', null, '1595930881', null, null, null, null);
INSERT INTO `mail` VALUES ('20', '梁永青调薪通知', 'HR02', 'INHE-0622,', 'INHE-0692,IM-0015,INHE-0450,', null, '1595930881', null, '1595930881', null, null, null, null);
INSERT INTO `mail` VALUES ('21', '易双荣调薪通知', 'HR02', 'INHE-0900,', 'INHE-0692,IM-0015,INHE-0450,', null, '1595930881', null, '1595930881', null, null, null, null);
INSERT INTO `mail` VALUES ('22', '郭林鹏调薪通知', 'HR02', 'INHE-0450,', 'INHE-0692,IM-0015,INHE-0450,', null, '1595930926', null, '1595930926', null, null, null, null);
INSERT INTO `mail` VALUES ('23', '梁永青调薪通知', 'HR02', 'INHE-0622,', 'INHE-0692,IM-0015,INHE-0450,', null, '1595930926', null, '1595930926', null, null, null, null);
INSERT INTO `mail` VALUES ('24', '易双荣调薪通知', 'HR02', 'INHE-0900,', 'INHE-0692,IM-0015,INHE-0450,', null, '1595930926', null, '1595930926', null, null, null, null);
INSERT INTO `mail` VALUES ('25', '郭林鹏调薪通知', 'HR02', 'INHE-0450,', 'INHE-0692,IM-0015,INHE-0450,', null, '1595931355', null, '1595931355', null, null, null, null);
INSERT INTO `mail` VALUES ('26', '梁永青调薪通知', 'HR02', 'INHE-0622,', 'INHE-0692,IM-0015,INHE-0450,', null, '1595931355', null, '1595931355', null, null, null, null);
INSERT INTO `mail` VALUES ('27', '易双荣调薪通知', 'HR02', 'INHE-0900,', 'INHE-0692,IM-0015,INHE-0450,', null, '1595931355', null, '1595931355', null, null, null, null);
INSERT INTO `mail` VALUES ('28', '郭林鹏调薪通知', 'HR02', 'INHE-0450,', 'INHE-0692,IM-0015,INHE-0450,', null, '1595931368', null, '1595931368', null, null, null, null);
INSERT INTO `mail` VALUES ('29', '梁永青调薪通知', 'HR02', 'INHE-0622,', 'INHE-0692,IM-0015,INHE-0450,', null, '1595931368', null, '1595931368', null, null, null, null);
INSERT INTO `mail` VALUES ('30', '易双荣调薪通知', 'HR02', 'INHE-0900,', 'INHE-0692,IM-0015,INHE-0450,', null, '1595931368', null, '1595931368', null, null, null, null);
INSERT INTO `mail` VALUES ('31', '郭林鹏调薪通知', 'HR02', 'INHE-0450,', 'INHE-0692,IM-0015,INHE-0450,', null, '1595931382', null, '1595931382', null, null, null, null);
INSERT INTO `mail` VALUES ('32', '梁永青调薪通知', 'HR02', 'INHE-0622,', 'INHE-0692,IM-0015,INHE-0450,', null, '1595931382', null, '1595931382', null, null, null, null);
INSERT INTO `mail` VALUES ('33', '易双荣调薪通知', 'HR02', 'INHE-0900,', 'INHE-0692,IM-0015,INHE-0450,', null, '1595931382', null, '1595931382', null, null, null, null);
INSERT INTO `mail` VALUES ('34', '郭林鹏调薪通知', 'HR02', 'INHE-0450,', 'INHE-0692,IM-0015,INHE-0450,', null, '1595931389', null, '1595931389', null, null, null, null);
INSERT INTO `mail` VALUES ('35', '梁永青调薪通知', 'HR02', 'INHE-0622,', 'INHE-0692,IM-0015,INHE-0450,', null, '1595931389', null, '1595931389', null, null, null, null);
INSERT INTO `mail` VALUES ('36', '易双荣调薪通知', 'HR02', 'INHE-0900,', 'INHE-0692,IM-0015,INHE-0450,', null, '1595931389', null, '1595931389', null, null, null, null);
INSERT INTO `mail` VALUES ('37', '郭林鹏调薪通知', 'HR02', 'INHE-0450,', 'INHE-0692,IM-0015,INHE-0450,', null, '1595931408', null, '1595931408', null, null, null, null);
INSERT INTO `mail` VALUES ('38', '梁永青调薪通知', 'HR02', 'INHE-0622,', 'INHE-0692,IM-0015,INHE-0450,', null, '1595931408', null, '1595931408', null, null, null, null);
INSERT INTO `mail` VALUES ('39', '易双荣调薪通知', 'HR02', 'INHE-0900,', 'INHE-0692,IM-0015,INHE-0450,', null, '1595931408', null, '1595931408', null, null, null, null);
INSERT INTO `mail` VALUES ('40', '郭林鹏调薪通知', 'HR02', 'INHE-0450,', 'INHE-0692,IM-0015,INHE-0450,', null, '1595931415', null, '1595931415', null, null, null, null);
INSERT INTO `mail` VALUES ('41', '梁永青调薪通知', 'HR02', 'INHE-0622,', 'INHE-0692,IM-0015,INHE-0450,', null, '1595931415', null, '1595931415', null, null, null, null);
INSERT INTO `mail` VALUES ('42', '易双荣调薪通知', 'HR02', 'INHE-0900,', 'INHE-0692,IM-0015,INHE-0450,', null, '1595931415', null, '1595931415', null, null, null, null);
INSERT INTO `mail` VALUES ('43', '郭林鹏调薪通知', 'HR02', 'INHE-0450,', 'INHE-0692,IM-0015,INHE-0450,', null, '1595931430', null, '1595931430', null, null, null, null);
INSERT INTO `mail` VALUES ('44', '梁永青调薪通知', 'HR02', 'INHE-0622,', 'INHE-0692,IM-0015,INHE-0450,', null, '1595931430', null, '1595931430', null, null, null, null);
INSERT INTO `mail` VALUES ('45', '易双荣调薪通知', 'HR02', 'INHE-0900,', 'INHE-0692,IM-0015,INHE-0450,', null, '1595931430', null, '1595931430', null, null, null, null);
INSERT INTO `mail` VALUES ('46', '郭林鹏调薪通知', 'HR02', 'INHE-0450,', 'INHE-0692,IM-0015,INHE-0450,', null, '1595931441', null, '1595931441', null, null, null, null);
INSERT INTO `mail` VALUES ('47', '梁永青调薪通知', 'HR02', 'INHE-0622,', 'INHE-0692,IM-0015,INHE-0450,', null, '1595931441', null, '1595931441', null, null, null, null);
INSERT INTO `mail` VALUES ('48', '易双荣调薪通知', 'HR02', 'INHE-0900,', 'INHE-0692,IM-0015,INHE-0450,', null, '1595931441', null, '1595931441', null, null, null, null);
INSERT INTO `mail` VALUES ('49', '郭林鹏调薪通知', 'HR02', 'INHE-0450,', 'INHE-0692,IM-0015,INHE-0450,', null, '1595931446', null, '1595931446', null, null, null, null);
INSERT INTO `mail` VALUES ('50', '梁永青调薪通知', 'HR02', 'INHE-0622,', 'INHE-0692,IM-0015,INHE-0450,', null, '1595931446', null, '1595931446', null, null, null, null);
INSERT INTO `mail` VALUES ('51', '易双荣调薪通知', 'HR02', 'INHE-0900,', 'INHE-0692,IM-0015,INHE-0450,', null, '1595931446', null, '1595931446', null, null, null, null);
INSERT INTO `mail` VALUES ('52', '郭林鹏调薪通知', 'HR02', 'INHE-0450,', 'INHE-0900,,', null, '1595983557', null, '1595983557', null, null, null, null);
INSERT INTO `mail` VALUES ('53', '梁永青调薪通知', 'HR02', 'INHE-0622,', 'INHE-0900,,', null, '1595983557', null, '1595983557', null, null, null, null);
INSERT INTO `mail` VALUES ('54', '易双荣调薪通知', 'HR02', 'INHE-0900,', 'INHE-0900,,', null, '1595983557', null, '1595983557', null, null, null, null);
INSERT INTO `mail` VALUES ('55', '郭林鹏调薪通知', 'HR02', 'INHE-0450,', 'INHE-0900,IM-0015,INHE-0450,', null, '1595987470', null, '1595987470', null, null, null, null);
INSERT INTO `mail` VALUES ('56', '梁永青调薪通知', 'HR02', 'INHE-0622,', 'INHE-0900,IM-0015,INHE-0450,', null, '1595987470', null, '1595987470', null, null, null, null);
INSERT INTO `mail` VALUES ('57', '易双荣调薪通知', 'HR02', 'INHE-0900,', 'INHE-0900,IM-0015,INHE-0450,', null, '1595987470', null, '1595987470', null, null, null, null);
INSERT INTO `mail` VALUES ('58', '郭林鹏调薪通知', 'HR02', 'INHE-0450,', 'INHE-0692,IM-0015,INHE-0450,', null, '1595987656', null, '1595987656', null, null, null, null);
INSERT INTO `mail` VALUES ('59', '梁永青调薪通知', 'HR02', 'INHE-0622,', 'INHE-0692,IM-0015,INHE-0450,', null, '1595987656', null, '1595987656', null, null, null, null);
INSERT INTO `mail` VALUES ('60', '易双荣调薪通知', 'HR02', 'INHE-0900,', 'INHE-0692,IM-0015,INHE-0450,', null, '1595987656', null, '1595987656', null, null, null, null);
INSERT INTO `mail` VALUES ('61', '郭林鹏调薪通知', 'HR02', 'INHE-0450,', 'INHE-0692,IM-0015,INHE-0450,INHE-0284,INHE-0900,', null, '1595988993', null, '1595988993', null, null, null, null);
INSERT INTO `mail` VALUES ('62', '梁永青调薪通知', 'HR02', 'INHE-0622,', 'INHE-0692,IM-0015,INHE-0450,INHE-0284,INHE-0900,', null, '1595988993', null, '1595988993', null, null, null, null);
INSERT INTO `mail` VALUES ('63', '易双荣调薪通知', 'HR02', 'INHE-0900,', 'INHE-0692,IM-0015,INHE-0450,INHE-0284,INHE-0900,', null, '1595988993', null, '1595988993', null, null, null, null);
INSERT INTO `mail` VALUES ('64', '郭林鹏调薪通知', 'HR02', 'INHE-0450,', 'INHE-0692,IM-0015,INHE-0450,INHE-0284,INHE-0900,', null, '1595989694', null, '1595989694', null, null, null, null);
INSERT INTO `mail` VALUES ('65', '梁永青调薪通知', 'HR02', 'INHE-0622,', 'INHE-0692,IM-0015,INHE-0450,INHE-0284,INHE-0900,', null, '1595989694', null, '1595989694', null, null, null, null);
INSERT INTO `mail` VALUES ('66', '易双荣调薪通知', 'HR02', 'INHE-0900,', 'INHE-0692,IM-0015,INHE-0450,INHE-0284,INHE-0900,', null, '1595989694', null, '1595989694', null, null, null, null);
INSERT INTO `mail` VALUES ('67', '郭林鹏调薪通知', 'HR02', 'INHE-0450,', 'INHE-0692,IM-0015,INHE-0450,INHE-0284,INHE-0900,', null, '1595990846', null, '1595990846', null, null, null, null);
INSERT INTO `mail` VALUES ('68', '梁永青调薪通知', 'HR02', 'INHE-0622,', 'INHE-0692,IM-0015,INHE-0450,INHE-0284,INHE-0900,', null, '1595990846', null, '1595990846', null, null, null, null);
INSERT INTO `mail` VALUES ('69', '易双荣调薪通知', 'HR02', 'INHE-0900,', 'INHE-0692,IM-0015,INHE-0450,INHE-0284,INHE-0900,', null, '1595990846', null, '1595990846', '', null, null, 'W');
INSERT INTO `mail` VALUES ('70', '郭林鹏调薪通知', 'HR02', 'INHE-0450,', 'INHE-0692,IM-0015,INHE-0450,INHE-0284,INHE-0900,', null, '1595991646', null, '1595991646', null, null, null, null);
INSERT INTO `mail` VALUES ('71', '梁永青调薪通知', 'HR02', 'INHE-0622,', 'INHE-0692,IM-0015,INHE-0450,INHE-0284,INHE-0900,', null, '1595991646', null, '1595991646', null, null, null, null);
INSERT INTO `mail` VALUES ('72', '郭林鹏调薪通知', 'HR02', 'INHE-0450,', 'INHE-0692,IM-0015,INHE-0450,INHE-0284,INHE-0900,', null, '1595993080', null, '1595993080', null, null, null, null);
INSERT INTO `mail` VALUES ('73', '梁永青调薪通知', 'HR02', 'INHE-0622,', 'INHE-0692,IM-0015,INHE-0450,INHE-0284,INHE-0900,', null, '1595993080', null, '1595993080', null, null, null, null);
INSERT INTO `mail` VALUES ('74', '易双荣调薪通知', 'HR02', 'INHE-0900,', 'INHE-0692,IM-0015,INHE-0450,INHE-0284,INHE-0900,', null, '1595993080', null, '1595993080', null, null, null, null);
INSERT INTO `mail` VALUES ('75', '郭林鹏调薪通知', 'HR02', 'INHE-0450,', 'IM-0015,INHE-0450,INHE-0692,INHE-0284,INHE-0900,', null, '1596074856', null, '1596074856', null, null, null, null);
INSERT INTO `mail` VALUES ('76', '梁永青调薪通知', 'HR02', 'INHE-0622,', 'IM-0015,INHE-0450,INHE-0692,INHE-0284,INHE-0900,', null, '1596074856', null, '1596074856', null, null, null, null);
INSERT INTO `mail` VALUES ('77', '易双荣调薪通知', 'HR02', 'INHE-0900,', 'IM-0015,INHE-0450,INHE-0692,INHE-0284,INHE-0900,', null, '1596074856', null, '1596074856', null, null, null, 'W');
INSERT INTO `mail` VALUES ('78', '33', 'INHE-0900', '', '', null, '1600395846', null, '1600395846', null, '', '', null);
INSERT INTO `mail` VALUES ('79', '33', 'INHE-0900', '', '', null, '1600396123', null, '1600396123', null, '', '', null);
INSERT INTO `mail` VALUES ('80', '5555', 'INHE-0900', '', '', null, '1600396280', null, '1600396280', null, '', '', null);
INSERT INTO `mail` VALUES ('81', 'TTD项目商务支持申请', 'INHE-0900', '', '', null, '1600398360', null, '1600398544', ',INHE-0900', '', '', null);
