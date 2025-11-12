/*
Navicat MySQL Data Transfer

Source Server         : oa
Source Server Version : 50525
Source Host           : localhost:3336
Source Database       : TD_OA

Target Server Type    : MYSQL
Target Server Version : 50525
File Encoding         : 65001

Date: 2020-08-24 07:05:37
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for department
-- ----------------------------
DROP TABLE IF EXISTS `department`;
CREATE TABLE `department` (
  `DEPT_ID` int(11) NOT NULL DEFAULT '0' COMMENT '自增唯一ID',
  `DEPT_NAME` varchar(50) NOT NULL DEFAULT '' COMMENT '部门名称',
  `TEL_NO` varchar(50) NOT NULL DEFAULT '' COMMENT '电话',
  `FAX_NO` varchar(50) NOT NULL DEFAULT '' COMMENT '传真',
  `DEPT_ADDRESS` varchar(100) NOT NULL COMMENT '部门地址',
  `DEPT_NO` varchar(200) NOT NULL DEFAULT '0' COMMENT '部门排序号',
  `DEPT_PARENT` int(11) NOT NULL DEFAULT '0' COMMENT '上级部门ID',
  `MANAGER` text NOT NULL COMMENT '部门主管',
  `ASSISTANT_ID` text NOT NULL COMMENT '部门助理',
  `LEADER1` text NOT NULL COMMENT '上级主管领导',
  `LEADER2` text NOT NULL COMMENT '上级分管领导',
  `DEPT_FUNC` text NOT NULL COMMENT '部门职能',
  `IS_ORG` char(1) NOT NULL DEFAULT '0' COMMENT '是否是分支机构(0-否,1-是)',
  `ORG_ADMIN` varchar(200) NOT NULL COMMENT '机构管理员',
  `DEPT_EMAIL_AUDITS_IDS` varchar(255) NOT NULL DEFAULT '' COMMENT '保密邮件审核人',
  `USER_BU` varchar(20) DEFAULT NULL,
  `is_director` varchar(10) DEFAULT NULL,
  PRIMARY KEY (`DEPT_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=gbk;

-- ----------------------------
-- Records of department
-- ----------------------------
INSERT INTO `department` VALUES ('2', '董事长(Chairman)', '', '', '', '1', '0', 'meterw,', '', 'meterw,', '', '', '0', '', '', 'CORP', '');
INSERT INTO `department` VALUES ('3', '市场总监（国际业务一部）', '', '', '', '1005', '2', '', 'lhz,', 'meterw,', '', '', '0', '', '', 'INHE', 'Y');
INSERT INTO `department` VALUES ('4', '技术总监(Technical Director)', '', '', '', '1025', '2', 'szdfm,', '', 'meterw,', '', '', '0', '', '', 'INHE', 'Y');
INSERT INTO `department` VALUES ('5', '营运总监(Operation Director)', '', '', '', '1040', '2', 'IM-0015,', '', 'meterw,', '', '', '0', '', '', 'INHE', 'Y');
INSERT INTO `department` VALUES ('6', '财务总监(Financial Director)', '', '', '', '1034', '2', 'INHE-0918,', '', 'meterw,', '', '', '0', '', '', 'INHE', 'Y');
INSERT INTO `department` VALUES ('8', '国内业务部(DBD)', '', '', '', '1.055001007e+012', '89', 'JXINHE-4037,', '', 'INHE-0038,', '', '', '0', '', '', 'JXINHE', '');
INSERT INTO `department` VALUES ('10', '客户中心', '', '', '', '1023005', '58', 'INHE-0144,', '', 'INHE-0144,', '', '', '0', '', '', 'INHE', '');
INSERT INTO `department` VALUES ('11', '研发中心(RDC)', '', '', '', '1025010', '4', '', '', 'szdfm,', '', '', '0', '', '', 'INHE', '');
INSERT INTO `department` VALUES ('12', '制造中心', '', '', '', '1055001030', '41', '', '', 'INHE-0038,', '', '', '0', '', '', 'JXINHE', '');
INSERT INTO `department` VALUES ('13', '计量室', '', '', '', '1025020', '4', '', '', '', '', '', '0', '', '', 'INHE', '');
INSERT INTO `department` VALUES ('14', '标准研究与文控部', '', '', '', '1025025', '4', 'INHE-0067,', '', 'szdfm,', '', '', '0', '', '', 'INHE', '');
INSERT INTO `department` VALUES ('15', '行政人力资源部(HR)', '', '', '', '1040005', '5', 'INHE-0359,', '', 'IM-0015,', '', '', '0', '', '', 'INHE', '');
INSERT INTO `department` VALUES ('17', '企业管理与信息化部', '', '', '', '1040015', '5', 'INHE-0450,', '', 'IM-0015,', 't03,', '', '0', '', '', 'INHE', '');
INSERT INTO `department` VALUES ('18', '会计部(FD)', '', '', '', '1034005', '6', 'INHE-0800,', '', 'INHE-0918,', '', '', '0', '', '', 'INHE', '');
INSERT INTO `department` VALUES ('19', '证券部', '', '', '', '1043010', '91', 'INHE-0711,', '', 'meterw,', '', '', '0', '', '', 'INHE', '');
INSERT INTO `department` VALUES ('20', '电子部', '', '', '', '1025010005', '11', 'INHE-0247,', '', 'szdfm,', '', '', '0', '', '', 'INHE', '');
INSERT INTO `department` VALUES ('21', '结构部', '', '', '', '1025010010', '11', 'INHE-0516,', '', 'szdfm,', '', '', '0', '', '', 'INHE', '');
INSERT INTO `department` VALUES ('22', '软件事业部', '', '', '', '1025011', '4', 'kenny,', '', 'szdfm,', '', '', '0', '', '', 'INHE', '');
INSERT INTO `department` VALUES ('23', '实验室', '', '', '', '1025010020', '11', 'szyuxl,', '', 'szdfm,', '', '', '0', '', '', 'INHE', '');
INSERT INTO `department` VALUES ('24', '品管部', '', '', '', '1.05500103e+012', '12', 'INHE-0158,', '', 'INHE-0038,', '', '', '0', '', '', 'JXINHE', '');
INSERT INTO `department` VALUES ('26', '生产部', '', '', '', '1.05500103e+012', '12', 'INHE-0038,', '', 'INHE-0038,', '', '', '0', '', '', 'JXINHE', '');
INSERT INTO `department` VALUES ('27', '采购部(POD)', '', '', '', '1.05500103e+012', '12', 'JXINHE-1646,', '', 'INHE-0038,', '', '', '0', '', '', 'JXINHE', '');
INSERT INTO `department` VALUES ('38', '江西银河', '', '', '', '1055', '2', 'INHE-0038,', '', 'meterw,', '', '', '0', '', '', 'JXINHE', '');
INSERT INTO `department` VALUES ('39', '财务部(FD)', '', '', '', '1055001040', '41', 'JXINHE-2272,', '', 'INHE-0038,', '', '', '0', '', '', 'JXINHE', '');
INSERT INTO `department` VALUES ('41', '总经理(Managing Director)', '', '', '', '1055001', '38', 'INHE-0038,', '', 'meterw,', '', '', '0', '', '', 'JXINHE', '');
INSERT INTO `department` VALUES ('42', '仓储部', '', '', '', '1055001035', '41', 'JXINHE-0385,', '', 'INHE-0038,', '', '', '0', '', '', 'JXINHE', '');
INSERT INTO `department` VALUES ('43', '中试车间', '', '', '', '1.05500103e+015', '26', 'JXINHE-0276,', '', 'INHE-0038,', '', '', '0', '', '', 'JXINHE', '');
INSERT INTO `department` VALUES ('44', '行政人力资源部副总经理', '', '', '', '1055001045', '41', 'JXINHE-2860,', '', 'INHE-0038,', '', '', '0', '', '', 'JXINHE', '');
INSERT INTO `department` VALUES ('46', 'PMC', '', '', '', '1055001015', '41', 'INHE-0038,', '', 'INHE-0038,', '', '', '0', '', '', 'JXINHE', '');
INSERT INTO `department` VALUES ('48', '采购资源部', '', '', '', '1025015', '4', 'szwy,', '', 'szdfm,', '', '', '0', '', '', 'INHE', '');
INSERT INTO `department` VALUES ('49', '总经办(GD)', '', '', '', '1055001003', '41', '', '', 'INHE-0038,', '', '', '0', '', '', 'JXINHE', '');
INSERT INTO `department` VALUES ('50', '研发部(RDD)', '', '', '', '1055001012', '41', 'JXINHE-1995,', '', 'INHE-0038,', '', '', '0', '', '', 'JXINHE', '');
INSERT INTO `department` VALUES ('51', '实验室', '', '', '', '1.055001012e+012', '50', 'JXINHE-1995,', '', '', '', '', '0', '', '', 'JXINHE', '');
INSERT INTO `department` VALUES ('53', '国际业务部(OBD)', '', '', '', '1055001006', '41', 'INHE-0038,', '', 'INHE-0038,', '', '', '0', '', '', 'JXINHE', '');
INSERT INTO `department` VALUES ('54', '跟单组', '', '', '', '1.055001006e+012', '53', 'INHE-0038,', '', 'INHE-0038,', '', '', '0', '', '', 'JXINHE', '');
INSERT INTO `department` VALUES ('55', '跟单组(Logistics Division)', '', '', '', '1014017015', '97', 'lhz,', 'INHE-0051,', 'INHE-0051,', '', '', '0', '', '', 'INHE', '');
INSERT INTO `department` VALUES ('56', '工程部', '', '', '', '1.05500103e+012', '12', 'JXINHE-3100,', '', 'INHE-0038,', '', '', '0', '', '', 'JXINHE', '');
INSERT INTO `department` VALUES ('57', '技术总监助理', '', '', '', '1025005', '4', 'zcp,', '', 'szdfm,', '', '', '0', '', '', 'INHE', '');
INSERT INTO `department` VALUES ('58', '技术支持总监', '', '', '', '1023', '2', 'INHE-0144,', '', 'meterw,', '', '', '0', '', '', 'INHE', 'Y');
INSERT INTO `department` VALUES ('59', '市场总监（国际业务二部）', '', '', '', '1010', '2', 'INHE-0036,', '', 'meterw,', '', '', '0', '', '', 'INHE', 'Y');
INSERT INTO `department` VALUES ('60', '国际一部 INTL.Business Dept.1', '', '', '', '1005005', '3', 'WL-0048,', '', 'WL-0048,', '', '', '0', '', '', 'INHE', '');
INSERT INTO `department` VALUES ('62', '审计部(Audit Dept.)', '', '', '', '1045', '2', 'INHE-0214,', '', 'meterw,', '', '', '0', '', '', 'INHE', '');
INSERT INTO `department` VALUES ('64', '慧联通信(WITLINK)', '', '', '', '1080060', '126', 'INHE-0224,', '', 'meterw,', '', '', '0', '', '', 'WITLINK', '');
INSERT INTO `department` VALUES ('65', '市场总监（国际业务三部）', '', '', '', '1011', '2', 'INHE-0702,', '', 'meterw,', '', '', '0', '', '', 'INHE', 'Y');
INSERT INTO `department` VALUES ('66', '国际三部 INTL.Business Dept.3', '', '', '', '1011001', '65', 'INHE-0702,', '', 'INHE-0702,', '', '', '0', '', '', 'INHE', '');
INSERT INTO `department` VALUES ('68', '总经理(Managing Director)', '', '', '', '1080060010', '64', 'WL-0008,', '', 'meterw,', '', '', '0', '', '', 'WITLINK', '');
INSERT INTO `department` VALUES ('69', '业务部(BD)', '', '', '', '1.08006001e+012', '68', 'WL-0048,', '', 'INHE-0224,', '', '', '0', '', '', 'WITLINK', '');
INSERT INTO `department` VALUES ('71', '财务部(FD)', '', '', '', '1.08006001e+012', '68', 'INHE-0918,', '', 'INHE-0918,', '', '', '0', '', '', 'WITLINK', '');
INSERT INTO `department` VALUES ('72', '采购部(POD)', '', '', '', '1.08006001e+012', '68', 'WL-0008,', '', 'WL-0008,', '', '', '0', '', '', 'WITLINK', '');
INSERT INTO `department` VALUES ('73', '研发部(RDD)', '', '', '', '1.08006001e+012', '68', 'WL-0008,', '', 'WL-0008,', '', '', '0', '', '', 'WITLINK', '');
INSERT INTO `department` VALUES ('74', '项目融资和风险控制部(Legal Dept.)', '', '', '', '1018', '2', 'INHE-0260,', '', 'meterw,', '', '', '0', '', '', 'INHE', '');
INSERT INTO `department` VALUES ('75', '银河非洲公司IAC', '', '', '', '1010070', '59', 'INHE-0432,', '', 'INHE-0036,', '', '', '0', '', '', 'INHE', '');
INSERT INTO `department` VALUES ('82', '结构组', '', '', '', '1.055001012e+012', '50', 'JXINHE-1995,jxinhe-1995,', '', '', '', '', '0', '', '', 'JXINHE', '');
INSERT INTO `department` VALUES ('83', '软件组', '', '', '', '1.055001012e+012', '50', 'kenny,', '', 'INHE-0038,', '', '', '0', '', '', 'JXINHE', '');
INSERT INTO `department` VALUES ('85', '行政人力资源部(HR)', '', '', '', '1.08006001e+012', '68', 'INHE-0224,', '', 'INHE-0224,', '', '', '0', '', '', 'WITLINK', '');
INSERT INTO `department` VALUES ('87', '银河电网(INHEGRID)', '', '', '', '1080033', '126', 'szyaj,', '', 'meterw,', '', '', '0', '', '', 'INHEGRID', '');
INSERT INTO `department` VALUES ('89', '国内市场总监', '', '', '', '1055001007', '41', 'INHE-0038,', '', 'meterw,', '', '', '0', '', '', 'JXINHE', 'Y');
INSERT INTO `department` VALUES ('91', '董事会秘书(Board Secretary)', '', '', '', '1043', '2', 'INHE-0711,', '', 'meterw,', '', '', '0', '', '', 'INHE', 'Y');
INSERT INTO `department` VALUES ('92', '投资部', '', '', '', '1043020', '91', '', '', '', '', '', '0', '', '', 'INHE', '');
INSERT INTO `department` VALUES ('93', '国际业务部(OBD)', '', '', '', '1.080033002e+012', '98', 'INHE-0091,', '', 'szyaj,', '', '', '0', '', '', 'INHEGRID', '');
INSERT INTO `department` VALUES ('94', '研发部(RDD)', '', '', '', '1.080033002e+015', '110', 'INHEGRID-0024,', '', 'szyaj,', '', '', '0', '', '', 'INHEGRID', '');
INSERT INTO `department` VALUES ('95', '总经理(Managing Director)', '', '', '', '1080034044', '112', 'INHE-0768,', '', 'meterw,', '', '', '0', '', '', 'INHENERGY', '');
INSERT INTO `department` VALUES ('97', '商务中心', '', '', '', '1014017', '166', 'INHE-0051,', '', 'meterw,', '', '', '0', '', '', 'INHE', '');
INSERT INTO `department` VALUES ('98', '总经理(Managing Director)', '', '', '', '1080033002', '87', 'szyaj,', '', 'meterw,', '', '', '0', '', '', 'INHEGRID', '');
INSERT INTO `department` VALUES ('99', '财务部(FD)', '', '', '', '1.080033002e+015', '103', 'IM-0042,', '', 'szyaj,', '', '', '0', '', '', 'INHEGRID', '');
INSERT INTO `department` VALUES ('100', '行政人力资源部(HR)', '', '', '', '1.080033002e+012', '98', 'INHEGRID-0018,', '', 'szyaj,', '', '', '0', '', '', 'INHEGRID', '');
INSERT INTO `department` VALUES ('101', '品牌战略与市场研究部', '', '', '', '1043019', '91', 'INHE-0919,', '', 'INHE-0711,', '', '', '0', '', '', 'INHE', '');
INSERT INTO `department` VALUES ('102', '副总经理(VGM)', '', '', '', '1.080033002e+012', '98', 'szyaj,', '', 'szyaj,', '', '', '0', '', '', 'INHEGRID', '');
INSERT INTO `department` VALUES ('103', '财务总监(Financial Director)', '', '', '', '1.080033002e+012', '98', 'IM-0042,', '', 'szyaj,', '', '', '0', '', '', 'INHEGRID', 'Y');
INSERT INTO `department` VALUES ('104', '章春鹏项目组', '', '', '', '1.025010005e+012', '20', 'zcp,', '', 'szdfm,', '', '', '0', '', '', 'INHE', '');
INSERT INTO `department` VALUES ('105', '王顺万项目组', '', '', '', '1.025010005e+012', '20', 'INHE-0156,', '', 'INHE-0247,', 'szdfm,', '', '0', '', '', 'INHE', '');
INSERT INTO `department` VALUES ('106', '何梓劲项目组', '', '', '', '1.025010005e+012', '20', 'INHE-0247,', '', 'szdfm,', '', '', '0', '', '', 'INHE', '');
INSERT INTO `department` VALUES ('107', '王占颍项目组', '', '', '', '1.025010005e+012', '20', 'INHE-0285,', '', 'INHE-0247,', 'szdfm,', '', '0', '', '', 'INHE', '');
INSERT INTO `department` VALUES ('108', '张建刚硬件组', '', '', '', '1.025010005e+012', '20', 'INHE-0193,', '', 'INHE-0247,', 'szdfm,', '', '0', '', '', 'INHE', '');
INSERT INTO `department` VALUES ('109', '技术支持(Technical Support)', '', '', '', '1.080033002e+012', '98', 'INHE-0621,', '', 'szyaj,', 'INHEGRID-0060,', '', '0', '', '', 'INHEGRID', '');
INSERT INTO `department` VALUES ('110', '技术总监(Technical Director)', '', '', '', '1.080033002e+012', '98', 'szyaj,', '', 'szyaj,', '', '', '0', '', '', 'INHEGRID', 'Y');
INSERT INTO `department` VALUES ('111', '国内业务部(DBD)', '', '', '', '1.080033002e+015', '148', 'szyaj,', '', 'szyaj,', '', '', '0', '', '', 'INHEGRID', '');
INSERT INTO `department` VALUES ('112', '银河耐吉(INHENERGY)', '', '', '', '1080034', '126', '', '', '', '', '', '0', '', '', 'INHENERGY', '');
INSERT INTO `department` VALUES ('113', '技术总监(Technical Director)', '', '', '', '1.080034044e+012', '95', 'INHE-0768,', '', 'meterw,', '', '', '0', '', '', 'INHENERGY', 'Y');
INSERT INTO `department` VALUES ('115', '研发部(RDD)', '', '', '', '1.080034044e+015', '113', 'INHE-0777,', '', 'INHE-0768,', '', '', '0', '', '', 'INHENERGY', '');
INSERT INTO `department` VALUES ('116', '技术支持(Technical Support)', '', '', '', '1.080034044e+015', '113', 'INHE-0777,', '', 'INHE-0768,', '', '', '0', '', '', 'INHENERGY', '');
INSERT INTO `department` VALUES ('117', '采购资源部', '', '', '', '1.080034044e+015', '113', 'INHE-0777,', '', 'INHE-0768,', '', '', '0', '', '', 'INHENERGY', '');
INSERT INTO `department` VALUES ('118', '制造总监', '', '', '', '1.080034044e+012', '95', 'INHE-0872,', '', 'INHE-0768,', '', '', '0', '', '', 'INHENERGY', 'Y');
INSERT INTO `department` VALUES ('119', '生产部', '', '', '', '1.080034044e+015', '118', 'INHE-0872,', '', 'INHE-0768,', '', '', '0', '', '', 'INHENERGY', '');
INSERT INTO `department` VALUES ('121', '营销中心总经理', '', '', '', '1.080034044e+012', '95', 'INHE-0768,', '', 'meterw,', '', '', '0', '', '', 'INHENERGY', '');
INSERT INTO `department` VALUES ('123', '华南营销总监', '', '', '', '1.080034044e+021', '151', 'INHE-0825,', '', 'INHE-0768,', '', '', '0', '', '', 'INHENERGY', '');
INSERT INTO `department` VALUES ('124', '华南营销部', '', '', '', '1.080034044e+024', '123', 'INHE-0825,', '', 'INHE-0768,', '', '', '0', '', '', 'INHENERGY', '');
INSERT INTO `department` VALUES ('125', '行政人力资源部(HR)', '', '', '', '1.080034044e+012', '95', 'INHENERGY-0064,', '', 'INHE-0768,', '', '', '0', '', '', 'INHENERGY', '');
INSERT INTO `department` VALUES ('126', '各分公司(BO)', '', '', '', '1080', '2', '', '', '', '', '', '0', '', '', 'CORP', '');
INSERT INTO `department` VALUES ('127', '大客户营销总监', '', '', '', '1.080034044e+021', '151', '', '', '', '', '', '0', '', '', 'INHENERGY', '');
INSERT INTO `department` VALUES ('128', '国际营销总监', '', '', '', '1.080034044e+015', '121', 'INHE-0768,', '', 'INHE-0768,', '', '', '0', '', '', 'INHENERGY', 'Y');
INSERT INTO `department` VALUES ('129', '华北营销总监', '', '', '', '1.080034044e+021', '151', '', '', '', '', '', '0', '', '', 'INHENERGY', '');
INSERT INTO `department` VALUES ('130', '华东营销总监', '', '', '', '1.080034044e+021', '151', '', '', '', '', '', '0', '', '', 'INHENERGY', '');
INSERT INTO `department` VALUES ('131', '华中营销总监', '', '', '', '1.080034044e+021', '151', '', '', '', '', '', '0', '', '', 'INHENERGY', '');
INSERT INTO `department` VALUES ('132', '大客户营销部', '', '', '', '1.080034044e+024', '127', '', '', '', '', '', '0', '', '', 'INHENERGY', '');
INSERT INTO `department` VALUES ('133', '国际业务部', '', '', '', '1.080034044e+018', '128', 'INHE-0768,', '', 'INHE-0768,', '', '', '0', '', '', 'INHENERGY', '');
INSERT INTO `department` VALUES ('134', '华北营销部', '', '', '', '1.080034044e+024', '129', 'INHE-0825,', '', 'INHE-0768,', '', '', '0', '', '', 'INHENERGY', '');
INSERT INTO `department` VALUES ('135', '华东营销部', '', '', '', '1.080034044e+024', '130', 'INHE-0825,', '', 'INHE-0768,', '', '', '0', '', '', 'INHENERGY', '');
INSERT INTO `department` VALUES ('136', '华中营销部', '', '', '', '1.080034044e+024', '131', 'INHE-0825,', '', 'INHE-0768,', '', '', '0', '', '', 'INHENERGY', '');
INSERT INTO `department` VALUES ('137', '财务部(FD)', '', '', '', '1.080034044e+012', '95', 'IM-0042,', '', 'INHE-0768,', '', '', '0', '', '', 'INHENERGY', '');
INSERT INTO `department` VALUES ('138', '工程部', '', '', '', '1.080034044e+015', '118', 'INHE-0872,', '', 'INHE-0768,', '', '', '0', '', '', 'INHENERGY', '');
INSERT INTO `department` VALUES ('139', '资材部', '', '', '', '1.080034044e+015', '118', 'INHE-0872,', '', 'INHE-0768,', '', '', '0', '', '', 'INHENERGY', '');
INSERT INTO `department` VALUES ('140', '仓储部', '', '', '', '1.080034044e+015', '118', 'INHENERGY-0051,', '', 'INHE-0872,', '', '', '0', '', '', 'INHENERGY', '');
INSERT INTO `department` VALUES ('141', '基建办(Construction Dept.)', '', '', '', '1.080033002e+012', '98', 'INHEGRID-0055,', '', 'meterw,', '', '', '0', '', '', 'INHEGRID', '');
INSERT INTO `department` VALUES ('142', '总经办(GD)', '', '', '', '1003', '2', 'IM-0044,', '', 'meterw,', '', '', '0', '', '', 'INHE', 'Y');
INSERT INTO `department` VALUES ('143', '竞标组', '', '', '', '1014017003', '97', 'INHE-0051,', '', 'meterw,', '', '', '0', '', '', 'INHE', '');
INSERT INTO `department` VALUES ('144', '投标英语组', '', '', '', '1014017005', '97', 'INHE-0662,', '', 'INHE-0051,', '', '', '0', '', '', 'INHE', '');
INSERT INTO `department` VALUES ('146', '市场部', '', '', '', '1.080034044e+012', '95', 'INHE-0768,', '', 'meterw,', '', '', '0', '', '', 'INHENERGY', '');
INSERT INTO `department` VALUES ('147', '品管部', '', '', '', '1.080034044e+012', '95', 'INHE-0872,', '', 'INHE-0768,', '', '', '0', '', '', 'INHENERGY', '');
INSERT INTO `department` VALUES ('148', '国内市场总监(DMD)', '', '', '', '1.080033002e+012', '98', 'szyaj,', '', 'szyaj,', '', '', '0', '', '', 'INHEGRID', 'Y');
INSERT INTO `department` VALUES ('149', '商务跟单(Logistics Specialis)', '', '', '', '1.080033002e+015', '93', 'INHE-0091,', '', 'szyaj,', '', '', '0', '', '', 'INHEGRID', '');
INSERT INTO `department` VALUES ('150', '国内营销总监', '', '', '', '1.080034044e+015', '121', 'INHE-0825,', '', 'INHE-0768,', '', '', '0', '', '', 'INHENERGY', 'Y');
INSERT INTO `department` VALUES ('151', '国内业务部', '', '', '', '1.080034044e+018', '150', 'INHE-0825,', '', 'INHE-0825,', '', '', '0', '', '', 'INHENERGY', '');
INSERT INTO `department` VALUES ('152', '商务部', '', '', '', '1.080034044e+012', '95', '', '', '', '', '', '0', '', '', 'INHENERGY', '');
INSERT INTO `department` VALUES ('153', '重大项目部', '', '', '', '1003010', '142', 'INHE-0015,', '', 'IM-0044,', '', '', '0', '', '', 'INHE', '');
INSERT INTO `department` VALUES ('154', '单相车间', '', '', '', '1.05500103e+015', '26', 'JXINHE-0018,', '', 'INHE-0038,', '', '', '0', '', '', 'JXINHE', '');
INSERT INTO `department` VALUES ('155', '生产部(Production dept.)', '', '', '', '1.080033002e+012', '98', 'INHEGRID-0065,', '', 'szyaj,', '', '', '0', '', '', 'INHEGRID', '');
INSERT INTO `department` VALUES ('156', '人力资源部', '', '', '', '1.055001045e+012', '44', 'JXINHE-4040,', '', 'JXINHE-2860,', '', '', '0', '', '', 'JXINHE', '');
INSERT INTO `department` VALUES ('157', '行政部', '', '', '', '1.055001045e+012', '44', 'JXINHE-2860,', '', 'INHE-0038,', '', '', '0', '', '', 'JXINHE', '');
INSERT INTO `department` VALUES ('158', '山东区区域总监', '', '', '', '1.080034044e+021', '151', '', '', '', '', '', '0', '', '', 'INHENERGY', '');
INSERT INTO `department` VALUES ('159', '山东营销部', '', '', '', '1.080034044e+024', '158', '', '', '', '', '', '0', '', '', 'INHENERGY', '');
INSERT INTO `department` VALUES ('160', '技术顾问(Technical consultan)', '', '', '', '1.080033002e+012', '98', 'szyaj,', '', 'szyaj,', '', '', '0', '', '', 'INHEGRID', '');
INSERT INTO `department` VALUES ('161', '技术支持总监助理', '', '', '', '1023003', '58', 'INHE-0272,', '', 'INHE-0144,', '', '', '0', '', '', 'INHE', '');
INSERT INTO `department` VALUES ('162', '方案组', '', '', '', '1023005003', '10', 'INHE-0272,', 'INHE-0863,', 'INHE-0144,', '', '', '0', '', '', 'INHE', '');
INSERT INTO `department` VALUES ('163', '投标组', '', '', '', '1023005007', '10', 'INHE-0797,', 'INHE-0272,INHE-0863,', 'INHE-0144,', '', '', '0', '', '', 'INHE', '');
INSERT INTO `department` VALUES ('164', '交付组', '', '', '', '1023005009', '10', 'INHE-0749,', 'INHE-0272,INHE-0863,', 'INHE-0144,', '', '', '0', '', '', 'INHE', '');
INSERT INTO `department` VALUES ('165', '客服专员', '', '', '', '1023004', '58', 'INHE-0272,', '', 'INHE-0144,', '', '', '0', '', '', 'INHE', '');
INSERT INTO `department` VALUES ('166', '商务总监', '', '', '', '1014', '2', 'INHE-0051,', '', 'meterw,', '', '', '0', '', '', 'INHE', 'Y');
INSERT INTO `department` VALUES ('167', '投标法语组', '', '', '', '1014017007', '97', 'INHE-0771,', '', 'INHE-0051,', '', '', '0', '', '', 'INHE', '');
INSERT INTO `department` VALUES ('169', '总监助理', '', '', '', '1010002', '59', 'INHE-0036,', '', 'meterw,', '', '', '0', '', '', '', '');
INSERT INTO `department` VALUES ('170', '东非区EAC', '', '', '', '1010006', '59', 'INHE-0036,', '', 'meterw,', '', '', '0', '', '', '', '');
INSERT INTO `department` VALUES ('171', '西非区WAC', '', '', '', '1010008', '59', 'INHE-0036,', '', 'meterw,', '', '', '0', '', '', '', '');
INSERT INTO `department` VALUES ('172', '中非区CAC', '', '', '', '1010010', '59', 'INHE-0036,', '', 'meterw,', '', '', '0', '', '', '', '');
INSERT INTO `department` VALUES ('173', '南非区SADC', '', '', '', '1010013', '59', 'INHE-0036,', '', 'meterw,', '', '', '0', '', '', '', '');
INSERT INTO `department` VALUES ('174', '东北非NEAC', '', '', '', '1010015', '59', 'INHE-0036,', '', 'meterw,', '', '', '0', '', '', '', '');
INSERT INTO `department` VALUES ('175', '南美区S. America', '', '', '', '1010017', '59', 'INHE-0036,', '', 'meterw,', '', '', '0', '', '', '', '');
INSERT INTO `department` VALUES ('176', '中北美区NC America', '', '', '', '1010019', '59', 'INHE-0036,', '', 'meterw,', '', '', '0', '', '', '', '');
