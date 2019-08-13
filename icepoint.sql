/*
 Navicat Premium Data Transfer

 Source Server         : cqbd123.cn
 Source Server Type    : MySQL
 Source Server Version : 50726
 Source Host           : cqbd123.cn:3306
 Source Schema         : icepoint

 Target Server Type    : MySQL
 Target Server Version : 50726
 File Encoding         : 65001

 Date: 13/08/2019 14:56:53
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for card
-- ----------------------------
DROP TABLE IF EXISTS `card`;
CREATE TABLE `card`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `code` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `pass` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `status` tinyint(4) NOT NULL DEFAULT 10,
  `cardtypeid` int(11) NULL DEFAULT NULL,
  `cardtypename` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `qrcode` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `codepre` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `codeno` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `codelen` tinyint(255) NULL DEFAULT NULL,
  `createby` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT '导入',
  `createtime` bigint(20) NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 25001 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for xiao_admin
-- ----------------------------
DROP TABLE IF EXISTS `xiao_admin`;
CREATE TABLE `xiao_admin`  (
  `userid` smallint(5) UNSIGNED NOT NULL AUTO_INCREMENT,
  `username` varchar(20) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `password` varchar(32) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `roleid` smallint(5) NULL DEFAULT 0,
  `realname` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '',
  `auth` text CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `list_size` smallint(5) NOT NULL,
  `left_width` smallint(5) NOT NULL DEFAULT 150,
  PRIMARY KEY (`userid`) USING BTREE,
  INDEX `username`(`username`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 3 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for xiao_block
-- ----------------------------
DROP TABLE IF EXISTS `xiao_block`;
CREATE TABLE `xiao_block`  (
  `id` smallint(8) UNSIGNED NOT NULL AUTO_INCREMENT,
  `type` tinyint(1) NOT NULL,
  `name` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `content` mediumtext CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 7 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for xiao_card
-- ----------------------------
DROP TABLE IF EXISTS `xiao_card`;
CREATE TABLE `xiao_card`  (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '主键',
  `cardtypeid` int(11) NOT NULL COMMENT '卡券类型编号',
  `cardtypename` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '卡券名称',
  `customermobile` varchar(20) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '绑定用户手机号，销售时绑定客户手机',
  `customerid` int(11) NULL DEFAULT NULL COMMENT '客户编号，激活后绑定才有值',
  `status` tinyint(4) NOT NULL COMMENT '卡券状态，未销售（初始状态）：10，销售：20，激活：30，失效（作废）：40',
  `exptime` bigint(20) NULL DEFAULT NULL COMMENT '过期时间，激活时：当前时间+卡券提货有效天数',
  `activetime` bigint(20) NULL DEFAULT NULL COMMENT '激活时间',
  `saletime` bigint(20) NULL DEFAULT NULL COMMENT '销售时间',
  `saleby` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '销售员',
  `code` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '卡券编码',
  `pass` varchar(30) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '卡券密码',
  `qrcode` varchar(32) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '卡券二维码',
  `codepre` varchar(30) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '卡券编码前缀',
  `codeno` bigint(20) NOT NULL COMMENT '卡券编码数字编号',
  `codelen` tinyint(4) NOT NULL COMMENT '卡券号码字符长度',
  `createtime` bigint(20) NOT NULL COMMENT '激活时间',
  `createby` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '创建人',
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `itx_card_qrcode`(`qrcode`) USING BTREE,
  INDEX `itx_card_code_info`(`codepre`, `codeno`, `codelen`) USING BTREE,
  INDEX `itx_card_mobile`(`customermobile`) USING BTREE,
  INDEX `itx_card_query`(`cardtypeid`, `status`, `code`, `customermobile`) USING BTREE,
  INDEX `itx_card_status`(`status`) USING BTREE,
  INDEX `itx_card_code`(`code`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 32772 CHARACTER SET = utf8 COLLATE = utf8_general_ci COMMENT = '卡券表' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for xiao_card_item
-- ----------------------------
DROP TABLE IF EXISTS `xiao_card_item`;
CREATE TABLE `xiao_card_item`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `cardid` int(11) NOT NULL COMMENT '卡券编号',
  `sku` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '商品sku',
  `cardtypeid` int(11) NOT NULL COMMENT '卡券类型编号',
  `productname` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '商品名称',
  `quantity` int(11) NOT NULL COMMENT '商品数量',
  `validquantity` int(11) NOT NULL COMMENT '可提数量',
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `itx_carditem_cardid`(`cardid`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 5 CHARACTER SET = utf8 COLLATE = utf8_general_ci COMMENT = '卡券商品明细' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for xiao_card_type
-- ----------------------------
DROP TABLE IF EXISTS `xiao_card_type`;
CREATE TABLE `xiao_card_type`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '卡券类型名称',
  `begintime` bigint(20) NOT NULL COMMENT '有效激活开始时间',
  `endtime` bigint(20) NOT NULL COMMENT '有效激活结束时间',
  `vailddays` int(11) NOT NULL COMMENT '提货有效期（天）',
  `createtime` bigint(20) NOT NULL COMMENT '创建时间',
  `createby` varchar(30) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '创建人',
  `updatetime` bigint(20) NULL DEFAULT NULL COMMENT '修改时间',
  `updateby` varchar(30) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '修改人',
  `canedit` bit(1) NOT NULL DEFAULT b'1' COMMENT '如果已经生成卡券，则不能修改，值为：0',
  `canbuild` bit(1) NOT NULL DEFAULT b'0' COMMENT '如果已经绑定商品，则可以生成卡券',
  `isvalid` bit(1) NOT NULL DEFAULT b'1' COMMENT '状态，1启用，0作废',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 6 CHARACTER SET = utf8 COLLATE = utf8_general_ci COMMENT = '卡券类型' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for xiao_card_type_item
-- ----------------------------
DROP TABLE IF EXISTS `xiao_card_type_item`;
CREATE TABLE `xiao_card_type_item`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `sku` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '商品sku',
  `cardtypeid` int(11) NOT NULL COMMENT '卡券类型编号',
  `productname` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '商品名称',
  `quantity` int(11) NOT NULL COMMENT '商品数量',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 5 CHARACTER SET = utf8 COLLATE = utf8_general_ci COMMENT = '卡券类型商品明细' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for xiao_category
-- ----------------------------
DROP TABLE IF EXISTS `xiao_category`;
CREATE TABLE `xiao_category`  (
  `catid` smallint(5) UNSIGNED NOT NULL AUTO_INCREMENT,
  `typeid` tinyint(1) NOT NULL,
  `modelid` smallint(5) UNSIGNED NOT NULL DEFAULT 0,
  `parentid` smallint(5) UNSIGNED NOT NULL DEFAULT 0,
  `child` tinyint(1) UNSIGNED NOT NULL DEFAULT 0,
  `childids` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `catname` varchar(30) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `image` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `content` mediumtext CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `seo_title` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `seo_keywords` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `seo_description` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `catdir` varchar(30) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `http` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `items` mediumint(8) UNSIGNED NOT NULL DEFAULT 0,
  `listorder` smallint(5) UNSIGNED NOT NULL DEFAULT 0,
  `ismenu` tinyint(1) UNSIGNED NOT NULL DEFAULT 1,
  `ispost` smallint(2) NOT NULL,
  `verify` smallint(2) NOT NULL DEFAULT 0,
  `islook` smallint(2) NOT NULL,
  `listtpl` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `showtpl` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `pagetpl` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `pagesize` smallint(5) NOT NULL,
  PRIMARY KEY (`catid`) USING BTREE,
  INDEX `listorder`(`listorder`, `catid`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 18 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for xiao_comment
-- ----------------------------
DROP TABLE IF EXISTS `xiao_comment`;
CREATE TABLE `xiao_comment`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `orderid` int(11) NOT NULL COMMENT '关联订单编号',
  `createtime` bigint(20) NOT NULL COMMENT '评论时间',
  `isontime` bit(1) NOT NULL COMMENT '是否准时到货',
  `iscontact` bit(1) NOT NULL COMMENT '是否主动联系',
  `isdestination` bit(1) NOT NULL COMMENT '是否送到目的地',
  `isattitude` bit(1) NOT NULL COMMENT '服务态度是否热情',
  `isclothing` bit(1) NOT NULL COMMENT '衣着是否正规',
  `remark` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '备注',
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `itx_comment_orderid`(`orderid`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 2 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for xiao_content
-- ----------------------------
DROP TABLE IF EXISTS `xiao_content`;
CREATE TABLE `xiao_content`  (
  `id` mediumint(8) UNSIGNED NOT NULL AUTO_INCREMENT,
  `catid` smallint(5) UNSIGNED NOT NULL DEFAULT 0,
  `modelid` smallint(5) NOT NULL,
  `title` varchar(80) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '',
  `thumb` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '',
  `keywords` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '',
  `description` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `listorder` tinyint(3) UNSIGNED NOT NULL DEFAULT 0,
  `status` tinyint(2) UNSIGNED NOT NULL DEFAULT 1,
  `hits` smallint(5) UNSIGNED NOT NULL DEFAULT 0,
  `username` char(20) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `time` int(10) UNSIGNED NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `catid`(`catid`, `listorder`, `time`) USING BTREE,
  INDEX `time`(`catid`, `time`) USING BTREE,
  INDEX `status`(`catid`, `status`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 38 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for xiao_content_news
-- ----------------------------
DROP TABLE IF EXISTS `xiao_content_news`;
CREATE TABLE `xiao_content_news`  (
  `id` mediumint(8) NOT NULL,
  `catid` smallint(5) NOT NULL,
  `content` mediumtext CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `catid`(`catid`) USING BTREE
) ENGINE = MyISAM CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for xiao_content_product
-- ----------------------------
DROP TABLE IF EXISTS `xiao_content_product`;
CREATE TABLE `xiao_content_product`  (
  `id` mediumint(8) NOT NULL,
  `catid` smallint(5) NOT NULL,
  `content` mediumtext CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `catid`(`catid`) USING BTREE
) ENGINE = MyISAM CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for xiao_customer
-- ----------------------------
DROP TABLE IF EXISTS `xiao_customer`;
CREATE TABLE `xiao_customer`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT '用户姓名',
  `nickname` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT '用户昵称',
  `mobile` varchar(15) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '手机号码',
  `openid` varchar(128) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '微信OpenId',
  `unionid` varchar(128) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '微信Unionid',
  `headimg` varchar(500) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '头像图片',
  `createtime` bigint(20) NOT NULL COMMENT '创建时间',
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `itx_customer_openid`(`openid`) USING BTREE,
  UNIQUE INDEX `itx_customer_mobile`(`mobile`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 47 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for xiao_customer_address
-- ----------------------------
DROP TABLE IF EXISTS `xiao_customer_address`;
CREATE TABLE `xiao_customer_address`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `customerid` int(255) NOT NULL COMMENT '用户编号',
  `province` varchar(128) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT '省份',
  `city` varchar(128) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '城市',
  `area` varchar(128) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '地域',
  `address` varchar(512) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '地址',
  `isDefault` tinyint(1) NULL DEFAULT NULL COMMENT '是否默认',
  `createtime` bigint(20) NOT NULL COMMENT '创建时间',
  `mobile` varchar(11) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '收货手机',
  `name` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '收货人姓名',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 15 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for xiao_form_comment
-- ----------------------------
DROP TABLE IF EXISTS `xiao_form_comment`;
CREATE TABLE `xiao_form_comment`  (
  `id` mediumint(8) NOT NULL AUTO_INCREMENT,
  `cid` mediumint(8) NOT NULL,
  `userid` mediumint(8) NOT NULL,
  `username` char(20) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `listorder` tinyint(3) UNSIGNED NOT NULL DEFAULT 0,
  `status` tinyint(2) UNSIGNED NOT NULL DEFAULT 1,
  `time` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `ip` char(20) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `pinglunneirong` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `listorder`(`listorder`) USING BTREE,
  INDEX `status`(`status`) USING BTREE,
  INDEX `time`(`time`) USING BTREE,
  INDEX `userid`(`userid`) USING BTREE,
  INDEX `cid`(`cid`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 1 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for xiao_form_gestbook
-- ----------------------------
DROP TABLE IF EXISTS `xiao_form_gestbook`;
CREATE TABLE `xiao_form_gestbook`  (
  `id` mediumint(8) NOT NULL AUTO_INCREMENT,
  `cid` mediumint(8) NOT NULL,
  `userid` mediumint(8) NOT NULL,
  `username` char(20) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `listorder` tinyint(3) UNSIGNED NOT NULL DEFAULT 0,
  `status` tinyint(2) UNSIGNED NOT NULL DEFAULT 1,
  `time` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `ip` char(20) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `nindexingming` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `lianxiQQ` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `liuyanneirong` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `listorder`(`listorder`) USING BTREE,
  INDEX `status`(`status`) USING BTREE,
  INDEX `time`(`time`) USING BTREE,
  INDEX `userid`(`userid`) USING BTREE,
  INDEX `cid`(`cid`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 1 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for xiao_kv
-- ----------------------------
DROP TABLE IF EXISTS `xiao_kv`;
CREATE TABLE `xiao_kv`  (
  `key` varchar(20) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `value` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL
) ENGINE = InnoDB CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for xiao_log
-- ----------------------------
DROP TABLE IF EXISTS `xiao_log`;
CREATE TABLE `xiao_log`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `content` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `target` int(11) NOT NULL,
  `type` varchar(20) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `createtime` datetime(0) NOT NULL DEFAULT CURRENT_TIMESTAMP(0),
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `itx_log_type`(`type`) USING BTREE,
  INDEX `itx_log_target`(`target`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 58088 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for xiao_member
-- ----------------------------
DROP TABLE IF EXISTS `xiao_member`;
CREATE TABLE `xiao_member`  (
  `id` mediumint(8) UNSIGNED NOT NULL AUTO_INCREMENT,
  `username` char(20) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '',
  `password` char(32) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '',
  `email` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `avatar` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '',
  `modelid` smallint(5) NOT NULL,
  `regdate` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `regip` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `username`(`username`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 1 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for xiao_member_geren
-- ----------------------------
DROP TABLE IF EXISTS `xiao_member_geren`;
CREATE TABLE `xiao_member_geren`  (
  `id` mediumint(8) NOT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = MyISAM CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Fixed;

-- ----------------------------
-- Table structure for xiao_model
-- ----------------------------
DROP TABLE IF EXISTS `xiao_model`;
CREATE TABLE `xiao_model`  (
  `modelid` smallint(5) UNSIGNED NOT NULL AUTO_INCREMENT,
  `typeid` tinyint(3) NOT NULL,
  `modelname` char(30) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `tablename` char(20) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `listtpl` varchar(30) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `showtpl` varchar(30) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `joinid` smallint(5) NULL DEFAULT NULL,
  `setting` text CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
  PRIMARY KEY (`modelid`) USING BTREE,
  INDEX `typeid`(`typeid`) USING BTREE,
  INDEX `joinid`(`joinid`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 6 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for xiao_model_field
-- ----------------------------
DROP TABLE IF EXISTS `xiao_model_field`;
CREATE TABLE `xiao_model_field`  (
  `fieldid` mediumint(8) UNSIGNED NOT NULL AUTO_INCREMENT,
  `modelid` smallint(5) UNSIGNED NOT NULL DEFAULT 0,
  `field` varchar(20) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `name` varchar(30) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `isshow` tinyint(1) UNSIGNED NOT NULL DEFAULT 1,
  `tips` text CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `pattern` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `errortips` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `formtype` varchar(20) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `setting` mediumtext CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `listorder` mediumint(8) UNSIGNED NOT NULL DEFAULT 0,
  `disabled` tinyint(1) UNSIGNED NOT NULL DEFAULT 0,
  PRIMARY KEY (`fieldid`) USING BTREE,
  INDEX `modelid`(`modelid`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 7 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for xiao_order
-- ----------------------------
DROP TABLE IF EXISTS `xiao_order`;
CREATE TABLE `xiao_order`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `customerid` int(11) NOT NULL COMMENT '客户编号',
  `createtime` bigint(20) NOT NULL COMMENT '创建时间',
  `contact` varchar(30) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '联系人',
  `mobile` varchar(30) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '联系电话',
  `address` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '配送地址',
  `province` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '配送：省',
  `city` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '配送：市',
  `area` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '配送：区',
  `status` tinyint(4) NOT NULL COMMENT '订单状态，待发货：10，待揽收：20，待配送：30，配送中：40，待签收：50，已签收：60，已完成（评价）：70，关闭：-10',
  `remark` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '用户留言',
  `commentlevel` tinyint(4) NULL DEFAULT NULL COMMENT '评价等级',
  `comment` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '评价内容',
  `commenttime` bigint(20) NULL DEFAULT NULL COMMENT '评论时间',
  `erporderid` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT 'ERP订单编号',
  `expressname` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '快递名称',
  `expresscode` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '快递公司编码',
  `expressno` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '快递单号',
  `isdeleted` bit(1) NOT NULL DEFAULT b'0' COMMENT '是否删除',
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `itx_order_status`(`status`) USING BTREE,
  INDEX `itx_order_erporderid`(`erporderid`) USING BTREE,
  INDEX `itx_order_area`(`area`) USING BTREE,
  INDEX `itx_order_mobile`(`mobile`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 17 CHARACTER SET = utf8 COLLATE = utf8_general_ci COMMENT = '卡券订单' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for xiao_order_item
-- ----------------------------
DROP TABLE IF EXISTS `xiao_order_item`;
CREATE TABLE `xiao_order_item`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `sku` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '商品sku',
  `orderid` int(11) NOT NULL COMMENT '订单编号 ',
  `cardid` int(11) NOT NULL COMMENT '来源卡券编号',
  `cardtypeid` int(11) NOT NULL COMMENT '所属卡券类型编号',
  `productname` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '商品名称',
  `quantity` int(11) NOT NULL COMMENT '商品数量',
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `itx_orderitem_orderid`(`orderid`) USING BTREE,
  INDEX `itx_orderitem_pdname`(`productname`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 17 CHARACTER SET = utf8 COLLATE = utf8_general_ci COMMENT = '订单商品明细' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for xiao_order_log
-- ----------------------------
DROP TABLE IF EXISTS `xiao_order_log`;
CREATE TABLE `xiao_order_log`  (
  `id` int(11) NULL DEFAULT NULL,
  `orderid` int(11) NULL DEFAULT NULL COMMENT '订单编号',
  `content` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '日志内容',
  `createtime` bigint(20) NULL DEFAULT NULL COMMENT '创建时间',
  `createby` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '创建人'
) ENGINE = InnoDB CHARACTER SET = utf8 COLLATE = utf8_general_ci COMMENT = '订单日志表' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for xiao_product
-- ----------------------------
DROP TABLE IF EXISTS `xiao_product`;
CREATE TABLE `xiao_product`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `sku` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '商品sku',
  `title` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '商品名称',
  `subtitle` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '商品第二名称',
  `thumb` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '商品缩略图',
  `img` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '商品大图',
  `createtime` bigint(20) NOT NULL COMMENT '商品创建时间',
  `synctime` bigint(20) NOT NULL COMMENT '商品同步时间',
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `itx_product_sku`(`sku`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 13 CHARACTER SET = utf8 COLLATE = utf8_general_ci COMMENT = '同步有赞商品' ROW_FORMAT = Dynamic;

-- ----------------------------
-- View structure for xiao_vi_card_type
-- ----------------------------
DROP VIEW IF EXISTS `xiao_vi_card_type`;
CREATE ALGORITHM = UNDEFINED SQL SECURITY DEFINER VIEW `xiao_vi_card_type` AS select `xiao_card_type`.`id` AS `id`,`xiao_card_type`.`name` AS `name`,`xiao_card_type`.`begintime` AS `begintime`,`xiao_card_type`.`endtime` AS `endtime`,`xiao_card_type`.`vailddays` AS `vailddays`,`xiao_card_type`.`createtime` AS `createtime`,`xiao_card_type`.`createby` AS `createby`,`xiao_card_type`.`updatetime` AS `updatetime`,`xiao_card_type`.`updateby` AS `updateby`,`xiao_card_type`.`canedit` AS `canedit`,group_concat(concat(`xiao_card_type_item`.`productname`,'x',`xiao_card_type_item`.`quantity`) separator '，') AS `description`,`xiao_card_type`.`canbuild` AS `canbuild`,`xiao_card_type`.`isvalid` AS `isvalid` from (`xiao_card_type` left join `xiao_card_type_item` on((`xiao_card_type`.`id` = `xiao_card_type_item`.`cardtypeid`))) group by `xiao_card_type`.`id`;

-- ----------------------------
-- View structure for xiao_vi_comment
-- ----------------------------
DROP VIEW IF EXISTS `xiao_vi_comment`;
CREATE ALGORITHM = UNDEFINED SQL SECURITY DEFINER VIEW `xiao_vi_comment` AS select `cu`.`name` AS `name`,`cu`.`nickname` AS `nickname`,`cu`.`mobile` AS `mobile`,`co`.`id` AS `id`,`co`.`orderid` AS `orderid`,`co`.`createtime` AS `createtime`,`co`.`isontime` AS `isontime`,`co`.`iscontact` AS `iscontact`,`co`.`isdestination` AS `isdestination`,`co`.`isattitude` AS `isattitude`,`co`.`isclothing` AS `isclothing`,`co`.`remark` AS `remark` from ((`xiao_comment` `co` join `xiao_order` `o` on((`co`.`orderid` = `o`.`id`))) join `xiao_customer` `cu` on((`o`.`customerid` = `cu`.`id`)));

-- ----------------------------
-- View structure for xiao_vi_order
-- ----------------------------
DROP VIEW IF EXISTS `xiao_vi_order`;
CREATE ALGORITHM = UNDEFINED SQL SECURITY DEFINER VIEW `xiao_vi_order` AS select `o`.`id` AS `id`,`o`.`createtime` AS `createtime`,`o`.`contact` AS `contact`,`o`.`mobile` AS `mobile`,`o`.`address` AS `address`,`o`.`area` AS `area`,`o`.`city` AS `city`,`o`.`province` AS `province`,`o`.`status` AS `status`,`i`.`productname` AS `productname`,`i`.`quantity` AS `quantity`,`c`.`name` AS `name`,`c`.`mobile` AS `customermobile` from ((`xiao_order` `o` join `xiao_order_item` `i` on((`o`.`id` = `i`.`orderid`))) join `xiao_customer` `c` on((`o`.`customerid` = `c`.`id`))) where (`o`.`id` = `i`.`orderid`);

-- ----------------------------
-- View structure for xiao_vi_order_item
-- ----------------------------
DROP VIEW IF EXISTS `xiao_vi_order_item`;
CREATE ALGORITHM = UNDEFINED SQL SECURITY DEFINER VIEW `xiao_vi_order_item` AS select `oi`.`productname` AS `productname`,`oi`.`quantity` AS `quantity`,`pd`.`title` AS `title`,`pd`.`subtitle` AS `subtitle`,`pd`.`thumb` AS `thumb`,`oi`.`orderid` AS `orderid` from (`xiao_order_item` `oi` join `xiao_product` `pd` on((`oi`.`sku` = `pd`.`sku`)));

-- ----------------------------
-- View structure for xiao_vi_wealth
-- ----------------------------
DROP VIEW IF EXISTS `xiao_vi_wealth`;
CREATE ALGORITHM = UNDEFINED SQL SECURITY DEFINER VIEW `xiao_vi_wealth` AS select `ci`.`id` AS `id`,`ci`.`productname` AS `productname`,`ci`.`quantity` AS `quantity`,`ci`.`validquantity` AS `validquantity`,`ca`.`exptime` AS `exptime`,`cu`.`name` AS `name`,`cu`.`nickname` AS `nickname`,`cu`.`mobile` AS `mobile`,`ca`.`cardtypename` AS `cardtypename` from ((`xiao_card_item` `ci` join `xiao_card` `ca` on((`ci`.`cardid` = `ca`.`id`))) join `xiao_customer` `cu` on((`ca`.`customerid` = `cu`.`id`)));

-- ----------------------------
-- View structure for xiao_vi_wealth_valid
-- ----------------------------
DROP VIEW IF EXISTS `xiao_vi_wealth_valid`;
CREATE ALGORITHM = UNDEFINED SQL SECURITY DEFINER VIEW `xiao_vi_wealth_valid` AS select `i`.`productname` AS `productname`,`i`.`quantity` AS `quantity`,`i`.`validquantity` AS `validquantity`,`i`.`sku` AS `sku`,`c`.`cardtypename` AS `cardtypename`,`c`.`exptime` AS `exptime`,`c`.`activetime` AS `activetime`,`c`.`customerid` AS `customerid`,`c`.`id` AS `cardid`,`c`.`cardtypeid` AS `cardtypeid`,`p`.`thumb` AS `thumb`,`i`.`id` AS `carditemid` from ((`xiao_card` `c` join `xiao_card_item` `i` on((`i`.`cardid` = `c`.`id`))) join `xiao_product` `p` on((`i`.`sku` = `p`.`sku`))) where ((`c`.`status` = 30) and (`c`.`exptime` > unix_timestamp()) and (`i`.`validquantity` > 0));

SET FOREIGN_KEY_CHECKS = 1;
