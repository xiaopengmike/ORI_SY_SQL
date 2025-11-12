/*
Navicat MySQL Data Transfer

Source Server         : localhost_3336
Source Server Version : 50525
Source Host           : localhost:3336
Source Database       : TD_OA

Target Server Type    : MYSQL
Target Server Version : 50525
File Encoding         : 65001

Date: 2020-09-30 18:20:55
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for inhe_hr_flow_type
-- ----------------------------
DROP TABLE IF EXISTS `inhe_hr_flow_type`;
CREATE TABLE `inhe_hr_flow_type` (
  `flow_TYPE_ID` varchar(3) NOT NULL,
  `flow_NAME` varchar(25) DEFAULT NULL,
  PRIMARY KEY (`flow_TYPE_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=gbk;

-- ----------------------------
-- Records of inhe_hr_flow_type
-- ----------------------------
INSERT INTO `inhe_hr_flow_type` VALUES ('F01', '面试记录审核流程');
INSERT INTO `inhe_hr_flow_type` VALUES ('F02', '入职登记表流程');
INSERT INTO `inhe_hr_flow_type` VALUES ('F03', '人事异动流程');
INSERT INTO `inhe_hr_flow_type` VALUES ('F04', '工作能力和工作态度绩效考评');
INSERT INTO `inhe_hr_flow_type` VALUES ('F05', 'KPI考核');
INSERT INTO `inhe_hr_flow_type` VALUES ('F06', '离职申请流程');
INSERT INTO `inhe_hr_flow_type` VALUES ('F07', '工作交接流程');
INSERT INTO `inhe_hr_flow_type` VALUES ('F08', '奖惩申请单流程');
INSERT INTO `inhe_hr_flow_type` VALUES ('F09', '合同管理');
INSERT INTO `inhe_hr_flow_type` VALUES ('F10', '薪资管理');
INSERT INTO `inhe_hr_flow_type` VALUES ('F11', '人事档案');
