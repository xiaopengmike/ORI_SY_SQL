/*
Navicat MySQL Data Transfer

Source Server         : localhost_3336
Source Server Version : 50525
Source Host           : localhost:3336
Source Database       : TD_OA

Target Server Type    : MYSQL
Target Server Version : 50525
File Encoding         : 65001

Date: 2020-08-11 19:51:49
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for inhe_flow_opinion
-- ----------------------------
DROP TABLE IF EXISTS `inhe_flow_opinion`;
CREATE TABLE `inhe_flow_opinion` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `flow_id` varchar(30) DEFAULT NULL,
  `step` varchar(255) DEFAULT NULL,
  `role` varchar(255) DEFAULT NULL,
  `approver` varchar(255) DEFAULT NULL,
  `next` varchar(255) DEFAULT NULL,
  `opinion` varchar(255) DEFAULT NULL,
  `project_star` varchar(255) DEFAULT NULL,
  `have_manager` varchar(255) DEFAULT NULL,
  `time` datetime DEFAULT NULL,
  `if_agree` varchar(255) DEFAULT NULL,
  `status` varchar(255) DEFAULT NULL,
  `approve_round` varchar(255) DEFAULT NULL,
  `type` varchar(255) DEFAULT NULL,
  `user_bu` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=gbk;

-- ----------------------------
-- Records of inhe_flow_opinion
-- ----------------------------
INSERT INTO `inhe_flow_opinion` VALUES ('1', 'lxdj202008110001', '1', '申请人', 'INHE-0900', 'INHE-0662', '总部和分公司的付款流程权限调整一下，总部的取消江西国内国际付款流程的权限，只开分公司的，江西采购的开放总部的付款流程，取消分公司的付款流程', '3', '0', '2020-08-11 11:50:20', '1', 'H', '1', 'project_review', 'INHE');
INSERT INTO `inhe_flow_opinion` VALUES ('2', 'lxdj202008110001', '2', '商务组确认项目代号', 'INHE-0662', 'INHE-0051', '2', '3', null, '2020-08-11 16:29:23', '1', 'H', '1', 'project_review', 'INHE');
INSERT INTO `inhe_flow_opinion` VALUES ('3', 'lxdj202008110001', '3', '商务部经理', 'INHE-0051', 'INHE-0702', '3', '3', '', '2020-08-11 16:29:23', '1', 'H', '1', 'project_review', 'INHE');
INSERT INTO `inhe_flow_opinion` VALUES ('4', 'lxdj202008110001', '4', '市场总监', 'INHE-0702', 'INHE-0260', '3', '3', '', '2020-08-11 16:29:23', '1', 'H', '1', 'project_review', 'INHE');
INSERT INTO `inhe_flow_opinion` VALUES ('5', 'lxdj202008110001', '5', '项目融资和风险控制', 'INHE-0260', 'INHE-0144', '3', '3', '', '2020-08-11 16:29:23', '1', 'H', '1', 'project_review', 'INHE');
INSERT INTO `inhe_flow_opinion` VALUES ('6', 'lxdj202008110001', '6', '技术支持总监', 'INHE-0144', 'szdfm', '3', '3', '', '2020-08-11 16:29:23', '1', 'H', '1', 'project_review', 'INHE');
INSERT INTO `inhe_flow_opinion` VALUES ('7', 'lxdj202008110001', '7', '技术总监', 'szdfm', 'meterw', '3', '3', '', '2020-08-11 16:29:23', '1', 'H', '1', 'project_review', 'INHE');
INSERT INTO `inhe_flow_opinion` VALUES ('8', 'lxdj202008110001', '8', '董事长', 'meterw', 'INHE-0662', '3', '3', null, '2020-08-11 16:42:11', '1', 'H', '1', 'project_review', 'INHE');
INSERT INTO `inhe_flow_opinion` VALUES ('9', 'lxdj202008110001', '9', '商务备案', 'INHE-0662', null, null, null, null, '2020-08-11 17:40:37', '1', 'E', '1', 'project_review', 'INHE');
