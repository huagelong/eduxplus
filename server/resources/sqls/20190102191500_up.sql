CREATE TABLE `base_city`  (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `country` varchar(256) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `state` varchar(256) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `city` varchar(256) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `state_code` varchar(64) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `city_code` varchar(64) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `cn_city` varchar(256) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `cn_state` varchar(256) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `cn_country` varchar(256) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '',
  `country_code` varchar(64) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `created_at` int(11) NULL DEFAULT NULL,
  `updated_at` int(11) NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

ALTER TABLE `jw_classes_teachers` ADD COLUMN `teacher_id` int(11) NULL DEFAULT NULL COMMENT '老师id' AFTER `classes_id`;

ALTER TABLE `jw_classes_teachers` DROP COLUMN `uid`;

CREATE TABLE `jw_school`  (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '学校名称',
  `address` varchar(200) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '学校地址',
  `descr` text CHARACTER SET utf8 COLLATE utf8_general_ci NULL COMMENT '描叙',
  `state_code` varchar(64) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '省份',
  `city_id` int(11) NULL DEFAULT NULL COMMENT '城市id',
  `linkin` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '' COMMENT '联系方式',
  `status` tinyint(1) NULL DEFAULT 1 COMMENT '是否有效',
  `created_at` int(11) NULL DEFAULT NULL,
  `updated_at` int(11) NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

CREATE TABLE `jw_teacher`  (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(200) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `descr` text CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
  `type` tinyint(1) NULL DEFAULT NULL COMMENT '1-网课老师，2-分校老师，3-全部',
  `school_id` int(11) NULL DEFAULT NULL COMMENT '所属分校',
  `status` tinyint(1) NULL DEFAULT 1 COMMENT '是否有效',
  `created_at` int(11) NULL DEFAULT NULL,
  `updated_at` int(11) NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;


ALTER TABLE `teach_course` ADD COLUMN `school_id` int(11) NULL DEFAULT NULL COMMENT '线下分校' AFTER `course_hour`;

ALTER TABLE `teach_course` DROP COLUMN `teachers_id`;

ALTER TABLE `teach_course` DROP COLUMN `version_number`;

ALTER TABLE `teach_course_teachers` ADD COLUMN `teacher_id` int(11) NULL DEFAULT NULL COMMENT '老师id' AFTER `id`;

ALTER TABLE `teach_course_teachers` DROP COLUMN `uid`;

ALTER TABLE `wx_article_tag` MODIFY COLUMN `created_at` int(11) NOT NULL COMMENT '创建时间' AFTER `is_show`;

ALTER TABLE `wx_article_tag` MODIFY COLUMN `updated_at` int(11) NOT NULL COMMENT '更新时间' AFTER `created_at`;

