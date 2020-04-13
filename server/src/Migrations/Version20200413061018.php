<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200413061018 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE teach_category DROP is_delete');
        $this->addSql('ALTER TABLE mall_goods_introduce CHANGE introduce_type introduce_type TINYINT(1) DEFAULT NULL COMMENT \'课程介绍类型,1-班型介绍， 2-特色服务，3-适用人群，4-学习目标，0-图文介绍\'');
        $this->addSql('ALTER TABLE mall_goods CHANGE teaching_method teaching_method TINYINT(1) DEFAULT NULL COMMENT \'授课方式1.面授2.直播3.面授+直播4录播\', CHANGE group_type group_type TINYINT(1) DEFAULT NULL COMMENT \'1-可选,2-全部\'');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE mall_goods CHANGE teaching_method teaching_method TINYINT(1) DEFAULT NULL COMMENT \'授课方式1.面授2.直播3.面授+直播4录播5.VIP\', CHANGE group_type group_type TINYINT(1) DEFAULT NULL COMMENT \'1-选择,2-全部\'');
        $this->addSql('ALTER TABLE mall_goods_introduce CHANGE introduce_type introduce_type TINYINT(1) DEFAULT NULL COMMENT \'课程介绍类型,1-版型介绍， 2-特色服务，3-适用人群，4-学习目标，0-图文介绍\'');
        $this->addSql('ALTER TABLE teach_category ADD is_delete TINYINT(1) DEFAULT NULL COMMENT \'是否删除\'');
    }
}
