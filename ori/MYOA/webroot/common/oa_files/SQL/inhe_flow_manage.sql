/*
Navicat MySQL Data Transfer

Source Server         : localhost_3336
Source Server Version : 50525
Source Host           : localhost:3336
Source Database       : TD_OA

Target Server Type    : MYSQL
Target Server Version : 50525
File Encoding         : 65001

Date: 2020-08-11 19:51:40
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for inhe_flow_manage
-- ----------------------------
DROP TABLE IF EXISTS `inhe_flow_manage`;
CREATE TABLE `inhe_flow_manage` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '自增主键',
  `flow_id` varchar(30) DEFAULT NULL COMMENT '流程ID',
  `step` int(11) DEFAULT NULL COMMENT '步骤',
  `role` varchar(50) DEFAULT NULL,
  `approver` varchar(30) DEFAULT NULL,
  `next_step` varchar(100) DEFAULT NULL,
  `operate` varchar(20) DEFAULT NULL,
  `star_select` varchar(255) DEFAULT NULL,
  `remind_content` text,
  `project_user_select` varchar(255) DEFAULT NULL,
  `notice_user` varchar(255) DEFAULT NULL,
  `notice_dept` varchar(255) DEFAULT NULL,
  `if_show` varchar(255) DEFAULT NULL,
  `type` varchar(20) DEFAULT NULL,
  `type_desc` varchar(255) DEFAULT NULL,
  `user_bu` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=gbk;

-- ----------------------------
-- Records of inhe_flow_manage
-- ----------------------------
INSERT INTO `inhe_flow_manage` VALUES ('1', '1', '1', '项目经理', null, '2', 'approve', '1', null, null, null, null, '1', 'project_review', '立项申请流程', 'INHE');
INSERT INTO `inhe_flow_manage` VALUES ('2', '1', '2', '商务组确认项目代号', 'INHE-0662', '3', 'approve', '', null, null, null, null, '0', 'project_review', '立项申请流程', 'INHE');
INSERT INTO `inhe_flow_manage` VALUES ('3', '1', '3', '商务部经理', 'INHE-0051', '4', 'approve', '1', null, null, null, null, '1', 'project_review', '立项申请流程', 'INHE');
INSERT INTO `inhe_flow_manage` VALUES ('4', '1', '4', '市场总监', null, '5', 'approve', '1', '此项要求：市场总监同意后可选择继续提交，或者暂不提交，待项目确定后再继续提交。', '1', null, null, '1', 'project_review', '立项申请流程', 'INHE');
INSERT INTO `inhe_flow_manage` VALUES ('5', '1', '5', '项目融资和风险控制', 'INHE-0260', '6', 'approve', '1', null, null, null, null, '1', 'project_review', '立项申请流程', 'INHE');
INSERT INTO `inhe_flow_manage` VALUES ('6', '1', '6', '技术支持总监', 'INHE-0144', '7', 'approve', '1', null, '1', null, null, '1', 'project_review', '立项申请流程', 'INHE');
INSERT INTO `inhe_flow_manage` VALUES ('7', '1', '7', '技术总监', 'szdfm', '8', 'approve', '1', null, '1', null, null, '1', 'project_review', '立项申请流程', 'INHE');
INSERT INTO `inhe_flow_manage` VALUES ('8', '1', '8', '董事长', 'meterw', '9', 'approve', '1', '董事长审核通过后流向跟单组组长，跟单组组长依据技术总监指定人选择提交下一步。可对下一步研发接收人员对应屏蔽一些字段。', null, null, null, '1', 'project_review', '立项申请流程', 'INHE');
INSERT INTO `inhe_flow_manage` VALUES ('9', '1', '9', '备案', null, null, 'record,notice', null, null, null, null, null, '0', 'project_review', '立项申请流程', 'INHE');
INSERT INTO `inhe_flow_manage` VALUES ('10', '2', '1', '商务审核', 'INHE-0051', '1', 'approve', null, null, null, null, null, null, 'customer_register', '客户信息登记', 'INHE');
INSERT INTO `inhe_flow_manage` VALUES ('11', '3', '1', '商务部经理', null, '2', 'approve', null, null, null, null, null, null, 'sales_contract_revie', '销售合同评审', 'INHE');
INSERT INTO `inhe_flow_manage` VALUES ('12', '1', '10', '结束流程', null, null, null, null, null, null, null, null, null, null, null, null);
