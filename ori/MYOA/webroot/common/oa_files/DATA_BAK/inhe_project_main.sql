/*
Navicat MySQL Data Transfer

Source Server         : localhost_3336
Source Server Version : 50525
Source Host           : localhost:3336
Source Database       : TD_OA

Target Server Type    : MYSQL
Target Server Version : 50525
File Encoding         : 65001

Date: 2020-09-30 18:17:44
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for inhe_project_main
-- ----------------------------
DROP TABLE IF EXISTS `inhe_project_main`;
CREATE TABLE `inhe_project_main` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `form_id` varchar(20) DEFAULT NULL,
  `title` varchar(255) DEFAULT NULL,
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
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=gbk;

-- ----------------------------
-- Records of inhe_project_main
-- ----------------------------
INSERT INTO `inhe_project_main` VALUES ('1', 'lxdj202008110001', null, 'GPA', '', '17', 'INHE-0900', '2020-08-11 09:30:09', null, null, 'INHE-0662', '2020-08-21 14:11:36', 'P', '2', 'INHE');
INSERT INTO `inhe_project_main` VALUES ('7', 'lxdj202008170002', null, 'PPP', '', '17', 'INHE-0900', '2020-08-17 17:48:39', null, null, '', '2020-08-21 14:11:18', 'E', '11', 'INHE');
INSERT INTO `inhe_project_main` VALUES ('8', 'lxdj2020081801', 'lxdj2020081801/GAB', 'GAB', '无', '172', 'INHE-0913', '2020-08-18 17:09:57', null, '2020-08-24 08:42:14', null, null, 'E', '11', 'INHE');
