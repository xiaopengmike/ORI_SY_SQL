/*
Navicat MySQL Data Transfer

Source Server         : localhost_3336
Source Server Version : 50525
Source Host           : localhost:3336
Source Database       : TD_OA

Target Server Type    : MYSQL
Target Server Version : 50525
File Encoding         : 65001

Date: 2020-09-30 18:19:25
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for inhe_shipping_notice
-- ----------------------------
DROP TABLE IF EXISTS `inhe_shipping_notice`;
CREATE TABLE `inhe_shipping_notice` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `form_id` varchar(50) DEFAULT NULL,
  `title` varchar(255) DEFAULT NULL,
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
  `dept_id` int(11) DEFAULT NULL,
  `create_user` varchar(255) DEFAULT NULL,
  `create_time` date DEFAULT NULL,
  `remark` text,
  `signature` varchar(255) DEFAULT NULL,
  `approve_user` varchar(255) DEFAULT NULL,
  `approver_time` date DEFAULT NULL,
  `update_user` datetime DEFAULT NULL,
  `update_time` date DEFAULT NULL,
  `user_bu` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=gbk;

-- ----------------------------
-- Records of inhe_shipping_notice
-- ----------------------------
INSERT INTO `inhe_shipping_notice` VALUES ('1', 'fhtz2020090401', 'BAP08/fhtz2020090401/FactorFast (PTY) Ltd', '1', '2', '3', '哈博罗内', '20000473 CA	', '2710-20200811002', 'USD', null, null, '2020-09-04', '空运（CIP,哈博罗内）', '客户承担', '杨政(Echo)', '国际业务二部', '否', null, '55', '李平', '2020-09-04', 'BAP08项目ERP：2211-20200902001，合同号：INHESC2020082401。', '李平2020-09-04 13:50:37', null, null, null, null, 'INHE');
