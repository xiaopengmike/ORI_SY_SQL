/*
Navicat MySQL Data Transfer

Source Server         : localhost_3336
Source Server Version : 50525
Source Host           : localhost:3336
Source Database       : TD_OA

Target Server Type    : MYSQL
Target Server Version : 50525
File Encoding         : 65001

Date: 2020-07-28 20:53:56
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for inhe_flow_step
-- ----------------------------
DROP TABLE IF EXISTS `inhe_flow_step`;
CREATE TABLE `inhe_flow_step` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '自增主键',
  `user_bu` varchar(10) NOT NULL COMMENT '公司归属，关联company表',
  `dept_id` varchar(10) DEFAULT NULL COMMENT '部门ID',
  `flow_type` varchar(10) NOT NULL COMMENT '流程类型',
  `bz` varchar(10) DEFAULT NULL COMMENT '步骤',
  `role` varchar(255) DEFAULT NULL COMMENT '审批人角色',
  `approve_user` varchar(20) DEFAULT NULL COMMENT '审批人ID',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=gbk;

-- ----------------------------
-- Records of inhe_flow_step
-- ----------------------------
INSERT INTO `inhe_flow_step` VALUES ('1', 'INHE', '60', 'F06', '2', '部门经理/主管', 'INHE-0051');
INSERT INTO `inhe_flow_step` VALUES ('2', 'INHE', '61', 'F06', '2', '部门经理/主管', 'INHE-0051');
INSERT INTO `inhe_flow_step` VALUES ('3', 'INHE', '66', 'F06', '2', '部门经理/主管', 'INHE-0051');
