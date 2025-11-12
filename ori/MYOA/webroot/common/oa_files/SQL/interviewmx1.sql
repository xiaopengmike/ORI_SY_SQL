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

 Date: 30/12/2019 08:32:20
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for interviewmx1
-- ----------------------------
DROP TABLE IF EXISTS `interviewmx1`;
CREATE TABLE `interviewmx1`  (
  `flow_id` varchar(30) CHARACTER SET gbk COLLATE gbk_chinese_ci NOT NULL,
  `DJ` varchar(10) CHARACTER SET gbk COLLATE gbk_chinese_ci NULL DEFAULT NULL,
  `DJ1` varchar(10) CHARACTER SET gbk COLLATE gbk_chinese_ci NULL DEFAULT NULL,
  `GWGZ` int(10) NULL DEFAULT NULL,
  `GWGZ1` int(10) NULL DEFAULT NULL,
  `JXGZ` int(10) NULL DEFAULT NULL,
  `JXGZ1` int(10) NULL DEFAULT NULL,
  `HSBT` int(10) NULL DEFAULT NULL,
  `HSBT1` int(10) NULL DEFAULT NULL,
  `ZFBT` int(10) NULL DEFAULT NULL,
  `ZFBT1` int(10) NULL DEFAULT NULL,
  `TBJX` int(10) NULL DEFAULT NULL,
  `TBJX1` int(10) NULL DEFAULT NULL,
  `JTBT` int(10) NULL DEFAULT NULL,
  `JTBT1` int(10) NULL DEFAULT NULL,
  `XJXD` int(10) NULL DEFAULT NULL,
  `XJXD1` int(10) NULL DEFAULT NULL,
  `NG` int(10) NULL DEFAULT NULL,
  `NG1` int(10) NULL DEFAULT NULL,
  `TXBZ1` int(10) NULL DEFAULT NULL,
  `TXBZ` int(10) NULL DEFAULT NULL,
  `JE` int(10) NULL DEFAULT NULL,
  `JE1` int(10) NULL DEFAULT NULL,
  PRIMARY KEY (`flow_id`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = gbk COLLATE = gbk_chinese_ci ROW_FORMAT = Compact;

SET FOREIGN_KEY_CHECKS = 1;
