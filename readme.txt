CREATE TABLE `book` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '自增id',
  `title` varchar(255) DEFAULT NULL COMMENT '书名',
  `uuid` char(50) NOT NULL DEFAULT '' COMMENT '唯一id',
  `url` varchar(500) DEFAULT NULL COMMENT '链接地址',
  `author` varchar(255) DEFAULT NULL COMMENT '作者',
  `profiles` varchar(1000) DEFAULT NULL COMMENT '书名简介',
  `type_id` tinyint(1) DEFAULT '2' COMMENT '1：全本；2：连载',
  `update_time` timestamp NULL DEFAULT NULL COMMENT '章节更新时间',
  `update_fild` varchar(1000) DEFAULT NULL COMMENT '最新章节',
  `create_time` timestamp NULL DEFAULT NULL COMMENT '添加时间',
  `book_type` varchar(50) DEFAULT NULL COMMENT '所属分类',
  `book_cover` varchar(300) DEFAULT NULL COMMENT '封面',
  `curl_status` tinyint(1) DEFAULT '2' COMMENT '2：未抓取；1：已抓取',
  `font_size` int(10) DEFAULT '0' COMMENT '字数',
  `vip_status` tinyint(1) DEFAULT '1' COMMENT '1：免费；2：收费',
  `recom_num` int(10) DEFAULT '0' COMMENT '推荐得票',
  `status` tinyint(1) DEFAULT '1' COMMENT '1：启用；2：暂停',
  `read_num` int(10) DEFAULT '0' COMMENT '阅读量',
  PRIMARY KEY (`id`,`uuid`)
) ENGINE=MyISAM AUTO_INCREMENT=3524 DEFAULT CHARSET=utf8mb4 COMMENT='书名表';

CREATE TABLE `book_chapter` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '自增id',
  `uuid` varchar(50) NOT NULL DEFAULT '' COMMENT '唯一id',
  `title` varchar(300) DEFAULT NULL COMMENT '章节名称',
  `book_uuid` varchar(50) DEFAULT NULL COMMENT '绑定书名',
  `create_time` timestamp NULL DEFAULT CURRENT_TIMESTAMP COMMENT '添加时间',
  `content` text COMMENT '章节内容',
  `reading_num` int(10) DEFAULT '0' COMMENT '阅读量',
  `vip_num` int(10) DEFAULT '0' COMMENT '收取费用',
  `vip_status` tinyint(1) DEFAULT '1' COMMENT '1：免费；2：收费',
  `url` varchar(500) DEFAULT NULL COMMENT '链接地址',
  PRIMARY KEY (`id`,`uuid`),
  KEY `id` (`book_uuid`) USING HASH,
  KEY `uuid` (`uuid`)
) ENGINE=MyISAM AUTO_INCREMENT=3904189 DEFAULT CHARSET=utf8mb4 COMMENT='内容表';

