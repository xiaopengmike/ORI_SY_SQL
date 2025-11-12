/*
Navicat MySQL Data Transfer

Source Server         : oa
Source Server Version : 50525
Source Host           : localhost:3336
Source Database       : TD_OA

Target Server Type    : MYSQL
Target Server Version : 50525
File Encoding         : 65001

Date: 2020-08-03 17:14:50
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for inhe_bidding_strategy
-- ----------------------------
DROP TABLE IF EXISTS `inhe_bidding_strategy`;
CREATE TABLE `inhe_bidding_strategy` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `form_id` varchar(20) DEFAULT NULL,
  `tender_no` varchar(20) DEFAULT NULL,
  `end_date` datetime DEFAULT NULL,
  `project_name` varchar(255) DEFAULT NULL,
  `tenders` varchar(255) DEFAULT NULL,
  `bid_rules` varchar(255) DEFAULT NULL,
  `biding_rules` varchar(255) DEFAULT NULL,
  `effective_demand` varchar(255) DEFAULT NULL,
  `tender_demand` varchar(255) DEFAULT NULL,
  `file_demand` varchar(255) DEFAULT NULL,
  `format` varchar(255) DEFAULT NULL,
  `reason` varchar(255) DEFAULT NULL,
  `procedure` varchar(255) DEFAULT NULL,
  `pay_process` varchar(255) DEFAULT NULL,
  `pay_energy` varchar(255) DEFAULT NULL,
  `partner_analysis` varchar(255) DEFAULT NULL,
  `relationship` varchar(255) DEFAULT NULL,
  `market_analysis` varchar(255) DEFAULT NULL,
  `strategy_analysis` varchar(255) DEFAULT NULL,
  `key_to_success` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=gbk;

-- ----------------------------
-- Records of inhe_bidding_strategy
-- ----------------------------
