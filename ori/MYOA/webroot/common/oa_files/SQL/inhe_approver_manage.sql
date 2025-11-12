/*
Navicat MySQL Data Transfer

Source Server         : localhost_3336
Source Server Version : 50525
Source Host           : localhost:3336
Source Database       : TD_OA

Target Server Type    : MYSQL
Target Server Version : 50525
File Encoding         : 65001

Date: 2020-07-28 20:54:40
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for inhe_approver_manage
-- ----------------------------
DROP TABLE IF EXISTS `inhe_approver_manage`;
CREATE TABLE `inhe_approver_manage` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `dept_id` varchar(20) DEFAULT NULL,
  `dept_name` varchar(100) DEFAULT NULL,
  `manager` varchar(30) DEFAULT NULL,
  `module_type` varchar(30) DEFAULT NULL,
  `module_name` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=gbk;

-- ----------------------------
-- Records of inhe_approver_manage
-- ----------------------------
INSERT INTO `inhe_approver_manage` VALUES ('2', '55', '跟单组(Logistics Division)', 'INHE-0051', 'F03', '人事异动流程');
INSERT INTO `inhe_approver_manage` VALUES ('3', '104', '章春鹏项目组', 'INHE-0247', 'F03', '人事异动流程');
INSERT INTO `inhe_approver_manage` VALUES ('4', '105', '王顺万项目组', 'INHE-0247', 'F03', '人事异动流程');
INSERT INTO `inhe_approver_manage` VALUES ('5', '106', '何梓劲项目组', 'INHE-0247', 'F03', '人事异动流程');
INSERT INTO `inhe_approver_manage` VALUES ('6', '107', '王占颍项目组', 'INHE-0247', 'F03', '人事异动流程');
INSERT INTO `inhe_approver_manage` VALUES ('7', '108', '张建刚硬件组', 'INHE-0247', 'F03', '人事异动流程');
INSERT INTO `inhe_approver_manage` VALUES ('8', '143', '竞标组', 'INHE-0051', 'F03', '人事异动流程');
INSERT INTO `inhe_approver_manage` VALUES ('9', '144', '投标英语组', 'INHE-0051', 'F03', '人事异动流程');
INSERT INTO `inhe_approver_manage` VALUES ('10', '162', '方案组', 'INHE-0144', 'F03', '人事异动流程');
INSERT INTO `inhe_approver_manage` VALUES ('11', '163', '投标组', 'INHE-0144', 'F03', '人事异动流程');
INSERT INTO `inhe_approver_manage` VALUES ('12', '164', '交付组', 'INHE-0144', 'F03', '人事异动流程');
INSERT INTO `inhe_approver_manage` VALUES ('13', '167', '投标法语组', 'INHE-0051', 'F03', '人事异动流程');
INSERT INTO `inhe_approver_manage` VALUES ('14', '14', '标准研究与文控部', 'szdfm', 'F03', '人事异动流程');
