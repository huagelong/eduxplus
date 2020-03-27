<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200327105004 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE base_role_menu (id INT AUTO_INCREMENT NOT NULL, menu_id INT NOT NULL COMMENT \'导航id\', role_id INT NOT NULL COMMENT \'角色id\', access INT DEFAULT 0 NOT NULL COMMENT \'权限0-查看，1-增加，2-删除，3-修改\', deleted_at DATETIME DEFAULT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE base_menu (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(88) NOT NULL COMMENT \'导航名称\', url VARCHAR(255) DEFAULT NULL COMMENT \'网址\', pid INT DEFAULT 0 NOT NULL COMMENT \'父节点id\', sort INT DEFAULT 0 NOT NULL COMMENT \'排序\', style VARCHAR(88) NOT NULL COMMENT \'导航样式\', is_show TINYINT(1) DEFAULT \'0\' NOT NULL COMMENT \'是否显示\', is_blank TINYINT(1) DEFAULT \'0\' NOT NULL COMMENT \'是否外部导航\', is_lock TINYINT(1) DEFAULT \'0\' NOT NULL COMMENT \'是否锁定，锁定后不能修改\', descr VARCHAR(255) DEFAULT NULL, deleted_at DATETIME DEFAULT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, UNIQUE INDEX UNIQ_EB66F4635E237E06 (name), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE base_role ADD aliases VARCHAR(50) DEFAULT NULL COMMENT \'别名\', ADD is_lock TINYINT(1) DEFAULT NULL COMMENT \'是否被锁定,1-是，0-否\', CHANGE name name VARCHAR(50) DEFAULT NULL COMMENT \'角色名称\'');
        $this->addSql('ALTER TABLE base_access ADD url VARCHAR(255) DEFAULT NULL COMMENT \'网址\', ADD descr VARCHAR(255) DEFAULT NULL, DROP role_id, CHANGE access access INT DEFAULT 0 NOT NULL COMMENT \'权限0-查看，1-增加，2-删除，3-修改\'');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE base_role_menu');
        $this->addSql('DROP TABLE base_menu');
        $this->addSql('ALTER TABLE base_access ADD role_id INT DEFAULT NULL, DROP url, DROP descr, CHANGE access access VARCHAR(100) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE base_role DROP aliases, DROP is_lock, CHANGE name name VARCHAR(50) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci` COMMENT \'权限名称\'');
    }
}
