/*
Navicat MySQL Data Transfer

Source Server         : localhost_3336
Source Server Version : 50525
Source Host           : localhost:3336
Source Database       : TD_OA

Target Server Type    : MYSQL
Target Server Version : 50525
File Encoding         : 65001

Date: 2020-09-30 18:23:35
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for inhe_dynamic_page
-- ----------------------------
DROP TABLE IF EXISTS `inhe_dynamic_page`;
CREATE TABLE `inhe_dynamic_page` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `page_name` varchar(255) DEFAULT NULL,
  `page_type` varchar(255) DEFAULT NULL,
  `page_url` varchar(255) DEFAULT NULL,
  `do_url` varchar(255) DEFAULT NULL,
  `module_type` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=gbk;

-- ----------------------------
-- Records of inhe_dynamic_page
-- ----------------------------
INSERT INTO `inhe_dynamic_page` VALUES ('1', '查询列表', 'list', '/general/control_library/query.php', null, 'control_library');
INSERT INTO `inhe_dynamic_page` VALUES ('2', '新增名词数据', 'add', '/general/control_library/add.php', '/general/control_library/action.php?action=Add', 'control_library');
INSERT INTO `inhe_dynamic_page` VALUES ('3', '修改名词数据', 'modify', '/general/control_library/modify.php', '/general/control_library/action.php?action=Modify', 'control_library');
INSERT INTO `inhe_dynamic_page` VALUES ('4', '删除名词数据', 'delete', '/general/control_library/delete.php', '/general/control_library/action.php?action=Delete', 'control_library');
INSERT INTO `inhe_dynamic_page` VALUES ('5', '查阅名词详情', 'detail', '/general/control_library/detail.php', '/general/control_library/action.php?action=Detail', 'control_library');
INSERT INTO `inhe_dynamic_page` VALUES ('6', '审核名词数据', 'approve', '/general/control_library/approve.php', '/general/control_library/action.php?action=Approve', 'control_library');
