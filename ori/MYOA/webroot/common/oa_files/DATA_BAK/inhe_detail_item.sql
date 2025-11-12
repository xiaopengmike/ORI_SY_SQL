/*
Navicat MySQL Data Transfer

Source Server         : localhost_3336
Source Server Version : 50525
Source Host           : localhost:3336
Source Database       : TD_OA

Target Server Type    : MYSQL
Target Server Version : 50525
File Encoding         : 65001

Date: 2020-09-30 18:19:17
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for inhe_detail_item
-- ----------------------------
DROP TABLE IF EXISTS `inhe_detail_item`;
CREATE TABLE `inhe_detail_item` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `module_id` varchar(50) DEFAULT NULL,
  `module_name` varchar(255) DEFAULT NULL,
  `table_id` varchar(100) DEFAULT NULL,
  `table_name` varchar(255) DEFAULT NULL,
  `item_id` varchar(50) DEFAULT NULL,
  `item_name` varchar(255) DEFAULT NULL,
  `have_attach` varchar(255) DEFAULT NULL,
  `if_select` varchar(255) DEFAULT NULL,
  `sort_code` int(11) DEFAULT NULL,
  `user_bu` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=46 DEFAULT CHARSET=gbk;

-- ----------------------------
-- Records of inhe_detail_item
-- ----------------------------
INSERT INTO `inhe_detail_item` VALUES ('1', 'production_order', '生产通知单', 'meter_one', '电表类明细', 'serial_number', '序号', null, null, '1', 'INHE');
INSERT INTO `inhe_detail_item` VALUES ('2', 'production_order', '生产通知单', 'meter_one', '电表类明细', 'product_name', '产品名称', null, null, '2', 'INHE');
INSERT INTO `inhe_detail_item` VALUES ('3', 'production_order', '生产通知单', 'meter_one', '电表类明细', 'internal_model', '内部型号', null, null, '3', 'INHE');
INSERT INTO `inhe_detail_item` VALUES ('4', 'production_order', '生产通知单', 'meter_one', '电表类明细', 'order_number', '订单数量', null, null, '4', 'INHE');
INSERT INTO `inhe_detail_item` VALUES ('5', 'production_order', '生产通知单', 'meter_one', '电表类明细', 'voltage', '电压', null, null, '5', 'INHE');
INSERT INTO `inhe_detail_item` VALUES ('6', 'production_order', '生产通知单', 'meter_one', '电表类明细', 'current', '电流', null, null, '6', 'INHE');
INSERT INTO `inhe_detail_item` VALUES ('7', 'production_order', '生产通知单', 'meter_one', '电表类明细', 'frequency', '频率', null, null, '7', 'INHE');
INSERT INTO `inhe_detail_item` VALUES ('8', 'production_order', '生产通知单', 'meter_one', '电表类明细', 'active_precision', '有功精度', null, null, '8', 'INHE');
INSERT INTO `inhe_detail_item` VALUES ('9', 'production_order', '生产通知单', 'meter_one', '电表类明细', 'reactive_power_accuracy', '无功精度', null, null, '9', 'INHE');
INSERT INTO `inhe_detail_item` VALUES ('10', 'production_order', '生产通知单', 'meter_one', '电表类明细', 'active_pulse_constant', '有功脉冲常数', null, null, '10', 'INHE');
INSERT INTO `inhe_detail_item` VALUES ('11', 'production_order', '生产通知单', 'meter_one', '电表类明细', 'reactive_pulse_constant', '无功脉冲常数', null, null, '11', 'INHE');
INSERT INTO `inhe_detail_item` VALUES ('12', 'production_order', '生产通知单', 'meter_one', '电表类明细', 'display_mode', '显示方式', null, 'display_mode', '12', 'INHE');
INSERT INTO `inhe_detail_item` VALUES ('13', 'production_order', '生产通知单', 'meter_one', '电表类明细', 'display_digit', '显示位数', null, null, '13', 'INHE');
INSERT INTO `inhe_detail_item` VALUES ('14', 'production_order', '生产通知单', 'meter_two', '电表明细表2', 'serial_number', '序号', null, null, '1', 'INHE');
INSERT INTO `inhe_detail_item` VALUES ('15', 'production_order', '生产通知单', 'meter_two', '电表明细表2', 'product_name', '产品名称', null, null, '2', 'INHE');
INSERT INTO `inhe_detail_item` VALUES ('16', 'production_order', '生产通知单', 'meter_two', '电表明细表2', 'connection_mode', '接线方式', null, null, '3', 'INHE');
INSERT INTO `inhe_detail_item` VALUES ('17', 'production_order', '生产通知单', 'meter_two', '电表明细表2', 'production_mode', '生产方式', null, 'production_mode', '4', 'INHE');
INSERT INTO `inhe_detail_item` VALUES ('18', 'production_order', '生产通知单', 'meter_two', '电表明细表2', 'time_zone', '时区', null, null, '5', 'INHE');
INSERT INTO `inhe_detail_item` VALUES ('19', 'production_order', '生产通知单', 'meter_two', '电表明细表2', 'dst_option', '是否使用夏令时', null, 'yer_or_no', '6', 'INHE');
INSERT INTO `inhe_detail_item` VALUES ('20', 'production_order', '生产通知单', 'meter_two', '电表明细表2', 'meter_no_range', '表号范围', null, null, '7', 'INHE');
INSERT INTO `inhe_detail_item` VALUES ('21', 'production_order', '生产通知单', 'meter_two', '电表明细表2', 'enclosure_requirement', '外壳要求', null, null, '8', 'INHE');
INSERT INTO `inhe_detail_item` VALUES ('22', 'production_order', '生产通知单', 'meter_two', '电表明细表2', 'logo', 'LOGO', null, null, '9', 'INHE');
INSERT INTO `inhe_detail_item` VALUES ('23', 'production_order', '生产通知单', 'meter_two', '电表明细表2', 'panel', '面板', null, null, '10', 'INHE');
INSERT INTO `inhe_detail_item` VALUES ('24', 'production_order', '生产通知单', 'meter_two', '电表明细表2', 'power_plug_model', 'CIP电源插头型号', null, null, '11', 'INHE');
INSERT INTO `inhe_detail_item` VALUES ('25', 'production_order', '生产通知单', 'meter_two', '电表明细表2', 'card_matching_text', '配卡文字', null, null, '12', 'INHE');
INSERT INTO `inhe_detail_item` VALUES ('26', 'production_order', '生产通知单', 'meter_two', '电表明细表2', 'factory_settings_file', '出厂设置文件', null, null, '13', 'INHE');
INSERT INTO `inhe_detail_item` VALUES ('27', 'production_order', '生产通知单', 'meter_three', '电表明细表3', 'serial_number', '序号', null, null, '1', 'INHE');
INSERT INTO `inhe_detail_item` VALUES ('28', 'production_order', '生产通知单', 'meter_three', '电表明细表3', 'product_name', '产品名称', null, null, '2', 'INHE');
INSERT INTO `inhe_detail_item` VALUES ('29', 'production_order', '生产通知单', 'meter_three', '电表明细表3', 'product_specification', '产品说明书', null, null, '3', 'INHE');
INSERT INTO `inhe_detail_item` VALUES ('30', 'production_order', '生产通知单', 'meter_three', '电表明细表3', 'operation_manual', '操作手册', null, null, '4', 'INHE');
INSERT INTO `inhe_detail_item` VALUES ('31', 'production_order', '生产通知单', 'meter_three', '电表明细表3', 'carton_design_document', '外箱设计文件', null, null, '5', 'INHE');
INSERT INTO `inhe_detail_item` VALUES ('32', 'production_order', '生产通知单', 'meter_three', '电表明细表3', 'inner_box_design_document', '内盒设计文件', null, null, '6', 'INHE');
INSERT INTO `inhe_detail_item` VALUES ('33', 'production_order', '生产通知单', 'meter_three', '电表明细表3', 'accessories_requirement', '配件要求', null, null, '7', 'INHE');
INSERT INTO `inhe_detail_item` VALUES ('34', 'production_order', '生产通知单', 'meter_three', '电表明细表3', 'test_report', '测试报告', null, 'test_report', '8', 'INHE');
INSERT INTO `inhe_detail_item` VALUES ('35', 'production_order', '生产通知单', 'meter_three', '电表明细表3', 'play_tray', '是否打托盘', null, 'yer_or_no', '9', 'INHE');
INSERT INTO `inhe_detail_item` VALUES ('36', 'production_order', '生产通知单', 'meter_three', '电表明细表3', 'wooden_cases_packed', '是否木箱包装', null, 'yer_or_no', '10', 'INHE');
INSERT INTO `inhe_detail_item` VALUES ('37', 'production_order', '生产通知单', 'meter_three', '电表明细表3', 'related_attachment', '相关附件', '1', null, '11', 'INHE');
INSERT INTO `inhe_detail_item` VALUES ('38', 'production_order', '生产通知单', 'meter_three', '电表明细表3', '\r\nother_instruction', '其他特殊要求和补充说明', null, null, '12', 'INHE');
INSERT INTO `inhe_detail_item` VALUES ('39', 'production_order', '生产通知单', 'meter_three', '电表明细表3', 'attachment', '附件', '1', null, '13', 'INHE');
INSERT INTO `inhe_detail_item` VALUES ('40', 'production_order', '生产通知单', 'non_meter', '非电表类产品', 'non_serial_number', '序号', null, null, '1', 'INHE');
INSERT INTO `inhe_detail_item` VALUES ('41', 'production_order', '生产通知单', 'non_meter', '非电表类产品', 'non_product_name', '产品名称', null, null, '2', 'INHE');
INSERT INTO `inhe_detail_item` VALUES ('42', 'production_order', '生产通知单', 'non_meter', '非电表类产品', 'non_order_number', '订单数量', null, null, '3', 'INHE');
INSERT INTO `inhe_detail_item` VALUES ('43', 'production_order', '生产通知单', 'non_meter', '非电表类产品', 'specification_model', '规格型号', null, null, '4', 'INHE');
INSERT INTO `inhe_detail_item` VALUES ('44', 'production_order', '生产通知单', 'non_meter', '非电表类产品', 'unit', '单位', null, null, '5', 'INHE');
INSERT INTO `inhe_detail_item` VALUES ('45', 'production_order', '生产通知单', 'non_meter', '非电表类产品', 'non_attachment', '附件', '1', null, '6', 'INHE');
