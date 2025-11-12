/*
Navicat MySQL Data Transfer

Source Server         : localhost_3336
Source Server Version : 50525
Source Host           : localhost:3336
Source Database       : TD_OA

Target Server Type    : MYSQL
Target Server Version : 50525
File Encoding         : 65001

Date: 2020-09-30 18:19:10
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for inhe_order_detail
-- ----------------------------
DROP TABLE IF EXISTS `inhe_order_detail`;
CREATE TABLE `inhe_order_detail` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `form_id` varchar(50) DEFAULT NULL,
  `table_id` varchar(50) DEFAULT NULL,
  `data_no` int(11) DEFAULT NULL,
  `item_no` varchar(100) DEFAULT NULL,
  `item_id` varchar(255) DEFAULT NULL,
  `item_value` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=104 DEFAULT CHARSET=gbk;

-- ----------------------------
-- Records of inhe_order_detail
-- ----------------------------
INSERT INTO `inhe_order_detail` VALUES ('2', 'sctz2020081001', 'meter_one', '1', '1', 'serial_number', '1');
INSERT INTO `inhe_order_detail` VALUES ('3', 'sctz2020081001', 'meter_one', '1', '2', 'product_name', '单相一体式可更换通讯模块代码预付费电表（RF模块）');
INSERT INTO `inhe_order_detail` VALUES ('4', 'sctz2020081001', 'meter_one', '1', '3', 'internal_model', 'i210');
INSERT INTO `inhe_order_detail` VALUES ('5', 'sctz2020081001', 'meter_one', '1', '4', 'order_number', '33536');
INSERT INTO `inhe_order_detail` VALUES ('6', 'sctz2020081001', 'meter_one', '1', '5', 'voltage', '230');
INSERT INTO `inhe_order_detail` VALUES ('7', 'sctz2020081001', 'meter_one', '1', '6', 'current', '50');
INSERT INTO `inhe_order_detail` VALUES ('8', 'sctz2020081001', 'meter_one', '1', '7', 'frequency', '5（60）');
INSERT INTO `inhe_order_detail` VALUES ('9', 'sctz2020081001', 'meter_one', '1', '8', 'active_precision', '1');
INSERT INTO `inhe_order_detail` VALUES ('10', 'sctz2020081001', 'meter_one', '1', '9', 'reactive_power_accuracy', '2');
INSERT INTO `inhe_order_detail` VALUES ('11', 'sctz2020081001', 'meter_one', '1', '10', 'active_pulse_constant', '1000');
INSERT INTO `inhe_order_detail` VALUES ('12', 'sctz2020081001', 'meter_one', '1', '11', 'reactive_pulse_constant', '1000');
INSERT INTO `inhe_order_detail` VALUES ('13', 'sctz2020081001', 'meter_one', '1', '12', 'display_mode', 'LCD');
INSERT INTO `inhe_order_detail` VALUES ('14', 'sctz2020081001', 'meter_one', '1', '13', 'display_digit', '6+2');
INSERT INTO `inhe_order_detail` VALUES ('15', 'sctz2020081001', 'meter_two', '1', '14', 'serial_number', '1');
INSERT INTO `inhe_order_detail` VALUES ('16', 'sctz2020081001', 'meter_two', '1', '15', 'product_name', '单相一体式可更换通讯模块代码预付费电表（RF模块）');
INSERT INTO `inhe_order_detail` VALUES ('17', 'sctz2020081001', 'meter_two', '1', '16', 'connection_mode', '对称接线');
INSERT INTO `inhe_order_detail` VALUES ('18', 'sctz2020081001', 'meter_two', '1', '17', 'production_mode', '成品');
INSERT INTO `inhe_order_detail` VALUES ('19', 'sctz2020081001', 'meter_two', '1', '18', 'time_zone', 'UTC+0');
INSERT INTO `inhe_order_detail` VALUES ('20', 'sctz2020081001', 'meter_two', '1', '19', 'dst_option', '否');
INSERT INTO `inhe_order_detail` VALUES ('21', 'sctz2020081001', 'meter_two', '1', '20', 'meter_no_range', '研发提供');
INSERT INTO `inhe_order_detail` VALUES ('22', 'sctz2020081001', 'meter_two', '1', '21', 'enclosure_requirement', '带客户LOGO');
INSERT INTO `inhe_order_detail` VALUES ('23', 'sctz2020081001', 'meter_two', '1', '22', 'logo', 'CIE');
INSERT INTO `inhe_order_detail` VALUES ('24', 'sctz2020081001', 'meter_two', '1', '23', 'panel', '研发提供');
INSERT INTO `inhe_order_detail` VALUES ('25', 'sctz2020081001', 'meter_two', '1', '24', 'power_plug_model', '无');
INSERT INTO `inhe_order_detail` VALUES ('26', 'sctz2020081001', 'meter_two', '1', '25', 'card_matching_text', '研发提供');
INSERT INTO `inhe_order_detail` VALUES ('27', 'sctz2020081001', 'meter_two', '1', '26', 'factory_settings_file', '研发提供');
INSERT INTO `inhe_order_detail` VALUES ('28', 'sctz2020081001', 'meter_three', '1', '27', 'serial_number', '1');
INSERT INTO `inhe_order_detail` VALUES ('29', 'sctz2020081001', 'meter_three', '1', '28', 'product_name', '单相一体式可更换通讯模块代码预付费电表（RF模块）');
INSERT INTO `inhe_order_detail` VALUES ('30', 'sctz2020081001', 'meter_three', '1', '29', 'product_specification', '研发提供');
INSERT INTO `inhe_order_detail` VALUES ('31', 'sctz2020081001', 'meter_three', '1', '30', 'operation_manual', '研发提供');
INSERT INTO `inhe_order_detail` VALUES ('32', 'sctz2020081001', 'meter_three', '1', '31', 'carton_design_document', '1');
INSERT INTO `inhe_order_detail` VALUES ('33', 'sctz2020081001', 'meter_three', '1', '32', 'inner_box_design_document', '1');
INSERT INTO `inhe_order_detail` VALUES ('34', 'sctz2020081001', 'meter_three', '1', '33', 'accessories_requirement', '研发提供');
INSERT INTO `inhe_order_detail` VALUES ('35', 'sctz2020081001', 'meter_three', '1', '34', 'test_report', '电子版');
INSERT INTO `inhe_order_detail` VALUES ('36', 'sctz2020081001', 'meter_three', '1', '35', 'play_tray', '是');
INSERT INTO `inhe_order_detail` VALUES ('37', 'sctz2020081001', 'meter_three', '1', '36', 'wooden_cases_packed', '否');
INSERT INTO `inhe_order_detail` VALUES ('38', 'sctz2020081001', 'meter_three', '1', '37', 'related_attachment', '技术规格.zip');
INSERT INTO `inhe_order_detail` VALUES ('39', 'sctz2020081001', 'meter_three', '1', '38', '\r\nother_instruction', null);
INSERT INTO `inhe_order_detail` VALUES ('40', 'sctz2020081001', 'meter_three', '1', '39', 'attachment', null);
INSERT INTO `inhe_order_detail` VALUES ('41', 'sctz2020081001', 'non_meter', '1', '40', 'non_serial_number', '1');
INSERT INTO `inhe_order_detail` VALUES ('42', 'sctz2020081001', 'non_meter', '1', '41', 'non_product_name', '运费');
INSERT INTO `inhe_order_detail` VALUES ('43', 'sctz2020081001', 'non_meter', '1', '42', 'non_order_number', '2');
INSERT INTO `inhe_order_detail` VALUES ('44', 'sctz2020081001', 'non_meter', '1', '43', 'specification_model', '九江-阿比让\r\n1TC 40’HP');
INSERT INTO `inhe_order_detail` VALUES ('45', 'sctz2020081001', 'non_meter', '1', '44', 'unit', 'U');
INSERT INTO `inhe_order_detail` VALUES ('46', 'sctz2020081001', 'non_meter', '1', '45', 'non_attachment', null);
INSERT INTO `inhe_order_detail` VALUES ('65', 'sctz2020081001', 'meter_one', '2', '1', 'serial_number', '2');
INSERT INTO `inhe_order_detail` VALUES ('66', 'sctz2020081001', 'meter_one', '2', '2', 'product_name', '三相一体式可更换通讯模块代码预付费电表（RF模块）');
INSERT INTO `inhe_order_detail` VALUES ('67', 'sctz2020081001', 'meter_one', '2', '3', 'internal_model', 'i310');
INSERT INTO `inhe_order_detail` VALUES ('68', 'sctz2020081001', 'meter_one', '2', '4', 'order_number', '1080');
INSERT INTO `inhe_order_detail` VALUES ('69', 'sctz2020081001', 'meter_one', '2', '5', 'voltage', '3x230/400V');
INSERT INTO `inhe_order_detail` VALUES ('70', 'sctz2020081001', 'meter_one', '2', '6', 'current', '5(100)');
INSERT INTO `inhe_order_detail` VALUES ('71', 'sctz2020081001', 'meter_one', '2', '7', 'frequency', '50');
INSERT INTO `inhe_order_detail` VALUES ('72', 'sctz2020081001', 'meter_one', '2', '8', 'active_precision', '1');
INSERT INTO `inhe_order_detail` VALUES ('73', 'sctz2020081001', 'meter_one', '2', '9', 'reactive_power_accuracy', '2');
INSERT INTO `inhe_order_detail` VALUES ('74', 'sctz2020081001', 'meter_one', '2', '10', 'active_pulse_constant', '1000');
INSERT INTO `inhe_order_detail` VALUES ('75', 'sctz2020081001', 'meter_one', '2', '11', 'reactive_pulse_constant', '1000');
INSERT INTO `inhe_order_detail` VALUES ('76', 'sctz2020081001', 'meter_one', '2', '12', 'display_mode', 'LCD');
INSERT INTO `inhe_order_detail` VALUES ('77', 'sctz2020081001', 'meter_one', '2', '13', 'display_digit', '6+2');
INSERT INTO `inhe_order_detail` VALUES ('78', 'sctz2020081001', 'meter_two', '2', '14', 'serial_number', '2');
INSERT INTO `inhe_order_detail` VALUES ('79', 'sctz2020081001', 'meter_two', '2', '15', 'product_name', '三相一体式可更换通讯模块代码预付费电表（RF模块）');
INSERT INTO `inhe_order_detail` VALUES ('80', 'sctz2020081001', 'meter_two', '2', '16', 'connection_mode', '非对称接线');
INSERT INTO `inhe_order_detail` VALUES ('81', 'sctz2020081001', 'meter_two', '2', '17', 'production_mode', '成品');
INSERT INTO `inhe_order_detail` VALUES ('82', 'sctz2020081001', 'meter_two', '2', '18', 'time_zone', 'UTC+0');
INSERT INTO `inhe_order_detail` VALUES ('83', 'sctz2020081001', 'meter_two', '2', '19', 'dst_option', '否');
INSERT INTO `inhe_order_detail` VALUES ('84', 'sctz2020081001', 'meter_two', '2', '20', 'meter_no_range', '研发提供');
INSERT INTO `inhe_order_detail` VALUES ('85', 'sctz2020081001', 'meter_two', '2', '21', 'enclosure_requirement', '带客户LOGO');
INSERT INTO `inhe_order_detail` VALUES ('86', 'sctz2020081001', 'meter_two', '2', '22', 'logo', 'CIE');
INSERT INTO `inhe_order_detail` VALUES ('87', 'sctz2020081001', 'meter_two', '2', '23', 'panel', '研发提供');
INSERT INTO `inhe_order_detail` VALUES ('88', 'sctz2020081001', 'meter_two', '2', '24', 'power_plug_model', '无');
INSERT INTO `inhe_order_detail` VALUES ('89', 'sctz2020081001', 'meter_two', '2', '25', 'card_matching_text', '研发提供');
INSERT INTO `inhe_order_detail` VALUES ('90', 'sctz2020081001', 'meter_two', '2', '26', 'factory_settings_file', '研发提供');
INSERT INTO `inhe_order_detail` VALUES ('91', 'sctz2020081001', 'meter_three', '2', '27', 'serial_number', '2');
INSERT INTO `inhe_order_detail` VALUES ('92', 'sctz2020081001', 'meter_three', '2', '28', 'product_name', '三相一体式可更换通讯模块代码预付费电表（RF模块）');
INSERT INTO `inhe_order_detail` VALUES ('93', 'sctz2020081001', 'meter_three', '2', '29', 'product_specification', '研发提供');
INSERT INTO `inhe_order_detail` VALUES ('94', 'sctz2020081001', 'meter_three', '2', '30', 'operation_manual', '研发提供');
INSERT INTO `inhe_order_detail` VALUES ('95', 'sctz2020081001', 'meter_three', '2', '31', 'carton_design_document', '1');
INSERT INTO `inhe_order_detail` VALUES ('96', 'sctz2020081001', 'meter_three', '2', '32', 'inner_box_design_document', '1');
INSERT INTO `inhe_order_detail` VALUES ('97', 'sctz2020081001', 'meter_three', '2', '33', 'accessories_requirement', '研发提供');
INSERT INTO `inhe_order_detail` VALUES ('98', 'sctz2020081001', 'meter_three', '2', '34', 'test_report', '电子版');
INSERT INTO `inhe_order_detail` VALUES ('99', 'sctz2020081001', 'meter_three', '2', '35', 'play_tray', '是');
INSERT INTO `inhe_order_detail` VALUES ('100', 'sctz2020081001', 'meter_three', '2', '36', 'wooden_cases_packed', '否');
INSERT INTO `inhe_order_detail` VALUES ('101', 'sctz2020081001', 'meter_three', '2', '37', 'related_attachment', null);
INSERT INTO `inhe_order_detail` VALUES ('102', 'sctz2020081001', 'meter_three', '2', '38', '\r\nother_instruction', null);
INSERT INTO `inhe_order_detail` VALUES ('103', 'sctz2020081001', 'meter_three', '2', '39', 'attachment', null);
