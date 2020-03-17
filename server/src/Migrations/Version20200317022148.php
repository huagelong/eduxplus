<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200317022148 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE base_user (id INT NOT NULL, uuid CHAR(36) DEFAULT NULL COMMENT \'唯一码(DC2Type:guid)\', mobile VARCHAR(12) NOT NULL, full_name VARCHAR(100) DEFAULT NULL COMMENT \'姓名\', display_name VARCHAR(100) DEFAULT NULL COMMENT \'昵称\', face_img VARCHAR(250) DEFAULT NULL COMMENT \'人物头像\', birthday VARCHAR(10) DEFAULT NULL COMMENT \'生日\', sex TINYINT(1) DEFAULT NULL COMMENT \'1-男,2-女\', report_uid INT DEFAULT 1 COMMENT \'汇报上级\', is_lock TINYINT(1) DEFAULT NULL COMMENT \'是否被锁定,1-是，0-否\', reg_source VARCHAR(11) DEFAULT NULL COMMENT \'pc,ios,android\', roles JSON NOT NULL, password VARCHAR(255) NOT NULL, password_change_date INT DEFAULT NULL, created_at DATETIME DEFAULT NULL, updated_at DATETIME DEFAULT NULL, deleted_at DATETIME DEFAULT NULL, UNIQUE INDEX UNIQ_1BF018B9D17F50A6 (uuid), UNIQUE INDEX UNIQ_1BF018B93C7323E0 (mobile), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE base_user');
    }
}
