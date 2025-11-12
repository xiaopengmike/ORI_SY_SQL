/*
Navicat MySQL Data Transfer

Source Server         : localhost_3336
Source Server Version : 50525
Source Host           : localhost:3336
Source Database       : TD_OA

Target Server Type    : MYSQL
Target Server Version : 50525
File Encoding         : 65001

Date: 2020-09-30 18:20:31
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for inhe_system_admin
-- ----------------------------
DROP TABLE IF EXISTS `inhe_system_admin`;
CREATE TABLE `inhe_system_admin` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '主键ID',
  `user_id` varchar(20) NOT NULL COMMENT '用户ID',
  `dept_id` varchar(100) DEFAULT NULL,
  `user_bu` varchar(100) DEFAULT NULL,
  `dept_ids` text,
  `user_ids` text,
  `is_admin` int(1) DEFAULT NULL COMMENT '是否系统管理员角色权限',
  `system_admin` int(1) DEFAULT NULL,
  `type` varchar(20) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=118 DEFAULT CHARSET=gbk;

-- ----------------------------
-- Records of inhe_system_admin
-- ----------------------------
INSERT INTO `inhe_system_admin` VALUES ('1', 'ysr', null, 'INHE', null, null, '1', null, 'F05');
INSERT INTO `inhe_system_admin` VALUES ('2', 'admin1', null, 'INHE,INHENERGY', null, null, '1', null, 'F05');
INSERT INTO `inhe_system_admin` VALUES ('3', 'fxs', null, 'INHE', null, null, '1', null, 'F05');
INSERT INTO `inhe_system_admin` VALUES ('4', 'INHE-0475', null, 'INHE,INHENERGY', null, null, '1', null, 'F05');
INSERT INTO `inhe_system_admin` VALUES ('5', 'INHE-0808', null, 'INHENERGY', null, null, '1', null, 'F05');
INSERT INTO `inhe_system_admin` VALUES ('6', 'INHEGRID-0018', null, 'INHEGRID', null, null, '1', null, 'F05');
INSERT INTO `inhe_system_admin` VALUES ('7', '', null, null, null, null, '1', null, 'kh_new');
INSERT INTO `inhe_system_admin` VALUES ('8', '', null, '', null, null, '1', null, 'kh_sp');
INSERT INTO `inhe_system_admin` VALUES ('9', 'INHENERGY-0042', null, 'INHENERGY', null, null, '1', null, 'F05');
INSERT INTO `inhe_system_admin` VALUES ('10', '', null, null, null, null, '1', null, 'jdkh_new');
INSERT INTO `inhe_system_admin` VALUES ('11', 'admin1', null, 'INHE,INHEGRID,INHENERGY,WITLINK,JXINHE', null, null, '1', null, 'F01');
INSERT INTO `inhe_system_admin` VALUES ('12', 'glp', null, 'INHE,INHEGRID,INHENERGY,WITLINK,JXINHE', null, null, '1', null, 'F03');
INSERT INTO `inhe_system_admin` VALUES ('13', 'admin1', null, 'INHE,INHEGRID,INHENERGY,WITLINK,JXINHE', null, null, '1', null, 'F02');
INSERT INTO `inhe_system_admin` VALUES ('14', 'admin1', null, 'INHE,INHEGRID,INHENERGY,WITLINK,JXINHE', null, null, '1', null, 'F10');
INSERT INTO `inhe_system_admin` VALUES ('15', 'admin1', null, 'INHE,INHEGRID,INHENERGY,WITLINK,JXINHE', null, null, '1', null, 'F08');
INSERT INTO `inhe_system_admin` VALUES ('16', 'admin', null, 'INHE,INHEGRID,INHENERGY,WITLINK,JXINHE', null, null, '1', null, 'F10');
INSERT INTO `inhe_system_admin` VALUES ('17', 'admin1', null, 'INHE,INHEGRID,INHENERGY,WITLINK,JXINHE', null, null, '1', null, 'F09');
INSERT INTO `inhe_system_admin` VALUES ('18', 'INHEGRID-0039', null, 'INHEGRID', null, null, '1', null, 'F04');
INSERT INTO `inhe_system_admin` VALUES ('19', 'admin1', null, 'INHE,INHEGRID,INHENERGY,WITLINK,JXINHE', null, null, '1', null, 'F03');
INSERT INTO `inhe_system_admin` VALUES ('20', 'INHE-0231', null, 'WITLINK', null, null, '1', null, 'F10');
INSERT INTO `inhe_system_admin` VALUES ('21', 'INHE-0284', null, 'INHE,WITLINK', null, null, '1', null, 'F10');
INSERT INTO `inhe_system_admin` VALUES ('22', 'INHE-0359', null, 'INHE,WITLINK,', null, null, '1', null, 'F03');
INSERT INTO `inhe_system_admin` VALUES ('23', 'INHE-0900', null, 'INHE,INHEGRID,INHENERGY,WITLINK,JXINHE', null, null, '1', null, 'F05');
INSERT INTO `inhe_system_admin` VALUES ('24', 'INHE-0900', null, 'INHE,WITLINK', null, null, '1', null, 'F04');
INSERT INTO `inhe_system_admin` VALUES ('25', 'IM-0015', null, 'INHE,INHEGRID,INHENERGY,WITLINK,JXINHE', null, null, '1', null, 'F01');
INSERT INTO `inhe_system_admin` VALUES ('26', 'admin1', null, 'INHE,INHEGRID,INHENERGY,WITLINK,JXINHE', null, null, '1', null, 'dept_select');
INSERT INTO `inhe_system_admin` VALUES ('27', 'meterw', null, 'INHE,INHEGRID,INHENERGY,WITLINK,JXINHE', null, null, '1', null, 'dept_select');
INSERT INTO `inhe_system_admin` VALUES ('28', 'IM-0015', null, 'INHE,INHEGRID,INHENERGY,WITLINK,JXINHE', null, null, '1', null, 'dept_select');
INSERT INTO `inhe_system_admin` VALUES ('29', 'INHE-0808', null, 'INHE,INHENERGY', null, null, '1', null, 'dept_select');
INSERT INTO `inhe_system_admin` VALUES ('30', 'INHE-0692', null, 'INHE,WITLINK', null, null, '1', null, 'dept_select');
INSERT INTO `inhe_system_admin` VALUES ('31', 'INHE-0692', null, 'INHE,INHEGRID,INHENERGY,WITLINK,JXINHE', null, null, '1', null, 'F02');
INSERT INTO `inhe_system_admin` VALUES ('32', 'INHE-0692', null, 'INHENERGY', null, null, '1', null, 'F01');
INSERT INTO `inhe_system_admin` VALUES ('33', 'WL-0008', null, 'WITLINK', null, null, '1', null, 'F05');
INSERT INTO `inhe_system_admin` VALUES ('34', 'WL-0038', null, 'WITLINK', null, null, '1', null, 'F04');
INSERT INTO `inhe_system_admin` VALUES ('35', 'INHE-0900', null, 'INHE,WITLINK', null, null, '1', null, 'F03');
INSERT INTO `inhe_system_admin` VALUES ('36', 'INHE-0900', null, 'INHE,WITLINK', null, null, '1', null, 'F02');
INSERT INTO `inhe_system_admin` VALUES ('37', 'INHE-0900', null, 'INHE,WITLINK', null, null, '1', null, 'dept_select');
INSERT INTO `inhe_system_admin` VALUES ('38', 'JXINHE-1753', null, 'JXINHE', null, null, '1', null, 'dept_select');
INSERT INTO `inhe_system_admin` VALUES ('39', 'meterw', null, 'INHE,INHEGRID,INHENERGY,WITLINK,JXINHE', null, null, '1', null, 'F03');
INSERT INTO `inhe_system_admin` VALUES ('40', 'meterw', null, 'INHE,INHEGRID,INHENERGY,WITLINK,JXINHE', null, null, '1', null, 'F01');
INSERT INTO `inhe_system_admin` VALUES ('41', 'meterw', null, 'INHE,INHEGRID,INHENERGY,WITLINK,JXINHE', null, null, '1', null, 'F02');
INSERT INTO `inhe_system_admin` VALUES ('43', 'meterw', null, 'INHE,INHEGRID,INHENERGY,WITLINK,JXINHE', null, null, '1', null, 'F04');
INSERT INTO `inhe_system_admin` VALUES ('44', 'meterw', null, 'INHE,INHEGRID,INHENERGY,WITLINK,JXINHE', null, null, '1', null, 'F05');
INSERT INTO `inhe_system_admin` VALUES ('45', 'meterw', null, 'INHE,INHEGRID,INHENERGY,WITLINK,JXINHE', null, null, '1', null, 'F06');
INSERT INTO `inhe_system_admin` VALUES ('46', 'meterw', null, 'INHE,INHEGRID,INHENERGY,WITLINK,JXINHE', null, null, '1', null, 'F07');
INSERT INTO `inhe_system_admin` VALUES ('47', 'meterw', null, 'INHE,INHEGRID,INHENERGY,WITLINK,JXINHE', null, null, '1', null, 'F08');
INSERT INTO `inhe_system_admin` VALUES ('48', 'meterw', null, 'INHE,INHEGRID,INHENERGY,WITLINK,JXINHE', null, null, '1', null, 'F09');
INSERT INTO `inhe_system_admin` VALUES ('49', 'INHE-0900', null, 'INHE,INHEGRID,INHENERGY,WITLINK,JXINHE', null, null, '1', null, 'F10');
INSERT INTO `inhe_system_admin` VALUES ('50', 'INHE-0900', null, null, null, null, '1', null, 'control_library');
INSERT INTO `inhe_system_admin` VALUES ('51', 'INHE-0900', null, 'INHE,WITLINK', null, null, '1', null, 'F08');
INSERT INTO `inhe_system_admin` VALUES ('52', 'INHE-0900', null, 'INHE', null, null, '1', null, 'F11');
INSERT INTO `inhe_system_admin` VALUES ('53', 'INHE-0450', null, 'CORP', null, null, '1', null, 'F11');
INSERT INTO `inhe_system_admin` VALUES ('54', 'IM-0015', null, 'CORP', null, null, '1', null, 'F11');
INSERT INTO `inhe_system_admin` VALUES ('55', 'meterw', null, 'CORP', null, null, '1', null, 'F11');
INSERT INTO `inhe_system_admin` VALUES ('56', 'WL-0042', null, 'WITLINK', null, null, '1', null, 'F11');
INSERT INTO `inhe_system_admin` VALUES ('57', 'admin', null, 'CORP', null, null, '1', null, 'F11');
INSERT INTO `inhe_system_admin` VALUES ('58', 'INHE-0359', null, 'INHE', null, null, '1', null, 'F11');
INSERT INTO `inhe_system_admin` VALUES ('59', 'INHE-0692', null, 'INHE,WITLINK', null, null, '1', null, 'F11');
INSERT INTO `inhe_system_admin` VALUES ('60', 'INHENERGY-0064', null, 'INHENERGY', null, null, '1', null, 'F11');
INSERT INTO `inhe_system_admin` VALUES ('61', 'INHE-0768', null, 'INHENERGY', null, null, '1', null, 'F11');
INSERT INTO `inhe_system_admin` VALUES ('62', 'INHEGRID-0018', null, 'INHEGRID', null, null, '1', null, 'F11');
INSERT INTO `inhe_system_admin` VALUES ('63', 'szyaj', null, 'INHEGRID', null, null, '1', null, 'F11');
INSERT INTO `inhe_system_admin` VALUES ('64', 'INHE-0038', null, 'JXINHE', null, null, '1', null, 'F11');
INSERT INTO `inhe_system_admin` VALUES ('65', 'JXINHE-2860', null, 'JXINHE', null, null, '1', null, 'F11');
INSERT INTO `inhe_system_admin` VALUES ('66', 'JXINHE-4040', null, 'JXINHE', null, null, '1', null, 'F11');
INSERT INTO `inhe_system_admin` VALUES ('67', 'JXINHE-1753', null, 'JXINHE', null, null, '1', null, 'F11');
INSERT INTO `inhe_system_admin` VALUES ('68', 'INHE-0900', null, 'INHE,INHEGRID', null, null, '1', null, 'customer_data');
INSERT INTO `inhe_system_admin` VALUES ('69', 'INHE-0900', null, null, '02', null, '1', '1', 'contract_protocol');
INSERT INTO `inhe_system_admin` VALUES ('70', 'INHE-0900', '', '', '02', '', '1', '1', 'authorization_letter');
INSERT INTO `inhe_system_admin` VALUES ('71', 'INHE-0900', '', '', '02', '', '1', '1', 'sales_contract');
INSERT INTO `inhe_system_admin` VALUES ('103', 'INHE-0674', null, null, null, null, '1', '1', 'contract_protocol');
INSERT INTO `inhe_system_admin` VALUES ('104', 'INHE-0674', null, null, null, null, '1', '1', 'sales_contract');
INSERT INTO `inhe_system_admin` VALUES ('105', 'INHE-0674', null, null, null, null, '1', '1', 'authorization_letter');
INSERT INTO `inhe_system_admin` VALUES ('107', 'INHE-0036', null, null, '02,04', null, '1', '0', 'sales_contract');
INSERT INTO `inhe_system_admin` VALUES ('108', 'INHE-0036', null, null, '02,04', null, '1', '0', 'authorization_letter');
INSERT INTO `inhe_system_admin` VALUES ('109', 'INHE-0036', '', '', '02,04', '', '1', '0', 'contract_protocol');
INSERT INTO `inhe_system_admin` VALUES ('110', 'INHE-0578', '169', 'INHE', '01,02', '', '1', '0', 'contract_protocol');
INSERT INTO `inhe_system_admin` VALUES ('111', 'INHE-0578', '169', 'INHE', '02,03', '', '1', '0', 'contract_protocol');
INSERT INTO `inhe_system_admin` VALUES ('112', 'INHE-0654', '175', 'INHE', '02,03', '', '1', '0', 'sales_contract');
INSERT INTO `inhe_system_admin` VALUES ('113', 'INHE-0843', '176', 'INHE', '02,03', '', '1', '0', 'authorization_letter');
INSERT INTO `inhe_system_admin` VALUES ('114', 'INHE-0900', null, null, null, null, '1', '1', 'guarantee_letter');
INSERT INTO `inhe_system_admin` VALUES ('115', 'INHE-0843', '176', 'INHE', '01,02,03', '', '1', '0', 'guarantee_letter');
INSERT INTO `inhe_system_admin` VALUES ('116', 'INHE-0036', '59', 'INHE', '01,02', '', '1', '0', 'guarantee_letter');
INSERT INTO `inhe_system_admin` VALUES ('117', 'INHE-0260', '74', 'INHE', '01,02,03', '', '1', '0', 'contract_protocol');
