/*
Navicat MySQL Data Transfer

Source Server         : localhost_3336
Source Server Version : 50525
Source Host           : localhost:3336
Source Database       : TD_OA

Target Server Type    : MYSQL
Target Server Version : 50525
File Encoding         : 65001

Date: 2020-09-30 18:23:20
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for inhe_report_column
-- ----------------------------
DROP TABLE IF EXISTS `inhe_report_column`;
CREATE TABLE `inhe_report_column` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `system_id` varchar(20) DEFAULT NULL,
  `module_id` varchar(30) DEFAULT NULL,
  `field_name` varchar(255) DEFAULT NULL,
  `column_name` text,
  `order_no` int(11) DEFAULT NULL,
  `field_type` varchar(20) DEFAULT NULL,
  `verify` varchar(20) DEFAULT NULL,
  `css` varchar(255) DEFAULT NULL,
  `other_info` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=64 DEFAULT CHARSET=gbk;

-- ----------------------------
-- Records of inhe_report_column
-- ----------------------------
INSERT INTO `inhe_report_column` VALUES ('1', 'crm', 'contract_protocol', 'serial_number', 'No.', '1', 'input', null, 'readonly', null);
INSERT INTO `inhe_report_column` VALUES ('2', 'crm', 'contract_protocol', 'continent', '大洲', '2', 'select', null, null, 'continent');
INSERT INTO `inhe_report_column` VALUES ('3', 'crm', 'contract_protocol', 'country', '国家', '3', 'input', null, null, null);
INSERT INTO `inhe_report_column` VALUES ('4', 'crm', 'contract_protocol', 'project_code', '项目代号', '4', 'input', null, null, null);
INSERT INTO `inhe_report_column` VALUES ('5', 'crm', 'contract_protocol', 'customer_name', '相对方', '5', 'input', null, null, null);
INSERT INTO `inhe_report_column` VALUES ('6', 'crm', 'contract_protocol', 'protocol_no', '协议编号', '6', 'input', null, null, null);
INSERT INTO `inhe_report_column` VALUES ('7', 'crm', 'contract_protocol', 'detail_type', '协议明细类别', '7', 'input', null, null, null);
INSERT INTO `inhe_report_column` VALUES ('8', 'crm', 'contract_protocol', 'type', '协议类型', '8', 'select', null, null, 'contract_protocol_type');
INSERT INTO `inhe_report_column` VALUES ('9', 'crm', 'contract_protocol', 'if_exclusive', '是否为独家合作协议', '9', 'select', null, null, 'yes_or_no');
INSERT INTO `inhe_report_column` VALUES ('10', 'crm', 'contract_protocol', 'effective_condition', '独家合作生效条件', '10', 'input', null, null, null);
INSERT INTO `inhe_report_column` VALUES ('11', 'crm', 'contract_protocol', 'effective_date', '生效时间', '11', 'date', null, null, null);
INSERT INTO `inhe_report_column` VALUES ('12', 'crm', 'contract_protocol', 'end_date', '终止时间', '12', 'date', null, null, null);
INSERT INTO `inhe_report_column` VALUES ('13', 'crm', 'contract_protocol', 'sign_date', '签订时间', '13', 'date', null, null, null);
INSERT INTO `inhe_report_column` VALUES ('14', 'crm', 'contract_protocol', 'flow_no', '审批流程流水号', '14', 'input', null, null, null);
INSERT INTO `inhe_report_column` VALUES ('15', 'crm', 'contract_protocol', 'user_id', '经办人', '16', 'user_select', null, null, 'user_id');
INSERT INTO `inhe_report_column` VALUES ('16', 'crm', 'contract_protocol', 'apply_dept', '申请部门', '15', 'dept_select', null, null, 'apply_dept');
INSERT INTO `inhe_report_column` VALUES ('17', 'crm', 'contract_protocol', 'if_archive', '是否存档', '17', 'select', null, null, 'yes_or_no');
INSERT INTO `inhe_report_column` VALUES ('18', 'crm', 'contract_protocol', 'archive_mode', '存档形式', '18', 'input', null, null, null);
INSERT INTO `inhe_report_column` VALUES ('19', 'crm', 'contract_protocol', 'attach_name', '附件', '19', 'attach', null, null, 'contract_protocol');
INSERT INTO `inhe_report_column` VALUES ('20', 'crm', 'contract_protocol', 'remark', '备注', '20', 'textarea', null, null, null);
INSERT INTO `inhe_report_column` VALUES ('21', 'crm', 'contract_protocol', 'manage_dept', '管理部门', '21', 'dept_select', null, null, 'manage_dept');
INSERT INTO `inhe_report_column` VALUES ('22', 'crm', 'sales_contract', 'serial_number', '序号', '1', 'input', '', 'readonly', '');
INSERT INTO `inhe_report_column` VALUES ('23', 'crm', 'sales_contract', 'sign_date', '合同签署时间', '2', 'date', '', '', '');
INSERT INTO `inhe_report_column` VALUES ('24', 'crm', 'sales_contract', 'contract_category', '合同类别', '3', 'select', '', '', 'contract_category');
INSERT INTO `inhe_report_column` VALUES ('25', 'crm', 'sales_contract', 'project_name', '项目名称', '4', 'input', '', '', '');
INSERT INTO `inhe_report_column` VALUES ('26', 'crm', 'sales_contract', 'project_no', '项目编号', '5', 'model', '', '', '');
INSERT INTO `inhe_report_column` VALUES ('27', 'crm', 'sales_contract', 'contract_no', '合同编号', '6', 'input', '', '', '');
INSERT INTO `inhe_report_column` VALUES ('28', 'crm', 'sales_contract', 'currency', '币种', '7', 'input', '', '', '');
INSERT INTO `inhe_report_column` VALUES ('29', 'crm', 'sales_contract', 'amount', '合同金额', '8', 'input', '', '', '');
INSERT INTO `inhe_report_column` VALUES ('30', 'crm', 'sales_contract', 'remark', '备注', '9', 'textarea', '', '', '');
INSERT INTO `inhe_report_column` VALUES ('31', 'crm', 'sales_contract', 'foreign_attach', '外文附件', '10', 'attach', '', '', '');
INSERT INTO `inhe_report_column` VALUES ('32', 'crm', 'sales_contract', 'chinese_attach', '中文翻译件', '11', 'attach', '', '', '');
INSERT INTO `inhe_report_column` VALUES ('33', 'crm', 'sales_contract', 'customer_name', '客户名称', '12', 'input', '', '', '');
INSERT INTO `inhe_report_column` VALUES ('34', 'crm', 'sales_contract', 'contract_party', '合同相对方', '13', 'input', '', '', '');
INSERT INTO `inhe_report_column` VALUES ('35', 'crm', 'sales_contract', 'business_belong', '业务归属', '14', 'input', '', '', '');
INSERT INTO `inhe_report_column` VALUES ('36', 'crm', 'sales_contract', 'user_name', '制单人', '16', 'input', '', '', '');
INSERT INTO `inhe_report_column` VALUES ('37', 'crm', 'sales_contract', 'dept_name', '部门', '15', 'input', '', '', '');
INSERT INTO `inhe_report_column` VALUES ('38', 'crm', 'sales_contract', 'bid_date', '中标时间', '17', 'date', '', '', '');
INSERT INTO `inhe_report_column` VALUES ('39', 'crm', 'sales_contract', 'bid_attach', '中标函附件', '18', 'attach', '', '', '');
INSERT INTO `inhe_report_column` VALUES ('40', 'crm', 'sales_contract', 'manage_dept', '管理部门', '19', 'dept_select', '', '', 'manage_dept');
INSERT INTO `inhe_report_column` VALUES ('43', 'crm', 'authorization_letter', 'serial_number', 'No.', '1', 'input', '', 'readonly', '');
INSERT INTO `inhe_report_column` VALUES ('44', 'crm', 'authorization_letter', 'project_code', '项目代号', '2', 'select', '', '', '');
INSERT INTO `inhe_report_column` VALUES ('45', 'crm', 'authorization_letter', 'country', '国家', '3', 'input', '', '', '');
INSERT INTO `inhe_report_column` VALUES ('46', 'crm', 'authorization_letter', 'content', '授权函内容', '4', 'input', '', '', '');
INSERT INTO `inhe_report_column` VALUES ('47', 'crm', 'authorization_letter', 'user_name', '被授权人', '5', 'input', '', '', '');
INSERT INTO `inhe_report_column` VALUES ('48', 'crm', 'authorization_letter', 'opposite_party', '相对方', '6', 'input', '', '', '');
INSERT INTO `inhe_report_column` VALUES ('49', 'crm', 'authorization_letter', 'effective_date', '生效日期', '7', 'input', '', '', '');
INSERT INTO `inhe_report_column` VALUES ('50', 'crm', 'authorization_letter', 'end_date', '终止日期', '8', 'select', '', '', 'contract_protocol_type');
INSERT INTO `inhe_report_column` VALUES ('51', 'crm', 'authorization_letter', 'sign_date', '签订日期', '9', 'select', '', '', 'yes_or_no');
INSERT INTO `inhe_report_column` VALUES ('52', 'crm', 'authorization_letter', 'process_no', '审批流程流水号', '10', 'input', '', '', '');
INSERT INTO `inhe_report_column` VALUES ('53', 'crm', 'authorization_letter', 'apply_dept', '申请部门', '11', 'date', '', '', '');
INSERT INTO `inhe_report_column` VALUES ('54', 'crm', 'authorization_letter', 'apply_user', '经办人', '12', 'date', '', '', '');
INSERT INTO `inhe_report_column` VALUES ('59', 'crm', 'authorization_letter', 'if_archive', '是否存档', '17', 'select', '', '', 'yes_or_no');
INSERT INTO `inhe_report_column` VALUES ('60', 'crm', 'authorization_letter', 'archive_mode', '存档形式', '18', 'input', '', '', '');
INSERT INTO `inhe_report_column` VALUES ('61', 'crm', 'authorization_letter', 'attach_name', '附件', '19', 'attach', '', '', 'contract_protocol');
INSERT INTO `inhe_report_column` VALUES ('63', 'crm', 'authorization_letter', 'manage_dept', '管理部门', '21', 'dept_select', '', '', 'manage_dept');
