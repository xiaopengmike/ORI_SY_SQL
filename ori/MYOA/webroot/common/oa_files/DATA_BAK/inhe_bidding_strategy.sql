/*
Navicat MySQL Data Transfer

Source Server         : localhost_3336
Source Server Version : 50525
Source Host           : localhost:3336
Source Database       : TD_OA

Target Server Type    : MYSQL
Target Server Version : 50525
File Encoding         : 65001

Date: 2020-09-30 18:18:11
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
  `end_date` date DEFAULT NULL,
  `project_name` varchar(255) DEFAULT NULL,
  `tender` varchar(255) DEFAULT NULL,
  `bid_rule` varchar(255) DEFAULT NULL,
  `biding_rule` varchar(255) DEFAULT NULL,
  `effective_demand` varchar(255) DEFAULT NULL,
  `tender_demand` varchar(255) DEFAULT NULL,
  `file_demand` varchar(255) DEFAULT NULL,
  `format` varchar(255) DEFAULT NULL,
  `reason` varchar(255) DEFAULT NULL,
  `process_description` text,
  `pay_process` text,
  `pay_energy` text,
  `partner_analysis` text,
  `relationship` varchar(255) DEFAULT NULL,
  `market_analysis` text,
  `strategy_analysis` text,
  `key_to_success` varchar(255) DEFAULT NULL,
  `personal_opinion` varchar(255) DEFAULT NULL,
  `project_level` varchar(255) DEFAULT NULL,
  `star_icon` varchar(255) DEFAULT NULL,
  `have_manager` varchar(255) DEFAULT NULL,
  `signature` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=gbk;

-- ----------------------------
-- Records of inhe_bidding_strategy
-- ----------------------------
INSERT INTO `inhe_bidding_strategy` VALUES ('1', 'lxdj202008110001', '1', '0000-00-00', '3', '4', '5', '6', '7', '8', '9', '1', '2', '3', '4', '5', '6', '7', '8', '9', '1', '3', '03', null, '01', null);
INSERT INTO `inhe_bidding_strategy` VALUES ('10', 'lxdj202008170002', '11', '0000-00-00', '33', '44', '55', '66', '77', '88', '99', '1', '2', '', '4', '5', '6', '7', '8', '9', '01', '111222', '04', null, '02', null);
INSERT INTO `inhe_bidding_strategy` VALUES ('11', 'lxdj2020081801', 'EIB/01/G/PSR/2019', '2020-09-04', 'Supply and Installation of Equipment for Metering Solution 供应和安装计量方案产品', '质保：365天 需要在30天内更换或维修缺陷产品\r\n交期：最早60天，最晚120天\r\n价格条款： CIP San Tomé Island\r\n付款条件： \r\n1.在签订合同后的三十（30）天内支付合同货款的百分之十。供应商需提供与合同合同10%预付款金额相当的预付款保函；\r\n2.供应商发货后需提供在其所在国家/地区的银行开立信用证，业主支付合同货款的百分之八十（80）。\r\n3.供应商在收到业主发的验收通知30天内，业主应支付余下10%货款。', '本项目共有3个lot,lot将会被逐次评标', '每个lot最低价中标且投标文件响应标书要求', '标书要求投标保函转开\r\n投标保函：Lot 1: 欧元4,000;\r\nLot 2: 欧元10,000;\r\nLot 3. 欧元32,000.\r\n投标保函有效期应该在投标有效期后28天，即为148天。\r\n投标保函有效期应至2021年2月4日\r\n\r\n\r\n收到中标通知后的二十八（28）天内，中标人应提供履约保函\r\n履约保函为合同金额的10%\r\n\r\n预付款保函应与合同10%预付款金额相当。', '财务：\r\n1. 过去3年设备制造总营业额：2,000,000美元；\r\n2. 必须提交最近三年的财务报表；\r\n\r\n供货：\r\n过去三年提供类似规格产品的供货记录\r\n\r\n过去三年生产制造与本标书要求产品数量相当的生产能力证明', '8月20日上午11:00前', '代理商投标注：经与电力财务部沟通，目前中国的银行在当地没有合作行，所以目前在与代理沟通由他们在当地开投标保函，电力授权代理投标。', '', '因项目资金来源为电力公司的自有资金，因此招标方的决策权限较大', '圣普政府已申请或收到了来自欧洲投资银行的融资，圣普政府打算将部分资金用于本项目。在AFAP将中标结果告知圣普政府后，圣普政府向欧洲投资银行（EIB）提交付款申请。EIB之后会审核招标并在审核完成后向供应商支付货款。', 'SEEG为私有化公司，且为上市公司，加上近几年SEEG财政盈利状况好，支付能力有一定的保障', '与银河合作的代理，此前已经有过经验，和其他厂商合作，在加蓬安装了水表的AMI系统，并且已经投入运营。同时，代理与电力公司有一定的客户关系，客情条件较好，对于信息的获取都比较及时。此项目还未正式发标，但是已经获知电力公司的需求', '目前还未与电力公司建立客户关系', '已知参与厂商有兰吉尔等三家厂商，并且兰吉尔已经在电力公司内部进行了AMI系统的演示', '优势： 在发标之前银河已经获知项目情报，并且在代理的帮助下，可以争取到与电力公司直接进行内部演示的机会，抢占先机 劣势： 1.银河此前未进入过加蓬市场，对于电力公司以及市场情况了解不够深入 2.主要客户关系都握在代理手中，所有项目信息均由代理提供，和电力公司没有直接的客户关系，受限大 竞标策略： 加蓬作为一个新市场，虽然银河的客户关系和市场信息不够完善，但是参与是十分有必要的，能够为未来打下基础。一方面，需要尽快调查加蓬市场的情况，输出市场调查报告；另一方面，目前暂时判断，代理的客户关系比较靠谱，需要电力公司和代理两边同时进行，尽快建立起与电力公司的直接客户关系，有可能的话直接对接客户，跳过代理', '本周内，希望能够安排客服部支持，与代理进行一次银河AMI系统的线上演示，并尽快推动与电力公司的内部演示', '加蓬此AMI项目，由于还未正式发出投标信息，希望能够尽快推动与代理进行电力公司的直接接洽，抢占先机', '02', '★★★', '01', '魏紫薇2020-08-18 17:44:11');
