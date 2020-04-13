<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200413134859 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE teach_course_chapter ADD study_way TINYINT(1) DEFAULT NULL COMMENT \'学习方式, 1-线上，2-线下，3-混合\', DROP is_del, CHANGE is_free is_free TINYINT(1) DEFAULT NULL COMMENT \'是否免费（试听课），1-是，0-否\', CHANGE fsort sort INT DEFAULT NULL COMMENT \'排序\'');
        $this->addSql('ALTER TABLE jw_school DROP status, CHANGE descr descr TEXT DEFAULT NULL COMMENT \'描述\'');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE jw_school ADD status TINYINT(1) DEFAULT \'1\' COMMENT \'是否有效\', CHANGE descr descr TEXT CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci` COMMENT \'描叙\'');
        $this->addSql('ALTER TABLE teach_course_chapter ADD is_del TINYINT(1) DEFAULT NULL COMMENT \'1-是，0-否\', DROP study_way, CHANGE is_free is_free TINYINT(1) DEFAULT NULL COMMENT \'是否试听课，1-是，0-否\', CHANGE sort fsort INT DEFAULT NULL COMMENT \'排序\'');
    }
}
