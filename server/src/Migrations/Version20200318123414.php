<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200318123414 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP INDEX UNIQ_1BF018B97BA2F5EB ON base_user');
        $this->addSql('ALTER TABLE base_user ADD app_token VARCHAR(50) DEFAULT NULL COMMENT \'ios,android token\', ADD html_token VARCHAR(50) DEFAULT NULL COMMENT \'m站，pc站 token\', ADD admin_token VARCHAR(50) DEFAULT NULL COMMENT \'后台 token\', DROP api_token');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_1BF018B989E42CF6 ON base_user (app_token)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_1BF018B9EFC0C1D4 ON base_user (html_token)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_1BF018B9FF1D5AC1 ON base_user (admin_token)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP INDEX UNIQ_1BF018B989E42CF6 ON base_user');
        $this->addSql('DROP INDEX UNIQ_1BF018B9EFC0C1D4 ON base_user');
        $this->addSql('DROP INDEX UNIQ_1BF018B9FF1D5AC1 ON base_user');
        $this->addSql('ALTER TABLE base_user ADD api_token VARCHAR(50) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci` COMMENT \'api token\', DROP app_token, DROP html_token, DROP admin_token');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_1BF018B97BA2F5EB ON base_user (api_token)');
    }
}
