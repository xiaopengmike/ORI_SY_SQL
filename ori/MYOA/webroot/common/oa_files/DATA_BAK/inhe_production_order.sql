/*
Navicat MySQL Data Transfer

Source Server         : localhost_3336
Source Server Version : 50525
Source Host           : localhost:3336
Source Database       : TD_OA

Target Server Type    : MYSQL
Target Server Version : 50525
File Encoding         : 65001

Date: 2020-09-30 18:19:02
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for inhe_production_order
-- ----------------------------
DROP TABLE IF EXISTS `inhe_production_order`;
CREATE TABLE `inhe_production_order` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `form_id` varchar(50) DEFAULT NULL,
  `title` varchar(255) DEFAULT NULL,
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
  `other_explain` varchar(255) DEFAULT NULL,
  `meter_number` int(11) DEFAULT NULL,
  `package_in_sequence` varchar(255) DEFAULT NULL,
  `dept_id` int(11) DEFAULT NULL,
  `create_user` varchar(255) DEFAULT NULL,
  `create_time` datetime DEFAULT NULL,
  `approve_user` varchar(255) DEFAULT NULL,
  `approver_time` datetime DEFAULT NULL,
  `update_user` varchar(255) DEFAULT NULL,
  `update_time` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=gbk;

-- ----------------------------
-- Records of inhe_production_order
-- ----------------------------
INSERT INTO `inhe_production_order` VALUES ('1', 'sctz2020081001', 'sctz2020081001/NEW11/生产通知单', '需结构部重新对胶水进行选型，防止再次出现粘贴不牢固很容易揭开的问题。', 'COMPAGNIE IVOIRIENNE D`ELECTRICITE', 'NEW', 'NEW11', '20000473 CA', 'ERANOVE SA', '2020-09-14', '投标法语组', '吴艳', '是', '其他', '03', '银河电力', '1.发货前需要做VOC验货。2.发货需要提供电表的校准证书，技术规格 3.订单号20000473 CA， Ref：20000128YS / 20000175YS', '34616', '不按照表号顺序包装', null, '吴艳', '2020-08-10 14:38:59', null, null, null, null);
