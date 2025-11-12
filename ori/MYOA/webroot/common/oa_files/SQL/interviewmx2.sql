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

 Date: 30/12/2019 08:33:03
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for interviewmx2
-- ----------------------------
DROP TABLE IF EXISTS `interviewmx2`;
CREATE TABLE `interviewmx2`  (
  `flow_id` varchar(30) CHARACTER SET gbk COLLATE gbk_chinese_ci NOT NULL,
  `GS` varchar(10) CHARACTER SET gbk COLLATE gbk_chinese_ci NULL DEFAULT NULL,
  `RZGH` varchar(20) CHARACTER SET gbk COLLATE gbk_chinese_ci NULL DEFAULT NULL,
  `ZH` varchar(20) CHARACTER SET gbk COLLATE gbk_chinese_ci NULL DEFAULT NULL,
  `ZW` varchar(50) CHARACTER SET gbk COLLATE gbk_chinese_ci NULL DEFAULT NULL,
  `BM_ID` int(11) NULL DEFAULT NULL,
  `BM` varchar(100) CHARACTER SET gbk COLLATE gbk_chinese_ci NULL DEFAULT NULL,
  `dj` varchar(10) CHARACTER SET gbk COLLATE gbk_chinese_ci NULL DEFAULT NULL,
  `gwgz` int(10) NULL DEFAULT NULL,
  `jxgz` int(10) NULL DEFAULT NULL,
  `hsbt` int(10) NULL DEFAULT NULL,
  `zfbt` int(10) NULL DEFAULT NULL,
  `tbjx` int(10) NULL DEFAULT NULL,
  `jtbt` int(10) NULL DEFAULT NULL,
  `xjxd` int(10) NULL DEFAULT NULL,
  `ng` int(10) NULL DEFAULT NULL,
  `txbz` int(10) NULL DEFAULT NULL,
  `je` int(10) NULL DEFAULT NULL,
  `RZSJ` varchar(20) CHARACTER SET gbk COLLATE gbk_chinese_ci NULL DEFAULT NULL,
  PRIMARY KEY (`flow_id`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = gbk COLLATE = gbk_chinese_ci ROW_FORMAT = Compact;

SET FOREIGN_KEY_CHECKS = 1;
