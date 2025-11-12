/*
Navicat MySQL Data Transfer

Source Server         : localhost_3336
Source Server Version : 50525
Source Host           : localhost:3336
Source Database       : TD_OA

Target Server Type    : MYSQL
Target Server Version : 50525
File Encoding         : 65001

Date: 2020-09-30 18:19:39
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for inhe_project_participant
-- ----------------------------
DROP TABLE IF EXISTS `inhe_project_participant`;
CREATE TABLE `inhe_project_participant` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `form_id` varchar(30) DEFAULT NULL,
  `title` varchar(255) DEFAULT NULL,
  `dept_belong` varchar(255) DEFAULT NULL,
  `project_code` varchar(255) DEFAULT NULL,
  `customer_name` varchar(255) DEFAULT NULL,
  `leader` varchar(255) DEFAULT NULL,
  `remark` varchar(255) DEFAULT NULL,
  `status` varchar(1) DEFAULT NULL,
  `dept_id` int(11) DEFAULT NULL,
  `create_user` varchar(255) DEFAULT NULL,
  `create_time` datetime DEFAULT NULL,
  `update_user` varchar(255) DEFAULT NULL,
  `update_time` datetime DEFAULT NULL,
  `approve_user` varchar(255) DEFAULT NULL,
  `approve_time` datetime DEFAULT NULL,
  `user_bu` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=gbk;

-- ----------------------------
-- Records of inhe_project_participant
-- ----------------------------
INSERT INTO `inhe_project_participant` VALUES ('1', 'menb2020090802', ' menb2020090802/ZUP/项目参与人登记表 ', '国际二部 INTL.Business Dept.2', 'ZUP', 'Powerline Solutions&Investments Ltd.', '刘佩(Ted)', '走ZUP的合同评审', 'Y', '144', 'INHE-0662', '2020-09-08 17:03:05', 'INHE-0662', '2020-09-08 17:07:40', null, null, 'INHE');
