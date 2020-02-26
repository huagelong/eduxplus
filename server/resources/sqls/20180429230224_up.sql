DROP TABLE IF EXISTS `base_access`;

CREATE TABLE `base_access` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `role_id` int(11) DEFAULT NULL,
  `access` varchar(100) DEFAULT NULL,
  `created_at` int(11) DEFAULT NULL,
  `updated_at` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='角色权限表';



# Dump of table base_admin_action_log
# ------------------------------------------------------------

DROP TABLE IF EXISTS `base_admin_action_log`;

CREATE TABLE `base_admin_action_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(50) DEFAULT NULL,
  `route` varchar(100) DEFAULT NULL,
  `ip` varchar(20) DEFAULT NULL,
  `input_data` text,
  `created_at` int(11) DEFAULT NULL,
  `updated_at` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Dump of table base_admin_user_role
# ------------------------------------------------------------

DROP TABLE IF EXISTS `base_admin_user_role`;

CREATE TABLE `base_admin_user_role` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) DEFAULT NULL,
  `role_id` int(11) DEFAULT NULL,
  `created_at` int(11) DEFAULT NULL,
  `updated_at` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='用户权限';



# Dump of table base_backup
# ------------------------------------------------------------

DROP TABLE IF EXISTS `base_backup`;

CREATE TABLE `base_backup` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(50) DEFAULT NULL,
  `tables` text,
  `path` varchar(100) DEFAULT NULL COMMENT '备份路径',
  `status` tinyint(1) DEFAULT '0' COMMENT '0-备份失败,1-备份中，2-备份成功',
  `note` text,
  `yun_file_id` int(11) DEFAULT NULL,
  `created_at` int(11) DEFAULT NULL,
  `updated_at` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4;


INSERT INTO `base_backup` (`id`, `name`, `tables`, `path`, `status`, `note`, `yun_file_id`, `created_at`, `updated_at`)
VALUES
	(1,'20181217192332','base_access,base_admin_action_log,base_admin_user_role,base_backup,base_dbsync,base_file,base_file_group,base_file_used,base_option,base_option_group,base_role,base_user,jw_classes,jw_classes_members,jw_classes_teachers,teach_agreement,teach_brand,teach_category,teach_course,teach_course_chapter,teach_course_homework,teach_course_materials,teach_course_teachers,teach_course_test,teach_course_videos,teach_products,teach_study_plan,teach_study_plan_sub,wx_app_version,wx_article_tag,wx_articles,wx_banner,wx_banner_group,wx_coupon,wx_coupon_group,wx_goods,wx_goods_group,wx_goods_introduce,wx_goods_pay_type,wx_mobile_sms_code,wx_order,wx_pay','/2018/12/20181217192332.sql',2,'【上传云失败】RequestCoreException: cURL resource: Resource id #303; cURL error: Connection timed out after 1001 milliseconds (28)<br>#0 /Users/wangkaihui/work/code/php/duocaiEdu/vendor/aliyuncs/oss-sdk-php/src/OSS/OssClient.php(1180): OSS\\OssClient->auth(Array)\n#1 /Users/wangkaihui/work/code/php/duocaiEdu/application/Lib/Support/File/AliOss/Oss.php(77): OSS\\OssClient->uploadFile(\'1111\', \'backupsql/2018/...\', \'/tmp/backup/201...\')\n#2 /Users/wangkaihui/work/code/php/duocaiEdu/application/Lib/Support/File/File.php(46): Lib\\Support\\File\\AliOss\\Oss->save(\'backupsql/2018/...\', \'/tmp/backup/201...\')\n#3 /Users/wangkaihui/work/code/php/duocaiEdu/application/Lib/Service/FileService.php(124): Lib\\Support\\File\\File->save(\'backupsql/2018/...\', \'/tmp/backup/201...\')\n#4 /Users/wangkaihui/work/code/php/duocaiEdu/application/AdminBundle/Service/BackupService.php(171): Lib\\Service\\FileService->push(\'/tmp/backup/201...\', \'backupsql/2018/...\', \'backupsql\', \'1\', \'20181217192332....\')\n#5 /Users/wangkaihui/work/code/php/duocaiEdu/application/AdminBundle/Controllers/Data/BackupController.php(79): Admin\\Service\\BackupService->upyun(\'1\', \'1\')\n#6 [internal function]: Admin\\Controllers\\Data\\BackupController->upyun(\'1\')\n#7 /Users/wangkaihui/work/code/php/duocaiEdu/vendor/trensy/framework/src/trensy/Mvc/Route/RouteMatch.php(377): call_user_func_array(Array, Array)\n#8 /Users/wangkaihui/work/code/php/duocaiEdu/vendor/trensy/framework/src/trensy/Mvc/Route/RouteMatch.php(286): Trensy\\Mvc\\Route\\RouteMatch->runBase(Array, Array)\n#9 /Users/wangkaihui/work/code/php/duocaiEdu/vendor/trensy/framework/src/trensy/Foundation/Application.php(111): Trensy\\Mvc\\Route\\RouteMatch->run(\'/data/backup_up...\', Object(Trensy\\Http\\Request), Object(Trensy\\Http\\Response))\n#10 /Users/wangkaihui/work/code/php/duocaiEdu/vendor/trensy/framework/src/trensy/Server/Swoole/HttpdServer.php(185): Trensy\\Foundation\\Application::start(Object(Trensy\\Http\\Request), Object(Trensy\\Http\\Response))\n#11 /Users/wangkaihui/work/code/php/duocaiEdu/vendor/trensy/framework/src/trensy/Server/Swoole/HttpdServer.php(151): Trensy\\Server\\Swoole\\HttpdServer->requestHtmlHandle(Object(Trensy\\Http\\Request), Object(Trensy\\Http\\Response))\n#12 /Users/wangkaihui/work/code/php/duocaiEdu/vendor/trensy/framework/src/trensy/Server/Swoole/HttpdServer.php(144): Trensy\\Server\\Swoole\\HttpdServer->response(Object(Trensy\\Http\\Request), Object(Trensy\\Http\\Response))\n#13 {main}',NULL,1545045812,1545045870),
	(2,'20181217192439','base_access,base_admin_action_log,base_admin_user_role,base_backup,base_dbsync,base_file,base_file_group,base_file_used,base_option,base_option_group,base_role,base_user,jw_classes,jw_classes_members,jw_classes_teachers,teach_agreement,teach_brand,teach_category,teach_course,teach_course_chapter,teach_course_homework,teach_course_materials,teach_course_teachers,teach_course_test,teach_course_videos,teach_products,teach_study_plan,teach_study_plan_sub,wx_app_version,wx_article_tag,wx_articles,wx_banner,wx_banner_group,wx_coupon,wx_coupon_group,wx_goods,wx_goods_group,wx_goods_introduce,wx_goods_pay_type,wx_mobile_sms_code,wx_order,wx_pay','/2018/12/20181217192439.sql',2,NULL,NULL,1545045879,1545045879);



# Dump of table base_file
# ------------------------------------------------------------

DROP TABLE IF EXISTS `base_file`;

CREATE TABLE `base_file` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '上传文件ID',
  `group_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '上传文件组ID',
  `uid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '上传人ID',
  `obj` varchar(255) NOT NULL DEFAULT '' COMMENT '文件对象',
  `originalz_name` varchar(100) DEFAULT NULL COMMENT '文件原始名称',
  `mime` varchar(255) DEFAULT NULL COMMENT '文件MIME',
  `size` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '文件大小',
  `thirdpart_data` text COMMENT '第三方数据',
  `created_at` int(11) DEFAULT NULL COMMENT '上传时间',
  `updated_at` int(11) DEFAULT NULL COMMENT '更新时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8;


INSERT INTO `base_file` (`id`, `group_id`, `uid`, `obj`, `originalz_name`, `mime`, `size`, `thirdpart_data`, `created_at`, `updated_at`)
VALUES
	(1,4,0,'dfsx/advert/2018/12/18/96e29386-029d-11e9-8a82-720008a07430.jpg','guli.jpg','image/jpeg; charset=binary',26,'{\"x-upyun-content-length\":\"26540\",\"x-upyun-file-type\":\"JPEG\",\"x-upyun-content-type\":\"image\\/jpeg\",\"x-upyun-height\":\"300\",\"x-upyun-width\":\"499\",\"x-upyun-frames\":\"1\"}',1545121147,1545121147),
	(2,4,0,'dfsx/advert/2018/12/18/f9a3f94c-029d-11e9-9047-720008a07430.jpeg','jiaban.jpeg','image/jpeg; charset=binary',17,'{\"x-upyun-content-length\":\"17224\",\"x-upyun-file-type\":\"JPEG\",\"x-upyun-content-type\":\"image\\/jpeg\",\"x-upyun-height\":\"300\",\"x-upyun-width\":\"386\",\"x-upyun-frames\":\"1\"}',1545121313,1545121313),
	(3,1,0,'dfsx/default/2018/12/21/f697081c-0503-11e9-9200-720008a07430.jpeg','jiaban.jpeg','image/jpeg; charset=binary',17,'{\"x-upyun-content-length\":\"17224\",\"x-upyun-file-type\":\"JPEG\",\"x-upyun-content-type\":\"image\\/jpeg\",\"x-upyun-height\":\"300\",\"x-upyun-width\":\"386\",\"x-upyun-frames\":\"1\"}',1545385019,1545385019),
	(4,1,0,'dfsx/default/2018/12/21/5191e7dc-0504-11e9-b430-720008a07430.jpeg','jiaban.jpeg','image/jpeg; charset=binary',17,'{\"x-upyun-content-length\":\"17224\",\"x-upyun-file-type\":\"JPEG\",\"x-upyun-content-type\":\"image\\/jpeg\",\"x-upyun-height\":\"300\",\"x-upyun-width\":\"386\",\"x-upyun-frames\":\"1\"}',1545385174,1545385174),
	(5,1,0,'dfsx/default/2018/12/21/a39e0e8e-0504-11e9-9c75-720008a07430.jpeg','jiaban.jpeg','image/jpeg; charset=binary',17,'{\"x-upyun-content-length\":\"17224\",\"x-upyun-file-type\":\"JPEG\",\"x-upyun-content-type\":\"image\\/jpeg\",\"x-upyun-height\":\"300\",\"x-upyun-width\":\"386\",\"x-upyun-frames\":\"1\"}',1545385309,1545385309),
	(6,1,0,'dfsx/default/2018/12/21/d29bb916-0504-11e9-8afc-720008a07430.jpeg','jiaban.jpeg','image/jpeg; charset=binary',17,'{\"x-upyun-content-length\":\"17224\",\"x-upyun-file-type\":\"JPEG\",\"x-upyun-content-type\":\"image\\/jpeg\",\"x-upyun-height\":\"300\",\"x-upyun-width\":\"386\",\"x-upyun-frames\":\"1\"}',1545385388,1545385388),
	(7,1,0,'dfsx/default/2018/12/21/c108f040-0506-11e9-b385-720008a07430.jpeg','jiaban.jpeg','image/jpeg; charset=binary',17,'{\"x-upyun-content-length\":\"17224\",\"x-upyun-file-type\":\"JPEG\",\"x-upyun-content-type\":\"image\\/jpeg\",\"x-upyun-height\":\"300\",\"x-upyun-width\":\"386\",\"x-upyun-frames\":\"1\"}',1545386218,1545386218);



# Dump of table base_file_group
# ------------------------------------------------------------

DROP TABLE IF EXISTS `base_file_group`;

CREATE TABLE `base_file_group` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '上传文件组ID',
  `name` varchar(100) NOT NULL DEFAULT '' COMMENT '上传文件组名称',
  `code` varchar(100) NOT NULL DEFAULT '' COMMENT '上传文件组编码',
  `created_at` int(11) DEFAULT NULL,
  `updated_at` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;


INSERT INTO `base_file_group` (`id`, `name`, `code`, `created_at`, `updated_at`)
VALUES
	(1,'默认','default',1543209055,1543209055),
	(2,'头像','face',1543209055,1543209055),
	(4,'广告','advert',1543209055,1543209055),
	(5,'sql备份','backupsql',1545045855,1545045855);



# Dump of table base_file_used
# ------------------------------------------------------------

DROP TABLE IF EXISTS `base_file_used`;

CREATE TABLE `base_file_used` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `file_id` int(11) DEFAULT NULL COMMENT 'upload_files id',
  `target_type` varchar(32) DEFAULT NULL,
  `target_id` int(11) DEFAULT NULL,
  `created_at` int(11) DEFAULT NULL,
  `updated_at` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;


INSERT INTO `base_file_used` (`id`, `file_id`, `target_type`, `target_id`, `created_at`, `updated_at`)
VALUES
	(1,NULL,'backupsql',0,1545045818,1545045818);



# Dump of table base_option
# ------------------------------------------------------------

DROP TABLE IF EXISTS `base_option`;

CREATE TABLE `base_option` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `option_name` varchar(50) DEFAULT NULL COMMENT '配置名称',
  `option_key` varchar(50) DEFAULT NULL COMMENT '配置key',
  `group_id` int(11) DEFAULT NULL COMMENT 'group id',
  `option_value` text COMMENT '配置值',
  `created_at` int(11) DEFAULT NULL,
  `updated_at` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=22 DEFAULT CHARSET=utf8mb4;


INSERT INTO `base_option` (`id`, `option_name`, `option_key`, `group_id`, `option_value`, `created_at`, `updated_at`)
VALUES
	(1,'oss_accessKeyID','oss_accessKeyID',1,'1111',1543209055,1544666369),
	(2,'oss_accessKeySecret','oss_accessKeySecret',1,'11111',1543209055,1544671494),
	(3,'oss_endpoint','oss_endpoint',1,'http://xxx.com',1543209055,1544598358),
	(4,'oss_bucket','oss_bucket',1,'1111',1543209055,1543209055),
	(5,'oss_useCname','oss_useCname',1,'1',1543209055,1543209055),
	(6,'uss_serviceName','uss_serviceName',2,'2tagcn',1543209055,1543209055),
	(7,'uss_operatorName','uss_operatorName',2,'kaihui',1543209055,1543209055),
	(8,'uss_operatorPwd','uss_operatorPwd',2,'168wang',1543209055,1543209055),
	(9,'uss_endpoint','uss_endpoint',2,'//2tagcn.test.upcdn.net',1543209055,1543209055),
	(10,'云存储切换(aliyun,upyun)','file_adapter',3,'upyun',1543209055,1545120998),
	(11,'oss_timeout','oss_timeout',1,'10',1543209055,1543209055),
	(12,'oss_ConnectTimeout','oss_ConnectTimeout',1,'1',1543209055,1544603156),
	(13,'数据库备份目录','backup_dir',3,'/tmp/backup',1543209055,1543209055),
	(14,'网站名称','site_name',3,'2tag社区',1543209055,1543209055),
	(15,'主机','host',4,'smtp.163.com',1543209055,1543209055),
	(16,'端口','port',4,'465',1543209055,1543209055),
	(17,'账户','username',4,'xkgitlab@163.com',1543209055,1543209055),
	(18,'密码','password',4,'xkgitlab2018',1543209055,1543209055),
	(19,'传输协议','encryption',4,'ssl',1543209055,1543209055),
	(20,'网站网址','site_host',3,'www.2tag.cn',1543209055,1543209055),
	(21,'统计','tongji',3,'111',1543209055,1543209055);



# Dump of table base_option_group
# ------------------------------------------------------------

DROP TABLE IF EXISTS `base_option_group`;

CREATE TABLE `base_option_group` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `group_name` varchar(50) DEFAULT NULL COMMENT '组名称',
  `group_key` varchar(50) DEFAULT NULL COMMENT '组key',
  `created_at` int(11) DEFAULT NULL,
  `updated_at` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4;


INSERT INTO `base_option_group` (`id`, `group_name`, `group_key`, `created_at`, `updated_at`)
VALUES
	(1,'阿里云','aliyun',1543209055,1543209055),
	(2,'又拍云','upyun',1543209055,1543209055),
	(3,'基本配置','base',1543209055,1543209055),
	(4,'邮箱发送','email_smtp',1543209055,1543209055);



# Dump of table base_role
# ------------------------------------------------------------

DROP TABLE IF EXISTS `base_role`;

CREATE TABLE `base_role` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) DEFAULT NULL COMMENT '权限名称',
  `descr` text COMMENT '描述',
  `created_at` int(11) DEFAULT NULL,
  `updated_at` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COMMENT='角色表';


INSERT INTO `base_role` (`id`, `name`, `descr`, `created_at`, `updated_at`)
VALUES
	(1,'普通会员','普通会员,没有后台权限',1543209055,1543209055),
	(2,'测试管理员','测试管理员',1543209055,1543209055);



# Dump of table base_user
# ------------------------------------------------------------

DROP TABLE IF EXISTS `base_user`;

CREATE TABLE `base_user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `mobile` varchar(12) DEFAULT NULL,
  `display_name` varchar(100) DEFAULT NULL COMMENT '昵称',
  `full_name` varchar(100) DEFAULT NULL COMMENT '姓名',
  `face_img` varchar(250) DEFAULT NULL COMMENT '人物头像',
  `birthday` date DEFAULT NULL COMMENT '生日',
  `sex` tinyint(1) DEFAULT NULL COMMENT '1-男,2-女',
  `passwd` varchar(100) DEFAULT NULL,
  `is_admin` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否是管理员账号',
  `is_lock` tinyint(1) DEFAULT '0' COMMENT '是否被锁定,1-是，0-否',
  `last_login_time` int(11) DEFAULT NULL,
  `report_uid` int(11) DEFAULT '1' COMMENT '汇报上级',
  `reg_source` varchar(11) DEFAULT NULL COMMENT 'pc,ios,android',
  `pc_login_token` varchar(100) DEFAULT NULL,
  `app_login_token` varchar(100) DEFAULT NULL,
  `updated_at` int(11) DEFAULT NULL,
  `created_at` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;


INSERT INTO `base_user` (`id`, `mobile`, `display_name`, `full_name`, `face_img`, `birthday`, `sex`, `passwd`, `is_admin`, `is_lock`, `last_login_time`, `report_uid`, `reg_source`, `pc_login_token`, `app_login_token`, `updated_at`, `created_at`)
VALUES
	(1,'11111111111','管理员',NULL,'',NULL,NULL,'jNUXj1rymueNxln5FkXYdRhqSdH53+xgGHyORC8H0OR0CYV5tC0zyb15NxQShuwNZQ==',2,0,1545371155,0,NULL,'7bac8b8b0aee058b310be61f31f64af14381993e',NULL,1545371155,1543209055);



# Dump of table jw_classes
# ------------------------------------------------------------

DROP TABLE IF EXISTS `jw_classes`;

CREATE TABLE `jw_classes` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(100) DEFAULT NULL COMMENT '班级名称',
  `classes_no` varchar(100) DEFAULT NULL COMMENT '班级编码',
  `study_plan_id` int(11) DEFAULT NULL COMMENT '学习计划',
  `max_member_number` int(11) DEFAULT NULL COMMENT '最大班级人数，自动分班用',
  `product_id` int(11) DEFAULT NULL COMMENT '产品id',
  `created_at` int(11) DEFAULT NULL,
  `updated_at` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Dump of table jw_classes_members
# ------------------------------------------------------------

DROP TABLE IF EXISTS `jw_classes_members`;

CREATE TABLE `jw_classes_members` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `classes_id` int(11) DEFAULT NULL COMMENT '班级id',
  `uid` int(11) DEFAULT NULL,
  `type` tinyint(1) DEFAULT NULL COMMENT '1-在学学员，2-退学学员',
  `created_at` int(11) DEFAULT NULL,
  `updated_at` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Dump of table jw_classes_teachers
# ------------------------------------------------------------

DROP TABLE IF EXISTS `jw_classes_teachers`;

CREATE TABLE `jw_classes_teachers` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `classes_id` int(11) DEFAULT NULL COMMENT '班级id',
  `uid` int(11) DEFAULT NULL COMMENT '老师id',
  `teacher_type` tinyint(1) DEFAULT NULL COMMENT '1-班主任',
  `created_at` int(11) DEFAULT NULL,
  `updated_at` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Dump of table teach_agreement
# ------------------------------------------------------------

DROP TABLE IF EXISTS `teach_agreement`;

CREATE TABLE `teach_agreement` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL COMMENT '协议名称',
  `content` mediumtext COMMENT '协议内容',
  `is_show` tinyint(1) DEFAULT '1',
  `created_at` int(11) DEFAULT NULL,
  `updated_at` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;


INSERT INTO `teach_agreement` (`id`, `name`, `content`, `is_show`, `created_at`, `updated_at`)
VALUES
	(2,'测试协议','<p>测试协议测试协议测试协议测试协议</p><p><img src=\"//2tagcn.test.upcdn.net/dfsx/default/2018/12/21/c108f040-0506-11e9-b385-720008a07430.jpeg\" data-filename=\"img\" style=\"width: 386px;\"><br></p>',0,1545385419,1545386219);



# Dump of table teach_brand
# ------------------------------------------------------------

DROP TABLE IF EXISTS `teach_brand`;

CREATE TABLE `teach_brand` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(90) NOT NULL DEFAULT '' COMMENT '品类名称',
  `is_show` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '是否显示',
  `created_at` int(11) DEFAULT NULL,
  `updated_at` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Dump of table teach_category
# ------------------------------------------------------------

DROP TABLE IF EXISTS `teach_category`;

CREATE TABLE `teach_category` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(90) NOT NULL DEFAULT '' COMMENT '分类名称',
  `parent_id` smallint(5) unsigned NOT NULL DEFAULT '0' COMMENT '父id',
  `brand_id` int(11) DEFAULT NULL COMMENT '品类id',
  `is_show` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '是否显示',
  `created_at` int(11) DEFAULT NULL,
  `updated_at` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `parent_id` (`parent_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Dump of table teach_course
# ------------------------------------------------------------

DROP TABLE IF EXISTS `teach_course`;

CREATE TABLE `teach_course` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(100) DEFAULT NULL COMMENT '课程名称',
  `type` tinyint(1) DEFAULT NULL COMMENT '1-线上,2-线下,3-混合',
  `big_img` varchar(100) DEFAULT NULL COMMENT '封面图',
  `descr` varchar(200) DEFAULT NULL COMMENT '简介',
  `cate_id` int(11) DEFAULT NULL COMMENT '类目id',
  `brand_id` int(11) DEFAULT NULL COMMENT '品类id',
  `create_uid` int(11) DEFAULT NULL COMMENT '创建人uid，自己创建的自己可见',
  `status` tinyint(1) DEFAULT '0' COMMENT '0-草稿，2-上架,1-下架',
  `course_hour` int(11) DEFAULT NULL COMMENT '课时，乘以100',
  `teachers_id` int(11) DEFAULT NULL COMMENT '讲师id',
  `version_number` varchar(100) DEFAULT NULL COMMENT '课程期号',
  `created_at` int(11) DEFAULT NULL,
  `updated_at` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Dump of table teach_course_chapter
# ------------------------------------------------------------

DROP TABLE IF EXISTS `teach_course_chapter`;

CREATE TABLE `teach_course_chapter` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `course_id` int(11) DEFAULT NULL,
  `name` varchar(200) DEFAULT NULL COMMENT '章节名称',
  `open_time` int(11) DEFAULT NULL COMMENT '开课时间',
  `parent_id` int(11) DEFAULT NULL COMMENT '父章节',
  `is_free` tinyint(1) DEFAULT '0' COMMENT '1-是，0-否',
  `created_at` int(11) DEFAULT NULL,
  `updated_at` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Dump of table teach_course_homework
# ------------------------------------------------------------

DROP TABLE IF EXISTS `teach_course_homework`;

CREATE TABLE `teach_course_homework` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `course_id` int(11) DEFAULT NULL,
  `chapter_id` int(11) DEFAULT NULL,
  `name` int(11) DEFAULT NULL,
  `test_id` int(11) DEFAULT NULL,
  `created_at` int(11) DEFAULT NULL,
  `updated_at` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Dump of table teach_course_materials
# ------------------------------------------------------------

DROP TABLE IF EXISTS `teach_course_materials`;

CREATE TABLE `teach_course_materials` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `course_id` int(11) DEFAULT NULL,
  `chapter_id` int(11) DEFAULT NULL COMMENT '章节id',
  `name` varchar(100) DEFAULT NULL COMMENT '资料名称',
  `path` varchar(254) DEFAULT NULL COMMENT '资料下载路径',
  `created_at` int(11) DEFAULT NULL,
  `updated_at` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Dump of table teach_course_teachers
# ------------------------------------------------------------

DROP TABLE IF EXISTS `teach_course_teachers`;

CREATE TABLE `teach_course_teachers` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(11) DEFAULT NULL COMMENT '老师id',
  `course_id` int(11) DEFAULT NULL COMMENT '课程id',
  `created_at` int(11) DEFAULT NULL,
  `updated_at` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Dump of table teach_course_test
# ------------------------------------------------------------

DROP TABLE IF EXISTS `teach_course_test`;

CREATE TABLE `teach_course_test` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `course_id` int(11) DEFAULT NULL,
  `name` varchar(100) DEFAULT NULL COMMENT '试卷名称',
  `test_id` int(11) DEFAULT NULL,
  `created_at` int(11) DEFAULT NULL,
  `update_at` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Dump of table teach_course_videos
# ------------------------------------------------------------

DROP TABLE IF EXISTS `teach_course_videos`;

CREATE TABLE `teach_course_videos` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `course_id` int(11) DEFAULT NULL,
  `chapter_id` int(11) DEFAULT NULL COMMENT '章节id',
  `type` tinyint(1) DEFAULT NULL COMMENT '1-直播,2-录播',
  `video_channel` tinyint(1) DEFAULT NULL COMMENT '1-cc视频，2-百度云',
  `channel_data` text COMMENT '渠道数据',
  `created_at` int(11) DEFAULT NULL,
  `updated_at` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Dump of table teach_products
# ------------------------------------------------------------

DROP TABLE IF EXISTS `teach_products`;

CREATE TABLE `teach_products` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(100) DEFAULT NULL,
  `cate_id` int(11) DEFAULT NULL COMMENT '分类id',
  `descr` text COMMENT '简介',
  `agreement_id` int(11) DEFAULT NULL COMMENT '协议id',
  `status` tinyint(1) DEFAULT NULL COMMENT '0-草稿，2-上架,1-下架',
  `created_at` int(11) DEFAULT NULL,
  `updated_at` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Dump of table teach_study_plan
# ------------------------------------------------------------

DROP TABLE IF EXISTS `teach_study_plan`;

CREATE TABLE `teach_study_plan` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(100) DEFAULT NULL COMMENT '名称',
  `is_default` tinyint(1) DEFAULT NULL COMMENT '是否默认计划',
  `is_block` tinyint(1) DEFAULT NULL COMMENT '是否挡板',
  `created_at` int(11) DEFAULT NULL,
  `updated_at` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Dump of table teach_study_plan_sub
# ------------------------------------------------------------

DROP TABLE IF EXISTS `teach_study_plan_sub`;

CREATE TABLE `teach_study_plan_sub` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `study_plan_id` int(11) DEFAULT NULL,
  `course_id` int(11) DEFAULT NULL COMMENT '课程',
  `created_at` int(11) DEFAULT NULL,
  `updated_at` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Dump of table wx_app_version
# ------------------------------------------------------------

DROP TABLE IF EXISTS `wx_app_version`;

CREATE TABLE `wx_app_version` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `version_num` varchar(50) NOT NULL COMMENT '版本号',
  `is_ios` tinyint(2) DEFAULT '0' COMMENT 'ios',
  `is_android` tinyint(2) DEFAULT '0' COMMENT 'android',
  `is_show` tinyint(2) DEFAULT '0' COMMENT '0:下架 1:上架',
  `is_update` tinyint(2) DEFAULT '0' COMMENT '是否强制升級 0:否 1:是',
  `description` text COMMENT 'app版本描述',
  `created_at` int(11) DEFAULT NULL,
  `updated_at` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8;


INSERT INTO `wx_app_version` (`id`, `version_num`, `is_ios`, `is_android`, `is_show`, `is_update`, `description`, `created_at`, `updated_at`)
VALUES
	(4,'1.1.1',0,1,1,0,'1.1.111111',1545287949,1545288032),
	(5,'2.0.1',1,0,1,0,'2.0.1xxxx',1545371443,1545371576),
	(6,'asasda',1,0,1,1,'dasdasd',1545371869,1545371869),
	(7,'asdasda',1,0,1,1,'sdasdasd',1545371876,1545371876),
	(8,'asaasdasdasd',1,0,1,1,'asdasdasd',1545371885,1545371885),
	(9,'asdasdasd',1,0,1,1,'asdasda',1545371892,1545371892),
	(10,'aaaaa',1,0,1,1,'aaaa',1545371900,1545371900),
	(11,'2222222',1,0,1,1,'22222',1545371908,1545371908),
	(12,'dddddddd',1,0,1,1,'dddddd',1545371918,1545371918);



# Dump of table wx_article_tag
# ------------------------------------------------------------

DROP TABLE IF EXISTS `wx_article_tag`;

CREATE TABLE `wx_article_tag` (
  `id` int(10) NOT NULL AUTO_INCREMENT COMMENT '自增id',
  `tag_id` int(10) DEFAULT NULL COMMENT '三级标签id',
  `article_id` int(10) DEFAULT NULL COMMENT '文章id',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '创建时间',
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '更新时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Dump of table wx_articles
# ------------------------------------------------------------

DROP TABLE IF EXISTS `wx_articles`;

CREATE TABLE `wx_articles` (
  `id` int(10) NOT NULL AUTO_INCREMENT COMMENT '主键id',
  `title` varchar(200) NOT NULL DEFAULT '' COMMENT '标题',
  `subtitle` text COMMENT '副标题',
  `is_show` tinyint(1) NOT NULL DEFAULT '1' COMMENT '是否显示1为显示',
  `is_sort` int(3) DEFAULT '50' COMMENT '排序100最大',
  `brand_id` int(10) DEFAULT NULL COMMENT '品牌id',
  `cate_id` int(11) DEFAULT NULL COMMENT '类别id',
  `tag_id` int(10) DEFAULT NULL COMMENT '一级标签id',
  `create_uid` int(10) DEFAULT NULL COMMENT '创建人',
  `channel` varchar(100) NOT NULL DEFAULT 'default' COMMENT '渠道',
  `view_number` int(10) DEFAULT '100' COMMENT '浏览量',
  `img` varchar(255) DEFAULT NULL COMMENT '新闻图片',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '创建时间',
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '更新时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Dump of table wx_articles_sub
# ------------------------------------------------------------

DROP TABLE IF EXISTS `wx_articles_sub`;

CREATE TABLE `wx_articles_sub` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `article_id` int(11) DEFAULT NULL,
  `content` text,
  `created_at` int(11) DEFAULT NULL,
  `updated_at` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Dump of table wx_banner
# ------------------------------------------------------------

DROP TABLE IF EXISTS `wx_banner`;

CREATE TABLE `wx_banner` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `banner_group_id` int(11) DEFAULT NULL,
  `banner_sort` int(11) DEFAULT NULL COMMENT '排序',
  `banner_name` varchar(120) DEFAULT NULL COMMENT '广告名称',
  `banner_href` varchar(250) DEFAULT NULL COMMENT '广告连接',
  `banner_img` varchar(250) DEFAULT NULL COMMENT '广告图片',
  `create_uid` int(11) DEFAULT NULL COMMENT '创建uid',
  `is_show` tinyint(1) DEFAULT '0' COMMENT '是否显示',
  `created_at` int(11) DEFAULT NULL,
  `updated_at` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;


INSERT INTO `wx_banner` (`id`, `banner_group_id`, `banner_sort`, `banner_name`, `banner_href`, `banner_img`, `create_uid`, `is_show`, `created_at`, `updated_at`)
VALUES
	(1,1,1,'测试广告','http://admin.dc.test/banner/add','//2tagcn.test.upcdn.net/dfsx/advert/2018/12/18/f9a3f94c-029d-11e9-9047-720008a07430.jpeg',NULL,1,1545121151,1545121317);



# Dump of table wx_banner_group
# ------------------------------------------------------------

DROP TABLE IF EXISTS `wx_banner_group`;

CREATE TABLE `wx_banner_group` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(120) DEFAULT NULL COMMENT 'banner组标题',
  `alias_name` varchar(120) DEFAULT NULL COMMENT '英文别名',
  `is_show` tinyint(1) DEFAULT '0' COMMENT '是否显示',
  `created_at` int(11) DEFAULT NULL,
  `updated_at` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;


INSERT INTO `wx_banner_group` (`id`, `name`, `alias_name`, `is_show`, `created_at`, `updated_at`)
VALUES
	(1,'首页','home',1,1545110509,1545110509);



# Dump of table wx_coupon
# ------------------------------------------------------------

DROP TABLE IF EXISTS `wx_coupon`;

CREATE TABLE `wx_coupon` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '优惠码id',
  `coupon_group_id` int(10) unsigned NOT NULL COMMENT '优惠码管理id',
  `coupon_sn` varchar(20) NOT NULL COMMENT '优惠码编号',
  `uid` int(10) unsigned DEFAULT NULL COMMENT '使用会员',
  `used_time` int(10) unsigned DEFAULT NULL COMMENT '使用时间',
  `send_time` int(10) unsigned DEFAULT NULL COMMENT '发送时间',
  `coupon_status` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '优惠码状态:0未使用,1已使用',
  `export_status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '导出状态0：不导出1：导出',
  `created_at` int(11) DEFAULT NULL,
  `updated_at` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `coupon_status` (`coupon_status`) USING BTREE,
  KEY `coupon_manage_id` (`coupon_group_id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8 COMMENT='优惠码表';


INSERT INTO `wx_coupon` (`id`, `coupon_group_id`, `coupon_sn`, `uid`, `used_time`, `send_time`, `coupon_status`, `export_status`, `created_at`, `updated_at`)
VALUES
	(1,2,'D0000001V9N4COR5',NULL,NULL,NULL,0,0,1544682796,NULL),
	(2,2,'D000000284OXVXE8',NULL,NULL,NULL,0,0,1544682796,NULL),
	(3,2,'D0000003K4ZFKPMV',NULL,NULL,NULL,0,0,1544682796,NULL),
	(4,2,'D0000004OG2S89WO',NULL,NULL,NULL,0,0,1544682796,NULL),
	(5,2,'D0000005CLU0OHQD',NULL,NULL,NULL,0,0,1544682796,NULL),
	(6,2,'D0000006A3VMN6MA',NULL,NULL,NULL,0,0,1544682796,NULL),
	(7,2,'D00000070XOTNHYT',NULL,NULL,NULL,0,0,1544682796,NULL),
	(8,2,'D00000082GVPQ37R',NULL,NULL,NULL,0,0,1544682796,NULL),
	(9,2,'D0000009EX4FX724',NULL,NULL,NULL,0,0,1544682796,NULL),
	(10,2,'D00000106U3WLJBV',NULL,NULL,NULL,0,0,1544682796,NULL),
	(11,2,'D00000111CP2KVIH',NULL,NULL,NULL,0,0,1544682796,NULL),
	(12,2,'D0000012AZUP6E6G',NULL,NULL,NULL,0,0,1544682796,NULL);



# Dump of table wx_coupon_group
# ------------------------------------------------------------

DROP TABLE IF EXISTS `wx_coupon_group`;

CREATE TABLE `wx_coupon_group` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '优惠码组id',
  `coupon_name` varchar(20) NOT NULL COMMENT '优惠码名称',
  `coupon_type` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '类型:0金额优惠,1折扣优惠',
  `discount` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '优惠码折扣(百分数)/优惠码金额(乘以100)',
  `order_lower_limit` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '订单下限售价,乘以100',
  `count_num` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '发放数量',
  `used_num` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '已使用的数量',
  `expiration_start` int(10) unsigned NOT NULL COMMENT '开始有效日期',
  `expiration_end` int(10) unsigned NOT NULL COMMENT '结束有效日期',
  `is_show` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '上架1，下架0',
  `create_uid` int(10) unsigned NOT NULL COMMENT '创建人',
  `coupon_description` varchar(200) NOT NULL DEFAULT '' COMMENT '优惠码描述',
  `category_id` varchar(250) DEFAULT NULL COMMENT '配套产品，分类id',
  `teaching_method` varchar(50) DEFAULT NULL COMMENT '授课方式,配套班型:1面授课,2直播课,3面授课+直播课,4网课,5VIP',
  `goods_id` varchar(50) DEFAULT NULL COMMENT '商品id',
  `created_at` int(11) DEFAULT NULL,
  `updated_at` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `coupon_type` (`coupon_type`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COMMENT='优惠码组表';


INSERT INTO `wx_coupon_group` (`id`, `coupon_name`, `coupon_type`, `discount`, `order_lower_limit`, `count_num`, `used_num`, `expiration_start`, `expiration_end`, `is_show`, `create_uid`, `coupon_description`, `category_id`, `teaching_method`, `goods_id`, `created_at`, `updated_at`)
VALUES
	(1,'测试优惠券',0,11100,11100,12,0,1544025600,1544803199,1,1,'测试优惠券',NULL,NULL,NULL,1544682649,1544682649),
	(2,'测试优惠券',0,11100,11100,12,0,1544025600,1544803199,1,1,'测试优惠券',NULL,NULL,NULL,1544682796,1544682796);



# Dump of table wx_goods
# ------------------------------------------------------------

DROP TABLE IF EXISTS `wx_goods`;

CREATE TABLE `wx_goods` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `goods_name` varchar(120) DEFAULT NULL COMMENT '课程名称',
  `subhead` varchar(250) DEFAULT NULL COMMENT '副标题',
  `cate_id` int(11) DEFAULT '0' COMMENT '分类id',
  `brand_id` int(11) DEFAULT NULL COMMENT '品类id',
  `product_id` int(11) DEFAULT NULL COMMENT '产品id',
  `study_plan_id` int(11) DEFAULT NULL COMMENT '学习计划,可为空，如果不为空，直接分班',
  `type` tinyint(1) DEFAULT '1' COMMENT '1-直播,2-录播,3-混合',
  `teaching_method` tinyint(2) DEFAULT NULL COMMENT '授课方式1.面授2.直播3.面授+直播4录播5.VIP',
  `teaching_teacher` varchar(250) DEFAULT NULL COMMENT '授课教师,多个',
  `course_hour` int(11) DEFAULT NULL COMMENT '课时，乘以10',
  `course_count` int(11) DEFAULT NULL COMMENT '课次数',
  `market_price` int(11) DEFAULT NULL COMMENT '成本价,乘以100',
  `shop_price` int(11) DEFAULT NULL COMMENT '售价,乘以100',
  `buy_number` int(11) DEFAULT NULL COMMENT '购买人数',
  `goods_img` varchar(250) DEFAULT NULL COMMENT '商品海报',
  `recommended_img` varchar(250) DEFAULT NULL COMMENT '推荐图',
  `goods_small_img` varchar(250) DEFAULT NULL COMMENT '商品默认小图对应的标签',
  `is_show` tinyint(1) DEFAULT '0' COMMENT '是否上架,0-下家,1-上架',
  `creater_uid` int(11) DEFAULT NULL COMMENT '后台创建人',
  `recommended_position` tinyint(1) DEFAULT '0' COMMENT '5个位置，每个位置只能有一个',
  `is_group` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否是组合0：否，1：是',
  `group_type` tinyint(11) DEFAULT '0' COMMENT '1-选择,2-全部',
  `agreement_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '协议id',
  `created_at` int(11) DEFAULT NULL,
  `updated_at` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_category_id` (`cate_id`) USING BTREE,
  KEY `idx_teaching_method` (`teaching_method`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Dump of table wx_goods_group
# ------------------------------------------------------------

DROP TABLE IF EXISTS `wx_goods_group`;

CREATE TABLE `wx_goods_group` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `goods_id` int(11) DEFAULT NULL COMMENT '商品id',
  `name` varchar(100) NOT NULL DEFAULT '' COMMENT '组名',
  `group_goods__id` varchar(100) NOT NULL DEFAULT '' COMMENT '组名下商品id',
  `created_at` int(11) DEFAULT NULL,
  `updated_at` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Dump of table wx_goods_introduce
# ------------------------------------------------------------

DROP TABLE IF EXISTS `wx_goods_introduce`;

CREATE TABLE `wx_goods_introduce` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `goods_id` int(11) DEFAULT NULL,
  `introduce_type` tinyint(2) DEFAULT '0' COMMENT '课程介绍类型,1-版型介绍， 2-特色服务，3-适用人群，4-学习目标，0-图文介绍',
  `content` text COMMENT '课程介绍内容',
  `created_at` int(11) DEFAULT NULL,
  `updated_at` int(11) DEFAULT NULL,
  `href` text COMMENT '视频ID',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Dump of table wx_goods_pay_type
# ------------------------------------------------------------

DROP TABLE IF EXISTS `wx_goods_pay_type`;

CREATE TABLE `wx_goods_pay_type` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `goods_id` int(11) DEFAULT NULL COMMENT '商品id',
  `pay_name` varchar(50) NOT NULL COMMENT '支付名称',
  `pay_price` int(11) NOT NULL DEFAULT '0' COMMENT '支付价格',
  `created_at` int(11) DEFAULT NULL,
  `updated_at` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Dump of table wx_mobile_sms_code
# ------------------------------------------------------------

DROP TABLE IF EXISTS `wx_mobile_sms_code`;

CREATE TABLE `wx_mobile_sms_code` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `mobile` varchar(13) DEFAULT NULL COMMENT '手机号码',
  `type` varchar(25) DEFAULT NULL COMMENT '验证码类型',
  `code` varchar(25) DEFAULT NULL COMMENT '验证码',
  `created_at` int(11) NOT NULL,
  `updated_at` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;


INSERT INTO `wx_mobile_sms_code` (`id`, `mobile`, `type`, `code`, `created_at`, `updated_at`)
VALUES
	(1,'18500000199','site_login','123456',1545127966,1545127966);


# Dump of table wx_order
# ------------------------------------------------------------

DROP TABLE IF EXISTS `wx_order`;

CREATE TABLE `wx_order` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '订单表id',
  `order_no` varchar(20) NOT NULL COMMENT '订单号',
  `uid` int(10) unsigned NOT NULL COMMENT 'users表的用户id',
  `goods_id` int(10) unsigned NOT NULL COMMENT 'goods表的商品id',
  `order_amount` int(20) NOT NULL DEFAULT '0' COMMENT '订单原价',
  `discount_amount` int(20) NOT NULL DEFAULT '0' COMMENT '优惠金额',
  `order_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '下单时间',
  `order_status` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '订单状态:0待支付,1已支付,2已取消',
  `user_notes` varchar(200) NOT NULL DEFAULT '' COMMENT '用户备注',
  `referer` varchar(20) NOT NULL DEFAULT 'CRM后台创建' COMMENT '订单来源',
  `coupon_sn` varchar(20) NOT NULL DEFAULT '' COMMENT '订单使用优惠码编号',
  `goods_all` varchar(100) NOT NULL DEFAULT '' COMMENT '多个goodsid',
  `goods_name` varchar(1000) NOT NULL DEFAULT '' COMMENT '订单商品名称',
  `created_at` int(11) DEFAULT NULL,
  `updated_at` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `order_no` (`order_no`) USING BTREE,
  KEY `order_time` (`order_time`) USING BTREE,
  KEY `user_id` (`uid`) USING BTREE,
  KEY `order_status` (`order_status`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='订单表';



# Dump of table wx_pay
# ------------------------------------------------------------

DROP TABLE IF EXISTS `wx_pay`;

CREATE TABLE `wx_pay` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '支付表id',
  `transaction_id` varchar(200) NOT NULL COMMENT '支付流水号',
  `order_id` int(10) unsigned NOT NULL COMMENT '订单表id',
  `pay_time` int(10) unsigned NOT NULL COMMENT '支付时间',
  `payment_type` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '支付方式:0支付宝,1微信支付',
  `pay_status` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '付款状态:0支付失败,1支付成功',
  `amount` int(10) unsigned NOT NULL COMMENT '乘以100',
  `updated_at` int(11) DEFAULT NULL,
  `created_at` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='支付表';

