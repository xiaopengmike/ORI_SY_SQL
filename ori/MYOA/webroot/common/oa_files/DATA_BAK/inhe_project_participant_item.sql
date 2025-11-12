/*
Navicat MySQL Data Transfer

Source Server         : localhost_3336
Source Server Version : 50525
Source Host           : localhost:3336
Source Database       : TD_OA

Target Server Type    : MYSQL
Target Server Version : 50525
File Encoding         : 65001

Date: 2020-09-30 18:19:46
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for inhe_project_participant_item
-- ----------------------------
DROP TABLE IF EXISTS `inhe_project_participant_item`;
CREATE TABLE `inhe_project_participant_item` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `form_id` varchar(30) DEFAULT NULL,
  `title` varchar(255) DEFAULT NULL,
  `serial_number` int(11) DEFAULT NULL,
  `user_id` varchar(30) DEFAULT NULL,
  `user_name` varchar(255) DEFAULT NULL,
  `proportion` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=gbk;

-- ----------------------------
-- Records of inhe_project_participant_item
-- ----------------------------
INSERT INTO `inhe_project_participant_item` VALUES ('1', 'menb2020090802', 'menb2020090802/ZUP/项目参与人登记表', '1', '', '付文涛(Leo)', null);
INSERT INTO `inhe_project_participant_item` VALUES ('2', 'menb2020090802', 'menb2020090802/ZUP/项目参与人登记表', '2', null, '陈丽', null);
