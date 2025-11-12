/*
Navicat MySQL Data Transfer

Source Server         : localhost_3336
Source Server Version : 50525
Source Host           : localhost:3336
Source Database       : TD_OA

Target Server Type    : MYSQL
Target Server Version : 50525
File Encoding         : 65001

Date: 2020-09-30 18:19:32
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for inhe_shipping_detail
-- ----------------------------
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
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=gbk;

-- ----------------------------
-- Records of inhe_shipping_detail
-- ----------------------------
INSERT INTO `inhe_shipping_detail` VALUES ('1', 'fhtz2020090401', '1', '单相一体式可更换通讯模块代码预付费电表（RF模块）', '60A', '33536', null, null, 'NEW11', 'NEW', '20000473 CA', 'electric');
INSERT INTO `inhe_shipping_detail` VALUES ('2', 'fhtz2020090401', '2', '三相一体式可更换通讯模块代码预付费电表（RF模块）', '100A', '1080', null, null, 'NEW11', 'NEW', '20000473 CA', 'electric');
INSERT INTO `inhe_shipping_detail` VALUES ('3', 'fhtz2020090401', '1', '运费', '九江-阿比让1TC 40’HP', '2', null, null, 'NEW11', 'NEW', '20000473 CA', 'other');
INSERT INTO `inhe_shipping_detail` VALUES ('4', 'fhtz2020090401', '1', '软件服务', '1', '1', null, null, 'NEW11', 'NEW', '20000473 CA', 'soft');
