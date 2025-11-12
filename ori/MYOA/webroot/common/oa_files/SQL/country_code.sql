/*
Navicat MySQL Data Transfer

Source Server         : localhost_3336
Source Server Version : 50525
Source Host           : localhost:3336
Source Database       : TD_OA

Target Server Type    : MYSQL
Target Server Version : 50525
File Encoding         : 65001

Date: 2020-08-03 21:10:02
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for country_code
-- ----------------------------
DROP TABLE IF EXISTS `country_code`;
CREATE TABLE `country_code` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `iso_two` varchar(255) DEFAULT NULL,
  `iso_three` varchar(255) DEFAULT NULL,
  `iso_no` int(11) DEFAULT NULL,
  `country` varchar(255) DEFAULT NULL,
  `zh_name` varchar(255) DEFAULT NULL,
  `capital` varchar(255) DEFAULT NULL,
  `continent` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=605 DEFAULT CHARSET=gbk;

-- ----------------------------
-- Records of country_code
-- ----------------------------
INSERT INTO `country_code` VALUES ('1', 'AD', 'AND', '20', 'Andorra', '安道尔', 'Andorra la Vella', 'EU');
INSERT INTO `country_code` VALUES ('3', 'AE', 'ARE', '784', 'United Arab Emirates', '阿拉伯联合酋长国', 'Abu Dhabi', 'AS');
INSERT INTO `country_code` VALUES ('5', 'AF', 'AFG', '4', 'Afghanistan', '阿富汗', 'Kabul', 'AS');
INSERT INTO `country_code` VALUES ('7', 'AG', 'ATG', '28', 'Antigua and Barbuda', '安提瓜和巴布达', 'St. John’s', 'NA');
INSERT INTO `country_code` VALUES ('9', 'AI', 'AIA', '660', 'Anguilla', '安圭拉岛', 'The Valley', 'NA');
INSERT INTO `country_code` VALUES ('11', 'AL', 'ALB', '8', 'Albania', '阿尔巴尼亚', 'Tirana', 'EU');
INSERT INTO `country_code` VALUES ('13', 'AM', 'ARM', '51', 'Armenia', '亚美尼亚', 'Yerevan', 'AS');
INSERT INTO `country_code` VALUES ('15', 'AN', 'ANT', '530', 'Netherlands Antilles', '荷属安的列斯', 'Willemstad', 'NA');
INSERT INTO `country_code` VALUES ('17', 'AO', 'AGO', '24', 'Angola', '安哥拉', 'Luanda', 'AF');
INSERT INTO `country_code` VALUES ('19', 'AQ', 'ATA', '10', 'Antarctica', '南极洲', '', 'AN');
INSERT INTO `country_code` VALUES ('21', 'AR', 'ARG', '32', 'Argentina', '阿根廷', 'Buenos Aires', 'SA');
INSERT INTO `country_code` VALUES ('23', 'AS', 'ASM', '16', 'American Samoa', '美属萨摩亚', 'Pago Pago', 'OC');
INSERT INTO `country_code` VALUES ('25', 'AT', 'AUT', '40', 'Austria', '奥地利', 'Vienna', 'EU');
INSERT INTO `country_code` VALUES ('27', 'AU', 'AUS', '36', 'Australia', '澳大利亚', 'Canberra', 'OC');
INSERT INTO `country_code` VALUES ('29', 'AW', 'ABW', '533', 'Aruba', '阿鲁巴岛', 'Oranjestad', 'NA');
INSERT INTO `country_code` VALUES ('31', 'AX', 'ALA', '248', '?land', 'Azerbaijan', 'Mariehamn', 'EU');
INSERT INTO `country_code` VALUES ('32', 'AZ', 'AZE', '31', 'Azerbaijan', '阿塞拜疆', 'Baku', 'AS');
INSERT INTO `country_code` VALUES ('34', 'BA', 'BIH', '70', 'Bosnia and Herzegovina', '波斯尼亚和黑塞哥维那', 'Sarajevo', 'EU');
INSERT INTO `country_code` VALUES ('36', 'BB', 'BRB', '52', 'Barbados', '巴巴多斯', 'Bridgetown', 'NA');
INSERT INTO `country_code` VALUES ('38', 'BD', 'BGD', '50', 'Bangladesh', '孟加拉国', 'Dhaka', 'AS');
INSERT INTO `country_code` VALUES ('40', 'BE', 'BEL', '56', 'Belgium', '比利时', 'Brussels', 'EU');
INSERT INTO `country_code` VALUES ('42', 'BF', 'BFA', '854', 'Burkina Faso', '布基纳法索', 'Ouagadougou', 'AF');
INSERT INTO `country_code` VALUES ('44', 'BG', 'BGR', '100', 'Bulgaria', '保加利亚', 'Sofia', 'EU');
INSERT INTO `country_code` VALUES ('46', 'BH', 'BHR', '48', 'Bahrain', '巴林', 'Manama', 'AS');
INSERT INTO `country_code` VALUES ('48', 'BI', 'BDI', '108', 'Burundi', '布隆迪', 'Gitega', 'AF');
INSERT INTO `country_code` VALUES ('50', 'BJ', 'BEN', '204', 'Benin', '贝宁', 'Porto-Novo', 'AF');
INSERT INTO `country_code` VALUES ('52', 'BL', 'BLM', '652', 'Saint Barthélemy', '圣巴塞洛缪', 'Gustavia', 'NA');
INSERT INTO `country_code` VALUES ('54', 'BM', 'BMU', '60', 'Bermuda', '百慕大', 'Hamilton', 'NA');
INSERT INTO `country_code` VALUES ('56', 'BN', 'BRN', '96', 'Brunei', '文莱', 'Bandar Seri Begawan', 'AS');
INSERT INTO `country_code` VALUES ('58', 'BO', 'BOL', '68', 'Bolivia', '玻利维亚', 'Sucre', 'SA');
INSERT INTO `country_code` VALUES ('60', 'BQ', 'BES', '535', 'Bonaire, Sint Eustatius, and Saba', '博内尔，圣尤斯特歇斯和萨巴', '', 'NA');
INSERT INTO `country_code` VALUES ('62', 'BR', 'BRA', '76', 'Brazil', '巴西', 'Brasilia', 'SA');
INSERT INTO `country_code` VALUES ('64', 'BS', 'BHS', '44', 'Bahamas', '巴哈马', 'Nassau', 'NA');
INSERT INTO `country_code` VALUES ('66', 'BT', 'BTN', '64', 'Bhutan', '不丹', 'Thimphu', 'AS');
INSERT INTO `country_code` VALUES ('68', 'BV', 'BVT', '74', 'Bouvet Island', '布维岛', '', 'AN');
INSERT INTO `country_code` VALUES ('70', 'BW', 'BWA', '72', 'Botswana', '博茨瓦纳', 'Gaborone', 'AF');
INSERT INTO `country_code` VALUES ('72', 'BY', 'BLR', '112', 'Belarus', '白俄罗斯', 'Minsk', 'EU');
INSERT INTO `country_code` VALUES ('74', 'BZ', 'BLZ', '84', 'Belize', '伯利兹', 'Belmopan', 'NA');
INSERT INTO `country_code` VALUES ('76', 'CA', 'CAN', '124', 'Canada', '加拿大', 'Ottawa', 'NA');
INSERT INTO `country_code` VALUES ('78', 'CC', 'CCK', '166', 'Cocos [Keeling] Islands', '[基林]群岛', 'West Island', 'AS');
INSERT INTO `country_code` VALUES ('80', 'CD', 'COD', '180', 'DR Congo', '刚果民主共和国', 'Kinshasa', 'AF');
INSERT INTO `country_code` VALUES ('82', 'CF', 'CAF', '140', 'Central African Republic', '中非共和国', 'Bangui', 'AF');
INSERT INTO `country_code` VALUES ('84', 'CG', 'COG', '178', 'Congo Republic', '刚果共和国', 'Brazzaville', 'AF');
INSERT INTO `country_code` VALUES ('86', 'CH', 'CHE', '756', 'Switzerland', '瑞士', 'Bern', 'EU');
INSERT INTO `country_code` VALUES ('88', 'CI', 'CIV', '384', 'Ivory Coast', 'Cook Islands', 'Yamoussoukro', 'AF');
INSERT INTO `country_code` VALUES ('89', 'CK', 'COK', '184', 'Cook Islands', '库克群岛', 'Avarua', 'OC');
INSERT INTO `country_code` VALUES ('91', 'CL', 'CHL', '152', 'Chile', '智利', 'Santiago', 'SA');
INSERT INTO `country_code` VALUES ('93', 'CM', 'CMR', '120', 'Cameroon', '喀麦隆', 'Yaounde', 'AF');
INSERT INTO `country_code` VALUES ('95', 'CN', 'CHN', '156', 'China', '中国', 'Beijing', 'AS');
INSERT INTO `country_code` VALUES ('97', 'CO', 'COL', '170', 'Colombia', '哥伦比亚', 'Bogota', 'SA');
INSERT INTO `country_code` VALUES ('99', 'CR', 'CRI', '188', 'Costa Rica', '哥斯达黎加', 'San Jose', 'NA');
INSERT INTO `country_code` VALUES ('101', 'CS', 'SCG', '891', 'Serbia and Montenegro', '塞尔维亚和黑山', 'Belgrade', 'EU');
INSERT INTO `country_code` VALUES ('103', 'CU', 'CUB', '192', 'Cuba', '古巴', 'Havana', 'NA');
INSERT INTO `country_code` VALUES ('105', 'CV', 'CPV', '132', 'Cabo Verde', '佛得角', 'Praia', 'AF');
INSERT INTO `country_code` VALUES ('107', 'CW', 'CUW', '531', 'Cura?ao', '库拉索', 'Willemstad', 'NA');
INSERT INTO `country_code` VALUES ('109', 'CX', 'CXR', '162', 'Christmas Island', '圣诞岛', 'Flying Fish Cove', 'OC');
INSERT INTO `country_code` VALUES ('111', 'CY', 'CYP', '196', 'Cyprus', '塞浦路斯', 'Nicosia', 'EU');
INSERT INTO `country_code` VALUES ('113', 'CZ', 'CZE', '203', 'Czechia', '捷克', 'Prague', 'EU');
INSERT INTO `country_code` VALUES ('115', 'DE', 'DEU', '276', 'Germany', '德国', 'Berlin', 'EU');
INSERT INTO `country_code` VALUES ('117', 'DJ', 'DJI', '262', 'Djibouti', '吉布地', 'Djibouti', 'AF');
INSERT INTO `country_code` VALUES ('119', 'DK', 'DNK', '208', 'Denmark', '丹麦', 'Copenhagen', 'EU');
INSERT INTO `country_code` VALUES ('121', 'DM', 'DMA', '212', 'Dominica', '多米尼加', 'Roseau', 'NA');
INSERT INTO `country_code` VALUES ('123', 'DO', 'DOM', '214', 'Dominican Republic', '多明尼加共和国', 'Santo Domingo', 'NA');
INSERT INTO `country_code` VALUES ('125', 'DZ', 'DZA', '12', 'Algeria', '阿尔及利亚', 'Algiers', 'AF');
INSERT INTO `country_code` VALUES ('127', 'EC', 'ECU', '218', 'Ecuador', '厄瓜多尔', 'Quito', 'SA');
INSERT INTO `country_code` VALUES ('129', 'EE', 'EST', '233', 'Estonia', '爱沙尼亚', 'Tallinn', 'EU');
INSERT INTO `country_code` VALUES ('131', 'EG', 'EGY', '818', 'Egypt', '埃及', 'Cairo', 'AF');
INSERT INTO `country_code` VALUES ('133', 'EH', 'ESH', '732', 'Western Sahara', '西撒哈拉', 'El-Aaiun', 'AF');
INSERT INTO `country_code` VALUES ('135', 'ER', 'ERI', '232', 'Eritrea', '厄立特里亚', 'Asmara', 'AF');
INSERT INTO `country_code` VALUES ('137', 'ES', 'ESP', '724', 'Spain', '西班牙', 'Madrid', 'EU');
INSERT INTO `country_code` VALUES ('139', 'ET', 'ETH', '231', 'Ethiopia', '埃塞俄比亚', 'Addis Ababa', 'AF');
INSERT INTO `country_code` VALUES ('141', 'FI', 'FIN', '246', 'Finland', '芬兰', 'Helsinki', 'EU');
INSERT INTO `country_code` VALUES ('143', 'FJ', 'FJI', '242', 'Fiji', '斐济', 'Suva', 'OC');
INSERT INTO `country_code` VALUES ('145', 'FK', 'FLK', '238', 'Falkland Islands', '福克兰群岛', 'Stanley', 'SA');
INSERT INTO `country_code` VALUES ('147', 'FM', 'FSM', '583', 'Micronesia', '密克罗尼西亚', 'Palikir', 'OC');
INSERT INTO `country_code` VALUES ('149', 'FO', 'FRO', '234', 'Faroe Islands', '法罗群岛', 'Torshavn', 'EU');
INSERT INTO `country_code` VALUES ('151', 'FR', 'FRA', '250', 'France', '法国', 'Paris', 'EU');
INSERT INTO `country_code` VALUES ('153', 'GA', 'GAB', '266', 'Gabon', '加蓬', 'Libreville', 'AF');
INSERT INTO `country_code` VALUES ('155', 'GB', 'GBR', '826', 'United Kingdom', '英国', 'London', 'EU');
INSERT INTO `country_code` VALUES ('157', 'GD', 'GRD', '308', 'Grenada', '格林纳达', 'St. George’s', 'NA');
INSERT INTO `country_code` VALUES ('159', 'GE', 'GEO', '268', 'Georgia', '佐治亚州', 'Tbilisi', 'AS');
INSERT INTO `country_code` VALUES ('161', 'GF', 'GUF', '254', 'French Guiana', '法属圭亚那', 'Cayenne', 'SA');
INSERT INTO `country_code` VALUES ('163', 'GG', 'GGY', '831', 'Guernsey', '根西岛', 'St Peter Port', 'EU');
INSERT INTO `country_code` VALUES ('165', 'GH', 'GHA', '288', 'Ghana', '加纳', 'Accra', 'AF');
INSERT INTO `country_code` VALUES ('167', 'GI', 'GIB', '292', 'Gibraltar', '直布罗陀', 'Gibraltar', 'EU');
INSERT INTO `country_code` VALUES ('169', 'GL', 'GRL', '304', 'Greenland', '格陵兰', 'Nuuk', 'NA');
INSERT INTO `country_code` VALUES ('171', 'GM', 'GMB', '270', 'Gambia', '冈比亚', 'Banjul', 'AF');
INSERT INTO `country_code` VALUES ('173', 'GN', 'GIN', '324', 'Guinea', '几内亚', 'Conakry', 'AF');
INSERT INTO `country_code` VALUES ('175', 'GP', 'GLP', '312', 'Guadeloupe', '瓜德罗普岛', 'Basse-Terre', 'NA');
INSERT INTO `country_code` VALUES ('177', 'GQ', 'GNQ', '226', 'Equatorial Guinea', '几内亚', 'Malabo', 'AF');
INSERT INTO `country_code` VALUES ('179', 'GR', 'GRC', '300', 'Greece', '希腊', 'Athens', 'EU');
INSERT INTO `country_code` VALUES ('181', 'GS', 'SGS', '239', 'South Georgia and South Sandwich Islands', '南乔治亚岛和南桑威奇群岛', 'Grytviken', 'AN');
INSERT INTO `country_code` VALUES ('183', 'GT', 'GTM', '320', 'Guatemala', '危地马拉', 'Guatemala City', 'NA');
INSERT INTO `country_code` VALUES ('185', 'GU', 'GUM', '316', 'Guam', '关岛', 'Hagatna', 'OC');
INSERT INTO `country_code` VALUES ('187', 'GW', 'GNB', '624', 'Guinea-Bissau', '几内亚比绍', 'Bissau', 'AF');
INSERT INTO `country_code` VALUES ('189', 'GY', 'GUY', '328', 'Guyana', '圭亚那', 'Georgetown', 'SA');
INSERT INTO `country_code` VALUES ('191', 'HK', 'HKG', '344', 'Hong Kong', '中国香港', 'Hong Kong', 'AS');
INSERT INTO `country_code` VALUES ('193', 'HM', 'HMD', '334', 'Heard Island and McDonald Islands', '希尔德岛和麦当劳群岛', '', 'AN');
INSERT INTO `country_code` VALUES ('195', 'HN', 'HND', '340', 'Honduras', '洪都拉斯', 'Tegucigalpa', 'NA');
INSERT INTO `country_code` VALUES ('197', 'HR', 'HRV', '191', 'Croatia', '克罗地亚', 'Zagreb', 'EU');
INSERT INTO `country_code` VALUES ('199', 'HT', 'HTI', '332', 'Haiti', '海地', 'Port-au-Prince', 'NA');
INSERT INTO `country_code` VALUES ('201', 'HU', 'HUN', '348', 'Hungary', '匈牙利', 'Budapest', 'EU');
INSERT INTO `country_code` VALUES ('203', 'ID', 'IDN', '360', 'Indonesia', '印度尼西亚', 'Jakarta', 'AS');
INSERT INTO `country_code` VALUES ('205', 'IE', 'IRL', '372', 'Ireland', '爱尔兰', 'Dublin', 'EU');
INSERT INTO `country_code` VALUES ('207', 'IL', 'ISR', '376', 'Israel', '以色列', 'Jerusalem', 'AS');
INSERT INTO `country_code` VALUES ('209', 'IM', 'IMN', '833', 'Isle of Man', '马恩岛', 'Douglas', 'EU');
INSERT INTO `country_code` VALUES ('211', 'IN', 'IND', '356', 'India', '印度', 'New Delhi', 'AS');
INSERT INTO `country_code` VALUES ('213', 'IO', 'IOT', '86', 'British Indian Ocean Territory', 'Iraq', 'Diego Garcia', 'AS');
INSERT INTO `country_code` VALUES ('214', 'IQ', 'IRQ', '368', 'Iraq', '伊拉克', 'Baghdad', 'AS');
INSERT INTO `country_code` VALUES ('216', 'IR', 'IRN', '364', 'Iran', '伊朗', 'Tehran', 'AS');
INSERT INTO `country_code` VALUES ('218', 'IS', 'ISL', '352', 'Iceland', '冰岛', 'Reykjavik', 'EU');
INSERT INTO `country_code` VALUES ('220', 'IT', 'ITA', '380', 'Italy', '意大利', 'Rome', 'EU');
INSERT INTO `country_code` VALUES ('222', 'JE', 'JEY', '832', 'Jersey', '泽西岛', 'Saint Helier', 'EU');
INSERT INTO `country_code` VALUES ('224', 'JM', 'JAM', '388', 'Jamaica', '牙买加', 'Kingston', 'NA');
INSERT INTO `country_code` VALUES ('226', 'JO', 'JOR', '400', 'Jordan', '约旦', 'Amman', 'AS');
INSERT INTO `country_code` VALUES ('228', 'JP', 'JPN', '392', 'Japan', '日本', 'Tokyo', 'AS');
INSERT INTO `country_code` VALUES ('230', 'KE', 'KEN', '404', 'Kenya', '肯尼亚', 'Nairobi', 'AF');
INSERT INTO `country_code` VALUES ('232', 'KG', 'KGZ', '417', 'Kyrgyzstan', '吉尔吉斯斯坦', 'Bishkek', 'AS');
INSERT INTO `country_code` VALUES ('234', 'KH', 'KHM', '116', 'Cambodia', '柬埔寨', 'Phnom Penh', 'AS');
INSERT INTO `country_code` VALUES ('236', 'KI', 'KIR', '296', 'Kiribati', '基里巴斯', 'Tarawa', 'OC');
INSERT INTO `country_code` VALUES ('238', 'KM', 'COM', '174', 'Comoros', '科摩罗', 'Moroni', 'AF');
INSERT INTO `country_code` VALUES ('240', 'KN', 'KNA', '659', 'St Kitts and Nevis', '圣基茨和尼维斯', 'Basseterre', 'NA');
INSERT INTO `country_code` VALUES ('242', 'KP', 'PRK', '408', 'North Korea', '北朝鲜', 'Pyongyang', 'AS');
INSERT INTO `country_code` VALUES ('244', 'KR', 'KOR', '410', 'South Korea', '南韩', 'Seoul', 'AS');
INSERT INTO `country_code` VALUES ('246', 'KW', 'KWT', '414', 'Kuwait', '科威特', 'Kuwait City', 'AS');
INSERT INTO `country_code` VALUES ('248', 'KY', 'CYM', '136', 'Cayman Islands', '开曼群岛', 'George Town', 'NA');
INSERT INTO `country_code` VALUES ('250', 'KZ', 'KAZ', '398', 'Kazakhstan', '哈萨克斯坦', 'Nur-Sultan', 'AS');
INSERT INTO `country_code` VALUES ('252', 'LA', 'LAO', '418', 'Laos', '老挝', 'Vientiane', 'AS');
INSERT INTO `country_code` VALUES ('254', 'LB', 'LBN', '422', 'Lebanon', '黎巴嫩', 'Beirut', 'AS');
INSERT INTO `country_code` VALUES ('256', 'LC', 'LCA', '662', 'Saint Lucia', '圣卢西亚', 'Castries', 'NA');
INSERT INTO `country_code` VALUES ('258', 'LI', 'LIE', '438', 'Liechtenstein', '列支敦士登', 'Vaduz', 'EU');
INSERT INTO `country_code` VALUES ('260', 'LK', 'LKA', '144', 'Sri Lanka', '斯里兰卡', 'Colombo', 'AS');
INSERT INTO `country_code` VALUES ('262', 'LR', 'LBR', '430', 'Liberia', '利比里亚', 'Monrovia', 'AF');
INSERT INTO `country_code` VALUES ('264', 'LS', 'LSO', '426', 'Lesotho', '莱索托', 'Maseru', 'AF');
INSERT INTO `country_code` VALUES ('266', 'LT', 'LTU', '440', 'Lithuania', '立陶宛', 'Vilnius', 'EU');
INSERT INTO `country_code` VALUES ('268', 'LU', 'LUX', '442', 'Luxembourg', '卢森堡', 'Luxembourg', 'EU');
INSERT INTO `country_code` VALUES ('270', 'LV', 'LVA', '428', 'Latvia', '拉脱维亚', 'Riga', 'EU');
INSERT INTO `country_code` VALUES ('272', 'LY', 'LBY', '434', 'Libya', '利比亚', 'Tripoli', 'AF');
INSERT INTO `country_code` VALUES ('274', 'MA', 'MAR', '504', 'Morocco', '摩洛哥', 'Rabat', 'AF');
INSERT INTO `country_code` VALUES ('276', 'MC', 'MCO', '492', 'Monaco', '摩纳哥', 'Monaco', 'EU');
INSERT INTO `country_code` VALUES ('278', 'MD', 'MDA', '498', 'Moldova', '摩尔多瓦', 'Chisinau', 'EU');
INSERT INTO `country_code` VALUES ('280', 'ME', 'MNE', '499', 'Montenegro', '黑山共和国', 'Podgorica', 'EU');
INSERT INTO `country_code` VALUES ('282', 'MF', 'MAF', '663', 'Saint Martin', '圣马丁', 'Marigot', 'NA');
INSERT INTO `country_code` VALUES ('284', 'MG', 'MDG', '450', 'Madagascar', '马达加斯加', 'Antananarivo', 'AF');
INSERT INTO `country_code` VALUES ('286', 'MH', 'MHL', '584', 'Marshall Islands', '马绍尔群岛', 'Majuro', 'OC');
INSERT INTO `country_code` VALUES ('288', 'MK', 'MKD', '807', 'North Macedonia', '北马其顿', 'Skopje', 'EU');
INSERT INTO `country_code` VALUES ('290', 'ML', 'MLI', '466', 'Mali', '马里', 'Bamako', 'AF');
INSERT INTO `country_code` VALUES ('292', 'MM', 'MMR', '104', 'Myanmar', '缅甸', 'Nay Pyi Taw', 'AS');
INSERT INTO `country_code` VALUES ('294', 'MN', 'MNG', '496', 'Mongolia', '蒙古', 'Ulaanbaatar', 'AS');
INSERT INTO `country_code` VALUES ('296', 'MO', 'MAC', '446', 'Macao', '中国澳门', 'Macao', 'AS');
INSERT INTO `country_code` VALUES ('298', 'MP', 'MNP', '580', 'Northern Mariana Islands', '北马里亚纳群岛', 'Saipan', 'OC');
INSERT INTO `country_code` VALUES ('300', 'MQ', 'MTQ', '474', 'Martinique', '马提尼克岛', 'Fort-de-France', 'NA');
INSERT INTO `country_code` VALUES ('302', 'MR', 'MRT', '478', 'Mauritania', '毛里塔尼亚', 'Nouakchott', 'AF');
INSERT INTO `country_code` VALUES ('304', 'MS', 'MSR', '500', 'Montserrat', '蒙特塞拉特', 'Plymouth', 'NA');
INSERT INTO `country_code` VALUES ('306', 'MT', 'MLT', '470', 'Malta', '马耳他', 'Valletta', 'EU');
INSERT INTO `country_code` VALUES ('308', 'MU', 'MUS', '480', 'Mauritius', '毛里求斯', 'Port Louis', 'AF');
INSERT INTO `country_code` VALUES ('310', 'MV', 'MDV', '462', 'Maldives', '马尔代夫', 'Male', 'AS');
INSERT INTO `country_code` VALUES ('312', 'MW', 'MWI', '454', 'Malawi', '马拉维', 'Lilongwe', 'AF');
INSERT INTO `country_code` VALUES ('314', 'MX', 'MEX', '484', 'Mexico', '墨西哥', 'Mexico City', 'NA');
INSERT INTO `country_code` VALUES ('316', 'MY', 'MYS', '458', 'Malaysia', '马来西亚', 'Kuala Lumpur', 'AS');
INSERT INTO `country_code` VALUES ('318', 'MZ', 'MOZ', '508', 'Mozambique', '莫桑比克', 'Maputo', 'AF');
INSERT INTO `country_code` VALUES ('320', 'NA', 'NAM', '516', 'Namibia', '纳米比亚', 'Windhoek', 'AF');
INSERT INTO `country_code` VALUES ('322', 'NC', 'NCL', '540', 'New Caledonia', '新喀里多尼亚', 'Noumea', 'OC');
INSERT INTO `country_code` VALUES ('324', 'NE', 'NER', '562', 'Niger', '尼日尔', 'Niamey', 'AF');
INSERT INTO `country_code` VALUES ('326', 'NF', 'NFK', '574', 'Norfolk Island', '诺福克岛', 'Kingston', 'OC');
INSERT INTO `country_code` VALUES ('328', 'NG', 'NGA', '566', 'Nigeria', 'Nicaragua', 'Abuja', 'AF');
INSERT INTO `country_code` VALUES ('329', 'NI', 'NIC', '558', 'Nicaragua', '尼加拉瓜', 'Managua', 'NA');
INSERT INTO `country_code` VALUES ('331', 'NL', 'NLD', '528', 'Netherlands', '荷兰', 'Amsterdam', 'EU');
INSERT INTO `country_code` VALUES ('333', 'NO', 'NOR', '578', 'Norway', '挪威', 'Oslo', 'EU');
INSERT INTO `country_code` VALUES ('335', 'NP', 'NPL', '524', 'Nepal', '尼泊尔', 'Kathmandu', 'AS');
INSERT INTO `country_code` VALUES ('337', 'NR', 'NRU', '520', 'Nauru', '瑙鲁', 'Yaren', 'OC');
INSERT INTO `country_code` VALUES ('339', 'NU', 'NIU', '570', 'Niue', '纽埃', 'Alofi', 'OC');
INSERT INTO `country_code` VALUES ('341', 'NZ', 'NZL', '554', 'New Zealand', '新西兰', 'Wellington', 'OC');
INSERT INTO `country_code` VALUES ('343', 'OM', 'OMN', '512', 'Oman', '阿曼', 'Muscat', 'AS');
INSERT INTO `country_code` VALUES ('345', 'PA', 'PAN', '591', 'Panama', '巴拿马', 'Panama City', 'NA');
INSERT INTO `country_code` VALUES ('347', 'PE', 'PER', '604', 'Peru', '秘鲁', 'Lima', 'SA');
INSERT INTO `country_code` VALUES ('349', 'PF', 'PYF', '258', 'French Polynesia', '法属波利尼西亚', 'Papeete', 'OC');
INSERT INTO `country_code` VALUES ('351', 'PG', 'PNG', '598', 'Papua New Guinea', '巴布亚新几内亚', 'Port Moresby', 'OC');
INSERT INTO `country_code` VALUES ('353', 'PH', 'PHL', '608', 'Philippines', '菲律宾', 'Manila', 'AS');
INSERT INTO `country_code` VALUES ('355', 'PK', 'PAK', '586', 'Pakistan', '巴基斯坦', 'Islamabad', 'AS');
INSERT INTO `country_code` VALUES ('357', 'PL', 'POL', '616', 'Poland', '波兰', 'Warsaw', 'EU');
INSERT INTO `country_code` VALUES ('359', 'PM', 'SPM', '666', 'Saint Pierre and Miquelon', '圣皮埃尔和密克隆群岛', 'Saint-Pierre', 'NA');
INSERT INTO `country_code` VALUES ('361', 'PN', 'PCN', '612', 'Pitcairn Islands', '皮特凯恩群岛', 'Adamstown', 'OC');
INSERT INTO `country_code` VALUES ('363', 'PR', 'PRI', '630', 'Puerto Rico', '波多黎各', 'San Juan', 'NA');
INSERT INTO `country_code` VALUES ('365', 'PS', 'PSE', '275', 'Palestine', '巴勒斯坦', 'East Jerusalem', 'AS');
INSERT INTO `country_code` VALUES ('367', 'PT', 'PRT', '620', 'Portugal', '葡萄牙', 'Lisbon', 'EU');
INSERT INTO `country_code` VALUES ('369', 'PW', 'PLW', '585', 'Palau', '帕劳', 'Melekeok', 'OC');
INSERT INTO `country_code` VALUES ('371', 'PY', 'PRY', '600', 'Paraguay', '巴拉圭', 'Asuncion', 'SA');
INSERT INTO `country_code` VALUES ('373', 'QA', 'QAT', '634', 'Qatar', '卡塔尔', 'Doha', 'AS');
INSERT INTO `country_code` VALUES ('375', 'RE', 'REU', '638', 'Réunion', '留尼汪', 'Saint-Denis', 'AF');
INSERT INTO `country_code` VALUES ('377', 'RO', 'ROU', '642', 'Romania', '罗马尼亚', 'Bucharest', 'EU');
INSERT INTO `country_code` VALUES ('379', 'RS', 'SRB', '688', 'Serbia', '塞尔维亚', 'Belgrade', 'EU');
INSERT INTO `country_code` VALUES ('381', 'RU', 'RUS', '643', 'Russia', '俄国', 'Moscow', 'EU');
INSERT INTO `country_code` VALUES ('383', 'RW', 'RWA', '646', 'Rwanda', '卢旺达', 'Kigali', 'AF');
INSERT INTO `country_code` VALUES ('385', 'SA', 'SAU', '682', 'Saudi Arabia', '沙特阿拉伯', 'Riyadh', 'AS');
INSERT INTO `country_code` VALUES ('387', 'SB', 'SLB', '90', 'Solomon Islands', '所罗门群岛', 'Honiara', 'OC');
INSERT INTO `country_code` VALUES ('389', 'SC', 'SYC', '690', 'Seychelles', '塞舌尔', 'Victoria', 'AF');
INSERT INTO `country_code` VALUES ('391', 'SD', 'SDN', '729', 'Sudan', '苏丹', 'Khartoum', 'AF');
INSERT INTO `country_code` VALUES ('393', 'SE', 'SWE', '752', 'Sweden', '瑞典', 'Stockholm', 'EU');
INSERT INTO `country_code` VALUES ('395', 'SG', 'SGP', '702', 'Singapore', '新加坡', 'Singapore', 'AS');
INSERT INTO `country_code` VALUES ('397', 'SH', 'SHN', '654', 'Saint Helena', '圣海伦娜', 'Jamestown', 'AF');
INSERT INTO `country_code` VALUES ('399', 'SI', 'SVN', '705', 'Slovenia', '斯洛文尼亚', 'Ljubljana', 'EU');
INSERT INTO `country_code` VALUES ('401', 'SJ', 'SJM', '744', 'Svalbard and Jan Mayen', '斯瓦尔巴和扬·马延', 'Longyearbyen', 'EU');
INSERT INTO `country_code` VALUES ('403', 'SK', 'SVK', '703', 'Slovakia', '斯洛伐克', 'Bratislava', 'EU');
INSERT INTO `country_code` VALUES ('405', 'SL', 'SLE', '694', 'Sierra Leone', '塞拉利昂', 'Freetown', 'AF');
INSERT INTO `country_code` VALUES ('407', 'SM', 'SMR', '674', 'San Marino', '圣马力诺', 'San Marino', 'EU');
INSERT INTO `country_code` VALUES ('409', 'SN', 'SEN', '686', 'Senegal', '塞内加尔', 'Dakar', 'AF');
INSERT INTO `country_code` VALUES ('411', 'SO', 'SOM', '706', 'Somalia', '索马里', 'Mogadishu', 'AF');
INSERT INTO `country_code` VALUES ('413', 'SR', 'SUR', '740', 'Suriname', '苏里南', 'Paramaribo', 'SA');
INSERT INTO `country_code` VALUES ('415', 'SS', 'SSD', '728', 'South Sudan', '南苏丹', 'Juba', 'AF');
INSERT INTO `country_code` VALUES ('417', 'ST', 'STP', '678', 'S?o Tomé and Príncipe', '圣多美和普林西比', 'Sao Tome', 'AF');
INSERT INTO `country_code` VALUES ('419', 'SV', 'SLV', '222', 'El Salvador', '萨尔瓦多', 'San Salvador', 'NA');
INSERT INTO `country_code` VALUES ('421', 'SX', 'SXM', '534', 'Sint Maarten', '圣马丁', 'Philipsburg', 'NA');
INSERT INTO `country_code` VALUES ('423', 'SY', 'SYR', '760', 'Syria', '叙利亚', 'Damascus', 'AS');
INSERT INTO `country_code` VALUES ('425', 'SZ', 'SWZ', '748', 'Eswatini', '埃斯瓦蒂尼', 'Mbabane', 'AF');
INSERT INTO `country_code` VALUES ('427', 'TC', 'TCA', '796', 'Turks and Caicos Islands', '特克斯和凯科斯群岛', 'Cockburn Town', 'NA');
INSERT INTO `country_code` VALUES ('429', 'TD', 'TCD', '148', 'Chad', '乍得', 'N’Djamena', 'AF');
INSERT INTO `country_code` VALUES ('431', 'TF', 'ATF', '260', 'French Southern Territories', '法属南部领地', 'Port-aux-Francais', 'AN');
INSERT INTO `country_code` VALUES ('433', 'TG', 'TGO', '768', 'Togo', '多哥', 'Lome', 'AF');
INSERT INTO `country_code` VALUES ('435', 'TH', 'THA', '764', 'Thailand', '泰国', 'Bangkok', 'AS');
INSERT INTO `country_code` VALUES ('437', 'TJ', 'TJK', '762', 'Tajikistan', '塔吉克斯坦', 'Dushanbe', 'AS');
INSERT INTO `country_code` VALUES ('439', 'TK', 'TKL', '772', 'Tokelau', '托克劳', '', 'OC');
INSERT INTO `country_code` VALUES ('441', 'TL', 'TLS', '626', 'Timor-Leste', '东帝汶', 'Dili', 'OC');
INSERT INTO `country_code` VALUES ('443', 'TM', 'TKM', '795', 'Turkmenistan', '土库曼斯坦', 'Ashgabat', 'AS');
INSERT INTO `country_code` VALUES ('445', 'TN', 'TUN', '788', 'Tunisia', '突尼斯', 'Tunis', 'AF');
INSERT INTO `country_code` VALUES ('447', 'TO', 'TON', '776', 'Tonga', '汤加', 'Nuku’alofa', 'OC');
INSERT INTO `country_code` VALUES ('449', 'TR', 'TUR', '792', 'Turkey', '土耳其', 'Ankara', 'AS');
INSERT INTO `country_code` VALUES ('451', 'TT', 'TTO', '780', 'Trinidad and Tobago', '特立尼达和多巴哥', 'Port of Spain', 'NA');
INSERT INTO `country_code` VALUES ('453', 'TV', 'TUV', '798', 'Tuvalu', '图瓦卢', 'Funafuti', 'OC');
INSERT INTO `country_code` VALUES ('455', 'TW', 'TWN', '158', 'Taiwan', '中国台湾', 'Taipei', 'AS');
INSERT INTO `country_code` VALUES ('457', 'TZ', 'TZA', '834', 'Tanzania', '坦桑尼亚', 'Dodoma', 'AF');
INSERT INTO `country_code` VALUES ('459', 'UA', 'UKR', '804', 'Ukraine', '乌克兰', 'Kyiv', 'EU');
INSERT INTO `country_code` VALUES ('461', 'UG', 'UGA', '800', 'Uganda', '乌干达', 'Kampala', 'AF');
INSERT INTO `country_code` VALUES ('463', 'UM', 'UMI', '581', 'U.S. Minor Outlying Islands', 'United States', '', 'OC');
INSERT INTO `country_code` VALUES ('464', 'US', 'USA', '840', 'United States', '美国', 'Washington', 'NA');
INSERT INTO `country_code` VALUES ('466', 'UY', 'URY', '858', 'Uruguay', '乌拉圭', 'Montevideo', 'SA');
INSERT INTO `country_code` VALUES ('468', 'UZ', 'UZB', '860', 'Uzbekistan', '乌兹别克斯坦', 'Tashkent', 'AS');
INSERT INTO `country_code` VALUES ('470', 'VA', 'VAT', '336', 'Vatican City', '梵蒂冈城', 'Vatican City', 'EU');
INSERT INTO `country_code` VALUES ('472', 'VC', 'VCT', '670', 'St Vincent and Grenadines', '圣文森特和格林纳丁斯', 'Kingstown', 'NA');
INSERT INTO `country_code` VALUES ('474', 'VE', 'VEN', '862', 'Venezuela', '委内瑞拉', 'Caracas', 'SA');
INSERT INTO `country_code` VALUES ('476', 'VG', 'VGB', '92', 'British Virgin Islands', '英属维尔京群岛', 'Road Town', 'NA');
INSERT INTO `country_code` VALUES ('478', 'VI', 'VIR', '850', 'U.S. Virgin Islands', '美属维尔京群岛', 'Charlotte Amalie', 'NA');
INSERT INTO `country_code` VALUES ('480', 'VN', 'VNM', '704', 'Vietnam', '越南', 'Hanoi', 'AS');
INSERT INTO `country_code` VALUES ('482', 'VU', 'VUT', '548', 'Vanuatu', '瓦努阿图', 'Port Vila', 'OC');
INSERT INTO `country_code` VALUES ('484', 'WF', 'WLF', '876', 'Wallis and Futuna', '瓦利斯和富图纳群岛', 'Mata Utu', 'OC');
INSERT INTO `country_code` VALUES ('486', 'WS', 'WSM', '882', 'Samoa', '萨摩亚', 'Apia', 'OC');
INSERT INTO `country_code` VALUES ('488', 'XK', 'XKX', '0', 'Kosovo', '科索沃', 'Pristina', 'EU');
INSERT INTO `country_code` VALUES ('490', 'YE', 'YEM', '887', 'Yemen', '也门', 'Sanaa', 'AS');
INSERT INTO `country_code` VALUES ('492', 'YT', 'MYT', '175', 'Mayotte', '马约特岛', 'Mamoudzou', 'AF');
INSERT INTO `country_code` VALUES ('494', 'ZA', 'ZAF', '710', 'South Africa', '南非', 'Pretoria', 'AF');
INSERT INTO `country_code` VALUES ('496', 'ZM', 'ZMB', '894', 'Zambia', '赞比亚', 'Lusaka', 'AF');
INSERT INTO `country_code` VALUES ('498', 'ZW', 'ZWE', '716', 'Zimbabwe', '津巴布韦', 'Harare', 'AF');
