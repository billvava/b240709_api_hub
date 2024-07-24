<style>
    pre{
        border: 1px solid #ccc;
        padding: 10px;
        margin: 10px;
    }
    p{padding: 10px;
        margin: 10px;
        
    }
</style>
<p>统计图测试表<font color="red">（直接创建这个表，就可以测试统计图的效果了）</font></p>
<pre>
CREATE TABLE IF NOT EXISTS `xf_test` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '编号',
  `time` datetime DEFAULT NULL COMMENT '时间',
  `total` float(8,2) DEFAULT NULL COMMENT '金额',
  `status` tinyint(4) DEFAULT '1' COMMENT '状态|1=可用,2=不可用',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8  COMMENT='报表测试';
</pre>

<p>订单测试表</p>
<pre>
CREATE TABLE IF NOT EXISTS `@表名@` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '编号',
  `ordernum` varchar(255) DEFAULT NULL COMMENT '订单号',
  `user_id` int(11) DEFAULT NULL COMMENT '用户ID',
  `create_time` datetime DEFAULT NULL COMMENT '创建时间',
  `total` float(8,2) DEFAULT NULL COMMENT '金额',
  `is_pay` tinyint(4) DEFAULT '0' COMMENT '支付状态|1=支付,0=未支付',
  `tra_no` varchar(255) DEFAULT NULL COMMENT '流水号',
  `pay_time` datetime DEFAULT NULL COMMENT '支付时间',
  `pay_type` tinyint(4) DEFAULT NULL COMMENT '支付方式',
  `is_feek` tinyint(4) DEFAULT '0' COMMENT '退款状态',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COMMENT='订单'
</pre>

<p>文章</p>
<pre>
CREATE TABLE IF NOT EXISTS `@表名@` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '编号',
  `title` varchar(255) DEFAULT NULL COMMENT '标题',
  `remark` varchar(500) DEFAULT NULL COMMENT '简介',
  `thumb` varchar(255) DEFAULT NULL COMMENT '展示图',
  `user_id` int(11) DEFAULT NULL COMMENT '用户编号',
  `hit` int(10) unsigned DEFAULT '0' COMMENT '阅读数',
  `time` datetime DEFAULT NULL COMMENT '时间',
  `is_show` tinyint(4) DEFAULT '1' COMMENT '显示状态|1=显示,0=隐藏',
  `comment_num` int(10) unsigned DEFAULT '0' COMMENT '评论数',
  `content` text COMMENT '内容',
  `img` text COMMENT '组图',
   `sort` tinyint(4) DEFAULT '99' COMMENT '排序',
  `catid` int(11) DEFAULT NULL COMMENT '栏目',
  `is_recommond` tinyint(4) NOT NULL DEFAULT '0' COMMENT '推荐',
  `zan_num` int(11) NOT NULL DEFAULT '0' COMMENT '点赞数量',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='@表注释@' AUTO_INCREMENT=1 ;
</pre>


<p>收支</p>
<pre>
CREATE TABLE IF NOT EXISTS `@表名@` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '系统编号',
  `user_id` int(11) NOT NULL COMMENT '用户编号',
  `type` tinyint(4) DEFAULT '1' COMMENT '类型|1=收入,2=减少',
  `total` float(10,2) DEFAULT NULL COMMENT '额度',
  `ordernum` varchar(55) DEFAULT NULL COMMENT '单号',
  `msg` varchar(100) DEFAULT NULL COMMENT '备注',
  `time` datetime DEFAULT NULL COMMENT '时间',
  `cate` tinyint(4) DEFAULT '0' COMMENT '分类',
  `admin_id` int(11) DEFAULT NULL COMMENT '操作管理员',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='@表注释@' AUTO_INCREMENT=1 ;
</pre>


<p>站内信</p>
<pre>
CREATE TABLE IF NOT EXISTS `@表名@` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '编号',
  `user_id` int(11) DEFAULT NULL COMMENT '用户',
  `create_time` datetime DEFAULT NULL COMMENT '时间',
  `is_read` tinyint(4) DEFAULT '0' COMMENT '阅读状态|1=已读,0=未读',
  `title` varchar(255) DEFAULT NULL COMMENT '标题',
  `content` varchar(1500) DEFAULT NULL COMMENT '内容',
  `read_time` datetime DEFAULT NULL COMMENT '阅读时间',
  `is_show` tinyint(4) DEFAULT '1' COMMENT '是否显示|1=显示,0=隐藏',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COMMENT='@表注释@'
</pre>
