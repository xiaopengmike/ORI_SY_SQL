/*
Navicat MySQL Data Transfer

Source Server         : localhost_3336
Source Server Version : 50525
Source Host           : localhost:3336
Source Database       : TD_OA

Target Server Type    : MYSQL
Target Server Version : 50525
File Encoding         : 65001

Date: 2020-09-30 18:18:36
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for inhe_product_detail
-- ----------------------------
DROP TABLE IF EXISTS `inhe_product_detail`;
CREATE TABLE `inhe_product_detail` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `form_id` varchar(50) DEFAULT NULL,
  `serial_number` int(11) DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  `specification` varchar(255) DEFAULT NULL,
  `unit` varchar(255) DEFAULT NULL,
  `number` int(11) DEFAULT NULL,
  `price` decimal(30,4) DEFAULT NULL,
  `total_amount` decimal(30,4) DEFAULT NULL,
  `tech_requirement` varchar(255) DEFAULT NULL,
  `type` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=gbk;

-- ----------------------------
-- Records of inhe_product_detail
-- ----------------------------
INSERT INTO `inhe_product_detail` VALUES ('1', 'INHESC2020081101', '1', 'DIN导轨安装单相两线分体式（PLC载波通信）代码智能电表 ', '50Hz, 230V, 5(100) A ', '套', '400', '52.0000', '20800.0000', 'EOI翻单', 'electric');
INSERT INTO `inhe_product_detail` VALUES ('2', 'INHESC2020081101', '1', 'LoRa Outdoor Wireless Gateway', null, '套  ', '1', '1200.0000', '1200.0000', null, 'other');
INSERT INTO `inhe_product_detail` VALUES ('3', 'INHESC2020081101', '2', ' 安美通集抄模块（不需配天线） APC910M，4.5-6V，432-434MHz，不需配天线  ', 'APC910M，4.5-6V，432-434MHz，不需配天线', '个', '380', '53.7000', '20406.0000', '20406', 'other');
INSERT INTO `inhe_product_detail` VALUES ('4', 'INHESC2020081101', '1', '在SMART AMI HES中集中器与开发-海兴-威盛兼容服务', '*集中器与电表兼容培训与协助 \r\n*实现兼容的开发与准备工作 \r\n*实现开发，测试，以及集中器与开发电表的兼容 \r\n*实现开发，测试，以及集中器与海兴电表的兼容 \r\n*实现开发，测试，以及集中器与威盛电表的兼容', 'unit', '1', '186400.0000', '186400.0000', null, 'soft');
INSERT INTO `inhe_product_detail` VALUES ('5', 'INHESC2020082802', '1', '11', '12', '13', '14', '15.0000', '16.0000', '17', 'electric');
INSERT INTO `inhe_product_detail` VALUES ('6', 'INHESC2020082802', '1', '21', '22', '23', '24', '25.0000', '26.0000', '27', 'other');
INSERT INTO `inhe_product_detail` VALUES ('7', 'INHESC2020082802', '1', '31', '32', '33', '34', '35.0000', '36.0000', '', 'soft');
INSERT INTO `inhe_product_detail` VALUES ('8', 'INHESC2020083101', '1', '2', '3', '4', '5', '6.0000', '7.0000', '8', 'electric');
INSERT INTO `inhe_product_detail` VALUES ('9', 'INHESC2020083101', '1', '22', '33', '44', '55', '66.0000', '77.0000', '88', 'other');
INSERT INTO `inhe_product_detail` VALUES ('10', 'INHESC2020083101', '1', '222', '333', '444', '555', '666.0000', '777.0000', '', 'soft');
