<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200409075333 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP INDEX UNIQ_EB66F4635E237E06 ON base_menu');
        $this->addSql('ALTER TABLE test ADD mobile VARCHAR(12) NOT NULL');
        $this->addSql('ALTER TABLE admin_action_log ADD mobile VARCHAR(12) NOT NULL COMMENT \'手机码\'');
        $this->addSql('DROP INDEX UNIQ_1BF018B93C7323E0 ON base_user');
        $this->addSql('ALTER TABLE base_user CHANGE uuid uuid CHAR(36) NOT NULL COMMENT \'唯一码(DC2Type:guid)\', CHANGE mobile mobile VARCHAR(12) NOT NULL COMMENT \'手机码\', CHANGE password password VARCHAR(255) DEFAULT NULL COMMENT \'密码\'');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE admin_action_log DROP mobile');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_EB66F4635E237E06 ON base_menu (name)');
        $this->addSql('ALTER TABLE base_user CHANGE uuid uuid CHAR(36) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci` COMMENT \'唯一码(DC2Type:guid)\', CHANGE mobile mobile VARCHAR(12) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE password password VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci` COMMENT \'密码\'');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_1BF018B93C7323E0 ON base_user (mobile)');
        $this->addSql('ALTER TABLE test DROP mobile');
    }
}
