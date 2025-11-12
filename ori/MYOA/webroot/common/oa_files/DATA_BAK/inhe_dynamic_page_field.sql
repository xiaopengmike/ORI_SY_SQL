/*
Navicat MySQL Data Transfer

Source Server         : localhost_3336
Source Server Version : 50525
Source Host           : localhost:3336
Source Database       : TD_OA

Target Server Type    : MYSQL
Target Server Version : 50525
File Encoding         : 65001

Date: 2020-09-30 18:23:39
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for inhe_dynamic_page_field
-- ----------------------------
DROP TABLE IF EXISTS `inhe_dynamic_page_field`;
CREATE TABLE `inhe_dynamic_page_field` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `param_key` varchar(255) DEFAULT NULL,
  `param_name` varchar(255) DEFAULT NULL,
  `module_type` varchar(255) DEFAULT NULL,
  `data_type` varchar(255) DEFAULT NULL,
  `is_search` varchar(255) DEFAULT NULL,
  `is_add` varchar(255) DEFAULT NULL,
  `is_list` varchar(255) DEFAULT NULL,
  `is_must` varchar(255) DEFAULT NULL,
  `verify` varchar(255) DEFAULT NULL COMMENT 'required（必填项）|phone（手机号）|email（邮箱）|url（网址）|number（数字）|date（日期）|identity（身份证）|自定义值(pass)',
  `alias` varchar(255) DEFAULT NULL COMMENT 'center|left|right',
  `css` varchar(255) DEFAULT NULL COMMENT 'multi|center',
  `other_info` varchar(255) DEFAULT NULL COMMENT '附件type|下拉级别|其他使用可扩展',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=gbk;

-- ----------------------------
-- Records of inhe_dynamic_page_field
-- ----------------------------
INSERT INTO `inhe_dynamic_page_field` VALUES ('1', 'type', '分类', 'control_library', 'input', '1', '1', '1', '1', 'required', '', '', null);
INSERT INTO `inhe_dynamic_page_field` VALUES ('3', 'chinese', '中文', 'control_library', 'input', '1', '1', '1', '1', 'required', '', '', null);
INSERT INTO `inhe_dynamic_page_field` VALUES ('4', 'english', '英语', 'control_library', 'input', '1', '1', '1', '1', 'required', '', '', null);
INSERT INTO `inhe_dynamic_page_field` VALUES ('5', 'french', '法语', 'control_library', 'input', '1', '1', '1', '', 'pass', '', '', null);
INSERT INTO `inhe_dynamic_page_field` VALUES ('6', 'spanish', '西语', 'control_library', 'input', '1', '1', '1', '', 'pass', '', '', null);
INSERT INTO `inhe_dynamic_page_field` VALUES ('7', 'remark', '备注', 'control_library', 'text', null, '1', null, null, 'pass', '', '', '');
INSERT INTO `inhe_dynamic_page_field` VALUES ('8', 'attach', '附件上传', 'control_library', 'attach', null, '1', null, null, 'pass', null, null, 'control_library');
