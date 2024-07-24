
CREATE TABLE IF NOT EXISTS `{{pre}}user` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `pid` int(11) NOT NULL DEFAULT '0' COMMENT '推荐人',
  `username` varchar(32) NOT NULL COMMENT '用户名',
  `pwd` varchar(32) DEFAULT NULL COMMENT '密码',
  `email` varchar(32) DEFAULT '' COMMENT '邮箱',
  `qq` varchar(16) DEFAULT '' COMMENT 'qq',
  `status` tinyint(4) NOT NULL DEFAULT '1' COMMENT '1=启用，0=禁用',
  `is_rand` tinyint(4)  DEFAULT '0' COMMENT '1=随机，0=正常',
  `rank` tinyint(4) NOT NULL DEFAULT '1' COMMENT '等级',
  `create_time` datetime DEFAULT NULL COMMENT '添加时间',
  `create_ip` varchar(15) DEFAULT '' COMMENT '创建IP',
  `tel` varchar(15) DEFAULT '' COMMENT '手机号码',
  `openid` varchar(60) DEFAULT '' COMMENT 'openid',
  `unionid` varchar(60) DEFAULT '' COMMENT 'unionid',
  `nickname` varchar(30) DEFAULT '' COMMENT '昵称',
  `money` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '余额',
  `dot` int(10) NOT NULL DEFAULT '0' COMMENT '积分',
  `bro` decimal(8,2) NOT NULL DEFAULT '0.00' COMMENT '佣金',
  `headimgurl` varchar(155) DEFAULT '' COMMENT '头像',
  `sex` tinyint(4) DEFAULT NULL COMMENT '性别',
  `weixin` varchar(55) DEFAULT NULL COMMENT '微信号',
  `realname` varchar(55) DEFAULT NULL COMMENT '真实姓名',
  `province` int(11) DEFAULT NULL COMMENT '省份',
  `city` int(11) DEFAULT NULL COMMENT '城市',
  `country` int(11) DEFAULT NULL COMMENT '区域',
  `birthday` varchar(25) DEFAULT NULL COMMENT '生日',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COMMENT='用户';



CREATE TABLE IF NOT EXISTS `{{pre}}user_address` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT COMMENT '主键ID',
  `user_id` mediumint(9) NOT NULL COMMENT '用户ID',
  `name` varchar(25) NOT NULL DEFAULT '' COMMENT '收货人姓名',
  `tel` varchar(11) DEFAULT NULL COMMENT '手机',
  `province` int(8) NOT NULL COMMENT '省份',
  `city` int(8) NOT NULL COMMENT '城市',
  `country` int(8) NOT NULL COMMENT '区域',
  `address` varchar(255) NOT NULL COMMENT '详细地址',
  `is_default` tinyint(4) NOT NULL DEFAULT '0' COMMENT '1=默认，0=非默认',
  `postcode` char(6) NOT NULL DEFAULT '' COMMENT '邮政编码',
  `telephone` int(5) DEFAULT NULL COMMENT '固定电话',
  `reference` varchar(255) DEFAULT NULL COMMENT '地点',
  `reference_address` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8 COMMENT='用户地址';



CREATE TABLE IF NOT EXISTS `{{pre}}user_bro` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '系统编号',
  `user_id` int(11) NOT NULL COMMENT '用户编号',
  `type` tinyint(4) DEFAULT '1' COMMENT '1=收入，2=减少',
  `total` decimal(10,2) DEFAULT NULL COMMENT '额度',
  `current_total` decimal(10,2) DEFAULT NULL COMMENT '本次结余',
  `ordernum` varchar(55) DEFAULT NULL COMMENT '单号',
  `msg` varchar(100) DEFAULT NULL COMMENT '备注',
  `time` datetime DEFAULT NULL COMMENT '时间',
  `cate` tinyint(4) DEFAULT '0' COMMENT '分类',
  `admin_id` int(11) DEFAULT NULL COMMENT '操作管理员',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='佣金日志';



CREATE TABLE IF NOT EXISTS `{{pre}}user_brodj` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '变化',
  `user_id` int(11) DEFAULT NULL COMMENT '用户',
  `total` decimal(8,2) DEFAULT NULL COMMENT '金额',
  `expire` datetime DEFAULT NULL COMMENT '到期时间',
  `time` datetime DEFAULT NULL COMMENT '生成时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='佣金冻结记录';

CREATE TABLE IF NOT EXISTS `{{pre}}user_dot` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '系统编号',
  `user_id` int(11) NOT NULL COMMENT '用户编号',
  `type` tinyint(4) DEFAULT '1' COMMENT '1=收入，2=减少',
  `total` decimal(10,2) DEFAULT NULL COMMENT '额度',
 `current_total` decimal(10,2) DEFAULT NULL COMMENT '本次结余',
  `ordernum` varchar(55) DEFAULT NULL COMMENT '单号',
  `msg` varchar(100) DEFAULT NULL COMMENT '备注',
  `time` datetime DEFAULT NULL COMMENT '时间',
  `cate` tinyint(4) DEFAULT '0' COMMENT '分类',
  `admin_id` int(11) DEFAULT NULL COMMENT '操作管理员',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='用户积分明细表';


CREATE TABLE IF NOT EXISTS `{{pre}}user_ext` (
  `user_id` int(11) NOT NULL,
  `share_num` int(11) DEFAULT '0' COMMENT '邀请数量',
  `mall_total` decimal(10,2) DEFAULT '0.00' COMMENT '消费金额',
  `mall_order_avg` decimal(10,2) DEFAULT '0.00' COMMENT '订单均价',
  `mall_new_time` datetime DEFAULT NULL COMMENT '最近购买时间',
  `mall_order_num` int(8) DEFAULT '0' COMMENT '支付订单数量',
  `mall_feek_num` int(8) DEFAULT '0' COMMENT '售后订单数量',
  `mall_com_num` int(8) DEFAULT '0' COMMENT '评价数量',
  `mall_atn_num` int(8) DEFAULT '0' COMMENT '收藏商品数量',
  `get_coupon` int(8) DEFAULT '0' COMMENT '是否领取新人券',
  PRIMARY KEY (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='用户扩展';

CREATE TABLE IF NOT EXISTS `{{pre}}user_login` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '系统编号',
  `time` datetime DEFAULT NULL COMMENT '时间',
  `ip` varchar(25) DEFAULT NULL COMMENT 'IP',
  `user_id` int(11) DEFAULT NULL COMMENT '用户编号',
  `source` varchar(55) DEFAULT NULL COMMENT '来源',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=88 DEFAULT CHARSET=utf8 COMMENT='用户登录日志';


CREATE TABLE IF NOT EXISTS `{{pre}}user_money` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '系统编号',
  `user_id` int(11) NOT NULL COMMENT '用户编号',
  `type` tinyint(4) DEFAULT '1' COMMENT '1=收入，2=减少',
  `total` decimal(10,2) DEFAULT NULL COMMENT '额度',
  `current_total` decimal(10,2) DEFAULT NULL COMMENT '本次结余',
  `ordernum` varchar(55) DEFAULT NULL COMMENT '单号',
  `msg` varchar(100) DEFAULT NULL COMMENT '备注',
  `time` datetime DEFAULT NULL COMMENT '时间',
  `cate` tinyint(4) DEFAULT '0' COMMENT '分类',
  `admin_id` int(11) DEFAULT NULL COMMENT '操作管理员',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='用户余额记录表';


CREATE TABLE IF NOT EXISTS `{{pre}}user_msg` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` mediumint(9) NOT NULL COMMENT '接收信息的用户ID',
  `sender` varchar(25) NOT NULL DEFAULT '' COMMENT '发送者',
  `title` varchar(55) NOT NULL DEFAULT '' COMMENT '标题',
  `content` text NOT NULL COMMENT '内容',
  `time` datetime DEFAULT NULL COMMENT '发送时间',
  `is_read` tinyint(4) NOT NULL DEFAULT '0' COMMENT '0=未读，1=已读',
  `is_del` tinyint(4) NOT NULL DEFAULT '0' COMMENT '0=不删除，1=删除',
  `type` tinyint(4) DEFAULT '1' COMMENT '类型',
  `link` varchar(255) DEFAULT NULL COMMENT '链接',
  `map` text COMMENT '其他参数',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='用户消息表';


CREATE TABLE IF NOT EXISTS `{{pre}}user_parent` (
  `user_id` mediumint(9) unsigned NOT NULL,
  `pid1` mediumint(9) unsigned NOT NULL DEFAULT '0' COMMENT '一级PID',
  `pid2` mediumint(9) unsigned NOT NULL DEFAULT '0',
  `pid3` mediumint(9) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`user_id`),
  KEY `c1` (`pid1`),
  KEY `c3` (`pid3`),
  KEY `c2` (`pid2`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='user的父类关系';


CREATE TABLE IF NOT EXISTS `{{pre}}user_rank` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(55) DEFAULT NULL COMMENT '角色名称',
  `discount` decimal(8,2) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8 COMMENT='用户等级';

-- ----------------------------
-- Records of xf_user_rank
-- ----------------------------
INSERT INTO `{{pre}}user_rank` VALUES ('1', '普通会员', '100.00');
INSERT INTO `{{pre}}user_rank` VALUES ('2', '银级会员', '100.00');
INSERT INTO `{{pre}}user_rank` VALUES ('3', '金级会员', '100.00');
INSERT INTO `{{pre}}user_rank` VALUES ('4', '砖石会员', '100.00');
INSERT INTO `{{pre}}user_rank` VALUES ('5', '超级会员', '100.00');


CREATE TABLE IF NOT EXISTS `{{pre}}user_token` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '系统编号',
  `user_id` int(11) DEFAULT NULL COMMENT '用户编号',
  `token` varchar(40) DEFAULT NULL COMMENT 'token',
  `expire` datetime DEFAULT NULL COMMENT '过期时间',
  PRIMARY KEY (`id`),
  KEY `token` (`token`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COMMENT='用户token';




CREATE TABLE IF NOT EXISTS `{{pre}}user_cashout_channel` (
   `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '编号',
   `name` varchar(255) DEFAULT NULL COMMENT '银行名称',
   `address` varchar(255) DEFAULT NULL COMMENT '开户点',
   `num` varchar(255) DEFAULT NULL COMMENT '账号',
   `user_id` int(11) DEFAULT NULL COMMENT '用户',
   `realname` varchar(255) DEFAULT NULL COMMENT '姓名',
   `tel` varchar(255) DEFAULT NULL COMMENT '手机',
   `cate` varchar(25) DEFAULT '' COMMENT '渠道分类|weixin=微信,bank=银行卡,alipay=支付宝',
   PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8 COMMENT='提现渠道';

CREATE TABLE IF NOT EXISTS `{{pre}}user_cashout_apply` (
     `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
     `user_id` mediumint(9) NOT NULL COMMENT '用户ID',
     `money` decimal(12,2) NOT NULL DEFAULT '0.00' COMMENT '申请金额',
     `time` datetime DEFAULT NULL COMMENT '申请时间',
     `update_time` datetime DEFAULT NULL COMMENT '处理时间',
     `status` tinyint(4) NOT NULL DEFAULT '0' COMMENT '状态|0=未处理,1=已发放现金,2=退回,3=待核实',
     `order_num` varchar(20) NOT NULL DEFAULT '' COMMENT '流水号',
     `partner_trade_no` varchar(255) DEFAULT NULL COMMENT '微信退款号',
     `real_total` decimal(12,2) DEFAULT NULL COMMENT '到账金额',
     `plus_total` decimal(12,2) DEFAULT NULL COMMENT '手续费',
     `cate` varchar(15) DEFAULT NULL COMMENT '金额分类',
     `name` varchar(255) DEFAULT NULL COMMENT '银行名称',
     `address` varchar(255) DEFAULT NULL COMMENT '开户点',
     `num` varchar(255) DEFAULT NULL COMMENT '账号',
     `realname` varchar(255) DEFAULT NULL COMMENT '姓名',
     `tel` varchar(255) DEFAULT NULL COMMENT '手机',
     `channel_cate` varchar(15) DEFAULT NULL COMMENT '渠道分类',
     `api_json` varchar(500) DEFAULT NULL COMMENT 'api返回',
     PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8 COMMENT='提现申请';


