<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200413083658 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE teach_brand');
        $this->addSql('ALTER TABLE teach_category DROP brand_id');
        $this->addSql('ALTER TABLE mall_goods ADD category_id INT DEFAULT NULL COMMENT \'类目id\', DROP cate_id, CHANGE brand_id first_category_id INT DEFAULT NULL COMMENT \'品类id\'');
        $this->addSql('ALTER TABLE teach_products ADD category_id INT DEFAULT NULL COMMENT \'类目id\', DROP cate_id');
        $this->addSql('ALTER TABLE teach_course CHANGE cate_id category_id INT DEFAULT NULL COMMENT \'类目id\', CHANGE brand_id first_category_id INT DEFAULT NULL COMMENT \'品类id\'');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE teach_brand (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(90) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci` COMMENT \'品类名称\', is_show TINYINT(1) DEFAULT \'1\' NOT NULL COMMENT \'是否显示\', sort INT DEFAULT NULL COMMENT \'排序\', created_at DATETIME DEFAULT NULL, updated_at DATETIME DEFAULT NULL, deleted_at DATETIME DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE mall_goods ADD cate_id INT DEFAULT NULL COMMENT \'分类id\', DROP category_id, CHANGE first_category_id brand_id INT DEFAULT NULL COMMENT \'品类id\'');
        $this->addSql('ALTER TABLE teach_category ADD brand_id INT DEFAULT NULL COMMENT \'品类id\'');
        $this->addSql('ALTER TABLE teach_course CHANGE category_id cate_id INT DEFAULT NULL COMMENT \'类目id\', CHANGE first_category_id brand_id INT DEFAULT NULL COMMENT \'品类id\'');
        $this->addSql('ALTER TABLE teach_products ADD cate_id INT DEFAULT NULL COMMENT \'分类id\', DROP category_id');
    }
}
