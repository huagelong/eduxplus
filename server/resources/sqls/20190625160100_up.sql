ALTER TABLE `jw_teacher` ADD COLUMN `pinyin` varchar(200) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '名称拼音' AFTER `descr`;

ALTER TABLE `teach_course` MODIFY COLUMN `status` tinyint(1) NULL DEFAULT 0 COMMENT '0-下架，1-上架' AFTER `create_uid`;

ALTER TABLE `teach_course` MODIFY COLUMN `school_id` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '线下分校,id以,分割' AFTER `course_hour`;

ALTER TABLE `teach_course_chapter` ADD COLUMN `is_del` tinyint(1) NULL DEFAULT 0 COMMENT '1-是，0-否' AFTER `is_free`;

ALTER TABLE `teach_course_chapter` ADD COLUMN `fsort` int(11) NULL DEFAULT 0 COMMENT '排序' AFTER `is_del`;

ALTER TABLE `teach_course_teachers` ADD COLUMN `chapter_id` int(11) NULL DEFAULT NULL COMMENT '章节id' AFTER `course_id`;
