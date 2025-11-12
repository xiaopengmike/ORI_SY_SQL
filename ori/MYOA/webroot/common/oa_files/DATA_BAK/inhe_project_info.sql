/*
Navicat MySQL Data Transfer

Source Server         : localhost_3336
Source Server Version : 50525
Source Host           : localhost:3336
Source Database       : TD_OA

Target Server Type    : MYSQL
Target Server Version : 50525
File Encoding         : 65001

Date: 2020-09-30 18:17:57
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for inhe_project_info
-- ----------------------------
DROP TABLE IF EXISTS `inhe_project_info`;
CREATE TABLE `inhe_project_info` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `form_id` varchar(20) DEFAULT NULL,
  `project_code` varchar(255) DEFAULT NULL,
  `country` varchar(255) DEFAULT NULL,
  `scope` varchar(255) DEFAULT NULL,
  `project_type` varchar(255) DEFAULT NULL,
  `prototype` text COMMENT '样机需求',
  `potential` text COMMENT '项目潜力',
  `background` text,
  `explain_user` text,
  `explain_project` text,
  `service` text,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=gbk;

-- ----------------------------
-- Records of inhe_project_info
-- ----------------------------
INSERT INTO `inhe_project_info` VALUES ('1', 'lxdj202008110001', 'GPA', '3', '4', '5', '6', '7', '8', '9', '1', '7');
INSERT INTO `inhe_project_info` VALUES ('7', 'lxdj202008170002', 'PPP', 'q', 'w', 'e', 'r', 't', 'y', 'u', 'i', '6');
INSERT INTO `inhe_project_info` VALUES ('8', 'lxdj2020081801', 'GAB', '加蓬', 'AMI系统', '3', '无', '1.目前加蓬市场银河还未进入，此项目将是一个很好的契机，能够打开加蓬市场2.此AMI项目之后，电力公司将有计划更换并采购预付费电表，银河可以通过AMI项目获取电表技术规格，项目信息以及建立一定的客户关系', '加蓬共和国，自从1960年8月17日脱离法国统治独立以来，加蓬共和国共经历了3届总统的统治。加蓬实行多政党制度和一部新民主宪法，并且对许多的政府机构进行了改革。2016年8月，加蓬举行总统大选，邦戈(Ali-Ben Bongo Ondimba)总统胜选，2020年7月16日，加蓬总统府宣布，总统邦戈任命国防部长奥苏卡出任新一届总理。奥苏卡成为加蓬首位女性总理，新政府改组，包括总理1名、国务部长11名、部长17名和部长级代表12名，现有合法政党40多个因盛产石油，加蓬经济一度发展迅速。以石油为主的采掘业发展较快，加工业和农业基础薄弱。对外贸易较为发达。主要出口石油、木材和锰；进口食品、轻工产品、机械设备等。主要出口对象国为中国、特多、澳大利亚；主要进口来源国为法国、比利时、中国。加蓬全国共有人口210万，40多个民族，主要有芳族（占全国人口40%）、巴普努族（占22%）等。官方语言为法语。居民50%信奉天主教；20%信奉基督教新教；10%信奉伊斯兰教；其余信奉原始宗教。低人口密度加上丰富的自然资源以及外国的个人投资帮助加蓬成为区域内最繁荣的国家，其人类发展指数是撒哈拉以南非洲国家里最高的', '无', '无', '暂不明确，因涉及到软件系统，后期可能需要提供培训');
