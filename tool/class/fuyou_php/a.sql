CREATE TABLE `xf_mall_order_no` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '编号',
  `ordernum` varchar(55) DEFAULT NULL COMMENT '订单',
  `no` varchar(255) DEFAULT NULL COMMENT '编号',
  `is_pay` tinyint(4) DEFAULT '0' COMMENT '支付状态',
  `time` datetime DEFAULT NULL COMMENT '创建时间',
  `pay_time` datetime DEFAULT NULL COMMENT '支付时间',
  `total` float(8,2) DEFAULT NULL COMMENT '金额',
  `transaction_id` varchar(64) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='富友订单';