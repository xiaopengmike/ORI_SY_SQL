/*
Navicat MySQL Data Transfer

Source Server         : localhost_3336
Source Server Version : 50525
Source Host           : localhost:3336
Source Database       : TD_OA

Target Server Type    : MYSQL
Target Server Version : 50525
File Encoding         : 65001

Date: 2020-09-30 18:18:29
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for inhe_contract_review
-- ----------------------------
DROP TABLE IF EXISTS `inhe_contract_review`;
CREATE TABLE `inhe_contract_review` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `serial_number` int(11) DEFAULT NULL,
  `form_id` varchar(50) DEFAULT NULL,
  `title` varchar(255) DEFAULT NULL,
  `customer_name` varchar(255) DEFAULT NULL,
  `contract_party` varchar(255) DEFAULT NULL,
  `project_name` varchar(255) DEFAULT NULL,
  `project_number` varchar(50) DEFAULT NULL,
  `currency` varchar(255) DEFAULT NULL,
  `delivery_time` date DEFAULT NULL,
  `project_star` varchar(255) DEFAULT NULL,
  `contract_belong` varchar(255) DEFAULT NULL,
  `dept_id` varchar(255) DEFAULT NULL,
  `create_user` varchar(255) DEFAULT NULL,
  `create_time` datetime DEFAULT NULL,
  `update_user` varchar(255) DEFAULT NULL,
  `update_time` datetime DEFAULT NULL,
  `approve_user` varchar(255) DEFAULT NULL,
  `approve_time` datetime DEFAULT NULL,
  `status` varchar(255) DEFAULT NULL,
  `step` varchar(255) DEFAULT NULL,
  `user_bu` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=gbk;

-- ----------------------------
-- Records of inhe_contract_review
-- ----------------------------
INSERT INTO `inhe_contract_review` VALUES ('1', '61', 'INHESC2020081101', 'The energy of cameroon/2007039804/CMN01/合同评审', 'The energy of cameroon', 'The energy of cameroon', 'CMN', 'CMN01', '03', '2020-08-19', '02', '银河电力', '61', 'INHE-0853', '2020-08-13 17:09:35', null, '2020-08-18 11:08:22', null, null, 'P', '1', 'INHE');
INSERT INTO `inhe_contract_review` VALUES ('2', '61', 'INHESC2020081602', 'The energy of cameroon/2007039804/CMN01/合同评审', 'The energy of cameroon', 'The energy of cameroon', 'BAP', 'BAP02', '03', '2020-08-19', '02', '银河电力', '61', 'INHE-0853', '2020-08-13 17:09:35', '', '2020-08-18 11:08:22', '', '0000-00-00 00:00:00', 'P', '1', 'INHE');
INSERT INTO `inhe_contract_review` VALUES ('3', null, 'INHESC2020082801', null, '1', null, '3', '4', '02', '2020-08-11', '00', '银河电力', '17', 'INHE-0900', '2020-08-28 16:10:10', null, null, null, null, 'P', '0', 'INHE');
INSERT INTO `inhe_contract_review` VALUES ('4', null, 'INHESC2020082802', null, '1', null, '3', '4', '02', '2020-07-29', '01', '银河电力', '17', 'INHE-0900', '2020-08-28 18:28:38', null, null, 'INHE-0051', null, 'P', '1', 'INHE');
INSERT INTO `inhe_contract_review` VALUES ('5', null, 'INHESC2020083101', null, '132', '', '312', '13', '02', '2020-07-27', '00', '银河电力', '17', 'INHE-0900', '2020-08-31 08:34:27', null, null, 'INHE-0051', null, 'P', '1', 'INHE');
