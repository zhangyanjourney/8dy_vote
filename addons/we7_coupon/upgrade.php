<?php
$sql="CREATE TABLE IF NOT EXISTS `ims_activity_clerk_menu` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) NOT NULL,
  `displayorder` int(4) NOT NULL,
  `pid` int(6) NOT NULL,
  `group_name` varchar(20) NOT NULL,
  `title` varchar(20) NOT NULL,
  `icon` varchar(50) NOT NULL,
  `url` varchar(255) NOT NULL,
  `type` varchar(20) NOT NULL,
  `permission` varchar(50) NOT NULL,
  `system` int(2) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_activity_clerks` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(10) unsigned NOT NULL,
  `uid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '关联users表uid',
  `storeid` int(10) unsigned NOT NULL DEFAULT '0',
  `name` varchar(20) NOT NULL,
  `password` varchar(20) NOT NULL,
  `mobile` varchar(20) NOT NULL,
  `openid` varchar(50) NOT NULL,
  `nickname` varchar(30) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `uniacid` (`uniacid`),
  KEY `password` (`password`),
  KEY `openid` (`openid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='积分兑换店员表';
CREATE TABLE IF NOT EXISTS `ims_activity_exchange` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) NOT NULL,
  `title` varchar(100) NOT NULL COMMENT '物品名称',
  `description` text NOT NULL COMMENT '描述信息',
  `thumb` varchar(500) NOT NULL COMMENT '缩略图',
  `type` tinyint(1) unsigned NOT NULL COMMENT '物品类型，1系统卡券，2微信呢卡券，3实物，4虚拟物品(未启用)，5营销模块操作次数',
  `extra` varchar(3000) NOT NULL DEFAULT '' COMMENT '兑换产品属性 卡券自增id',
  `credit` int(10) unsigned NOT NULL COMMENT '兑换积分数量',
  `credittype` varchar(10) NOT NULL COMMENT '兑换积分类型',
  `pretotal` int(11) NOT NULL COMMENT '每个人最大兑换次数',
  `num` int(11) NOT NULL COMMENT '已兑换礼品数量',
  `total` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '总量',
  `status` tinyint(3) unsigned NOT NULL DEFAULT '1' COMMENT '状态',
  `starttime` int(10) unsigned NOT NULL,
  `endtime` int(10) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `extra` (`extra`(333))
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='真实物品兑换表';
CREATE TABLE IF NOT EXISTS `ims_activity_exchange_trades` (
  `tid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(10) unsigned NOT NULL COMMENT '统一公号',
  `uid` int(10) unsigned NOT NULL COMMENT '用户(粉丝)id',
  `exid` int(10) unsigned NOT NULL COMMENT '兑换产品 exchangeid',
  `type` int(10) unsigned NOT NULL,
  `createtime` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '交换记录创建时间',
  PRIMARY KEY (`tid`),
  KEY `uniacid` (`uniacid`,`uid`,`exid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='真实物品兑换记录表';
CREATE TABLE IF NOT EXISTS `ims_activity_exchange_trades_shipping` (
  `tid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(10) unsigned NOT NULL,
  `exid` int(10) unsigned NOT NULL,
  `uid` int(10) unsigned NOT NULL,
  `status` tinyint(4) NOT NULL DEFAULT '0' COMMENT '订单状态，0为正常，-1为关闭，1为已发货，2为已完成',
  `createtime` int(10) unsigned NOT NULL,
  `province` varchar(30) NOT NULL,
  `city` varchar(30) NOT NULL,
  `district` varchar(30) NOT NULL,
  `address` varchar(255) NOT NULL,
  `zipcode` varchar(6) NOT NULL,
  `mobile` varchar(30) NOT NULL,
  `name` varchar(30) NOT NULL COMMENT '收件人',
  PRIMARY KEY (`tid`),
  KEY `uniacid` (`uniacid`),
  KEY `uid` (`uid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='真实物品兑换发货表';
CREATE TABLE IF NOT EXISTS `ims_activity_stores` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(10) unsigned NOT NULL,
  `business_name` varchar(50) NOT NULL,
  `branch_name` varchar(50) NOT NULL,
  `category` varchar(255) NOT NULL,
  `province` varchar(15) NOT NULL,
  `city` varchar(15) NOT NULL,
  `district` varchar(15) NOT NULL,
  `address` varchar(50) NOT NULL,
  `longitude` varchar(15) NOT NULL,
  `latitude` varchar(15) NOT NULL,
  `telephone` varchar(20) NOT NULL,
  `photo_list` varchar(10000) NOT NULL,
  `avg_price` int(10) unsigned NOT NULL,
  `recommend` varchar(255) NOT NULL,
  `special` varchar(255) NOT NULL,
  `introduction` varchar(255) NOT NULL,
  `open_time` varchar(50) NOT NULL,
  `location_id` int(10) unsigned NOT NULL,
  `status` tinyint(3) unsigned NOT NULL COMMENT '1 审核通过 2 审核中 3审核未通过',
  `source` tinyint(3) unsigned NOT NULL DEFAULT '1' COMMENT '1为系统门店，2为微信门店',
  `message` varchar(500) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `uniacid` (`uniacid`),
  KEY `location_id` (`location_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;
CREATE TABLE IF NOT EXISTS `ims_coupon` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(10) unsigned NOT NULL DEFAULT '0',
  `acid` int(10) unsigned NOT NULL DEFAULT '0',
  `card_id` varchar(50) NOT NULL,
  `type` varchar(15) NOT NULL COMMENT '卡券类型',
  `logo_url` varchar(150) NOT NULL,
  `code_type` tinyint(3) unsigned NOT NULL DEFAULT '1' COMMENT 'code类型（二维码/条形码/code码）',
  `brand_name` varchar(15) NOT NULL COMMENT '商家名称',
  `title` varchar(15) NOT NULL,
  `sub_title` varchar(20) NOT NULL,
  `color` varchar(15) NOT NULL,
  `notice` varchar(15) NOT NULL COMMENT '使用说明',
  `description` varchar(1000) NOT NULL,
  `date_info` varchar(200) NOT NULL COMMENT '使用期限',
  `quantity` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '总库存',
  `use_custom_code` tinyint(3) NOT NULL DEFAULT '0',
  `bind_openid` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `can_share` tinyint(3) unsigned NOT NULL DEFAULT '1' COMMENT '是否可分享',
  `can_give_friend` tinyint(3) unsigned NOT NULL DEFAULT '1' COMMENT '是否可转赠给朋友',
  `get_limit` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '每人领取限制',
  `service_phone` varchar(20) NOT NULL,
  `extra` varchar(1000) NOT NULL,
  `status` tinyint(3) unsigned NOT NULL DEFAULT '1' COMMENT '1:审核中,2:未通过,3:已通过,4:卡券被商户删除,5:未知',
  `is_display` tinyint(3) unsigned NOT NULL DEFAULT '1' COMMENT '是否上架',
  `is_selfconsume` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '是否开启自助核销',
  `promotion_url_name` varchar(10) NOT NULL,
  `promotion_url` varchar(100) NOT NULL,
  `promotion_url_sub_title` varchar(10) NOT NULL,
  `source` tinyint(3) unsigned NOT NULL DEFAULT '2',
  `dosage` int(10) unsigned DEFAULT '0' COMMENT '已领取数量',
  PRIMARY KEY (`id`),
  KEY `uniacid` (`uniacid`,`acid`),
  KEY `card_id` (`card_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_coupon_activity` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(10) NOT NULL,
  `msg_id` int(10) NOT NULL DEFAULT '0',
  `status` int(10) NOT NULL DEFAULT '1',
  `title` varchar(255) NOT NULL DEFAULT '',
  `type` int(3) NOT NULL DEFAULT '0' COMMENT '1 发送系统卡券 2发送微信卡券',
  `thumb` varchar(255) NOT NULL DEFAULT '',
  `coupons` varchar(255) NOT NULL DEFAULT '',
  `description` varchar(255) NOT NULL DEFAULT '‘’',
  `members` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_coupon_groups` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(10) NOT NULL,
  `couponid` varchar(255) NOT NULL DEFAULT '',
  `groupid` int(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_coupon_location` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(10) unsigned NOT NULL,
  `acid` int(10) unsigned NOT NULL,
  `sid` int(10) unsigned NOT NULL,
  `location_id` int(10) unsigned NOT NULL,
  `business_name` varchar(50) NOT NULL,
  `branch_name` varchar(50) NOT NULL,
  `category` varchar(255) NOT NULL,
  `province` varchar(15) NOT NULL,
  `city` varchar(15) NOT NULL,
  `district` varchar(15) NOT NULL,
  `address` varchar(50) NOT NULL,
  `longitude` varchar(15) NOT NULL,
  `latitude` varchar(15) NOT NULL,
  `telephone` varchar(20) NOT NULL,
  `photo_list` varchar(10000) NOT NULL,
  `avg_price` int(10) unsigned NOT NULL,
  `open_time` varchar(50) NOT NULL,
  `recommend` varchar(255) NOT NULL,
  `special` varchar(255) NOT NULL,
  `introduction` varchar(255) NOT NULL,
  `offset_type` tinyint(3) unsigned NOT NULL,
  `status` tinyint(3) unsigned NOT NULL,
  `message` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `uniacid` (`uniacid`,`acid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_coupon_modules` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(10) unsigned NOT NULL,
  `acid` int(10) unsigned NOT NULL,
  `couponid` int(10) unsigned NOT NULL DEFAULT '0',
  `module` varchar(30) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `cid` (`couponid`),
  KEY `uniacid` (`uniacid`,`acid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_coupon_record` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(10) unsigned NOT NULL,
  `acid` int(10) unsigned NOT NULL,
  `card_id` varchar(50) NOT NULL,
  `openid` varchar(50) NOT NULL,
  `friend_openid` varchar(50) NOT NULL,
  `givebyfriend` tinyint(3) unsigned NOT NULL,
  `code` varchar(50) NOT NULL,
  `hash` varchar(32) NOT NULL,
  `addtime` int(10) unsigned NOT NULL,
  `usetime` int(10) unsigned NOT NULL,
  `status` tinyint(3) NOT NULL,
  `clerk_name` varchar(15) NOT NULL,
  `clerk_id` int(10) unsigned NOT NULL,
  `store_id` int(10) unsigned NOT NULL,
  `clerk_type` tinyint(3) unsigned NOT NULL,
  `couponid` int(10) unsigned NOT NULL,
  `uid` int(10) unsigned NOT NULL,
  `grantmodule` varchar(255) NOT NULL,
  `remark` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `uniacid` (`uniacid`,`acid`),
  KEY `card_id` (`card_id`),
  KEY `hash` (`hash`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_coupon_store` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(10) NOT NULL,
  `couponid` varchar(255) NOT NULL DEFAULT '',
  `storeid` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `couponid` (`couponid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_mc_card` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(10) unsigned NOT NULL,
  `title` varchar(100) NOT NULL DEFAULT '' COMMENT '会员卡名称',
  `color` varchar(255) NOT NULL DEFAULT '' COMMENT '会员卡字颜色',
  `background` varchar(255) NOT NULL DEFAULT '' COMMENT '背景设置',
  `logo` varchar(255) NOT NULL DEFAULT '' COMMENT 'logo图片',
  `format_type` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '是否用手机号作为会员卡号',
  `format` varchar(50) NOT NULL DEFAULT '' COMMENT '会员卡卡号规则',
  `description` varchar(512) NOT NULL DEFAULT '' COMMENT '会员卡说明',
  `fields` varchar(1000) NOT NULL DEFAULT '' COMMENT '会员卡资料',
  `snpos` int(11) NOT NULL DEFAULT '0',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '是否启用1:启用0:关闭',
  `business` text NOT NULL,
  `discount_type` tinyint(3) unsigned NOT NULL DEFAULT '1' COMMENT '折扣类型.1:满减,2:折扣',
  `discount` varchar(3000) NOT NULL DEFAULT '' COMMENT '各个会员组的优惠详情',
  `grant` varchar(3000) NOT NULL COMMENT '领卡赠送:积分,余额,优惠券',
  `grant_rate` varchar(20) NOT NULL DEFAULT '0' COMMENT '消费返积分比率',
  `offset_rate` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '积分抵现比例',
  `offset_max` int(10) NOT NULL DEFAULT '0' COMMENT '每单最多可抵现金数量',
  `nums_status` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '计次是否开启，0为关闭，1为开启',
  `nums_text` varchar(15) NOT NULL COMMENT '计次名称',
  `nums` varchar(1000) NOT NULL DEFAULT '' COMMENT '计次规则',
  `times_status` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '计时是否开启，0为关闭，1为开启',
  `times_text` varchar(15) NOT NULL COMMENT '计时名称',
  `times` varchar(1000) NOT NULL DEFAULT '' COMMENT '计时规则',
  `params` longtext NOT NULL,
  `html` longtext NOT NULL,
  `recommend_status` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `sign_status` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '签到功能是否开启，0为关闭，1为开启',
  `brand_name` varchar(128) NOT NULL DEFAULT '' COMMENT '商户名字,',
  `notice` varchar(48) NOT NULL DEFAULT '' COMMENT '卡券使用提醒',
  `quantity` int(10) NOT NULL DEFAULT '0' COMMENT '会员卡库存',
  `max_increase_bonus` int(10) NOT NULL DEFAULT '0' COMMENT '用户单次可获取的积分上限',
  `least_money_to_use_bonus` int(10) NOT NULL DEFAULT '0' COMMENT '抵扣条件',
  `source` int(1) NOT NULL DEFAULT '1' COMMENT '1.系统会员卡，2微信会员卡',
  `card_id` varchar(250) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `uniacid` (`uniacid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_mc_card_credit_set` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(10) unsigned NOT NULL DEFAULT '0',
  `sign` varchar(1000) NOT NULL,
  `share` varchar(500) NOT NULL,
  `content` text NOT NULL,
  PRIMARY KEY (`id`),
  KEY `uniacid` (`uniacid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_mc_card_members` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(10) unsigned NOT NULL,
  `uid` int(10) DEFAULT NULL,
  `openid` varchar(50) NOT NULL,
  `cid` int(10) NOT NULL DEFAULT '0',
  `cardsn` varchar(20) NOT NULL DEFAULT '',
  `status` tinyint(1) NOT NULL,
  `createtime` int(10) unsigned NOT NULL,
  `nums` int(10) unsigned NOT NULL DEFAULT '0',
  `endtime` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_mc_card_notices` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(10) unsigned NOT NULL DEFAULT '0',
  `uid` int(10) unsigned NOT NULL DEFAULT '0',
  `type` tinyint(3) unsigned NOT NULL DEFAULT '1' COMMENT '1:公共消息，2:个人消息',
  `title` varchar(30) NOT NULL,
  `thumb` varchar(100) NOT NULL,
  `groupid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '通知会员组。默认为所有会员',
  `content` text NOT NULL,
  `addtime` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `uniacid` (`uniacid`),
  KEY `uid` (`uid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_mc_card_notices_unread` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(10) unsigned NOT NULL DEFAULT '0',
  `notice_id` int(10) unsigned NOT NULL DEFAULT '0',
  `uid` int(10) unsigned NOT NULL DEFAULT '0',
  `is_new` tinyint(3) unsigned NOT NULL DEFAULT '1',
  `type` tinyint(3) unsigned NOT NULL DEFAULT '1' COMMENT '1:公共通知，2：个人通知',
  PRIMARY KEY (`id`),
  KEY `uniacid` (`uniacid`),
  KEY `uid` (`uid`),
  KEY `notice_id` (`notice_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_mc_card_record` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(10) unsigned NOT NULL DEFAULT '0',
  `uid` int(10) unsigned NOT NULL DEFAULT '0',
  `type` varchar(15) NOT NULL,
  `model` tinyint(3) unsigned NOT NULL DEFAULT '1' COMMENT '1：充值，2：消费',
  `fee` decimal(10,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '充值金额',
  `tag` varchar(10) NOT NULL COMMENT '次数|时长|充值金额',
  `note` varchar(255) NOT NULL,
  `remark` varchar(200) NOT NULL COMMENT '备注，只有管理员可以看',
  `addtime` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `uniacid` (`uniacid`),
  KEY `uid` (`uid`),
  KEY `addtime` (`addtime`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_mc_card_sign_record` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(10) unsigned NOT NULL DEFAULT '0',
  `uid` int(10) unsigned NOT NULL DEFAULT '0',
  `credit` int(10) unsigned NOT NULL DEFAULT '0',
  `is_grant` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `addtime` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `uniacid` (`uniacid`),
  KEY `uid` (`uid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
";
pdo_run($sql);
if(!pdo_fieldexists('activity_clerk_menu',  'id')) {
	pdo_query("ALTER TABLE ".tablename('activity_clerk_menu')." ADD `id` int(11) NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('activity_clerk_menu',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('activity_clerk_menu')." ADD `uniacid` int(11) NOT NULL;");
}
if(!pdo_fieldexists('activity_clerk_menu',  'displayorder')) {
	pdo_query("ALTER TABLE ".tablename('activity_clerk_menu')." ADD `displayorder` int(4) NOT NULL;");
}
if(!pdo_fieldexists('activity_clerk_menu',  'pid')) {
	pdo_query("ALTER TABLE ".tablename('activity_clerk_menu')." ADD `pid` int(6) NOT NULL;");
}
if(!pdo_fieldexists('activity_clerk_menu',  'group_name')) {
	pdo_query("ALTER TABLE ".tablename('activity_clerk_menu')." ADD `group_name` varchar(20) NOT NULL;");
}
if(!pdo_fieldexists('activity_clerk_menu',  'title')) {
	pdo_query("ALTER TABLE ".tablename('activity_clerk_menu')." ADD `title` varchar(20) NOT NULL;");
}
if(!pdo_fieldexists('activity_clerk_menu',  'icon')) {
	pdo_query("ALTER TABLE ".tablename('activity_clerk_menu')." ADD `icon` varchar(50) NOT NULL;");
}
if(!pdo_fieldexists('activity_clerk_menu',  'url')) {
	pdo_query("ALTER TABLE ".tablename('activity_clerk_menu')." ADD `url` varchar(255) NOT NULL;");
}
if(!pdo_fieldexists('activity_clerk_menu',  'type')) {
	pdo_query("ALTER TABLE ".tablename('activity_clerk_menu')." ADD `type` varchar(20) NOT NULL;");
}
if(!pdo_fieldexists('activity_clerk_menu',  'permission')) {
	pdo_query("ALTER TABLE ".tablename('activity_clerk_menu')." ADD `permission` varchar(50) NOT NULL;");
}
if(!pdo_fieldexists('activity_clerk_menu',  'system')) {
	pdo_query("ALTER TABLE ".tablename('activity_clerk_menu')." ADD `system` int(2) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('activity_clerks',  'id')) {
	pdo_query("ALTER TABLE ".tablename('activity_clerks')." ADD `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('activity_clerks',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('activity_clerks')." ADD `uniacid` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('activity_clerks',  'uid')) {
	pdo_query("ALTER TABLE ".tablename('activity_clerks')." ADD `uid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '关联users表uid';");
}
if(!pdo_fieldexists('activity_clerks',  'storeid')) {
	pdo_query("ALTER TABLE ".tablename('activity_clerks')." ADD `storeid` int(10) unsigned NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('activity_clerks',  'name')) {
	pdo_query("ALTER TABLE ".tablename('activity_clerks')." ADD `name` varchar(20) NOT NULL;");
}
if(!pdo_fieldexists('activity_clerks',  'password')) {
	pdo_query("ALTER TABLE ".tablename('activity_clerks')." ADD `password` varchar(20) NOT NULL;");
}
if(!pdo_fieldexists('activity_clerks',  'mobile')) {
	pdo_query("ALTER TABLE ".tablename('activity_clerks')." ADD `mobile` varchar(20) NOT NULL;");
}
if(!pdo_fieldexists('activity_clerks',  'openid')) {
	pdo_query("ALTER TABLE ".tablename('activity_clerks')." ADD `openid` varchar(50) NOT NULL;");
}
if(!pdo_fieldexists('activity_clerks',  'nickname')) {
	pdo_query("ALTER TABLE ".tablename('activity_clerks')." ADD `nickname` varchar(30) NOT NULL;");
}
if(!pdo_indexexists('activity_clerks',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('activity_clerks')." ADD KEY `uniacid` (`uniacid`);");
}
if(!pdo_indexexists('activity_clerks',  'password')) {
	pdo_query("ALTER TABLE ".tablename('activity_clerks')." ADD KEY `password` (`password`);");
}
if(!pdo_indexexists('activity_clerks',  'openid')) {
	pdo_query("ALTER TABLE ".tablename('activity_clerks')." ADD KEY `openid` (`openid`);");
}
if(!pdo_fieldexists('activity_exchange',  'id')) {
	pdo_query("ALTER TABLE ".tablename('activity_exchange')." ADD `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('activity_exchange',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('activity_exchange')." ADD `uniacid` int(11) NOT NULL;");
}
if(!pdo_fieldexists('activity_exchange',  'title')) {
	pdo_query("ALTER TABLE ".tablename('activity_exchange')." ADD `title` varchar(100) NOT NULL COMMENT '物品名称';");
}
if(!pdo_fieldexists('activity_exchange',  'description')) {
	pdo_query("ALTER TABLE ".tablename('activity_exchange')." ADD `description` text NOT NULL COMMENT '描述信息';");
}
if(!pdo_fieldexists('activity_exchange',  'thumb')) {
	pdo_query("ALTER TABLE ".tablename('activity_exchange')." ADD `thumb` varchar(500) NOT NULL COMMENT '缩略图';");
}
if(!pdo_fieldexists('activity_exchange',  'type')) {
	pdo_query("ALTER TABLE ".tablename('activity_exchange')." ADD `type` tinyint(1) unsigned NOT NULL COMMENT '物品类型，1系统卡券，2微信呢卡券，3实物，4虚拟物品(未启用)，5营销模块操作次数';");
}
if(!pdo_fieldexists('activity_exchange',  'extra')) {
	pdo_query("ALTER TABLE ".tablename('activity_exchange')." ADD `extra` varchar(3000) NOT NULL DEFAULT '' COMMENT '兑换产品属性 卡券自增id';");
}
if(!pdo_fieldexists('activity_exchange',  'credit')) {
	pdo_query("ALTER TABLE ".tablename('activity_exchange')." ADD `credit` int(10) unsigned NOT NULL COMMENT '兑换积分数量';");
}
if(!pdo_fieldexists('activity_exchange',  'credittype')) {
	pdo_query("ALTER TABLE ".tablename('activity_exchange')." ADD `credittype` varchar(10) NOT NULL COMMENT '兑换积分类型';");
}
if(!pdo_fieldexists('activity_exchange',  'pretotal')) {
	pdo_query("ALTER TABLE ".tablename('activity_exchange')." ADD `pretotal` int(11) NOT NULL COMMENT '每个人最大兑换次数';");
}
if(!pdo_fieldexists('activity_exchange',  'num')) {
	pdo_query("ALTER TABLE ".tablename('activity_exchange')." ADD `num` int(11) NOT NULL COMMENT '已兑换礼品数量';");
}
if(!pdo_fieldexists('activity_exchange',  'total')) {
	pdo_query("ALTER TABLE ".tablename('activity_exchange')." ADD `total` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '总量';");
}
if(!pdo_fieldexists('activity_exchange',  'status')) {
	pdo_query("ALTER TABLE ".tablename('activity_exchange')." ADD `status` tinyint(3) unsigned NOT NULL DEFAULT '1' COMMENT '状态';");
}
if(!pdo_fieldexists('activity_exchange',  'starttime')) {
	pdo_query("ALTER TABLE ".tablename('activity_exchange')." ADD `starttime` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('activity_exchange',  'endtime')) {
	pdo_query("ALTER TABLE ".tablename('activity_exchange')." ADD `endtime` int(10) NOT NULL;");
}
if(!pdo_indexexists('activity_exchange',  'extra')) {
	pdo_query("ALTER TABLE ".tablename('activity_exchange')." ADD KEY `extra` (`extra`(333));");
}
if(!pdo_fieldexists('activity_exchange_trades',  'tid')) {
	pdo_query("ALTER TABLE ".tablename('activity_exchange_trades')." ADD `tid` int(10) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('activity_exchange_trades',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('activity_exchange_trades')." ADD `uniacid` int(10) unsigned NOT NULL COMMENT '统一公号';");
}
if(!pdo_fieldexists('activity_exchange_trades',  'uid')) {
	pdo_query("ALTER TABLE ".tablename('activity_exchange_trades')." ADD `uid` int(10) unsigned NOT NULL COMMENT '用户(粉丝)id';");
}
if(!pdo_fieldexists('activity_exchange_trades',  'exid')) {
	pdo_query("ALTER TABLE ".tablename('activity_exchange_trades')." ADD `exid` int(10) unsigned NOT NULL COMMENT '兑换产品 exchangeid';");
}
if(!pdo_fieldexists('activity_exchange_trades',  'type')) {
	pdo_query("ALTER TABLE ".tablename('activity_exchange_trades')." ADD `type` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('activity_exchange_trades',  'createtime')) {
	pdo_query("ALTER TABLE ".tablename('activity_exchange_trades')." ADD `createtime` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '交换记录创建时间';");
}
if(!pdo_indexexists('activity_exchange_trades',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('activity_exchange_trades')." ADD KEY `uniacid` (`uniacid`,`uid`,`exid`);");
}
if(!pdo_fieldexists('activity_exchange_trades_shipping',  'tid')) {
	pdo_query("ALTER TABLE ".tablename('activity_exchange_trades_shipping')." ADD `tid` int(10) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('activity_exchange_trades_shipping',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('activity_exchange_trades_shipping')." ADD `uniacid` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('activity_exchange_trades_shipping',  'exid')) {
	pdo_query("ALTER TABLE ".tablename('activity_exchange_trades_shipping')." ADD `exid` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('activity_exchange_trades_shipping',  'uid')) {
	pdo_query("ALTER TABLE ".tablename('activity_exchange_trades_shipping')." ADD `uid` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('activity_exchange_trades_shipping',  'status')) {
	pdo_query("ALTER TABLE ".tablename('activity_exchange_trades_shipping')." ADD `status` tinyint(4) NOT NULL DEFAULT '0' COMMENT '订单状态，0为正常，-1为关闭，1为已发货，2为已完成';");
}
if(!pdo_fieldexists('activity_exchange_trades_shipping',  'createtime')) {
	pdo_query("ALTER TABLE ".tablename('activity_exchange_trades_shipping')." ADD `createtime` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('activity_exchange_trades_shipping',  'province')) {
	pdo_query("ALTER TABLE ".tablename('activity_exchange_trades_shipping')." ADD `province` varchar(30) NOT NULL;");
}
if(!pdo_fieldexists('activity_exchange_trades_shipping',  'city')) {
	pdo_query("ALTER TABLE ".tablename('activity_exchange_trades_shipping')." ADD `city` varchar(30) NOT NULL;");
}
if(!pdo_fieldexists('activity_exchange_trades_shipping',  'district')) {
	pdo_query("ALTER TABLE ".tablename('activity_exchange_trades_shipping')." ADD `district` varchar(30) NOT NULL;");
}
if(!pdo_fieldexists('activity_exchange_trades_shipping',  'address')) {
	pdo_query("ALTER TABLE ".tablename('activity_exchange_trades_shipping')." ADD `address` varchar(255) NOT NULL;");
}
if(!pdo_fieldexists('activity_exchange_trades_shipping',  'zipcode')) {
	pdo_query("ALTER TABLE ".tablename('activity_exchange_trades_shipping')." ADD `zipcode` varchar(6) NOT NULL;");
}
if(!pdo_fieldexists('activity_exchange_trades_shipping',  'mobile')) {
	pdo_query("ALTER TABLE ".tablename('activity_exchange_trades_shipping')." ADD `mobile` varchar(30) NOT NULL;");
}
if(!pdo_fieldexists('activity_exchange_trades_shipping',  'name')) {
	pdo_query("ALTER TABLE ".tablename('activity_exchange_trades_shipping')." ADD `name` varchar(30) NOT NULL COMMENT '收件人';");
}
if(!pdo_indexexists('activity_exchange_trades_shipping',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('activity_exchange_trades_shipping')." ADD KEY `uniacid` (`uniacid`);");
}
if(!pdo_indexexists('activity_exchange_trades_shipping',  'uid')) {
	pdo_query("ALTER TABLE ".tablename('activity_exchange_trades_shipping')." ADD KEY `uid` (`uid`);");
}
if(!pdo_fieldexists('activity_stores',  'id')) {
	pdo_query("ALTER TABLE ".tablename('activity_stores')." ADD `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('activity_stores',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('activity_stores')." ADD `uniacid` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('activity_stores',  'business_name')) {
	pdo_query("ALTER TABLE ".tablename('activity_stores')." ADD `business_name` varchar(50) NOT NULL;");
}
if(!pdo_fieldexists('activity_stores',  'branch_name')) {
	pdo_query("ALTER TABLE ".tablename('activity_stores')." ADD `branch_name` varchar(50) NOT NULL;");
}
if(!pdo_fieldexists('activity_stores',  'category')) {
	pdo_query("ALTER TABLE ".tablename('activity_stores')." ADD `category` varchar(255) NOT NULL;");
}
if(!pdo_fieldexists('activity_stores',  'province')) {
	pdo_query("ALTER TABLE ".tablename('activity_stores')." ADD `province` varchar(15) NOT NULL;");
}
if(!pdo_fieldexists('activity_stores',  'city')) {
	pdo_query("ALTER TABLE ".tablename('activity_stores')." ADD `city` varchar(15) NOT NULL;");
}
if(!pdo_fieldexists('activity_stores',  'district')) {
	pdo_query("ALTER TABLE ".tablename('activity_stores')." ADD `district` varchar(15) NOT NULL;");
}
if(!pdo_fieldexists('activity_stores',  'address')) {
	pdo_query("ALTER TABLE ".tablename('activity_stores')." ADD `address` varchar(50) NOT NULL;");
}
if(!pdo_fieldexists('activity_stores',  'longitude')) {
	pdo_query("ALTER TABLE ".tablename('activity_stores')." ADD `longitude` varchar(15) NOT NULL;");
}
if(!pdo_fieldexists('activity_stores',  'latitude')) {
	pdo_query("ALTER TABLE ".tablename('activity_stores')." ADD `latitude` varchar(15) NOT NULL;");
}
if(!pdo_fieldexists('activity_stores',  'telephone')) {
	pdo_query("ALTER TABLE ".tablename('activity_stores')." ADD `telephone` varchar(20) NOT NULL;");
}
if(!pdo_fieldexists('activity_stores',  'photo_list')) {
	pdo_query("ALTER TABLE ".tablename('activity_stores')." ADD `photo_list` varchar(10000) NOT NULL;");
}
if(!pdo_fieldexists('activity_stores',  'avg_price')) {
	pdo_query("ALTER TABLE ".tablename('activity_stores')." ADD `avg_price` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('activity_stores',  'recommend')) {
	pdo_query("ALTER TABLE ".tablename('activity_stores')." ADD `recommend` varchar(255) NOT NULL;");
}
if(!pdo_fieldexists('activity_stores',  'special')) {
	pdo_query("ALTER TABLE ".tablename('activity_stores')." ADD `special` varchar(255) NOT NULL;");
}
if(!pdo_fieldexists('activity_stores',  'introduction')) {
	pdo_query("ALTER TABLE ".tablename('activity_stores')." ADD `introduction` varchar(255) NOT NULL;");
}
if(!pdo_fieldexists('activity_stores',  'open_time')) {
	pdo_query("ALTER TABLE ".tablename('activity_stores')." ADD `open_time` varchar(50) NOT NULL;");
}
if(!pdo_fieldexists('activity_stores',  'location_id')) {
	pdo_query("ALTER TABLE ".tablename('activity_stores')." ADD `location_id` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('activity_stores',  'status')) {
	pdo_query("ALTER TABLE ".tablename('activity_stores')." ADD `status` tinyint(3) unsigned NOT NULL COMMENT '1 审核通过 2 审核中 3审核未通过';");
}
if(!pdo_fieldexists('activity_stores',  'source')) {
	pdo_query("ALTER TABLE ".tablename('activity_stores')." ADD `source` tinyint(3) unsigned NOT NULL DEFAULT '1' COMMENT '1为系统门店，2为微信门店';");
}
if(!pdo_fieldexists('activity_stores',  'message')) {
	pdo_query("ALTER TABLE ".tablename('activity_stores')." ADD `message` varchar(500) NOT NULL;");
}
if(!pdo_indexexists('activity_stores',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('activity_stores')." ADD KEY `uniacid` (`uniacid`);");
}
if(!pdo_indexexists('activity_stores',  'location_id')) {
	pdo_query("ALTER TABLE ".tablename('activity_stores')." ADD KEY `location_id` (`location_id`);");
}
if(!pdo_fieldexists('coupon',  'id')) {
	pdo_query("ALTER TABLE ".tablename('coupon')." ADD `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('coupon',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('coupon')." ADD `uniacid` int(10) unsigned NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('coupon',  'acid')) {
	pdo_query("ALTER TABLE ".tablename('coupon')." ADD `acid` int(10) unsigned NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('coupon',  'card_id')) {
	pdo_query("ALTER TABLE ".tablename('coupon')." ADD `card_id` varchar(50) NOT NULL;");
}
if(!pdo_fieldexists('coupon',  'type')) {
	pdo_query("ALTER TABLE ".tablename('coupon')." ADD `type` varchar(15) NOT NULL COMMENT '卡券类型';");
}
if(!pdo_fieldexists('coupon',  'logo_url')) {
	pdo_query("ALTER TABLE ".tablename('coupon')." ADD `logo_url` varchar(150) NOT NULL;");
}
if(!pdo_fieldexists('coupon',  'code_type')) {
	pdo_query("ALTER TABLE ".tablename('coupon')." ADD `code_type` tinyint(3) unsigned NOT NULL DEFAULT '1' COMMENT 'code类型（二维码/条形码/code码）';");
}
if(!pdo_fieldexists('coupon',  'brand_name')) {
	pdo_query("ALTER TABLE ".tablename('coupon')." ADD `brand_name` varchar(15) NOT NULL COMMENT '商家名称';");
}
if(!pdo_fieldexists('coupon',  'title')) {
	pdo_query("ALTER TABLE ".tablename('coupon')." ADD `title` varchar(15) NOT NULL;");
}
if(!pdo_fieldexists('coupon',  'sub_title')) {
	pdo_query("ALTER TABLE ".tablename('coupon')." ADD `sub_title` varchar(20) NOT NULL;");
}
if(!pdo_fieldexists('coupon',  'color')) {
	pdo_query("ALTER TABLE ".tablename('coupon')." ADD `color` varchar(15) NOT NULL;");
}
if(!pdo_fieldexists('coupon',  'notice')) {
	pdo_query("ALTER TABLE ".tablename('coupon')." ADD `notice` varchar(15) NOT NULL COMMENT '使用说明';");
}
if(!pdo_fieldexists('coupon',  'description')) {
	pdo_query("ALTER TABLE ".tablename('coupon')." ADD `description` varchar(1000) NOT NULL;");
}
if(!pdo_fieldexists('coupon',  'date_info')) {
	pdo_query("ALTER TABLE ".tablename('coupon')." ADD `date_info` varchar(200) NOT NULL COMMENT '使用期限';");
}
if(!pdo_fieldexists('coupon',  'quantity')) {
	pdo_query("ALTER TABLE ".tablename('coupon')." ADD `quantity` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '总库存';");
}
if(!pdo_fieldexists('coupon',  'use_custom_code')) {
	pdo_query("ALTER TABLE ".tablename('coupon')." ADD `use_custom_code` tinyint(3) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('coupon',  'bind_openid')) {
	pdo_query("ALTER TABLE ".tablename('coupon')." ADD `bind_openid` tinyint(3) unsigned NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('coupon',  'can_share')) {
	pdo_query("ALTER TABLE ".tablename('coupon')." ADD `can_share` tinyint(3) unsigned NOT NULL DEFAULT '1' COMMENT '是否可分享';");
}
if(!pdo_fieldexists('coupon',  'can_give_friend')) {
	pdo_query("ALTER TABLE ".tablename('coupon')." ADD `can_give_friend` tinyint(3) unsigned NOT NULL DEFAULT '1' COMMENT '是否可转赠给朋友';");
}
if(!pdo_fieldexists('coupon',  'get_limit')) {
	pdo_query("ALTER TABLE ".tablename('coupon')." ADD `get_limit` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '每人领取限制';");
}
if(!pdo_fieldexists('coupon',  'service_phone')) {
	pdo_query("ALTER TABLE ".tablename('coupon')." ADD `service_phone` varchar(20) NOT NULL;");
}
if(!pdo_fieldexists('coupon',  'extra')) {
	pdo_query("ALTER TABLE ".tablename('coupon')." ADD `extra` varchar(1000) NOT NULL;");
}
if(!pdo_fieldexists('coupon',  'status')) {
	pdo_query("ALTER TABLE ".tablename('coupon')." ADD `status` tinyint(3) unsigned NOT NULL DEFAULT '1' COMMENT '1:审核中,2:未通过,3:已通过,4:卡券被商户删除,5:未知';");
}
if(!pdo_fieldexists('coupon',  'is_display')) {
	pdo_query("ALTER TABLE ".tablename('coupon')." ADD `is_display` tinyint(3) unsigned NOT NULL DEFAULT '1' COMMENT '是否上架';");
}
if(!pdo_fieldexists('coupon',  'is_selfconsume')) {
	pdo_query("ALTER TABLE ".tablename('coupon')." ADD `is_selfconsume` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '是否开启自助核销';");
}
if(!pdo_fieldexists('coupon',  'promotion_url_name')) {
	pdo_query("ALTER TABLE ".tablename('coupon')." ADD `promotion_url_name` varchar(10) NOT NULL;");
}
if(!pdo_fieldexists('coupon',  'promotion_url')) {
	pdo_query("ALTER TABLE ".tablename('coupon')." ADD `promotion_url` varchar(100) NOT NULL;");
}
if(!pdo_fieldexists('coupon',  'promotion_url_sub_title')) {
	pdo_query("ALTER TABLE ".tablename('coupon')." ADD `promotion_url_sub_title` varchar(10) NOT NULL;");
}
if(!pdo_fieldexists('coupon',  'source')) {
	pdo_query("ALTER TABLE ".tablename('coupon')." ADD `source` tinyint(3) unsigned NOT NULL DEFAULT '2';");
}
if(!pdo_fieldexists('coupon',  'dosage')) {
	pdo_query("ALTER TABLE ".tablename('coupon')." ADD `dosage` int(10) unsigned DEFAULT '0' COMMENT '已领取数量';");
}
if(!pdo_indexexists('coupon',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('coupon')." ADD KEY `uniacid` (`uniacid`,`acid`);");
}
if(!pdo_indexexists('coupon',  'card_id')) {
	pdo_query("ALTER TABLE ".tablename('coupon')." ADD KEY `card_id` (`card_id`);");
}
if(!pdo_fieldexists('coupon_activity',  'id')) {
	pdo_query("ALTER TABLE ".tablename('coupon_activity')." ADD `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('coupon_activity',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('coupon_activity')." ADD `uniacid` int(10) NOT NULL;");
}
if(!pdo_fieldexists('coupon_activity',  'msg_id')) {
	pdo_query("ALTER TABLE ".tablename('coupon_activity')." ADD `msg_id` int(10) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('coupon_activity',  'status')) {
	pdo_query("ALTER TABLE ".tablename('coupon_activity')." ADD `status` int(10) NOT NULL DEFAULT '1';");
}
if(!pdo_fieldexists('coupon_activity',  'title')) {
	pdo_query("ALTER TABLE ".tablename('coupon_activity')." ADD `title` varchar(255) NOT NULL DEFAULT '';");
}
if(!pdo_fieldexists('coupon_activity',  'type')) {
	pdo_query("ALTER TABLE ".tablename('coupon_activity')." ADD `type` int(3) NOT NULL DEFAULT '0' COMMENT '1 发送系统卡券 2发送微信卡券';");
}
if(!pdo_fieldexists('coupon_activity',  'thumb')) {
	pdo_query("ALTER TABLE ".tablename('coupon_activity')." ADD `thumb` varchar(255) NOT NULL DEFAULT '';");
}
if(!pdo_fieldexists('coupon_activity',  'coupons')) {
	pdo_query("ALTER TABLE ".tablename('coupon_activity')." ADD `coupons` varchar(255) NOT NULL DEFAULT '';");
}
if(!pdo_fieldexists('coupon_activity',  'description')) {
	pdo_query("ALTER TABLE ".tablename('coupon_activity')." ADD `description` varchar(255) NOT NULL DEFAULT '‘’';");
}
if(!pdo_fieldexists('coupon_activity',  'members')) {
	pdo_query("ALTER TABLE ".tablename('coupon_activity')." ADD `members` varchar(255) NOT NULL DEFAULT '';");
}
if(!pdo_fieldexists('coupon_groups',  'id')) {
	pdo_query("ALTER TABLE ".tablename('coupon_groups')." ADD `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('coupon_groups',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('coupon_groups')." ADD `uniacid` int(10) NOT NULL;");
}
if(!pdo_fieldexists('coupon_groups',  'couponid')) {
	pdo_query("ALTER TABLE ".tablename('coupon_groups')." ADD `couponid` varchar(255) NOT NULL DEFAULT '';");
}
if(!pdo_fieldexists('coupon_groups',  'groupid')) {
	pdo_query("ALTER TABLE ".tablename('coupon_groups')." ADD `groupid` int(10) NOT NULL;");
}
if(!pdo_fieldexists('coupon_location',  'id')) {
	pdo_query("ALTER TABLE ".tablename('coupon_location')." ADD `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('coupon_location',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('coupon_location')." ADD `uniacid` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('coupon_location',  'acid')) {
	pdo_query("ALTER TABLE ".tablename('coupon_location')." ADD `acid` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('coupon_location',  'sid')) {
	pdo_query("ALTER TABLE ".tablename('coupon_location')." ADD `sid` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('coupon_location',  'location_id')) {
	pdo_query("ALTER TABLE ".tablename('coupon_location')." ADD `location_id` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('coupon_location',  'business_name')) {
	pdo_query("ALTER TABLE ".tablename('coupon_location')." ADD `business_name` varchar(50) NOT NULL;");
}
if(!pdo_fieldexists('coupon_location',  'branch_name')) {
	pdo_query("ALTER TABLE ".tablename('coupon_location')." ADD `branch_name` varchar(50) NOT NULL;");
}
if(!pdo_fieldexists('coupon_location',  'category')) {
	pdo_query("ALTER TABLE ".tablename('coupon_location')." ADD `category` varchar(255) NOT NULL;");
}
if(!pdo_fieldexists('coupon_location',  'province')) {
	pdo_query("ALTER TABLE ".tablename('coupon_location')." ADD `province` varchar(15) NOT NULL;");
}
if(!pdo_fieldexists('coupon_location',  'city')) {
	pdo_query("ALTER TABLE ".tablename('coupon_location')." ADD `city` varchar(15) NOT NULL;");
}
if(!pdo_fieldexists('coupon_location',  'district')) {
	pdo_query("ALTER TABLE ".tablename('coupon_location')." ADD `district` varchar(15) NOT NULL;");
}
if(!pdo_fieldexists('coupon_location',  'address')) {
	pdo_query("ALTER TABLE ".tablename('coupon_location')." ADD `address` varchar(50) NOT NULL;");
}
if(!pdo_fieldexists('coupon_location',  'longitude')) {
	pdo_query("ALTER TABLE ".tablename('coupon_location')." ADD `longitude` varchar(15) NOT NULL;");
}
if(!pdo_fieldexists('coupon_location',  'latitude')) {
	pdo_query("ALTER TABLE ".tablename('coupon_location')." ADD `latitude` varchar(15) NOT NULL;");
}
if(!pdo_fieldexists('coupon_location',  'telephone')) {
	pdo_query("ALTER TABLE ".tablename('coupon_location')." ADD `telephone` varchar(20) NOT NULL;");
}
if(!pdo_fieldexists('coupon_location',  'photo_list')) {
	pdo_query("ALTER TABLE ".tablename('coupon_location')." ADD `photo_list` varchar(10000) NOT NULL;");
}
if(!pdo_fieldexists('coupon_location',  'avg_price')) {
	pdo_query("ALTER TABLE ".tablename('coupon_location')." ADD `avg_price` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('coupon_location',  'open_time')) {
	pdo_query("ALTER TABLE ".tablename('coupon_location')." ADD `open_time` varchar(50) NOT NULL;");
}
if(!pdo_fieldexists('coupon_location',  'recommend')) {
	pdo_query("ALTER TABLE ".tablename('coupon_location')." ADD `recommend` varchar(255) NOT NULL;");
}
if(!pdo_fieldexists('coupon_location',  'special')) {
	pdo_query("ALTER TABLE ".tablename('coupon_location')." ADD `special` varchar(255) NOT NULL;");
}
if(!pdo_fieldexists('coupon_location',  'introduction')) {
	pdo_query("ALTER TABLE ".tablename('coupon_location')." ADD `introduction` varchar(255) NOT NULL;");
}
if(!pdo_fieldexists('coupon_location',  'offset_type')) {
	pdo_query("ALTER TABLE ".tablename('coupon_location')." ADD `offset_type` tinyint(3) unsigned NOT NULL;");
}
if(!pdo_fieldexists('coupon_location',  'status')) {
	pdo_query("ALTER TABLE ".tablename('coupon_location')." ADD `status` tinyint(3) unsigned NOT NULL;");
}
if(!pdo_fieldexists('coupon_location',  'message')) {
	pdo_query("ALTER TABLE ".tablename('coupon_location')." ADD `message` varchar(255) NOT NULL;");
}
if(!pdo_indexexists('coupon_location',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('coupon_location')." ADD KEY `uniacid` (`uniacid`,`acid`);");
}
if(!pdo_fieldexists('coupon_modules',  'id')) {
	pdo_query("ALTER TABLE ".tablename('coupon_modules')." ADD `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('coupon_modules',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('coupon_modules')." ADD `uniacid` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('coupon_modules',  'acid')) {
	pdo_query("ALTER TABLE ".tablename('coupon_modules')." ADD `acid` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('coupon_modules',  'couponid')) {
	pdo_query("ALTER TABLE ".tablename('coupon_modules')." ADD `couponid` int(10) unsigned NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('coupon_modules',  'module')) {
	pdo_query("ALTER TABLE ".tablename('coupon_modules')." ADD `module` varchar(30) NOT NULL;");
}
if(!pdo_indexexists('coupon_modules',  'cid')) {
	pdo_query("ALTER TABLE ".tablename('coupon_modules')." ADD KEY `cid` (`couponid`);");
}
if(!pdo_indexexists('coupon_modules',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('coupon_modules')." ADD KEY `uniacid` (`uniacid`,`acid`);");
}
if(!pdo_fieldexists('coupon_record',  'id')) {
	pdo_query("ALTER TABLE ".tablename('coupon_record')." ADD `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('coupon_record',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('coupon_record')." ADD `uniacid` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('coupon_record',  'acid')) {
	pdo_query("ALTER TABLE ".tablename('coupon_record')." ADD `acid` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('coupon_record',  'card_id')) {
	pdo_query("ALTER TABLE ".tablename('coupon_record')." ADD `card_id` varchar(50) NOT NULL;");
}
if(!pdo_fieldexists('coupon_record',  'openid')) {
	pdo_query("ALTER TABLE ".tablename('coupon_record')." ADD `openid` varchar(50) NOT NULL;");
}
if(!pdo_fieldexists('coupon_record',  'friend_openid')) {
	pdo_query("ALTER TABLE ".tablename('coupon_record')." ADD `friend_openid` varchar(50) NOT NULL;");
}
if(!pdo_fieldexists('coupon_record',  'givebyfriend')) {
	pdo_query("ALTER TABLE ".tablename('coupon_record')." ADD `givebyfriend` tinyint(3) unsigned NOT NULL;");
}
if(!pdo_fieldexists('coupon_record',  'code')) {
	pdo_query("ALTER TABLE ".tablename('coupon_record')." ADD `code` varchar(50) NOT NULL;");
}
if(!pdo_fieldexists('coupon_record',  'hash')) {
	pdo_query("ALTER TABLE ".tablename('coupon_record')." ADD `hash` varchar(32) NOT NULL;");
}
if(!pdo_fieldexists('coupon_record',  'addtime')) {
	pdo_query("ALTER TABLE ".tablename('coupon_record')." ADD `addtime` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('coupon_record',  'usetime')) {
	pdo_query("ALTER TABLE ".tablename('coupon_record')." ADD `usetime` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('coupon_record',  'status')) {
	pdo_query("ALTER TABLE ".tablename('coupon_record')." ADD `status` tinyint(3) NOT NULL;");
}
if(!pdo_fieldexists('coupon_record',  'clerk_name')) {
	pdo_query("ALTER TABLE ".tablename('coupon_record')." ADD `clerk_name` varchar(15) NOT NULL;");
}
if(!pdo_fieldexists('coupon_record',  'clerk_id')) {
	pdo_query("ALTER TABLE ".tablename('coupon_record')." ADD `clerk_id` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('coupon_record',  'store_id')) {
	pdo_query("ALTER TABLE ".tablename('coupon_record')." ADD `store_id` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('coupon_record',  'clerk_type')) {
	pdo_query("ALTER TABLE ".tablename('coupon_record')." ADD `clerk_type` tinyint(3) unsigned NOT NULL;");
}
if(!pdo_fieldexists('coupon_record',  'couponid')) {
	pdo_query("ALTER TABLE ".tablename('coupon_record')." ADD `couponid` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('coupon_record',  'uid')) {
	pdo_query("ALTER TABLE ".tablename('coupon_record')." ADD `uid` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('coupon_record',  'grantmodule')) {
	pdo_query("ALTER TABLE ".tablename('coupon_record')." ADD `grantmodule` varchar(255) NOT NULL;");
}
if(!pdo_fieldexists('coupon_record',  'remark')) {
	pdo_query("ALTER TABLE ".tablename('coupon_record')." ADD `remark` varchar(255) NOT NULL;");
}
if(!pdo_indexexists('coupon_record',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('coupon_record')." ADD KEY `uniacid` (`uniacid`,`acid`);");
}
if(!pdo_indexexists('coupon_record',  'card_id')) {
	pdo_query("ALTER TABLE ".tablename('coupon_record')." ADD KEY `card_id` (`card_id`);");
}
if(!pdo_indexexists('coupon_record',  'hash')) {
	pdo_query("ALTER TABLE ".tablename('coupon_record')." ADD KEY `hash` (`hash`);");
}
if(!pdo_fieldexists('coupon_store',  'id')) {
	pdo_query("ALTER TABLE ".tablename('coupon_store')." ADD `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('coupon_store',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('coupon_store')." ADD `uniacid` int(10) NOT NULL;");
}
if(!pdo_fieldexists('coupon_store',  'couponid')) {
	pdo_query("ALTER TABLE ".tablename('coupon_store')." ADD `couponid` varchar(255) NOT NULL DEFAULT '';");
}
if(!pdo_fieldexists('coupon_store',  'storeid')) {
	pdo_query("ALTER TABLE ".tablename('coupon_store')." ADD `storeid` int(10) unsigned NOT NULL DEFAULT '0';");
}
if(!pdo_indexexists('coupon_store',  'couponid')) {
	pdo_query("ALTER TABLE ".tablename('coupon_store')." ADD KEY `couponid` (`couponid`);");
}
if(!pdo_fieldexists('mc_card',  'id')) {
	pdo_query("ALTER TABLE ".tablename('mc_card')." ADD `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('mc_card',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('mc_card')." ADD `uniacid` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('mc_card',  'title')) {
	pdo_query("ALTER TABLE ".tablename('mc_card')." ADD `title` varchar(100) NOT NULL DEFAULT '' COMMENT '会员卡名称';");
}
if(!pdo_fieldexists('mc_card',  'color')) {
	pdo_query("ALTER TABLE ".tablename('mc_card')." ADD `color` varchar(255) NOT NULL DEFAULT '' COMMENT '会员卡字颜色';");
}
if(!pdo_fieldexists('mc_card',  'background')) {
	pdo_query("ALTER TABLE ".tablename('mc_card')." ADD `background` varchar(255) NOT NULL DEFAULT '' COMMENT '背景设置';");
}
if(!pdo_fieldexists('mc_card',  'logo')) {
	pdo_query("ALTER TABLE ".tablename('mc_card')." ADD `logo` varchar(255) NOT NULL DEFAULT '' COMMENT 'logo图片';");
}
if(!pdo_fieldexists('mc_card',  'format_type')) {
	pdo_query("ALTER TABLE ".tablename('mc_card')." ADD `format_type` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '是否用手机号作为会员卡号';");
}
if(!pdo_fieldexists('mc_card',  'format')) {
	pdo_query("ALTER TABLE ".tablename('mc_card')." ADD `format` varchar(50) NOT NULL DEFAULT '' COMMENT '会员卡卡号规则';");
}
if(!pdo_fieldexists('mc_card',  'description')) {
	pdo_query("ALTER TABLE ".tablename('mc_card')." ADD `description` varchar(512) NOT NULL DEFAULT '' COMMENT '会员卡说明';");
}
if(!pdo_fieldexists('mc_card',  'fields')) {
	pdo_query("ALTER TABLE ".tablename('mc_card')." ADD `fields` varchar(1000) NOT NULL DEFAULT '' COMMENT '会员卡资料';");
}
if(!pdo_fieldexists('mc_card',  'snpos')) {
	pdo_query("ALTER TABLE ".tablename('mc_card')." ADD `snpos` int(11) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('mc_card',  'status')) {
	pdo_query("ALTER TABLE ".tablename('mc_card')." ADD `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '是否启用1:启用0:关闭';");
}
if(!pdo_fieldexists('mc_card',  'business')) {
	pdo_query("ALTER TABLE ".tablename('mc_card')." ADD `business` text NOT NULL;");
}
if(!pdo_fieldexists('mc_card',  'discount_type')) {
	pdo_query("ALTER TABLE ".tablename('mc_card')." ADD `discount_type` tinyint(3) unsigned NOT NULL DEFAULT '1' COMMENT '折扣类型.1:满减,2:折扣';");
}
if(!pdo_fieldexists('mc_card',  'discount')) {
	pdo_query("ALTER TABLE ".tablename('mc_card')." ADD `discount` varchar(3000) NOT NULL DEFAULT '' COMMENT '各个会员组的优惠详情';");
}
if(!pdo_fieldexists('mc_card',  'grant')) {
	pdo_query("ALTER TABLE ".tablename('mc_card')." ADD `grant` varchar(3000) NOT NULL COMMENT '领卡赠送:积分,余额,优惠券';");
}
if(!pdo_fieldexists('mc_card',  'grant_rate')) {
	pdo_query("ALTER TABLE ".tablename('mc_card')." ADD `grant_rate` varchar(20) NOT NULL DEFAULT '0' COMMENT '消费返积分比率';");
}
if(!pdo_fieldexists('mc_card',  'offset_rate')) {
	pdo_query("ALTER TABLE ".tablename('mc_card')." ADD `offset_rate` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '积分抵现比例';");
}
if(!pdo_fieldexists('mc_card',  'offset_max')) {
	pdo_query("ALTER TABLE ".tablename('mc_card')." ADD `offset_max` int(10) NOT NULL DEFAULT '0' COMMENT '每单最多可抵现金数量';");
}
if(!pdo_fieldexists('mc_card',  'nums_status')) {
	pdo_query("ALTER TABLE ".tablename('mc_card')." ADD `nums_status` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '计次是否开启，0为关闭，1为开启';");
}
if(!pdo_fieldexists('mc_card',  'nums_text')) {
	pdo_query("ALTER TABLE ".tablename('mc_card')." ADD `nums_text` varchar(15) NOT NULL COMMENT '计次名称';");
}
if(!pdo_fieldexists('mc_card',  'nums')) {
	pdo_query("ALTER TABLE ".tablename('mc_card')." ADD `nums` varchar(1000) NOT NULL DEFAULT '' COMMENT '计次规则';");
}
if(!pdo_fieldexists('mc_card',  'times_status')) {
	pdo_query("ALTER TABLE ".tablename('mc_card')." ADD `times_status` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '计时是否开启，0为关闭，1为开启';");
}
if(!pdo_fieldexists('mc_card',  'times_text')) {
	pdo_query("ALTER TABLE ".tablename('mc_card')." ADD `times_text` varchar(15) NOT NULL COMMENT '计时名称';");
}
if(!pdo_fieldexists('mc_card',  'times')) {
	pdo_query("ALTER TABLE ".tablename('mc_card')." ADD `times` varchar(1000) NOT NULL DEFAULT '' COMMENT '计时规则';");
}
if(!pdo_fieldexists('mc_card',  'params')) {
	pdo_query("ALTER TABLE ".tablename('mc_card')." ADD `params` longtext NOT NULL;");
}
if(!pdo_fieldexists('mc_card',  'html')) {
	pdo_query("ALTER TABLE ".tablename('mc_card')." ADD `html` longtext NOT NULL;");
}
if(!pdo_fieldexists('mc_card',  'recommend_status')) {
	pdo_query("ALTER TABLE ".tablename('mc_card')." ADD `recommend_status` tinyint(3) unsigned NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('mc_card',  'sign_status')) {
	pdo_query("ALTER TABLE ".tablename('mc_card')." ADD `sign_status` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '签到功能是否开启，0为关闭，1为开启';");
}
if(!pdo_fieldexists('mc_card',  'brand_name')) {
	pdo_query("ALTER TABLE ".tablename('mc_card')." ADD `brand_name` varchar(128) NOT NULL DEFAULT '' COMMENT '商户名字,';");
}
if(!pdo_fieldexists('mc_card',  'notice')) {
	pdo_query("ALTER TABLE ".tablename('mc_card')." ADD `notice` varchar(48) NOT NULL DEFAULT '' COMMENT '卡券使用提醒';");
}
if(!pdo_fieldexists('mc_card',  'quantity')) {
	pdo_query("ALTER TABLE ".tablename('mc_card')." ADD `quantity` int(10) NOT NULL DEFAULT '0' COMMENT '会员卡库存';");
}
if(!pdo_fieldexists('mc_card',  'max_increase_bonus')) {
	pdo_query("ALTER TABLE ".tablename('mc_card')." ADD `max_increase_bonus` int(10) NOT NULL DEFAULT '0' COMMENT '用户单次可获取的积分上限';");
}
if(!pdo_fieldexists('mc_card',  'least_money_to_use_bonus')) {
	pdo_query("ALTER TABLE ".tablename('mc_card')." ADD `least_money_to_use_bonus` int(10) NOT NULL DEFAULT '0' COMMENT '抵扣条件';");
}
if(!pdo_fieldexists('mc_card',  'source')) {
	pdo_query("ALTER TABLE ".tablename('mc_card')." ADD `source` int(1) NOT NULL DEFAULT '1' COMMENT '1.系统会员卡，2微信会员卡';");
}
if(!pdo_fieldexists('mc_card',  'card_id')) {
	pdo_query("ALTER TABLE ".tablename('mc_card')." ADD `card_id` varchar(250) NOT NULL DEFAULT '';");
}
if(!pdo_indexexists('mc_card',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('mc_card')." ADD KEY `uniacid` (`uniacid`);");
}
if(!pdo_fieldexists('mc_card_credit_set',  'id')) {
	pdo_query("ALTER TABLE ".tablename('mc_card_credit_set')." ADD `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('mc_card_credit_set',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('mc_card_credit_set')." ADD `uniacid` int(10) unsigned NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('mc_card_credit_set',  'sign')) {
	pdo_query("ALTER TABLE ".tablename('mc_card_credit_set')." ADD `sign` varchar(1000) NOT NULL;");
}
if(!pdo_fieldexists('mc_card_credit_set',  'share')) {
	pdo_query("ALTER TABLE ".tablename('mc_card_credit_set')." ADD `share` varchar(500) NOT NULL;");
}
if(!pdo_fieldexists('mc_card_credit_set',  'content')) {
	pdo_query("ALTER TABLE ".tablename('mc_card_credit_set')." ADD `content` text NOT NULL;");
}
if(!pdo_indexexists('mc_card_credit_set',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('mc_card_credit_set')." ADD KEY `uniacid` (`uniacid`);");
}
if(!pdo_fieldexists('mc_card_members',  'id')) {
	pdo_query("ALTER TABLE ".tablename('mc_card_members')." ADD `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('mc_card_members',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('mc_card_members')." ADD `uniacid` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('mc_card_members',  'uid')) {
	pdo_query("ALTER TABLE ".tablename('mc_card_members')." ADD `uid` int(10) DEFAULT NULL;");
}
if(!pdo_fieldexists('mc_card_members',  'openid')) {
	pdo_query("ALTER TABLE ".tablename('mc_card_members')." ADD `openid` varchar(50) NOT NULL;");
}
if(!pdo_fieldexists('mc_card_members',  'cid')) {
	pdo_query("ALTER TABLE ".tablename('mc_card_members')." ADD `cid` int(10) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('mc_card_members',  'cardsn')) {
	pdo_query("ALTER TABLE ".tablename('mc_card_members')." ADD `cardsn` varchar(20) NOT NULL DEFAULT '';");
}
if(!pdo_fieldexists('mc_card_members',  'status')) {
	pdo_query("ALTER TABLE ".tablename('mc_card_members')." ADD `status` tinyint(1) NOT NULL;");
}
if(!pdo_fieldexists('mc_card_members',  'createtime')) {
	pdo_query("ALTER TABLE ".tablename('mc_card_members')." ADD `createtime` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('mc_card_members',  'nums')) {
	pdo_query("ALTER TABLE ".tablename('mc_card_members')." ADD `nums` int(10) unsigned NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('mc_card_members',  'endtime')) {
	pdo_query("ALTER TABLE ".tablename('mc_card_members')." ADD `endtime` int(10) unsigned NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('mc_card_notices',  'id')) {
	pdo_query("ALTER TABLE ".tablename('mc_card_notices')." ADD `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('mc_card_notices',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('mc_card_notices')." ADD `uniacid` int(10) unsigned NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('mc_card_notices',  'uid')) {
	pdo_query("ALTER TABLE ".tablename('mc_card_notices')." ADD `uid` int(10) unsigned NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('mc_card_notices',  'type')) {
	pdo_query("ALTER TABLE ".tablename('mc_card_notices')." ADD `type` tinyint(3) unsigned NOT NULL DEFAULT '1' COMMENT '1:公共消息，2:个人消息';");
}
if(!pdo_fieldexists('mc_card_notices',  'title')) {
	pdo_query("ALTER TABLE ".tablename('mc_card_notices')." ADD `title` varchar(30) NOT NULL;");
}
if(!pdo_fieldexists('mc_card_notices',  'thumb')) {
	pdo_query("ALTER TABLE ".tablename('mc_card_notices')." ADD `thumb` varchar(100) NOT NULL;");
}
if(!pdo_fieldexists('mc_card_notices',  'groupid')) {
	pdo_query("ALTER TABLE ".tablename('mc_card_notices')." ADD `groupid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '通知会员组。默认为所有会员';");
}
if(!pdo_fieldexists('mc_card_notices',  'content')) {
	pdo_query("ALTER TABLE ".tablename('mc_card_notices')." ADD `content` text NOT NULL;");
}
if(!pdo_fieldexists('mc_card_notices',  'addtime')) {
	pdo_query("ALTER TABLE ".tablename('mc_card_notices')." ADD `addtime` int(10) unsigned NOT NULL DEFAULT '0';");
}
if(!pdo_indexexists('mc_card_notices',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('mc_card_notices')." ADD KEY `uniacid` (`uniacid`);");
}
if(!pdo_indexexists('mc_card_notices',  'uid')) {
	pdo_query("ALTER TABLE ".tablename('mc_card_notices')." ADD KEY `uid` (`uid`);");
}
if(!pdo_fieldexists('mc_card_notices_unread',  'id')) {
	pdo_query("ALTER TABLE ".tablename('mc_card_notices_unread')." ADD `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('mc_card_notices_unread',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('mc_card_notices_unread')." ADD `uniacid` int(10) unsigned NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('mc_card_notices_unread',  'notice_id')) {
	pdo_query("ALTER TABLE ".tablename('mc_card_notices_unread')." ADD `notice_id` int(10) unsigned NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('mc_card_notices_unread',  'uid')) {
	pdo_query("ALTER TABLE ".tablename('mc_card_notices_unread')." ADD `uid` int(10) unsigned NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('mc_card_notices_unread',  'is_new')) {
	pdo_query("ALTER TABLE ".tablename('mc_card_notices_unread')." ADD `is_new` tinyint(3) unsigned NOT NULL DEFAULT '1';");
}
if(!pdo_fieldexists('mc_card_notices_unread',  'type')) {
	pdo_query("ALTER TABLE ".tablename('mc_card_notices_unread')." ADD `type` tinyint(3) unsigned NOT NULL DEFAULT '1' COMMENT '1:公共通知，2：个人通知';");
}
if(!pdo_indexexists('mc_card_notices_unread',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('mc_card_notices_unread')." ADD KEY `uniacid` (`uniacid`);");
}
if(!pdo_indexexists('mc_card_notices_unread',  'uid')) {
	pdo_query("ALTER TABLE ".tablename('mc_card_notices_unread')." ADD KEY `uid` (`uid`);");
}
if(!pdo_indexexists('mc_card_notices_unread',  'notice_id')) {
	pdo_query("ALTER TABLE ".tablename('mc_card_notices_unread')." ADD KEY `notice_id` (`notice_id`);");
}
if(!pdo_fieldexists('mc_card_record',  'id')) {
	pdo_query("ALTER TABLE ".tablename('mc_card_record')." ADD `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('mc_card_record',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('mc_card_record')." ADD `uniacid` int(10) unsigned NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('mc_card_record',  'uid')) {
	pdo_query("ALTER TABLE ".tablename('mc_card_record')." ADD `uid` int(10) unsigned NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('mc_card_record',  'type')) {
	pdo_query("ALTER TABLE ".tablename('mc_card_record')." ADD `type` varchar(15) NOT NULL;");
}
if(!pdo_fieldexists('mc_card_record',  'model')) {
	pdo_query("ALTER TABLE ".tablename('mc_card_record')." ADD `model` tinyint(3) unsigned NOT NULL DEFAULT '1' COMMENT '1：充值，2：消费';");
}
if(!pdo_fieldexists('mc_card_record',  'fee')) {
	pdo_query("ALTER TABLE ".tablename('mc_card_record')." ADD `fee` decimal(10,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '充值金额';");
}
if(!pdo_fieldexists('mc_card_record',  'tag')) {
	pdo_query("ALTER TABLE ".tablename('mc_card_record')." ADD `tag` varchar(10) NOT NULL COMMENT '次数|时长|充值金额';");
}
if(!pdo_fieldexists('mc_card_record',  'note')) {
	pdo_query("ALTER TABLE ".tablename('mc_card_record')." ADD `note` varchar(255) NOT NULL;");
}
if(!pdo_fieldexists('mc_card_record',  'remark')) {
	pdo_query("ALTER TABLE ".tablename('mc_card_record')." ADD `remark` varchar(200) NOT NULL COMMENT '备注，只有管理员可以看';");
}
if(!pdo_fieldexists('mc_card_record',  'addtime')) {
	pdo_query("ALTER TABLE ".tablename('mc_card_record')." ADD `addtime` int(10) unsigned NOT NULL DEFAULT '0';");
}
if(!pdo_indexexists('mc_card_record',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('mc_card_record')." ADD KEY `uniacid` (`uniacid`);");
}
if(!pdo_indexexists('mc_card_record',  'uid')) {
	pdo_query("ALTER TABLE ".tablename('mc_card_record')." ADD KEY `uid` (`uid`);");
}
if(!pdo_indexexists('mc_card_record',  'addtime')) {
	pdo_query("ALTER TABLE ".tablename('mc_card_record')." ADD KEY `addtime` (`addtime`);");
}
if(!pdo_fieldexists('mc_card_sign_record',  'id')) {
	pdo_query("ALTER TABLE ".tablename('mc_card_sign_record')." ADD `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('mc_card_sign_record',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('mc_card_sign_record')." ADD `uniacid` int(10) unsigned NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('mc_card_sign_record',  'uid')) {
	pdo_query("ALTER TABLE ".tablename('mc_card_sign_record')." ADD `uid` int(10) unsigned NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('mc_card_sign_record',  'credit')) {
	pdo_query("ALTER TABLE ".tablename('mc_card_sign_record')." ADD `credit` int(10) unsigned NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('mc_card_sign_record',  'is_grant')) {
	pdo_query("ALTER TABLE ".tablename('mc_card_sign_record')." ADD `is_grant` tinyint(3) unsigned NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('mc_card_sign_record',  'addtime')) {
	pdo_query("ALTER TABLE ".tablename('mc_card_sign_record')." ADD `addtime` int(10) unsigned NOT NULL DEFAULT '0';");
}
if(!pdo_indexexists('mc_card_sign_record',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('mc_card_sign_record')." ADD KEY `uniacid` (`uniacid`);");
}
if(!pdo_indexexists('mc_card_sign_record',  'uid')) {
	pdo_query("ALTER TABLE ".tablename('mc_card_sign_record')." ADD KEY `uid` (`uid`);");
}
if(!pdo_fieldexists('paycenter_order',  'id')) {
	pdo_query("ALTER TABLE ".tablename('paycenter_order')." ADD `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('paycenter_order',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('paycenter_order')." ADD `uniacid` int(10) unsigned NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('paycenter_order',  'uid')) {
	pdo_query("ALTER TABLE ".tablename('paycenter_order')." ADD `uid` int(10) unsigned NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('paycenter_order',  'pid')) {
	pdo_query("ALTER TABLE ".tablename('paycenter_order')." ADD `pid` int(10) unsigned NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('paycenter_order',  'clerk_id')) {
	pdo_query("ALTER TABLE ".tablename('paycenter_order')." ADD `clerk_id` int(10) unsigned NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('paycenter_order',  'store_id')) {
	pdo_query("ALTER TABLE ".tablename('paycenter_order')." ADD `store_id` int(10) unsigned NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('paycenter_order',  'clerk_type')) {
	pdo_query("ALTER TABLE ".tablename('paycenter_order')." ADD `clerk_type` tinyint(3) unsigned NOT NULL DEFAULT '2';");
}
if(!pdo_fieldexists('paycenter_order',  'uniontid')) {
	pdo_query("ALTER TABLE ".tablename('paycenter_order')." ADD `uniontid` varchar(40) NOT NULL;");
}
if(!pdo_fieldexists('paycenter_order',  'transaction_id')) {
	pdo_query("ALTER TABLE ".tablename('paycenter_order')." ADD `transaction_id` varchar(40) NOT NULL;");
}
if(!pdo_fieldexists('paycenter_order',  'type')) {
	pdo_query("ALTER TABLE ".tablename('paycenter_order')." ADD `type` varchar(10) NOT NULL COMMENT '支付方式';");
}
if(!pdo_fieldexists('paycenter_order',  'trade_type')) {
	pdo_query("ALTER TABLE ".tablename('paycenter_order')." ADD `trade_type` varchar(10) NOT NULL COMMENT '支付类型:刷卡支付,扫描支付';");
}
if(!pdo_fieldexists('paycenter_order',  'body')) {
	pdo_query("ALTER TABLE ".tablename('paycenter_order')." ADD `body` varchar(255) NOT NULL COMMENT '商品信息';");
}
if(!pdo_fieldexists('paycenter_order',  'fee')) {
	pdo_query("ALTER TABLE ".tablename('paycenter_order')." ADD `fee` varchar(15) NOT NULL COMMENT '商品费用';");
}
if(!pdo_fieldexists('paycenter_order',  'final_fee')) {
	pdo_query("ALTER TABLE ".tablename('paycenter_order')." ADD `final_fee` decimal(10,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '优惠后应付价格';");
}
if(!pdo_fieldexists('paycenter_order',  'credit1')) {
	pdo_query("ALTER TABLE ".tablename('paycenter_order')." ADD `credit1` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '抵消积分';");
}
if(!pdo_fieldexists('paycenter_order',  'credit1_fee')) {
	pdo_query("ALTER TABLE ".tablename('paycenter_order')." ADD `credit1_fee` decimal(10,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '积分抵消金额';");
}
if(!pdo_fieldexists('paycenter_order',  'credit2')) {
	pdo_query("ALTER TABLE ".tablename('paycenter_order')." ADD `credit2` decimal(10,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '余额支付金额';");
}
if(!pdo_fieldexists('paycenter_order',  'cash')) {
	pdo_query("ALTER TABLE ".tablename('paycenter_order')." ADD `cash` decimal(10,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '线上支付金额';");
}
if(!pdo_fieldexists('paycenter_order',  'remark')) {
	pdo_query("ALTER TABLE ".tablename('paycenter_order')." ADD `remark` varchar(255) NOT NULL;");
}
if(!pdo_fieldexists('paycenter_order',  'auth_code')) {
	pdo_query("ALTER TABLE ".tablename('paycenter_order')." ADD `auth_code` varchar(30) NOT NULL;");
}
if(!pdo_fieldexists('paycenter_order',  'openid')) {
	pdo_query("ALTER TABLE ".tablename('paycenter_order')." ADD `openid` varchar(50) NOT NULL;");
}
if(!pdo_fieldexists('paycenter_order',  'nickname')) {
	pdo_query("ALTER TABLE ".tablename('paycenter_order')." ADD `nickname` varchar(50) NOT NULL COMMENT '付款人';");
}
if(!pdo_fieldexists('paycenter_order',  'follow')) {
	pdo_query("ALTER TABLE ".tablename('paycenter_order')." ADD `follow` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '是否关注公众号';");
}
if(!pdo_fieldexists('paycenter_order',  'status')) {
	pdo_query("ALTER TABLE ".tablename('paycenter_order')." ADD `status` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '线上支付状态';");
}
if(!pdo_fieldexists('paycenter_order',  'credit_status')) {
	pdo_query("ALTER TABLE ".tablename('paycenter_order')." ADD `credit_status` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '积分,余额的交易状态.0:未扣除,1:已扣除';");
}
if(!pdo_fieldexists('paycenter_order',  'paytime')) {
	pdo_query("ALTER TABLE ".tablename('paycenter_order')." ADD `paytime` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '支付时间';");
}
if(!pdo_fieldexists('paycenter_order',  'createtime')) {
	pdo_query("ALTER TABLE ".tablename('paycenter_order')." ADD `createtime` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间';");
}
if(!pdo_indexexists('paycenter_order',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('paycenter_order')." ADD KEY `uniacid` (`uniacid`);");
}

?>