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

 Date: 30/12/2019 08:34:45
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for yijian2
-- ----------------------------
DROP TABLE IF EXISTS `yijian2`;
CREATE TABLE `yijian2`  (
  `DH` varchar(255) CHARACTER SET gbk COLLATE gbk_chinese_ci NULL DEFAULT NULL,
  `BZ` int(11) NULL DEFAULT NULL,
  `YJ` varchar(255) CHARACTER SET gbk COLLATE gbk_chinese_ci NULL DEFAULT NULL,
  `SHR` varchar(255) CHARACTER SET gbk COLLATE gbk_chinese_ci NULL DEFAULT NULL,
  `SJ` datetime NULL DEFAULT NULL,
  `YN` int(11) NULL DEFAULT NULL
) ENGINE = InnoDB CHARACTER SET = gbk COLLATE = gbk_chinese_ci ROW_FORMAT = Compact;

SET FOREIGN_KEY_CHECKS = 1;
