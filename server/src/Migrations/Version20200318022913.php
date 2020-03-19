<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200318022913 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP INDEX UNIQ_1BF018B989E42CF6 ON base_user');
        $this->addSql('ALTER TABLE base_user CHANGE app_token api_token VARCHAR(50) DEFAULT NULL COMMENT \'api token\'');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_1BF018B97BA2F5EB ON base_user (api_token)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP INDEX UNIQ_1BF018B97BA2F5EB ON base_user');
        $this->addSql('ALTER TABLE base_user CHANGE api_token app_token VARCHAR(50) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci` COMMENT \'api token\'');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_1BF018B989E42CF6 ON base_user (app_token)');
    }
}
