/*
Navicat MySQL Data Transfer

Source Server         : 192.168.1.20
Source Server Version : 50722
Source Host           : 192.168.1.20:3336
Source Database       : TD_OA

Target Server Type    : MYSQL
Target Server Version : 50722
File Encoding         : 65001

Date: 2020-09-30 18:12:21
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for department
-- ----------------------------
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
  KEY `DEPT_PARENT` (`DEPT_PARENT`)
) ENGINE=InnoDB AUTO_INCREMENT=178 DEFAULT CHARSET=gbk COMMENT='������Ϣ��';

-- ----------------------------
-- Records of department
-- ----------------------------
INSERT INTO `department` VALUES ('2', '董事长(Chairman)', '', '', '', '001', '0', 'meterw,', '', 'meterw,', '', '', '0', '', '', 'CORP', null);
INSERT INTO `department` VALUES ('3', '市场总监（国际业务一部）', '', '', '', '001005', '2', '', 'lhz,', 'meterw,', '', '', '0', '', '', 'INHE', 'Y');
INSERT INTO `department` VALUES ('4', '技术总监(Technical Director)', '', '', '', '001025', '2', 'szdfm,', '', 'meterw,', '', '', '0', '', '', 'INHE', 'Y');
INSERT INTO `department` VALUES ('5', '营运总监(Operation Director)', '', '', '', '001040', '2', 'IM-0015,', '', 'meterw,', '', '', '0', '', '', 'INHE', 'Y');
INSERT INTO `department` VALUES ('6', '财务总监(Financial Director)', '', '', '', '001034', '2', 'INHE-0918,', '', 'meterw,', '', '', '0', '', '', 'INHE', 'Y');
INSERT INTO `department` VALUES ('8', '国内业务部(DBD)', '', '', '', '001055001007020', '89', 'JXINHE-4037,', '', 'INHE-0038,', '', '', '0', '', '', 'JXINHE', null);
INSERT INTO `department` VALUES ('10', '客户中心', '', '', '', '001023005', '58', 'INHE-0144,', '', 'INHE-0144,', '', '', '0', '', '', 'INHE', null);
INSERT INTO `department` VALUES ('11', '研发中心(RDC)', '', '', '', '001025010', '4', '', '', 'szdfm,', '', '', '0', '', '', 'INHE', null);
INSERT INTO `department` VALUES ('12', '制造中心', '', '', '', '001055001030', '41', '', '', 'INHE-0038,', '', '', '0', '', '', 'JXINHE', null);
INSERT INTO `department` VALUES ('13', '计量室', '', '', '', '001025020', '4', '', '', '', '', '', '0', '', '', 'INHE', null);
INSERT INTO `department` VALUES ('14', '标准研究与文控部', '', '', '', '001025025', '4', 'INHE-0067,', '', 'szdfm,', '', '', '0', '', '', 'INHE', null);
INSERT INTO `department` VALUES ('15', '行政人力资源部(HR)', '', '', '', '001040005', '5', 'INHE-0359,', '', 'IM-0015,', '', '', '0', '', '', 'INHE', null);
INSERT INTO `department` VALUES ('17', '企业管理与信息化部', '', '', '', '001040015', '5', 'INHE-0450,', '', 'IM-0015,', 't03,', '', '0', '', '', 'INHE', null);
INSERT INTO `department` VALUES ('18', '会计部(FD)', '', '', '', '001034005', '6', 'INHE-0800,', '', 'INHE-0918,', '', '', '0', '', '', 'INHE', null);
INSERT INTO `department` VALUES ('19', '证券部', '', '', '', '001043010', '91', 'INHE-0711,', '', 'meterw,', '', '', '0', '', '', 'INHE', null);
INSERT INTO `department` VALUES ('20', '电子部', '', '', '', '001025010005', '11', 'INHE-0247,', '', 'szdfm,', '', '', '0', '', '', 'INHE', null);
INSERT INTO `department` VALUES ('21', '结构部', '', '', '', '001025010010', '11', 'INHE-0516,', '', 'szdfm,', '', '', '0', '', '', 'INHE', null);
INSERT INTO `department` VALUES ('22', '软件事业部', '', '', '', '001025011', '4', 'kenny,', '', 'szdfm,', '', '', '0', '', '', 'INHE', null);
INSERT INTO `department` VALUES ('23', '实验室', '', '', '', '001025010020', '11', 'szyuxl,', '', 'szdfm,', '', '', '0', '', '', 'INHE', null);
INSERT INTO `department` VALUES ('24', '品管部', '', '', '', '001055001030005', '12', 'INHE-0158,', '', 'INHE-0038,', '', '', '0', '', '', 'JXINHE', null);
INSERT INTO `department` VALUES ('26', '生产部', '', '', '', '001055001030010', '12', 'INHE-0038,', '', 'INHE-0038,', '', '', '0', '', '', 'JXINHE', null);
INSERT INTO `department` VALUES ('27', '采购部(POD)', '', '', '', '001055001030015', '12', 'JXINHE-1646,', '', 'INHE-0038,', '', '', '0', '', '', 'JXINHE', null);
INSERT INTO `department` VALUES ('38', '江西银河', '', '', '', '001055', '2', 'INHE-0038,', '', 'meterw,', '', '', '0', '', '', 'JXINHE', null);
INSERT INTO `department` VALUES ('39', '财务部(FD)', '', '', '', '001055001040', '41', 'JXINHE-2272,', '', 'INHE-0038,', '', '', '0', '', '', 'JXINHE', null);
INSERT INTO `department` VALUES ('41', '总经理(Managing Director)', '', '', '', '001055001', '38', 'INHE-0038,', '', 'meterw,', '', '', '0', ' ', '', 'JXINHE', null);
INSERT INTO `department` VALUES ('42', '仓储部', '', '', '', '001055001035', '41', 'JXINHE-0385,', '', 'INHE-0038,', '', '', '0', '', '', 'JXINHE', null);
INSERT INTO `department` VALUES ('43', '中试车间', '', '', '', '001055001030010005', '26', 'JXINHE-0276,', '', 'INHE-0038,', '', '', '0', '', '', 'JXINHE', null);
INSERT INTO `department` VALUES ('44', '行政人力资源部副总经理', '', '', '', '001055001045', '41', 'JXINHE-2860,', '', 'INHE-0038,', '', '', '0', '', '', 'JXINHE', null);
INSERT INTO `department` VALUES ('46', 'PMC', '', '', '', '001055001015', '41', 'INHE-0038,', '', 'INHE-0038,', '', '', '0', '', '', 'JXINHE', null);
INSERT INTO `department` VALUES ('48', '采购资源部', '', '', '', '001025015', '4', 'szwy,', '', 'szdfm,', '', '', '0', '', '', 'INHE', null);
INSERT INTO `department` VALUES ('49', '总经办(GD)', '', '', '', '001055001003', '41', '', '', 'INHE-0038,', '', '', '0', '', '', 'JXINHE', null);
INSERT INTO `department` VALUES ('50', '研发部(RDD)', '', '', '', '001055001012', '41', 'JXINHE-1995,', '', 'INHE-0038,', '', '', '0', '', '', 'JXINHE', null);
INSERT INTO `department` VALUES ('51', '实验室', '', '', '', '001055001012005', '50', 'JXINHE-1995,', '', '', '', '', '0', '', '', 'JXINHE', null);
INSERT INTO `department` VALUES ('53', '国际业务部(OBD)', '', '', '', '001055001006', '41', 'INHE-0038,', '', 'INHE-0038,', '', '', '0', '', '', 'JXINHE', null);
INSERT INTO `department` VALUES ('54', '跟单组', '', '', '', '001055001006005', '53', 'INHE-0038,', '', 'INHE-0038,', '', '', '0', '', '', 'JXINHE', null);
INSERT INTO `department` VALUES ('55', '跟单组(Logistics Division)', '', '', '', '001014017015', '97', 'lhz,', 'INHE-0051,', 'INHE-0051,', '', '', '0', '', '', 'INHE', null);
INSERT INTO `department` VALUES ('56', '工程部', '', '', '', '001055001030020', '12', 'JXINHE-3100,', '', 'INHE-0038,', '', '', '0', '', '', 'JXINHE', null);
INSERT INTO `department` VALUES ('57', '技术总监助理', '', '', '', '001025005', '4', 'zcp,', '', 'szdfm,', '', '', '0', '', '', 'INHE', null);
INSERT INTO `department` VALUES ('58', '技术支持总监', '', '', '', '001023', '2', 'INHE-0144,', '', 'meterw,', '', '', '0', '', '', 'INHE', 'Y');
INSERT INTO `department` VALUES ('59', '市场总监（国际业务二部）', '', '', '', '001010', '2', 'INHE-0036,', '', 'meterw,', '', '', '0', '', '', 'INHE', 'Y');
INSERT INTO `department` VALUES ('60', '国际一部 INTL.Business Dept.1', '', '', '', '001005005', '3', 'WL-0048,', '', 'WL-0048,', '', '', '0', '', '', 'INHE', null);
INSERT INTO `department` VALUES ('62', '审计部(Audit Dept.)', '', '', '', '001045', '2', 'INHE-0214,', '', 'meterw,', '', '', '0', '', '', 'INHE', null);
INSERT INTO `department` VALUES ('64', '慧联通信(WITLINK)', '', '', '', '001080060', '126', 'INHE-0224,', '', 'meterw,', '', '', '0', '', '', 'WITLINK', null);
INSERT INTO `department` VALUES ('65', '市场总监（国际业务三部）', '', '', '', '001011', '2', 'INHE-0702,', '', 'meterw,', '', '', '0', '', '', 'INHE', 'Y');
INSERT INTO `department` VALUES ('66', '国际三部 INTL.Business Dept.3', '', '', '', '001011001', '65', 'INHE-0702,', '', 'INHE-0702,', '', '', '0', '', '', 'INHE', null);
INSERT INTO `department` VALUES ('68', '总经理(Managing Director)', '', '', '', '001080060010', '64', 'WL-0008,', '', 'meterw,', '', '', '0', '', '', 'WITLINK', null);
INSERT INTO `department` VALUES ('69', '业务部(BD)', '', '', '', '001080060010020', '68', 'WL-0048,', '', 'INHE-0224,', '', '', '0', '', '', 'WITLINK', null);
INSERT INTO `department` VALUES ('71', '财务部(FD)', '', '', '', '001080060010024', '68', 'INHE-0918,', '', 'INHE-0918,', '', '', '0', '', '', 'WITLINK', null);
INSERT INTO `department` VALUES ('72', '采购部(POD)', '', '', '', '001080060010023', '68', 'szwy,', '', 'WL-0008,', '', '', '0', '', '', 'WITLINK', null);
INSERT INTO `department` VALUES ('73', '研发部(RDD)', '', '', '', '001080060010022', '68', 'WL-0008,', '', 'WL-0008,', '', '', '0', '', '', 'WITLINK', null);
INSERT INTO `department` VALUES ('74', '项目融资和风险控制部(Legal Dept.)', '', '', '', '001018', '2', 'INHE-0260,', '', 'meterw,', '', '', '0', '', '', 'INHE', null);
INSERT INTO `department` VALUES ('75', '银河非洲公司IAC', '', '', '', '001010070', '59', 'INHE-0432,', '', 'INHE-0036,', '', '', '0', '', '', 'INHE', null);
INSERT INTO `department` VALUES ('82', '结构组', '', '', '', '001055001012001', '50', 'JXINHE-1995,jxinhe-1995,', '', '', '', '', '0', '', '', 'JXINHE', null);
INSERT INTO `department` VALUES ('83', '软件组', '', '', '', '001055001012002', '50', 'kenny,', '', 'INHE-0038,', '', '', '0', '', '', 'JXINHE', null);
INSERT INTO `department` VALUES ('85', '行政人力资源部(HR)', '', '', '', '001080060010025', '68', 'INHE-0224,', '', 'INHE-0224,', '', '', '0', '', '', 'WITLINK', null);
INSERT INTO `department` VALUES ('87', '银河电网(INHEGRID)', '', '', '', '001080033', '126', 'szyaj,', '', 'meterw,', '', '', '0', '', '', 'INHEGRID', null);
INSERT INTO `department` VALUES ('89', '国内市场总监', '', '', '', '001055001007', '41', 'INHE-0038,', '', 'meterw,', '', '', '0', '', '', 'JXINHE', 'Y');
INSERT INTO `department` VALUES ('91', '董事会秘书(Board Secretary)', '', '', '', '001043', '2', 'INHE-0711,', '', 'meterw,', '', '', '0', '', '', 'INHE', 'Y');
INSERT INTO `department` VALUES ('92', '投资部', '', '', '', '001043020', '91', '', '', '', '', '', '0', '', '', 'INHE', null);
INSERT INTO `department` VALUES ('93', '国际业务部(OBD)', '', '', '', '001080033002011', '98', 'INHE-0091,', '', 'szyaj,', '', '', '0', '', '', 'INHEGRID', null);
INSERT INTO `department` VALUES ('94', '研发部(RDD)', '', '', '', '001080033002014015', '110', 'INHEGRID-0024,', '', 'szyaj,', '', '', '0', '', '', 'INHEGRID', null);
INSERT INTO `department` VALUES ('95', '总经理(Managing Director)', '', '', '', '001080034044', '112', 'INHE-0768,', '', 'meterw,', '', '', '0', '', '', 'INHENERGY', null);
INSERT INTO `department` VALUES ('97', '商务中心', '', '', '', '001014017', '166', 'INHE-0051,', '', 'meterw,', '', '', '0', '', '', 'INHE', null);
INSERT INTO `department` VALUES ('98', '总经理(Managing Director)', '', '', '', '001080033002', '87', 'szyaj,', '', 'meterw,', '', '', '0', '', '', 'INHEGRID', null);
INSERT INTO `department` VALUES ('99', '财务部(FD)', '', '', '', '001080033002016017', '103', 'IM-0042,', '', 'szyaj,', '', '', '0', '', '', 'INHEGRID', null);
INSERT INTO `department` VALUES ('100', '行政人力资源部(HR)', '', '', '', '001080033002018', '98', 'INHEGRID-0018,', '', 'szyaj,', '', '', '0', '', '', 'INHEGRID', null);
INSERT INTO `department` VALUES ('101', '品牌战略与市场研究部', '', '', '', '001043019', '91', 'INHE-0919,', '', 'INHE-0711,', '', '', '0', '', '', 'INHE', null);
INSERT INTO `department` VALUES ('102', '副总经理(VGM)', '', '', '', '001080033002005', '98', 'szyaj,', '', 'szyaj,', '', '', '0', '', '', 'INHEGRID', null);
INSERT INTO `department` VALUES ('103', '财务总监(Financial Director)', '', '', '', '001080033002016', '98', 'IM-0042,', '', 'szyaj,', '', '', '0', '', '', 'INHEGRID', 'Y');
INSERT INTO `department` VALUES ('104', '章春鹏项目组', '', '', '', '001025010005002', '20', 'zcp,', '', 'szdfm,', '', '', '0', '', '', 'INHE', null);
INSERT INTO `department` VALUES ('105', '王顺万项目组', '', '', '', '001025010005003', '20', 'INHE-0156,', '', 'INHE-0247,', 'szdfm,', '', '0', '', '', 'INHE', null);
INSERT INTO `department` VALUES ('106', '何梓劲项目组', '', '', '', '001025010005004', '20', 'INHE-0247,', '', 'szdfm,', '', '', '0', '', '', 'INHE', null);
INSERT INTO `department` VALUES ('107', '王占颍项目组', '', '', '', '001025010005005', '20', 'INHE-0285,', '', 'INHE-0247,', 'szdfm,', '', '0', '', '', 'INHE', null);
INSERT INTO `department` VALUES ('108', '张建刚硬件组', '', '', '', '001025010005006', '20', 'INHE-0193,', '', 'INHE-0247,', 'szdfm,', '', '0', '', '', 'INHE', null);
INSERT INTO `department` VALUES ('109', '技术支持(Technical Support)', '', '', '', '001080033002013', '98', 'INHE-0621,', '', 'szyaj,', 'INHEGRID-0060,', '', '0', '', '', 'INHEGRID', null);
INSERT INTO `department` VALUES ('110', '技术总监(Technical Director)', '', '', '', '001080033002014', '98', 'szyaj,', '', 'szyaj,', '', '', '0', '', '', 'INHEGRID', 'Y');
INSERT INTO `department` VALUES ('111', '国内业务部(DBD)', '', '', '', '001080033002012022', '148', 'szyaj,', '', 'szyaj,', '', '', '0', '', '', 'INHEGRID', null);
INSERT INTO `department` VALUES ('112', '银河耐吉(INHENERGY)', '', '', '', '001080034', '126', '', '', '', '', '', '0', '', '', 'INHENERGY', null);
INSERT INTO `department` VALUES ('113', '技术总监(Technical Director)', '', '', '', '001080034044010', '95', 'INHE-0768,', '', 'meterw,', '', '', '0', '', '', 'INHENERGY', 'Y');
INSERT INTO `department` VALUES ('115', '研发部(RDD)', '', '', '', '001080034044010010', '113', 'INHE-0777,', '', 'INHE-0768,', '', '', '0', '', '', 'INHENERGY', null);
INSERT INTO `department` VALUES ('116', '技术支持(Technical Support)', '', '', '', '001080034044010003', '113', 'INHE-0777,', '', 'INHE-0768,', '', '', '0', '', '', 'INHENERGY', null);
INSERT INTO `department` VALUES ('117', '采购资源部', '', '', '', '001080034044010030', '113', 'INHE-0777,', '', 'INHE-0768,', '', '', '0', '', '', 'INHENERGY', null);
INSERT INTO `department` VALUES ('118', '制造总监', '', '', '', '001080034044020', '95', 'INHE-0872,', '', 'INHE-0768,', '', '', '0', '', '', 'INHENERGY', 'Y');
INSERT INTO `department` VALUES ('119', '生产部', '', '', '', '001080034044020020', '118', 'INHE-0872,', '', 'INHE-0768,', '', '', '0', '', '', 'INHENERGY', null);
INSERT INTO `department` VALUES ('121', '营销中心总经理', '', '', '', '001080034044005', '95', 'INHE-0768,', '', 'meterw,', '', '', '0', '', '', 'INHENERGY', null);
INSERT INTO `department` VALUES ('123', '华南营销总监', '', '', '', '001080034044005022010030', '151', 'INHE-0825,', '', 'INHE-0768,', '', '', '0', '', '', 'INHENERGY', null);
INSERT INTO `department` VALUES ('124', '华南营销部', '', '', '', '001080034044005022010030010', '123', 'INHE-0825,', '', 'INHE-0768,', '', '', '0', '', '', 'INHENERGY', null);
INSERT INTO `department` VALUES ('125', '行政人力资源部(HR)', '', '', '', '001080034044040', '95', 'INHENERGY-0064,', '', 'INHE-0768,', '', '', '0', '', '', 'INHENERGY', null);
INSERT INTO `department` VALUES ('126', '各分公司(BO)', '', '', '', '001080', '2', '', '', '', '', '', '0', '', '', 'CORP', null);
INSERT INTO `department` VALUES ('127', '大客户营销总监', '', '', '', '001080034044005022010010', '151', '', '', '', '', '', '0', '', '', 'INHENERGY', null);
INSERT INTO `department` VALUES ('128', '国际营销总监', '', '', '', '001080034044005020', '121', 'INHE-0768,', '', 'INHE-0768,', '', '', '0', '', '', 'INHENERGY', 'Y');
INSERT INTO `department` VALUES ('129', '华北营销总监', '', '', '', '001080034044005022010040', '151', '', '', '', '', '', '0', '', '', 'INHENERGY', null);
INSERT INTO `department` VALUES ('130', '华东营销总监', '', '', '', '001080034044005022010050', '151', '', '', '', '', '', '0', '', '', 'INHENERGY', null);
INSERT INTO `department` VALUES ('131', '华中营销总监', '', '', '', '001080034044005022010060', '151', '', '', '', '', '', '0', '', '', 'INHENERGY', null);
INSERT INTO `department` VALUES ('132', '大客户营销部', '', '', '', '001080034044005022010010010', '127', '', '', '', '', '', '0', '', '', 'INHENERGY', null);
INSERT INTO `department` VALUES ('133', '国际业务部', '', '', '', '001080034044005020010', '128', 'INHE-0768,', '', 'INHE-0768,', '', '', '0', '', '', 'INHENERGY', null);
INSERT INTO `department` VALUES ('134', '华北营销部', '', '', '', '001080034044005022010040010', '129', 'INHE-0825,', '', 'INHE-0768,', '', '', '0', '', '', 'INHENERGY', null);
INSERT INTO `department` VALUES ('135', '华东营销部', '', '', '', '001080034044005022010050010', '130', 'INHE-0825,', '', 'INHE-0768,', '', '', '0', '', '', 'INHENERGY', null);
INSERT INTO `department` VALUES ('136', '华中营销部', '', '', '', '001080034044005022010060010', '131', 'INHE-0825,', '', 'INHE-0768,', '', '', '0', '', '', 'INHENERGY', null);
INSERT INTO `department` VALUES ('137', '财务部(FD)', '', '', '', '001080034044030', '95', 'IM-0042,', '', 'INHE-0768,', '', '', '0', '', '', 'INHENERGY', null);
INSERT INTO `department` VALUES ('138', '工程部', '', '', '', '001080034044020030', '118', 'INHE-0872,', '', 'INHE-0768,', '', '', '0', '', '', 'INHENERGY', null);
INSERT INTO `department` VALUES ('139', '资材部', '', '', '', '001080034044020040', '118', 'INHE-0872,', '', 'INHE-0768,', '', '', '0', '', '', 'INHENERGY', null);
INSERT INTO `department` VALUES ('140', '仓储部', '', '', '', '001080034044020050', '118', 'INHENERGY-0051,', '', 'INHE-0872,', '', '', '0', '', '', 'INHENERGY', null);
INSERT INTO `department` VALUES ('141', '基建办(Construction Dept.)', '', '', '', '001080033002021', '98', 'INHEGRID-0055,', '', 'meterw,', '', '', '0', '', '', 'INHEGRID', null);
INSERT INTO `department` VALUES ('142', '总经办(GD)', '', '', '', '001003', '2', 'IM-0044,', '', 'meterw,', '', '', '0', '', '', 'INHE', 'Y');
INSERT INTO `department` VALUES ('143', '竞标组', '', '', '', '001014017003', '97', 'INHE-0051,', '', 'meterw,', '', '', '0', '', '', 'INHE', null);
INSERT INTO `department` VALUES ('144', '投标英语组', '', '', '', '001014017005', '97', 'INHE-0662,', '', 'INHE-0051,', '', '', '0', '', '', 'INHE', null);
INSERT INTO `department` VALUES ('146', '市场部', '', '', '', '001080034044006', '95', 'INHE-0768,', '', 'meterw,', '', '', '0', '', '', 'INHENERGY', null);
INSERT INTO `department` VALUES ('147', '品管部', '', '', '', '001080034044025', '95', 'INHE-0872,', '', 'INHE-0768,', '', '', '0', '', '', 'INHENERGY', null);
INSERT INTO `department` VALUES ('148', '国内市场总监(DMD)', '', '', '', '001080033002012', '98', 'szyaj,', '', 'szyaj,', '', '', '0', '', '', 'INHEGRID', 'Y');
INSERT INTO `department` VALUES ('149', '商务跟单(Logistics Specialis)', '', '', '', '001080033002011002', '93', 'INHE-0091,', '', 'szyaj,', '', '', '0', '', '', 'INHEGRID', null);
INSERT INTO `department` VALUES ('150', '国内营销总监', '', '', '', '001080034044005022', '121', 'INHE-0825,', '', 'INHE-0768,', '', '', '0', '', '', 'INHENERGY', 'Y');
INSERT INTO `department` VALUES ('151', '国内业务部', '', '', '', '001080034044005022010', '150', 'INHE-0825,', '', 'INHE-0825,', '', '', '0', '', '', 'INHENERGY', null);
INSERT INTO `department` VALUES ('152', '商务部', '', '', '', '001080034044008', '95', '', '', '', '', '', '0', '', '', 'INHENERGY', null);
INSERT INTO `department` VALUES ('153', '重大项目部', '', '', '', '001003010', '142', 'INHE-0015,', '', 'IM-0044,', '', '', '0', '', '', 'INHE', null);
INSERT INTO `department` VALUES ('154', '单相车间', '', '', '', '001055001030010002', '26', 'JXINHE-0018,', '', 'INHE-0038,', '', '', '0', '', '', 'JXINHE', null);
INSERT INTO `department` VALUES ('155', '生产部(Production dept.)', '', '', '', '001080033002015', '98', 'INHEGRID-0065,', '', 'szyaj,', '', '', '0', '', '', 'INHEGRID', null);
INSERT INTO `department` VALUES ('156', '人力资源部', '', '', '', '001055001045002', '44', 'JXINHE-4040,', '', 'JXINHE-2860,', '', '', '0', '', '', 'JXINHE', null);
INSERT INTO `department` VALUES ('157', '行政部', '', '', '', '001055001045004', '44', 'JXINHE-2860,', '', 'INHE-0038,', '', '', '0', '', '', 'JXINHE', null);
INSERT INTO `department` VALUES ('158', '山东区区域总监', '', '', '', '001080034044005022010070', '151', '', '', '', '', '', '0', '', '', 'INHENERGY', null);
INSERT INTO `department` VALUES ('159', '山东营销部', '', '', '', '001080034044005022010070010', '158', '', '', '', '', '', '0', '', '', 'INHENERGY', null);
INSERT INTO `department` VALUES ('160', '技术顾问(Technical consultan)', '', '', '', '001080033002006', '98', 'szyaj,', '', 'szyaj,', '', '', '0', '', '', 'INHEGRID', null);
INSERT INTO `department` VALUES ('161', '技术支持总监助理', '', '', '', '001023003', '58', 'INHE-0272,', '', 'INHE-0144,', '', '', '0', '', '', 'INHE', null);
INSERT INTO `department` VALUES ('162', '方案组', '', '', '', '001023005003', '10', 'INHE-0272,', 'INHE-0863,', 'INHE-0144,', '', '', '0', '', '', 'INHE', null);
INSERT INTO `department` VALUES ('163', '投标组', '', '', '', '001023005007', '10', 'INHE-0797,', 'INHE-0272,INHE-0863,', 'INHE-0144,', '', '', '0', '', '', 'INHE', null);
INSERT INTO `department` VALUES ('164', '交付组', '', '', '', '001023005009', '10', 'INHE-0749,', 'INHE-0272,INHE-0863,', 'INHE-0144,', '', '', '0', '', '', 'INHE', null);
INSERT INTO `department` VALUES ('165', '客服专员', '', '', '', '001023004', '58', 'INHE-0272,', '', 'INHE-0144,', '', '', '0', '', '', 'INHE', null);
INSERT INTO `department` VALUES ('166', '商务总监', '', '', '', '001014', '2', 'INHE-0051,', '', 'meterw,', '', '', '0', '', '', 'INHE', 'Y');
INSERT INTO `department` VALUES ('167', '投标法语组', '', '', '', '001014017007', '97', 'INHE-0771,', '', 'INHE-0051,', '', '', '0', '', '', 'INHE', null);
INSERT INTO `department` VALUES ('169', '总监助理', '', '', '', '001010002', '59', 'INHE-0036,', '', 'meterw,', '', '', '0', '', '', 'INHE', null);
INSERT INTO `department` VALUES ('170', '东非区EAC', '', '', '', '001010006', '59', 'INHE-0036,', '', 'meterw,', '', '', '0', '', '', 'INHE', null);
INSERT INTO `department` VALUES ('171', '西非区WAC', '', '', '', '001010008', '59', 'INHE-0036,', '', 'meterw,', '', '', '0', '', '', 'INHE', null);
INSERT INTO `department` VALUES ('172', '中非区CAC', '', '', '', '001010010', '59', 'INHE-0036,', '', 'meterw,', '', '', '0', '', '', 'INHE', null);
INSERT INTO `department` VALUES ('173', '南非区SADC', '', '', '', '001010013', '59', 'INHE-0036,', '', 'meterw,', '', '', '0', '', '', 'INHE', null);
INSERT INTO `department` VALUES ('174', '东北非NEAC', '', '', '', '001010015', '59', 'INHE-0036,', '', 'meterw,', '', '', '0', '', '', 'INHE', null);
INSERT INTO `department` VALUES ('175', '南美区S. America', '', '', '', '001010017', '59', 'INHE-0036,', '', 'meterw,', '', '', '0', '', '', 'INHE', null);
INSERT INTO `department` VALUES ('176', '中北美区NC America', '', '', '', '001010019', '59', 'INHE-0036,', '', 'meterw,', '', '', '0', '', '', 'INHE', null);
INSERT INTO `department` VALUES ('177', '国际业务部', '', '', '', '001080034044003', '95', 'IM-0044,', '', 'IM-0044,', '', '', '0', '', '', '', null);
