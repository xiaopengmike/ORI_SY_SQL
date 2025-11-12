/*
Navicat MySQL Data Transfer

Source Server         : localhost_3336
Source Server Version : 50525
Source Host           : localhost:3336
Source Database       : TD_OA

Target Server Type    : MYSQL
Target Server Version : 50525
File Encoding         : 65001

Date: 2020-08-13 11:35:46
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for inhe_project_main
-- ----------------------------
DROP TABLE IF EXISTS `inhe_project_main`;
CREATE TABLE `inhe_project_main` (
  `id` int(11) NOT NULL,
  `form_id` varchar(20) DEFAULT NULL,
  `project_code` varchar(255) DEFAULT NULL,
  `remark` varchar(255) DEFAULT NULL,
  `dept_id` int(11) DEFAULT NULL,
  `create_user` varchar(255) DEFAULT NULL,
  `create_time` datetime DEFAULT NULL,
  `update_user` datetime DEFAULT NULL,
  `update_time` datetime DEFAULT NULL,
  `approve_user` varchar(255) DEFAULT NULL,
  `approve_time` datetime DEFAULT NULL,
  `status` varchar(255) DEFAULT NULL,
  `step` varchar(255) DEFAULT NULL,
  `user_bu` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=gbk;

-- ----------------------------
-- Records of inhe_project_main
-- ----------------------------
INSERT INTO `inhe_project_main` VALUES ('1', 'lxdj202008110001', 'GPA', null, '17', 'INHE-0900', '2020-08-11 09:30:09', null, null, 'INHE-0900', null, 'P', '0', 'INHE');
