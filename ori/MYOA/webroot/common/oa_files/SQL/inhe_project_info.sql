/*
Navicat MySQL Data Transfer

Source Server         : oa
Source Server Version : 50525
Source Host           : localhost:3336
Source Database       : TD_OA

Target Server Type    : MYSQL
Target Server Version : 50525
File Encoding         : 65001

Date: 2020-08-03 17:15:19
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for inhe_project_info
-- ----------------------------
DROP TABLE IF EXISTS `inhe_project_info`;
CREATE TABLE `inhe_project_info` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `form_id` varchar(20) DEFAULT NULL,
  `project_code` varchar(255) DEFAULT NULL,
  `country` varchar(255) DEFAULT NULL,
  `scope` varchar(255) DEFAULT NULL,
  `project_type` varchar(255) DEFAULT NULL,
  `prototype` varchar(255) DEFAULT NULL COMMENT '样机需求',
  `potential` varchar(255) DEFAULT NULL COMMENT '项目潜力',
  `background` varchar(255) DEFAULT NULL,
  `explain_user` varchar(255) DEFAULT NULL,
  `explain_project` varchar(255) DEFAULT NULL,
  `service` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=gbk;

-- ----------------------------
-- Records of inhe_project_info
-- ----------------------------
