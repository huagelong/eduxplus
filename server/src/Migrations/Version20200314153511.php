<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200314153511 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE teach_category CHANGE id id INT AUTO_INCREMENT NOT NULL');
        $this->addSql('ALTER TABLE teach_study_plan_sub CHANGE id id INT AUTO_INCREMENT NOT NULL');
        $this->addSql('ALTER TABLE jw_classes_members CHANGE id id INT AUTO_INCREMENT NOT NULL');
        $this->addSql('ALTER TABLE base_option_group CHANGE id id INT AUTO_INCREMENT NOT NULL');
        $this->addSql('ALTER TABLE teach_course_videos CHANGE id id INT AUTO_INCREMENT NOT NULL');
        $this->addSql('ALTER TABLE base_backup CHANGE id id INT AUTO_INCREMENT NOT NULL');
        $this->addSql('ALTER TABLE jw_classes CHANGE id id INT AUTO_INCREMENT NOT NULL');
        $this->addSql('ALTER TABLE teach_course_teachers CHANGE id id INT AUTO_INCREMENT NOT NULL');
        $this->addSql('ALTER TABLE jw_classes_teachers CHANGE id id INT AUTO_INCREMENT NOT NULL');
        $this->addSql('ALTER TABLE teach_course_materials CHANGE id id INT AUTO_INCREMENT NOT NULL');
        $this->addSql('ALTER TABLE teach_course_homework CHANGE id id INT AUTO_INCREMENT NOT NULL');
        $this->addSql('ALTER TABLE teach_brand CHANGE id id INT AUTO_INCREMENT NOT NULL');
        $this->addSql('ALTER TABLE teach_study_plan CHANGE id id INT AUTO_INCREMENT NOT NULL');
        $this->addSql('ALTER TABLE teach_course_chapter CHANGE id id INT AUTO_INCREMENT NOT NULL');
        $this->addSql('ALTER TABLE base_option CHANGE id id INT AUTO_INCREMENT NOT NULL');
        $this->addSql('ALTER TABLE jw_school CHANGE id id INT AUTO_INCREMENT NOT NULL');
        $this->addSql('ALTER TABLE jw_teacher CHANGE id id INT AUTO_INCREMENT NOT NULL');
        $this->addSql('ALTER TABLE teach_products CHANGE id id INT AUTO_INCREMENT NOT NULL');
        $this->addSql('ALTER TABLE base_user CHANGE uuid uuid CHAR(36) NOT NULL COMMENT \'唯一码(DC2Type:guid)\'');
        $this->addSql('ALTER TABLE teach_course_test CHANGE id id INT AUTO_INCREMENT NOT NULL');
        $this->addSql('ALTER TABLE teach_course CHANGE id id INT AUTO_INCREMENT NOT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE base_backup CHANGE id id INT UNSIGNED AUTO_INCREMENT NOT NULL');
        $this->addSql('ALTER TABLE base_option CHANGE id id INT UNSIGNED AUTO_INCREMENT NOT NULL');
        $this->addSql('ALTER TABLE base_option_group CHANGE id id INT UNSIGNED AUTO_INCREMENT NOT NULL');
        $this->addSql('ALTER TABLE base_user CHANGE uuid uuid CHAR(36) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci` COMMENT \'(DC2Type:guid)\'');
        $this->addSql('ALTER TABLE jw_classes CHANGE id id INT UNSIGNED AUTO_INCREMENT NOT NULL');
        $this->addSql('ALTER TABLE jw_classes_members CHANGE id id INT UNSIGNED AUTO_INCREMENT NOT NULL');
        $this->addSql('ALTER TABLE jw_classes_teachers CHANGE id id INT UNSIGNED AUTO_INCREMENT NOT NULL');
        $this->addSql('ALTER TABLE jw_school CHANGE id id INT UNSIGNED AUTO_INCREMENT NOT NULL');
        $this->addSql('ALTER TABLE jw_teacher CHANGE id id INT UNSIGNED AUTO_INCREMENT NOT NULL');
        $this->addSql('ALTER TABLE teach_brand CHANGE id id INT UNSIGNED AUTO_INCREMENT NOT NULL');
        $this->addSql('ALTER TABLE teach_category CHANGE id id INT UNSIGNED AUTO_INCREMENT NOT NULL');
        $this->addSql('ALTER TABLE teach_course CHANGE id id INT UNSIGNED AUTO_INCREMENT NOT NULL');
        $this->addSql('ALTER TABLE teach_course_chapter CHANGE id id INT UNSIGNED AUTO_INCREMENT NOT NULL');
        $this->addSql('ALTER TABLE teach_course_homework CHANGE id id INT UNSIGNED AUTO_INCREMENT NOT NULL');
        $this->addSql('ALTER TABLE teach_course_materials CHANGE id id INT UNSIGNED AUTO_INCREMENT NOT NULL');
        $this->addSql('ALTER TABLE teach_course_teachers CHANGE id id INT UNSIGNED AUTO_INCREMENT NOT NULL');
        $this->addSql('ALTER TABLE teach_course_test CHANGE id id INT UNSIGNED AUTO_INCREMENT NOT NULL');
        $this->addSql('ALTER TABLE teach_course_videos CHANGE id id INT UNSIGNED AUTO_INCREMENT NOT NULL');
        $this->addSql('ALTER TABLE teach_products CHANGE id id INT UNSIGNED AUTO_INCREMENT NOT NULL');
        $this->addSql('ALTER TABLE teach_study_plan CHANGE id id INT UNSIGNED AUTO_INCREMENT NOT NULL');
        $this->addSql('ALTER TABLE teach_study_plan_sub CHANGE id id INT UNSIGNED AUTO_INCREMENT NOT NULL');
    }
}
