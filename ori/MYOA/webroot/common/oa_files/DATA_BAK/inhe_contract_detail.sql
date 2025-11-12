/*
Navicat MySQL Data Transfer

Source Server         : localhost_3336
Source Server Version : 50525
Source Host           : localhost:3336
Source Database       : TD_OA

Target Server Type    : MYSQL
Target Server Version : 50525
File Encoding         : 65001

Date: 2020-09-30 18:18:42
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for inhe_contract_detail
-- ----------------------------
DROP TABLE IF EXISTS `inhe_contract_detail`;
CREATE TABLE `inhe_contract_detail` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `form_id` varchar(50) DEFAULT NULL,
  `total_amount` varchar(255) DEFAULT NULL,
  `create_user` varchar(255) DEFAULT NULL,
  `dept_id` int(11) DEFAULT NULL,
  `business_attribution` varchar(255) DEFAULT NULL,
  `form_date` date DEFAULT NULL,
  `transport_mode` varchar(255) DEFAULT NULL,
  `freight_bearing` varchar(255) DEFAULT NULL,
  `delivery_place` varchar(255) DEFAULT NULL,
  `shipping_address` varchar(255) DEFAULT NULL,
  `package_requirement` varchar(255) DEFAULT NULL,
  `acceptance_criteria` varchar(255) DEFAULT NULL,
  `have_entrepot_trade` varchar(255) DEFAULT NULL,
  `involved_product` varchar(255) DEFAULT NULL,
  `quote_source` varchar(255) DEFAULT NULL,
  `history_project_code` varchar(255) DEFAULT NULL,
  `have_tech_specification` varchar(255) DEFAULT NULL,
  `have_email_approval` varchar(255) DEFAULT NULL,
  `email_name` varchar(255) DEFAULT NULL,
  `product_model` varchar(255) DEFAULT NULL,
  `have_inventory` varchar(255) DEFAULT NULL,
  `functional_requirement` varchar(255) DEFAULT NULL,
  `special_requirement` varchar(255) DEFAULT NULL,
  `change_explain` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=gbk;

-- ----------------------------
-- Records of inhe_contract_detail
-- ----------------------------
INSERT INTO `inhe_contract_detail` VALUES ('1', 'INHESC2020081101', '21605.18', 'INHE-0066', '60', '李忠鹏', '2020-08-19', '空运（CIP,哈博罗内）', '客户承担', '哈博罗内', '江西工厂', '按照EOI项目标准', '按照EOI 项目标准', '01', '涉及产品', '01', '历史项目代号', '02', '01', 'BAP03项目报价申请', 'DN15', '01', '无', '无', '无');
INSERT INTO `inhe_contract_detail` VALUES ('3', 'INHESC2020082801', '1', 'INHE-0900', '17', '2', '2020-08-28', '3', '4', '5', '6', '7', '8', '01', '9', '02', '1', '01', '01', '3', '2', '01', '3', '4', '5');
INSERT INTO `inhe_contract_detail` VALUES ('4', 'INHESC2020082802', '', 'INHE-0900', '17', 'user', '2020-08-28', 'transport', 'fee', 'address1', 'address2', 'requirement', 'bz', '01', 'pro', '01', 'code', '01', '01', 'g11', 'size12', '01', 'r111', 'r222', 'r333');
INSERT INTO `inhe_contract_detail` VALUES ('5', 'INHESC2020083101', 'dsad', 'INHE-0900', '17', 'dsad', '2020-08-31', 'dsa', 'adsa', 'dasd', 'dsa', 'ads', 'dasd', '01', 'sad', '01', 'asdas', '01', '01', '312', 'das', '01', 'dsa', '312', '23');
