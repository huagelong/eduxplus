ALTER TABLE `teach_brand` ADD COLUMN `sort` int(11) NULL DEFAULT NULL COMMENT '排序' AFTER `is_show`;
ALTER TABLE `teach_brand` ADD COLUMN `is_delete` tinyint(1) NULL DEFAULT 0 COMMENT '是否删除' AFTER `sort`;
ALTER TABLE `teach_category` MODIFY COLUMN `parent_id` int(11) UNSIGNED NOT NULL DEFAULT 0 COMMENT '父id' AFTER `name`;

ALTER TABLE `teach_category` ADD COLUMN `sort` int(11) NULL DEFAULT NULL COMMENT '排序' AFTER `brand_id`;

ALTER TABLE `teach_category` ADD COLUMN `is_delete` tinyint(1) NULL DEFAULT 0 COMMENT '是否删除' AFTER `sort`;


