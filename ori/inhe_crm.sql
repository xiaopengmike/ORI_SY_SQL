/*
SQLyog Trial
MySQL - 5.7.22-log : Database - TD_OA
*********************************************************************
*/

/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
/*Table structure for table `department` */

DROP TABLE IF EXISTS `department`;

CREATE TABLE `department` (
  `DEPT_ID` int(11) NOT NULL AUTO_INCREMENT COMMENT '����ΨһID',
  `DEPT_NAME` varchar(50) NOT NULL DEFAULT '' COMMENT '��������',
  `TEL_NO` varchar(50) NOT NULL DEFAULT '' COMMENT '�绰',
  `FAX_NO` varchar(50) NOT NULL DEFAULT '' COMMENT '����',
  `DEPT_ADDRESS` varchar(100) NOT NULL COMMENT '���ŵ�ַ',
  `DEPT_NO` varchar(200) NOT NULL DEFAULT '0' COMMENT '���������',
  `DEPT_PARENT` int(11) NOT NULL DEFAULT '0' COMMENT '�ϼ�����ID',
  `MANAGER` text NOT NULL COMMENT '��������',
  `ASSISTANT_ID` text NOT NULL COMMENT '��������',
  `LEADER1` text NOT NULL COMMENT '�ϼ������쵼',
  `LEADER2` text NOT NULL COMMENT '�ϼ��ֹ��쵼',
  `DEPT_FUNC` text NOT NULL COMMENT '����ְ��',
  `IS_ORG` char(1) NOT NULL DEFAULT '0' COMMENT '�Ƿ��Ƿ�֧����(0-��,1-��)',
  `ORG_ADMIN` varchar(200) NOT NULL COMMENT '��������Ա',
  `DEPT_EMAIL_AUDITS_IDS` varchar(255) NOT NULL DEFAULT '' COMMENT '�����ʼ������',
  `USER_BU` varchar(20) NOT NULL COMMENT 'USER_BU',
  `IS_DIRECTOR` varchar(1) DEFAULT NULL,
  PRIMARY KEY (`DEPT_ID`),
  KEY `DEPT_NO` (`DEPT_NO`),
  KEY `DEPT_PARENT` (`DEPT_PARENT`),
  KEY `USER_BU` (`USER_BU`)
) ENGINE=InnoDB AUTO_INCREMENT=401 DEFAULT CHARSET=gbk COMMENT='������Ϣ��';

/*Table structure for table `inhe_attachment` */

DROP TABLE IF EXISTS `inhe_attachment`;

CREATE TABLE `inhe_attachment` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `filename` text,
  `path` text,
  `size` varchar(30) DEFAULT NULL,
  `relatedId` varchar(150) DEFAULT NULL,
  `type` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `relatedId` (`relatedId`),
  KEY `type` (`type`)
) ENGINE=InnoDB AUTO_INCREMENT=25489 DEFAULT CHARSET=gbk;

/*Table structure for table `inhe_authorization_letter` */

DROP TABLE IF EXISTS `inhe_authorization_letter`;

CREATE TABLE `inhe_authorization_letter` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `organ_id` int(11) unsigned NOT NULL DEFAULT '1',
  `user_id` varchar(20) NOT NULL DEFAULT 'admin',
  `create_time` datetime DEFAULT '0000-00-00 00:00:00',
  `update_time` datetime DEFAULT NULL,
  `title` varchar(255) DEFAULT NULL,
  `project_code` varchar(255) DEFAULT NULL,
  `bid_type` varchar(255) DEFAULT NULL,
  `opening_time` varchar(255) DEFAULT NULL,
  `status` varchar(255) DEFAULT NULL,
  `effective_date` date DEFAULT NULL,
  `end_date` date DEFAULT NULL,
  `bank_charge` varchar(255) DEFAULT NULL,
  `transfer_fee` varchar(255) DEFAULT NULL,
  `currency` varchar(255) DEFAULT NULL,
  `guarantee_amount` varchar(255) DEFAULT NULL,
  `margin_amount` varchar(255) DEFAULT NULL,
  `occupation_quota` varchar(255) DEFAULT NULL,
  `opening_bank` varchar(255) DEFAULT NULL,
  `transfer_bank` varchar(255) DEFAULT NULL,
  `apply_dept` varchar(255) DEFAULT NULL,
  `apply_dept_name` varchar(255) DEFAULT NULL,
  `project_country` varchar(255) DEFAULT NULL,
  `flow_no` varchar(100) DEFAULT NULL,
  `commerce_manager` varchar(255) DEFAULT NULL,
  `business_id` varchar(50) DEFAULT NULL,
  `business_leader` varchar(255) DEFAULT NULL,
  `apply_attach` varchar(255) DEFAULT NULL,
  `bank_attach` varchar(255) DEFAULT NULL,
  `transfer_attach` varchar(255) DEFAULT NULL,
  `manage_dept` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=291 DEFAULT CHARSET=gbk;

/*Table structure for table `inhe_authorization_letter_grid` */

DROP TABLE IF EXISTS `inhe_authorization_letter_grid`;

CREATE TABLE `inhe_authorization_letter_grid` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `organ_id` int(11) unsigned NOT NULL DEFAULT '1',
  `user_id` varchar(20) NOT NULL DEFAULT 'admin',
  `create_time` datetime DEFAULT '0000-00-00 00:00:00',
  `update_time` datetime DEFAULT NULL,
  `title` varchar(255) DEFAULT NULL,
  `project_code` varchar(255) DEFAULT NULL,
  `bid_type` varchar(255) DEFAULT NULL,
  `opening_time` varchar(255) DEFAULT NULL,
  `status` varchar(255) DEFAULT NULL,
  `effective_date` date DEFAULT NULL,
  `end_date` date DEFAULT NULL,
  `bank_charge` varchar(255) DEFAULT NULL,
  `transfer_fee` varchar(255) DEFAULT NULL,
  `currency` varchar(255) DEFAULT NULL,
  `guarantee_amount` varchar(255) DEFAULT NULL,
  `margin_amount` varchar(255) DEFAULT NULL,
  `occupation_quota` varchar(255) DEFAULT NULL,
  `opening_bank` varchar(255) DEFAULT NULL,
  `transfer_bank` varchar(255) DEFAULT NULL,
  `apply_dept` varchar(255) DEFAULT NULL,
  `apply_dept_name` varchar(255) DEFAULT NULL,
  `project_country` varchar(255) DEFAULT NULL,
  `flow_no` varchar(100) DEFAULT NULL,
  `commerce_manager` varchar(255) DEFAULT NULL,
  `business_id` varchar(50) DEFAULT NULL,
  `business_leader` varchar(255) DEFAULT NULL,
  `apply_attach` varchar(255) DEFAULT NULL,
  `bank_attach` varchar(255) DEFAULT NULL,
  `transfer_attach` varchar(255) DEFAULT NULL,
  `manage_dept` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=gbk;

/*Table structure for table `inhe_authorization_letter_nergy` */

DROP TABLE IF EXISTS `inhe_authorization_letter_nergy`;

CREATE TABLE `inhe_authorization_letter_nergy` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `organ_id` int(11) unsigned NOT NULL DEFAULT '1',
  `user_id` varchar(20) NOT NULL DEFAULT 'admin',
  `create_time` datetime DEFAULT '0000-00-00 00:00:00',
  `update_time` datetime DEFAULT NULL,
  `title` varchar(255) DEFAULT NULL,
  `project_code` varchar(255) DEFAULT NULL,
  `bid_type` varchar(255) DEFAULT NULL,
  `opening_time` varchar(255) DEFAULT NULL,
  `status` varchar(255) DEFAULT NULL,
  `effective_date` date DEFAULT NULL,
  `end_date` date DEFAULT NULL,
  `bank_charge` varchar(255) DEFAULT NULL,
  `transfer_fee` varchar(255) DEFAULT NULL,
  `currency` varchar(255) DEFAULT NULL,
  `guarantee_amount` varchar(255) DEFAULT NULL,
  `margin_amount` varchar(255) DEFAULT NULL,
  `occupation_quota` varchar(255) DEFAULT NULL,
  `opening_bank` varchar(255) DEFAULT NULL,
  `transfer_bank` varchar(255) DEFAULT NULL,
  `apply_dept` varchar(255) DEFAULT NULL,
  `apply_dept_name` varchar(255) DEFAULT NULL,
  `project_country` varchar(255) DEFAULT NULL,
  `flow_no` varchar(100) DEFAULT NULL,
  `commerce_manager` varchar(255) DEFAULT NULL,
  `business_id` varchar(50) DEFAULT NULL,
  `business_leader` varchar(255) DEFAULT NULL,
  `apply_attach` varchar(255) DEFAULT NULL,
  `bank_attach` varchar(255) DEFAULT NULL,
  `transfer_attach` varchar(255) DEFAULT NULL,
  `manage_dept` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=gbk;

/*Table structure for table `inhe_bidding_strategy` */

DROP TABLE IF EXISTS `inhe_bidding_strategy`;

CREATE TABLE `inhe_bidding_strategy` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) DEFAULT NULL,
  `form_id` varchar(255) DEFAULT NULL,
  `tender_no` varchar(255) DEFAULT NULL,
  `end_date` varchar(255) DEFAULT NULL,
  `project_name` text,
  `tender` text,
  `bid_rule` text,
  `biding_rule` text,
  `effective_demand` text,
  `tender_demand` text,
  `file_demand` text,
  `format` text,
  `reason` text,
  `process_description` text,
  `pay_process` text,
  `pay_energy` text,
  `partner_analysis` text,
  `relationship` text,
  `market_analysis` text,
  `strategy_analysis` text,
  `key_to_success` text,
  `personal_opinion` text,
  `project_level` varchar(255) DEFAULT NULL,
  `star_icon` varchar(255) DEFAULT NULL,
  `have_manager` varchar(255) DEFAULT NULL,
  `signature` varchar(255) DEFAULT NULL,
  `user_bu` varchar(255) DEFAULT NULL,
  `feasibility_analysis` text COMMENT '可行性分析会议纪要',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4229 DEFAULT CHARSET=gbk;

/*Table structure for table `inhe_contract_detail` */

DROP TABLE IF EXISTS `inhe_contract_detail`;

CREATE TABLE `inhe_contract_detail` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `form_id` varchar(255) DEFAULT NULL,
  `total_amount` varchar(255) DEFAULT NULL,
  `create_user` varchar(255) DEFAULT NULL,
  `dept_id` int(11) DEFAULT NULL,
  `business_attribution` varchar(255) DEFAULT NULL,
  `form_date` date DEFAULT NULL,
  `transport_mode` varchar(255) DEFAULT NULL,
  `freight_bearing` varchar(255) DEFAULT NULL,
  `delivery_place` varchar(255) DEFAULT NULL,
  `shipping_address` varchar(255) DEFAULT NULL,
  `package_requirement` varchar(255) DEFAULT NULL,
  `acceptance_criteria` varchar(255) DEFAULT NULL,
  `have_entrepot_trade` varchar(255) DEFAULT NULL,
  `involved_product` varchar(255) DEFAULT NULL,
  `quote_source` varchar(255) DEFAULT NULL,
  `history_project_code` varchar(255) DEFAULT NULL,
  `have_tech_specification` varchar(255) DEFAULT NULL,
  `have_email_approval` varchar(255) DEFAULT NULL,
  `email_name` varchar(255) DEFAULT NULL,
  `product_model` varchar(255) DEFAULT NULL,
  `have_inventory` varchar(255) DEFAULT NULL,
  `functional_requirement` text,
  `special_requirement` text,
  `change_explain` text,
  `spare_requirement` text COMMENT '备品要求',
  `user_bu` varchar(255) DEFAULT NULL,
  `business_dept_id` int(11) DEFAULT NULL,
  `business_user_id` varchar(255) DEFAULT NULL,
  `if_eligible` varchar(10) DEFAULT NULL COMMENT '是否符合：公司格式合同 + 全款提货 + 有库存 + 高于最底价 + 运保费足够？',
  `contract_background` text COMMENT '合同背景',
  `is_collective` varchar(10) DEFAULT NULL COMMENT '是否为集抄项目',
  `past_pcode` varchar(255) DEFAULT NULL COMMENT '本市场过往项目代号',
  `c_past_pcode` varchar(255) DEFAULT NULL COMMENT '本市场不同客户曾经出现过的项目代号',
  `product_project_type` varchar(255) DEFAULT NULL COMMENT '项目类型',
  `financing_bank` varchar(255) DEFAULT NULL COMMENT '若是融资行资金，需填写融资行名称',
  `funds_come` varchar(255) DEFAULT NULL COMMENT '项目资金来源',
  `contract_begin_time` datetime DEFAULT NULL COMMENT '合同开始时间',
  `contract_end_time` datetime DEFAULT NULL COMMENT '合同结束时间',
  `customer_service` varchar(100) DEFAULT NULL COMMENT '交付经理/交付项目负责人',
  PRIMARY KEY (`id`),
  KEY `form_id` (`form_id`)
) ENGINE=InnoDB AUTO_INCREMENT=4093 DEFAULT CHARSET=gbk;

/*Table structure for table `inhe_contract_protocol` */

DROP TABLE IF EXISTS `inhe_contract_protocol`;

CREATE TABLE `inhe_contract_protocol` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `dept_id` int(11) DEFAULT NULL,
  `create_user` varchar(30) DEFAULT NULL,
  `create_time` datetime DEFAULT NULL,
  `update_time` datetime DEFAULT NULL,
  `approve_user` varchar(255) DEFAULT NULL,
  `approve_time` datetime DEFAULT NULL,
  `title` varchar(255) DEFAULT NULL,
  `serial_number` int(11) unsigned NOT NULL,
  `continent` varchar(255) DEFAULT NULL,
  `country` varchar(255) DEFAULT NULL,
  `project_code` varchar(255) DEFAULT NULL,
  `contract_party` varchar(255) DEFAULT NULL,
  `protocol_no` varchar(100) DEFAULT NULL,
  `detail_type` varchar(255) DEFAULT NULL,
  `type` varchar(255) DEFAULT NULL,
  `if_exclusive` varchar(255) DEFAULT NULL,
  `effective_condition` varchar(255) DEFAULT NULL,
  `effective_date` varchar(20) DEFAULT NULL,
  `end_date` varchar(50) DEFAULT NULL,
  `sign_date` varchar(255) DEFAULT NULL,
  `flow_no` varchar(255) DEFAULT NULL,
  `apply_dept` varchar(255) DEFAULT NULL,
  `apply_dept_name` varchar(255) DEFAULT NULL,
  `user_id` varchar(30) DEFAULT NULL,
  `user_name` varchar(255) DEFAULT NULL,
  `if_archive` varchar(255) DEFAULT NULL,
  `archive_mode` varchar(255) DEFAULT NULL,
  `attach_id` int(11) DEFAULT NULL,
  `attach_name` varchar(255) DEFAULT NULL,
  `remark` varchar(255) DEFAULT NULL,
  `manage_dept` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=671 DEFAULT CHARSET=gbk;

/*Table structure for table `inhe_contract_protocol_grid` */

DROP TABLE IF EXISTS `inhe_contract_protocol_grid`;

CREATE TABLE `inhe_contract_protocol_grid` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `dept_id` int(11) DEFAULT NULL,
  `create_user` varchar(30) DEFAULT NULL,
  `create_time` datetime DEFAULT NULL,
  `update_time` datetime DEFAULT NULL,
  `approve_user` varchar(255) DEFAULT NULL,
  `approve_time` datetime DEFAULT NULL,
  `title` varchar(255) DEFAULT NULL,
  `serial_number` int(11) unsigned NOT NULL,
  `continent` varchar(255) DEFAULT NULL,
  `country` varchar(255) DEFAULT NULL,
  `project_code` varchar(255) DEFAULT NULL,
  `contract_party` varchar(255) DEFAULT NULL,
  `protocol_no` varchar(100) DEFAULT NULL,
  `detail_type` varchar(255) DEFAULT NULL,
  `type` varchar(255) DEFAULT NULL,
  `if_exclusive` varchar(255) DEFAULT NULL,
  `effective_condition` varchar(255) DEFAULT NULL,
  `effective_date` varchar(20) DEFAULT NULL,
  `end_date` varchar(50) DEFAULT NULL,
  `sign_date` varchar(255) DEFAULT NULL,
  `flow_no` varchar(255) DEFAULT NULL,
  `apply_dept` varchar(255) DEFAULT NULL,
  `apply_dept_name` varchar(255) DEFAULT NULL,
  `user_id` varchar(30) DEFAULT NULL,
  `user_name` varchar(255) DEFAULT NULL,
  `if_archive` varchar(255) DEFAULT NULL,
  `archive_mode` varchar(255) DEFAULT NULL,
  `attach_id` int(11) DEFAULT NULL,
  `attach_name` varchar(255) DEFAULT NULL,
  `remark` varchar(255) DEFAULT NULL,
  `manage_dept` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=gbk;

/*Table structure for table `inhe_contract_protocol_nergy` */

DROP TABLE IF EXISTS `inhe_contract_protocol_nergy`;

CREATE TABLE `inhe_contract_protocol_nergy` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `dept_id` int(11) DEFAULT NULL,
  `create_user` varchar(30) DEFAULT NULL,
  `create_time` datetime DEFAULT NULL,
  `update_time` datetime DEFAULT NULL,
  `approve_user` varchar(255) DEFAULT NULL,
  `approve_time` datetime DEFAULT NULL,
  `title` varchar(255) DEFAULT NULL,
  `serial_number` int(11) unsigned NOT NULL,
  `continent` varchar(255) DEFAULT NULL,
  `country` varchar(255) DEFAULT NULL,
  `project_code` varchar(255) DEFAULT NULL,
  `contract_party` varchar(255) DEFAULT NULL,
  `protocol_no` varchar(100) DEFAULT NULL,
  `detail_type` varchar(255) DEFAULT NULL,
  `type` varchar(255) DEFAULT NULL,
  `if_exclusive` varchar(255) DEFAULT NULL,
  `effective_condition` varchar(255) DEFAULT NULL,
  `effective_date` varchar(20) DEFAULT NULL,
  `end_date` varchar(50) DEFAULT NULL,
  `sign_date` varchar(255) DEFAULT NULL,
  `flow_no` varchar(255) DEFAULT NULL,
  `apply_dept` varchar(255) DEFAULT NULL,
  `apply_dept_name` varchar(255) DEFAULT NULL,
  `user_id` varchar(30) DEFAULT NULL,
  `user_name` varchar(255) DEFAULT NULL,
  `if_archive` varchar(255) DEFAULT NULL,
  `archive_mode` varchar(255) DEFAULT NULL,
  `attach_id` int(11) DEFAULT NULL,
  `attach_name` varchar(255) DEFAULT NULL,
  `remark` varchar(255) DEFAULT NULL,
  `manage_dept` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=gbk;

/*Table structure for table `inhe_contract_review` */

DROP TABLE IF EXISTS `inhe_contract_review`;

CREATE TABLE `inhe_contract_review` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `organ_id` varchar(255) DEFAULT NULL,
  `user_id` varchar(255) DEFAULT NULL,
  `write_time` datetime DEFAULT NULL,
  `update_time` datetime DEFAULT NULL,
  `title` varchar(255) DEFAULT NULL,
  `form_id` varchar(255) DEFAULT NULL,
  `related_id` varchar(255) DEFAULT NULL,
  `customer_name` varchar(255) DEFAULT NULL,
  `contract_party` varchar(255) DEFAULT NULL,
  `contract_number` varchar(255) DEFAULT NULL,
  `project_name` varchar(255) DEFAULT NULL,
  `project_number` varchar(50) DEFAULT NULL,
  `currency` varchar(255) DEFAULT NULL,
  `delivery_time` varchar(255) DEFAULT NULL,
  `project_star` varchar(255) DEFAULT NULL,
  `contract_belong` varchar(255) DEFAULT NULL,
  `status` varchar(255) DEFAULT NULL,
  `type` varchar(255) DEFAULT NULL,
  `user_bu` varchar(255) DEFAULT NULL,
  `version` tinyint(3) NOT NULL DEFAULT '0',
  `customer_order_no` varchar(255) DEFAULT NULL COMMENT '客户采购合同编号',
  `contract_type` tinyint(3) DEFAULT '1' COMMENT '耐吉合同评审流程类型 1批量合同 2样机合同',
  `from_id` varchar(100) DEFAULT NULL COMMENT '来自抛转表单ID',
  `create_user_bu` varchar(100) DEFAULT NULL COMMENT '创建人公司',
  `urgent` varchar(10) DEFAULT NULL COMMENT '是否紧急',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5084 DEFAULT CHARSET=gbk;

/*Table structure for table `inhe_contract_settle` */

DROP TABLE IF EXISTS `inhe_contract_settle`;

CREATE TABLE `inhe_contract_settle` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `form_id` varchar(255) DEFAULT NULL,
  `item_id` varchar(255) DEFAULT NULL,
  `content` text,
  `attach_name` varchar(255) DEFAULT NULL,
  `user_bu` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `form_id` (`form_id`)
) ENGINE=InnoDB AUTO_INCREMENT=65353 DEFAULT CHARSET=gbk;

/*Table structure for table `inhe_crm_agreement` */

DROP TABLE IF EXISTS `inhe_crm_agreement`;

CREATE TABLE `inhe_crm_agreement` (
  `SC_NO` varchar(40) NOT NULL,
  `PM_ID` varchar(40) DEFAULT NULL,
  `USER_NAME` varchar(255) DEFAULT NULL COMMENT '编号使用人',
  `USER_ID` varchar(40) DEFAULT NULL COMMENT '取号人ID',
  `Create_date` datetime DEFAULT NULL COMMENT '取号时间',
  `Partner_name` varchar(255) DEFAULT NULL,
  `agreement_type` varchar(255) DEFAULT NULL,
  `area_name` varchar(255) DEFAULT NULL,
  `remark` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`SC_NO`)
) ENGINE=InnoDB DEFAULT CHARSET=gbk;

/*Table structure for table `inhe_crm_agreement_grid` */

DROP TABLE IF EXISTS `inhe_crm_agreement_grid`;

CREATE TABLE `inhe_crm_agreement_grid` (
  `SC_NO` varchar(20) NOT NULL,
  `PM_ID` varchar(20) DEFAULT NULL,
  `USER_NAME` varchar(255) DEFAULT NULL COMMENT '编号使用人',
  `USER_ID` varchar(40) DEFAULT NULL COMMENT '取号人ID',
  `Create_date` datetime DEFAULT NULL COMMENT '取号时间',
  `Partner_name` varchar(255) DEFAULT NULL,
  `agreement_type` varchar(255) DEFAULT NULL,
  `area_name` varchar(255) DEFAULT NULL,
  `remark` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`SC_NO`)
) ENGINE=InnoDB DEFAULT CHARSET=gbk;

/*Table structure for table `inhe_crm_agreement_ihjx` */

DROP TABLE IF EXISTS `inhe_crm_agreement_ihjx`;

CREATE TABLE `inhe_crm_agreement_ihjx` (
  `SC_NO` varchar(40) NOT NULL,
  `PM_ID` varchar(40) DEFAULT NULL,
  `USER_NAME` varchar(255) DEFAULT NULL COMMENT '编号使用人',
  `USER_ID` varchar(40) DEFAULT NULL COMMENT '取号人ID',
  `Create_date` datetime DEFAULT NULL COMMENT '取号时间',
  `Partner_name` varchar(255) DEFAULT NULL,
  `agreement_type` varchar(255) DEFAULT NULL,
  `area_name` varchar(255) DEFAULT NULL,
  `remark` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`SC_NO`)
) ENGINE=InnoDB DEFAULT CHARSET=gbk;

/*Table structure for table `inhe_crm_agreement_jxih` */

DROP TABLE IF EXISTS `inhe_crm_agreement_jxih`;

CREATE TABLE `inhe_crm_agreement_jxih` (
  `SC_NO` varchar(40) NOT NULL,
  `PM_ID` varchar(40) DEFAULT NULL,
  `USER_NAME` varchar(255) DEFAULT NULL COMMENT '编号使用人',
  `USER_ID` varchar(40) DEFAULT NULL COMMENT '取号人ID',
  `Create_date` datetime DEFAULT NULL COMMENT '取号时间',
  `Partner_name` varchar(255) DEFAULT NULL,
  `agreement_type` varchar(255) DEFAULT NULL,
  `area_name` varchar(255) DEFAULT NULL,
  `remark` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`SC_NO`)
) ENGINE=InnoDB DEFAULT CHARSET=gbk;

/*Table structure for table `inhe_crm_agreement_nergy` */

DROP TABLE IF EXISTS `inhe_crm_agreement_nergy`;

CREATE TABLE `inhe_crm_agreement_nergy` (
  `SC_NO` varchar(20) NOT NULL,
  `PM_ID` varchar(20) DEFAULT NULL,
  `USER_NAME` varchar(255) DEFAULT NULL COMMENT '编号使用人',
  `USER_ID` varchar(40) DEFAULT NULL COMMENT '取号人ID',
  `Create_date` datetime DEFAULT NULL COMMENT '取号时间',
  `Partner_name` varchar(255) DEFAULT NULL,
  `agreement_type` varchar(255) DEFAULT NULL,
  `area_name` varchar(255) DEFAULT NULL,
  `remark` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`SC_NO`)
) ENGINE=InnoDB DEFAULT CHARSET=gbk;

/*Table structure for table `inhe_crm_agreement_witlink` */

DROP TABLE IF EXISTS `inhe_crm_agreement_witlink`;

CREATE TABLE `inhe_crm_agreement_witlink` (
  `SC_NO` varchar(20) NOT NULL,
  `PM_ID` varchar(20) DEFAULT NULL,
  `USER_NAME` varchar(255) DEFAULT NULL COMMENT '编号使用人',
  `USER_ID` varchar(40) DEFAULT NULL COMMENT '取号人ID',
  `Create_date` datetime DEFAULT NULL COMMENT '取号时间',
  `Partner_name` varchar(255) DEFAULT NULL,
  `agreement_type` varchar(255) DEFAULT NULL,
  `area_name` varchar(255) DEFAULT NULL,
  `remark` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`SC_NO`)
) ENGINE=InnoDB DEFAULT CHARSET=gbk;

/*Table structure for table `inhe_crm_invoice` */

DROP TABLE IF EXISTS `inhe_crm_invoice`;

CREATE TABLE `inhe_crm_invoice` (
  `SC_NO` varchar(20) NOT NULL,
  `PM_ID` varchar(20) DEFAULT NULL,
  `USER_NAME` varchar(255) DEFAULT NULL COMMENT '编号使用人',
  `USER_ID` varchar(40) DEFAULT NULL COMMENT '取号人ID',
  `Create_date` datetime DEFAULT NULL COMMENT '取号时间',
  PRIMARY KEY (`SC_NO`)
) ENGINE=InnoDB DEFAULT CHARSET=gbk;

/*Table structure for table `inhe_crm_invoice_grid` */

DROP TABLE IF EXISTS `inhe_crm_invoice_grid`;

CREATE TABLE `inhe_crm_invoice_grid` (
  `SC_NO` varchar(20) NOT NULL,
  `PM_ID` varchar(20) DEFAULT NULL,
  `USER_NAME` varchar(255) DEFAULT NULL COMMENT '编号使用人',
  `USER_ID` varchar(40) DEFAULT NULL COMMENT '取号人ID',
  `Create_date` datetime DEFAULT NULL COMMENT '取号时间',
  PRIMARY KEY (`SC_NO`)
) ENGINE=InnoDB DEFAULT CHARSET=gbk;

/*Table structure for table `inhe_crm_invoice_ihjx` */

DROP TABLE IF EXISTS `inhe_crm_invoice_ihjx`;

CREATE TABLE `inhe_crm_invoice_ihjx` (
  `SC_NO` varchar(20) NOT NULL,
  `PM_ID` varchar(20) DEFAULT NULL,
  `USER_NAME` varchar(255) DEFAULT NULL COMMENT '编号使用人',
  `USER_ID` varchar(40) DEFAULT NULL COMMENT '取号人ID',
  `Create_date` datetime DEFAULT NULL COMMENT '取号时间',
  PRIMARY KEY (`SC_NO`)
) ENGINE=InnoDB DEFAULT CHARSET=gbk;

/*Table structure for table `inhe_crm_invoice_jxih` */

DROP TABLE IF EXISTS `inhe_crm_invoice_jxih`;

CREATE TABLE `inhe_crm_invoice_jxih` (
  `SC_NO` varchar(20) NOT NULL,
  `PM_ID` varchar(20) DEFAULT NULL,
  `USER_NAME` varchar(255) DEFAULT NULL COMMENT '编号使用人',
  `USER_ID` varchar(40) DEFAULT NULL COMMENT '取号人ID',
  `Create_date` datetime DEFAULT NULL COMMENT '取号时间',
  PRIMARY KEY (`SC_NO`)
) ENGINE=InnoDB DEFAULT CHARSET=gbk;

/*Table structure for table `inhe_crm_invoice_nergy` */

DROP TABLE IF EXISTS `inhe_crm_invoice_nergy`;

CREATE TABLE `inhe_crm_invoice_nergy` (
  `SC_NO` varchar(20) NOT NULL,
  `PM_ID` varchar(20) DEFAULT NULL,
  `USER_NAME` varchar(255) DEFAULT NULL COMMENT '编号使用人',
  `USER_ID` varchar(40) DEFAULT NULL COMMENT '取号人ID',
  `Create_date` datetime DEFAULT NULL COMMENT '取号时间',
  PRIMARY KEY (`SC_NO`)
) ENGINE=InnoDB DEFAULT CHARSET=gbk;

/*Table structure for table `inhe_crm_invoice_witlink` */

DROP TABLE IF EXISTS `inhe_crm_invoice_witlink`;

CREATE TABLE `inhe_crm_invoice_witlink` (
  `SC_NO` varchar(20) NOT NULL,
  `PM_ID` varchar(20) DEFAULT NULL,
  `USER_NAME` varchar(255) DEFAULT NULL COMMENT '编号使用人',
  `USER_ID` varchar(40) DEFAULT NULL COMMENT '取号人ID',
  `Create_date` datetime DEFAULT NULL COMMENT '取号时间',
  PRIMARY KEY (`SC_NO`)
) ENGINE=InnoDB DEFAULT CHARSET=gbk;

/*Table structure for table `inhe_crm_memorandum` */

DROP TABLE IF EXISTS `inhe_crm_memorandum`;

CREATE TABLE `inhe_crm_memorandum` (
  `SC_NO` varchar(20) NOT NULL,
  `PM_ID` varchar(20) DEFAULT NULL,
  `USER_NAME` varchar(255) DEFAULT NULL COMMENT '编号使用人',
  `USER_ID` varchar(40) DEFAULT NULL COMMENT '取号人ID',
  `Create_date` datetime DEFAULT NULL COMMENT '取号时间',
  PRIMARY KEY (`SC_NO`)
) ENGINE=InnoDB DEFAULT CHARSET=gbk;

/*Table structure for table `inhe_crm_memorandum_grid` */

DROP TABLE IF EXISTS `inhe_crm_memorandum_grid`;

CREATE TABLE `inhe_crm_memorandum_grid` (
  `SC_NO` varchar(20) NOT NULL,
  `PM_ID` varchar(20) DEFAULT NULL,
  `USER_NAME` varchar(255) DEFAULT NULL COMMENT '编号使用人',
  `USER_ID` varchar(40) DEFAULT NULL COMMENT '取号人ID',
  `Create_date` datetime DEFAULT NULL COMMENT '取号时间',
  PRIMARY KEY (`SC_NO`)
) ENGINE=InnoDB DEFAULT CHARSET=gbk;

/*Table structure for table `inhe_crm_memorandum_ihjx` */

DROP TABLE IF EXISTS `inhe_crm_memorandum_ihjx`;

CREATE TABLE `inhe_crm_memorandum_ihjx` (
  `SC_NO` varchar(20) NOT NULL,
  `PM_ID` varchar(20) DEFAULT NULL,
  `USER_NAME` varchar(255) DEFAULT NULL COMMENT '编号使用人',
  `USER_ID` varchar(40) DEFAULT NULL COMMENT '取号人ID',
  `Create_date` datetime DEFAULT NULL COMMENT '取号时间',
  PRIMARY KEY (`SC_NO`)
) ENGINE=InnoDB DEFAULT CHARSET=gbk;

/*Table structure for table `inhe_crm_memorandum_jxih` */

DROP TABLE IF EXISTS `inhe_crm_memorandum_jxih`;

CREATE TABLE `inhe_crm_memorandum_jxih` (
  `SC_NO` varchar(20) NOT NULL,
  `PM_ID` varchar(20) DEFAULT NULL,
  `USER_NAME` varchar(255) DEFAULT NULL COMMENT '编号使用人',
  `USER_ID` varchar(40) DEFAULT NULL COMMENT '取号人ID',
  `Create_date` datetime DEFAULT NULL COMMENT '取号时间',
  PRIMARY KEY (`SC_NO`)
) ENGINE=InnoDB DEFAULT CHARSET=gbk;

/*Table structure for table `inhe_crm_memorandum_nergy` */

DROP TABLE IF EXISTS `inhe_crm_memorandum_nergy`;

CREATE TABLE `inhe_crm_memorandum_nergy` (
  `SC_NO` varchar(20) NOT NULL,
  `PM_ID` varchar(20) DEFAULT NULL,
  `USER_NAME` varchar(255) DEFAULT NULL COMMENT '编号使用人',
  `USER_ID` varchar(40) DEFAULT NULL COMMENT '取号人ID',
  `Create_date` datetime DEFAULT NULL COMMENT '取号时间',
  PRIMARY KEY (`SC_NO`)
) ENGINE=InnoDB DEFAULT CHARSET=gbk;

/*Table structure for table `inhe_crm_memorandum_witlink` */

DROP TABLE IF EXISTS `inhe_crm_memorandum_witlink`;

CREATE TABLE `inhe_crm_memorandum_witlink` (
  `SC_NO` varchar(20) NOT NULL,
  `PM_ID` varchar(20) DEFAULT NULL,
  `USER_NAME` varchar(255) DEFAULT NULL COMMENT '编号使用人',
  `USER_ID` varchar(40) DEFAULT NULL COMMENT '取号人ID',
  `Create_date` datetime DEFAULT NULL COMMENT '取号时间',
  PRIMARY KEY (`SC_NO`)
) ENGINE=InnoDB DEFAULT CHARSET=gbk;

/*Table structure for table `inhe_crm_meter` */

DROP TABLE IF EXISTS `inhe_crm_meter`;

CREATE TABLE `inhe_crm_meter` (
  `SC_NO` varchar(20) NOT NULL,
  `PM_ID` varchar(20) DEFAULT NULL,
  `USER_NAME` varchar(255) DEFAULT NULL COMMENT '编号使用人',
  `USER_ID` varchar(40) DEFAULT NULL COMMENT '取号人ID',
  `Create_date` datetime DEFAULT NULL COMMENT '取号时间',
  PRIMARY KEY (`SC_NO`)
) ENGINE=InnoDB DEFAULT CHARSET=gbk;

/*Table structure for table `inhe_crm_meter_f1` */

DROP TABLE IF EXISTS `inhe_crm_meter_f1`;

CREATE TABLE `inhe_crm_meter_f1` (
  `SC_NO` varchar(20) NOT NULL,
  `PM_ID` varchar(20) DEFAULT NULL,
  `USER_NAME` varchar(255) DEFAULT NULL COMMENT '编号使用人',
  `USER_ID` varchar(40) DEFAULT NULL COMMENT '取号人ID',
  `Create_date` datetime DEFAULT NULL COMMENT '取号时间',
  PRIMARY KEY (`SC_NO`)
) ENGINE=InnoDB DEFAULT CHARSET=gbk;

/*Table structure for table `inhe_crm_meter_f1_ihjx` */

DROP TABLE IF EXISTS `inhe_crm_meter_f1_ihjx`;

CREATE TABLE `inhe_crm_meter_f1_ihjx` (
  `SC_NO` varchar(20) NOT NULL,
  `PM_ID` varchar(20) DEFAULT NULL,
  `USER_NAME` varchar(255) DEFAULT NULL COMMENT '编号使用人',
  `USER_ID` varchar(40) DEFAULT NULL COMMENT '取号人ID',
  `Create_date` datetime DEFAULT NULL COMMENT '取号时间',
  PRIMARY KEY (`SC_NO`)
) ENGINE=InnoDB DEFAULT CHARSET=gbk;

/*Table structure for table `inhe_crm_meter_f1_jxih` */

DROP TABLE IF EXISTS `inhe_crm_meter_f1_jxih`;

CREATE TABLE `inhe_crm_meter_f1_jxih` (
  `SC_NO` varchar(20) NOT NULL,
  `PM_ID` varchar(20) DEFAULT NULL,
  `USER_NAME` varchar(255) DEFAULT NULL COMMENT '编号使用人',
  `USER_ID` varchar(40) DEFAULT NULL COMMENT '取号人ID',
  `Create_date` datetime DEFAULT NULL COMMENT '取号时间',
  PRIMARY KEY (`SC_NO`)
) ENGINE=InnoDB DEFAULT CHARSET=gbk;

/*Table structure for table `inhe_crm_meter_f3` */

DROP TABLE IF EXISTS `inhe_crm_meter_f3`;

CREATE TABLE `inhe_crm_meter_f3` (
  `SC_NO` varchar(50) NOT NULL,
  `PM_ID` varchar(50) DEFAULT NULL,
  `USER_NAME` varchar(255) DEFAULT NULL COMMENT '编号使用人',
  `USER_ID` varchar(40) DEFAULT NULL COMMENT '取号人ID',
  `Create_date` datetime DEFAULT NULL COMMENT '取号时间',
  PRIMARY KEY (`SC_NO`)
) ENGINE=InnoDB DEFAULT CHARSET=gbk;

/*Table structure for table `inhe_crm_meter_f3_ihjx` */

DROP TABLE IF EXISTS `inhe_crm_meter_f3_ihjx`;

CREATE TABLE `inhe_crm_meter_f3_ihjx` (
  `SC_NO` varchar(50) NOT NULL,
  `PM_ID` varchar(50) DEFAULT NULL,
  `USER_NAME` varchar(255) DEFAULT NULL COMMENT '编号使用人',
  `USER_ID` varchar(40) DEFAULT NULL COMMENT '取号人ID',
  `Create_date` datetime DEFAULT NULL COMMENT '取号时间',
  PRIMARY KEY (`SC_NO`)
) ENGINE=InnoDB DEFAULT CHARSET=gbk;

/*Table structure for table `inhe_crm_meter_f3_jxih` */

DROP TABLE IF EXISTS `inhe_crm_meter_f3_jxih`;

CREATE TABLE `inhe_crm_meter_f3_jxih` (
  `SC_NO` varchar(50) NOT NULL,
  `PM_ID` varchar(50) DEFAULT NULL,
  `USER_NAME` varchar(255) DEFAULT NULL COMMENT '编号使用人',
  `USER_ID` varchar(40) DEFAULT NULL COMMENT '取号人ID',
  `Create_date` datetime DEFAULT NULL COMMENT '取号时间',
  PRIMARY KEY (`SC_NO`)
) ENGINE=InnoDB DEFAULT CHARSET=gbk;

/*Table structure for table `inhe_crm_meter_ihjx` */

DROP TABLE IF EXISTS `inhe_crm_meter_ihjx`;

CREATE TABLE `inhe_crm_meter_ihjx` (
  `SC_NO` varchar(20) NOT NULL,
  `PM_ID` varchar(20) DEFAULT NULL,
  `USER_NAME` varchar(255) DEFAULT NULL COMMENT '编号使用人',
  `USER_ID` varchar(40) DEFAULT NULL COMMENT '取号人ID',
  `Create_date` datetime DEFAULT NULL COMMENT '取号时间',
  PRIMARY KEY (`SC_NO`)
) ENGINE=InnoDB DEFAULT CHARSET=gbk;

/*Table structure for table `inhe_crm_meter_jxih` */

DROP TABLE IF EXISTS `inhe_crm_meter_jxih`;

CREATE TABLE `inhe_crm_meter_jxih` (
  `SC_NO` varchar(20) NOT NULL,
  `PM_ID` varchar(20) DEFAULT NULL,
  `USER_NAME` varchar(255) DEFAULT NULL COMMENT '编号使用人',
  `USER_ID` varchar(40) DEFAULT NULL COMMENT '取号人ID',
  `Create_date` datetime DEFAULT NULL COMMENT '取号时间',
  PRIMARY KEY (`SC_NO`)
) ENGINE=InnoDB DEFAULT CHARSET=gbk;

/*Table structure for table `inhe_crm_official_letter` */

DROP TABLE IF EXISTS `inhe_crm_official_letter`;

CREATE TABLE `inhe_crm_official_letter` (
  `SC_NO` varchar(20) NOT NULL,
  `PM_ID` varchar(20) DEFAULT NULL,
  `USER_NAME` varchar(255) DEFAULT NULL COMMENT '编号使用人',
  `USER_ID` varchar(40) DEFAULT NULL COMMENT '取号人ID',
  `Create_date` datetime DEFAULT NULL COMMENT '取号时间',
  PRIMARY KEY (`SC_NO`)
) ENGINE=InnoDB DEFAULT CHARSET=gbk;

/*Table structure for table `inhe_crm_official_letter_grid` */

DROP TABLE IF EXISTS `inhe_crm_official_letter_grid`;

CREATE TABLE `inhe_crm_official_letter_grid` (
  `SC_NO` varchar(20) NOT NULL,
  `PM_ID` varchar(20) DEFAULT NULL,
  `USER_NAME` varchar(255) DEFAULT NULL COMMENT '编号使用人',
  `USER_ID` varchar(40) DEFAULT NULL COMMENT '取号人ID',
  `Create_date` datetime DEFAULT NULL COMMENT '取号时间',
  PRIMARY KEY (`SC_NO`)
) ENGINE=InnoDB DEFAULT CHARSET=gbk;

/*Table structure for table `inhe_crm_official_letter_ihjx` */

DROP TABLE IF EXISTS `inhe_crm_official_letter_ihjx`;

CREATE TABLE `inhe_crm_official_letter_ihjx` (
  `SC_NO` varchar(20) NOT NULL,
  `PM_ID` varchar(20) DEFAULT NULL,
  `USER_NAME` varchar(255) DEFAULT NULL COMMENT '编号使用人',
  `USER_ID` varchar(40) DEFAULT NULL COMMENT '取号人ID',
  `Create_date` datetime DEFAULT NULL COMMENT '取号时间',
  PRIMARY KEY (`SC_NO`)
) ENGINE=InnoDB DEFAULT CHARSET=gbk;

/*Table structure for table `inhe_crm_official_letter_jxih` */

DROP TABLE IF EXISTS `inhe_crm_official_letter_jxih`;

CREATE TABLE `inhe_crm_official_letter_jxih` (
  `SC_NO` varchar(20) NOT NULL,
  `PM_ID` varchar(20) DEFAULT NULL,
  `USER_NAME` varchar(255) DEFAULT NULL COMMENT '编号使用人',
  `USER_ID` varchar(40) DEFAULT NULL COMMENT '取号人ID',
  `Create_date` datetime DEFAULT NULL COMMENT '取号时间',
  PRIMARY KEY (`SC_NO`)
) ENGINE=InnoDB DEFAULT CHARSET=gbk;

/*Table structure for table `inhe_crm_official_letter_nergy` */

DROP TABLE IF EXISTS `inhe_crm_official_letter_nergy`;

CREATE TABLE `inhe_crm_official_letter_nergy` (
  `SC_NO` varchar(20) NOT NULL,
  `PM_ID` varchar(20) DEFAULT NULL,
  `USER_NAME` varchar(255) DEFAULT NULL COMMENT '编号使用人',
  `USER_ID` varchar(40) DEFAULT NULL COMMENT '取号人ID',
  `Create_date` datetime DEFAULT NULL COMMENT '取号时间',
  PRIMARY KEY (`SC_NO`)
) ENGINE=InnoDB DEFAULT CHARSET=gbk;

/*Table structure for table `inhe_crm_official_letter_witlink` */

DROP TABLE IF EXISTS `inhe_crm_official_letter_witlink`;

CREATE TABLE `inhe_crm_official_letter_witlink` (
  `SC_NO` varchar(20) NOT NULL,
  `PM_ID` varchar(20) DEFAULT NULL,
  `USER_NAME` varchar(255) DEFAULT NULL COMMENT '编号使用人',
  `USER_ID` varchar(40) DEFAULT NULL COMMENT '取号人ID',
  `Create_date` datetime DEFAULT NULL COMMENT '取号时间',
  PRIMARY KEY (`SC_NO`)
) ENGINE=InnoDB DEFAULT CHARSET=gbk;

/*Table structure for table `inhe_crm_product_f1` */

DROP TABLE IF EXISTS `inhe_crm_product_f1`;

CREATE TABLE `inhe_crm_product_f1` (
  `SC_NO` varchar(20) NOT NULL,
  `PM_ID` varchar(20) DEFAULT NULL,
  `USER_NAME` varchar(255) DEFAULT NULL COMMENT '编号使用人',
  `USER_ID` varchar(40) DEFAULT NULL COMMENT '取号人ID',
  `Create_date` datetime DEFAULT NULL COMMENT '取号时间',
  PRIMARY KEY (`SC_NO`)
) ENGINE=InnoDB DEFAULT CHARSET=gbk;

/*Table structure for table `inhe_crm_product_f1_ihjx` */

DROP TABLE IF EXISTS `inhe_crm_product_f1_ihjx`;

CREATE TABLE `inhe_crm_product_f1_ihjx` (
  `SC_NO` varchar(20) NOT NULL,
  `PM_ID` varchar(20) DEFAULT NULL,
  `USER_NAME` varchar(255) DEFAULT NULL COMMENT '编号使用人',
  `USER_ID` varchar(40) DEFAULT NULL COMMENT '取号人ID',
  `Create_date` datetime DEFAULT NULL COMMENT '取号时间',
  PRIMARY KEY (`SC_NO`)
) ENGINE=InnoDB DEFAULT CHARSET=gbk;

/*Table structure for table `inhe_crm_product_f1_jxih` */

DROP TABLE IF EXISTS `inhe_crm_product_f1_jxih`;

CREATE TABLE `inhe_crm_product_f1_jxih` (
  `SC_NO` varchar(20) NOT NULL,
  `PM_ID` varchar(20) DEFAULT NULL,
  `USER_NAME` varchar(255) DEFAULT NULL COMMENT '编号使用人',
  `USER_ID` varchar(40) DEFAULT NULL COMMENT '取号人ID',
  `Create_date` datetime DEFAULT NULL COMMENT '取号时间',
  PRIMARY KEY (`SC_NO`)
) ENGINE=InnoDB DEFAULT CHARSET=gbk;

/*Table structure for table `inhe_crm_product_f2` */

DROP TABLE IF EXISTS `inhe_crm_product_f2`;

CREATE TABLE `inhe_crm_product_f2` (
  `SC_NO` varchar(40) NOT NULL,
  `PM_ID` varchar(255) DEFAULT NULL,
  `USER_NAME` varchar(255) DEFAULT NULL COMMENT '编号使用人',
  `USER_ID` varchar(40) DEFAULT NULL COMMENT '取号人ID',
  `Create_date` datetime DEFAULT NULL COMMENT '取号时间',
  PRIMARY KEY (`SC_NO`)
) ENGINE=InnoDB DEFAULT CHARSET=gbk;

/*Table structure for table `inhe_crm_product_f2_ihjx` */

DROP TABLE IF EXISTS `inhe_crm_product_f2_ihjx`;

CREATE TABLE `inhe_crm_product_f2_ihjx` (
  `SC_NO` varchar(40) NOT NULL,
  `PM_ID` varchar(255) DEFAULT NULL,
  `USER_NAME` varchar(255) DEFAULT NULL COMMENT '编号使用人',
  `USER_ID` varchar(40) DEFAULT NULL COMMENT '取号人ID',
  `Create_date` datetime DEFAULT NULL COMMENT '取号时间',
  PRIMARY KEY (`SC_NO`)
) ENGINE=InnoDB DEFAULT CHARSET=gbk;

/*Table structure for table `inhe_crm_product_f2_jxih` */

DROP TABLE IF EXISTS `inhe_crm_product_f2_jxih`;

CREATE TABLE `inhe_crm_product_f2_jxih` (
  `SC_NO` varchar(40) NOT NULL,
  `PM_ID` varchar(255) DEFAULT NULL,
  `USER_NAME` varchar(255) DEFAULT NULL COMMENT '编号使用人',
  `USER_ID` varchar(40) DEFAULT NULL COMMENT '取号人ID',
  `Create_date` datetime DEFAULT NULL COMMENT '取号时间',
  PRIMARY KEY (`SC_NO`)
) ENGINE=InnoDB DEFAULT CHARSET=gbk;

/*Table structure for table `inhe_crm_quotation` */

DROP TABLE IF EXISTS `inhe_crm_quotation`;

CREATE TABLE `inhe_crm_quotation` (
  `SC_NO` varchar(20) NOT NULL,
  `PM_ID` varchar(20) DEFAULT NULL,
  `USER_NAME` varchar(255) DEFAULT NULL COMMENT '编号使用人',
  `USER_ID` varchar(40) DEFAULT NULL COMMENT '取号人ID',
  `Create_date` datetime DEFAULT NULL COMMENT '取号时间',
  PRIMARY KEY (`SC_NO`)
) ENGINE=InnoDB DEFAULT CHARSET=gbk;

/*Table structure for table `inhe_crm_quotation_grid` */

DROP TABLE IF EXISTS `inhe_crm_quotation_grid`;

CREATE TABLE `inhe_crm_quotation_grid` (
  `SC_NO` varchar(40) NOT NULL,
  `PM_ID` varchar(255) DEFAULT NULL,
  `USER_NAME` varchar(255) DEFAULT NULL COMMENT '编号使用人',
  `USER_ID` varchar(40) DEFAULT NULL COMMENT '取号人ID',
  `Create_date` datetime DEFAULT NULL COMMENT '取号时间',
  PRIMARY KEY (`SC_NO`)
) ENGINE=InnoDB DEFAULT CHARSET=gbk;

/*Table structure for table `inhe_crm_quotation_ihjx` */

DROP TABLE IF EXISTS `inhe_crm_quotation_ihjx`;

CREATE TABLE `inhe_crm_quotation_ihjx` (
  `SC_NO` varchar(20) NOT NULL,
  `PM_ID` varchar(20) DEFAULT NULL,
  `USER_NAME` varchar(255) DEFAULT NULL COMMENT '编号使用人',
  `USER_ID` varchar(40) DEFAULT NULL COMMENT '取号人ID',
  `Create_date` datetime DEFAULT NULL COMMENT '取号时间',
  PRIMARY KEY (`SC_NO`)
) ENGINE=InnoDB DEFAULT CHARSET=gbk;

/*Table structure for table `inhe_crm_quotation_jxih` */

DROP TABLE IF EXISTS `inhe_crm_quotation_jxih`;

CREATE TABLE `inhe_crm_quotation_jxih` (
  `SC_NO` varchar(20) NOT NULL,
  `PM_ID` varchar(20) DEFAULT NULL,
  `USER_NAME` varchar(255) DEFAULT NULL COMMENT '编号使用人',
  `USER_ID` varchar(40) DEFAULT NULL COMMENT '取号人ID',
  `Create_date` datetime DEFAULT NULL COMMENT '取号时间',
  PRIMARY KEY (`SC_NO`)
) ENGINE=InnoDB DEFAULT CHARSET=gbk;

/*Table structure for table `inhe_crm_quotation_nergy` */

DROP TABLE IF EXISTS `inhe_crm_quotation_nergy`;

CREATE TABLE `inhe_crm_quotation_nergy` (
  `SC_NO` varchar(40) NOT NULL,
  `PM_ID` varchar(255) DEFAULT NULL,
  `USER_NAME` varchar(255) DEFAULT NULL COMMENT '编号使用人',
  `USER_ID` varchar(40) DEFAULT NULL COMMENT '取号人ID',
  `Create_date` datetime DEFAULT NULL COMMENT '取号时间',
  PRIMARY KEY (`SC_NO`)
) ENGINE=InnoDB DEFAULT CHARSET=gbk;

/*Table structure for table `inhe_crm_quotation_witlink` */

DROP TABLE IF EXISTS `inhe_crm_quotation_witlink`;

CREATE TABLE `inhe_crm_quotation_witlink` (
  `SC_NO` varchar(40) NOT NULL,
  `PM_ID` varchar(255) DEFAULT NULL,
  `USER_NAME` varchar(255) DEFAULT NULL COMMENT '编号使用人',
  `USER_ID` varchar(40) DEFAULT NULL COMMENT '取号人ID',
  `Create_date` datetime DEFAULT NULL COMMENT '取号时间',
  PRIMARY KEY (`SC_NO`)
) ENGINE=InnoDB DEFAULT CHARSET=gbk;

/*Table structure for table `inhe_crm_sales_confirmation` */

DROP TABLE IF EXISTS `inhe_crm_sales_confirmation`;

CREATE TABLE `inhe_crm_sales_confirmation` (
  `SC_NO` varchar(20) NOT NULL,
  `PM_ID` varchar(20) DEFAULT NULL,
  `USER_NAME` varchar(255) DEFAULT NULL COMMENT '编号使用人',
  `USER_ID` varchar(40) DEFAULT NULL COMMENT '取号人ID',
  `Create_date` datetime DEFAULT NULL COMMENT '取号时间',
  `S_temp` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`SC_NO`)
) ENGINE=InnoDB DEFAULT CHARSET=gbk;

/*Table structure for table `inhe_crm_sales_confirmation_grid` */

DROP TABLE IF EXISTS `inhe_crm_sales_confirmation_grid`;

CREATE TABLE `inhe_crm_sales_confirmation_grid` (
  `SC_NO` varchar(20) NOT NULL,
  `PM_ID` varchar(20) DEFAULT NULL,
  `USER_NAME` varchar(255) DEFAULT NULL COMMENT '编号使用人',
  `USER_ID` varchar(40) DEFAULT NULL COMMENT '取号人ID',
  `Create_date` datetime DEFAULT NULL COMMENT '取号时间',
  PRIMARY KEY (`SC_NO`)
) ENGINE=InnoDB DEFAULT CHARSET=gbk;

/*Table structure for table `inhe_crm_sales_confirmation_ihjx` */

DROP TABLE IF EXISTS `inhe_crm_sales_confirmation_ihjx`;

CREATE TABLE `inhe_crm_sales_confirmation_ihjx` (
  `SC_NO` varchar(20) NOT NULL,
  `PM_ID` varchar(20) DEFAULT NULL,
  `USER_NAME` varchar(255) DEFAULT NULL COMMENT '编号使用人',
  `USER_ID` varchar(40) DEFAULT NULL COMMENT '取号人ID',
  `Create_date` datetime DEFAULT NULL COMMENT '取号时间',
  `S_temp` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`SC_NO`)
) ENGINE=InnoDB DEFAULT CHARSET=gbk;

/*Table structure for table `inhe_crm_sales_confirmation_jxih` */

DROP TABLE IF EXISTS `inhe_crm_sales_confirmation_jxih`;

CREATE TABLE `inhe_crm_sales_confirmation_jxih` (
  `SC_NO` varchar(20) NOT NULL,
  `PM_ID` varchar(20) DEFAULT NULL,
  `USER_NAME` varchar(255) DEFAULT NULL COMMENT '编号使用人',
  `USER_ID` varchar(40) DEFAULT NULL COMMENT '取号人ID',
  `Create_date` datetime DEFAULT NULL COMMENT '取号时间',
  `S_temp` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`SC_NO`)
) ENGINE=InnoDB DEFAULT CHARSET=gbk;

/*Table structure for table `inhe_crm_sales_confirmation_nergy` */

DROP TABLE IF EXISTS `inhe_crm_sales_confirmation_nergy`;

CREATE TABLE `inhe_crm_sales_confirmation_nergy` (
  `SC_NO` varchar(20) NOT NULL,
  `PM_ID` varchar(20) DEFAULT NULL,
  `USER_NAME` varchar(255) DEFAULT NULL COMMENT '编号使用人',
  `USER_ID` varchar(40) DEFAULT NULL COMMENT '取号人ID',
  `Create_date` datetime DEFAULT NULL COMMENT '取号时间',
  PRIMARY KEY (`SC_NO`)
) ENGINE=InnoDB DEFAULT CHARSET=gbk;

/*Table structure for table `inhe_crm_sales_confirmation_witlink` */

DROP TABLE IF EXISTS `inhe_crm_sales_confirmation_witlink`;

CREATE TABLE `inhe_crm_sales_confirmation_witlink` (
  `SC_NO` varchar(20) NOT NULL,
  `PM_ID` varchar(20) DEFAULT NULL,
  `USER_NAME` varchar(255) DEFAULT NULL COMMENT '编号使用人',
  `USER_ID` varchar(40) DEFAULT NULL COMMENT '取号人ID',
  `Create_date` datetime DEFAULT NULL COMMENT '取号时间',
  PRIMARY KEY (`SC_NO`)
) ENGINE=InnoDB DEFAULT CHARSET=gbk;

/*Table structure for table `inhe_customer_company` */

DROP TABLE IF EXISTS `inhe_customer_company`;

CREATE TABLE `inhe_customer_company` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `customer_id` int(11) DEFAULT NULL,
  `form_id` varchar(100) DEFAULT NULL,
  `contract_party` varchar(500) DEFAULT NULL,
  `short_name` varchar(500) DEFAULT NULL,
  `country` varchar(500) DEFAULT NULL,
  `address` varchar(500) DEFAULT NULL,
  `contact_name` varchar(500) DEFAULT NULL,
  `phone` varchar(500) DEFAULT NULL,
  `user_bu` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2685 DEFAULT CHARSET=gbk;

/*Table structure for table `inhe_customer_contact` */

DROP TABLE IF EXISTS `inhe_customer_contact`;

CREATE TABLE `inhe_customer_contact` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `customer_id` int(11) DEFAULT NULL,
  `form_id` varchar(50) DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  `phone_one` varchar(100) DEFAULT NULL,
  `phone_two` varchar(100) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `remark` text,
  `user_bu` varchar(255) DEFAULT NULL,
  `position` varchar(255) DEFAULT NULL COMMENT '职位',
  PRIMARY KEY (`id`),
  UNIQUE KEY `id` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5015 DEFAULT CHARSET=gbk;

/*Table structure for table `inhe_customer_data` */

DROP TABLE IF EXISTS `inhe_customer_data`;

CREATE TABLE `inhe_customer_data` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `organ_id` int(11) unsigned NOT NULL DEFAULT '1',
  `user_id` varchar(30) NOT NULL DEFAULT 'admin',
  `write_time` varchar(30) DEFAULT '0000-00-00 00:00:00',
  `update_time` datetime DEFAULT NULL,
  `title` varchar(255) DEFAULT NULL,
  `form_id` varchar(100) DEFAULT NULL,
  `related_id` varchar(100) DEFAULT NULL,
  `customer_name` varchar(255) DEFAULT NULL,
  `short_name` varchar(50) DEFAULT NULL,
  `continent` varchar(50) DEFAULT NULL,
  `country` varchar(100) DEFAULT NULL,
  `website` varchar(150) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `summarize` text,
  `user_name` varchar(50) DEFAULT NULL,
  `dept_name` varchar(100) DEFAULT NULL,
  `status` varchar(1) DEFAULT NULL,
  `type` varchar(100) DEFAULT NULL,
  `user_bu` varchar(30) DEFAULT NULL,
  `version` tinyint(3) NOT NULL DEFAULT '0',
  `customer_type` varchar(10) DEFAULT NULL COMMENT '客户类型',
  `focus` text COMMENT '客户关注点',
  `project_star` varchar(10) DEFAULT NULL COMMENT '星级',
  `customer_source` varchar(10) DEFAULT NULL COMMENT '客户来源',
  `is_vaild` varchar(10) DEFAULT NULL COMMENT '是否有效',
  `change_explain` text COMMENT '变更说明',
  `create_user_id` varchar(50) DEFAULT NULL COMMENT '创建人账号',
  `create_dept_id` int(11) DEFAULT NULL COMMENT '创建人部门',
  PRIMARY KEY (`id`),
  UNIQUE KEY `id` (`id`) USING BTREE,
  KEY `idx_u_w` (`user_id`,`write_time`) USING BTREE,
  KEY `index_user` (`user_id`(5)) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=4870 DEFAULT CHARSET=gbk;

/*Table structure for table `inhe_customer_data_flow` */

DROP TABLE IF EXISTS `inhe_customer_data_flow`;

CREATE TABLE `inhe_customer_data_flow` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `form_id` varchar(100) DEFAULT NULL COMMENT '客户表流程单号',
  `customer_id` int(11) DEFAULT NULL COMMENT '客户表id',
  `flow_date` date DEFAULT NULL COMMENT '跟进日期',
  `create_time` datetime DEFAULT NULL COMMENT '创建时间',
  `remark` text COMMENT '备注',
  `user_id` varchar(50) DEFAULT NULL COMMENT '账号',
  `user_name` varchar(100) DEFAULT NULL COMMENT '姓名',
  `flow_type` varchar(255) DEFAULT NULL COMMENT '跟进方式',
  `ATTACH_NAME` text COMMENT '附件',
  `ATTACH_ID` text COMMENT '附件id',
  `contract_id` int(11) DEFAULT NULL COMMENT '关联联系人',
  `contract_name` varchar(255) DEFAULT NULL COMMENT '联系人名称',
  `order_num` int(11) DEFAULT NULL COMMENT '订单数量',
  `order_will` varchar(1) DEFAULT NULL COMMENT '是否有订单意愿 Y N',
  `need` varchar(255) DEFAULT NULL COMMENT '需求',
  `purma` varchar(255) DEFAULT NULL COMMENT '供应商',
  `dept_id` int(11) DEFAULT NULL COMMENT '部门id',
  `user_bu` varchar(255) DEFAULT NULL COMMENT '公司别名',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1257 DEFAULT CHARSET=gbk;

/*Table structure for table `inhe_customer_info` */

DROP TABLE IF EXISTS `inhe_customer_info`;

CREATE TABLE `inhe_customer_info` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `form_id` varchar(40) DEFAULT NULL,
  `customer_name` varchar(255) DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  `customer_id` varchar(255) DEFAULT NULL,
  `country` varchar(255) DEFAULT NULL,
  `contact` varchar(255) DEFAULT NULL,
  `phone` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `introduction` text,
  `type` varchar(255) DEFAULT NULL COMMENT '1代理商；2招标方；3买方',
  `user_bu` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `form_id` (`form_id`)
) ENGINE=InnoDB AUTO_INCREMENT=15472 DEFAULT CHARSET=gbk;

/*Table structure for table `inhe_customer_share` */

DROP TABLE IF EXISTS `inhe_customer_share`;

CREATE TABLE `inhe_customer_share` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `customer_id` int(11) DEFAULT NULL,
  `customer_name` varchar(255) DEFAULT NULL,
  `user_ids` text,
  `create_time` datetime DEFAULT NULL COMMENT '时间',
  `user_id` varchar(50) DEFAULT NULL COMMENT '创建人账号',
  `form_id` varchar(50) DEFAULT NULL COMMENT '分享流程表form_id',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=561 DEFAULT CHARSET=gbk;

/*Table structure for table `inhe_customer_share_main` */

DROP TABLE IF EXISTS `inhe_customer_share_main`;

CREATE TABLE `inhe_customer_share_main` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `organ_id` int(11) unsigned NOT NULL DEFAULT '1',
  `user_id` varchar(30) NOT NULL DEFAULT 'admin',
  `write_time` varchar(30) DEFAULT '0000-00-00 00:00:00',
  `update_time` datetime DEFAULT NULL,
  `title` varchar(255) DEFAULT NULL,
  `form_id` varchar(100) DEFAULT NULL,
  `related_id` varchar(100) DEFAULT NULL,
  `customer_name` varchar(255) DEFAULT NULL,
  `user_ids` text COMMENT '共享员工工号',
  `user_name` varchar(50) DEFAULT NULL,
  `dept_name` varchar(100) DEFAULT NULL,
  `status` varchar(1) DEFAULT NULL,
  `type` varchar(100) DEFAULT NULL,
  `user_bu` varchar(30) DEFAULT NULL,
  `version` tinyint(3) NOT NULL DEFAULT '0',
  `customer_id` int(11) DEFAULT NULL COMMENT '共享客户id',
  `remark` text COMMENT '备注',
  PRIMARY KEY (`id`),
  UNIQUE KEY `id` (`id`) USING BTREE,
  KEY `form_id` (`form_id`)
) ENGINE=InnoDB AUTO_INCREMENT=190 DEFAULT CHARSET=gbk;

/*Table structure for table `inhe_customer_share_user` */

DROP TABLE IF EXISTS `inhe_customer_share_user`;

CREATE TABLE `inhe_customer_share_user` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `form_id` varchar(50) DEFAULT NULL COMMENT '分享记录form_id',
  `user_id` varchar(30) DEFAULT NULL COMMENT '用户user_id',
  `user_name` varchar(255) DEFAULT NULL COMMENT '用户名称',
  `dept_name` varchar(255) DEFAULT NULL COMMENT '部门名称',
  `customer_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=199 DEFAULT CHARSET=gbk;

/*Table structure for table `inhe_customer_transfer` */

DROP TABLE IF EXISTS `inhe_customer_transfer`;

CREATE TABLE `inhe_customer_transfer` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `organ_id` int(11) unsigned DEFAULT '1',
  `user_id` varchar(30) DEFAULT 'admin',
  `write_time` varchar(30) DEFAULT '0000-00-00 00:00:00',
  `update_time` datetime DEFAULT NULL,
  `title` varchar(255) DEFAULT NULL,
  `form_id` varchar(100) DEFAULT NULL,
  `related_id` varchar(100) DEFAULT NULL,
  `user_name` varchar(50) DEFAULT NULL,
  `dept_name` varchar(100) DEFAULT NULL,
  `status` varchar(1) DEFAULT NULL,
  `type` varchar(255) DEFAULT NULL,
  `user_bu` varchar(30) DEFAULT NULL,
  `version` tinyint(3) NOT NULL DEFAULT '0',
  `from_userid` varchar(30) DEFAULT NULL COMMENT '原员工',
  `to_userid` varchar(30) DEFAULT NULL COMMENT '新员工',
  `customer_ids` text,
  `remark` text,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=gbk;

/*Table structure for table `inhe_customer_transfer_detail` */

DROP TABLE IF EXISTS `inhe_customer_transfer_detail`;

CREATE TABLE `inhe_customer_transfer_detail` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `customer_id` int(11) DEFAULT NULL,
  `form_id` varchar(100) DEFAULT NULL,
  `transfer_type` varchar(10) DEFAULT NULL COMMENT '客户类型',
  `customer_name` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=30 DEFAULT CHARSET=gbk;

/*Table structure for table `inhe_detail_item` */

DROP TABLE IF EXISTS `inhe_detail_item`;

CREATE TABLE `inhe_detail_item` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `module_id` varchar(50) DEFAULT NULL,
  `module_name` varchar(255) DEFAULT NULL,
  `table_id` varchar(100) DEFAULT NULL,
  `table_name` varchar(255) DEFAULT NULL,
  `item_id` varchar(50) DEFAULT NULL,
  `item_name` varchar(255) DEFAULT NULL,
  `have_attach` varchar(255) DEFAULT NULL,
  `if_select` varchar(255) DEFAULT NULL,
  `sort_code` int(11) DEFAULT NULL,
  `colspan` varchar(2) DEFAULT NULL,
  `nowrap` varchar(10) DEFAULT NULL,
  `user_bu` varchar(255) DEFAULT NULL,
  `is_required` varchar(2) DEFAULT NULL COMMENT '是否必填项',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=79 DEFAULT CHARSET=gbk;

/*Table structure for table `inhe_flow_manage` */

DROP TABLE IF EXISTS `inhe_flow_manage`;

CREATE TABLE `inhe_flow_manage` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '自增主键',
  `step` int(11) DEFAULT NULL COMMENT '步骤',
  `total_step` int(5) DEFAULT NULL,
  `role_id` int(11) DEFAULT NULL,
  `role` varchar(50) DEFAULT NULL,
  `approver` varchar(765) DEFAULT NULL,
  `next_step` varchar(100) DEFAULT NULL,
  `operate` varchar(20) DEFAULT NULL,
  `other_condition` varchar(255) DEFAULT NULL,
  `star_select` varchar(255) DEFAULT NULL,
  `remind_content` text,
  `project_user_select` varchar(255) DEFAULT NULL,
  `notice_user` varchar(255) DEFAULT NULL,
  `notice_dept` varchar(255) DEFAULT NULL,
  `if_show` varchar(255) DEFAULT NULL,
  `type` varchar(100) DEFAULT NULL,
  `type_desc` varchar(255) DEFAULT NULL,
  `user_bu` varchar(50) DEFAULT NULL,
  `common_opinion` varchar(255) DEFAULT NULL COMMENT '生产通知单子单生成，1生成子单复制步骤，2子单下一步审核',
  `auto_approver` text COMMENT '自动选择领导审批',
  `into_condition` text COMMENT '转入条件',
  `cdinhe_approver` varchar(100) DEFAULT NULL COMMENT '成都审核人',
  PRIMARY KEY (`id`) USING BTREE,
  KEY `inhe_flow_manage_user_bu_IDX` (`user_bu`) USING BTREE,
  KEY `inhe_flow_manage_type_IDX` (`type`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=2812 DEFAULT CHARSET=gbk ROW_FORMAT=DYNAMIC;

/*Table structure for table `inhe_flow_opinion` */

DROP TABLE IF EXISTS `inhe_flow_opinion`;

CREATE TABLE `inhe_flow_opinion` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `type` varchar(255) DEFAULT NULL,
  `user_id` varchar(100) DEFAULT NULL,
  `write_time` varchar(100) DEFAULT NULL,
  `form_id` varchar(255) DEFAULT NULL,
  `title` varchar(255) DEFAULT NULL,
  `step` int(5) DEFAULT NULL,
  `work_flow` varchar(255) DEFAULT NULL,
  `approve_user` varchar(255) DEFAULT NULL,
  `approve_time` varchar(30) DEFAULT NULL,
  `status` varchar(255) DEFAULT NULL,
  `prev_task` varchar(255) DEFAULT NULL,
  `prev_writer` varchar(25) DEFAULT NULL,
  `prev_time` varchar(25) DEFAULT NULL,
  `opinion` text,
  `project_star` varchar(255) DEFAULT NULL,
  `have_manager` varchar(255) DEFAULT NULL,
  `extra` text,
  `if_agree` varchar(255) DEFAULT NULL,
  `signature` varchar(255) DEFAULT NULL,
  `user_bu` varchar(255) DEFAULT NULL,
  `common_opinion` varchar(255) DEFAULT NULL COMMENT '根据 flow_manage设置写入',
  `urgent` varchar(10) DEFAULT NULL COMMENT '是否紧急',
  PRIMARY KEY (`id`),
  KEY `form_id` (`form_id`,`user_id`) USING BTREE,
  KEY `approve_user` (`approve_user`)
) ENGINE=InnoDB AUTO_INCREMENT=506172 DEFAULT CHARSET=gbk;

/*Table structure for table `inhe_form_user_manage` */

DROP TABLE IF EXISTS `inhe_form_user_manage`;

CREATE TABLE `inhe_form_user_manage` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` text NOT NULL COMMENT '用户id(多个可英文逗号拼接)',
  `type` varchar(100) NOT NULL COMMENT '表单类型：以英文为标识(销售合同评审sales_contract_review；',
  `user_bu` varchar(255) DEFAULT NULL COMMENT '不可见公司别名，如电力：INHE，全部填ALL',
  `used` tinyint(4) DEFAULT '1' COMMENT '是否有效 1 有效 0无效，默认1',
  `user_name` varchar(100) DEFAULT NULL COMMENT '姓名',
  `remark` varchar(100) DEFAULT NULL COMMENT '备注',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=33 DEFAULT CHARSET=gbk;

/*Table structure for table `inhe_guarantee_letter` */

DROP TABLE IF EXISTS `inhe_guarantee_letter`;

CREATE TABLE `inhe_guarantee_letter` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `organ_id` int(11) unsigned NOT NULL DEFAULT '1',
  `user_id` varchar(20) NOT NULL DEFAULT 'admin',
  `create_time` datetime DEFAULT '0000-00-00 00:00:00',
  `update_time` datetime DEFAULT NULL,
  `title` varchar(255) DEFAULT NULL,
  `serial_number` int(11) DEFAULT NULL,
  `project_code` varchar(255) DEFAULT NULL,
  `country` varchar(255) DEFAULT NULL,
  `content` text,
  `authorized_person` varchar(255) DEFAULT NULL,
  `contract_party` varchar(255) DEFAULT NULL,
  `effective_date` date DEFAULT NULL,
  `end_date` date DEFAULT NULL,
  `sign_date` date DEFAULT NULL,
  `flow_no` varchar(255) DEFAULT NULL,
  `dept_id` varchar(255) DEFAULT NULL,
  `dept_name` varchar(255) DEFAULT NULL,
  `apply_user` varchar(255) DEFAULT NULL,
  `apply_name` varchar(255) DEFAULT NULL,
  `if_archive` varchar(255) DEFAULT NULL,
  `archive_mode` varchar(255) DEFAULT NULL,
  `attach_id` varchar(255) DEFAULT NULL,
  `attach_name` varchar(255) DEFAULT NULL,
  `manage_dept` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=gbk;

/*Table structure for table `inhe_guarantee_letter_nergy` */

DROP TABLE IF EXISTS `inhe_guarantee_letter_nergy`;

CREATE TABLE `inhe_guarantee_letter_nergy` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `organ_id` int(11) unsigned NOT NULL DEFAULT '1' COMMENT '创建人部门id',
  `user_id` varchar(20) NOT NULL DEFAULT 'admin' COMMENT '创建人账号',
  `create_time` datetime DEFAULT '0000-00-00 00:00:00' COMMENT '创建时间',
  `update_time` datetime DEFAULT NULL COMMENT '更新时间',
  `title` varchar(255) DEFAULT NULL COMMENT '标题',
  `serial_number` int(11) DEFAULT NULL COMMENT '\r\n序号',
  `project_code` varchar(255) DEFAULT NULL COMMENT '项目代号',
  `country` varchar(255) DEFAULT NULL COMMENT '国家',
  `content` text COMMENT '大洲',
  `authorized_person` varchar(255) DEFAULT NULL COMMENT '被授权人',
  `contract_party` varchar(255) DEFAULT NULL COMMENT '相对方',
  `effective_date` date DEFAULT NULL COMMENT '生效日期',
  `end_date` date DEFAULT NULL COMMENT '终止日期',
  `sign_date` date DEFAULT NULL COMMENT '签订日期',
  `flow_no` varchar(255) DEFAULT NULL COMMENT '审批流程流水号',
  `dept_id` varchar(255) DEFAULT NULL COMMENT '申请部门id',
  `dept_name` varchar(255) DEFAULT NULL COMMENT '申请部门',
  `apply_user` varchar(255) DEFAULT NULL COMMENT '申请账号',
  `apply_name` varchar(255) DEFAULT NULL COMMENT '申请人姓名',
  `if_archive` varchar(255) DEFAULT NULL COMMENT '是否存档',
  `archive_mode` varchar(255) DEFAULT NULL COMMENT '存档形式',
  `attach_id` varchar(255) DEFAULT NULL COMMENT '附件id',
  `attach_name` varchar(255) DEFAULT NULL COMMENT '附件名称',
  `manage_dept` varchar(255) DEFAULT NULL COMMENT '管理部门代号',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=gbk COMMENT='授权函登记表';

/*Table structure for table `inhe_lieu_receive` */

DROP TABLE IF EXISTS `inhe_lieu_receive`;

CREATE TABLE `inhe_lieu_receive` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `organ_id` int(11) DEFAULT NULL,
  `user_id` varchar(255) DEFAULT NULL,
  `write_time` datetime DEFAULT NULL,
  `update_time` datetime DEFAULT NULL,
  `title` varchar(255) DEFAULT NULL,
  `form_id` varchar(50) DEFAULT NULL,
  `related_id` varchar(50) DEFAULT NULL,
  `form_type` varchar(10) DEFAULT NULL,
  `remark` text,
  `other_explain` text,
  `status` varchar(1) DEFAULT NULL,
  `type` varchar(100) DEFAULT NULL,
  `user_bu` varchar(30) DEFAULT NULL,
  `version` tinyint(3) NOT NULL DEFAULT '0',
  `excipients_form_id` varchar(255) DEFAULT NULL COMMENT '辅料表单号',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=gbk COMMENT='辅料代收货表';

/*Table structure for table `inhe_lieureceive_detail` */

DROP TABLE IF EXISTS `inhe_lieureceive_detail`;

CREATE TABLE `inhe_lieureceive_detail` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `form_id` varchar(255) DEFAULT NULL,
  `non_lieu_serial_number` varchar(255) DEFAULT NULL,
  `non_lieu_name` varchar(255) DEFAULT NULL,
  `non_lieu_specification` varchar(255) DEFAULT NULL,
  `non_lieu_model` varchar(255) DEFAULT NULL,
  `non_lieu_number` varchar(255) DEFAULT NULL,
  `non_lieu_attachment` varchar(255) DEFAULT NULL,
  `non_lieu_excipients_id` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=gbk COMMENT='辅料代收货明细表';

/*Table structure for table `inhe_number_participant` */

DROP TABLE IF EXISTS `inhe_number_participant`;

CREATE TABLE `inhe_number_participant` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `organ_id` int(11) unsigned NOT NULL DEFAULT '1',
  `user_id` varchar(20) NOT NULL DEFAULT 'admin',
  `write_time` varchar(25) DEFAULT NULL,
  `update_time` datetime DEFAULT NULL,
  `title` varchar(255) DEFAULT NULL,
  `form_id` text,
  `related_id` varchar(30) DEFAULT NULL,
  `remark` tinytext,
  `dept_belong` text,
  `project_code` text,
  `project_number` text,
  `customer_name` text,
  `leader_id` varchar(50) DEFAULT NULL,
  `leader` text,
  `proportion` double DEFAULT NULL,
  `status` varchar(5) DEFAULT NULL,
  `type` varchar(100) DEFAULT NULL,
  `user_bu` varchar(20) DEFAULT NULL,
  `version` tinyint(3) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=988 DEFAULT CHARSET=gbk;

/*Table structure for table `inhe_number_participant_item` */

DROP TABLE IF EXISTS `inhe_number_participant_item`;

CREATE TABLE `inhe_number_participant_item` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `form_id` varchar(255) DEFAULT NULL,
  `serial_number` int(11) DEFAULT NULL,
  `user_id` varchar(50) DEFAULT NULL,
  `user_name` text,
  `proportion` double DEFAULT NULL,
  `user_bu` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1869 DEFAULT CHARSET=gbk;

/*Table structure for table `inhe_oa_paras` */

DROP TABLE IF EXISTS `inhe_oa_paras`;

CREATE TABLE `inhe_oa_paras` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '主键ID',
  `paras_group` varchar(100) DEFAULT NULL COMMENT '分组',
  `type` varchar(100) DEFAULT NULL COMMENT '参数类型',
  `type_desc` varchar(255) DEFAULT NULL COMMENT '参数类型中文描述',
  `bz` varchar(10) DEFAULT NULL COMMENT '流程步骤',
  `paras_desc` varchar(255) DEFAULT NULL COMMENT '参数名称',
  `paras_value` varchar(255) DEFAULT NULL,
  `sub_group` varchar(100) DEFAULT NULL COMMENT '子分组',
  `extra` varchar(255) DEFAULT NULL COMMENT '其他',
  `related_table` varchar(255) DEFAULT NULL,
  `url` text,
  `sort_code` int(11) DEFAULT NULL,
  `is_used` varchar(2) DEFAULT NULL COMMENT '是否使用',
  `user_bu` varchar(20) DEFAULT NULL,
  `en_paras_desc` varchar(255) DEFAULT NULL COMMENT '英文参数名称',
  PRIMARY KEY (`id`),
  KEY `type` (`type`),
  KEY `paras_value` (`paras_value`)
) ENGINE=InnoDB AUTO_INCREMENT=1183 DEFAULT CHARSET=gbk;

/*Table structure for table `inhe_outexcipients_detail` */

DROP TABLE IF EXISTS `inhe_outexcipients_detail`;

CREATE TABLE `inhe_outexcipients_detail` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `form_id` varchar(255) DEFAULT NULL,
  `non_excipients_serial_number` varchar(255) DEFAULT NULL,
  `non_excipients_name` varchar(255) DEFAULT NULL,
  `non_excipients_specification` varchar(255) DEFAULT NULL,
  `non_excipients_model` varchar(255) DEFAULT NULL,
  `non_excipients_number` varchar(255) DEFAULT NULL,
  `non_excipients_attachment` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=92 DEFAULT CHARSET=gbk COMMENT='辅料明细表';

/*Table structure for table `inhe_outsourcing_excipients` */

DROP TABLE IF EXISTS `inhe_outsourcing_excipients`;

CREATE TABLE `inhe_outsourcing_excipients` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `organ_id` int(11) DEFAULT NULL,
  `user_id` varchar(255) DEFAULT NULL,
  `write_time` datetime DEFAULT NULL,
  `update_time` datetime DEFAULT NULL,
  `title` varchar(255) DEFAULT NULL,
  `form_id` varchar(50) DEFAULT NULL,
  `related_id` varchar(50) DEFAULT NULL,
  `form_type` varchar(10) DEFAULT NULL,
  `remark` text,
  `other_explain` text,
  `status` varchar(1) DEFAULT NULL,
  `type` varchar(100) DEFAULT NULL,
  `user_bu` varchar(30) DEFAULT NULL,
  `version` tinyint(3) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=gbk COMMENT='辅料表';

/*Table structure for table `inhe_product_detail` */

DROP TABLE IF EXISTS `inhe_product_detail`;

CREATE TABLE `inhe_product_detail` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) DEFAULT NULL,
  `form_id` varchar(255) DEFAULT NULL,
  `serial_number` int(11) DEFAULT NULL,
  `name` text,
  `specification` text,
  `unit` varchar(255) DEFAULT NULL,
  `number` int(11) DEFAULT NULL,
  `price` decimal(30,4) DEFAULT NULL,
  `total_amount` decimal(30,4) DEFAULT NULL,
  `tech_requirement` text,
  `brand` varchar(255) DEFAULT NULL,
  `type` varchar(255) DEFAULT NULL,
  `user_bu` varchar(255) DEFAULT NULL,
  `license_product` varchar(10) DEFAULT NULL COMMENT '授权产品名称',
  `license_years` varchar(10) DEFAULT NULL COMMENT '授权产品年限',
  `license_type` varchar(10) DEFAULT NULL COMMENT '授权产品方式',
  `other_company_product` varchar(100) DEFAULT NULL COMMENT '其它公司产品',
  PRIMARY KEY (`id`),
  KEY `form_id` (`form_id`)
) ENGINE=InnoDB AUTO_INCREMENT=40727 DEFAULT CHARSET=gbk;

/*Table structure for table `inhe_production_detail_non` */

DROP TABLE IF EXISTS `inhe_production_detail_non`;

CREATE TABLE `inhe_production_detail_non` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `form_id` varchar(255) DEFAULT NULL,
  `non_serial_number` varchar(255) DEFAULT NULL,
  `non_product_name` varchar(255) DEFAULT NULL,
  `non_order_number` varchar(255) DEFAULT NULL,
  `specification_model` text,
  `unit` varchar(255) DEFAULT NULL,
  `non_attachment` varchar(255) DEFAULT NULL,
  `non_play_tray` varchar(255) DEFAULT NULL,
  `non_type` varchar(255) DEFAULT NULL,
  `sub_process` varchar(255) DEFAULT NULL,
  `license_product` varchar(10) DEFAULT NULL COMMENT '授权产品名称',
  `license_years` varchar(10) DEFAULT NULL COMMENT '授权产品年限',
  `license_type` varchar(10) DEFAULT NULL COMMENT '授权产品方式',
  `license_product_code` varchar(255) DEFAULT NULL COMMENT '品号',
  `license_product_name` varchar(255) DEFAULT NULL COMMENT '品名',
  `other_company` varchar(100) DEFAULT NULL COMMENT '涉及其他公司产品',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=10277 DEFAULT CHARSET=gbk;

/*Table structure for table `inhe_production_detail_one` */

DROP TABLE IF EXISTS `inhe_production_detail_one`;

CREATE TABLE `inhe_production_detail_one` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `form_id` varchar(255) DEFAULT NULL,
  `serial_number` varchar(255) DEFAULT NULL,
  `product_name` varchar(255) DEFAULT NULL,
  `internal_model` varchar(255) DEFAULT NULL,
  `order_number` varchar(255) DEFAULT NULL,
  `voltage` varchar(255) DEFAULT NULL,
  `current` varchar(255) DEFAULT NULL,
  `frequency` varchar(255) DEFAULT NULL,
  `active_precision` varchar(255) DEFAULT NULL,
  `reactive_power_accuracy` varchar(255) DEFAULT NULL,
  `active_pulse_constant` varchar(255) DEFAULT NULL,
  `reactive_pulse_constant` varchar(255) DEFAULT NULL,
  `display_mode` varchar(255) DEFAULT NULL,
  `display_digit` varchar(255) DEFAULT NULL,
  `meter_type` varchar(255) DEFAULT NULL,
  `product_brand` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6018 DEFAULT CHARSET=gbk;

/*Table structure for table `inhe_production_detail_three` */

DROP TABLE IF EXISTS `inhe_production_detail_three`;

CREATE TABLE `inhe_production_detail_three` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `form_id` varchar(255) DEFAULT NULL,
  `serial_number` varchar(255) DEFAULT NULL,
  `product_name` varchar(255) DEFAULT NULL,
  `product_specification` varchar(255) DEFAULT NULL,
  `operation_manual` varchar(255) DEFAULT NULL,
  `carton_design_document` varchar(255) DEFAULT NULL,
  `inner_box_design_document` varchar(255) DEFAULT NULL,
  `accessories_requirement` varchar(255) DEFAULT NULL,
  `test_report` varchar(255) DEFAULT NULL,
  `play_tray` varchar(255) DEFAULT NULL,
  `wooden_cases_packed` varchar(255) DEFAULT NULL,
  `related_attachment` varchar(255) DEFAULT NULL,
  `other_instruction` varchar(255) DEFAULT NULL,
  `attachment` varchar(255) DEFAULT NULL,
  `product_brand` varchar(255) DEFAULT NULL,
  `uhas_module` varchar(100) DEFAULT NULL COMMENT '是否带模块',
  `module_shipping` varchar(100) DEFAULT NULL COMMENT '带模块出货方式',
  `other_company` varchar(100) DEFAULT NULL COMMENT '涉及其他公司配件',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6018 DEFAULT CHARSET=gbk;

/*Table structure for table `inhe_production_detail_two` */

DROP TABLE IF EXISTS `inhe_production_detail_two`;

CREATE TABLE `inhe_production_detail_two` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `form_id` varchar(255) DEFAULT NULL,
  `serial_number` varchar(255) DEFAULT NULL,
  `product_name` varchar(255) DEFAULT NULL,
  `connection_mode` varchar(255) DEFAULT NULL,
  `production_mode` varchar(255) DEFAULT NULL,
  `time_zone` varchar(255) DEFAULT NULL,
  `dst_option` varchar(255) DEFAULT NULL,
  `meter_no_range` varchar(255) DEFAULT NULL,
  `enclosure_requirement` varchar(255) DEFAULT NULL,
  `logo` varchar(255) DEFAULT NULL,
  `panel` varchar(255) DEFAULT NULL,
  `power_plug_model` varchar(255) DEFAULT NULL,
  `card_matching_text` varchar(255) DEFAULT NULL,
  `factory_settings_file` varchar(255) DEFAULT NULL,
  `communication_module` varchar(255) DEFAULT NULL,
  `product_brand` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6018 DEFAULT CHARSET=gbk;

/*Table structure for table `inhe_production_order` */

DROP TABLE IF EXISTS `inhe_production_order`;

CREATE TABLE `inhe_production_order` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `organ_id` int(11) DEFAULT NULL,
  `user_id` varchar(255) DEFAULT NULL,
  `write_time` datetime DEFAULT NULL,
  `update_time` datetime DEFAULT NULL,
  `title` varchar(255) DEFAULT NULL,
  `form_id` varchar(50) DEFAULT NULL,
  `related_id` varchar(50) DEFAULT NULL,
  `form_type` varchar(10) DEFAULT NULL,
  `remark` text,
  `customer_name` varchar(255) DEFAULT NULL,
  `project_code` varchar(255) DEFAULT NULL,
  `project_num` varchar(255) DEFAULT NULL,
  `order_num` varchar(255) DEFAULT NULL,
  `contract_party` varchar(255) DEFAULT NULL,
  `delivery_time` date DEFAULT NULL,
  `apply_dept` varchar(255) DEFAULT NULL,
  `apply_user` varchar(255) DEFAULT NULL,
  `third_inspection` varchar(255) DEFAULT NULL,
  `inspect_type` varchar(255) DEFAULT NULL,
  `project_star` varchar(255) DEFAULT NULL,
  `contract_belong` varchar(255) DEFAULT NULL,
  `other_explain` text,
  `spare_requirement` text,
  `meter_number` int(11) DEFAULT NULL,
  `package_in_sequence` varchar(255) DEFAULT NULL,
  `status` varchar(1) DEFAULT NULL,
  `type` varchar(100) DEFAULT NULL,
  `user_bu` varchar(30) DEFAULT NULL,
  `version` tinyint(3) NOT NULL DEFAULT '0',
  `sub_user_bu` varchar(10) DEFAULT NULL COMMENT '电表类产品抛转公司 默认JXINHE 总部INHE',
  `is_collective` varchar(10) DEFAULT NULL COMMENT '是否为集抄项目',
  `past_pcode` varchar(255) DEFAULT NULL COMMENT '本市场过往项目代号',
  `c_past_pcode` varchar(255) DEFAULT NULL COMMENT '本市场不同客户曾经出现过的项目代号',
  `is_sgc` varchar(10) DEFAULT NULL COMMENT '是否有SGC码',
  `sgc` text COMMENT '具体SGC码',
  `acceptance_date` date DEFAULT NULL COMMENT '验收日期',
  `server_end_date` date DEFAULT NULL,
  `order_form_id` varchar(100) DEFAULT NULL COMMENT '合同评审form_id',
  `from_id` varchar(100) DEFAULT NULL COMMENT '原单据编号',
  `create_user_bu` varchar(100) DEFAULT NULL COMMENT '创建人公司',
  `urgent` varchar(10) DEFAULT NULL COMMENT '是否紧急',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3671 DEFAULT CHARSET=gbk;

/*Table structure for table `inhe_production_order_pn` */

DROP TABLE IF EXISTS `inhe_production_order_pn`;

CREATE TABLE `inhe_production_order_pn` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `form_id` varchar(100) DEFAULT NULL COMMENT '生产通知单form_id',
  `license_product_code` varchar(100) DEFAULT NULL COMMENT '品号',
  `license_product_name` varchar(100) DEFAULT NULL COMMENT '品名',
  `remark` text COMMENT '备注',
  `create_time` datetime DEFAULT NULL,
  `user_id` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=130 DEFAULT CHARSET=gbk COMMENT='生产通知单品号';

/*Table structure for table `inhe_project_detail` */

DROP TABLE IF EXISTS `inhe_project_detail`;

CREATE TABLE `inhe_project_detail` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `form_id` varchar(255) DEFAULT NULL,
  `serial_number` int(5) DEFAULT NULL,
  `product` varchar(255) DEFAULT NULL,
  `price` varchar(255) DEFAULT NULL,
  `first_price` varchar(255) DEFAULT NULL,
  `standard` varchar(255) DEFAULT NULL,
  `number` int(11) DEFAULT NULL,
  `attach_name` varchar(255) DEFAULT NULL,
  `attach_type` varchar(255) DEFAULT NULL,
  `supplier` varchar(255) DEFAULT NULL COMMENT '现有供应商',
  `type` varchar(255) DEFAULT NULL COMMENT 'other 其他 inhenergy 耐吉 inhegrid 电网 soft 慧软 electric 电表',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=22321 DEFAULT CHARSET=gbk;

/*Table structure for table `inhe_project_info` */

DROP TABLE IF EXISTS `inhe_project_info`;

CREATE TABLE `inhe_project_info` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `form_id` varchar(255) DEFAULT NULL,
  `project_code` varchar(255) DEFAULT NULL,
  `country` varchar(255) DEFAULT NULL,
  `scope` varchar(255) DEFAULT NULL,
  `project_type` varchar(255) DEFAULT NULL,
  `prototype` text COMMENT '样机需求',
  `potential` text COMMENT '项目潜力',
  `background` text,
  `explain_user` text,
  `explain_project` text,
  `service` text,
  `temp_project_code` varchar(255) DEFAULT NULL COMMENT '临时项目代号',
  `user_bu` varchar(255) DEFAULT NULL,
  `cooperate_history` text COMMENT '合作历史',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3278 DEFAULT CHARSET=gbk;

/*Table structure for table `inhe_project_info_witlink` */

DROP TABLE IF EXISTS `inhe_project_info_witlink`;

CREATE TABLE `inhe_project_info_witlink` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `form_id` varchar(255) NOT NULL,
  `customer_type` varchar(255) DEFAULT '' COMMENT '客户类型',
  `num_users` varchar(255) DEFAULT '' COMMENT '用户数量',
  `users_five_years_later` varchar(255) DEFAULT '' COMMENT '5年后用户总数',
  `replacement_reason` text COMMENT '更换系统原因',
  `old_system_situation` text COMMENT '旧系统价格情况',
  `purchase_method` varchar(255) DEFAULT '' COMMENT '购买方式',
  `customer_it_leader` varchar(255) DEFAULT '' COMMENT '客户it负责人',
  `leader_phone` varchar(255) DEFAULT '' COMMENT '客户it负责人电话',
  `leader_email` varchar(255) DEFAULT '' COMMENT '客户it负责人邮箱',
  `supply_server_database` varchar(255) DEFAULT '' COMMENT '是否包括服务器、数据库供货',
  `database_type` varchar(255) DEFAULT '' COMMENT '数据库类型',
  `database_config` varchar(255) DEFAULT '' COMMENT '数据库配置',
  `database_num` varchar(255) DEFAULT '' COMMENT '数据库数量',
  `server_model` varchar(255) DEFAULT '' COMMENT '服务器型号',
  `server_config` varchar(255) DEFAULT '' COMMENT '服务器配置',
  `server_num` varchar(255) DEFAULT '' COMMENT '服务器数量',
  `central_type` varchar(255) DEFAULT '' COMMENT '中央式',
  `distributed` varchar(255) DEFAULT '' COMMENT '分布式',
  `cloud_deployment` varchar(255) DEFAULT '' COMMENT '云部署',
  `os_language` text COMMENT 'OS语言',
  `data_migration` text COMMENT '数据迁移',
  `compatible_equipment` text COMMENT '兼容第三方计量设备',
  `compatible_module` text COMMENT '兼容第三方安全模块',
  `integrated_software` text COMMENT '集成第三方软件',
  `custom_api` text COMMENT '定制化第三方接口文档',
  `other_requirements` text COMMENT '其他特殊技术要求',
  `demand_change` text COMMENT '后期需求变更约束',
  `maintain_obligation` varchar(255) DEFAULT '' COMMENT '维护义务',
  `maintain_mode` varchar(255) DEFAULT '' COMMENT '维护方式',
  `maintain_years` varchar(255) DEFAULT '' COMMENT '维护年限',
  `service_level` varchar(255) DEFAULT '' COMMENT '服务等级',
  `implement_scene` varchar(255) DEFAULT NULL COMMENT '现场实施',
  `implement_long` varchar(255) DEFAULT NULL COMMENT '远程实施',
  `implement_scene_duration` varchar(255) DEFAULT NULL COMMENT '现场实施时长',
  `implement_agent` varchar(255) DEFAULT NULL COMMENT '代理实施',
  `implement_scene_person` varchar(255) DEFAULT NULL COMMENT '现场实施人员',
  `implement_long_duration` varchar(255) DEFAULT NULL COMMENT '远程实施时长',
  `implement_long_person` varchar(255) DEFAULT NULL COMMENT '远程实施人员',
  `implement_agent_duration` varchar(255) DEFAULT NULL COMMENT '代理实施时长',
  `implement_agent_person` varchar(255) DEFAULT NULL COMMENT '代理实施人员',
  `cooperate_history` text COMMENT '合作历史',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=gbk;

/*Table structure for table `inhe_project_main` */

DROP TABLE IF EXISTS `inhe_project_main`;

CREATE TABLE `inhe_project_main` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `organ_id` int(11) DEFAULT NULL,
  `user_id` varchar(255) DEFAULT NULL,
  `write_time` datetime DEFAULT NULL,
  `update_time` datetime DEFAULT NULL,
  `title` varchar(255) DEFAULT NULL,
  `form_id` varchar(255) DEFAULT NULL,
  `related_id` varchar(50) DEFAULT NULL,
  `project_code` varchar(255) DEFAULT NULL,
  `if_project` varchar(255) DEFAULT NULL,
  `project_star` varchar(255) DEFAULT NULL,
  `star_icon` varchar(255) DEFAULT NULL,
  `remark` text,
  `attach_name` varchar(255) DEFAULT NULL,
  `dept_name` varchar(255) DEFAULT NULL,
  `user_name` varchar(255) DEFAULT NULL,
  `create_date` date DEFAULT NULL,
  `flow_type` varchar(500) DEFAULT NULL,
  `status` varchar(255) DEFAULT NULL,
  `type` varchar(100) DEFAULT NULL,
  `user_bu` varchar(255) DEFAULT NULL,
  `version` tinyint(3) NOT NULL DEFAULT '0',
  `is_bidding_project` varchar(255) DEFAULT NULL COMMENT '耐吉使用，是否招标项目：01是  02 否',
  `change_star` varchar(255) DEFAULT '0',
  `temp_project_code` varchar(255) DEFAULT NULL COMMENT '临时项目代号',
  `is_collective` varchar(10) DEFAULT NULL COMMENT '是否为集抄项目',
  `past_pcode` varchar(255) DEFAULT NULL COMMENT '本市场过往项目代号',
  `c_past_pcode` varchar(255) DEFAULT NULL COMMENT '本市场不同客户曾经出现过的项目代号',
  `from_id` varchar(100) DEFAULT NULL COMMENT '来自抛转表单ID',
  `create_user_bu` varchar(100) DEFAULT NULL COMMENT '创建人公司',
  PRIMARY KEY (`id`),
  KEY `project_code` (`project_code`),
  KEY `form_id` (`form_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2734 DEFAULT CHARSET=gbk;

/*Table structure for table `inhe_project_participant` */

DROP TABLE IF EXISTS `inhe_project_participant`;

CREATE TABLE `inhe_project_participant` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `organ_id` int(11) DEFAULT NULL,
  `user_id` varchar(255) DEFAULT NULL,
  `write_time` datetime DEFAULT NULL,
  `update_time` datetime DEFAULT NULL,
  `title` varchar(255) DEFAULT NULL,
  `form_id` varchar(30) DEFAULT NULL,
  `related_id` varchar(30) DEFAULT NULL,
  `dept_belong` varchar(255) DEFAULT NULL,
  `project_code` varchar(255) DEFAULT NULL,
  `customer_name` varchar(255) DEFAULT NULL,
  `leader_id` varchar(30) DEFAULT NULL,
  `leader` varchar(255) DEFAULT NULL,
  `proportion` varchar(10) DEFAULT NULL,
  `remark` text,
  `status` varchar(1) DEFAULT NULL,
  `type` varchar(100) DEFAULT NULL,
  `user_bu` varchar(255) DEFAULT NULL,
  `version` tinyint(3) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `form_id` (`form_id`),
  KEY `project_code` (`project_code`)
) ENGINE=InnoDB AUTO_INCREMENT=1494 DEFAULT CHARSET=gbk;

/*Table structure for table `inhe_project_participant_item` */

DROP TABLE IF EXISTS `inhe_project_participant_item`;

CREATE TABLE `inhe_project_participant_item` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `form_id` varchar(30) DEFAULT NULL,
  `title` varchar(255) DEFAULT NULL,
  `serial_number` int(11) DEFAULT NULL,
  `user_id` varchar(30) DEFAULT NULL,
  `user_name` varchar(255) DEFAULT NULL,
  `proportion` varchar(255) DEFAULT NULL,
  `user_bu` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `form_id` (`form_id`)
) ENGINE=InnoDB AUTO_INCREMENT=1615 DEFAULT CHARSET=gbk;

/*Table structure for table `inhe_project_region` */

DROP TABLE IF EXISTS `inhe_project_region`;

CREATE TABLE `inhe_project_region` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) DEFAULT NULL COMMENT '区域名称',
  `business_user` varchar(255) DEFAULT NULL COMMENT '业务负责人',
  `delivery_user` varchar(255) DEFAULT NULL COMMENT '交付经理',
  `user_name` varchar(100) DEFAULT NULL COMMENT '创建人员',
  `user_id` varchar(100) DEFAULT NULL COMMENT '创建人账号',
  `create_time` varchar(100) DEFAULT NULL,
  `user_bu` varchar(100) DEFAULT NULL,
  `type` varchar(100) DEFAULT NULL,
  `status` varchar(100) DEFAULT NULL,
  `delivery_user_name` text,
  `business_user_name` text,
  `form_id` varchar(100) DEFAULT NULL,
  `title` varchar(100) DEFAULT NULL,
  `dept_id` int(11) DEFAULT NULL,
  `business_dept_id` int(11) DEFAULT NULL COMMENT '业务部门',
  `business_dept_name` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=gbk COMMENT='项目区域管理表';

/*Table structure for table `inhe_project_schedule` */

DROP TABLE IF EXISTS `inhe_project_schedule`;

CREATE TABLE `inhe_project_schedule` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `create_user_id` varchar(40) DEFAULT NULL COMMENT '创建人账号',
  `create_time` datetime DEFAULT NULL COMMENT '创建时间',
  `project_code` varchar(100) DEFAULT NULL COMMENT '项目代号',
  `progress_nodes` varchar(100) DEFAULT NULL COMMENT '进度节点',
  `region` varchar(100) DEFAULT NULL COMMENT '区域',
  `dept_id` int(11) DEFAULT NULL COMMENT '部门id',
  `user_bu` varchar(100) DEFAULT NULL COMMENT '公司',
  `form_id` varchar(100) DEFAULT NULL COMMENT '单号',
  `title` varchar(100) DEFAULT NULL COMMENT '标题',
  `nodes_desc` text COMMENT '节点内容描述',
  `attachment_id` text COMMENT '附件id',
  `attachment_name` text COMMENT '附件名称',
  `user_name` varchar(100) DEFAULT NULL COMMENT '创建人姓名',
  `status` varchar(100) DEFAULT NULL COMMENT '状态',
  `type` varchar(100) DEFAULT NULL COMMENT '表单类型',
  `region_id` int(11) DEFAULT NULL COMMENT '区域id',
  `project_number` text COMMENT '项目编号',
  `update_time` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=203 DEFAULT CHARSET=gbk COMMENT='项目进度登记表';

/*Table structure for table `inhe_project_schedule_detail` */

DROP TABLE IF EXISTS `inhe_project_schedule_detail`;

CREATE TABLE `inhe_project_schedule_detail` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `schedule_id` int(11) DEFAULT NULL,
  `form_id` varchar(100) DEFAULT NULL,
  `project_number` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=99 DEFAULT CHARSET=gbk COMMENT='项目进度编号明细';

/*Table structure for table `inhe_project_team` */

DROP TABLE IF EXISTS `inhe_project_team`;

CREATE TABLE `inhe_project_team` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '自增编号',
  `user_bu` varchar(20) DEFAULT NULL COMMENT '公司别名',
  `dept_id` int(11) DEFAULT NULL COMMENT '部门id',
  `team_name` varchar(255) DEFAULT NULL COMMENT '项目组',
  `form_id` varchar(255) DEFAULT NULL COMMENT '编号',
  `business_owner` varchar(255) DEFAULT NULL COMMENT '业务负责人',
  `commerce_owner` varchar(255) DEFAULT NULL COMMENT '商务负责人',
  `technical_support_owner` varchar(255) DEFAULT NULL COMMENT '技术支持负责人',
  `business` varchar(255) DEFAULT NULL COMMENT '业务团队',
  `commerce` varchar(255) DEFAULT NULL COMMENT '商务团队',
  `technical_support` varchar(255) DEFAULT NULL COMMENT '技术支持团队',
  `region` text COMMENT '负责区域',
  `update_time` datetime DEFAULT NULL COMMENT '修改时间',
  `update_user_id` varchar(40) DEFAULT NULL COMMENT '修改人账号',
  `business_name` text COMMENT '业务团队人名',
  `commerce_name` text COMMENT '商务团队人名',
  `technical_support_name` text COMMENT '技术支持团队人名',
  `business_owner_name` varchar(40) DEFAULT NULL COMMENT '业务负责人姓名',
  `commerce_owner_name` varchar(40) DEFAULT NULL COMMENT '商务负责人姓名',
  `technical_support_owner_name` varchar(40) DEFAULT NULL COMMENT '技术支持负责人姓名',
  `type` varchar(10) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=gbk COMMENT='项目组名单';

/*Table structure for table `inhe_sales_contract` */

DROP TABLE IF EXISTS `inhe_sales_contract`;

CREATE TABLE `inhe_sales_contract` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `organ_id` int(11) DEFAULT NULL,
  `user_id` varchar(255) DEFAULT NULL,
  `create_time` datetime DEFAULT NULL,
  `update_time` datetime DEFAULT NULL,
  `title` varchar(255) DEFAULT NULL,
  `serial_number` int(11) DEFAULT NULL,
  `sign_date` date DEFAULT NULL,
  `contract_category` varchar(255) DEFAULT NULL,
  `project_name` varchar(255) DEFAULT NULL,
  `project_no` varchar(100) DEFAULT NULL,
  `contract_no` varchar(100) DEFAULT NULL,
  `currency` varchar(255) DEFAULT NULL,
  `amount` varchar(255) DEFAULT NULL,
  `remark` text,
  `foreign_attach` varchar(255) DEFAULT NULL,
  `chinese_attach` varchar(255) DEFAULT NULL,
  `customer_name` varchar(255) DEFAULT NULL,
  `contract_party` varchar(255) DEFAULT NULL,
  `business_belong` varchar(255) DEFAULT NULL,
  `create_user` varchar(255) DEFAULT NULL,
  `create_user_name` varchar(255) DEFAULT NULL,
  `dept_id` int(11) DEFAULT NULL,
  `dept_name` varchar(255) DEFAULT NULL,
  `manage_dept` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1468 DEFAULT CHARSET=gbk;

/*Table structure for table `inhe_sales_receipt` */

DROP TABLE IF EXISTS `inhe_sales_receipt`;

CREATE TABLE `inhe_sales_receipt` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `organ_id` int(11) unsigned NOT NULL DEFAULT '1',
  `user_id` varchar(20) NOT NULL DEFAULT 'admin',
  `write_time` varchar(25) DEFAULT '0000-00-00 00:00:00',
  `update_time` datetime DEFAULT NULL,
  `title` varchar(255) DEFAULT NULL,
  `form_id` text,
  `payer_name` text,
  `receipt_bank` varchar(255) DEFAULT NULL COMMENT '到款银行',
  `original_amount` decimal(20,2) DEFAULT NULL,
  `currency` text,
  `rmb_amount` decimal(20,2) DEFAULT NULL,
  `payment_term` text,
  `receipt_date` date DEFAULT NULL,
  `business_dept` text,
  `project_number` varchar(500) DEFAULT NULL,
  `business_remark` varchar(500) DEFAULT NULL,
  `business_signature` varchar(500) DEFAULT NULL,
  `status` varchar(1) DEFAULT NULL,
  `type` varchar(150) DEFAULT NULL,
  `user_bu` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6688 DEFAULT CHARSET=gbk;

/*Table structure for table `inhe_sales_receipt_detail` */

DROP TABLE IF EXISTS `inhe_sales_receipt_detail`;

CREATE TABLE `inhe_sales_receipt_detail` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `form_id` varchar(100) NOT NULL,
  `serial_number` int(11) DEFAULT NULL,
  `project_code` text,
  `project_number` text,
  `order_number` text,
  `customer_name` text,
  `contract_amount` decimal(20,2) DEFAULT NULL,
  `currency` text,
  `rmb_amount` decimal(20,2) DEFAULT NULL,
  `receipt_amount` decimal(20,2) DEFAULT NULL,
  `other_cost` decimal(20,2) DEFAULT NULL,
  `receipted` decimal(20,2) DEFAULT NULL,
  `un_receipt` decimal(20,2) DEFAULT NULL,
  `remark` text,
  `contract_party` text,
  `if_third_pay` varchar(500) DEFAULT NULL,
  `user_bu` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7124 DEFAULT CHARSET=gbk;

/*Table structure for table `inhe_shipping_detail` */

DROP TABLE IF EXISTS `inhe_shipping_detail`;

CREATE TABLE `inhe_shipping_detail` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `form_id` varchar(50) DEFAULT NULL,
  `serial_number` int(5) DEFAULT NULL,
  `product_name` varchar(255) DEFAULT NULL,
  `internal_model` varchar(255) DEFAULT NULL,
  `number` int(11) DEFAULT NULL,
  `price` decimal(16,4) DEFAULT NULL,
  `total_amount` decimal(16,4) DEFAULT NULL,
  `project_number` varchar(255) DEFAULT NULL,
  `project_code` varchar(255) DEFAULT NULL,
  `order_number` varchar(255) DEFAULT NULL,
  `type` varchar(255) DEFAULT NULL,
  `user_bu` varchar(255) DEFAULT NULL,
  `license_product` varchar(100) DEFAULT NULL COMMENT '授权产品',
  `license_years` varchar(100) DEFAULT NULL COMMENT '授权年限',
  `license_type` varchar(100) DEFAULT NULL COMMENT '授权类型',
  `license_key` varchar(255) DEFAULT NULL COMMENT '授权KEY',
  `license_term` varchar(100) DEFAULT NULL COMMENT '授权期限\r\n填数字  单位：月（备注: 永久请填999）',
  `license_key_date` date DEFAULT NULL COMMENT '期望授权码生效日期',
  `license_version` varchar(100) DEFAULT NULL COMMENT '授权产品系统版本',
  `license_effect_time` datetime DEFAULT NULL COMMENT '授权生效日期',
  `license_times` varchar(100) DEFAULT NULL COMMENT '授权次数',
  `license_days` varchar(100) DEFAULT NULL COMMENT '授权天数 单位 天',
  `license_code` varchar(100) DEFAULT NULL COMMENT '授权码',
  `license_expire_time` datetime DEFAULT NULL COMMENT '授权到期时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=23418 DEFAULT CHARSET=gbk;

/*Table structure for table `inhe_shipping_notice` */

DROP TABLE IF EXISTS `inhe_shipping_notice`;

CREATE TABLE `inhe_shipping_notice` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `organ_id` int(11) DEFAULT NULL,
  `user_id` varchar(255) DEFAULT NULL,
  `write_time` datetime DEFAULT NULL,
  `update_time` datetime DEFAULT NULL,
  `title` varchar(255) DEFAULT NULL,
  `form_id` varchar(50) DEFAULT NULL,
  `related_id` varchar(50) DEFAULT NULL,
  `customer_name` varchar(255) DEFAULT NULL,
  `contract_party` varchar(255) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `shipment_place` varchar(255) DEFAULT NULL,
  `order_no` varchar(50) DEFAULT NULL,
  `erp_no` varchar(50) DEFAULT NULL,
  `currency` varchar(255) DEFAULT NULL,
  `consignee` varchar(255) DEFAULT NULL,
  `phone` varchar(255) DEFAULT NULL,
  `shipment_time` date DEFAULT NULL,
  `transport_mode` varchar(255) DEFAULT NULL,
  `freight_bearing` varchar(255) DEFAULT NULL,
  `business_belong` varchar(255) DEFAULT NULL,
  `business_dept` varchar(255) DEFAULT NULL,
  `have_entrepot_trade` varchar(255) DEFAULT NULL,
  `involved_product` varchar(255) DEFAULT NULL,
  `notice_type` varchar(10) DEFAULT NULL,
  `remark` text,
  `signature` varchar(255) DEFAULT NULL,
  `status` varchar(1) DEFAULT NULL,
  `type` varchar(100) DEFAULT NULL,
  `user_bu` varchar(255) DEFAULT NULL,
  `project_number` varchar(255) DEFAULT NULL,
  `order_form_id` varchar(100) DEFAULT NULL COMMENT '合同评审from_id',
  `customer_service` varchar(255) DEFAULT NULL COMMENT '产品经理(客服)',
  `customer_service_name` varchar(255) DEFAULT NULL COMMENT '产品经理客服名称',
  `import_permit` varchar(100) DEFAULT NULL COMMENT '客户是否已申请好进口国进口许可',
  `batch_number` varchar(100) DEFAULT NULL COMMENT '项目编号发货批次',
  `create_user_bu` varchar(100) DEFAULT NULL COMMENT '创建人公司',
  `from_id` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3426 DEFAULT CHARSET=gbk;

/*Table structure for table `inhe_temp_code` */

DROP TABLE IF EXISTS `inhe_temp_code`;

CREATE TABLE `inhe_temp_code` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `temp_code` varchar(20) DEFAULT NULL,
  `project_name` varchar(255) DEFAULT NULL,
  `location` varchar(100) DEFAULT NULL,
  `scope_code` varchar(1) DEFAULT NULL,
  `scope` varchar(20) DEFAULT NULL,
  `formal_code` varchar(20) DEFAULT NULL,
  `remark` text,
  `dept_id` int(11) DEFAULT NULL,
  `dept_name` varchar(100) DEFAULT NULL,
  `user_id` varchar(30) DEFAULT NULL,
  `user_name` varchar(100) DEFAULT NULL,
  `create_time` datetime DEFAULT NULL,
  `company` varchar(20) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1402 DEFAULT CHARSET=gbk;

/*Table structure for table `user` */

DROP TABLE IF EXISTS `user`;

CREATE TABLE `user` (
  `UID` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Ψһ����ID',
  `USER_ID` varchar(40) NOT NULL COMMENT '�û���',
  `USER_NAME` varchar(60) NOT NULL DEFAULT '' COMMENT '�û���ʵ����',
  `USER_NAME_INDEX` varchar(60) NOT NULL DEFAULT '' COMMENT '�û���������',
  `BYNAME` varchar(20) NOT NULL DEFAULT '' COMMENT '����',
  `USEING_KEY` char(1) NOT NULL DEFAULT '0' COMMENT 'ʹ��USB KEY��¼',
  `USING_FINGER` char(1) NOT NULL DEFAULT '0' COMMENT 'ʹ��ָ����֤',
  `PASSWORD` varchar(50) NOT NULL DEFAULT '' COMMENT '�û�����',
  `KEY_SN` varchar(20) NOT NULL DEFAULT '' COMMENT '��USB Key���к�',
  `SECURE_KEY_SN` varchar(20) NOT NULL COMMENT '��̬���뿨��',
  `USER_PRIV` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '��ɫ���',
  `USER_PRIV_NO` smallint(5) unsigned NOT NULL DEFAULT '0' COMMENT '��ɫ�����',
  `USER_PRIV_NAME` varchar(40) NOT NULL DEFAULT '' COMMENT '��ɫ����',
  `POST_PRIV` char(1) NOT NULL DEFAULT '0' COMMENT '������Χ',
  `POST_DEPT` varchar(1000) NOT NULL DEFAULT '' COMMENT '������Χָ������',
  `DEPT_ID` int(11) NOT NULL DEFAULT '0' COMMENT '����ID',
  `DEPT_ID_OTHER` varchar(100) NOT NULL DEFAULT '' COMMENT '��������',
  `SEX` char(1) NOT NULL DEFAULT '' COMMENT '�Ա�',
  `BIRTHDAY` date NOT NULL DEFAULT '0000-00-00' COMMENT '����',
  `IS_LUNAR` char(1) NOT NULL DEFAULT '0' COMMENT '�Ƿ�ũ��(1-��,0-��)',
  `TEL_NO_DEPT` varchar(50) NOT NULL DEFAULT '' COMMENT '�����绰',
  `FAX_NO_DEPT` varchar(50) NOT NULL DEFAULT '' COMMENT '��������',
  `ADD_HOME` varchar(200) NOT NULL DEFAULT '' COMMENT '��ͥסַ',
  `POST_NO_HOME` varchar(50) NOT NULL DEFAULT '' COMMENT '��ͥ�ʱ�',
  `TEL_NO_HOME` varchar(50) NOT NULL DEFAULT '' COMMENT '��ͥ�绰',
  `MOBIL_NO` varchar(50) NOT NULL DEFAULT '' COMMENT '�ֻ���',
  `BP_NO` varchar(50) NOT NULL DEFAULT '' COMMENT 'BP����',
  `EMAIL` varchar(50) NOT NULL DEFAULT '' COMMENT '�����ʼ���ַ',
  `OICQ_NO` varchar(50) NOT NULL DEFAULT '' COMMENT 'QQ����',
  `ICQ_NO` varchar(50) NOT NULL DEFAULT '' COMMENT 'ICQ����',
  `MSN` varchar(200) NOT NULL DEFAULT '' COMMENT 'MSN����',
  `AVATAR` varchar(20) NOT NULL DEFAULT '' COMMENT '�Զ���Сͷ��',
  `CALL_SOUND` char(1) NOT NULL DEFAULT '1' COMMENT '������ʾ��',
  `LAST_VISIT_TIME` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '�ϴη���ϵͳ��ʱ��',
  `SMS_ON` char(1) NOT NULL DEFAULT '1',
  `MENU_TYPE` char(1) NOT NULL DEFAULT '1' COMMENT '��¼ģʽ(1-�ڱ����ڴ�OA,2-���´��ڴ�OA��ʾ������,3-���´��ڴ�)',
  `LAST_PASS_TIME` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '�ϴ��޸������ʱ��',
  `THEME` tinyint(3) unsigned NOT NULL DEFAULT '10' COMMENT '��������',
  `SHORTCUT` varchar(100) NOT NULL DEFAULT '' COMMENT '��ݲ˵�ID��',
  `PORTAL` varchar(20) NOT NULL DEFAULT '' COMMENT '�Ż�ID��',
  `PANEL` char(1) NOT NULL DEFAULT '1' COMMENT '��¼����ʾ��������(1-����,2-��֯,3-����,4-����)',
  `ONLINE` int(11) NOT NULL DEFAULT '0' COMMENT '����ʱ��',
  `ON_STATUS` char(1) NOT NULL DEFAULT '1' COMMENT '��¼����״̬(1-����,2-æµ,3-�뿪)',
  `ATTEND_STATUS` char(1) NOT NULL DEFAULT '0' COMMENT '��λ״̬(1-����,2-���,3-���)',
  `MOBIL_NO_HIDDEN` char(1) NOT NULL DEFAULT '0' COMMENT '�ֻ������Ƿ񹫿�(0-����,1-������)',
  `MYTABLE_LEFT` varchar(200) NOT NULL DEFAULT 'ALL' COMMENT '��������ģ��',
  `MYTABLE_RIGHT` varchar(200) NOT NULL DEFAULT 'ALL' COMMENT '�Ҳ������ģ��',
  `USER_PRIV_OTHER` varchar(100) NOT NULL DEFAULT '' COMMENT '������ɫ���봮',
  `USER_NO` smallint(5) unsigned NOT NULL DEFAULT '10' COMMENT '�û������',
  `NOT_LOGIN` char(1) NOT NULL DEFAULT '0' COMMENT '��ֹ��¼OAϵͳ(1-��ֹ,0-����ֹ)',
  `NOT_VIEW_USER` char(1) NOT NULL DEFAULT '0' COMMENT '��ֹ�鿴�û��б�(1-��ֹ,0-����ֹ)',
  `NOT_VIEW_TABLE` char(1) NOT NULL DEFAULT '0' COMMENT '��ֹ��ʾ����(1-��ֹ,0-����ֹ)',
  `NOT_SEARCH` char(1) NOT NULL DEFAULT '0' COMMENT '��ֹOA����(1-��ֹ,0-����ֹ)',
  `BKGROUND` varchar(200) NOT NULL DEFAULT '' COMMENT '���汳��ͼƬ',
  `BIND_IP` varchar(200) NOT NULL DEFAULT '' COMMENT '��IP��ַ',
  `LAST_VISIT_IP` varchar(20) NOT NULL DEFAULT '' COMMENT '�ϴη���ϵͳ��IP',
  `MENU_IMAGE` varchar(10) NOT NULL DEFAULT '0' COMMENT '�˵�ͼ��(0-ÿ���˵�ʹ�ò�ͬͼ��,1-����ʾ�˵�ͼ��)',
  `WEATHER_CITY` varchar(50) DEFAULT NULL,
  `SHOW_RSS` char(1) NOT NULL DEFAULT '1' COMMENT '�Ƿ���ʾ������Ѷ(1-��ʾ,0-����ʾ)',
  `MY_RSS` text NOT NULL COMMENT '������Ѷ�е���ַ����',
  `REMARK` text NOT NULL COMMENT '��ע',
  `MENU_EXPAND` char(2) NOT NULL DEFAULT '' COMMENT 'Ĭ��չ���˵�',
  `MY_STATUS` varchar(200) NOT NULL DEFAULT '' COMMENT '����ǩ��',
  `LIMIT_LOGIN` char(1) NOT NULL DEFAULT '0' COMMENT '��¼��������',
  `PHOTO` varchar(20) NOT NULL COMMENT '�û���ƬͼƬ',
  `IM_RANGE` tinyint(3) unsigned NOT NULL DEFAULT '1' COMMENT '��ʱͨѶʹ��Ȩ��(1-����ʹ��,2-��ֹʹ��)',
  `LEAVE_TIME` datetime NOT NULL COMMENT '����ʱ��',
  `SECRET_LEVEL` int(2) DEFAULT '0' COMMENT '�ʼ��ܼ�����(1-������,2-����[һ��],3-����[��Ҫ],4-����[�ǳ���Ҫ])',
  `USER_PARA` text NOT NULL COMMENT '用户参数',
  `NOT_MOBILE_LOGIN` char(1) NOT NULL DEFAULT '0' COMMENT '禁止登录OA系统手机客户端(1-禁止,0-不禁止)',
  `USER_byID` varchar(20) DEFAULT NULL COMMENT '工号',
  `USER_JOB` varchar(15) DEFAULT NULL,
  `USER_BU` varchar(55) DEFAULT NULL,
  `IS_KH` varchar(1) DEFAULT NULL,
  `BEFORE_DEPT_ID` int(11) DEFAULT NULL,
  `BEFORE_DEPT_NAME` varchar(255) DEFAULT NULL,
  `IS_SALES` varchar(1) DEFAULT NULL COMMENT '是否业务员（Y/N）',
  PRIMARY KEY (`UID`),
  UNIQUE KEY `USER_ID` (`USER_ID`),
  KEY `USER_PRIV` (`USER_PRIV`),
  KEY `DEPT_ID` (`DEPT_ID`),
  KEY `BYNAME` (`BYNAME`),
  KEY `PRIV_NO_NAME` (`USER_PRIV_NO`,`USER_NO`,`USER_NAME`),
  KEY `USER_NAME_INDEX` (`USER_NAME_INDEX`),
  KEY `USER_PRIV_OTHER` (`USER_PRIV_OTHER`),
  KEY `USER_byID` (`USER_byID`)
) ENGINE=InnoDB AUTO_INCREMENT=2888 DEFAULT CHARSET=gbk COMMENT='�û���';

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
