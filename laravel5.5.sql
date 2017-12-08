/*
Navicat MySQL Data Transfer

Source Server         : localhost
Source Server Version : 50505
Source Host           : 127.0.0.1:3306
Source Database       : laravel5.5

Target Server Type    : MYSQL
Target Server Version : 50505
File Encoding         : 65001

Date: 2017-12-08 18:50:04
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for country
-- ----------------------------
DROP TABLE IF EXISTS `country`;
CREATE TABLE `country` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `continent_id` int(11) DEFAULT NULL COMMENT '所属大州id',
  `country_code` varchar(20) NOT NULL,
  `country` varchar(20) NOT NULL,
  `country_pinyin` varchar(255) DEFAULT NULL,
  `country_jianpin` varchar(255) DEFAULT NULL,
  `country_name_en` varchar(255) DEFAULT NULL COMMENT '国家英文名称',
  `author` int(11) DEFAULT '0' COMMENT '作者',
  `operator` int(11) NOT NULL DEFAULT '0' COMMENT '操作人',
  `audit` tinyint(4) NOT NULL DEFAULT '1' COMMENT '审核 1未通过 2 通过',
  `sort` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `idx_country_code` (`country_code`) USING BTREE,
  UNIQUE KEY `idx_country` (`country`),
  KEY `ix_continent_id` (`continent_id`)
) ENGINE=InnoDB AUTO_INCREMENT=146 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of country
-- ----------------------------
INSERT INTO `country` VALUES ('23', '1', 'KR', '韩国', 'hanguo', 'HG', 'SouthKorea', '0', '0', '2', '5', '2014-12-23 19:38:25', '2017-11-30 11:01:34');
INSERT INTO `country` VALUES ('24', '1', 'MY', '马来西亚', 'malaixiya', 'MLXY', 'Malaysia', '0', '0', '2', '1', '2014-12-23 19:38:25', '2017-11-30 11:01:34');
INSERT INTO `country` VALUES ('25', '1', 'TH', '泰国', 'taiguo', 'TG', 'Thailand', '0', '0', '2', '0', '2014-12-23 19:38:25', '2017-11-30 11:01:34');
INSERT INTO `country` VALUES ('26', '1', 'SG', '新加坡', 'xinjiapo', 'XJP', 'Singapore', '0', '0', '2', '2', '2014-12-23 19:38:25', '2017-11-30 11:01:34');
INSERT INTO `country` VALUES ('27', '1', 'PH', '菲律宾', 'feilvbin', 'FLB', 'Philippines', '0', '0', '2', '4', '2014-12-23 19:38:25', '2017-11-30 11:01:34');
INSERT INTO `country` VALUES ('28', '2', 'FR', '法国', 'faguo', 'FG', 'France', '0', '0', '2', '6', '2014-12-23 19:38:25', '2017-11-30 11:01:34');
INSERT INTO `country` VALUES ('29', '1', 'VN', '越南', 'yuenan', 'YN', 'Vietnam', '0', '0', '2', '7', '2014-12-23 19:38:25', '2017-11-30 11:01:34');
INSERT INTO `country` VALUES ('30', '2', 'CH', '瑞士', 'ruishi', 'RS', 'Switzerland', '0', '0', '2', '8', '2014-12-23 19:38:25', '2017-11-30 11:01:34');
INSERT INTO `country` VALUES ('31', '2', 'IT', '意大利', 'yidali', 'YDL', 'Italy', '0', '0', '2', '9', '2014-12-23 19:38:25', '2017-11-30 11:01:34');
INSERT INTO `country` VALUES ('32', '2', 'GR', '希腊', 'xila', 'XL', 'Greece', '0', '0', '2', '10', '2014-12-23 19:38:25', '2017-11-30 11:01:34');
INSERT INTO `country` VALUES ('33', '2', 'GB', '英国', 'yingguo', 'YG', 'UnitedKingdom', '0', '0', '2', '11', '2014-12-23 19:38:25', '2017-11-30 11:01:34');
INSERT INTO `country` VALUES ('34', '4', 'NZ', '新西兰', 'xinxilan', 'XXL', 'NewZealand', '0', '0', '2', '12', '2014-12-23 19:38:25', '2017-11-30 11:01:34');
INSERT INTO `country` VALUES ('35', '4', 'AU', '澳大利亚', 'aodaliya', 'ADLY', 'Australia', '0', '0', '2', '13', '2014-12-23 19:38:25', '2017-11-30 11:01:34');
INSERT INTO `country` VALUES ('36', '3', 'CA', '加拿大', 'jianada', 'JND', 'Canada', '0', '0', '2', '22', '2014-12-23 19:38:25', '2017-11-30 11:01:34');
INSERT INTO `country` VALUES ('37', '2', 'AT', '奥地利', 'aodili', 'ADL', 'Austria', '0', '0', '2', '14', '2014-12-23 19:38:25', '2017-11-30 11:01:34');
INSERT INTO `country` VALUES ('38', '2', 'IE', '爱尔兰', 'aierlan', 'AEL', 'Ireland', '0', '0', '2', '15', '2014-12-23 19:38:25', '2017-11-30 11:01:34');
INSERT INTO `country` VALUES ('39', '2', 'BE', '比利时', 'bilishi', 'BLS', 'Belgium', '0', '0', '2', '16', '2014-12-23 19:38:25', '2017-11-30 11:01:34');
INSERT INTO `country` VALUES ('40', '2', 'PL', '波兰', 'bolan', 'BL', 'Poland', '0', '0', '2', '17', '2014-12-23 19:38:25', '2017-11-30 11:01:34');
INSERT INTO `country` VALUES ('41', '2', 'DK', '丹麦', 'danmai', 'DM', 'Denmark', '0', '0', '2', '18', '2014-12-23 19:38:25', '2017-11-30 11:01:34');
INSERT INTO `country` VALUES ('42', '2', 'DE', '德国', 'deguo', 'DG', 'Germany', '0', '0', '2', '19', '2014-12-23 19:38:25', '2017-11-30 11:01:34');
INSERT INTO `country` VALUES ('43', '2', 'FI', '芬兰', 'fenlan', 'FL', 'Finland', '0', '0', '2', '20', '2014-12-23 19:38:25', '2017-11-30 11:01:34');
INSERT INTO `country` VALUES ('44', '2', 'NL', '荷兰', 'helan', 'HL', 'Netherlands', '0', '0', '2', '21', '2014-12-23 19:38:25', '2017-11-30 11:01:34');
INSERT INTO `country` VALUES ('45', '1', 'KH', '柬埔寨', 'jianpuzhai', 'JPZ', 'Cambodia', '0', '0', '2', '23', '2014-12-23 19:38:25', '2017-11-30 11:01:34');
INSERT INTO `country` VALUES ('46', '1', 'LA', '老挝', 'laowo', 'LW', 'Laos', '0', '0', '2', '24', '2014-12-23 19:38:25', '2017-11-30 11:01:34');
INSERT INTO `country` VALUES ('47', '2', 'SE', '瑞典', 'ruidian', 'RD', 'Sweden', '0', '0', '2', '25', '2014-12-23 19:38:25', '2017-11-30 11:01:34');
INSERT INTO `country` VALUES ('48', '2', 'ES', '西班牙', 'xibanya', 'XBY', 'Spain', '0', '0', '2', '26', '2014-12-23 19:38:25', '2017-11-30 11:01:34');
INSERT INTO `country` VALUES ('49', '2', 'HU', '匈牙利', 'xiongyali', 'XYL', 'Hungary', '0', '0', '2', '27', '2014-12-23 19:38:25', '2017-11-30 11:01:34');
INSERT INTO `country` VALUES ('50', '1', 'JP', '日本', 'riben', 'RB', 'Japan', '0', '0', '2', '28', '2014-12-23 19:38:25', '2017-11-30 11:01:34');
INSERT INTO `country` VALUES ('51', '1', 'AQ', '阿联酋', 'alianqiu', 'ALQ', 'The United Arab Emirates', '0', '0', '2', '29', '2014-12-23 19:38:25', '2017-11-30 11:01:34');
INSERT INTO `country` VALUES ('52', '2', 'CZ', '捷克', 'jieke', 'JK', 'CzechRepublic', '0', '0', '2', '30', '2014-12-23 19:38:25', '2017-11-30 11:01:34');
INSERT INTO `country` VALUES ('53', '1', 'MM', '缅甸', 'miandian', 'MD', 'Myanmar', '0', '0', '2', '31', '2014-12-23 19:38:25', '2017-11-30 11:01:34');
INSERT INTO `country` VALUES ('54', '1', 'NP', '尼泊尔', 'niboer', 'NBE', 'Nepal', '0', '0', '2', '32', '2014-12-23 19:38:25', '2017-11-30 11:01:34');
INSERT INTO `country` VALUES ('55', '2', 'NO', '挪威', 'nuewei', 'NW', 'Norway', '0', '0', '2', '33', '2014-12-23 19:38:25', '2017-11-30 11:01:34');
INSERT INTO `country` VALUES ('56', '2', 'PT', '葡萄牙', 'putaoya', 'PTY', 'Portugal', '0', '0', '2', '34', '2014-12-23 19:38:25', '2017-11-30 11:01:34');
INSERT INTO `country` VALUES ('69', '1', 'UZ', '乌兹别克斯坦', 'wuzibiekesitan', 'WZBKST', 'Uzbekistan', '0', '0', '2', '0', '2014-12-27 15:59:53', '2017-11-30 11:01:34');
INSERT INTO `country` VALUES ('70', '1', 'KG', '吉尔吉斯斯坦', 'jierjisisitan', 'JEJSST', 'Kyrgyzstan', '0', '0', '2', '0', '2015-01-20 17:32:00', '2017-11-30 11:01:34');
INSERT INTO `country` VALUES ('71', '2', 'RU', '俄罗斯', 'eluosi', 'ELS', 'Russia', '0', '0', '2', '0', '2015-01-20 17:32:18', '2017-11-30 11:01:34');
INSERT INTO `country` VALUES ('72', '5', 'ZA', '南非', 'nanfei', 'NF', 'SouthAfrica', '0', '0', '2', '0', '2015-02-12 16:09:15', '2017-11-30 11:01:34');
INSERT INTO `country` VALUES ('73', '3', 'US', '美国', 'meiguo', 'MG', 'UnitedStates', '0', '0', '2', '0', '2015-03-11 13:00:02', '2017-11-30 11:01:34');
INSERT INTO `country` VALUES ('74', '1', 'TR', '土耳其', 'tuerqi', 'TEQ', 'Turkey', '0', '0', '2', '0', '2015-03-13 18:00:08', '2017-11-30 11:01:34');
INSERT INTO `country` VALUES ('75', '5', 'CM', '喀麦隆', 'kamailong', 'KML', 'Cameroon', '0', '0', '2', '0', '2015-04-15 14:35:17', '2017-11-30 11:01:34');
INSERT INTO `country` VALUES ('76', '1', 'IR', '伊朗', 'yilang', 'YL', 'Iran', '0', '0', '2', '0', '2015-04-15 14:35:52', '2017-11-30 11:01:34');
INSERT INTO `country` VALUES ('77', '1', 'PK', '巴基斯坦', 'bajisitan', 'BJST', 'Pakistan', '0', '0', '2', '0', '2015-04-15 14:36:32', '2017-11-30 11:01:34');
INSERT INTO `country` VALUES ('80', '1', 'MN', '蒙古', 'menggu', 'MG', 'Mongolia', '0', '0', '2', '0', '2015-04-15 14:37:25', '2017-11-30 11:01:34');
INSERT INTO `country` VALUES ('81', '5', 'TN', '突尼斯', 'tunisi', 'TNS', 'Tunisia', '0', '0', '2', '0', '2015-04-15 14:37:52', '2017-11-30 11:01:34');
INSERT INTO `country` VALUES ('82', '5', 'EG', '埃及', 'aiji', 'AJ', 'Egypt', '0', '0', '2', '0', '2015-04-15 14:38:29', '2017-11-30 11:01:34');
INSERT INTO `country` VALUES ('83', '1', 'SA', '沙特阿拉伯', 'shatealabo', 'STALB', 'SaudiArabia', '0', '0', '2', '0', '2015-04-15 14:38:49', '2017-11-30 11:01:34');
INSERT INTO `country` VALUES ('84', '5', 'KE', '肯尼亚', 'kenniya', 'KNY', 'Kenya', '0', '0', '2', '0', '2015-04-15 14:40:41', '2017-11-30 11:01:34');
INSERT INTO `country` VALUES ('85', '1', 'TW', '中国台湾', 'zhongguotaiwan', 'ZGTW', 'TaiWan', '0', '0', '2', '0', '2015-05-14 17:22:46', '2017-11-30 11:01:34');
INSERT INTO `country` VALUES ('88', '1', 'IN', '印度', 'yindu', 'YD', 'India', '0', '0', '2', '0', '2015-06-05 18:10:48', '2017-11-30 11:01:34');
INSERT INTO `country` VALUES ('107', '1', 'ID', '印尼', 'yinni', 'YN', 'Indonesia', '0', '0', '2', '0', '2015-06-18 16:44:11', '2017-11-30 11:01:34');
INSERT INTO `country` VALUES ('108', '5', 'MA', '摩洛哥', 'moluoge', 'MLG', 'Morocco', '0', '0', '2', '0', '2015-06-18 16:53:34', '2017-11-30 11:01:34');
INSERT INTO `country` VALUES ('109', '3', 'CU', '古巴', 'guba', 'GB', 'Cuba', '0', '0', '2', '0', '2015-06-18 17:08:05', '2017-11-30 11:01:34');
INSERT INTO `country` VALUES ('110', '3', 'CL', '智利', 'zhili', 'ZL', 'Chile', '0', '0', '2', '0', '2015-06-18 17:08:24', '2017-11-30 11:01:34');
INSERT INTO `country` VALUES ('111', '3', 'PE', '秘鲁', 'bilu', 'BL', 'Peru', '0', '0', '2', '0', '2015-06-18 17:08:46', '2017-11-30 11:01:34');
INSERT INTO `country` VALUES ('112', '1', 'BD', '孟加拉国', 'mengjiala', 'MJL', 'Bangladesh', '0', '0', '2', '0', '2015-08-19 14:24:35', '2017-11-30 11:01:34');
INSERT INTO `country` VALUES ('113', '3', 'BR', '巴西', 'baxi', 'BX', 'Brazil', '0', '0', '2', '0', '2015-08-19 14:57:27', '2017-11-30 11:01:34');
INSERT INTO `country` VALUES ('114', '4', 'TO', '汤加', 'Tangjia', 'TJ', 'Tonga', '0', '0', '2', '0', '2015-09-11 08:13:14', '2017-11-30 11:01:34');
INSERT INTO `country` VALUES ('115', '1', 'LK', '斯里兰卡', 'sililanka', 'SLLK', 'Sri Lanka', '0', '0', '2', '0', '2015-10-20 14:25:32', '2017-11-30 11:01:34');
INSERT INTO `country` VALUES ('116', '1', 'IL', '以色列', 'Yiselie', 'YSL', 'State of Israel', '0', '0', '2', '0', '2016-03-07 10:45:46', '2017-11-30 11:01:34');
INSERT INTO `country` VALUES ('119', '5', 'NG', '尼日利亚', 'niriliya', 'NRLY', 'Federal Republic of Nigeria', '0', '0', '2', '0', '2016-03-22 10:23:09', '2017-11-30 11:01:34');
INSERT INTO `country` VALUES ('120', '5', 'ML', '马里', 'mali', 'ML', 'Republic of Mali', '0', '0', '2', '0', '2016-03-22 10:24:36', '2017-11-30 11:01:34');
INSERT INTO `country` VALUES ('121', '2', 'LT', '立陶宛', 'Li Taowan', 'LTW', 'Lithuania', '0', '0', '2', '0', '2016-05-31 16:10:06', '2017-11-30 11:01:34');
INSERT INTO `country` VALUES ('122', '2', 'IS', '冰岛', 'Bing Dao', 'BD', 'Iceland', '0', '0', '2', '0', '2016-06-06 12:55:34', '2017-11-30 11:01:34');
INSERT INTO `country` VALUES ('123', '1', 'AZ', '阿塞拜疆', 'asaibaijiang', 'ASBJ', 'azerbaijan', '0', '0', '2', '0', '2016-06-15 11:09:04', '2017-11-30 11:01:34');
INSERT INTO `country` VALUES ('124', '1', 'TJ', '塔吉克斯坦', 'tajikesitan', 'TJKST', 'Tajikistan', '0', '0', '2', '0', '2016-06-16 10:46:47', '2017-11-30 11:01:34');
INSERT INTO `country` VALUES ('125', '1', 'GE', '格鲁吉亚', 'gelujiya', 'GLJY', 'Georgia', '0', '0', '2', '0', '2016-06-16 11:01:50', '2017-11-30 11:01:34');
INSERT INTO `country` VALUES ('126', '1', 'QA', '卡塔尔', 'Kataer', 'KTE', 'Qatar', '0', '0', '2', '0', '2016-06-27 15:08:02', '2017-11-30 11:01:34');
INSERT INTO `country` VALUES ('127', '2', 'MT', '马耳他', 'maerta', 'MET', 'Republic of Malta', '0', '0', '2', '0', '2016-06-30 14:19:23', '2017-11-30 11:01:34');
INSERT INTO `country` VALUES ('128', '5', 'UG', '乌干达', 'wuganda', 'WGD', 'Uganda', '0', '0', '2', '0', '2016-08-15 10:20:59', '2017-11-30 11:01:34');
INSERT INTO `country` VALUES ('129', '3', 'AR', '阿根廷', 'agenting', 'AGT', 'Argentina', '0', '0', '2', '0', '2016-09-01 13:07:30', '2017-11-30 11:01:34');
INSERT INTO `country` VALUES ('130', '2', 'UA', '乌克兰', 'wukelan', 'WKL', 'Ukraine', '0', '0', '2', '0', '2016-09-13 11:31:05', '2017-11-30 11:01:34');
INSERT INTO `country` VALUES ('131', '1', 'KZ', '哈萨克斯坦', 'hasakesitan', 'HSKST', 'kazakhstan', '0', '0', '2', '0', '2016-09-13 15:26:36', '2017-11-30 11:01:34');
INSERT INTO `country` VALUES ('133', '1', 'AM', '亚美尼亚', 'Yameiniya', 'YMNY', 'Armenia', '0', '0', '2', '0', '2016-12-09 20:56:04', '2017-11-30 11:01:34');
INSERT INTO `country` VALUES ('134', '1', 'OM', '阿曼', 'Aman', 'AM', 'Oman', '0', '0', '2', '0', '2016-12-09 20:58:16', '2017-11-30 11:01:34');
INSERT INTO `country` VALUES ('135', '5', 'ET', '埃塞俄比亚', 'Aisaiebiya', 'ASEBY', 'Ethiopia', '0', '0', '2', '0', '2017-01-09 10:06:56', '2017-11-30 11:01:34');
INSERT INTO `country` VALUES ('136', '5', 'TD', '乍得', 'Zhade', 'ZD', 'Chad', '0', '0', '2', '0', '2017-01-09 11:35:19', '2017-11-30 11:01:34');
INSERT INTO `country` VALUES ('137', '5', 'MG', '马达加斯加', 'Madajiasijia', 'MDJSJ', 'Madagascar', '0', '0', '2', '0', '2017-01-09 11:37:09', '2017-11-30 11:01:34');
INSERT INTO `country` VALUES ('138', '5', 'TG', '多哥', 'Duoge', 'DG', 'Togo', '0', '0', '2', '0', '2017-01-09 11:39:10', '2017-11-30 11:01:34');
INSERT INTO `country` VALUES ('139', '5', 'GN', '几内亚', 'Jineiya', 'JNY', 'guinea', '0', '0', '2', '0', '2017-01-09 11:41:04', '2017-11-30 11:01:34');
INSERT INTO `country` VALUES ('140', '5', 'TZ', '坦桑尼亚', 'Tansangniya', 'TSNY', 'Tanzania', '0', '0', '2', '0', '2017-01-09 11:43:03', '2017-11-30 11:01:34');
INSERT INTO `country` VALUES ('141', '5', 'SL', '塞拉利昂', 'Sailaliang', 'SLLA', 'Sierra leone', '0', '0', '2', '0', '2017-01-09 11:45:04', '2017-11-30 11:01:34');
INSERT INTO `country` VALUES ('143', '2', 'CY', '塞浦路斯', 'saipulusi', 'spls', 'Cyprus', '0', '0', '2', '0', '2017-07-05 13:31:09', '2017-11-30 11:01:34');
INSERT INTO `country` VALUES ('145', '2', 'LU', '卢森堡', 'lusenbao', 'lsb', 'Luxembourg', '0', '0', '2', '0', '2017-07-06 17:05:06', '2017-11-30 11:01:34');

-- ----------------------------
-- Table structure for knowledge
-- ----------------------------
DROP TABLE IF EXISTS `knowledge`;
CREATE TABLE `knowledge` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `title` varchar(200) NOT NULL DEFAULT '',
  `category_id` varchar(50) DEFAULT '',
  `country_id` int(10) DEFAULT '0' COMMENT '国家',
  `content` text,
  `status` tinyint(4) DEFAULT '0' COMMENT '状态 1新建 2待审核 3审核失败 4待发布 5上线 6下线',
  `enshrine` int(11) DEFAULT '0' COMMENT '收藏',
  `hit` int(11) DEFAULT '0' COMMENT '点击',
  `author` int(11) DEFAULT '0' COMMENT '作者',
  `operator` int(11) NOT NULL DEFAULT '0' COMMENT '操作人',
  `created_at` int(11) NOT NULL DEFAULT '0',
  `updated_at` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of knowledge
-- ----------------------------
INSERT INTO `knowledge` VALUES ('1', '切勿拿这些心灵鸡汤来教育孩子', '26', '42', '<p>请你准备好纸和笔，跟随下面这13个问题，进行一趟心灵的探索之旅，找到你的人生方向吧！</p><p>一、立足当下</p><p>我现在正在做的事情是什么？我喜欢它吗？我热爱它吗？我全身心得为这件事投入过吗？就像不需要报酬、不需要喝彩、不需要成就一样，而只是因为我喜欢做这件事吗？</p><p>二、反省</p><p>如果我不喜欢我正在做的事，那需要思考的是，当初我为什么会做了这样的“选择”？比起怨天尤人，我肯为我的“选择”负起责任吗？如果非要为上天也同意我做这件事找一个理由，那上天希望我从这件事中学习哪些能力呢？想锻炼我的什么品质呢？</p><p>三、接受现状的实相</p><p>抱着对生命旅途臣服的心，去思考——如果我将现在正在做的事情做到极致，我能为这个世界创造一份怎样的价值？那会是怎样的景象？我会为自己书写一个怎样的故事？</p><p>四、价值需求</p><p>我期望从此生得到的是什么？我认为人生中最重要的是什么？还有更多吗？为什么？</p><p>五、厘清身份</p><p>在我看来，什么样的人容易获得这些价值？而我是怎样一个人？</p><p>六、觉察使命</p><p>我本身所具备的天赋、才华与世界的需求之间有哪些交汇呢？如果我身兼一个使命而来，那个使命是什么？</p>', '3', '1', '23', '1', '1', '1443586647', '1512475363');
INSERT INTO `knowledge` VALUES ('2', '这些话将是一生所求的答案！', '23', '73', '<p>1.亦舒说:</p><p>人们日常所犯最大的错误，是对陌生人太客气，而对亲密的人太苛刻，</p><p>把这个坏习惯改过来，天下太平。</p><p>2.郭敬明说:</p><p>我终于发现自己看人的眼光太过简单，我从来没有去想面具下面是一张怎样的面容，</p><p>我总是直接把面具当做面孔来对待，却忘记了笑脸面具下往往都是一张流着泪的脸。</p><p>3.刘心武说:</p><p>对不起是一种真诚，没关系是一种风度。</p><p>如果你付出了真诚，却得不到风度，那只能说明对方的无知与粗俗！</p><p>4.韩寒说:</p><p>再累再苦就当自己是二百五再难再险就当自己是二皮脸。</p><p>5.安妮宝贝说:</p><p>当一个女子在看天空的时候，她并不想寻找什么。</p><p>她只是寂寞。</p>', '5', '1', '0', '1', '1', '1443586649', '1512475296');
INSERT INTO `knowledge` VALUES ('4', '为什么白天必须要结束？', '27', '23', '<p>金黄色的大太阳已经照了一整天，白天就要结束了。</p><p>小男孩看到白天结束非常伤心。</p><p>现在，他的妈妈来向他道晚安。</p><p>“为什么白天必须要结束呢？”他问妈妈。</p><p>“这样，夜晚才能开始啊。”</p><p>“可是，白天结束时，太阳到哪里去了呢？”</p><p>“白天其实没有结束，它会在别处开始，太阳将会在那里发光。这时夜晚会在这里开始。什么都不会结束。”</p><p>“真的什么都不会结束？”</p><p>“什么都不会，它会在另一个地方以另一种方式开始。”</p><p>小男孩躺在被窝里，妈妈坐在他身边。</p><p>“风停之后，风到哪里去了呢？”他继续问。</p><p>“它吹到别的地方，让那里的树跳舞去了。”</p>', '2', '1', '22', '1', '1', '1443586650', '1512474516');

-- ----------------------------
-- Table structure for knowledge_category
-- ----------------------------
DROP TABLE IF EXISTS `knowledge_category`;
CREATE TABLE `knowledge_category` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) DEFAULT NULL,
  `lft` int(10) NOT NULL DEFAULT '0',
  `rgt` int(10) NOT NULL DEFAULT '0',
  `parent` int(10) NOT NULL DEFAULT '0',
  `depth` int(10) DEFAULT '1',
  `operator` varchar(100) NOT NULL DEFAULT '' COMMENT '操作人',
  `created_at` int(11) DEFAULT NULL,
  `updated_at` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `lft` (`lft`),
  KEY `rgt` (`rgt`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of knowledge_category
-- ----------------------------
INSERT INTO `knowledge_category` VALUES ('1', '人力知识', '1', '2', '0', '1', '1', '1512543331', '1512543331');
INSERT INTO `knowledge_category` VALUES ('2', '财务知识', '3', '4', '0', '1', '1', '1512543339', '1512543339');
INSERT INTO `knowledge_category` VALUES ('3', '业务知识', '5', '10', '0', '1', '1', '1512543346', '1512543389');
INSERT INTO `knowledge_category` VALUES ('4', '法律法规', '11', '12', '0', '1', '1', '1512543352', '1512543389');
INSERT INTO `knowledge_category` VALUES ('5', '签证业务', '6', '7', '3', '2', '1', '1512543373', '1512543373');
INSERT INTO `knowledge_category` VALUES ('6', '商旅业务', '8', '9', '3', '2', '1', '1512543389', '1512543389');

-- ----------------------------
-- Table structure for migrations
-- ----------------------------
DROP TABLE IF EXISTS `migrations`;
CREATE TABLE `migrations` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------
-- Records of migrations
-- ----------------------------
INSERT INTO `migrations` VALUES ('1', '2017_12_08_112305_entrust_setup_tables', '1');

-- ----------------------------
-- Table structure for organization
-- ----------------------------
DROP TABLE IF EXISTS `organization`;
CREATE TABLE `organization` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) DEFAULT NULL,
  `lft` int(10) NOT NULL DEFAULT '0',
  `rgt` int(10) NOT NULL DEFAULT '0',
  `parent` int(10) NOT NULL DEFAULT '0',
  `depth` int(10) DEFAULT '1',
  `operator` varchar(100) NOT NULL DEFAULT '' COMMENT '操作人',
  `created_at` int(11) DEFAULT NULL,
  `updated_at` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `lft` (`lft`),
  KEY `rgt` (`rgt`)
) ENGINE=InnoDB AUTO_INCREMENT=33 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of organization
-- ----------------------------
INSERT INTO `organization` VALUES ('1', '北京百程国际旅游股份有限公司', '1', '64', '0', '1', '1', '1512542240', '1512638958');
INSERT INTO `organization` VALUES ('2', '北京总部', '2', '51', '1', '2', '1', '1512542380', '1512638958');
INSERT INTO `organization` VALUES ('3', '广州分公司', '52', '53', '1', '2', '1', '1512542481', '1512638958');
INSERT INTO `organization` VALUES ('4', '沈阳分公司', '54', '55', '1', '2', '1', '1512542498', '1512638958');
INSERT INTO `organization` VALUES ('5', '上海分公司', '56', '57', '1', '2', '1', '1512542511', '1512638958');
INSERT INTO `organization` VALUES ('6', '武汉分公司', '58', '59', '1', '2', '1', '1512542524', '1512638958');
INSERT INTO `organization` VALUES ('7', '深圳分公司', '60', '61', '1', '2', '1', '1512542540', '1512638958');
INSERT INTO `organization` VALUES ('8', '成都分公司', '62', '63', '1', '2', '1', '1512542571', '1512638958');
INSERT INTO `organization` VALUES ('9', '人力行政中心', '3', '8', '2', '3', '1', '1512542611', '1512543472');
INSERT INTO `organization` VALUES ('10', '技术支持中心', '9', '26', '2', '3', '1', '1512542625', '1512638958');
INSERT INTO `organization` VALUES ('11', '总裁办', '27', '28', '2', '3', '1', '1512542639', '1512638958');
INSERT INTO `organization` VALUES ('12', '客户服务中心', '29', '30', '2', '3', '1', '1512542657', '1512638958');
INSERT INTO `organization` VALUES ('13', '财务部', '31', '32', '2', '3', '1', '1512542675', '1512638958');
INSERT INTO `organization` VALUES ('14', '法务部', '33', '34', '2', '3', '1', '1512542690', '1512638958');
INSERT INTO `organization` VALUES ('15', '商旅事业部', '35', '36', '2', '3', '1', '1512542708', '1512638958');
INSERT INTO `organization` VALUES ('16', '运营中心', '37', '38', '2', '3', '1', '1512542725', '1512638958');
INSERT INTO `organization` VALUES ('17', '新兴事业部', '39', '40', '2', '3', '1', '1512542738', '1512638958');
INSERT INTO `organization` VALUES ('18', '定制事业部', '41', '42', '2', '3', '1', '1512542794', '1512638958');
INSERT INTO `organization` VALUES ('19', '签证事业部', '43', '44', '2', '3', '1', '1512542814', '1512638958');
INSERT INTO `organization` VALUES ('20', '目的地事业部', '45', '46', '2', '3', '1', '1512542845', '1512638958');
INSERT INTO `organization` VALUES ('21', '第三事业部', '47', '48', '2', '3', '1', '1512542865', '1512638958');
INSERT INTO `organization` VALUES ('22', '外采事业部', '49', '50', '2', '3', '1', '1512542886', '1512638958');
INSERT INTO `organization` VALUES ('23', '行政部', '4', '5', '9', '4', '1', '1512543457', '1512543457');
INSERT INTO `organization` VALUES ('24', '人力资源', '6', '7', '9', '4', '1', '1512543472', '1512543472');
INSERT INTO `organization` VALUES ('25', '技术部', '10', '21', '10', '4', '1', '1512543499', '1512543668');
INSERT INTO `organization` VALUES ('26', '产品部', '22', '25', '10', '4', '1', '1512543519', '1512638958');
INSERT INTO `organization` VALUES ('27', '移动开发组', '11', '12', '25', '5', '1', '1512543546', '1512543546');
INSERT INTO `organization` VALUES ('28', '运维组', '13', '14', '25', '5', '1', '1512543566', '1512543566');
INSERT INTO `organization` VALUES ('29', 'QA组', '15', '16', '25', '5', '1', '1512543587', '1512543587');
INSERT INTO `organization` VALUES ('30', '后端开发组', '17', '18', '25', '5', '1', '1512543607', '1512543607');
INSERT INTO `organization` VALUES ('31', '前端开发组', '19', '20', '25', '5', '1', '1512543645', '1512543668');
INSERT INTO `organization` VALUES ('32', '产品组', '23', '24', '26', '5', '1', '1512638958', '1512638958');

-- ----------------------------
-- Table structure for password_resets
-- ----------------------------
DROP TABLE IF EXISTS `password_resets`;
CREATE TABLE `password_resets` (
  `email` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  KEY `password_resets_email_index` (`email`),
  KEY `password_resets_token_index` (`token`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------
-- Records of password_resets
-- ----------------------------
INSERT INTO `password_resets` VALUES ('xiongjinchao@baicheng.com', '$2y$10$zmM8M4UdPvffspd0Khysr.UXwD0k3DE3tNJdeImfQcJ9Bj/TLbLq.', '2017-12-07 19:41:22');
INSERT INTO `password_resets` VALUES ('67218027@qq.com', '$2y$10$2NYx3jOFnm.H04ACyNqvg.nH8hZAQ3dOePJTw6RYQLgeL8MSGVU5.', '2017-12-07 19:48:32');

-- ----------------------------
-- Table structure for permissions
-- ----------------------------
DROP TABLE IF EXISTS `permissions`;
CREATE TABLE `permissions` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `display_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `permissions_name_unique` (`name`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=64 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------
-- Records of permissions
-- ----------------------------
INSERT INTO `permissions` VALUES ('1', '[GET]App\\Http\\Controllers\\HomeController@index', 'App\\Http\\Controllers\\HomeController@index', 'home', '2017-12-08 16:21:11', '2017-12-08 16:21:11');
INSERT INTO `permissions` VALUES ('2', '[GET]App\\Http\\Controllers\\OrganizationController@moveUp', 'App\\Http\\Controllers\\OrganizationController@moveUp', 'organization/move-up/{id}', '2017-12-08 16:21:11', '2017-12-08 16:21:11');
INSERT INTO `permissions` VALUES ('3', '[GET]App\\Http\\Controllers\\OrganizationController@moveDown', 'App\\Http\\Controllers\\OrganizationController@moveDown', 'organization/move-down/{id}', '2017-12-08 16:21:12', '2017-12-08 16:21:12');
INSERT INTO `permissions` VALUES ('4', '[GET]App\\Http\\Controllers\\UserController@listing', 'App\\Http\\Controllers\\UserController@listing', 'user/listing', '2017-12-08 16:21:12', '2017-12-08 16:21:12');
INSERT INTO `permissions` VALUES ('5', '[POST]App\\Http\\Controllers\\UserController@password', 'App\\Http\\Controllers\\UserController@password', 'user/password/{id}', '2017-12-08 16:21:12', '2017-12-08 16:21:12');
INSERT INTO `permissions` VALUES ('7', '[GET]App\\Http\\Controllers\\KnowledgeController@listing', 'App\\Http\\Controllers\\KnowledgeController@listing', 'knowledge/listing', '2017-12-08 16:21:12', '2017-12-08 16:21:12');
INSERT INTO `permissions` VALUES ('8', '[GET]App\\Http\\Controllers\\KnowledgeController@copy', 'App\\Http\\Controllers\\KnowledgeController@copy', 'knowledge/copy/{id}', '2017-12-08 16:21:12', '2017-12-08 16:21:12');
INSERT INTO `permissions` VALUES ('9', '[GET]App\\Http\\Controllers\\KnowledgeController@submit', 'App\\Http\\Controllers\\KnowledgeController@submit', 'knowledge/submit/{id}', '2017-12-08 16:21:12', '2017-12-08 16:21:12');
INSERT INTO `permissions` VALUES ('10', '[GET]App\\Http\\Controllers\\KnowledgeController@audit', 'App\\Http\\Controllers\\KnowledgeController@audit', 'knowledge/audit/{id}', '2017-12-08 16:21:12', '2017-12-08 16:21:12');
INSERT INTO `permissions` VALUES ('11', '[GET]App\\Http\\Controllers\\KnowledgeController@publish', 'App\\Http\\Controllers\\KnowledgeController@publish', 'knowledge/publish/{id}', '2017-12-08 16:21:13', '2017-12-08 16:21:13');
INSERT INTO `permissions` VALUES ('12', '[GET]App\\Http\\Controllers\\KnowledgeCategoryController@moveUp', 'App\\Http\\Controllers\\KnowledgeCategoryController@moveUp', 'knowledge-category/move-up/{id}', '2017-12-08 16:21:13', '2017-12-08 16:21:13');
INSERT INTO `permissions` VALUES ('13', '[GET]App\\Http\\Controllers\\KnowledgeCategoryController@moveDown', 'App\\Http\\Controllers\\KnowledgeCategoryController@moveDown', 'knowledge-category/move-down/{id}', '2017-12-08 16:21:13', '2017-12-08 16:21:13');
INSERT INTO `permissions` VALUES ('14', '[GET]App\\Http\\Controllers\\UserController@index', 'App\\Http\\Controllers\\UserController@index', 'user', '2017-12-08 16:21:13', '2017-12-08 16:21:13');
INSERT INTO `permissions` VALUES ('15', '[GET]App\\Http\\Controllers\\UserController@create', 'App\\Http\\Controllers\\UserController@create', 'user/create', '2017-12-08 16:21:13', '2017-12-08 16:21:13');
INSERT INTO `permissions` VALUES ('16', '[POST]App\\Http\\Controllers\\UserController@store', 'App\\Http\\Controllers\\UserController@store', 'user', '2017-12-08 16:21:13', '2017-12-08 16:21:13');
INSERT INTO `permissions` VALUES ('17', '[GET]App\\Http\\Controllers\\UserController@show', 'App\\Http\\Controllers\\UserController@show', 'user/{user}', '2017-12-08 16:21:13', '2017-12-08 16:21:13');
INSERT INTO `permissions` VALUES ('18', '[GET]App\\Http\\Controllers\\UserController@edit', 'App\\Http\\Controllers\\UserController@edit', 'user/{user}/edit', '2017-12-08 16:21:13', '2017-12-08 16:21:13');
INSERT INTO `permissions` VALUES ('19', '[PUT]App\\Http\\Controllers\\UserController@update', 'App\\Http\\Controllers\\UserController@update', 'user/{user}', '2017-12-08 16:21:13', '2017-12-08 16:21:13');
INSERT INTO `permissions` VALUES ('20', '[DELETE]App\\Http\\Controllers\\UserController@destroy', 'App\\Http\\Controllers\\UserController@destroy', 'user/{user}', '2017-12-08 16:21:13', '2017-12-08 16:21:13');
INSERT INTO `permissions` VALUES ('21', '[GET]App\\Http\\Controllers\\OrganizationController@index', 'App\\Http\\Controllers\\OrganizationController@index', 'organization', '2017-12-08 16:21:13', '2017-12-08 16:21:13');
INSERT INTO `permissions` VALUES ('22', '[GET]App\\Http\\Controllers\\OrganizationController@create', 'App\\Http\\Controllers\\OrganizationController@create', 'organization/create', '2017-12-08 16:21:13', '2017-12-08 16:21:13');
INSERT INTO `permissions` VALUES ('23', '[POST]App\\Http\\Controllers\\OrganizationController@store', 'App\\Http\\Controllers\\OrganizationController@store', 'organization', '2017-12-08 16:21:13', '2017-12-08 16:21:13');
INSERT INTO `permissions` VALUES ('24', '[GET]App\\Http\\Controllers\\OrganizationController@show', 'App\\Http\\Controllers\\OrganizationController@show', 'organization/{organization}', '2017-12-08 16:21:13', '2017-12-08 16:21:13');
INSERT INTO `permissions` VALUES ('25', '[GET]App\\Http\\Controllers\\OrganizationController@edit', 'App\\Http\\Controllers\\OrganizationController@edit', 'organization/{organization}/edit', '2017-12-08 16:21:14', '2017-12-08 16:21:14');
INSERT INTO `permissions` VALUES ('26', '[PUT]App\\Http\\Controllers\\OrganizationController@update', 'App\\Http\\Controllers\\OrganizationController@update', 'organization/{organization}', '2017-12-08 16:21:14', '2017-12-08 16:21:14');
INSERT INTO `permissions` VALUES ('27', '[DELETE]App\\Http\\Controllers\\OrganizationController@destroy', 'App\\Http\\Controllers\\OrganizationController@destroy', 'organization/{organization}', '2017-12-08 16:21:14', '2017-12-08 16:21:14');
INSERT INTO `permissions` VALUES ('28', '[GET]App\\Http\\Controllers\\RoleController@index', 'App\\Http\\Controllers\\RoleController@index', 'role', '2017-12-08 16:21:14', '2017-12-08 16:21:14');
INSERT INTO `permissions` VALUES ('29', '[GET]App\\Http\\Controllers\\RoleController@create', 'App\\Http\\Controllers\\RoleController@create', 'role/create', '2017-12-08 16:21:14', '2017-12-08 16:21:14');
INSERT INTO `permissions` VALUES ('30', '[POST]App\\Http\\Controllers\\RoleController@store', 'App\\Http\\Controllers\\RoleController@store', 'role', '2017-12-08 16:21:14', '2017-12-08 16:21:14');
INSERT INTO `permissions` VALUES ('31', '[GET]App\\Http\\Controllers\\RoleController@show', 'App\\Http\\Controllers\\RoleController@show', 'role/{role}', '2017-12-08 16:21:14', '2017-12-08 16:21:14');
INSERT INTO `permissions` VALUES ('32', '[GET]App\\Http\\Controllers\\RoleController@edit', 'App\\Http\\Controllers\\RoleController@edit', 'role/{role}/edit', '2017-12-08 16:21:14', '2017-12-08 16:21:14');
INSERT INTO `permissions` VALUES ('33', '[PUT]App\\Http\\Controllers\\RoleController@update', 'App\\Http\\Controllers\\RoleController@update', 'role/{role}', '2017-12-08 16:21:14', '2017-12-08 16:21:14');
INSERT INTO `permissions` VALUES ('34', '[DELETE]App\\Http\\Controllers\\RoleController@destroy', 'App\\Http\\Controllers\\RoleController@destroy', 'role/{role}', '2017-12-08 16:21:14', '2017-12-08 16:21:14');
INSERT INTO `permissions` VALUES ('35', '[GET]App\\Http\\Controllers\\KnowledgeCategoryController@index', 'App\\Http\\Controllers\\KnowledgeCategoryController@index', 'knowledge-category', '2017-12-08 16:21:14', '2017-12-08 16:21:14');
INSERT INTO `permissions` VALUES ('36', '[GET]App\\Http\\Controllers\\KnowledgeCategoryController@create', 'App\\Http\\Controllers\\KnowledgeCategoryController@create', 'knowledge-category/create', '2017-12-08 16:21:14', '2017-12-08 16:21:14');
INSERT INTO `permissions` VALUES ('37', '[POST]App\\Http\\Controllers\\KnowledgeCategoryController@store', 'App\\Http\\Controllers\\KnowledgeCategoryController@store', 'knowledge-category', '2017-12-08 16:21:14', '2017-12-08 16:21:14');
INSERT INTO `permissions` VALUES ('38', '[GET]App\\Http\\Controllers\\KnowledgeCategoryController@show', 'App\\Http\\Controllers\\KnowledgeCategoryController@show', 'knowledge-category/{knowledge_category}', '2017-12-08 16:21:15', '2017-12-08 16:21:15');
INSERT INTO `permissions` VALUES ('39', '[GET]App\\Http\\Controllers\\KnowledgeCategoryController@edit', 'App\\Http\\Controllers\\KnowledgeCategoryController@edit', 'knowledge-category/{knowledge_category}/edit', '2017-12-08 16:21:15', '2017-12-08 16:21:15');
INSERT INTO `permissions` VALUES ('40', '[PUT]App\\Http\\Controllers\\KnowledgeCategoryController@update', 'App\\Http\\Controllers\\KnowledgeCategoryController@update', 'knowledge-category/{knowledge_category}', '2017-12-08 16:21:15', '2017-12-08 16:21:15');
INSERT INTO `permissions` VALUES ('41', '[DELETE]App\\Http\\Controllers\\KnowledgeCategoryController@destroy', 'App\\Http\\Controllers\\KnowledgeCategoryController@destroy', 'knowledge-category/{knowledge_category}', '2017-12-08 16:21:15', '2017-12-08 16:21:15');
INSERT INTO `permissions` VALUES ('42', '[GET]App\\Http\\Controllers\\KnowledgeController@index', 'App\\Http\\Controllers\\KnowledgeController@index', 'knowledge', '2017-12-08 16:21:15', '2017-12-08 16:21:15');
INSERT INTO `permissions` VALUES ('43', '[GET]App\\Http\\Controllers\\KnowledgeController@create', 'App\\Http\\Controllers\\KnowledgeController@create', 'knowledge/create', '2017-12-08 16:21:15', '2017-12-08 16:21:15');
INSERT INTO `permissions` VALUES ('44', '[POST]App\\Http\\Controllers\\KnowledgeController@store', 'App\\Http\\Controllers\\KnowledgeController@store', 'knowledge', '2017-12-08 16:21:15', '2017-12-08 16:21:15');
INSERT INTO `permissions` VALUES ('45', '[GET]App\\Http\\Controllers\\KnowledgeController@show', 'App\\Http\\Controllers\\KnowledgeController@show', 'knowledge/{knowledge}', '2017-12-08 16:21:15', '2017-12-08 16:21:15');
INSERT INTO `permissions` VALUES ('46', '[GET]App\\Http\\Controllers\\KnowledgeController@edit', 'App\\Http\\Controllers\\KnowledgeController@edit', 'knowledge/{knowledge}/edit', '2017-12-08 16:21:15', '2017-12-08 16:21:15');
INSERT INTO `permissions` VALUES ('47', '[PUT]App\\Http\\Controllers\\KnowledgeController@update', 'App\\Http\\Controllers\\KnowledgeController@update', 'knowledge/{knowledge}', '2017-12-08 16:21:15', '2017-12-08 16:21:15');
INSERT INTO `permissions` VALUES ('48', '[DELETE]App\\Http\\Controllers\\KnowledgeController@destroy', 'App\\Http\\Controllers\\KnowledgeController@destroy', 'knowledge/{knowledge}', '2017-12-08 16:21:15', '2017-12-08 16:21:15');
INSERT INTO `permissions` VALUES ('49', '[GET]App\\Http\\Controllers\\FAQController@index', 'App\\Http\\Controllers\\FAQController@index', 'faq', '2017-12-08 16:21:15', '2017-12-08 16:21:15');
INSERT INTO `permissions` VALUES ('50', '[GET]App\\Http\\Controllers\\FAQController@create', 'App\\Http\\Controllers\\FAQController@create', 'faq/create', '2017-12-08 16:21:15', '2017-12-08 16:21:15');
INSERT INTO `permissions` VALUES ('51', '[POST]App\\Http\\Controllers\\FAQController@store', 'App\\Http\\Controllers\\FAQController@store', 'faq', '2017-12-08 16:21:16', '2017-12-08 16:21:16');
INSERT INTO `permissions` VALUES ('52', '[GET]App\\Http\\Controllers\\FAQController@show', 'App\\Http\\Controllers\\FAQController@show', 'faq/{faq}', '2017-12-08 16:21:16', '2017-12-08 16:21:16');
INSERT INTO `permissions` VALUES ('53', '[GET]App\\Http\\Controllers\\FAQController@edit', 'App\\Http\\Controllers\\FAQController@edit', 'faq/{faq}/edit', '2017-12-08 16:21:16', '2017-12-08 16:21:16');
INSERT INTO `permissions` VALUES ('54', '[PUT]App\\Http\\Controllers\\FAQController@update', 'App\\Http\\Controllers\\FAQController@update', 'faq/{faq}', '2017-12-08 16:21:16', '2017-12-08 16:21:16');
INSERT INTO `permissions` VALUES ('55', '[DELETE]App\\Http\\Controllers\\FAQController@destroy', 'App\\Http\\Controllers\\FAQController@destroy', 'faq/{faq}', '2017-12-08 16:21:16', '2017-12-08 16:21:16');
INSERT INTO `permissions` VALUES ('56', '[GET]App\\Http\\Controllers\\NoticeController@index', 'App\\Http\\Controllers\\NoticeController@index', 'notice', '2017-12-08 16:21:16', '2017-12-08 16:21:16');
INSERT INTO `permissions` VALUES ('57', '[GET]App\\Http\\Controllers\\NoticeController@create', 'App\\Http\\Controllers\\NoticeController@create', 'notice/create', '2017-12-08 16:21:16', '2017-12-08 16:21:16');
INSERT INTO `permissions` VALUES ('58', '[POST]App\\Http\\Controllers\\NoticeController@store', 'App\\Http\\Controllers\\NoticeController@store', 'notice', '2017-12-08 16:21:16', '2017-12-08 16:21:16');
INSERT INTO `permissions` VALUES ('59', '[GET]App\\Http\\Controllers\\NoticeController@show', 'App\\Http\\Controllers\\NoticeController@show', 'notice/{notice}', '2017-12-08 16:21:16', '2017-12-08 16:21:16');
INSERT INTO `permissions` VALUES ('60', '[GET]App\\Http\\Controllers\\NoticeController@edit', 'App\\Http\\Controllers\\NoticeController@edit', 'notice/{notice}/edit', '2017-12-08 16:21:16', '2017-12-08 16:21:16');
INSERT INTO `permissions` VALUES ('61', '[PUT]App\\Http\\Controllers\\NoticeController@update', 'App\\Http\\Controllers\\NoticeController@update', 'notice/{notice}', '2017-12-08 16:21:16', '2017-12-08 16:21:16');
INSERT INTO `permissions` VALUES ('62', '[DELETE]App\\Http\\Controllers\\NoticeController@destroy', 'App\\Http\\Controllers\\NoticeController@destroy', 'notice/{notice}', '2017-12-08 16:21:16', '2017-12-08 16:21:16');
INSERT INTO `permissions` VALUES ('63', '[GET]App\\Http\\Controllers\\PermissionController@retrieve', 'App\\Http\\Controllers\\PermissionController@retrieve', 'permission/retrieve', '2017-12-08 16:30:57', '2017-12-08 16:30:57');

-- ----------------------------
-- Table structure for permission_role
-- ----------------------------
DROP TABLE IF EXISTS `permission_role`;
CREATE TABLE `permission_role` (
  `permission_id` int(10) unsigned NOT NULL,
  `role_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`permission_id`,`role_id`),
  KEY `permission_role_role_id_foreign` (`role_id`),
  CONSTRAINT `permission_role_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `permission_role_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------
-- Records of permission_role
-- ----------------------------
INSERT INTO `permission_role` VALUES ('1', '1');
INSERT INTO `permission_role` VALUES ('2', '1');
INSERT INTO `permission_role` VALUES ('3', '1');
INSERT INTO `permission_role` VALUES ('4', '1');
INSERT INTO `permission_role` VALUES ('5', '1');
INSERT INTO `permission_role` VALUES ('7', '1');
INSERT INTO `permission_role` VALUES ('8', '1');
INSERT INTO `permission_role` VALUES ('9', '1');
INSERT INTO `permission_role` VALUES ('10', '1');
INSERT INTO `permission_role` VALUES ('11', '1');
INSERT INTO `permission_role` VALUES ('12', '1');
INSERT INTO `permission_role` VALUES ('13', '1');
INSERT INTO `permission_role` VALUES ('14', '1');
INSERT INTO `permission_role` VALUES ('15', '1');
INSERT INTO `permission_role` VALUES ('16', '1');
INSERT INTO `permission_role` VALUES ('17', '1');
INSERT INTO `permission_role` VALUES ('18', '1');
INSERT INTO `permission_role` VALUES ('19', '1');
INSERT INTO `permission_role` VALUES ('20', '1');
INSERT INTO `permission_role` VALUES ('21', '1');
INSERT INTO `permission_role` VALUES ('22', '1');
INSERT INTO `permission_role` VALUES ('23', '1');
INSERT INTO `permission_role` VALUES ('24', '1');
INSERT INTO `permission_role` VALUES ('25', '1');
INSERT INTO `permission_role` VALUES ('26', '1');
INSERT INTO `permission_role` VALUES ('27', '1');
INSERT INTO `permission_role` VALUES ('28', '1');
INSERT INTO `permission_role` VALUES ('29', '1');
INSERT INTO `permission_role` VALUES ('30', '1');
INSERT INTO `permission_role` VALUES ('31', '1');
INSERT INTO `permission_role` VALUES ('32', '1');
INSERT INTO `permission_role` VALUES ('33', '1');
INSERT INTO `permission_role` VALUES ('34', '1');
INSERT INTO `permission_role` VALUES ('35', '1');
INSERT INTO `permission_role` VALUES ('36', '1');
INSERT INTO `permission_role` VALUES ('37', '1');
INSERT INTO `permission_role` VALUES ('38', '1');
INSERT INTO `permission_role` VALUES ('39', '1');
INSERT INTO `permission_role` VALUES ('40', '1');
INSERT INTO `permission_role` VALUES ('41', '1');
INSERT INTO `permission_role` VALUES ('42', '1');
INSERT INTO `permission_role` VALUES ('43', '1');
INSERT INTO `permission_role` VALUES ('44', '1');
INSERT INTO `permission_role` VALUES ('45', '1');
INSERT INTO `permission_role` VALUES ('46', '1');
INSERT INTO `permission_role` VALUES ('47', '1');
INSERT INTO `permission_role` VALUES ('48', '1');
INSERT INTO `permission_role` VALUES ('49', '1');
INSERT INTO `permission_role` VALUES ('50', '1');
INSERT INTO `permission_role` VALUES ('51', '1');
INSERT INTO `permission_role` VALUES ('52', '1');
INSERT INTO `permission_role` VALUES ('53', '1');
INSERT INTO `permission_role` VALUES ('54', '1');
INSERT INTO `permission_role` VALUES ('55', '1');
INSERT INTO `permission_role` VALUES ('56', '1');
INSERT INTO `permission_role` VALUES ('57', '1');
INSERT INTO `permission_role` VALUES ('58', '1');
INSERT INTO `permission_role` VALUES ('59', '1');
INSERT INTO `permission_role` VALUES ('60', '1');
INSERT INTO `permission_role` VALUES ('61', '1');
INSERT INTO `permission_role` VALUES ('62', '1');
INSERT INTO `permission_role` VALUES ('63', '1');

-- ----------------------------
-- Table structure for roles
-- ----------------------------
DROP TABLE IF EXISTS `roles`;
CREATE TABLE `roles` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `display_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `roles_name_unique` (`name`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------
-- Records of roles
-- ----------------------------
INSERT INTO `roles` VALUES ('1', 'Admin', '系统管理员', '系统管理员可以执行任何操作', '2017-12-08 16:20:47', '2017-12-08 16:20:47');
INSERT INTO `roles` VALUES ('2', 'Editor', '编辑', null, '2017-12-08 17:23:01', '2017-12-08 17:23:01');

-- ----------------------------
-- Table structure for role_user
-- ----------------------------
DROP TABLE IF EXISTS `role_user`;
CREATE TABLE `role_user` (
  `user_id` int(10) unsigned NOT NULL,
  `role_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`user_id`,`role_id`),
  KEY `role_user_role_id_foreign` (`role_id`),
  CONSTRAINT `role_user_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `role_user_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------
-- Records of role_user
-- ----------------------------
INSERT INTO `role_user` VALUES ('1', '1');

-- ----------------------------
-- Table structure for users
-- ----------------------------
DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `organization_id` int(10) NOT NULL DEFAULT '0' COMMENT '所属组织架构',
  `email` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `mobile` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `operator` int(11) DEFAULT '0' COMMENT '操作人',
  `status` tinyint(4) DEFAULT '1' COMMENT '状态 1可以 2禁用',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `user_email_unique` (`email`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------
-- Records of users
-- ----------------------------
INSERT INTO `users` VALUES ('1', '总旋风', '31', 'admin@baicheng.com', '15911006066', '$2y$10$q4BPwflIuQ5hf3gmX1eS.Of11KYKvRRixDquSNoJbJkZGQf3aZ7.G', 'LzscCPJKYGtmr7YDoE4NKYbGhewVxzhe0oKwNYkqAzPdZCQLCSWpJRDhUXmd', '1', '1', '2017-11-28 18:22:29', '2017-12-07 17:18:56');
INSERT INTO `users` VALUES ('2', '孙小坦', '30', 'sunxiaotan@baicheng.com', '18611911257', '$2y$10$Hx69y2xvLFVmIeTXBsMDpu..TrWieVG02yjPS20o5tbPB7tPRBtr6', null, '1', '2', '2017-12-07 17:25:06', '2017-12-07 17:30:48');
INSERT INTO `users` VALUES ('3', '刘丽君', '32', 'liulijun@baicheng.com', '13801060351', '$2y$10$/cvLK8W1oWgR8KQo/trerODPUnpNGNqdVGWEnWE4FtIRqtssQmcki', null, '1', '2', '2017-12-07 17:30:17', '2017-12-07 17:30:17');
