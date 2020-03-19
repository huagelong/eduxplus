<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200318022448 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE base_user ADD app_token VARCHAR(50) DEFAULT NULL COMMENT \'api token\', DROP is_enabled, CHANGE roles roles JSON NOT NULL COMMENT \'角色\', CHANGE password password VARCHAR(255) NOT NULL COMMENT \'密码\', CHANGE password_change_date password_change_date INT DEFAULT NULL COMMENT \'密码修改时间\'');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_1BF018B989E42CF6 ON base_user (app_token)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP INDEX UNIQ_1BF018B989E42CF6 ON base_user');
        $this->addSql('ALTER TABLE base_user ADD is_enabled TINYINT(1) DEFAULT NULL COMMENT \'admin账号是否启用,1-是，0-否\', DROP app_token, CHANGE roles roles JSON NOT NULL, CHANGE password password VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE password_change_date password_change_date INT DEFAULT NULL');
    }
}
