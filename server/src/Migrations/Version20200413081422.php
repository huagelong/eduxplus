<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200413081422 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE mall_goods_introduce ADD course_chapter_id TEXT DEFAULT NULL COMMENT \'视频章节ID\', DROP href, CHANGE introduce_type introduce_type TINYINT(1) DEFAULT NULL COMMENT \'课程介绍类型 1-课程试听, 2-班型介绍， 3-特色服务，4-适用人群，5-学习目标, 6-图文介绍\'');
        $this->addSql('ALTER TABLE teach_study_plan_sub ADD sort INT DEFAULT NULL COMMENT \'排序\'');
        $this->addSql('ALTER TABLE mall_goods ADD sort INT DEFAULT NULL COMMENT \'排序\', DROP study_plan_id, DROP recommended_position, CHANGE teaching_method teaching_method TINYINT(1) DEFAULT NULL COMMENT \'授课方式 1.面授 2.直播 4.录播 5.面授+直播 6.直播+录播\'');
        $this->addSql('ALTER TABLE jw_classes DROP max_member_number, CHANGE study_plan_id study_plan_id INT DEFAULT NULL COMMENT \'学习计划id\'');
        $this->addSql('ALTER TABLE teach_study_plan ADD product_id INT DEFAULT NULL COMMENT \'产品id\', ADD applyed_at DATETIME DEFAULT NULL COMMENT \'预计报名时间，程序根据预计报名时间给出预警\', CHANGE is_default is_default TINYINT(1) DEFAULT NULL COMMENT \'是否当前默认计划\', CHANGE is_block is_block TINYINT(1) DEFAULT NULL COMMENT \'是否有挡板，必须一节节往下看\'');
        $this->addSql('ALTER TABLE teach_course_chapter CHANGE is_free is_free TINYINT(1) DEFAULT NULL COMMENT \'是否试听课，1-是，0-否\'');
        $this->addSql('ALTER TABLE teach_products ADD study_plan_auto TINYINT(1) DEFAULT NULL COMMENT \'是否根据报名时间自动更新学习计划,1-是，0-否\', ADD max_member_number INT DEFAULT NULL COMMENT \'自动分班，最大班级人数\'');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE jw_classes ADD max_member_number INT DEFAULT NULL COMMENT \'最大班级人数，自动分班用\', CHANGE study_plan_id study_plan_id INT DEFAULT NULL COMMENT \'学习计划\'');
        $this->addSql('ALTER TABLE mall_goods ADD study_plan_id INT DEFAULT NULL COMMENT \'学习计划,可为空，如果不为空，直接分班\', ADD recommended_position TINYINT(1) DEFAULT NULL COMMENT \'5个位置，每个位置只能有一个\', DROP sort, CHANGE teaching_method teaching_method TINYINT(1) DEFAULT NULL COMMENT \'授课方式1.面授2.直播3.面授+直播4录播\'');
        $this->addSql('ALTER TABLE mall_goods_introduce ADD href TEXT CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci` COMMENT \'视频ID\', DROP course_chapter_id, CHANGE introduce_type introduce_type TINYINT(1) DEFAULT NULL COMMENT \'课程介绍类型,1-班型介绍， 2-特色服务，3-适用人群，4-学习目标，0-图文介绍\'');
        $this->addSql('ALTER TABLE teach_course_chapter CHANGE is_free is_free TINYINT(1) DEFAULT NULL COMMENT \'1-是，0-否\'');
        $this->addSql('ALTER TABLE teach_products DROP study_plan_auto, DROP max_member_number');
        $this->addSql('ALTER TABLE teach_study_plan DROP product_id, DROP applyed_at, CHANGE is_default is_default TINYINT(1) DEFAULT NULL COMMENT \'是否默认计划\', CHANGE is_block is_block TINYINT(1) DEFAULT NULL COMMENT \'是否挡板\'');
        $this->addSql('ALTER TABLE teach_study_plan_sub DROP sort');
    }
}
