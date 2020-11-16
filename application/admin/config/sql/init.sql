/*Table structure for table `ad` */

DROP TABLE IF EXISTS `ad`;

CREATE TABLE `ad` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `ad_type` enum('image','flash','html','text') NOT NULL,
  `content` varchar(200) NOT NULL DEFAULT '',
  `width` int(11) NOT NULL DEFAULT '0',
  `height` int(11) NOT NULL DEFAULT '0',
  `enable` tinyint(1) NOT NULL DEFAULT '1',
  `category_id` int(11) NOT NULL,
  `path` varchar(100) NOT NULL DEFAULT '',
  `url` varchar(200) NOT NULL DEFAULT '',
  `sort` int(11) NOT NULL DEFAULT '0',
  `ad_text` varchar(100) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `ad_type_index` (`ad_type`),
  KEY `enable_index` (`enable`),
  KEY `category_id_index` (`category_id`)
) ENGINE=MyISAM AUTO_INCREMENT=11 DEFAULT CHARSET=utf8;

/*Data for the table `ad` */

insert  into `ad`(`id`,`ad_type`,`content`,`width`,`height`,`enable`,`category_id`,`path`,`url`,`sort`,`ad_text`) values (10,'image','',0,0,1,3,'uploads/2015/1123/20151123132612766018.jpg','',0,'');

/*Table structure for table `ad_group` */

DROP TABLE IF EXISTS `ad_group`;

CREATE TABLE `ad_group` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `group_name` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

/*Data for the table `ad_group` */

insert  into `ad_group`(`id`,`group_name`) values (3,'首页通栏广告[900x400]');

/*Table structure for table `admin` */

DROP TABLE IF EXISTS `admin`;

CREATE TABLE `admin` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `admin_group_id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(32) NOT NULL,
  `nickname` varchar(50) NOT NULL DEFAULT '',
  `real_name` varchar(50) NOT NULL DEFAULT '',
  `qq_number` varchar(20) NOT NULL DEFAULT '',
  `email` varchar(100) NOT NULL DEFAULT '',
  `add_time` int(11) NOT NULL,
  `ip` varchar(20) NOT NULL DEFAULT '',
  `ip_address` varchar(100) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `admin_group_id_index` (`admin_group_id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

/*Data for the table `admin` */

insert  into `admin`(`id`,`admin_group_id`,`username`,`password`,`nickname`,`real_name`,`qq_number`,`email`,`add_time`,`ip`,`ip_address`) values (1,1,'admin','e958e7f04472d6ff2f5def8f47fd25fb','dfasdf','sdfas','546546','sdfadf@gmail.com',1324714210,'127.0.0.1','');

/*Table structure for table `admin_group` */

DROP TABLE IF EXISTS `admin_group`;

CREATE TABLE `admin_group` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `group_name` varchar(50) NOT NULL,
  `permissions` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

/*Data for the table `admin_group` */

insert  into `admin_group`(`id`,`group_name`,`permissions`) values (1,'超级管理员','menu,menu_menuList,menu_add,menu_edit,menu_delete,menu_sort,content,test,test_index,test_add,test_edit,test_delete,test_html,test_htmlUpdate,test_htmlDelete,news,news_index,news_add,news_edit,news_delete,news_html,news_htmlUpdate,news_htmlDelete,cases,cases_index,cases_add,cases_edit,cases_delete,cases_html,cases_htmlUpdate,cases_htmlDelete,page,page_index,page_add,page_edit,page_delete,page_html,page_htmlUpdate,page_htmlDelete,video,video_index,video_add,video_edit,video_delete,video_html,video_htmlUpdate,video_htmlDelete,download,download_index,download_add,download_edit,download_delete,download_html,download_htmlUpdate,download_htmlDelete,picture,picture_index,picture_add,picture_edit,picture_delete,picture_html,picture_htmlUpdate,picture_htmlDelete,product,product_index,product_add,product_edit,product_delete,product_html,product_htmlUpdate,product_htmlDelete,link,link_index,link_add,link_edit,link_delete,job,job_index,job_add,job_edit,job_delete,guestbook,guestbook_index,guestbook_edit,guestbook_delete,sitemap,sitemap_index,team,team_index,team_add,team_edit,team_delete,team_html,team_htmlUpdate,team_htmlDelete,teacher,teacher_index,teacher_add,teacher_edit,teacher_delete,teacher_html,teacher_htmlUpdate,teacher_htmlDelete,ask,ask_index,ask_add,ask_edit,ask_delete,ask_html,ask_htmlUpdate,ask_htmlDelete,bbs,bbs_index,bbs_add,bbs_edit,bbs_delete,bbs_html,bbs_htmlUpdate,bbs_htmlDelete,user_g,user,user_index,user_add,user_edit,user_delete,usergroup,usergroup_index,usergroup_add,usergroup_edit,usergroup_delete,admin_g,admin,admin_index,admin_add,admin_edit,admin_delete,admingroup,admingroup_index,admingroup_add,admingroup_edit,admingroup_delete,html,html_index,ad_g,ad,ad_index,ad_add,ad_edit,ad_delete,ad_sort,adgroup,adgroup_index,adgroup_add,adgroup_edit,adgroup_delete,system,system_save,watermark_save,system_wf,backup,backup_index,backup_optimize,backup_repair,backup_backupDatabase,file,file_index,file_deleteFile,systemloginlog,systemloginlog_index');

/*Table structure for table `ask` */

DROP TABLE IF EXISTS `ask`;

CREATE TABLE `ask` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `category_id` int(11) NOT NULL,
  `title` varchar(100) NOT NULL,
  `custom_attribute` varchar(50) NOT NULL DEFAULT '',
  `keyword` varchar(200) NOT NULL DEFAULT '',
  `abstract` varchar(400) NOT NULL DEFAULT '',
  `hits` int(11) NOT NULL DEFAULT '0',
  `sort` int(11) NOT NULL DEFAULT '0',
  `add_time` int(11) NOT NULL,
  `display` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  KEY `category_id_index` (`category_id`),
  KEY `display_index` (`display`)
) ENGINE=MyISAM AUTO_INCREMENT=42 DEFAULT CHARSET=utf8;

/*Data for the table `ask` */

insert  into `ask`(`id`,`category_id`,`title`,`custom_attribute`,`keyword`,`abstract`,`hits`,`sort`,`add_time`,`display`) values (36,160,'特色办公室打造的几大要素特色办公室打造的几大要素特色办公室打造的几大要素特色办公室打造的几大要素','','特色 办公室 打造 的 几 大 要素 特色 办公室 打造 的 几 大 要素 特色 办公室 打造 的 几 大 要素 特色 办公室 打造 的 几 大 要素 ','特色办公室打造的几大要素特色办公室打造的几大要素特色办公室打造的几大要素特色办公室打造的几大要素特色办公室打造的几大要素特色办公室打造的几大要素',149,0,0,1),(35,160,'2015年6月9日，对于维利国德装饰有限公司的全体同仁来说，是值得纪念的一天是值得纪念的一天','','2015 年 6 月 9 日 ， 对于 维利 国 德 装饰 有限公司 的 全体 同仁 来说 ， 是 值得 纪念 的 一 天 是 值得 纪念 的 一 天 ','2015年6月9日，对于维利国德装饰有限公司的全体同仁来说，是值得纪念的一天。这一天，我们参加了“蜕变”特训营，这一天，我们真正意义上发生了“蜕变”。早上7点50分，维利国德的成员们我们参加了“蜕变”特训营，这一天，我们真正意义上发生了“蜕变”。早上7点50分',123,0,1440558242,1),(37,160,'dfasdfasdfasdf','','','asdfasdf',0,0,0,1),(38,160,'什么是免疫细胞','','什么 是 免疫 细胞 ','免疫细胞是指参与免疫应答或与免疫应答相关的细胞。包括淋巴细胞、树突状细胞、单核/巨噬细胞、粒细胞、肥大细胞等。免疫细胞可以分为多种，在人体中各种免疫细胞担任着重要的角色。',0,0,0,1),(39,160,'NK细胞为什么被誉为“人体免疫系统的第一防线”','','NK 细胞 为什么 被 誉为 “ 人体 免疫 系统 的 第一 防线 ” ','NK细胞是人体活性最强的细胞，它与我们一生相伴，承担这免疫监视、免疫防御和免疫稳定的功能。一方面抑制病毒和细菌入侵，另一方面对抗衰老细胞。它像巡警一样游弋在人体的各个角落，一旦发现不良细胞就会主动狙击。 ',0,0,0,1),(40,160,'什么年龄的NK细胞活性最好','','什么 年龄 的 NK 细胞 活性 最好 ','NK细胞活性最佳的年龄一般在8-12周岁（青春期前），此阶段的NK细胞功能以及逐步完善和成熟，先天免疫系统和后天免疫系统完成协同一致，构成了机体完备的防御机制。 ',0,0,0,1),(41,160,'NK细胞抗击病毒的原理','','NK 细胞 抗击 病毒 的 原理 ','NK细胞是人体活性最强的细胞，它与我们一生相伴，承担这免疫监视、免疫防御和免疫稳定的功能。一方面抑制病毒和细菌入侵，另一方面对抗衰老细胞。它像巡警一样游弋在人体的各个角落，一旦发现不良细胞就会主动狙击。',0,0,0,1);

/*Table structure for table `attachment` */

DROP TABLE IF EXISTS `attachment`;

CREATE TABLE `attachment` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `path` varchar(100) NOT NULL,
  `width` int(11) NOT NULL DEFAULT '0',
  `height` int(11) NOT NULL DEFAULT '0',
  `size` int(11) NOT NULL DEFAULT '0',
  `x1` int(11) NOT NULL DEFAULT '0',
  `y1` int(11) NOT NULL DEFAULT '0',
  `x2` int(11) NOT NULL DEFAULT '0',
  `y2` int(11) NOT NULL DEFAULT '0',
  `alt` varchar(100) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=214 DEFAULT CHARSET=utf8;

/*Data for the table `attachment` */

insert  into `attachment`(`id`,`path`,`width`,`height`,`size`,`x1`,`y1`,`x2`,`y2`,`alt`) values (159,'uploads/2012/0721/20120721230841281768.jpg',780,330,224,0,0,0,0,'20120721230841281768.jpg'),(160,'uploads/2012/0721/20120721230854171685.jpg',780,330,244,0,0,0,0,'20120721230854171685.jpg'),(161,'uploads/2012/0721/20120721230906420196.jpg',780,330,272,0,0,0,0,'20120721230906420196.jpg'),(162,'uploads/2015/0304/20150304145822336837.jpg',209,150,22,0,0,0,0,'20150304145822336837.jpg'),(163,'uploads/2015/0304/20150304150209349884.jpg',209,150,22,0,0,0,0,'20150304150209349884.jpg'),(164,'uploads/2015/0304/20150304170407838143.jpg',0,0,77975,0,0,0,0,''),(165,'uploads/2015/0304/20150304170433743249.jpg',0,0,77975,0,0,0,0,''),(166,'uploads/2015/0304/20150304170514281686.jpg',0,0,77975,0,0,0,0,'sdfsadf'),(167,'uploads/2015/0304/20150304171423461450.jpg',0,0,77975,0,0,0,0,'sdfasdfasdfsd'),(168,'uploads/2015/0304/20150304171637296957.jpg',0,0,77975,0,0,0,0,'dfdgsdfgd'),(169,'uploads/2015/0304/20150304171649787112.jpg',0,0,77975,0,0,0,0,'aaaaa'),(170,'uploads/2015/0304/20150304171746372653.jpg',0,0,22867,0,0,0,0,'qqqqqqq'),(171,'uploads/2015/0304/20150304171746466421.jpg',0,0,12486,0,0,0,0,'wwwwww'),(172,'uploads/2015/0304/20150304171746833969.jpg',0,0,21520,0,0,0,0,'eeeeee'),(173,'uploads/2015/0304/20150304172313455929.jpg',0,0,22867,0,0,0,0,'11111'),(174,'uploads/2015/0304/20150304172314997940.jpg',0,0,12486,0,0,0,0,'22222'),(175,'uploads/2015/0304/20150304172314730917.jpg',0,0,21520,0,0,0,0,'333333'),(176,'uploads/2015/0304/20150304173635929220.jpg',750,496,76,0,0,0,0,'20150304173635929220.jpg'),(177,'uploads/2015/0304/20150304175213464938.jpg',0,0,22867,0,0,0,0,''),(178,'uploads/2015/0304/20150304175435799114.jpg',0,0,22867,0,0,0,0,''),(179,'uploads/2015/0304/20150304175555325384.jpg',0,0,22867,0,0,0,0,''),(180,'uploads/2015/0304/20150304175742903485.jpg',0,0,12486,0,0,0,0,''),(181,'uploads/2015/0304/20150304175746220959.jpg',0,0,30023,0,0,0,0,''),(182,'uploads/2015/0304/20150304175847378915.jpg',0,0,12486,0,0,0,0,''),(183,'uploads/2015/0304/20150304175857505203.jpg',0,0,21520,0,0,0,0,''),(184,'uploads/2015/0304/20150304180030664944.jpg',0,0,19886,0,0,0,0,''),(185,'uploads/2015/0304/20150304180056397372.jpg',0,0,12486,0,0,0,0,''),(186,'uploads/2015/0304/20150304181020782992.jpg',0,0,22867,0,0,0,0,''),(187,'uploads/2015/0304/20150304181053970803.jpg',0,0,19886,0,0,0,0,''),(188,'uploads/2015/0304/20150304181110862863.jpg',0,0,22867,0,0,0,0,'1111'),(189,'uploads/2015/0304/20150304181110975967.jpg',0,0,12486,0,0,0,0,'2222'),(190,'uploads/2015/0304/20150304181110673788.jpg',0,0,21520,0,0,0,0,'333'),(191,'uploads/2015/0927/20150927221301741821.jpg',0,0,31335,0,0,0,0,''),(192,'uploads/2015/1008/20151008180713250677.png',0,0,85175,0,0,0,0,''),(193,'uploads/2015/1008/20151008180723259219.jpg',0,0,32021,0,0,0,0,'顶戴sd'),(194,'uploads/2015/1008/20151008180723340243.jpg',0,0,31335,0,0,0,0,'塔顶fas'),(195,'uploads/2015/1008/20151008180723944656.jpg',0,0,31885,0,0,0,0,'dfsdfasdf'),(196,'uploads/2015/1123/20151123132612766018.jpg',0,0,102370,0,0,0,0,''),(197,'uploads/2015/1123/20151123155351155838.png',0,0,75366,0,0,0,0,''),(198,'uploads/2015/1127/20151127233110355899.png',0,0,85175,0,0,0,0,''),(199,'uploads/2015/1127/20151127233110591610.png',0,0,64433,0,0,0,0,''),(200,'uploads/2015/1128/20151128002519142324.jpg',0,0,32021,0,0,0,0,''),(201,'uploads/2015/1128/20151128002519216317.png',0,0,85175,0,0,0,0,''),(202,'uploads/2015/1128/20151128002519982449.png',0,0,64433,0,0,0,0,''),(203,'uploads/2015/1128/20151128002716589083.jpg',0,0,32021,0,0,0,0,''),(204,'uploads/2015/1128/20151128002717354141.jpg',0,0,31335,0,0,0,0,''),(205,'uploads/2015/1128/20151128002922448376.jpg',0,0,32021,0,0,0,0,''),(206,'uploads/2015/1128/20151128002922331427.png',0,0,85175,0,0,0,0,''),(207,'uploads/2015/1128/20151128002944425689.jpg',0,0,32021,0,0,0,0,''),(208,'uploads/2015/1128/20151128002944846191.png',0,0,85175,0,0,0,0,''),(209,'uploads/2015/1128/20151128002945758245.png',0,0,64433,0,0,0,0,''),(210,'uploads/2016/0809/20160809222922998709.JPG',0,0,311445,0,0,0,0,''),(211,'uploads/2016/0809/20160809222923530416.jpg',0,0,25685,0,0,0,0,''),(212,'uploads/2016/0809/20160809223233845697.jpg',0,0,41944,0,0,0,0,''),(213,'uploads/2017/0114/20170114170726205001.jpg',0,0,23954,0,0,0,0,'');

/*Table structure for table `bbs` */

DROP TABLE IF EXISTS `bbs`;

CREATE TABLE `bbs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `category_id` int(11) NOT NULL,
  `title` varchar(100) NOT NULL,
  `seo_title` varchar(200) NOT NULL DEFAULT '',
  `title_color` varchar(10) NOT NULL DEFAULT '',
  `custom_attribute` varchar(50) NOT NULL DEFAULT '',
  `keyword` varchar(200) NOT NULL DEFAULT '',
  `abstract` varchar(400) NOT NULL DEFAULT '',
  `content` text NOT NULL,
  `hits` int(11) NOT NULL DEFAULT '0' COMMENT '浏览数',
  `reply_count` int(11) NOT NULL DEFAULT '0' COMMENT '回复数',
  `sort` int(11) NOT NULL DEFAULT '0',
  `add_time` int(11) NOT NULL,
  `display` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0＝待审核；1＝审核通过；2＝审核未通过；3＝被处罚,范围[0,1,2,3]',
  `user_id` int(11) NOT NULL COMMENT '发布者用户ID',
  `last_user_id` int(11) NOT NULL COMMENT '最后回复者ID',
  `last_add_time` int(11) NOT NULL COMMENT '最后回复时间',
  `remark` varchar(140) NOT NULL DEFAULT '' COMMENT '审核备注',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=31 DEFAULT CHARSET=utf8;

/*Data for the table `bbs` */

insert  into `bbs`(`id`,`category_id`,`title`,`seo_title`,`title_color`,`custom_attribute`,`keyword`,`abstract`,`content`,`hits`,`reply_count`,`sort`,`add_time`,`display`,`user_id`,`last_user_id`,`last_add_time`,`remark`) values (25,5,'在在地顶戴苛夺在','','','','在在,','&lt;p&gt;\n 在霜夺苛夺花样百出地花样百出地花样百出地苛夺茜&lt;/p&gt;\n&lt;p&gt;\n 枯地顶戴苛夺苛夺工地工地村在地&lt;/p&gt;\n','&lt;p&gt;\n 在霜夺苛夺花样百出地花样百出地花样百出地苛夺茜&lt;/p&gt;\n&lt;p&gt;\n 枯地顶戴苛夺苛夺工地工地村在地&lt;/p&gt;\n',4,3,0,1379344819,1,1,1,1379345095,''),(23,5,'顶戴要在苛夺械夺花样百出 苛夺花样百出 ','','#008000','h','花样百出,花样百出,','<p>\r\n 厅 花样百出地花样百出地花样百出载枯载枯地</p>\r\n','&lt;p&gt;\r\n	厅 花样百出地花样百出地花样百出载枯载枯地&lt;/p&gt;\r\n',12,1,0,1377878492,1,1,1,1378038416,''),(24,5,'在顶戴苛在花样百出 花样百出地花样百出在','','','h','向泽平','<p>\r\n 花样百出地棋载顶戴苛夺苛夺工顶戴花样百出地苛夺苛夺 工顶戴工顶戴苛夺</p>\r\n','&lt;p&gt;\r\n	花样百出地棋载顶戴苛夺苛夺工顶戴花样百出地苛夺苛夺 工顶戴工顶戴苛夺&lt;/p&gt;\r\n',0,0,0,1377936616,1,1,1,1377936616,''),(22,5,'顶戴花样百出顶戴 花样百出  ','','','h','花样百出,花样百出,','&lt;p&gt;\n 枯地械夺苛夺花样百出 花样百出地花样百出 期权&lt;/p&gt;\n','&lt;p&gt;\n 枯地械夺苛夺花样百出 花样百出地花样百出 期权&lt;/p&gt;\n',2,0,0,1377878262,1,1,1,1377878262,''),(21,5,'顶戴 花样百出载顶戴工枯载枯苛','','','h','顶戴,花样百出,','&lt;p&gt;\n 枯苛在塔顶 地花样百出地地枯苛地地苛夺苛在苛夺苛夺&lt;/p&gt;\n','&lt;p&gt;\n 枯苛在塔顶 地花样百出地地枯苛地地苛夺苛在苛夺苛夺&lt;/p&gt;\n',0,0,0,1377878193,1,1,1,1377878193,''),(20,5,'顶戴 花样百出载顶戴工枯载枯苛','','','h','顶戴,花样百出,','&lt;p&gt;\n 枯苛在塔顶 地花样百出地地枯苛地地苛夺苛在苛夺苛夺&lt;/p&gt;\n','&lt;p&gt;\n 枯苛在塔顶 地花样百出地地枯苛地地苛夺苛在苛夺苛夺&lt;/p&gt;\n',0,0,0,1377878179,1,1,1,1377878179,''),(19,5,'在模压 花样百出载枯载顶戴苛夺花样百出地花样百出 载枯','','','h','模压,花样百出,花样百出,花样百出,载枯,','&lt;p&gt;\n dsfasd sd f as f asd&lt;/p&gt;\n','&lt;p&gt;\n dsfasd sd f as f asd&lt;/p&gt;\n',0,0,0,1377878062,1,1,1,1377878062,''),(18,5,'在模压 花样百出载枯载顶戴苛夺花样百出地花样百出 载枯','','','h','模压,花样百出,花样百出,花样百出,载枯,','&lt;p&gt;\n dsfasd sd f as f asd&lt;/p&gt;\n','&lt;p&gt;\n dsfasd sd f as f asd&lt;/p&gt;\n',0,0,0,1377878053,1,1,1,1377878053,''),(17,5,'顶戴要夺花样百出地花样百出地花样百出地xxx','','','h','花样百出,花样百出,花样百出,','<p>\r\n 枯载顶戴枯工在地苛夺工奇趣苛</p>\r\n','&lt;p&gt;\r\n	枯载顶戴枯工在地苛夺工奇趣苛&lt;/p&gt;\r\n',35,0,0,1377877887,1,1,1,1377877887,''),(26,5,'测试一下','','','','测试,','&lt;p&gt;\n 压顶塔顶塔顶塔顶塔顶&lt;/p&gt;\n','&lt;p&gt;\n 压顶塔顶塔顶塔顶塔顶&lt;/p&gt;\n',0,0,0,1379683632,1,1,1,1379683632,''),(27,5,'你们不在在霜霜在地顶戴要大规模大规模夺','','','','你们,在在,大规模,大规模,','&lt;p&gt;\n 顶戴工枯载顶戴工顶戴工顶戴 花样百出塔顶&lt;/p&gt;\n&lt;p&gt;\n 村苛夺工顶戴要夺苛地苛霍布斯顶戴苛&lt;/p&gt;\n','&lt;p&gt;\n 顶戴工枯载顶戴工顶戴工顶戴 花样百出塔顶&lt;/p&gt;\n&lt;p&gt;\n 村苛夺工顶戴要夺苛地苛霍布斯顶戴苛&lt;/p&gt;\n',0,0,0,1379684738,1,1,1,1379684738,''),(28,5,'ddddddddddd','bbbbb','','','','sssssssssssssss\r\ndefgsdfgsfdfdf','&lt;p&gt;sssssssssssssss&lt;/p&gt;',5,4,0,1379685561,1,1,1,1379686423,'fffff'),(29,5,'在枯地花样百出地花样百出载顶戴苛夺在枯地花样百出地花样百出载顶戴苛夺','','','','花样百出,花样百出,','<p>\r\n  发是  地械夺苛夺苛夺花样百出载顶戴苛地苛压顶</p>\r\n','&lt;p&gt;&amp;nbsp;发是&amp;nbsp; 地械夺苛夺苛夺花样百出载顶戴苛地苛压顶&lt;/p&gt;',4,0,0,1379685581,1,1,1,1379685581,'dsadfasdf');

/*Table structure for table `bbs_comment` */

DROP TABLE IF EXISTS `bbs_comment`;

CREATE TABLE `bbs_comment` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `bbs_id` int(11) NOT NULL COMMENT '文章ID',
  `user_id` int(11) NOT NULL COMMENT '用户ID',
  `content` varchar(400) NOT NULL,
  `add_time` int(11) NOT NULL,
  `display` tinyint(1) NOT NULL DEFAULT '1' COMMENT '审核',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=22 DEFAULT CHARSET=utf8 CHECKSUM=1 DELAY_KEY_WRITE=1 ROW_FORMAT=DYNAMIC;

/*Data for the table `bbs_comment` */

insert  into `bbs_comment`(`id`,`bbs_id`,`user_id`,`content`,`add_time`,`display`) values (1,29,1,'枯枯顶戴工顶戴花样百出 花样百出 ',1377955644,1),(2,29,1,'在有要载枯花样百出地花样百出载枯载顶戴花样百出载顶戴苛地茜苛夺',1377956164,1),(3,29,1,'顶戴要夺苛夺苛夺 工顶戴苛夺革工枯载枯花样百出',1377956305,1),(4,29,1,'新华网沈阳８月３１日电（记者 李斌 李铮）在第十二届全国运动会即将开幕之际，中共中央总书记、国家主席、中央军委主席习近平３１日上午在沈阳会见了参加全国群众体育先进单位和先进个人表彰会、全国体育系统先进集体和先进工作者表彰会的代表，并发表重要讲话。习近平强调，发展体育运动，增强人民体质，是我国体育工作的根本方针和任务。全民健身是全体人民增强体魄、健康生活的基础和保障，人民身体健康是全面建成小康社会的重要内涵，是每一个人成长和实现幸福生活的重要基础。我们要广泛开展全民健身运动，促进群众体育和竞技体育全面发展。各级党委和政府要高度重视体育工作，把体育工作放在重要位置，切实抓紧抓好。',1377956518,1),(5,29,1,'新华网沈阳８月３１日电（记者 李斌 李铮）在第十二届全国运动会即将开幕之际，中共中央总书记、国家主席、中央军委主席习近平３１日上午在沈阳会见了参加全国群众体育先进单位和先进个人表彰会、',1377956527,1),(6,29,1,'全民健身是全体人民增强体魄、健康生活的基础和保障，人民身体健康是全面建成小康社会的重要内涵，是每一个人成长和实现幸福生活的重要基础。我们要广泛开展全民健身运动，促进群众体育和竞技体育全面发展。各级党委和政府要高度重视体育工作，把体育工作放在重要位置，切实抓紧抓好。',1377956534,1),(7,29,1,'新华网沈阳８月３１日电（记者 李斌 李铮）在第十二届全国运动会即将开幕之际，中共中央总书记、国家主席、中央军委主席习近平３１日上午在沈阳会见了参加全国群众体育先进单位和先进个人表彰会、全国体育系统先进集体和先进工作者表彰会的代表，并发表重要讲话。习近平强调，发展体育运动，增强人民体质，是我国体育工作的根本方针和任务。全民健身是全体人民增强体魄、健康生活的基础和保障，人民身体健康是全面建成小康社会的重要内涵，是每一个人成长和实现幸福生活的重要基础。我们要广泛开展全民健身运动，促进群众体育和竞技体育全面发展。各级党委和政府要高度重视体育工作，把体育工作放在重要位置，切实抓紧抓好。',1377956546,1),(8,29,1,'新华网沈阳８月３１日电（记者 李斌 李铮）在第十二届全国运动会即将开幕之际，中共中央总书记、国家主席、中央军委主席习近平３１日上午在沈阳会见了参加全国群众体育先进单位和先进个人表彰会、',1377956553,1),(9,29,1,'全国体育系统先进集体和先进工作者表彰会的代表，并发表重要讲话。',1377956719,1),(10,29,1,'我们要广泛开展全民健身运动，促进群众体育和竞技体育全面发展。各级党委和政府要高度重视体育工作，把体育工作放在重要位置，切实抓紧抓好。',1377956740,1),(11,29,1,'伙胡 是果蚵唱机呀',1377963959,1),(12,29,1,'efdsdf',1378017381,1),(13,29,1,'顶戴 枯工枯栽夺工顶戴工顶戴苛地花样百出地',1378038240,1),(14,29,1,'你还发了吗',1378038416,1),(15,29,1,'枯栽夺苛夺苯酚夺工顶戴苛地要夺苛夺工顶戴苛夺工桔柑地\n地 夺苛在载顶戴苛夺械夺械夺',1379344869,1),(16,29,1,'在 地地地寺 地寺地地 地地地地地地  地地地寺\n要要 在在在在在在 在',1379345062,1),(17,29,1,'在要要 要要要要要要林要要要\n在在在 在在 在大 在在在在在在在在 在',1379345095,1),(19,29,1,'sdfadfas',1379686410,1),(20,29,1,'sdfasdfasdf',1379686415,1),(21,29,1,'sdfasdasdfgdsf',1379686423,1);

/*Table structure for table `cases` */

DROP TABLE IF EXISTS `cases`;

CREATE TABLE `cases` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `category_id` int(11) NOT NULL,
  `title` varchar(100) NOT NULL DEFAULT '',
  `seo_title` varchar(200) NOT NULL DEFAULT '',
  `title_color` varchar(10) NOT NULL DEFAULT '',
  `custom_attribute` varchar(50) NOT NULL DEFAULT '',
  `author` varchar(20) NOT NULL DEFAULT '',
  `source` varchar(100) NOT NULL DEFAULT '',
  `keyword` varchar(200) NOT NULL DEFAULT '',
  `abstract` varchar(400) NOT NULL DEFAULT '',
  `content` text NOT NULL,
  `hits` int(11) NOT NULL DEFAULT '0',
  `sort` int(11) NOT NULL DEFAULT '0',
  `add_time` int(11) NOT NULL,
  `path` varchar(100) NOT NULL DEFAULT '',
  `batch_path_ids` varchar(200) NOT NULL DEFAULT '',
  `url` varchar(100) NOT NULL DEFAULT '',
  `display` tinyint(1) NOT NULL DEFAULT '1',
  `relation` varchar(200) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `category_id_index` (`category_id`),
  KEY `display_index` (`display`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;

/*Data for the table `cases` */

insert  into `cases`(`id`,`category_id`,`title`,`seo_title`,`title_color`,`custom_attribute`,`author`,`source`,`keyword`,`abstract`,`content`,`hits`,`sort`,`add_time`,`path`,`batch_path_ids`,`url`,`display`,`relation`) values (3,148,'ddffdgsdfgf','','','','王先生&李小姐','互联网','ddffdgsdfgf sdfg','fdsdfhsfdhsdfh','&lt;p&gt;\r\n	dfhdsfhsfhsfdh&lt;/p&gt;\r\n',114,0,1342798132,'uploads/2012/0720/20120720232901762997.jpg','','fdhfdshfd',1,'1,2,3'),(4,148,'多图示例','','','','dsfsadf','sdfsadf','多 图 示例 ','sdfasdasd','&lt;p&gt;\r\n	sdasdfasdf&lt;/p&gt;',75,0,1342861190,'uploads/2015/1008/20151008180713250677.png','210_211_','http://www.baidu.com',1,'1,2,3,4');

/*Table structure for table `ci_sessions` */

DROP TABLE IF EXISTS `ci_sessions`;

CREATE TABLE `ci_sessions` (
  `id` varchar(128) NOT NULL,
  `ip_address` varchar(45) NOT NULL,
  `timestamp` int(10) unsigned NOT NULL DEFAULT '0',
  `data` blob NOT NULL,
  KEY `ci_sessions_timestamp` (`timestamp`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

/*Data for the table `ci_sessions` */

insert  into `ci_sessions`(`id`,`ip_address`,`timestamp`,`data`) values ('om25q3gucui6p6ts4mrqdpovam7d22h7','127.0.0.1',1501564706,'__ci_last_regenerate|i:1501564682;menuRefUrl|s:68:\"http://my.model.com/51daima_jianzhan_com_3/admincp.php/menu/menuList\";newsRefUrl|s:59:\"http://my.model.com/51daima_jianzhan_com_3/admincp.php/news\";pageRefUrl|s:59:\"http://my.model.com/51daima_jianzhan_com_3/admincp.php/page\";');

/*Table structure for table `download` */

DROP TABLE IF EXISTS `download`;

CREATE TABLE `download` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `category_id` int(11) NOT NULL,
  `title` varchar(100) NOT NULL,
  `seo_title` varchar(200) NOT NULL DEFAULT '',
  `title_color` char(10) NOT NULL DEFAULT '',
  `source` varchar(100) NOT NULL DEFAULT '',
  `author` varchar(12) NOT NULL DEFAULT '',
  `keyword` varchar(100) NOT NULL DEFAULT '',
  `abstract` varchar(400) NOT NULL DEFAULT '',
  `content` text NOT NULL,
  `custom_attribute` varchar(50) NOT NULL DEFAULT '',
  `hits` int(11) NOT NULL DEFAULT '0',
  `sort` int(11) NOT NULL DEFAULT '0',
  `add_time` int(11) NOT NULL,
  `path` varchar(100) NOT NULL DEFAULT '',
  `file_path` varchar(100) NOT NULL DEFAULT '',
  `display` tinyint(1) NOT NULL DEFAULT '1',
  `soft_size` varchar(20) NOT NULL DEFAULT '',
  `unit` varchar(20) NOT NULL DEFAULT '',
  `file_type` varchar(200) NOT NULL DEFAULT '',
  `language` varchar(200) NOT NULL DEFAULT '',
  `license` varchar(30) NOT NULL DEFAULT '',
  `platform` text NOT NULL,
  `mark` int(1) NOT NULL DEFAULT '0',
  `download_time` int(11) NOT NULL DEFAULT '0',
  `batch_path_ids` text NOT NULL,
  `relation` varchar(200) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `category_id_index` (`category_id`),
  KEY `display_index` (`display`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

/*Data for the table `download` */

insert  into `download`(`id`,`category_id`,`title`,`seo_title`,`title_color`,`source`,`author`,`keyword`,`abstract`,`content`,`custom_attribute`,`hits`,`sort`,`add_time`,`path`,`file_path`,`display`,`soft_size`,`unit`,`file_type`,`language`,`license`,`platform`,`mark`,`download_time`,`batch_path_ids`,`relation`) values (1,139,'sddfgsdfg','','','','','sddfgsdfg ','ddfgdsfdfg','&lt;p&gt;\r\n	dfgsdfg&lt;/p&gt;\r\n','',21,0,1342794046,'uploads/2012/0720/20120720222057945233.jpg','',1,'2323','KB','.apk,.ipa','简体中文','共享软件','Android 1.5及以上,iPhone4.0及以上,WP7',5,0,'136_137_','1,2,3,4'),(2,139,'erterwerer','','','互联网','王先生&李小姐','erterwerer ','sdfasdasdfsafs','&lt;p&gt;\r\n	sgadsgadadgfd&lt;/p&gt;','',19,0,1342860684,'uploads/2012/0721/20120721165133840039.jpg','',1,'3244','KB','.apk,.ipa','简体中文','共享软件','Android 1.5及以上,iPhone4.0及以上,WP7',5,0,'150_151_','14,15,16,17');

/*Table structure for table `download_attachment` */

DROP TABLE IF EXISTS `download_attachment`;

CREATE TABLE `download_attachment` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `server_name` varchar(100) NOT NULL,
  `path` varchar(200) NOT NULL DEFAULT '',
  `download_id` int(11) NOT NULL DEFAULT '0',
  `size` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `download_id_index` (`download_id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 CHECKSUM=1 DELAY_KEY_WRITE=1 ROW_FORMAT=DYNAMIC;

/*Data for the table `download_attachment` */

insert  into `download_attachment`(`id`,`server_name`,`path`,`download_id`,`size`) values (2,'reretrt','uploads/2012/0720/20120720222119449942.zip',1,0);

/*Table structure for table `guestbook` */

DROP TABLE IF EXISTS `guestbook`;

CREATE TABLE `guestbook` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `contact_name` varchar(10) NOT NULL,
  `phone` varchar(15) DEFAULT NULL,
  `mobile` varchar(15) DEFAULT NULL,
  `qq` varchar(15) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `content` varchar(800) NOT NULL,
  `status` enum('0','1','2') NOT NULL DEFAULT '0',
  `add_time` int(11) NOT NULL DEFAULT '0',
  `reply_time` int(11) DEFAULT '0',
  `remark` varchar(400) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=12 DEFAULT CHARSET=utf8;

/*Data for the table `guestbook` */

insert  into `guestbook`(`id`,`contact_name`,`phone`,`mobile`,`qq`,`email`,`content`,`status`,`add_time`,`reply_time`,`remark`) values (9,'测试','0571-1231332','13134567890','23423423','324234@qq.com','测试','1',1342793028,1448246405,'ertwertwe'),(10,'','','','','','','0',1448255479,0,NULL),(11,'','','','','','','0',1448255644,0,NULL);

/*Table structure for table `job` */

DROP TABLE IF EXISTS `job`;

CREATE TABLE `job` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `category_id` int(11) NOT NULL,
  `title` varchar(100) NOT NULL,
  `title_color` varchar(10) NOT NULL DEFAULT '',
  `custom_attribute` varchar(50) NOT NULL DEFAULT '',
  `author` varchar(20) NOT NULL DEFAULT '',
  `source` varchar(100) NOT NULL DEFAULT '',
  `keyword` varchar(200) NOT NULL DEFAULT '',
  `abstract` varchar(400) NOT NULL DEFAULT '',
  `content` text NOT NULL,
  `hits` int(11) NOT NULL DEFAULT '0',
  `sort` int(11) NOT NULL DEFAULT '0',
  `add_time` int(11) NOT NULL,
  `display` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  KEY `category_id_index` (`category_id`),
  KEY `display_index` (`display`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

/*Data for the table `job` */

insert  into `job`(`id`,`category_id`,`title`,`title_color`,`custom_attribute`,`author`,`source`,`keyword`,`abstract`,`content`,`hits`,`sort`,`add_time`,`display`) values (3,145,'dfdfgsdfg','','','dfgsdf','dfgsdf','dfdfgsdfg ','sdfgsdfg','&lt;p&gt;\r\n	dfgsdfgsdf&lt;/p&gt;\r\n',79,0,1342873882,1);

/*Table structure for table `link` */

DROP TABLE IF EXISTS `link`;

CREATE TABLE `link` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `site_name` varchar(50) NOT NULL,
  `url` varchar(100) DEFAULT NULL,
  `sort` int(11) NOT NULL DEFAULT '0',
  `link_type` enum('logo','text') NOT NULL,
  `description` varchar(200) DEFAULT NULL,
  `qq` varchar(15) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `category_id` int(11) NOT NULL,
  `path` varchar(100) DEFAULT NULL,
  `display` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  KEY `link_type_index` (`link_type`),
  KEY `category_id_index` (`category_id`),
  KEY `display_index` (`display`)
) ENGINE=MyISAM AUTO_INCREMENT=9 DEFAULT CHARSET=utf8;

/*Data for the table `link` */

insert  into `link`(`id`,`site_name`,`url`,`sort`,`link_type`,`description`,`qq`,`email`,`category_id`,`path`,`display`) values (7,'fffffffff','fffffffff',0,'logo','ffffff','','',144,'',1),(8,'sdfsdfsd','[removed]void(0);',0,'logo','sdfsdf','','',144,'',1);

/*Table structure for table `menu` */

DROP TABLE IF EXISTS `menu`;

CREATE TABLE `menu` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `parent` int(11) NOT NULL DEFAULT '0',
  `menu_name` varchar(20) NOT NULL DEFAULT '',
  `en_menu_name` varchar(50) NOT NULL DEFAULT '',
  `seo_menu_name` varchar(200) NOT NULL DEFAULT '',
  `hide` tinyint(1) NOT NULL,
  `position` varchar(100) NOT NULL DEFAULT 'navigation',
  `menu_type` enum('0','1','2','3') NOT NULL DEFAULT '0',
  `url` varchar(100) NOT NULL DEFAULT '',
  `sort` int(11) NOT NULL DEFAULT '0',
  `model` varchar(50) NOT NULL,
  `keyword` varchar(200) NOT NULL,
  `abstract` varchar(400) NOT NULL,
  `content` text,
  `html_path` varchar(100) NOT NULL DEFAULT '',
  `template` varchar(30) NOT NULL DEFAULT '',
  `cover_function` varchar(50) NOT NULL DEFAULT '',
  `list_function` varchar(50) NOT NULL DEFAULT 'index',
  `detail_function` varchar(50) NOT NULL DEFAULT 'detail',
  PRIMARY KEY (`id`),
  KEY `parent_index` (`parent`),
  KEY `hide_index` (`hide`),
  KEY `position_index` (`position`),
  KEY `menu_type_index` (`menu_type`)
) ENGINE=MyISAM AUTO_INCREMENT=164 DEFAULT CHARSET=utf8;

/*Data for the table `menu` */

insert  into `menu`(`id`,`parent`,`menu_name`,`en_menu_name`,`seo_menu_name`,`hide`,`position`,`menu_type`,`url`,`sort`,`model`,`keyword`,`abstract`,`content`,`html_path`,`template`,`cover_function`,`list_function`,`detail_function`) values (146,0,'客户留言','','',0,'navigation','0','',9,'guestbook','','','','html/kehuliuyan','guestbook','cover','index','detail'),(145,0,'人才招聘','','',0,'footer','0','',13,'job','','','','html/rencaizhaopin','job','cover','index','detail'),(142,0,'图库管理','','',0,'navigation','0','',5,'picture','','','','html/tukuguanli','picture','cover','index','detail'),(143,0,'关于我们','','',0,'navigation','0','',3,'page','','','&lt;p&gt;sdfasdfsdfasdfs&lt;/p&gt;','html/guanyuwomen','page','cover','index','detail'),(144,0,'友情链接','','',0,'footer','0','',6,'link','','','','html/youqinglianjie','link','cover','index','detail'),(141,0,'视频管理','','',0,'footer','0','',11,'video','','','','html/shipinguanli','video','cover','index','detail'),(140,0,'产品管理','','',0,'navigation','0','',2,'product','','','','html/chanpinguanli','product','cover','index','detail'),(136,0,'优秀导师','','',0,'navigation','0','',7,'teacher','','','','html/youxiudaoshi','teacher','cover','index','detail'),(137,0,'优秀团队','','',0,'navigation','0','',8,'team','','','','html/youxiutuandui','team','cover','index','detail'),(138,0,'新闻资讯','','fgdfgdfg',0,'navigation','1','',1,'news','dfgd','dfgdg','','html/xinwenzixun','news','cover','index','detail'),(139,0,'软件下载','','',0,'footer','0','',10,'download','','','','html/ruanjianxiazai','download','cover','index','detail'),(147,0,'网站地图','','',0,'footer','0','',12,'sitemap','','','','html/wangzhanditu','sitemap','cover','index','detail'),(148,0,'成功案例','','',0,'navigation','0','',4,'cases','','','','html/chenggonganli','cases','cover','index','detail'),(149,136,'fffffffff','','',0,'navigation','0','',0,'teacher','','','','html/youxiudaoshi','teacher','cover','index','detail'),(150,138,'行业新闻','','',0,'navigation','0','',1,'news','','','','html/xinwenzixun','news','cover','index','detail'),(151,138,'公司新闻','','',0,'navigation','0','',2,'news','','','','html/xinwenzixun','news','cover','index','detail'),(160,0,'常见问题','','',0,'navigation','0','',14,'ask','','','','html/changjianwenti','ask','cover','index','detail'),(161,0,'论坛','','',0,'navigation','0','',15,'bbs','','','','html/luntan','bbs','cover','index','detail');

/*Table structure for table `news` */

DROP TABLE IF EXISTS `news`;

CREATE TABLE `news` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `category_id` int(11) NOT NULL,
  `title` varchar(100) NOT NULL,
  `seo_title` varchar(200) NOT NULL DEFAULT '',
  `title_color` varchar(10) NOT NULL DEFAULT '',
  `custom_attribute` varchar(50) NOT NULL DEFAULT '',
  `author` varchar(20) NOT NULL DEFAULT '',
  `source` varchar(100) NOT NULL DEFAULT '',
  `keyword` varchar(200) NOT NULL DEFAULT '',
  `abstract` varchar(400) NOT NULL DEFAULT '',
  `content` text NOT NULL,
  `hits` int(11) NOT NULL DEFAULT '0',
  `sort` int(11) NOT NULL DEFAULT '0',
  `add_time` int(11) NOT NULL,
  `path` varchar(100) NOT NULL DEFAULT '',
  `display` tinyint(1) NOT NULL DEFAULT '1',
  `relation` varchar(200) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `category_id_index` (`category_id`),
  KEY `display_index` (`display`)
) ENGINE=MyISAM AUTO_INCREMENT=36 DEFAULT CHARSET=utf8;

/*Data for the table `news` */

insert  into `news`(`id`,`category_id`,`title`,`seo_title`,`title_color`,`custom_attribute`,`author`,`source`,`keyword`,`abstract`,`content`,`hits`,`sort`,`add_time`,`path`,`display`,`relation`) values (32,150,'drgsdfhsdfh','','','c','王先生&李小姐','互联网','drgsdfhsdfh ','sdfgsdfhsdfh','&lt;p&gt;\r\n	dfhsfddfghg&lt;/p&gt;\r\n',99,0,1342793028,'uploads/2012/0720/20120720220400878710.jpg',1,'32,33,34'),(33,150,'ewtertewr','','','c','etrtert','erte','ewtertewr ','erwerwertwerwertwer','&lt;p&gt;\r\n	ddrsdgsdfgsdgsd&lt;/p&gt;\r\n',95,0,1342860651,'uploads/2012/0721/20120721165100994259.jpg',1,'32,33,34'),(34,150,'sdfasfsa','','','c','王先生&李小姐','互联网','sdfasfsa ','sdfasdfsd','&lt;p&gt;\r\n	sdfasdf&lt;/p&gt;\r\n',189,0,1342869059,'',1,'32,33,34'),(35,150,'sdfasdf','','','','','','sdfasdf ','asdfasdf','',181,0,1448265219,'uploads/2015/1123/20151123155351155838.png',1,'');

/*Table structure for table `page` */

DROP TABLE IF EXISTS `page`;

CREATE TABLE `page` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `category_id` int(11) NOT NULL,
  `title` varchar(100) NOT NULL,
  `seo_title` varchar(200) NOT NULL DEFAULT '',
  `title_color` varchar(10) NOT NULL DEFAULT '',
  `keyword` varchar(200) NOT NULL DEFAULT '',
  `abstract` varchar(400) NOT NULL DEFAULT '',
  `content` text NOT NULL,
  `hits` int(11) NOT NULL DEFAULT '0',
  `add_time` int(11) NOT NULL,
  `sort` int(11) NOT NULL DEFAULT '0',
  `url` varchar(200) NOT NULL DEFAULT '',
  `display` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  KEY `category_id_index` (`category_id`),
  KEY `display_index` (`display`)
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;

/*Data for the table `page` */

insert  into `page`(`id`,`category_id`,`title`,`seo_title`,`title_color`,`keyword`,`abstract`,`content`,`hits`,`add_time`,`sort`,`url`,`display`) values (5,143,'sdfsdfasf','','','sdfsdfasf ','dsgadgadg','&lt;div class=\"web_title\"&gt;&lt;h1&gt;CONTACT&amp;nbsp;US&lt;/h1&gt;&lt;h2&gt;联系我们&lt;/h2&gt;&lt;h3&gt;您若需要一个创意型的专业机构，我们愿意提供全部的专业协助！&lt;/h3&gt;&lt;/div&gt;&lt;div class=\"pro_main_box\"&gt;&lt;div class=\"abo_main\"&gt;&lt;ul&gt;&lt;li&gt;双收网络&lt;br/&gt;全称：杭州双收网络科技有限公司&lt;br/&gt;电话：0571-87092013 (9:00-18:00）&lt;br/&gt;杭州市经济技术开发区学源街1185号&lt;br/&gt;中国&middot;杭州市&lt;/li&gt;&lt;li&gt;&lt;img src=\"images/weixin.png\"/&gt;&lt;h1&gt;扫描二维码获取我们地址&lt;/h1&gt;&lt;/li&gt;&lt;p&gt;SHUANGSHOU Network (Hangzhou) Technology\r\nHangzhou Shuangshou Network Technologies Co., Ltd.\r\nXiasha Office address：Floor 9.905-907,No.1185\r\nXueyuan Road ,Xiasha District ,Hangzhou\r\nTel：+86 0571-87092013&lt;/p&gt;&lt;div class=\"clear\"&gt;&lt;/div&gt;&lt;/ul&gt;&lt;h4&gt;公司地图位置&lt;/h4&gt;&lt;div style=\" width:800px; height:420px; border: solid #c2c2c2 1px; margin-left:200px; margin-bottom:60px;\"&gt;&lt;/div&gt;&lt;div class=\"clear\"&gt;&lt;/div&gt;&lt;/div&gt;&lt;/div&gt;',48,1470375145,0,'',1);

/*Table structure for table `pattern` */

DROP TABLE IF EXISTS `pattern`;

CREATE TABLE `pattern` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `sort` int(11) NOT NULL DEFAULT '0',
  `title` varchar(50) NOT NULL,
  `title_color` varchar(10) NOT NULL DEFAULT '',
  `alias` varchar(50) NOT NULL DEFAULT '',
  `file_name` varchar(50) NOT NULL DEFAULT '',
  `type` enum('模块','小插件') NOT NULL DEFAULT '模块',
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `description` text NOT NULL,
  `add_time` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `NewIndex1` (`file_name`),
  KEY `type_index` (`type`),
  KEY `status_index` (`status`)
) ENGINE=MyISAM AUTO_INCREMENT=28 DEFAULT CHARSET=utf8;

/*Data for the table `pattern` */

insert  into `pattern`(`id`,`sort`,`title`,`title_color`,`alias`,`file_name`,`type`,`status`,`description`,`add_time`) values (2,1,'新闻资讯','','新闻','news','模块',1,'',1319891495),(3,5,'软件下载','#ff00ff','软件','download','模块',1,'',1319908079),(4,7,'产品管理','#0000ff','产品','product','模块',1,'',1319908211),(5,4,'视频管理','#ff0000','视频','video','模块',1,'',1319908482),(6,6,'图库管理','#000000','图库','picture','模块',1,'',1319908588),(7,3,'单页面管理','#800000','单页面','page','模块',1,'',1319908745),(8,8,'友情链接','#008000','友情链接','link','模块',1,'',1319909048),(10,9,'人才招聘','#808080','职位','job','模块',1,'',1319930459),(11,10,'留言簿','#800080','留言簿','guestbook','模块',1,'',1319979054),(12,11,'网站地图','#008000','网站地图','sitemap','模块',1,'',1319995403),(13,2,'成功案例','#0000ff','案例','cases','模块',1,'',1321311859),(14,13,'优秀团队','','团队','team','模块',1,'',1324831326),(15,14,'优秀导师','','导师','teacher','模块',1,'',1324831344),(24,16,'论坛管理','#0000ff','帖子','bbs','模块',1,'',1448719553),(23,15,'常见问题','#800080','常见问题','ask','模块',1,'',1448245732);

/*Table structure for table `picture` */

DROP TABLE IF EXISTS `picture`;

CREATE TABLE `picture` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `category_id` int(11) NOT NULL,
  `title` varchar(100) NOT NULL,
  `seo_title` varchar(200) NOT NULL DEFAULT '',
  `title_color` varchar(10) NOT NULL DEFAULT '',
  `custom_attribute` varchar(50) NOT NULL DEFAULT '',
  `author` varchar(20) NOT NULL DEFAULT '',
  `source` varchar(100) NOT NULL DEFAULT '',
  `keyword` varchar(200) NOT NULL DEFAULT '',
  `abstract` varchar(400) NOT NULL DEFAULT '',
  `content` text NOT NULL,
  `hits` int(11) NOT NULL DEFAULT '0',
  `sort` int(11) NOT NULL DEFAULT '0',
  `add_time` int(11) NOT NULL,
  `path` varchar(100) NOT NULL DEFAULT '',
  `batch_path_ids` varchar(200) NOT NULL DEFAULT '',
  `display` tinyint(1) NOT NULL DEFAULT '1',
  `relation` varchar(200) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `category_id_index` (`category_id`),
  KEY `display_index` (`display`)
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;

/*Data for the table `picture` */

insert  into `picture`(`id`,`category_id`,`title`,`seo_title`,`title_color`,`custom_attribute`,`author`,`source`,`keyword`,`abstract`,`content`,`hits`,`sort`,`add_time`,`path`,`batch_path_ids`,`display`,`relation`) values (4,142,'sdfasdfasdf','','','','王先生&李小姐','互联网','sdfasdfasdf ','sdfasdfasdf','&lt;p&gt;\r\n	sdfasdfasdfs&lt;/p&gt;\r\n',100,0,1342797723,'uploads/2012/0720/20120720232217657775.jpg','140_141_',1,'1,2,3,4'),(5,142,'ffffffffffffff','','','','sdfsad','sdfsd','ffffffffffffff ','sdfasfasd','&lt;p&gt;\r\n	sadfsdfsdfsd&lt;/p&gt;',33,0,1342861024,'uploads/2012/0721/20120721165713268832.jpg','136_137_',1,'1,2,3,4');

/*Table structure for table `product` */

DROP TABLE IF EXISTS `product`;

CREATE TABLE `product` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `category_id` int(11) NOT NULL,
  `title` varchar(100) NOT NULL,
  `seo_title` varchar(200) NOT NULL DEFAULT '',
  `title_color` varchar(10) NOT NULL DEFAULT '',
  `custom_attribute` varchar(50) NOT NULL DEFAULT '',
  `keyword` varchar(200) NOT NULL DEFAULT '',
  `abstract` varchar(400) NOT NULL DEFAULT '',
  `content` text NOT NULL,
  `hits` int(11) NOT NULL DEFAULT '0',
  `sort` int(11) NOT NULL DEFAULT '0',
  `market_price` double NOT NULL DEFAULT '0',
  `favorable_price` double NOT NULL DEFAULT '0',
  `unit` varchar(20) NOT NULL DEFAULT '',
  `brand` varchar(20) NOT NULL DEFAULT '',
  `add_time` int(11) NOT NULL,
  `path` varchar(100) NOT NULL DEFAULT '',
  `display` tinyint(1) NOT NULL DEFAULT '1',
  `relation` varchar(200) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `category_id_index` (`category_id`),
  KEY `display_index` (`display`)
) ENGINE=MyISAM AUTO_INCREMENT=9 DEFAULT CHARSET=utf8;

/*Data for the table `product` */

insert  into `product`(`id`,`category_id`,`title`,`seo_title`,`title_color`,`custom_attribute`,`keyword`,`abstract`,`content`,`hits`,`sort`,`market_price`,`favorable_price`,`unit`,`brand`,`add_time`,`path`,`display`,`relation`) values (6,140,'sdfasdfasd','','','c','sdfasdfasd ','sdfasdf','&lt;p&gt;\r\n	sdfasdfddfsdsdfhsdfh&lt;/p&gt;\r\n',69,0,232,22323,'件','dsfsdgsfg',1342795651,'uploads/2012/0721/20120721194224318435.jpg',1,'6,7,8'),(7,140,'rffffffffffffffffff','','','c','rffffffffffffffffff ','sdasdasdasf','&lt;p&gt;\r\n	sdfasdfasfasdfsd&lt;/p&gt;\r\n',192,0,2323,2323,'件','3324234',1342860758,'uploads/2012/0721/20120721165248769149.jpg',1,'6,7,8'),(8,140,'sdfasdfasd','','','c','sdfasdfasd ','sdasdg','&lt;p&gt;\r\n	sgasdadfafdgfd&lt;img alt=\"\" src=\"/uploads/images/43fe3eb95bddc5e8a22cfd88c8b1313c.jpg\" style=\"width: 200px; height: 300px;\"/&gt;&lt;/p&gt;',50,0,323,222,'件','sdfasdf',1342860888,'uploads/2012/0721/20120721165456827487.jpg',1,'6,7,8');

/*Table structure for table `system` */

DROP TABLE IF EXISTS `system`;

CREATE TABLE `system` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `index_name` varchar(50) NOT NULL DEFAULT '',
  `client_index` varchar(100) NOT NULL DEFAULT '',
  `site_name` varchar(200) NOT NULL DEFAULT '',
  `index_site_name` varchar(200) NOT NULL DEFAULT '',
  `site_copyright` varchar(800) NOT NULL DEFAULT '',
  `site_keycode` varchar(400) NOT NULL DEFAULT '',
  `site_description` varchar(800) NOT NULL DEFAULT '',
  `icp_code` varchar(400) NOT NULL DEFAULT '',
  `cache` tinyint(1) NOT NULL DEFAULT '0',
  `cache_time` int(11) NOT NULL DEFAULT '10',
  `html` tinyint(1) NOT NULL DEFAULT '0',
  `html_folder` varchar(50) NOT NULL DEFAULT '',
  `html_level` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0=行业级，1=企业级',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

/*Data for the table `system` */

insert  into `system`(`id`,`index_name`,`client_index`,`site_name`,`index_site_name`,`site_copyright`,`site_keycode`,`site_description`,`icp_code`,`cache`,`cache_time`,`html`,`html_folder`,`html_level`) values (1,'首页','index.php','_无忧企业宝(企业版)','_无忧企业宝(企业版)cc','<br>杭州网站制作,杭州网站建设,杭州网站推广,杭州虚拟主机,独立IP美国空间,香港空间,韩国空间,\r\n<br>无忧建站,第一选择,全静态网站制作, 售后服务:0571-86759583;邮箱1633839035@qq.com','dfasdf','asdfasdfdsf','浙ICP备10010181号',0,10,1,'html',1);

/*Table structure for table `system_login_log` */

DROP TABLE IF EXISTS `system_login_log`;

CREATE TABLE `system_login_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `ip` varchar(20) NOT NULL DEFAULT '' COMMENT '登录IP',
  `address` varchar(100) DEFAULT '' COMMENT '登录地址',
  `add_time` int(11) NOT NULL COMMENT '登录时间',
  `admin_id` int(11) NOT NULL COMMENT '用户ID',
  PRIMARY KEY (`id`),
  KEY `admin_id_index` (`admin_id`)
) ENGINE=MyISAM AUTO_INCREMENT=55 DEFAULT CHARSET=utf8 CHECKSUM=1 DELAY_KEY_WRITE=1 ROW_FORMAT=DYNAMIC;

/*Data for the table `system_login_log` */

insert  into `system_login_log`(`id`,`ip`,`address`,`add_time`,`admin_id`) values (7,'127.0.0.1',NULL,1448264273,1),(6,'127.0.0.1',NULL,1448262884,1),(5,'127.0.0.1',NULL,1448262766,1),(8,'127.0.0.1',NULL,1448282516,1),(9,'127.0.0.1',NULL,1448288019,1),(10,'127.0.0.1',NULL,1448293714,1),(11,'127.0.0.1',NULL,1448293756,1),(12,'127.0.0.1',NULL,1448329676,1),(13,'127.0.0.1',NULL,1448332185,1),(14,'127.0.0.1',NULL,1448344234,1),(15,'127.0.0.1',NULL,1448347349,1),(16,'127.0.0.1',NULL,1448349643,1),(17,'127.0.0.1',NULL,1448352097,1),(18,'127.0.0.1',NULL,1448418834,1),(19,'127.0.0.1',NULL,1448421047,1),(20,'127.0.0.1',NULL,1448591660,1),(21,'127.0.0.1',NULL,1448635544,1),(22,'127.0.0.1',NULL,1448641068,1),(23,'127.0.0.1',NULL,1448718623,1),(24,'127.0.0.1',NULL,1451624907,1),(25,'127.0.0.1',NULL,1452748250,1),(26,'127.0.0.1',NULL,1453529113,1),(27,'127.0.0.1',NULL,1456576652,1),(28,'127.0.0.1',NULL,1463108700,1),(29,'127.0.0.1',NULL,1463129626,1),(30,'127.0.0.1',NULL,1463212817,1),(31,'127.0.0.1',NULL,1463216245,1),(32,'127.0.0.1',NULL,1463453464,1),(33,'127.0.0.1',NULL,1463453922,1),(34,'127.0.0.1',NULL,1464680364,1),(35,'127.0.0.1',NULL,1464680390,1),(36,'127.0.0.1',NULL,1470237020,1),(37,'127.0.0.1',NULL,1470375137,1),(38,'127.0.0.1',NULL,1470752224,1),(39,'127.0.0.1',NULL,1471168933,1),(40,'127.0.0.1',NULL,1472381019,1),(41,'127.0.0.1',NULL,1474620786,1),(42,'127.0.0.1',NULL,1476151433,1),(43,'127.0.0.1',NULL,1478160489,1),(44,'127.0.0.1',NULL,1478160681,1),(45,'127.0.0.1',NULL,1478515720,1),(46,'127.0.0.1',NULL,1484384316,1),(47,'127.0.0.1',NULL,1484384835,1),(48,'127.0.0.1',NULL,1488181725,1),(49,'127.0.0.1',NULL,1491372019,1),(50,'127.0.0.1',NULL,1491894314,1),(51,'127.0.0.1',NULL,1496305940,1),(52,'127.0.0.1',NULL,1500434601,1),(53,'127.0.0.1',NULL,1500549066,1),(54,'127.0.0.1',NULL,1501564270,1);

/*Table structure for table `teacher` */

DROP TABLE IF EXISTS `teacher`;

CREATE TABLE `teacher` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `category_id` int(11) NOT NULL,
  `title` varchar(100) NOT NULL,
  `seo_title` varchar(200) NOT NULL DEFAULT '',
  `title_color` varchar(10) NOT NULL DEFAULT '',
  `custom_attribute` varchar(50) NOT NULL DEFAULT '',
  `good_at` varchar(100) NOT NULL DEFAULT '',
  `keyword` varchar(200) NOT NULL DEFAULT '',
  `abstract` varchar(400) NOT NULL DEFAULT '',
  `content` text NOT NULL,
  `hits` int(11) NOT NULL DEFAULT '0',
  `sort` int(11) NOT NULL DEFAULT '0',
  `add_time` int(11) NOT NULL,
  `path` varchar(100) NOT NULL DEFAULT '',
  `batch_path_ids` varchar(200) NOT NULL DEFAULT '',
  `display` tinyint(1) NOT NULL DEFAULT '1',
  `relation` varchar(200) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `category_id_index` (`category_id`),
  KEY `display_index` (`display`)
) ENGINE=MyISAM AUTO_INCREMENT=12 DEFAULT CHARSET=utf8;

/*Data for the table `teacher` */

insert  into `teacher`(`id`,`category_id`,`title`,`seo_title`,`title_color`,`custom_attribute`,`good_at`,`keyword`,`abstract`,`content`,`hits`,`sort`,`add_time`,`path`,`batch_path_ids`,`display`,`relation`) values (9,149,'sdgadg','','','c','sdfgsdfgsdf','sdgadg ','sdgsdfg','&lt;p&gt;\r\n	dfgsg&lt;/p&gt;\r\n',46,0,1342792039,'','',1,'1,2,3'),(10,149,'dfgsdfg','','','c','sdfgsdfgsdf','dfgsdfg ','fdfghdfghdfg','&lt;p&gt;\r\n	fggfhdfhdgj&lt;/p&gt;\r\n',175,0,1342792230,'uploads/2012/0720/20120720215103117935.jpg','',1,'1,2,3,4'),(11,149,'ddddddddd','sdfasdfasdfasdfasdfasdfasdf','','c','dddddddd禾rrr','ddddddddd ','sdfasf','&lt;p&gt;新华网北京3月3日电&amp;nbsp; 据新华社&amp;amp;ldquo;新华视点&amp;amp;rdquo;微信报道，习总书记是党和国家领导人，也是一名全国人大代表。接下来这十几天中，视点君将近距离记录习近平代表的两会时间，原汁原味地呈现给小伙伴们。&lt;/p&gt;&lt;p&gt;&lt;img alt=\"\" src=\"/uploads/images/20130621211043374285_thumb.jpg\" style=\"height:150px; width:209px\"/&gt;&lt;/p&gt;&lt;p&gt;（2014年3月9日，十二届全国人大二次会议在北京人民大会堂举行第二次全体会议，习近平步入会场）&lt;/p&gt;&lt;p&gt;习近平代表？没错！习总书记是党和国家领导人，也是一名全国人大代表。即将拉开帷幕的年度两会，怎能少了他的身影？接下来这十几天中，习近平将与代表委员们一起审议讨论。视点君也将近距离记录习近平代表的两会时间，原汁原味地呈现给小伙伴们。&lt;/p&gt;',6,0,1342860282,'uploads/2012/0721/20120721164453846548.jpg','',1,'1,2,3,4');

/*Table structure for table `team` */

DROP TABLE IF EXISTS `team`;

CREATE TABLE `team` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `category_id` int(11) NOT NULL,
  `title` varchar(100) NOT NULL,
  `seo_title` varchar(200) NOT NULL DEFAULT '',
  `title_color` varchar(10) NOT NULL DEFAULT '',
  `custom_attribute` varchar(50) NOT NULL DEFAULT '',
  `keyword` varchar(200) NOT NULL DEFAULT '',
  `abstract` varchar(400) NOT NULL DEFAULT '',
  `content` text NOT NULL,
  `hits` int(11) NOT NULL DEFAULT '0',
  `sort` int(11) NOT NULL DEFAULT '0',
  `add_time` int(11) NOT NULL,
  `path` varchar(100) NOT NULL DEFAULT '',
  `batch_path_ids` varchar(200) NOT NULL DEFAULT '',
  `display` tinyint(1) NOT NULL DEFAULT '1',
  `relation` varchar(200) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `category_id_index` (`category_id`),
  KEY `display_index` (`display`)
) ENGINE=MyISAM AUTO_INCREMENT=9 DEFAULT CHARSET=utf8;

/*Data for the table `team` */

insert  into `team`(`id`,`category_id`,`title`,`seo_title`,`title_color`,`custom_attribute`,`keyword`,`abstract`,`content`,`hits`,`sort`,`add_time`,`path`,`batch_path_ids`,`display`,`relation`) values (7,137,'dgsdfgsdfgsfdhdsf','','','','dgsdfgsdfgsfdhdsf ','fdgsfhsfdh','&lt;p&gt;\r\n	fdhdfghdfhdfgh&lt;/p&gt;\r\n',87,0,1342792714,'uploads/2012/0720/20120720215845995770.jpg','130_131_',1,'1,2,3'),(8,137,'fffffffffff','','','','fffffffffff ','dfgsdfsdffh','&lt;p&gt;\r\n	fdhshsdfhsdfsdfsd&lt;/p&gt;',125,0,1342860411,'uploads/2012/0721/20120721164732524456.jpg','146_147_',1,'1,2,3,4');

/*Table structure for table `user` */

DROP TABLE IF EXISTS `user`;

CREATE TABLE `user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(100) NOT NULL COMMENT '用户名',
  `password` varchar(32) NOT NULL COMMENT '登录密码',
  `safe_password` varchar(32) NOT NULL COMMENT '安全密码',
  `real_name` varchar(50) NOT NULL DEFAULT '',
  `sex` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0为未知,1为女,2为男',
  `qq` varchar(20) NOT NULL DEFAULT '',
  `mobile` varchar(20) NOT NULL DEFAULT '',
  `email` varchar(100) NOT NULL DEFAULT '',
  `add_time` int(11) NOT NULL,
  `score` int(11) NOT NULL DEFAULT '0' COMMENT '积分余额',
  `total` int(11) NOT NULL DEFAULT '0' COMMENT '账户余额',
  `display` tinyint(1) NOT NULL DEFAULT '0',
  `login_time` int(11) NOT NULL DEFAULT '0',
  `ip_address` varchar(20) NOT NULL DEFAULT '',
  `type` tinyint(1) NOT NULL DEFAULT '0' COMMENT '会员类型',
  `path` varchar(100) NOT NULL DEFAULT '' COMMENT '头像',
  `referee` varchar(100) NOT NULL DEFAULT '' COMMENT '推荐人',
  `login_times` int(11) NOT NULL DEFAULT '0' COMMENT '总登录次数',
  `is_safe_user` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否加入商保，0＝没有加入，1＝已加入商保',
  PRIMARY KEY (`id`),
  KEY `display_index` (`display`),
  KEY `type_index` (`type`)
) ENGINE=MyISAM AUTO_INCREMENT=12774 DEFAULT CHARSET=utf8;

/*Data for the table `user` */

insert  into `user`(`id`,`username`,`password`,`safe_password`,`real_name`,`sex`,`qq`,`mobile`,`email`,`add_time`,`score`,`total`,`display`,`login_time`,`ip_address`,`type`,`path`,`referee`,`login_times`,`is_safe_user`) values (12753,'test','e8fd235eeb427f0de2a24555830cb886','','向泽平',0,'4624356346','手机 ','邮件',1425910063,0,0,1,1425910267,'',2,'uploads/2015/0309/20150309220727485977.jpg','',0,0),(12751,'tw_jiangnan','5d8cb6f3ef2ff54e6c53d8692228801a','','童玮',0,'2342343','18626885390','tw_jiangnan@163.com',1423894826,1699,0,1,1426235793,'127.0.0.1',2,'uploads/2015/0214/20150214153155669833.jpg','',6,0),(12752,'tb9137598_2012','6a8c32ea66005ac0588921f647f67ec1','','王海燕',0,'123456','18699137598','',1425910106,1208,0,1,1428080034,'127.0.0.1',2,'uploads/2015/0309/20150309220802483889.jpg','',9,1),(12754,'13134567890','9c2ed9f5949ff4a3fdb7199b6ec06257','','浪哥禅机',0,'3453245345','','',1403437482,0,0,1,1425910723,'',0,'uploads/2015/0309/20150309221820832046.jpg','',0,0),(12755,'13134567891','0da628148b8e4b86a2237c718d8e8518','','东风一号',0,'3434534534','','',1403437537,0,0,1,1425910643,'',0,'uploads/2015/0309/20150309221702672662.jpg','',0,0),(12756,'13234567890','d873f15d2df8feb8509f79f432e7ae0c','','在要人',0,'34534535','','',1425910558,0,0,1,1425910558,'',0,'uploads/2015/0309/20150309221539154299.jpg','',0,0),(12757,'13234567896','9e36e06560e4c4d4440d7ffa7b2e7afc','','帅哥一号',0,'43535345','13134567890','',1403437831,0,0,0,1425910677,'',0,'uploads/2015/0309/20150309221735366198.jpg','',0,0),(12758,'15156782340','e0afa43c619e3c7333d54fe5ddc37b6c','','向泽平',0,'','15156782340','',1403439016,0,0,0,1403521703,'',1,'','',0,0),(12759,'15888888888','39043fae4b51e4fc5b360272be898edb','','',0,'','15888888888','',1403797051,0,0,0,0,'',1,'','',0,0),(12760,'15823459087','438343c319e88b70913db1672a1b4361','','',0,'','15823459087','',1403880923,0,0,0,0,'',0,'','',0,0),(12761,'13867892345','0792320416c224e7139d80d81499e3b0','','',0,'','13867892345','',1403881217,0,0,1,0,'',0,'','',0,0),(12762,'15812345678','5648c1884e49f94630eebacaed609d69','','',0,'','15812345678','',1404051562,0,0,0,0,'',0,'','',0,0),(12763,'15978900987','8bd7a2acdfc7270327f3550007e066fa','','',0,'','15978900987','',1404051742,0,0,0,0,'',0,'','',0,0),(12764,'13867892348','c99cbe1d488b065a3b68cbc3bffc1e3c','','',0,'','13867892348','',1404051937,0,0,0,0,'',0,'','',0,0),(12765,'15834567876','3a060e6e85c3319a8e6b881ba6af0d9d','','',1,'','15834567876','',1404053179,0,0,1,1404232870,'',0,'','',0,0),(12766,'test123456','40c37e50fcc75eabccb7172e30829321','','ffdfd',0,'34234234','','',1425909400,0,0,1,1428079772,'127.0.0.1',0,'uploads/2015/0309/20150309215630933505.png','',16,1),(12769,'Test12345','e6587fd2747667fbf2403328ecf09c8f','','东风二号',0,'23234234','13234567898','',1425968600,0,0,1,1448283763,'',0,'uploads/2015/0310/20150310142259613803.jpg','',1,0),(12770,'rt_jiangnan','ea236ca7e44a5d75f57c479e8baf8e6a','ea236ca7e44a5d75f57c479e8baf8e6a','',0,'88888','13234567898','fer@126.com',1426216195,0,0,0,0,'',0,'','',0,0),(12771,'yewstg','832e6ccb529e12974dbe153242705e2f','832e6ccb529e12974dbe153242705e2f','',0,'23234234','13234567898','fsdfsd@128.com',1426218996,0,0,0,0,'',0,'','',0,0),(12772,'test1234','a9b9d7772f6a29c204a2007191f02bb6','a9b9d7772f6a29c204a2007191f02bb6','',0,'23234234','13234567898','sdfsd@128.com',1426219992,0,0,0,1426220023,'127.0.0.1',0,'','',2,0),(12773,'sdfsd','830467e959018a5febda103f1a6434d3','830467e959018a5febda103f1a6434d3','sdfsd',0,'323423','13234567898','sdfsdf@128.com',1426237614,0,0,0,1428134223,'',0,'uploads/2015/0304/20150304181053970803.jpg','',1,0);

/*Table structure for table `user_group` */

DROP TABLE IF EXISTS `user_group`;

CREATE TABLE `user_group` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `group_name` varchar(50) NOT NULL,
  `score` int(11) NOT NULL DEFAULT '0',
  `sort` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=8 DEFAULT CHARSET=utf8;

/*Data for the table `user_group` */

insert  into `user_group`(`id`,`group_name`,`score`,`sort`) values (7,'fff',0,0);

/*Table structure for table `video` */

DROP TABLE IF EXISTS `video`;

CREATE TABLE `video` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `category_id` int(11) NOT NULL,
  `title` varchar(100) NOT NULL,
  `seo_title` varchar(200) NOT NULL DEFAULT '',
  `title_color` varchar(10) NOT NULL DEFAULT '',
  `custom_attribute` varchar(50) NOT NULL DEFAULT '',
  `author` varchar(20) NOT NULL DEFAULT '',
  `source` varchar(100) NOT NULL DEFAULT '',
  `keyword` varchar(200) NOT NULL DEFAULT '',
  `abstract` varchar(400) NOT NULL DEFAULT '',
  `content` text NOT NULL,
  `hits` int(11) NOT NULL DEFAULT '0',
  `sort` int(11) NOT NULL DEFAULT '0',
  `add_time` int(11) NOT NULL,
  `path` varchar(100) NOT NULL DEFAULT '',
  `display` tinyint(1) NOT NULL DEFAULT '1',
  `relation` varchar(200) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `category_id_index` (`category_id`),
  KEY `display_index` (`display`)
) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;

/*Data for the table `video` */

insert  into `video`(`id`,`category_id`,`title`,`seo_title`,`title_color`,`custom_attribute`,`author`,`source`,`keyword`,`abstract`,`content`,`hits`,`sort`,`add_time`,`path`,`display`,`relation`) values (5,141,'rertwreyryre','','','','王先生&李小姐','互联网','rertwreyryre ','erryretyert','&lt;p&gt;\r\n	rtyerty&lt;/p&gt;\r\n',144,0,1342796404,'erterterter',1,'5,6,7'),(6,141,'sdfasasasd','','','','sdsd','sdfsd','sdfasasasd ','sdfsadfasf','&lt;p&gt;\r\n	sfsdasfsadf&lt;/p&gt;',144,0,1342860947,'dsfsad',1,'1,2,3,4');

/*Table structure for table `watermark` */

DROP TABLE IF EXISTS `watermark`;

CREATE TABLE `watermark` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `is_open` tinyint(1) NOT NULL DEFAULT '0',
  `path` varchar(100) DEFAULT NULL,
  `location` varchar(200) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

/*Data for the table `watermark` */

insert  into `watermark`(`id`,`is_open`,`path`,`location`) values (1,0,'','bottom,right');