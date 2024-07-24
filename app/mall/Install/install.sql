
DROP TABLE IF EXISTS `xf_mall_after_log`;
CREATE TABLE IF NOT EXISTS `xf_mall_after_log` (
  `id` int(10) NOT NULL AUTO_INCREMENT COMMENT 'id',
  `type` tinyint(1) DEFAULT '0' COMMENT '操作类型|0=会员,1=门店',
  `channel` smallint(5) UNSIGNED DEFAULT '0' COMMENT '渠道编号。变动方式。',
  `order_id` int(10) DEFAULT NULL COMMENT '订单id',
  `after_id` int(11) DEFAULT NULL COMMENT '售后订单id',
  `handle_id` int(10) DEFAULT NULL COMMENT '操作人id',
  `content` varchar(255) DEFAULT NULL COMMENT '日志内容',
  `create_time` datetime DEFAULT NULL COMMENT '创建时间',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8 COMMENT='售后日志表' ROW_FORMAT=DYNAMIC;

--
-- 转存表中的数据 `xf_mall_after_log`
--

-- --------------------------------------------------------

--
-- 表的结构 `xf_mall_after_sale`
--

DROP TABLE IF EXISTS `xf_mall_after_sale`;
CREATE TABLE IF NOT EXISTS `xf_mall_after_sale` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `sn` varchar(20) DEFAULT '' COMMENT '退款单号',
  `user_id` int(10) NOT NULL COMMENT '用户id',
  `order_id` int(10) DEFAULT NULL COMMENT '订单id',
  `order_goods_id` int(10) DEFAULT NULL COMMENT '订单商品关联表id',
  `goods_id` int(10) DEFAULT NULL COMMENT '商品id',
  `item_id` int(10) DEFAULT '0' COMMENT '规格id',
  `goods_num` int(10) DEFAULT NULL COMMENT '商品数量',
  `refund_reason` varchar(255) DEFAULT NULL COMMENT '退款原因',
  `refund_remark` varchar(255) DEFAULT NULL COMMENT '退款说明',
  `refund_image` varchar(1050) DEFAULT NULL COMMENT '退款图片',
  `refund_type` tinyint(1) DEFAULT NULL COMMENT '退款类型|0=仅退款,1=退款退货',
  `refund_price` decimal(10,2) DEFAULT NULL COMMENT '退款金额',
  `express_name` varchar(255) DEFAULT NULL COMMENT '快递公司名称',
  `invoice_no` varchar(255) DEFAULT NULL COMMENT '快递单号',
  `express_remark` varchar(255) DEFAULT NULL COMMENT '物流备注说明',
  `express_image` varchar(255) DEFAULT NULL COMMENT '物流凭证',
  `confirm_take_time` datetime DEFAULT NULL COMMENT '确认收货时间',
  `status` tinyint(1) DEFAULT '0' COMMENT '售后状态|0=申请退款,1=商家拒绝,2=商品待退货,3=商家待收货,4=商家拒收货,5=等待退款,6=退款成功',
  `audit_time` datetime DEFAULT NULL COMMENT '审核时间',
  `admin_id` int(10) DEFAULT NULL COMMENT '门店管理员id',
  `admin_remark` varchar(255) DEFAULT NULL COMMENT '售后说明',
  `create_time` datetime DEFAULT NULL COMMENT '创建时间',
  `update_time` datetime DEFAULT NULL COMMENT '更新时间',
  `del` tinyint(1) DEFAULT '0' COMMENT '撤销状态|0=正常,1=已撤销',
  `goods_info` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COMMENT='售后表' ROW_FORMAT=DYNAMIC;

--
-- 转存表中的数据 `xf_mall_after_sale`
--

-- --------------------------------------------------------

--
-- 表的结构 `xf_mall_an`
--

DROP TABLE IF EXISTS `xf_mall_an`;
CREATE TABLE IF NOT EXISTS `xf_mall_an` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '系统ID',
  `title` varchar(100) DEFAULT NULL COMMENT '公告',
  `status` tinyint(4) DEFAULT '1' COMMENT '状态',
  `content` text COMMENT '内容',
  `time` datetime DEFAULT NULL COMMENT '发布时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='滚动公告';

-- --------------------------------------------------------

--
-- 表的结构 `xf_mall_bro_rule`
--

DROP TABLE IF EXISTS `xf_mall_bro_rule`;
CREATE TABLE IF NOT EXISTS `xf_mall_bro_rule` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '系统ID',
  `name` varchar(55) DEFAULT '' COMMENT '规则名称',
  `bro1` float(3,2) UNSIGNED DEFAULT '0.00' COMMENT '一级佣金比例',
  `bro2` float(3,2) DEFAULT '0.00' COMMENT '二级佣金比例',
  `bro3` float(3,2) UNSIGNED DEFAULT '0.00' COMMENT '三级佣金比例',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='佣金规则';

-- --------------------------------------------------------

--
-- 表的结构 `xf_mall_cart`
--

DROP TABLE IF EXISTS `xf_mall_cart`;
CREATE TABLE IF NOT EXISTS `xf_mall_cart` (
  `user_id` int(11) NOT NULL COMMENT '用户编号',
  `data` varchar(5000) DEFAULT '' COMMENT '购物车',
  PRIMARY KEY (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='购物车';

-- --------------------------------------------------------

--
-- 表的结构 `xf_mall_content`
--

DROP TABLE IF EXISTS `xf_mall_content`;
CREATE TABLE IF NOT EXISTS `xf_mall_content` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '编号',
  `title` varchar(255) DEFAULT NULL COMMENT '标题',
  `content` text COMMENT '文字说明',
  `type` tinyint(4) DEFAULT '1' COMMENT '类型',
  `status` tinyint(4) DEFAULT '1' COMMENT '状态',
  `sort` tinyint(4) DEFAULT '9' COMMENT '排序',
  `key` char(55) DEFAULT NULL COMMENT '标识',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='文字说明';

-- --------------------------------------------------------

--
-- 表的结构 `xf_mall_coupon`
--

DROP TABLE IF EXISTS `xf_mall_coupon`;
CREATE TABLE IF NOT EXISTS `xf_mall_coupon` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '系统编号',
  `name` varchar(55) DEFAULT NULL COMMENT '名称',
  `base_money` float(8,2) DEFAULT NULL COMMENT '起用金额',
  `money` float(8,2) DEFAULT NULL COMMENT '面值',
  `end` datetime DEFAULT NULL COMMENT '截止时间',
  `time` datetime DEFAULT NULL COMMENT '创建时间',
  `user_id` int(11) DEFAULT NULL COMMENT '用户ID',
  `status` tinyint(4) DEFAULT '0' COMMENT '是否使用',
  `goods_id` varchar(2500) DEFAULT '' COMMENT '限定商品',
  `category_id` varchar(2500) DEFAULT '' COMMENT '限定栏目',
  `range` tinyint(4) DEFAULT '1' COMMENT '1=全场,2=商品,3=分类',
  `tpl_id` int(10) UNSIGNED DEFAULT NULL COMMENT '模板编号',
  `source` tinyint(4) DEFAULT '1' COMMENT '来源',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COMMENT='优惠券';

-- --------------------------------------------------------

--
-- 表的结构 `xf_mall_coupon_tpl`
--

DROP TABLE IF EXISTS `xf_mall_coupon_tpl`;
CREATE TABLE IF NOT EXISTS `xf_mall_coupon_tpl` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '系统编号',
  `name` varchar(55) DEFAULT NULL COMMENT '名称',
  `base_money` float(8,2) DEFAULT NULL COMMENT '起用金额',
  `money` float(8,2) DEFAULT NULL COMMENT '面值',
  `time` datetime DEFAULT NULL COMMENT '创建时间',
  `range` tinyint(4) DEFAULT '1' COMMENT '1=全场,2=商品,3=分类',
  `goods_id` varchar(2500) DEFAULT '' COMMENT '限定商品',
  `category_id` varchar(2500) DEFAULT '' COMMENT '限定栏目',
  `type` tinyint(4) DEFAULT '1' COMMENT '1=模板,2=注册赠送,3=购物,4=全场',
  `end_type` tinyint(4) DEFAULT '1' COMMENT '1=固定日期,2=固定天数',
  `end` datetime DEFAULT NULL COMMENT '截止时间',
  `day` mediumint(3) UNSIGNED DEFAULT NULL COMMENT '固定天数',
  `remark` varchar(55) DEFAULT NULL COMMENT '备注',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COMMENT='优惠券模板';

-- --------------------------------------------------------

--
-- 表的结构 `xf_mall_delivery`
--

DROP TABLE IF EXISTS `xf_mall_delivery`;
CREATE TABLE IF NOT EXISTS `xf_mall_delivery` (
  `delivery_id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '模板id',
  `name` varchar(255) NOT NULL DEFAULT '' COMMENT '模板名称',
  `method` tinyint(3) UNSIGNED NOT NULL DEFAULT '1' COMMENT '计费方式(1按件数 2按重量)',
  `sort` int(11) UNSIGNED NOT NULL DEFAULT '0' COMMENT '排序方式(数字越小越靠前)',
  PRIMARY KEY (`delivery_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='运费模板';

-- --------------------------------------------------------

--
-- 表的结构 `xf_mall_delivery_rule`
--

DROP TABLE IF EXISTS `xf_mall_delivery_rule`;
CREATE TABLE IF NOT EXISTS `xf_mall_delivery_rule` (
  `rule_id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '规则id',
  `delivery_id` int(11) UNSIGNED NOT NULL DEFAULT '0' COMMENT '配送模板id',
  `region` text NOT NULL COMMENT '可配送区域(城市id集)',
  `first` double UNSIGNED NOT NULL DEFAULT '0' COMMENT '首件(个)/首重(Kg)',
  `first_fee` decimal(10,2) UNSIGNED NOT NULL DEFAULT '0.00' COMMENT '运费(元)',
  `additional` double UNSIGNED NOT NULL DEFAULT '0' COMMENT '续件/续重',
  `additional_fee` decimal(10,2) UNSIGNED NOT NULL DEFAULT '0.00' COMMENT '续费(元)',
  PRIMARY KEY (`rule_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='运费规则';

-- --------------------------------------------------------

--
-- 表的结构 `xf_mall_express_company`
--

DROP TABLE IF EXISTS `xf_mall_express_company`;
CREATE TABLE IF NOT EXISTS `xf_mall_express_company` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '系统ID',
  `status` tinyint(4) DEFAULT '0' COMMENT '是否显示',
  `expresskey` varchar(55) DEFAULT '' COMMENT '快递code',
  `expressname` varchar(55) DEFAULT '' COMMENT '快递名称',
  `expresswebsite` varchar(255) DEFAULT '' COMMENT '快递官网',
  `expresstelephone` varchar(25) DEFAULT '' COMMENT '快递电话',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8 COMMENT='快递公司';

--
-- 转存表中的数据 `xf_mall_express_company`
--

INSERT INTO `xf_mall_express_company` (`id`, `status`, `expresskey`, `expressname`, `expresswebsite`, `expresstelephone`) VALUES
(1, 1, 'yunda', '韵达', '', ''),
(2, 1, 'huitongkuaidi', '百世快递', '', ''),
(3, 1, 'yuantong', '圆通', '', ''),
(4, 1, 'zhongtong', '中通', '', ''),
(5, 1, 'shentong', '申通', '', ''),
(6, 1, 'ems', 'EMS', '', ''),
(7, 1, 'shunfeng', '顺丰', '', '');

-- --------------------------------------------------------

--
-- 表的结构 `xf_mall_goods`
--

DROP TABLE IF EXISTS `xf_mall_goods`;
CREATE TABLE IF NOT EXISTS `xf_mall_goods` (
  `goods_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '商品ID',
  `name` varchar(255) NOT NULL DEFAULT '' COMMENT '商品名称',
  `sort` tinyint(4) UNSIGNED DEFAULT '99' COMMENT '排序',
  `wares_no` varchar(55) DEFAULT NULL COMMENT '商家编码',
  `unit` varchar(5) DEFAULT '' COMMENT '计量单位',
  `thumb` varchar(255) DEFAULT '' COMMENT '缩略图',
  `status` tinyint(4) DEFAULT '1' COMMENT '1=正常，2=下架，0=软删除',
  `keywords` varchar(255) DEFAULT '' COMMENT '搜索关键词',
  `create_time` datetime DEFAULT NULL COMMENT '创建时间',
  `min_price` decimal(8,2) DEFAULT '0.00' COMMENT '商品最低价格',
  `min_market_price` decimal(8,2) DEFAULT '0.00' COMMENT '最低市场价',
  `min_pt_price` decimal(8,2) DEFAULT '0.00' COMMENT '最低拼团价',
  `small_title` varchar(55) DEFAULT '' COMMENT '小标题',
  `good_comment` mediumint(9) DEFAULT '0' COMMENT '好评数',
  `sale_num` mediumint(9) DEFAULT '0' COMMENT '销售数量',
  `is_recommend` tinyint(4) DEFAULT '0' COMMENT '推荐',
  `is_new` tinyint(4) DEFAULT '0' COMMENT '特价',
  `is_ms` tinyint(4) DEFAULT '0' COMMENT '秒杀',
  `is_down` tinyint(4) DEFAULT '1' COMMENT '倒计时效果',
  `category_id` mediumint(10) UNSIGNED DEFAULT NULL COMMENT '栏目',
  `brand_id` mediumint(10) UNSIGNED DEFAULT NULL COMMENT '品牌',
  `delivery_id` mediumint(9) DEFAULT NULL COMMENT '运费模板',
  `spec_type` tinyint(1) DEFAULT '1' COMMENT '规格|1=单规格,2=多规格',
  PRIMARY KEY (`goods_id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8 COMMENT='商品';

--
-- 转存表中的数据 `xf_mall_goods`
--

INSERT INTO `xf_mall_goods` (`goods_id`, `name`, `sort`, `wares_no`, `unit`, `thumb`, `status`, `keywords`, `create_time`, `min_price`, `min_market_price`, `min_pt_price`, `small_title`, `good_comment`, `sale_num`, `is_recommend`, `is_new`, `is_ms`, `category_id`, `brand_id`, `delivery_id`, `spec_type`) VALUES
(3, '多规格', 99, '', '', '/uploads/image/202101/3b5c0b68d522e46894834bc22a06de54.png', 1, '', NULL, '5.00', '10.00', NULL, '', 0, 17, 0, 0, 0, 0, 0, NULL, 2),
(4, '单规格', 99, '', '', '/uploads/image/202101/819b9099287cb1614284e9fb8fae96a9.jpg', 1, '', NULL, '10.00', '10.00', NULL, '', 0, 1, 0, 0, 0, 0, 0, NULL, 1);

-- --------------------------------------------------------

--
-- 表的结构 `xf_mall_goods_atn`
--

DROP TABLE IF EXISTS `xf_mall_goods_atn`;
CREATE TABLE IF NOT EXISTS `xf_mall_goods_atn` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '系统ID',
  `user_id` int(11) DEFAULT NULL COMMENT '用户ID',
  `time` datetime DEFAULT NULL COMMENT '收藏时间',
  `goods_id` int(11) DEFAULT NULL COMMENT '商品ID',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COMMENT='商品收藏';

-- --------------------------------------------------------

--
-- 表的结构 `xf_mall_goods_attr`
--

DROP TABLE IF EXISTS `xf_mall_goods_attr`;
CREATE TABLE IF NOT EXISTS `xf_mall_goods_attr` (
  `attr_id` smallint(5) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '属性ID',
  `name` varchar(25) DEFAULT NULL COMMENT '属性组名称',
  PRIMARY KEY (`attr_id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8 COMMENT='商品属性组';

--
-- 转存表中的数据 `xf_mall_goods_attr`
--

INSERT INTO `xf_mall_goods_attr` (`attr_id`, `name`) VALUES
(7, '手机'),
(8, '服装');

-- --------------------------------------------------------

--
-- 表的结构 `xf_mall_goods_attr_field`
--

DROP TABLE IF EXISTS `xf_mall_goods_attr_field`;
CREATE TABLE IF NOT EXISTS `xf_mall_goods_attr_field` (
  `field_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '字段ID',
  `name` varchar(55) DEFAULT '' COMMENT '字段名',
  `type` tinyint(4) DEFAULT '1' COMMENT '1=文本框，2=下拉,3=复选，4=多行文本',
  `sort` smallint(6) DEFAULT '99' COMMENT '排序',
  `param` text COMMENT '参数',
  `attr_id` mediumint(9) DEFAULT NULL COMMENT '所属属性ID',
  `default` varchar(55) DEFAULT '' COMMENT '默认值',
  `remark` varchar(155) DEFAULT '' COMMENT '提示信息',
  PRIMARY KEY (`field_id`)
) ENGINE=InnoDB AUTO_INCREMENT=54 DEFAULT CHARSET=utf8 COMMENT='属性值';

--
-- 转存表中的数据 `xf_mall_goods_attr_field`
--

INSERT INTO `xf_mall_goods_attr_field` (`field_id`, `name`, `type`, `sort`, `param`, `attr_id`, `default`, `remark`) VALUES
(11, '后置摄像头', 1, 99, NULL, 7, '1', '例如：1300万像素'),
(12, '摄像头类型', 1, 99, NULL, 7, '1', '例如：双摄像头（前后）'),
(13, '视频显示格式', 1, 99, NULL, 7, '1', '例如：1080P(全高清D5)'),
(14, '屏幕尺寸', 1, 99, NULL, 7, '1', '例如： 5.5英寸'),
(15, '触摸屏类型', 1, 99, NULL, 7, '1', '例如：电容式'),
(16, '分辨率', 1, 99, NULL, 7, '1', '例如：1920*1080'),
(17, '网络类型', 1, 99, NULL, 7, '1', '例如：4G全网通'),
(18, '网络模式', 1, 99, NULL, 7, '1', '例如：双卡双待'),
(19, '款式', 1, 99, NULL, 7, '1', '例如：直板'),
(20, '键盘类型', 1, 99, NULL, 7, '1', '例如：虚拟触屏键盘'),
(21, '运行内存RAM', 1, 99, NULL, 7, '1', '例如：4GB 3GB'),
(22, '存储容量', 1, 99, NULL, 7, '1', '例如：32GB 64GB'),
(23, 'CPU品牌', 1, 99, NULL, 7, '', '例如：联发科'),
(24, 'CPU型号', 1, 99, NULL, 7, '', '例如：Helio P20'),
(25, '产品名称', 4, 99, NULL, 7, '2', '例如：魅蓝E2'),
(26, '品牌', 1, 99, NULL, 7, '', '例如：Meizu/魅族'),
(27, '魅族型号', 1, 99, NULL, 7, '', '例如：魅蓝E2'),
(28, '机身颜色', 1, 99, NULL, 7, '', '例如：月光银 香槟金 曜石黑 变形金刚典藏版'),
(29, '操作系统', 1, 99, NULL, 7, '1', '例如：FLyme'),
(30, '手机类型', 1, 99, NULL, 7, '', '例如： 拍照手机 音乐手机 时尚手机 智能手机 4G手机'),
(31, '电池类型', 1, 99, NULL, 7, '', '例如：不可拆卸式电池'),
(32, '核心数', 1, 99, NULL, 7, '', '例如：智能真8核'),
(33, '包装清单', 1, 99, NULL, 7, '', '例如：主机1，数据线1，电源适配器1，保修证书1，SIM卡顶针'),
(34, '袖长', 1, 99, NULL, 8, '1', '例如：短袖'),
(35, '领型', 1, 99, NULL, 8, '1', '例如：圆领'),
(36, '袖型', 1, 99, NULL, 8, '1', '例如：常规'),
(37, '版型', 1, 99, NULL, 8, '1', '例如：标准'),
(38, '印花主题', 1, 99, NULL, 8, '1', '例如：其他'),
(39, '服饰工艺', 1, 99, NULL, 8, '1', '例如：其他'),
(40, '花型图案', 1, 99, NULL, 8, '1', '例如：其他'),
(41, '细分风格', 1, 99, NULL, 8, '1', '例如：基础大众'),
(42, '上市年份季节', 1, 99, NULL, 8, '1', '例如：2017年夏季'),
(43, '厚薄', 1, 99, NULL, 8, '1', '例如：常规'),
(44, '材质成分', 1, 99, NULL, 8, '1', '例如：棉95% 聚氨酯弹性纤维(氨纶)5%'),
(45, '货号', 1, 99, NULL, 8, '1', '例如：HNTBJ43454'),
(46, '销售渠道类型', 1, 99, NULL, 8, '1', '例如：商场同款'),
(47, '面料分类', 1, 99, NULL, 8, '1', '例如：针织布'),
(48, '品牌', 1, 99, NULL, 8, '1', '例如：海澜之家'),
(49, '基础风格', 1, 99, NULL, 8, '1', '例如：时尚都市'),
(50, '适用场景', 1, 99, NULL, 8, '', '例如：日常'),
(51, '适用对象', 1, 99, NULL, 8, '', '例如：青年'),
(52, '系统', 2, 99, '苹果\r\n安卓', 7, '苹果', ''),
(53, '地区', 3, 99, '北京\r\n上海\r\n广州', 7, '广州@上海', '');

-- --------------------------------------------------------

--
-- 表的结构 `xf_mall_goods_attr_record`
--

DROP TABLE IF EXISTS `xf_mall_goods_attr_record`;
CREATE TABLE IF NOT EXISTS `xf_mall_goods_attr_record` (
  `record_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '系统ID',
  `goods_id` int(11) DEFAULT '0' COMMENT '商品ID',
  `field_id` int(11) DEFAULT '0' COMMENT '属性子集ID',
  `val` varchar(500) DEFAULT '' COMMENT '值',
  `attr_id` mediumint(9) DEFAULT '0' COMMENT '属性组ID',
  PRIMARY KEY (`record_id`)
) ENGINE=InnoDB AUTO_INCREMENT=33 DEFAULT CHARSET=utf8 COMMENT='商品对应的属性值';

-- --------------------------------------------------------

--
-- 表的结构 `xf_mall_goods_brand`
--

DROP TABLE IF EXISTS `xf_mall_goods_brand`;
CREATE TABLE IF NOT EXISTS `xf_mall_goods_brand` (
  `brand_id` mediumint(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '品牌ID',
  `name` varchar(20) DEFAULT '' COMMENT '品牌名称',
  `desc` varchar(255) DEFAULT '' COMMENT '品牌描述',
  `sort` mediumint(9) DEFAULT '99' COMMENT '排序',
  `thumb` varchar(255) DEFAULT '' COMMENT 'logo',
  `status` tinyint(4) DEFAULT '1' COMMENT '状态',
  `letter` char(1) DEFAULT NULL COMMENT '首字母',
  PRIMARY KEY (`brand_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='商品品牌';

-- --------------------------------------------------------

--
-- 表的结构 `xf_mall_goods_category`
--

DROP TABLE IF EXISTS `xf_mall_goods_category`;
CREATE TABLE IF NOT EXISTS `xf_mall_goods_category` (
  `category_id` mediumint(8) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '类目ID',
  `name` varchar(20) DEFAULT '' COMMENT '类目名称',
  `desc` varchar(255) DEFAULT '' COMMENT '类目描述',
  `sort` mediumint(9) DEFAULT '99' COMMENT '排序',
  `is_show` tinyint(4) DEFAULT '1' COMMENT '是否显示',
  `is_nav` tinyint(4) DEFAULT '1' COMMENT '是否显示在导航',
  `thumb` varchar(255) DEFAULT '' COMMENT '缩略图',
  `pid` mediumint(9) DEFAULT '0' COMMENT '父级ID',
  `goods_num` mediumint(9) DEFAULT '0' COMMENT '商品数量',
  `english` varchar(55) DEFAULT '' COMMENT '英文',
  PRIMARY KEY (`category_id`),
  KEY `is_show` (`is_show`),
  KEY `is_nav` (`is_nav`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8 COMMENT='商品类目';

--
-- 转存表中的数据 `xf_mall_goods_category`
--

INSERT INTO `xf_mall_goods_category` (`category_id`, `name`, `desc`, `sort`, `is_show`, `is_nav`, `thumb`, `pid`, `goods_num`, `english`) VALUES
(1, '手机', '', 99, 1, 1, 'http://static.tx520.cn/chen/shouji/image/202010/79fe1e534bfdb0521d62179d18b3d948.png', 0, 0, ''),
(2, '荣耀', '', 99, 1, 1, 'http://static.tx520.cn/chen/shouji/image/202010/79fe1e534bfdb0521d62179d18b3d948.png', 1, 0, ''),
(3, '华为', '', 99, 1, 1, 'http://static.tx520.cn/chen/shouji/image/202010/04c1e2bde45e31ac1fa942095e8077e9.png', 1, 0, ''),
(4, '生鲜', '', 99, 1, 1, 'http://static.tx520.cn/ling/demo/nav-icon1.jpg', 0, 0, ''),
(6, '生鲜', '', 99, 1, 1, 'http://static.tx520.cn/ling/demo/nav-icon1.jpg', 0, 0, ''),
(7, '生鲜', '', 99, 1, 1, 'http://static.tx520.cn/ling/demo/nav-icon1.jpg', 0, 0, '');

-- --------------------------------------------------------

--
-- 表的结构 `xf_mall_goods_comment`
--

DROP TABLE IF EXISTS `xf_mall_goods_comment`;
CREATE TABLE IF NOT EXISTS `xf_mall_goods_comment` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `order_id` int(11) NOT NULL COMMENT '订单ID',
  `order_goods_id` int(11) NOT NULL COMMENT '订单商品ID',
  `goods_id` int(11) NOT NULL COMMENT '商品ID',
  `images` text COMMENT '图片',
  `rank` tinyint(4) DEFAULT '1' COMMENT '等级',
  `content` varchar(255) NOT NULL DEFAULT '' COMMENT '内容',
  `time` datetime NOT NULL COMMENT '时间',
  `user_id` int(11) NOT NULL COMMENT '用户编号',
  `status` tinyint(4) NOT NULL DEFAULT '1' COMMENT '状态',
  `nickname` varchar(50) DEFAULT NULL COMMENT '后台录入的评论者昵称',
  `star` tinyint(4) DEFAULT '5' COMMENT '星级',
  `type` tinyint(4) DEFAULT '1' COMMENT '1=正常，2=虚拟',
  `is_anonymous` tinyint(4) DEFAULT '1' COMMENT '是否匿名',
  `reply` varchar(255) DEFAULT '' COMMENT '商家回复',
  `sort` tinyint(4) DEFAULT '99' COMMENT '排序',
  `is_img` tinyint(4) DEFAULT '0' COMMENT '是否带图',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COMMENT='商品评论';

-- --------------------------------------------------------

--
-- 表的结构 `xf_mall_goods_data`
--

DROP TABLE IF EXISTS `xf_mall_goods_data`;
CREATE TABLE IF NOT EXISTS `xf_mall_goods_data` (
  `goods_id` int(10) UNSIGNED NOT NULL,
  `comment_good` int(11) DEFAULT '0' COMMENT '好评数',
  `comment_general` int(11) DEFAULT '0' COMMENT '中评数',
  `comment_low` int(11) DEFAULT '0' COMMENT '差评数',
  `comment_picture` int(11) DEFAULT '0' COMMENT '晒图评价数',
  `goods_rank` smallint(5) DEFAULT '10000' COMMENT '好评率，10000=100.00%',
  PRIMARY KEY (`goods_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='商品统计';

-- --------------------------------------------------------

--
-- 表的结构 `xf_mall_goods_ext`
--

DROP TABLE IF EXISTS `xf_mall_goods_ext`;
CREATE TABLE IF NOT EXISTS `xf_mall_goods_ext` (
  `goods_id` int(10) UNSIGNED NOT NULL COMMENT '商品ID',
  `content` mediumtext COMMENT '内容',
  `images` text COMMENT '多图',
  `bro_id` mediumint(9) DEFAULT NULL COMMENT '佣金规则对应的ID',
  `bro` float(8,2) DEFAULT '0.00' COMMENT '佣金',
  PRIMARY KEY (`goods_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='商品扩展';

--
-- 转存表中的数据 `xf_mall_goods_ext`
--

INSERT INTO `xf_mall_goods_ext` (`goods_id`, `content`, `images`, `bro_id`, `bro`) VALUES
(3, '', '[\"\\/uploads\\/image\\/202101\\/3b5c0b68d522e46894834bc22a06de54.png\"]', NULL, 0.00),
(4, '', 'null', NULL, 0.00);

-- --------------------------------------------------------

--
-- 表的结构 `xf_mall_goods_field_relation`
--

DROP TABLE IF EXISTS `xf_mall_goods_field_relation`;
CREATE TABLE IF NOT EXISTS `xf_mall_goods_field_relation` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '系统编号',
  `goods_id` int(11) DEFAULT NULL COMMENT '商品ID',
  `field_id` mediumint(9) DEFAULT NULL COMMENT '字段ID',
  `val` varchar(1000) DEFAULT NULL COMMENT '字段值',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='商品类型的属性';

-- --------------------------------------------------------

--
-- 表的结构 `xf_mall_goods_history`
--

DROP TABLE IF EXISTS `xf_mall_goods_history`;
CREATE TABLE IF NOT EXISTS `xf_mall_goods_history` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '编号',
  `user_id` int(11) DEFAULT NULL COMMENT '用户',
  `time` datetime DEFAULT NULL COMMENT '时间',
  `goods_id` int(8) DEFAULT NULL COMMENT '商品',
  `type` tinyint(4) DEFAULT '1' COMMENT '类型',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=229 DEFAULT CHARSET=utf8 COMMENT='商品足迹';

--
-- 转存表中的数据 `xf_mall_goods_history`
--

INSERT INTO `xf_mall_goods_history` (`id`, `user_id`, `time`, `goods_id`, `type`) VALUES
(42, 2, '2021-01-13 14:38:55', 2, 1),
(47, 2, '2021-01-14 17:31:19', 1, 1),
(227, 2, '2021-03-05 17:15:57', 3, 1),
(228, 2, '2021-03-05 17:25:01', 4, 1),
(80, 2, '2021-01-30 16:38:39', 5, 1);

-- --------------------------------------------------------

--
-- 表的结构 `xf_mall_goods_item`
--

DROP TABLE IF EXISTS `xf_mall_goods_item`;
CREATE TABLE IF NOT EXISTS `xf_mall_goods_item` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `image` varchar(128) DEFAULT NULL COMMENT '商品图',
  `goods_id` int(11) NOT NULL COMMENT '商品主表id',
  `spec_value_ids` varchar(32) DEFAULT '' COMMENT '多个规格id，隔开',
  `spec_value_str` varchar(64) DEFAULT '' COMMENT '多个规格名称，隔开',
  `market_price` decimal(10,2) DEFAULT NULL COMMENT '市场价',
  `price` decimal(10,2) DEFAULT NULL COMMENT '价格',
  `cost_price` decimal(10,2) DEFAULT NULL COMMENT '成本价',
  `stock` int(10) DEFAULT NULL COMMENT '库存',
  `volume` int(10) DEFAULT NULL COMMENT '体积',
  `weight` int(10) DEFAULT NULL COMMENT '重量',
  `bar_code` varchar(32) DEFAULT NULL COMMENT '条码',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=30 DEFAULT CHARSET=utf8 COMMENT='商品的SKU';

--
-- 转存表中的数据 `xf_mall_goods_item`
--

INSERT INTO `xf_mall_goods_item` (`id`, `image`, `goods_id`, `spec_value_ids`, `spec_value_str`, `market_price`, `price`, `cost_price`, `stock`, `volume`, `weight`, `bar_code`) VALUES
(24, '/uploads/image/202101/819b9099287cb1614284e9fb8fae96a9.jpg', 3, '10,12', '红,大', '10.00', '5.00', '10.00', 23421, 10, 10, '10'),
(21, '/uploads/image/202101/3b5c0b68d522e46894834bc22a06de54.png', 4, '9', '默认', '10.00', '10.00', '10.00', 9, 11, 1, '1'),
(25, '/uploads/image/202101/819b9099287cb1614284e9fb8fae96a9.jpg', 3, '10,13', '红,小', '10.00', '30.00', '10.00', 9, 10, 10, '10'),
(26, '/uploads/image/202101/819b9099287cb1614284e9fb8fae96a9.jpg', 3, '10,14', '红,中', '10.00', '40.00', '10.00', 10, 10, 10, '10'),
(27, '/uploads/image/202101/3b5c0b68d522e46894834bc22a06de54.png', 3, '11,12', '蓝,大', '10.00', '50.00', '10.00', 9, 10, 10, '10'),
(28, '/uploads/image/202101/3b5c0b68d522e46894834bc22a06de54.png', 3, '11,13', '蓝,小', '10.00', '60.00', '10.00', 10, 10, 10, '10'),
(29, '/uploads/image/202101/3b5c0b68d522e46894834bc22a06de54.png', 3, '11,14', '蓝,中', '10.00', '70.00', '10.00', 10, 10, 10, '10');

-- --------------------------------------------------------

--
-- 表的结构 `xf_mall_goods_spec`
--

DROP TABLE IF EXISTS `xf_mall_goods_spec`;
CREATE TABLE IF NOT EXISTS `xf_mall_goods_spec` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `goods_id` int(11) NOT NULL COMMENT '商品主表id',
  `name` varchar(16) NOT NULL COMMENT '规格名称',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8 COMMENT='商品规格' ROW_FORMAT=DYNAMIC;

--
-- 转存表中的数据 `xf_mall_goods_spec`
--

INSERT INTO `xf_mall_goods_spec` (`id`, `goods_id`, `name`) VALUES
(3, 0, '颜色'),
(4, 0, '默认'),
(5, 3, '颜色'),
(6, 3, '码数');

-- --------------------------------------------------------

--
-- 表的结构 `xf_mall_goods_spec_value`
--

DROP TABLE IF EXISTS `xf_mall_goods_spec_value`;
CREATE TABLE IF NOT EXISTS `xf_mall_goods_spec_value` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `goods_id` int(11) NOT NULL COMMENT '商品id',
  `spec_id` int(11) NOT NULL COMMENT '规格id',
  `value` varchar(32) NOT NULL COMMENT '规格属性值',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8 COMMENT='商品规格属性值表' ROW_FORMAT=DYNAMIC;

--
-- 转存表中的数据 `xf_mall_goods_spec_value`
--

INSERT INTO `xf_mall_goods_spec_value` (`id`, `goods_id`, `spec_id`, `value`) VALUES
(9, 4, 4, '默认'),
(10, 3, 5, '红'),
(11, 3, 5, '蓝'),
(12, 3, 6, '大'),
(13, 3, 6, '小'),
(14, 3, 6, '中');

-- --------------------------------------------------------


--
-- 表的结构 `xf_mall_order`
--

DROP TABLE IF EXISTS `xf_mall_order`;
CREATE TABLE IF NOT EXISTS `xf_mall_order` (
    `order_id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '订单ID',
    `ordernum` varchar(55) NOT NULL COMMENT '订单号',
    `type` tinyint(4) DEFAULT '1' COMMENT '类型|1=普通订单,2=秒杀订单,3=拼团订单,4=砍价订单',
    `goods_total` decimal(8,2) DEFAULT '0.00' COMMENT '商品价格',
    `old_total` decimal(8,2) DEFAULT '0.00' COMMENT '应付金额',
    `discount_total` decimal(8,2) DEFAULT '0.00' COMMENT '优惠金额',
    `total` decimal(8,2) DEFAULT '0.00' COMMENT '实际价格',
    `is_u_del` tinyint(4) DEFAULT '0' COMMENT '前台是否删除',
    `is_a_del` tinyint(4) DEFAULT '0' COMMENT '后台是否已删除',
    `user_id` int(11) DEFAULT NULL COMMENT '用户ID',
    `username` varchar(55) DEFAULT NULL COMMENT '用户名',
    `ip` varchar(20) DEFAULT '' COMMENT 'IP地址',
    `message` varchar(500) DEFAULT '' COMMENT '客户留言',
    `province` varchar(55) DEFAULT NULL COMMENT '省份',
    `city` varchar(55) DEFAULT NULL COMMENT '城市',
    `country` varchar(55) DEFAULT NULL COMMENT '区域',
    `address` varchar(100) DEFAULT '' COMMENT '地址',
    `linkman` varchar(55) DEFAULT NULL COMMENT '联系人',
    `tel` varchar(25) DEFAULT NULL COMMENT '联系电话',
    `dot` mediumint(9) DEFAULT '0' COMMENT '订单所用积分',
    `dot_total` float(8,2) DEFAULT '0.00' COMMENT '积分所抵金额',
    `coupon_id` mediumint(9) DEFAULT NULL COMMENT '所用优惠券',
    `coupon_total` float(8,2) DEFAULT '0.00' COMMENT '优惠券金额',
    `money` decimal(8,2) DEFAULT '0.00' COMMENT '所用余额',
    `rank_money` float(8,2) DEFAULT '0.00' COMMENT '角色折扣',
    `refund_status` tinyint(1) DEFAULT '0' COMMENT '退款状态|0=未退款,1=部分退款,2-全部退款',
    `refund_total` decimal(10,2) DEFAULT '0.00' COMMENT '退款金额',
    `pay_type` tinyint(4) DEFAULT '0' COMMENT '付款方式|1=货到付款,2=在线抵消,3=支付宝,4=微信',
    `pay_status` tinyint(4) DEFAULT '0' COMMENT '支付状态|0=待支付,1=已支付,2=已退款,3=拒绝退款',
    `pay_time` datetime DEFAULT NULL COMMENT '支付时间',
    `trade_no` varchar(64) DEFAULT '' COMMENT '第三方支付交易号',
    `delivery_type` tinyint(1) DEFAULT '1' COMMENT '配送方式|1=快递发货,2=上门自提,3=同城配送',
    `delivery_status` tinyint(1) unsigned DEFAULT '0' COMMENT '发货状态',
    `delivery_total` decimal(10,2) DEFAULT '0.00' COMMENT '运费',
    `delivery_time` datetime DEFAULT NULL COMMENT '最后新发货时间',
    `admin_remark` varchar(150) DEFAULT '' COMMENT '后台备注',
    `source` varchar(10) DEFAULT '0' COMMENT '来源',
    `is_new` tinyint(4) DEFAULT '0' COMMENT '新客户',
    `p_ordernum` varchar(64) DEFAULT '' COMMENT '拼团订单',
    `group_num` tinyint(4) DEFAULT '0' COMMENT '拼团数量',
    `seckill_id` int(10) DEFAULT NULL COMMENT '秒杀商品id',
    `group_id` int(10) DEFAULT NULL COMMENT '拼团产品ID',
    `goods_num` int(11) DEFAULT '0' COMMENT '商品数量',
    `status` tinyint(2) DEFAULT '0' COMMENT '订单状态|0=待付款,1=待发货,2=待收货,3=已完成,4=已关闭,5=拼团中,6=拼团失败',
    `comment_status` tinyint(1) DEFAULT '0' COMMENT '评论状态|0=未评论,1=已评论',
    `create_time` datetime DEFAULT NULL COMMENT '创建时间',
    `update_time` datetime DEFAULT NULL COMMENT '更新时间',
    `after_status` tinyint(4) DEFAULT '0' COMMENT '售后|0=未申请退款,1=申请退款中,2=等待退款,3=退款成功',
  PRIMARY KEY (`order_id`),
  KEY `ordernum` (`ordernum`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COMMENT='商城订单';

-- --------------------------------------------------------

--
-- 表的结构 `xf_mall_order_bro`
--

DROP TABLE IF EXISTS `xf_mall_order_bro`;
CREATE TABLE IF NOT EXISTS `xf_mall_order_bro` (
  `order_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '订单ID',
  `bro1` float(8,2) DEFAULT NULL COMMENT '一级佣金',
  `bro2` float(8,2) DEFAULT NULL COMMENT '二级佣金',
  `bro3` float(8,2) DEFAULT NULL COMMENT '三级佣金',
  `pid1` int(8) DEFAULT NULL COMMENT '一级上级',
  `pid2` int(8) DEFAULT NULL COMMENT '二级上级',
  `pid3` int(8) DEFAULT NULL COMMENT '三级上级',
  `status` tinyint(4) DEFAULT '0' COMMENT '发放状态',
  PRIMARY KEY (`order_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='订单佣金';

-- --------------------------------------------------------

--
-- 表的结构 `xf_mall_order_feedback`
--

DROP TABLE IF EXISTS `xf_mall_order_feedback`;
CREATE TABLE IF NOT EXISTS `xf_mall_order_feedback` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '系统ID',
  `order_id` int(11) DEFAULT NULL COMMENT '订单ID',
  `create_time` datetime DEFAULT NULL COMMENT '创建时间',
  `content` varchar(400) DEFAULT NULL COMMENT '申请缘由',
  `tel` varchar(25) DEFAULT NULL COMMENT '手机',
  `name` varchar(25) DEFAULT NULL COMMENT '姓名',
  `remark` varchar(500) DEFAULT NULL COMMENT '处理备注',
  `up_time` datetime DEFAULT NULL COMMENT '处理时间',
  `status` tinyint(4) DEFAULT '1' COMMENT '状态',
  `type` tinyint(4) DEFAULT '0' COMMENT '类型',
  `img` text COMMENT '图片',
  `is_chai` int(10) DEFAULT NULL COMMENT '是否拆开',
  `ex_name` varchar(155) DEFAULT NULL COMMENT '退货快递',
  `ex_num` varchar(155) DEFAULT NULL COMMENT '退货单号',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='订单售后';

-- --------------------------------------------------------

--
-- 表的结构 `xf_mall_order_flog`
--

DROP TABLE IF EXISTS `xf_mall_order_flog`;
CREATE TABLE IF NOT EXISTS `xf_mall_order_flog` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '编号',
  `order_id` int(11) DEFAULT NULL COMMENT '订单',
  `time` datetime DEFAULT NULL COMMENT '时间',
  `msg` varchar(255) DEFAULT NULL COMMENT '备注',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='售后日志';

-- --------------------------------------------------------

--
-- 表的结构 `xf_mall_order_goods`
--

DROP TABLE IF EXISTS `xf_mall_order_goods`;
CREATE TABLE IF NOT EXISTS `xf_mall_order_goods` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '系统ID',
  `order_id` int(11) DEFAULT NULL COMMENT '订单ID',
  `name` varchar(55) DEFAULT NULL COMMENT '商品名',
  `thumb` varchar(255) DEFAULT NULL COMMENT '缩略图',
  `num` int(11) DEFAULT '0' COMMENT '数量',
  `unit_price` float(8,2) DEFAULT NULL COMMENT '单价',
  `total_price` float(8,2) DEFAULT '0.00' COMMENT '小计',
  `total_pay_price` decimal(10,2) DEFAULT '0.00' COMMENT '实际支付商品金额',
  `discount_total` decimal(10,2) DEFAULT '0.00' COMMENT '优惠金额',
  `after_status` tinyint(1) DEFAULT '0' COMMENT '售后状态|0=未申请退款,1=申请退款,2=等待退款,3=退款成功',
  `delivery_id` int(11) DEFAULT '0' COMMENT '发货单ID',
  `goods_id` mediumint(9) DEFAULT NULL COMMENT '商品ID',
  `category_id` int(11) DEFAULT NULL COMMENT '分类',
  `sku` varchar(55) DEFAULT NULL COMMENT '货号',
  `is_comment` tinyint(1) DEFAULT '0' COMMENT '是否已评论|0=否,1=是',
  `goods_info` varchar(1000) DEFAULT NULL COMMENT '商品信息',
  `spec` varchar(2000) DEFAULT NULL COMMENT '参数',
  `spec_str` varchar(255) DEFAULT NULL COMMENT '规格字符串',
  `item_id` int(11) DEFAULT NULL COMMENT 'sku编号',
  `old_unit_price` decimal(10,2) DEFAULT '0.00' COMMENT '原单价',
  `old_total_price` decimal(10,2) DEFAULT '0.00' COMMENT '旧小计',
   `user_id` int(11) DEFAULT NULL COMMENT '用户',
  PRIMARY KEY (`id`),
  KEY `order_id` (`order_id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COMMENT='订单商品';

-- --------------------------------------------------------

--
-- 表的结构 `xf_mall_order_log`
--

DROP TABLE IF EXISTS `xf_mall_order_log`;
CREATE TABLE IF NOT EXISTS `xf_mall_order_log` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '系统ID',
  `order_id` int(11) DEFAULT NULL COMMENT '订单ID',
  `time` datetime DEFAULT NULL COMMENT '时间',
  `msg` varchar(255) DEFAULT NULL COMMENT '备注',
  `status` tinyint(4) DEFAULT '1' COMMENT '状态',
  `cate` tinyint(4) DEFAULT NULL COMMENT '类型',
  `type` tinyint(11) DEFAULT NULL COMMENT '操作类型',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=37 DEFAULT CHARSET=utf8 COMMENT='订单日志';

-- --------------------------------------------------------

--
-- 表的结构 `xf_mall_order_refund`
--

DROP TABLE IF EXISTS `xf_mall_order_refund`;
CREATE TABLE IF NOT EXISTS `xf_mall_order_refund` (
  `id` int(6) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'id',
  `order_id` int(1) UNSIGNED DEFAULT '0' COMMENT '订单id',
  `user_id` mediumint(8) UNSIGNED NOT NULL DEFAULT '0' COMMENT '下单用户id，冗余字段',
  `refund_sn` varchar(32) NOT NULL DEFAULT '' COMMENT '退款单号，一个订单分多次退款则有多个退款单号',
  `order_amount` decimal(10,2) UNSIGNED NOT NULL DEFAULT '0.00' COMMENT '订单总的应付款金额，冗余字段',
  `refund_amount` decimal(10,2) UNSIGNED DEFAULT '0.00' COMMENT '本次退款金额',
  `transaction_id` varchar(255) DEFAULT NULL COMMENT '第三方平台交易流水号',
  `refund_status` tinyint(1) UNSIGNED DEFAULT '0' COMMENT '退款状态|0=退款中,1=完成退款,2=退款失败,3=退款异常（人工去后台查询）',
  `refund_way` tinyint(1) UNSIGNED DEFAULT '0' COMMENT '退款方式，0原路退',
  `refund_at` datetime  DEFAULT NULL  COMMENT '退款时间',
  `wechat_refund_id` varchar(30) DEFAULT NULL COMMENT '微信返回退款id',
  `refund_msg` text COMMENT '微信返回信息',
  `create_time` datetime  DEFAULT NULL  COMMENT '创建时间',
  `update_time` datetime DEFAULT NULL COMMENT '更新时间',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COMMENT='订单退款表' ROW_FORMAT=DYNAMIC;

-- --------------------------------------------------------

--
-- 表的结构 `xf_mall_order_send`
--

DROP TABLE IF EXISTS `xf_mall_order_send`;
CREATE TABLE IF NOT EXISTS `xf_mall_order_send` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '系统ID',
  `order_id` int(11) DEFAULT NULL COMMENT '订单ID',
  `time` datetime DEFAULT NULL COMMENT '时间',
  `express_name` varchar(25) DEFAULT NULL COMMENT '快递中文',
  `express_key` varchar(35) DEFAULT NULL COMMENT '快递编码',
  `express_code` varchar(55) DEFAULT NULL COMMENT '快递单号',
  `send_type` tinyint(4) DEFAULT NULL COMMENT '发货类型',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COMMENT='订单发货记录';

-- --------------------------------------------------------

--
-- 表的结构 `xf_mall_visit_log`
--

DROP TABLE IF EXISTS `xf_mall_visit_log`;
CREATE TABLE IF NOT EXISTS `xf_mall_visit_log` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '系统编号',
  `user_id` int(11) DEFAULT NULL COMMENT '用户编号',
  `time` datetime DEFAULT NULL COMMENT '访问时间',
  `goods_id` int(11) DEFAULT NULL COMMENT '商品编号',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='商品浏览记录';

-- --------------------------------------------------------

--
-- 表的结构 `xf_ms_cate`
--

DROP TABLE IF EXISTS `xf_ms_cate`;
CREATE TABLE IF NOT EXISTS `xf_ms_cate` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '编号',
  `name` varchar(255) DEFAULT NULL COMMENT '名称',
  `start` time DEFAULT NULL COMMENT '开始',
  `end` time DEFAULT NULL COMMENT '结束',
  `status` tinyint(4) DEFAULT '1' COMMENT '状态',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COMMENT='秒杀分类';

--
-- 转存表中的数据 `xf_ms_cate`
--

INSERT INTO `xf_ms_cate` (`id`, `name`, `start`, `end`, `status`) VALUES
(1, '7点场', '09:00:00', '14:59:59', 1),
(2, '8点', '15:00:00', '15:59:59', 1),
(3, '16点', '16:00:00', '18:59:59', 1);

-- --------------------------------------------------------

--
-- 表的结构 `xf_ms_goods`
--

DROP TABLE IF EXISTS `xf_ms_goods`;
CREATE TABLE IF NOT EXISTS `xf_ms_goods` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '编号',
  `goods_id` int(11) DEFAULT NULL COMMENT '商品',
  `num` int(11) DEFAULT NULL COMMENT '库存数量',
  `cate_id` int(11) DEFAULT NULL COMMENT '分类',
  `status` tinyint(4) DEFAULT '1' COMMENT '状态',
  `sort` tinyint(4) DEFAULT '99' COMMENT '排序',
  `sale_num` int(11) NOT NULL DEFAULT '0' COMMENT '已售数量',
  `items` varchar(2500) DEFAULT '' COMMENT '规格参数',
  `min_price` decimal(10,2) DEFAULT NULL COMMENT '最低价格',
  `end` datetime DEFAULT NULL COMMENT '结束时间',
  `start` datetime DEFAULT NULL COMMENT '开始时间',

    PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COMMENT='秒杀商品';

--
-- 转存表中的数据 `xf_ms_goods`
--

INSERT INTO `xf_ms_goods` (`id`, `goods_id`, `num`, `cate_id`, `status`, `sort`, `sale_num`, `items`, `min_price`, `end`) VALUES
(1, 3, 1000, 3, 1, 99, 0, '{\"24\":\"4\",\"25\":\"20\"}', '4.00', '2021-03-31 00:00:00'),
(2, 3, 1000, 1, 1, 99, 0, '{\"24\":\"4\",\"25\":\"10\"}', '4.00', '2022-02-28 00:00:00');

-- --------------------------------------------------------

--
-- 表的结构 `xf_pt_goods`
--

DROP TABLE IF EXISTS `xf_pt_goods`;
CREATE TABLE `xf_pt_goods` (
   `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '编号',
   `goods_id` int(11) DEFAULT NULL COMMENT '商品编号',
   `shop_id` int(11) DEFAULT NULL COMMENT '店铺编号',
   `num` int(11) DEFAULT NULL COMMENT '名额',
   `name` varchar(255) DEFAULT NULL COMMENT '别名',
   `status` tinyint(4) DEFAULT '1' COMMENT '状态',
   `sort` tinyint(4) DEFAULT '10' COMMENT '排序',
   `pt_num` tinyint(4) DEFAULT '2' COMMENT '拼团数量',
   `end` datetime DEFAULT NULL COMMENT '结束日期',
   `items` varchar(2500) DEFAULT NULL COMMENT '价格参数',
   `min_price` decimal(10,2) DEFAULT NULL COMMENT '最低价格',
   `start` datetime DEFAULT NULL COMMENT '开始',
   `sale_num` int(11) DEFAULT '0' COMMENT '已售',
   PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COMMENT='拼团商品';



--
-- 转存表中的数据 `xf_pt_goods`
--

INSERT INTO `xf_pt_goods` (`id`, `goods_id`, `shop_id`, `num`, `name`, `status`, `sort`, `pt_num`, `end`, `items`, `min_price`) VALUES
(1, 3, 0, 99, 'A', 1, 10, 2, '2022-02-26 00:00:00', '{\"24\":\"4\",\"25\":\"20\"}', '4.00'),
(2, 3, 0, 94, 'B', 1, 10, 2, '2022-02-26 00:00:00', '{\"24\":\"2\",\"25\":\"20\",\"26\":\"30\",\"29\":\"50\"}', '2.00');

-- --------------------------------------------------------

--
-- 表的结构 `xf_pt_order`
--

DROP TABLE IF EXISTS `xf_pt_order`;
CREATE TABLE IF NOT EXISTS `xf_pt_order` (
    `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '编号',
    `ordernum` varchar(50) DEFAULT NULL COMMENT '订单号',
    `user_id` int(11) DEFAULT NULL COMMENT '用户',
    `time` datetime DEFAULT NULL COMMENT '时间',
    `type` tinyint(4) DEFAULT NULL COMMENT '类型1=开团,2=参团',
    `p_ordernum` varchar(50) DEFAULT NULL COMMENT '上级',
    `status` tinyint(4) DEFAULT '0' COMMENT '进度0=未完成,1=支付中,2=完成,3=失败',
    `group_num` tinyint(255) DEFAULT '0' COMMENT '需要数量',
    `num` tinyint(4) DEFAULT '0' COMMENT '当前数量',
    `end_time` datetime DEFAULT NULL COMMENT '截止时间',
    `goods_id` int(11) DEFAULT NULL COMMENT '商品',
    `goods_name` varchar(55) DEFAULT NULL COMMENT '商品',
    `shop_id` int(11) DEFAULT NULL,
    `group_id` int(10) DEFAULT NULL COMMENT '对应拼团ID',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8 COMMENT='拼团订单';

CREATE TABLE `xf_mall_code` (
  `id` bigint(11) NOT NULL COMMENT '核销码',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='核销码';

CREATE TABLE `xf_mall_picks` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '编号',
  `name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '名称',
  `thumb` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '展示图',
  `lat` varchar(55) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '纬度',
  `lng` varchar(55) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '经度',
  `address` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '地址',
  `status` tinyint(4) DEFAULT '1' COMMENT '状态',
  `sort` tinyint(4) DEFAULT '10' COMMENT '排序',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='自提点';

CREATE TABLE `xf_mall_order_no` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '编号',
  `ordernum` varchar(55) DEFAULT NULL COMMENT '订单',
  `no` varchar(255) DEFAULT NULL COMMENT '富友编号',
  `is_pay` tinyint(4) DEFAULT '0' COMMENT '支付状态',
  `time` datetime DEFAULT NULL COMMENT '创建时间',
  `pay_time` datetime DEFAULT NULL COMMENT '支付时间',
  `total` float(8,2) DEFAULT NULL COMMENT '金额',
  `transaction_id` varchar(64) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COMMENT='富友订单';

