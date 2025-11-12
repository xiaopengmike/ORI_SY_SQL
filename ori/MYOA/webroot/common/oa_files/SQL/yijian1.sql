/*
 Navicat Premium Data Transfer

 Source Server         : oa
 Source Server Type    : MySQL
 Source Server Version : 50525
 Source Host           : localhost:3336
 Source Schema         : TD_OA

 Target Server Type    : MySQL
 Target Server Version : 50525
 File Encoding         : 65001

 Date: 30/12/2019 08:34:38
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for yijian1
-- ----------------------------
DROP TABLE IF EXISTS `yijian1`;
CREATE TABLE `yijian1`  (
  `flow_id` varchar(30) CHARACTER SET gbk COLLATE gbk_chinese_ci NOT NULL,
  `DH` int(11) NULL DEFAULT NULL,
  `BZ` int(11) NOT NULL,
  `YJ` varchar(255) CHARACTER SET gbk COLLATE gbk_chinese_ci NULL DEFAULT NULL,
  `SHR` varchar(255) CHARACTER SET gbk COLLATE gbk_chinese_ci NULL DEFAULT NULL,
  `SJ` datetime NULL DEFAULT NULL,
  `YN` int(11) NULL DEFAULT NULL,
  PRIMARY KEY (`flow_id`, `BZ`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = gbk COLLATE = gbk_chinese_ci ROW_FORMAT = Compact;

SET FOREIGN_KEY_CHECKS = 1;
