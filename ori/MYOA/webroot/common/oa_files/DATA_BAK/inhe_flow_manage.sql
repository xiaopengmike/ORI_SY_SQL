/*
Navicat MySQL Data Transfer

Source Server         : localhost_3336
Source Server Version : 50525
Source Host           : localhost:3336
Source Database       : TD_OA

Target Server Type    : MYSQL
Target Server Version : 50525
File Encoding         : 65001

Date: 2020-09-30 18:20:45
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for inhe_flow_manage
-- ----------------------------
DROP TABLE IF EXISTS `inhe_flow_manage`;
CREATE TABLE `inhe_flow_manage` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '自增主键',
  `step` int(11) DEFAULT NULL COMMENT '步骤',
  `total_step` int(5) DEFAULT NULL,
  `role_id` int(11) DEFAULT NULL,
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
  `type` varchar(100) DEFAULT NULL,
  `type_desc` varchar(255) DEFAULT NULL,
  `user_bu` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=43 DEFAULT CHARSET=gbk;

-- ----------------------------
-- Records of inhe_flow_manage
-- ----------------------------
INSERT INTO `inhe_flow_manage` VALUES ('1', '1', '10', '1', '项目经理', 'INHE-0900', '2', 'approve', '1', null, null, null, null, '1', 'project_review', '立项申请流程', 'INHE');
INSERT INTO `inhe_flow_manage` VALUES ('2', '2', '10', '2', '商务组确认项目代号', 'INHE-0662', '3', 'approve', '', null, null, null, null, '0', 'project_review', '立项申请流程', 'INHE');
INSERT INTO `inhe_flow_manage` VALUES ('3', '3', '10', '3', '商务部经理', 'INHE-0051', '4', 'approve', '1', null, null, null, null, '1', 'project_review', '立项申请流程', 'INHE');
INSERT INTO `inhe_flow_manage` VALUES ('4', '4', '10', '4', '市场总监', 'INHE-0900', '5', 'approve', '1', '此项要求：市场总监同意后可选择继续提交，或者暂不提交，待项目确定后再继续提交。', '1', null, null, '1', 'project_review', '立项申请流程', 'INHE');
INSERT INTO `inhe_flow_manage` VALUES ('5', '5', '10', '5', '项目融资和风险控制', 'INHE-0260', '6', 'approve', '1', null, null, null, null, '1', 'project_review', '立项申请流程', 'INHE');
INSERT INTO `inhe_flow_manage` VALUES ('6', '6', '10', '6', '技术支持总监', 'INHE-0144', '7', 'approve', '1', null, '1', null, null, '1', 'project_review', '立项申请流程', 'INHE');
INSERT INTO `inhe_flow_manage` VALUES ('7', '7', '10', '7', '技术总监', 'szdfm', '8', 'approve', '1', null, '1', null, null, '1', 'project_review', '立项申请流程', 'INHE');
INSERT INTO `inhe_flow_manage` VALUES ('8', '8', '10', '8', '董事长', 'meterw', '9', 'approve', '1', '董事长审核通过后流向跟单组组长，跟单组组长依据技术总监指定人选择提交下一步。可对下一步研发接收人员对应屏蔽一些字段。', null, null, null, '1', 'project_review', '立项申请流程', 'INHE');
INSERT INTO `inhe_flow_manage` VALUES ('9', '9', '10', '9', '备案', 'INHE-0900', '', 'record,notice', null, null, null, null, null, '0', 'project_review', '立项申请流程', 'INHE');
INSERT INTO `inhe_flow_manage` VALUES ('10', '10', '10', '10', '结束流程', 'INHE-0900', null, 'over', null, null, null, null, null, '', 'project_review', '立项申请流程', 'INHE');
INSERT INTO `inhe_flow_manage` VALUES ('11', '1', '1', '1', '商务审核', 'INHE-0900', '', 'approve', null, null, null, null, null, '', 'customer_data', '客户信息登记', 'INHE');
INSERT INTO `inhe_flow_manage` VALUES ('13', '1', '9', '1', '商务部经理', 'INHE-0051', '2', 'approve', null, null, null, null, null, '1', 'sales_contract_review', '销售合同评审', 'INHE');
INSERT INTO `inhe_flow_manage` VALUES ('14', '2', '9', '2', '市场总监', 'INHE-0900', '3', 'approve', null, null, null, null, null, '1', 'sales_contract_review', '销售合同评审', 'INHE');
INSERT INTO `inhe_flow_manage` VALUES ('16', '3', '9', '3', '技术支持总监', 'INHE-0144', '4', 'approve', null, null, null, null, null, '1', 'sales_contract_review', '销售合同评审', 'INHE');
INSERT INTO `inhe_flow_manage` VALUES ('17', '4', '9', '4', '技术总监', 'szdfm', '5', 'approve', null, null, null, null, null, '1', 'sales_contract_review', '销售合同评审', 'INHE');
INSERT INTO `inhe_flow_manage` VALUES ('18', '5', '9', '5', '财务部', 'INHE-0671', '6', 'approve', null, null, null, null, null, '1', 'sales_contract_review', '销售合同评审', 'INHE');
INSERT INTO `inhe_flow_manage` VALUES ('19', '6', '9', '6', '财务经理', 'INHE-0800', '7', 'approve', null, null, null, null, null, '1', 'sales_contract_review', '销售合同评审', 'INHE');
INSERT INTO `inhe_flow_manage` VALUES ('20', '7', '9', '7', '风险控制与项目融资部', 'INHE-0260', '8', 'approve', null, null, null, null, null, '1', 'sales_contract_review', '销售合同评审', 'INHE');
INSERT INTO `inhe_flow_manage` VALUES ('21', '8', '9', '8', '董事长', 'meterw', '9', 'approve', null, null, null, null, null, '1', 'sales_contract_review', '销售合同评审', 'INHE');
INSERT INTO `inhe_flow_manage` VALUES ('22', '9', '9', '9', '备案', 'lhz,INHE-0051', null, 'record', null, null, null, null, null, '', 'sales_contract_review', '销售合同评审', 'INHE');
INSERT INTO `inhe_flow_manage` VALUES ('23', '1', '8', '1', '跟单组组长', 'lhz', '2', 'approve', null, null, null, null, null, '1', 'production_order', '生产通知单', 'INHE');
INSERT INTO `inhe_flow_manage` VALUES ('24', '2', '8', '2', '主管总监', 'INHE-0900', '3', 'approve', null, null, null, null, null, '1', 'production_order', '生产通知单', 'INHE');
INSERT INTO `inhe_flow_manage` VALUES ('25', '3', '8', '3', '财务会计', 'INHE-0671', '4', 'approve', null, null, null, null, null, '1', 'production_order', '生产通知单', 'INHE');
INSERT INTO `inhe_flow_manage` VALUES ('26', '4', '8', '4', '江西总经理', 'INHE-0038', '5', 'approve', null, null, null, null, null, '1', 'production_order', '生产通知单', 'INHE');
INSERT INTO `inhe_flow_manage` VALUES ('27', '5', '8', '5', '技术总监', 'szdfm', '6', 'approve', null, null, null, null, null, '1', 'production_order', '生产通知单', 'INHE');
INSERT INTO `inhe_flow_manage` VALUES ('28', '6', '8', '6', '董事长', 'meterw', '7', 'approve', null, null, null, null, null, '1', 'production_order', '生产通知单', 'INHE');
INSERT INTO `inhe_flow_manage` VALUES ('29', '7', '8', '7', '客服部备案', 'INHE-0144', '8', 'record', null, null, null, null, null, null, 'production_order', '生产通知单', 'INHE');
INSERT INTO `inhe_flow_manage` VALUES ('30', '8', '8', '8', '备案', 'lhz', null, 'record', null, null, null, null, null, null, 'production_order', '生产通知单', 'INHE');
INSERT INTO `inhe_flow_manage` VALUES ('35', '1', '8', '1', '跟单组长', '鲁慧珍', '郭涛(Captain)', 'approve', null, null, null, null, null, '1', 'shipping_notice', '发货通知单', 'INHE');
INSERT INTO `inhe_flow_manage` VALUES ('36', '2', '8', '2', '市场总监', '郭涛(Captain)', '朱能源', 'approve', null, null, null, null, null, '1', 'shipping_notice', '发货通知单', 'INHE');
INSERT INTO `inhe_flow_manage` VALUES ('37', '3', '8', '3', '销售会计', '朱能源', '喻美娥', 'approve', null, null, null, null, null, '1', 'shipping_notice', '发货通知单', 'INHE');
INSERT INTO `inhe_flow_manage` VALUES ('38', '4', '8', '4', '风险控制与项目融资部', '喻美娥', '黄时娟', 'approve', null, null, null, null, null, '1', 'shipping_notice', '发货通知单', 'INHE');
INSERT INTO `inhe_flow_manage` VALUES ('39', '5', '8', '5', '会计部经理', '黄时娟 ', '陶保荣', 'approve', null, null, null, null, null, '1', 'shipping_notice', '发货通知单', 'INHE');
INSERT INTO `inhe_flow_manage` VALUES ('40', '6', '8', '6', '江西总经理', '陶保荣', '王功勇', 'approve', null, null, null, null, null, '1', 'shipping_notice', '发货通知单', 'INHE');
INSERT INTO `inhe_flow_manage` VALUES ('41', '7', '8', '7', '董事长', '王功勇', '鲁慧珍', 'approve', null, null, null, null, null, '1', 'shipping_notice', '发货通知单', 'INHE');
INSERT INTO `inhe_flow_manage` VALUES ('42', '8', '8', '8', '备案', '鲁慧珍', null, 'record', null, null, null, null, null, null, 'shipping_notice', '发货通知单', 'INHE');
