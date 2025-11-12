/*
Navicat MySQL Data Transfer

Source Server         : localhost_3336
Source Server Version : 50525
Source Host           : localhost:3336
Source Database       : TD_OA

Target Server Type    : MYSQL
Target Server Version : 50525
File Encoding         : 65001

Date: 2020-09-30 18:17:50
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for inhe_customer_info
-- ----------------------------
DROP TABLE IF EXISTS `inhe_customer_info`;
CREATE TABLE `inhe_customer_info` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `form_id` varchar(20) DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  `customer_id` varchar(20) DEFAULT NULL,
  `country` varchar(255) DEFAULT NULL,
  `contact` varchar(255) DEFAULT NULL,
  `phone` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `introduction` text,
  `type` varchar(255) DEFAULT NULL COMMENT '1代理商；2招标方；3买方',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=25 DEFAULT CHARSET=gbk;

-- ----------------------------
-- Records of inhe_customer_info
-- ----------------------------
INSERT INTO `inhe_customer_info` VALUES ('1', 'lxdj202008110001', '附件1', '1', '2', '3', '4', '5', '6', '1');
INSERT INTO `inhe_customer_info` VALUES ('2', 'lxdj202008110001', '7', '8', '9', '1', '2', '3', '4', '2');
INSERT INTO `inhe_customer_info` VALUES ('3', 'lxdj202008110001', '5', '6', '7', '8', '9', '1', '2', '3');
INSERT INTO `inhe_customer_info` VALUES ('19', 'lxdj202008170002', '1', '2', '3', '4', '5', '6', '7', '1');
INSERT INTO `inhe_customer_info` VALUES ('20', 'lxdj202008170002', 'a', 's', 'd', 'f', 'g', 'h', 'h', '2');
INSERT INTO `inhe_customer_info` VALUES ('21', 'lxdj202008170002', 'z', 'x', 'c', 'v', 'b', 'n', 'm', '3');
INSERT INTO `inhe_customer_info` VALUES ('22', 'lxdj2020081801', 'Société PROSALES', 'PROSALES', '加蓬', 'Yves Florian GOKOU', '+241 77 15 64 84', 'prosalesgabon@gmail.com', '加蓬代理，主要进行水电表经销以及相关系统业务，此前已经与SEEG有过电表和系统合作经验', '1');
INSERT INTO `inhe_customer_info` VALUES ('23', 'lxdj2020081801', 'Société d`Energie et d`Eau du Gabon', 'SEEG', '加蓬', null, '+241 01761282', 'communication@seeg-gabon.com', '加蓬能源和水公司（SEEG）创建于1950，自1997年实现起私有化，由法国Veolia水务集团拥有51%的股份，加蓬政府、加蓬私人投资者和公司员工拥有49%的股份，垄断了加蓬的水电供应。', '2');
INSERT INTO `inhe_customer_info` VALUES ('24', 'lxdj2020081801', 'Société d`Energie et d`Eau du Gabon', 'SEEG', '加蓬', null, '+241 01761282', 'communication@seeg-gabon.com', '加蓬能源和水公司（SEEG）创建于1950，自1997年实现起私有化，由法国Veolia水务集团拥有51%的股份，加蓬政府、加蓬私人投资者和公司员工拥有49%的股份，垄断了加蓬的水电供应。', '3');
