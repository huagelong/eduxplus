CREATE TABLE `wx_article`  (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '主键id',
  `title` varchar(200) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '标题',
  `is_show` tinyint(1) NOT NULL DEFAULT 1 COMMENT '是否显示1为显示',
  `sort` int(11) NULL DEFAULT 50 COMMENT '排序100最大',
  `brand_id` int(11) NULL DEFAULT NULL COMMENT '品牌id',
  `cate_id` int(11) NULL DEFAULT NULL COMMENT '类别id',
  `tag_id` int(11) NULL DEFAULT NULL COMMENT '一级标签id',
  `create_uid` int(11) NULL DEFAULT NULL COMMENT '创建人',
  `view_number` int(11) NULL DEFAULT 100 COMMENT '浏览量',
  `created_at` int(11) NOT NULL COMMENT '创建时间',
  `updated_at` int(11) NOT NULL COMMENT '更新时间',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8 COLLATE = utf8_general_ci;

CREATE TABLE `wx_article_sub`  (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `article_id` int(11) NULL DEFAULT NULL,
  `content` text CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
  `created_at` int(11) NULL DEFAULT NULL,
  `updated_at` int(11) NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8 COLLATE = utf8_general_ci;

ALTER TABLE `wx_article_tag` ADD COLUMN `name` varchar(200) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '' COMMENT '名称' AFTER `id`;

ALTER TABLE `wx_article_tag` ADD COLUMN `sort` int(11) NULL DEFAULT 0 COMMENT '排序' AFTER `name`;

ALTER TABLE `wx_article_tag` ADD COLUMN `is_show` tinyint(1) NULL DEFAULT 1 COMMENT '是否显示' AFTER `sort`;

ALTER TABLE `wx_article_tag` MODIFY COLUMN `id` int(11) NOT NULL COMMENT '自增id' FIRST;

ALTER TABLE `wx_article_tag` MODIFY COLUMN `created_at` int(11) NOT NULL COMMENT '创建时间' AFTER `is_show`;

ALTER TABLE `wx_article_tag` MODIFY COLUMN `updated_at` int(11) NOT NULL COMMENT '更新时间' AFTER `created_at`;

ALTER TABLE `wx_article_tag` MODIFY COLUMN `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '自增id' FIRST;

ALTER TABLE `wx_article_tag` DROP COLUMN `tag_id`;

ALTER TABLE `wx_article_tag` DROP COLUMN `article_id`;

DROP TABLE `wx_articles`;

DROP TABLE `wx_articles_sub`;