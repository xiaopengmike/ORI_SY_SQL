/*
Navicat MySQL Data Transfer

Source Server         : localhost_3336
Source Server Version : 50525
Source Host           : localhost:3336
Source Database       : TD_OA

Target Server Type    : MYSQL
Target Server Version : 50525
File Encoding         : 65001

Date: 2020-09-30 18:21:14
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for inhe_flow_opinion
-- ----------------------------
DROP TABLE IF EXISTS `inhe_flow_opinion`;
CREATE TABLE `inhe_flow_opinion` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `flow_id` varchar(30) DEFAULT NULL,
  `step` int(5) DEFAULT NULL,
  `role_id` int(11) DEFAULT NULL,
  `role` varchar(255) DEFAULT NULL,
  `approver` varchar(255) DEFAULT NULL,
  `next` varchar(255) DEFAULT NULL,
  `opinion` text,
  `project_star` varchar(255) DEFAULT NULL,
  `have_manager` varchar(255) DEFAULT NULL,
  `extra` varchar(255) DEFAULT NULL,
  `time` datetime DEFAULT NULL,
  `if_agree` varchar(255) DEFAULT NULL,
  `signature` varchar(255) DEFAULT NULL,
  `status` varchar(255) DEFAULT NULL,
  `approve_round` varchar(255) DEFAULT NULL,
  `type` varchar(255) DEFAULT NULL,
  `user_bu` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=96 DEFAULT CHARSET=gbk;

-- ----------------------------
-- Records of inhe_flow_opinion
-- ----------------------------
INSERT INTO `inhe_flow_opinion` VALUES ('1', 'lxdj202008110001', '0', '0', '申请人', 'INHE-0900', 'INHE-0900', '总部和分公司的付款流程权限调整一下，总部的取消江西国内国际付款流程的权限，只开分公司的，江西采购的开放总部的付款流程，取消分公司的付款流程', '03', '0', null, '2020-08-11 11:50:20', '01', null, 'H', '1', 'project_review', 'INHE');
INSERT INTO `inhe_flow_opinion` VALUES ('2', 'lxdj2020081100011', '2', '2', '商务组确认项目代号', 'INHE-0662', 'INHE-0051', '', '', null, null, '2020-08-11 16:29:23', '01', null, 'H', '1', 'project_review', 'INHE');
INSERT INTO `inhe_flow_opinion` VALUES ('3', 'lxdj2020081100011', '3', '3', '商务部经理', 'INHE-0051', 'INHE-0702', '总部和分公司的付款流程权限调整一下，\r\n总部的取消江西国内国际付款流程的权限，只开分公司的，\r\n江西采购的开放总部的付款流程，取消分公司的付款流程总部和分公司的付款流程权限调整一下，\r\n总部的取消江西国内国际付款流程的权限，只开分公司的，江西采购的开放总部的付款流程，\r\n取消分公司的付款流程总部和分公司的付款流程权限调整一下，总部的取消江西国内国际付款流程的权限，\r\n只开分公司的，江西采购的开放总部的付款流程，取消分公司的付款流程总部和分公司的付款流程权限调整一下，\r\n总部的取消江西国内国际付款流程的权限，只开分公司的，江西采购的开放总部的付款流程，\r\n江西采购的开放总部的付款流程，取消分公司的付款流程总部和分公司的付款流程权限调整一下，\r\n总部的取消江西国内国际付款流程的权限，只开分公司的，江西采购的开放总部的付款流程，\r\n只开分公司的，江西', '03', '', null, '2020-08-11 16:29:23', '01', null, 'H', '1', 'project_review', 'INHE');
INSERT INTO `inhe_flow_opinion` VALUES ('4', 'lxdj2020081100011', '4', '4', '市场总监', 'INHE-0702', 'INHE-0260', '同意4', '03', '', null, '2020-08-11 16:29:23', '01', null, 'H', '1', 'project_review', 'INHE');
INSERT INTO `inhe_flow_opinion` VALUES ('5', 'lxdj2020081100011', '5', '5', '项目融资和风险控制', 'INHE-0260', 'INHE-0144', '同意5', '03', '', null, '2020-08-11 16:29:23', '01', null, 'H', '1', 'project_review', 'INHE');
INSERT INTO `inhe_flow_opinion` VALUES ('6', 'lxdj2020081100011', '6', '6', '技术支持总监', 'INHE-0144', 'szdfm', '同意6', '03', '', null, '2020-08-11 16:29:23', '01', null, 'H', '1', 'project_review', 'INHE');
INSERT INTO `inhe_flow_opinion` VALUES ('7', 'lxdj2020081100011', '7', '7', '技术总监', 'szdfm', 'meterw', '同意7', '03', '', null, '2020-08-11 16:29:23', '01', null, 'H', '1', 'project_review', 'INHE');
INSERT INTO `inhe_flow_opinion` VALUES ('8', 'lxdj2020081100011', '8', '8', '董事长', 'meterw', 'INHE-0662', '同意8，项目代号GAP', '03', null, null, '2020-08-11 16:42:11', '01', null, 'H', '1', 'project_review', 'INHE');
INSERT INTO `inhe_flow_opinion` VALUES ('9', 'lxdj2020081100011', '9', '9', '备案', 'INHE-0662', null, null, null, null, null, '2020-08-11 17:40:37', '01', null, 'E', '1', 'project_review', 'INHE');
INSERT INTO `inhe_flow_opinion` VALUES ('10', 'khxxb2020080508', '0', '0', '申请人', 'INHE-0900', 'INHE-0662', null, null, null, null, '2020-08-12 15:11:43', null, null, 'H', '1', 'customer_data', 'INHE');
INSERT INTO `inhe_flow_opinion` VALUES ('11', 'khxx202008121109', '0', '0', '申请人', 'INHE-0900', 'INHE-0662', '', '', '', null, '2020-08-12 15:11:43', '', null, 'H', '1', 'customer_data', 'INHE');
INSERT INTO `inhe_flow_opinion` VALUES ('14', 'khxx202008121110', '0', '0', '申请人', 'INHE-0900', 'INHE-0662', '', '', '', null, '2020-08-12 16:05:21', '', null, 'H', '1', 'customer_data', 'INHE');
INSERT INTO `inhe_flow_opinion` VALUES ('15', 'khxx202008121111', '0', '0', '申请人', 'INHE-0900', 'INHE-0900', '', '', '', null, '2020-08-12 16:07:16', '', null, 'H', '1', 'customer_data', 'INHE');
INSERT INTO `inhe_flow_opinion` VALUES ('18', 'khxx202008121111', '1', '1', '商务审核', 'INHE-0900', '', '', '', '', null, '2020-08-12 16:31:57', '01', null, 'E', '1', 'customer_data', 'INHE');
INSERT INTO `inhe_flow_opinion` VALUES ('19', 'khxx202008121110', '1', '1', '商务审核', 'INHE-0900', 'INHE-0900', '', '', '', null, '2020-08-12 16:50:50', '02', null, 'N', '1', 'customer_data', 'INHE');
INSERT INTO `inhe_flow_opinion` VALUES ('20', 'khxx202008131112', '0', '0', '申请人', 'INHE-0900', 'INHE-0900', '', '', '', null, '2020-08-13 11:16:50', '', null, 'H', '1', 'customer_data', 'INHE');
INSERT INTO `inhe_flow_opinion` VALUES ('26', 'lxdj202008170002', '0', '0', '申请人', 'INHE-0900', 'INHE-0900', '123', '04', '02', null, '2020-08-17 17:48:39', '', null, 'H', '1', 'project_review', 'INHE');
INSERT INTO `inhe_flow_opinion` VALUES ('30', 'lxdj202008170002', '1', '1', '项目经理', 'INHE-0900', 'INHE-0662', '112233', '04', '', null, '2020-08-18 09:21:48', '01', null, 'H', '1', 'project_review', 'INHE');
INSERT INTO `inhe_flow_opinion` VALUES ('31', 'lxdj202008170002', '2', '2', '商务组确认项目代号', 'INHE-0662', 'INHE-0051', '', '', '', null, '2020-08-18 13:51:07', '', null, 'H', '1', 'project_review', 'INHE');
INSERT INTO `inhe_flow_opinion` VALUES ('32', 'lxdj202008170002', '3', '3', '商务部经理', 'INHE-0051', 'INHE-0900', 'sw', '04', '', null, '2020-08-18 13:52:30', '01', null, 'H', '1', 'project_review', 'INHE');
INSERT INTO `inhe_flow_opinion` VALUES ('33', 'lxdj202008170002', '4', '4', '市场总监', 'INHE-0900', 'INHE-0260', '', '04', '', null, '2020-08-18 13:53:08', '01', null, 'H', '1', 'project_review', 'INHE');
INSERT INTO `inhe_flow_opinion` VALUES ('34', 'lxdj202008170002', '5', '5', '项目融资和风险控制', 'INHE-0260', 'INHE-0144', '123', '04', '', null, '2020-08-21 14:07:39', '01', null, 'H', '1', 'project_review', 'INHE');
INSERT INTO `inhe_flow_opinion` VALUES ('35', 'lxdj202008170002', '6', '6', '技术支持总监', 'INHE-0144', 'szdfm', 'qw', '04', '', null, '2020-08-21 14:08:47', '01', null, 'H', '1', 'project_review', 'INHE');
INSERT INTO `inhe_flow_opinion` VALUES ('36', 'lxdj202008170002', '7', '7', '技术总监', 'szdfm', 'meterw', 'er', '04', '', null, '2020-08-21 14:09:11', '01', null, 'H', '1', 'project_review', 'INHE');
INSERT INTO `inhe_flow_opinion` VALUES ('37', 'lxdj202008170002', '8', '8', '董事长', 'meterw', 'INHE-0900', 'over', '04', '', null, '2020-08-21 14:09:32', '01', null, 'H', '1', 'project_review', 'CORP');
INSERT INTO `inhe_flow_opinion` VALUES ('38', 'lxdj202008170002', '9', '9', '备案', 'INHE-0900', 'INHE-0900', '', '', '', null, '2020-08-21 14:11:08', '', null, 'H', '1', 'project_review', 'INHE');
INSERT INTO `inhe_flow_opinion` VALUES ('39', 'lxdj202008170002', '10', '10', '结束流程', 'INHE-0900', '', '', '', '', null, '2020-08-21 14:11:18', '', null, 'E', '1', 'project_review', 'INHE');
INSERT INTO `inhe_flow_opinion` VALUES ('40', 'lxdj202008110001', '1', '1', '项目经理', 'INHE-0900', 'INHE-0662', '1', '00', '', null, '2020-08-21 14:11:36', '01', null, 'H', '1', 'project_review', 'INHE');
INSERT INTO `inhe_flow_opinion` VALUES ('41', 'lxdj2020081801', '1', '1', '项目经理', '邓彧(Daniel)', '盛燕', '1. 项目还未确定正式发标还是直采，客户侧还未公布； \r\n2. 合作模式根据最终客户发布结果再确定，现阶段同时拓展代理和电力公司 \r\n3. 建议现阶段先聚焦解决方案，同意立项', '02', null, null, '2020-08-19 16:42:00', '01', '邓彧(Daniel)2020-08-19 16:42:00', 'H', '1', 'project_review', 'INHE');
INSERT INTO `inhe_flow_opinion` VALUES ('42', 'lxdj2020081801', '3', '3', '商务部经理', '盛燕', '郭涛(Captain)', '暂未正式招标，合作模式待定，商务条款待定，待正式发标后请走项目变更流程', '02', null, null, null, '01', '盛燕2020-08-20 11:36:05', 'H', '1', 'project_review', 'INHE');
INSERT INTO `inhe_flow_opinion` VALUES ('43', 'lxdj2020081801', '4', '4', '市场总监', '郭涛(Captain)', '喻美娥', '1.请魏紫薇按计划完成市场调研报告；\r\n2.请魏紫薇邓彧考虑说服对方加入CT表做试点，具体方案可以借鉴GNC（郑超波）和ALP（张柳芳）；\r\n3.请邓彧核实，安全可行情况，安排人力一线运作，纵深推进。', '01', null, null, null, '01', '郭涛(Captain)2020-08-20 15:35:29 ', 'H', '1', 'project_review', 'INHE');
INSERT INTO `inhe_flow_opinion` VALUES ('46', 'lxdj2020081801', '5', '5', '项目融资和风险控制部', '喻美娥', '谌冬辉', '请注意结算条款的谈判。', '01', null, null, null, '01', '喻美娥2020-08-20 15:37:29', 'H', '1', 'project_review', 'INHE');
INSERT INTO `inhe_flow_opinion` VALUES ('47', 'lxdj2020081801', '6', '6', '技术支持总监', '谌冬辉', '丁富民', '同意立项。客服已经根据银河电网需求完成技术规格初步解析并输出澄清列表，下周将重点准备相关技术资料。请商务协调确定以银河电网投标还是银河电力投标，并输出文件列表。', '01', null, null, null, '01', '谌冬辉2020-08-20 15:47:28', 'H', '1', 'project_review', 'INHE');
INSERT INTO `inhe_flow_opinion` VALUES ('48', 'lxdj2020081801', '7', '7', '技术总监', '丁富民', '王功勇', '同意立项，系统软件项目。', '01', null, null, null, '01', '丁富民2020-08-21 14:39:57', 'H', '1', 'project_review', 'INHE');
INSERT INTO `inhe_flow_opinion` VALUES ('49', 'lxdj2020081801', '8', '8', '董事长', '王功勇', '陈丽', '项目代号：GAB。纯软件项目', '01', null, null, null, '01', '王功勇2020-08-22 10:39:18  ', 'H', '1', 'project_review', 'INHE');
INSERT INTO `inhe_flow_opinion` VALUES ('50', 'lxdj2020081801', '9', '9', '商务组备案', '陈丽', '盛燕', null, null, null, null, null, '', '陈丽2020-08-24 08:42:14', 'H', '1', 'project_review', 'INHE');
INSERT INTO `inhe_flow_opinion` VALUES ('51', 'lxdj2020081801', '10', '10', '结束流程', '盛燕', '', null, null, null, null, null, null, '盛燕2020-08-24 09:16:17', 'E', '1', 'project_review', 'INHE');
INSERT INTO `inhe_flow_opinion` VALUES ('52', 'lxdj2020081801', '0', '0', '申请人', 'INHE-0913', '邓彧(Daniel)', null, null, null, null, null, null, '魏紫薇2020-08-18 17:44:11', 'H', '1', 'project_review', 'INHE');
INSERT INTO `inhe_flow_opinion` VALUES ('53', 'INHESC2020081101', '0', '0', '申请人', '邱洁', '盛燕', null, null, null, null, '2020-08-26 09:29:17', '', '邱洁2020-08-26 09:29:17', 'H', '1', 'sales_contract_review', 'INHE');
INSERT INTO `inhe_flow_opinion` VALUES ('54', 'INHESC2020081101', '1', '1', '商务部经理', '盛燕', '杨松', '1.买家名称 2.合同签订日期 3.运保费 ', null, null, '02', '2020-09-04 07:29:17', '01', '盛燕2020-08-26 09:29:17', 'H', '1', 'sales_contract_review', 'INHE');
INSERT INTO `inhe_flow_opinion` VALUES ('55', 'INHESC2020081101', '2', '2', '市场总监', '杨松', 'INHE-0144', '市场总监', null, null, null, '2020-08-26 09:29:17', '01', '杨松2020-08-26 09:29:17', 'H', '1', 'sales_contract_review', 'INHE');
INSERT INTO `inhe_flow_opinion` VALUES ('56', 'INHESC2020081101', '3', '3', '技术支持总监', 'INHE-0144', 'szdfm', '技术支持总监', null, null, null, '2020-08-26 09:29:17', '01', '谌冬辉2020-08-26 09:29:17', 'H', '1', 'sales_contract_review', 'INHE');
INSERT INTO `inhe_flow_opinion` VALUES ('57', 'INHESC2020081101', '4', '4', '技术总监', 'szdfm', 'INHE-0671', '技术总监', null, null, null, '2020-08-26 09:29:17', '01', '丁富民2020-08-26 09:29:17', 'H', '1', 'sales_contract_review', 'INHE');
INSERT INTO `inhe_flow_opinion` VALUES ('58', 'INHESC2020081101', '5', '5', '财务部', 'INHE-0671', 'INHE-0800', '财务部', null, null, null, '2020-08-26 09:29:17', '01', '朱君宜2020-08-26 09:29:17', 'H', '1', 'sales_contract_review', 'INHE');
INSERT INTO `inhe_flow_opinion` VALUES ('60', 'INHESC2020081101', '6', '6', '财务经理', 'INHE-0800', 'INHE-0260', '财务经理', null, null, null, '2020-08-26 09:29:17', '01', '黄时娟2020-08-26 09:29:17', 'H', '1', 'sales_contract_review', 'INHE');
INSERT INTO `inhe_flow_opinion` VALUES ('61', 'INHESC2020081101', '7', '7', '风险控制与项目融资部', 'INHE-0260', 'meterw', '风险控制与项目融资部', null, null, null, '2020-08-26 09:29:17', '01', '喻美娥2020-08-26 09:29:17', 'H', '1', 'sales_contract_review', 'INHE');
INSERT INTO `inhe_flow_opinion` VALUES ('62', 'INHESC2020081101', '8', '8', '董事长', 'meterw', 'lhz,INHE-0051', '董事长', null, null, null, '2020-08-26 09:29:17', '01', '王功勇2020-08-26 09:29:17', 'H', '1', 'sales_contract_review', 'INHE');
INSERT INTO `inhe_flow_opinion` VALUES ('63', 'INHESC2020081101', '9', '9', '备案', 'lhz,INHE-0051', null, null, null, null, null, '2020-08-26 09:29:17', '01', '鲁慧珍2020-08-26 09:29:17,盛燕2020-08-26 09:29:17', 'H', '1', 'sales_contract_review', 'INHE');
INSERT INTO `inhe_flow_opinion` VALUES ('64', 'INHESC2020082802', '0', '0', '申请人', 'INHE-0900', 'INHE-0051', '', '', '', null, '2020-08-28 18:28:38', '', null, 'H', '1', 'sales_contract_review', 'INHE');
INSERT INTO `inhe_flow_opinion` VALUES ('68', 'INHESC2020083101', '0', '0', '申请人', 'INHE-0900', 'INHE-0051', '', '', '', null, '2020-08-31 08:34:27', '', null, 'H', '1', 'sales_contract_review', 'INHE');
INSERT INTO `inhe_flow_opinion` VALUES ('69', 'khxx202008311128', '0', '0', '申请人', 'INHE-0900', 'INHE-0900', '', '', '', null, '2020-08-31 09:08:02', '', null, 'H', '1', 'customer_data', 'INHE');
INSERT INTO `inhe_flow_opinion` VALUES ('70', 'khxx202008311128', '1', '0', '', 'INHE-0900', '', '', '', '', null, '2020-08-31 09:08:12', '1', null, 'E', '1', 'customer_data', 'INHE');
INSERT INTO `inhe_flow_opinion` VALUES ('78', 'sctz2020081001', '1', '1', '跟单组组长', 'lhz', 'INHE-0900', '1', null, null, null, '2020-09-08 11:22:41', '01', '鲁慧珍2020-09-08 11:22:41', 'H', '1', 'production_order', 'INHE');
INSERT INTO `inhe_flow_opinion` VALUES ('79', 'sctz2020081001', '2', '2', '主管总监', 'INHE-0900', 'INHE-0671', '2', null, null, null, '2020-09-08 11:22:41', '01', '郭涛(Captain)2020-08-20 15:35:29 ', 'H', '1', 'production_order', 'INHE');
INSERT INTO `inhe_flow_opinion` VALUES ('80', 'sctz2020081001', '3', '3', '财务会计', 'INHE-0671', 'INHE-0038', '3', null, null, null, '2020-09-08 11:22:41', '01', '朱君宜2020-08-26 09:29:17', 'H', '1', 'production_order', 'INHE');
INSERT INTO `inhe_flow_opinion` VALUES ('81', 'sctz2020081001', '4', '4', '江西总经理', 'INHE-0038', 'szdfm', '4', null, null, null, '2020-09-08 11:22:41', '01', '陶保荣2020-08-20 15:35:29 ', 'H', '1', 'production_order', 'INHE');
INSERT INTO `inhe_flow_opinion` VALUES ('82', 'sctz2020081001', '5', '5', '技术总监', 'szdfm', 'meterw', '5', null, null, null, '2020-09-08 11:22:41', '01', '丁富民2020-09-08 11:22:41', 'H', '1', 'production_order', 'INHE');
INSERT INTO `inhe_flow_opinion` VALUES ('83', 'sctz2020081001', '6', '6', '董事长', 'meterw', 'INHE-0144', '6', null, null, null, '2020-09-08 11:22:41', '01', '王功勇2020-08-26 09:29:17', 'H', '1', 'production_order', 'INHE');
INSERT INTO `inhe_flow_opinion` VALUES ('84', 'sctz2020081001', '7', '7', '客服部备案', 'INHE-0144', 'lhz', '', null, null, null, '2020-09-08 11:22:41', '01', '谌冬辉2020-08-20 15:47:28', 'H', '1', 'production_order', 'INHE');
INSERT INTO `inhe_flow_opinion` VALUES ('85', 'sctz2020081001', '8', '8', '备案', 'lhz', null, '', null, null, null, '2020-09-08 11:22:41', '01', '鲁慧珍2020-09-08 11:22:41', 'E', '1', 'production_order', 'INHE');
INSERT INTO `inhe_flow_opinion` VALUES ('86', 'sctz2020081001', '0', '0', '申请人', 'INHE-0900', 'lhz', '0', null, null, null, '2020-09-08 11:22:41', '01', '吴艳2020-09-08 11:22:41', 'H', '1', 'production_order', 'INHE');
INSERT INTO `inhe_flow_opinion` VALUES ('87', 'fhtz2020090401', '0', '0', '申请单', '李平', '鲁慧珍', null, null, null, null, null, '1', null, 'H', '1', 'shipping_notice', 'INHE');
INSERT INTO `inhe_flow_opinion` VALUES ('88', 'fhtz2020090401', '1', '1', '跟单组长', '鲁慧珍', '郭涛(Captain)', null, null, null, null, null, '1', null, 'H', '1', 'shipping_notice', 'INHE');
INSERT INTO `inhe_flow_opinion` VALUES ('89', 'fhtz2020090401', '2', '2', '市场总监', '郭涛(Captain)', '朱能源', null, null, null, null, null, '1', null, 'H', '1', 'shipping_notice', 'INHE');
INSERT INTO `inhe_flow_opinion` VALUES ('90', 'fhtz2020090401', '3', '3', '销售会计', '朱能源', '喻美娥', null, null, null, null, null, '1', null, 'H', '1', 'shipping_notice', 'INHE');
INSERT INTO `inhe_flow_opinion` VALUES ('91', 'fhtz2020090401', '4', '4', '风险控制与项目融资部', '喻美娥', '黄时娟', null, null, null, null, null, '1', null, 'H', '1', 'shipping_notice', 'INHE');
INSERT INTO `inhe_flow_opinion` VALUES ('92', 'fhtz2020090401', '5', '5', '会计部经理', '黄时娟', '陶保荣', null, null, null, null, null, '1', null, 'H', '1', 'shipping_notice', 'INHE');
INSERT INTO `inhe_flow_opinion` VALUES ('93', 'fhtz2020090401', '6', '6', '江西总经理', '陶保荣', '王功勇', null, null, null, null, null, '1', null, 'H', '1', 'shipping_notice', 'INHE');
INSERT INTO `inhe_flow_opinion` VALUES ('94', 'fhtz2020090401', '7', '7', '董事长', '王功勇', '鲁慧珍', null, null, null, null, null, '1', null, 'H', '1', 'shipping_notice', 'INHE');
INSERT INTO `inhe_flow_opinion` VALUES ('95', 'fhtz2020090401', '8', '8', '备案', '鲁慧珍', null, null, null, null, null, null, '1', null, 'E', '1', 'shipping_notice', 'INHE');
