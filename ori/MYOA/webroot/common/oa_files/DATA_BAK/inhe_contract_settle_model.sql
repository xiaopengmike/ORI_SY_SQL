/*
Navicat MySQL Data Transfer

Source Server         : localhost_3336
Source Server Version : 50525
Source Host           : localhost:3336
Source Database       : TD_OA

Target Server Type    : MYSQL
Target Server Version : 50525
File Encoding         : 65001

Date: 2020-09-30 18:18:53
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for inhe_contract_settle_model
-- ----------------------------
DROP TABLE IF EXISTS `inhe_contract_settle_model`;
CREATE TABLE `inhe_contract_settle_model` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `item` varchar(255) DEFAULT NULL,
  `have_attach` varchar(255) DEFAULT NULL,
  `if_select` varchar(255) DEFAULT NULL,
  `sort_code` int(5) DEFAULT NULL,
  `user_bu` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=gbk;

-- ----------------------------
-- Records of inhe_contract_settle_model
-- ----------------------------
INSERT INTO `inhe_contract_settle_model` VALUES ('1', '1、单价中是否包含保费和运费？如包含，分别是多少？', null, null, '1', 'INHE');
INSERT INTO `inhe_contract_settle_model` VALUES ('2', '2、价格术语：', null, null, '2', 'INHE');
INSERT INTO `inhe_contract_settle_model` VALUES ('3', '3、结算方式（信用政策）：', null, null, '3', 'INHE');
INSERT INTO `inhe_contract_settle_model` VALUES ('4', '4、发货条件：', null, null, '4', 'INHE');
INSERT INTO `inhe_contract_settle_model` VALUES ('5', '5、产品质保期限：', null, null, '5', 'INHE');
INSERT INTO `inhe_contract_settle_model` VALUES ('6', '6、是否赊销？', null, null, '6', 'INHE');
INSERT INTO `inhe_contract_settle_model` VALUES ('7', '7、是否需要向中信保投保？', null, null, '7', 'INHE');
INSERT INTO `inhe_contract_settle_model` VALUES ('8', '8、交期延迟时，是否产生罚金或违约金？', null, null, '8', 'INHE');
INSERT INTO `inhe_contract_settle_model` VALUES ('9', '9、销售模式：', null, 'sale_model', '9', 'INHE');
INSERT INTO `inhe_contract_settle_model` VALUES ('10', '10、是否为第三方支付：', '1', 'yes_or_no', '10', 'INHE');
INSERT INTO `inhe_contract_settle_model` VALUES ('11', '11、是否需要支付佣金？', null, '', '11', 'INHE');
INSERT INTO `inhe_contract_settle_model` VALUES ('12', '12、佣金计算方法？', null, null, '12', 'INHE');
