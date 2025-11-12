/*
Navicat MySQL Data Transfer

Source Server         : localhost_3336
Source Server Version : 50525
Source Host           : localhost:3336
Source Database       : TD_OA

Target Server Type    : MYSQL
Target Server Version : 50525
File Encoding         : 65001

Date: 2020-09-30 18:18:04
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for inhe_project_detail
-- ----------------------------
DROP TABLE IF EXISTS `inhe_project_detail`;
CREATE TABLE `inhe_project_detail` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `form_id` varchar(20) DEFAULT NULL,
  `serial_number` int(5) DEFAULT NULL,
  `product` varchar(255) DEFAULT NULL,
  `price` decimal(10,2) DEFAULT NULL,
  `first_price` decimal(10,2) DEFAULT NULL,
  `standard` varchar(255) DEFAULT NULL,
  `number` int(11) DEFAULT NULL,
  `attach_name` varchar(255) DEFAULT NULL,
  `attach_type` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=gbk;

-- ----------------------------
-- Records of inhe_project_detail
-- ----------------------------
INSERT INTO `inhe_project_detail` VALUES ('7', 'lxdj202008170002', null, '1', '2.00', '3.00', '4', '5', null, null);
INSERT INTO `inhe_project_detail` VALUES ('8', 'lxdj2020081801', '1', 'AMI系统', '50.00', '40.00', '无', '4000', '{79EA887C1DC5437D8BF348EC86CA3083B6D2E2F5417D4A5DABC46AAC206790E4}_SPM项目标书.zip', null);
INSERT INTO `inhe_project_detail` VALUES ('9', 'lxdj2020081801', '2', 'LOT2: 用于变电站的中低压计量产品 ', '700.00', '500.00', '无', '60', null, null);
