/*
Navicat MySQL Data Transfer

Source Server         : localhost_3336
Source Server Version : 50525
Source Host           : localhost:3336
Source Database       : TD_OA

Target Server Type    : MYSQL
Target Server Version : 50525
File Encoding         : 65001

Date: 2020-09-30 18:18:49
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for inhe_contract_settle
-- ----------------------------
DROP TABLE IF EXISTS `inhe_contract_settle`;
CREATE TABLE `inhe_contract_settle` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `form_id` varchar(50) DEFAULT NULL,
  `item_id` varchar(255) DEFAULT NULL,
  `content` varchar(255) DEFAULT NULL,
  `attach_name` varchar(255) DEFAULT NULL,
  `user_bu` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=37 DEFAULT CHARSET=gbk;

-- ----------------------------
-- Records of inhe_contract_settle
-- ----------------------------
INSERT INTO `inhe_contract_settle` VALUES ('1', 'INHESC2020081101', '1', '否', null, 'INHE');
INSERT INTO `inhe_contract_settle` VALUES ('2', 'INHESC2020081101', '2', 'CIP,哈博罗内', null, 'INHE');
INSERT INTO `inhe_contract_settle` VALUES ('3', 'INHESC2020081101', '3', '合同金额的100%在签订合同30天内作为预付款在发货前T/T支付。', null, 'INHE');
INSERT INTO `inhe_contract_settle` VALUES ('4', 'INHESC2020081101', '4', '上述货物将在卖方收到买方T/T支付的合同全款后10天内，从卖方工厂发出。', null, 'INHE');
INSERT INTO `inhe_contract_settle` VALUES ('5', 'INHESC2020081101', '5', '从工厂发货后的12个月内', null, 'INHE');
INSERT INTO `inhe_contract_settle` VALUES ('6', 'INHESC2020081101', '6', '否', null, 'INHE');
INSERT INTO `inhe_contract_settle` VALUES ('7', 'INHESC2020081101', '7', '否', null, 'INHE');
INSERT INTO `inhe_contract_settle` VALUES ('8', 'INHESC2020081101', '8', '否', null, 'INHE');
INSERT INTO `inhe_contract_settle` VALUES ('9', 'INHESC2020081101', '9', '02', null, 'INHE');
INSERT INTO `inhe_contract_settle` VALUES ('10', 'INHESC2020081101', '10', '02', null, 'INHE');
INSERT INTO `inhe_contract_settle` VALUES ('11', 'INHESC2020081101', '11', '否', null, 'INHE');
INSERT INTO `inhe_contract_settle` VALUES ('12', 'INHESC2020081101', '12', '无', null, 'INHE');
INSERT INTO `inhe_contract_settle` VALUES ('13', 'INHESC2020082802', '1', 'f1', '', null);
INSERT INTO `inhe_contract_settle` VALUES ('14', 'INHESC2020082802', '2', 'f2', '', null);
INSERT INTO `inhe_contract_settle` VALUES ('15', 'INHESC2020082802', '3', 'f3', '', null);
INSERT INTO `inhe_contract_settle` VALUES ('16', 'INHESC2020082802', '4', 'f4', '', null);
INSERT INTO `inhe_contract_settle` VALUES ('17', 'INHESC2020082802', '5', 'f5', '', null);
INSERT INTO `inhe_contract_settle` VALUES ('18', 'INHESC2020082802', '6', 'f6', '', null);
INSERT INTO `inhe_contract_settle` VALUES ('19', 'INHESC2020082802', '7', 'f7', '', null);
INSERT INTO `inhe_contract_settle` VALUES ('20', 'INHESC2020082802', '8', 'f8', '', null);
INSERT INTO `inhe_contract_settle` VALUES ('21', 'INHESC2020082802', '9', '01', '', null);
INSERT INTO `inhe_contract_settle` VALUES ('22', 'INHESC2020082802', '10', '01', '', null);
INSERT INTO `inhe_contract_settle` VALUES ('23', 'INHESC2020082802', '11', 'f9', '', null);
INSERT INTO `inhe_contract_settle` VALUES ('24', 'INHESC2020082802', '12', 'f10', '', null);
INSERT INTO `inhe_contract_settle` VALUES ('25', 'INHESC2020083101', '1', 'dsa', '', null);
INSERT INTO `inhe_contract_settle` VALUES ('26', 'INHESC2020083101', '2', 'dsad', '', null);
INSERT INTO `inhe_contract_settle` VALUES ('27', 'INHESC2020083101', '3', '2312', '', null);
INSERT INTO `inhe_contract_settle` VALUES ('28', 'INHESC2020083101', '4', 'd', '', null);
INSERT INTO `inhe_contract_settle` VALUES ('29', 'INHESC2020083101', '5', 'asdsa', '', null);
INSERT INTO `inhe_contract_settle` VALUES ('30', 'INHESC2020083101', '6', 'd23', '', null);
INSERT INTO `inhe_contract_settle` VALUES ('31', 'INHESC2020083101', '7', '12', '', null);
INSERT INTO `inhe_contract_settle` VALUES ('32', 'INHESC2020083101', '8', 'dsa', '', null);
INSERT INTO `inhe_contract_settle` VALUES ('33', 'INHESC2020083101', '9', '01', '', null);
INSERT INTO `inhe_contract_settle` VALUES ('34', 'INHESC2020083101', '10', '01', '', null);
INSERT INTO `inhe_contract_settle` VALUES ('35', 'INHESC2020083101', '11', '123', '', null);
INSERT INTO `inhe_contract_settle` VALUES ('36', 'INHESC2020083101', '12', '123', '', null);
