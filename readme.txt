CREATE TABLE `book` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '����id',
  `title` varchar(255) DEFAULT NULL COMMENT '����',
  `uuid` char(50) NOT NULL DEFAULT '' COMMENT 'Ψһid',
  `url` varchar(500) DEFAULT NULL COMMENT '���ӵ�ַ',
  `author` varchar(255) DEFAULT NULL COMMENT '����',
  `profiles` varchar(1000) DEFAULT NULL COMMENT '�������',
  `type_id` tinyint(1) DEFAULT '2' COMMENT '1��ȫ����2������',
  `update_time` timestamp NULL DEFAULT NULL COMMENT '�½ڸ���ʱ��',
  `update_fild` varchar(1000) DEFAULT NULL COMMENT '�����½�',
  `create_time` timestamp NULL DEFAULT NULL COMMENT '���ʱ��',
  `book_type` varchar(50) DEFAULT NULL COMMENT '��������',
  `book_cover` varchar(300) DEFAULT NULL COMMENT '����',
  `curl_status` tinyint(1) DEFAULT '2' COMMENT '2��δץȡ��1����ץȡ',
  `font_size` int(10) DEFAULT '0' COMMENT '����',
  `vip_status` tinyint(1) DEFAULT '1' COMMENT '1����ѣ�2���շ�',
  `recom_num` int(10) DEFAULT '0' COMMENT '�Ƽ���Ʊ',
  `status` tinyint(1) DEFAULT '1' COMMENT '1�����ã�2����ͣ',
  `read_num` int(10) DEFAULT '0' COMMENT '�Ķ���',
  PRIMARY KEY (`id`,`uuid`)
) ENGINE=MyISAM AUTO_INCREMENT=3524 DEFAULT CHARSET=utf8mb4 COMMENT='������';

CREATE TABLE `book_chapter` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '����id',
  `uuid` varchar(50) NOT NULL DEFAULT '' COMMENT 'Ψһid',
  `title` varchar(300) DEFAULT NULL COMMENT '�½�����',
  `book_uuid` varchar(50) DEFAULT NULL COMMENT '������',
  `create_time` timestamp NULL DEFAULT CURRENT_TIMESTAMP COMMENT '���ʱ��',
  `content` text COMMENT '�½�����',
  `reading_num` int(10) DEFAULT '0' COMMENT '�Ķ���',
  `vip_num` int(10) DEFAULT '0' COMMENT '��ȡ����',
  `vip_status` tinyint(1) DEFAULT '1' COMMENT '1����ѣ�2���շ�',
  `url` varchar(500) DEFAULT NULL COMMENT '���ӵ�ַ',
  PRIMARY KEY (`id`,`uuid`),
  KEY `id` (`book_uuid`) USING HASH,
  KEY `uuid` (`uuid`)
) ENGINE=MyISAM AUTO_INCREMENT=3904189 DEFAULT CHARSET=utf8mb4 COMMENT='���ݱ�';

