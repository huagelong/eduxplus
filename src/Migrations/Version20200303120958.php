<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200303120958 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE admin_action_log (id INT AUTO_INCREMENT NOT NULL, uid INT DEFAULT NULL, route VARCHAR(100) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, ip VARCHAR(20) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, input_data TEXT CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, created_at INT DEFAULT NULL, updated_at INT DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE admin_user_role (id INT AUTO_INCREMENT NOT NULL, uid INT DEFAULT NULL, role_id INT DEFAULT NULL, created_at INT DEFAULT NULL, updated_at INT DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'用户权限\' ');
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE base_access (id INT AUTO_INCREMENT NOT NULL, role_id INT DEFAULT NULL, access VARCHAR(100) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, created_at INT DEFAULT NULL, updated_at INT DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'角色权限表\' ');
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE base_backup (id INT UNSIGNED AUTO_INCREMENT NOT NULL, name VARCHAR(50) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, tables TEXT CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, path VARCHAR(100) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci` COMMENT \'备份路径\', status TINYINT(1) DEFAULT \'0\' COMMENT \'0-备份失败,1-备份中，2-备份成功\', note TEXT CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, yun_file_id INT DEFAULT NULL, created_at INT DEFAULT NULL, updated_at INT DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE base_file (id INT UNSIGNED AUTO_INCREMENT NOT NULL COMMENT \'上传文件ID\', group_id INT UNSIGNED DEFAULT 0 NOT NULL COMMENT \'上传文件组ID\', uid INT UNSIGNED DEFAULT 0 NOT NULL COMMENT \'上传人ID\', obj VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'\' NOT NULL COLLATE `utf8mb4_unicode_ci` COMMENT \'文件对象\', originalz_name VARCHAR(100) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci` COMMENT \'文件原始名称\', mime VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci` COMMENT \'文件MIME\', size INT UNSIGNED DEFAULT 0 NOT NULL COMMENT \'文件大小\', thirdpart_data TEXT CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci` COMMENT \'第三方数据\', created_at INT DEFAULT NULL COMMENT \'上传时间\', updated_at INT DEFAULT NULL COMMENT \'更新时间\', PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE base_file_group (id INT AUTO_INCREMENT NOT NULL COMMENT \'上传文件组ID\', name VARCHAR(100) CHARACTER SET utf8mb4 DEFAULT \'\' NOT NULL COLLATE `utf8mb4_unicode_ci` COMMENT \'上传文件组名称\', code VARCHAR(100) CHARACTER SET utf8mb4 DEFAULT \'\' NOT NULL COLLATE `utf8mb4_unicode_ci` COMMENT \'上传文件组编码\', created_at INT DEFAULT NULL, updated_at INT DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE base_file_used (id INT AUTO_INCREMENT NOT NULL, file_id INT DEFAULT NULL COMMENT \'upload_files id\', target_type VARCHAR(32) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, target_id INT DEFAULT NULL, created_at INT DEFAULT NULL, updated_at INT DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE base_option (id INT UNSIGNED AUTO_INCREMENT NOT NULL, option_name VARCHAR(50) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci` COMMENT \'配置名称\', option_key VARCHAR(50) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci` COMMENT \'配置key\', group_id INT DEFAULT NULL COMMENT \'group id\', option_value TEXT CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci` COMMENT \'配置值\', created_at INT DEFAULT NULL, updated_at INT DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE base_option_group (id INT UNSIGNED AUTO_INCREMENT NOT NULL, group_name VARCHAR(50) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci` COMMENT \'组名称\', group_key VARCHAR(50) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci` COMMENT \'组key\', created_at INT DEFAULT NULL, updated_at INT DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE base_role (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(50) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci` COMMENT \'权限名称\', descr TEXT CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci` COMMENT \'描述\', created_at INT DEFAULT NULL, updated_at INT DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'角色表\' ');
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE base_user (id INT AUTO_INCREMENT NOT NULL, mobile VARCHAR(12) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, display_name VARCHAR(100) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci` COMMENT \'昵称\', full_name VARCHAR(100) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci` COMMENT \'姓名\', face_img VARCHAR(250) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci` COMMENT \'人物头像\', birthday DATE DEFAULT NULL COMMENT \'生日\', sex TINYINT(1) DEFAULT NULL COMMENT \'1-男,2-女\', passwd VARCHAR(100) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, is_admin TINYINT(1) DEFAULT \'0\' NOT NULL COMMENT \'是否是管理员账号\', is_lock TINYINT(1) DEFAULT \'0\' COMMENT \'是否被锁定,1-是，0-否\', last_login_time INT DEFAULT NULL, report_uid INT DEFAULT 1 COMMENT \'汇报上级\', reg_source VARCHAR(11) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci` COMMENT \'pc,ios,android\', pc_login_token VARCHAR(100) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, app_login_token VARCHAR(100) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, updated_at INT DEFAULT NULL, created_at INT DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE jw_classes (id INT UNSIGNED AUTO_INCREMENT NOT NULL, name VARCHAR(100) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci` COMMENT \'班级名称\', classes_no VARCHAR(100) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci` COMMENT \'班级编码\', study_plan_id INT DEFAULT NULL COMMENT \'学习计划\', max_member_number INT DEFAULT NULL COMMENT \'最大班级人数，自动分班用\', product_id INT DEFAULT NULL COMMENT \'产品id\', created_at INT DEFAULT NULL, updated_at INT DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE jw_classes_members (id INT UNSIGNED AUTO_INCREMENT NOT NULL, classes_id INT DEFAULT NULL COMMENT \'班级id\', uid INT DEFAULT NULL, type TINYINT(1) DEFAULT NULL COMMENT \'1-在学学员，2-退学学员\', created_at INT DEFAULT NULL, updated_at INT DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE jw_classes_teachers (id INT UNSIGNED AUTO_INCREMENT NOT NULL, classes_id INT DEFAULT NULL COMMENT \'班级id\', teacher_id INT DEFAULT NULL COMMENT \'老师id\', teacher_type TINYINT(1) DEFAULT NULL COMMENT \'1-班主任\', created_at INT DEFAULT NULL, updated_at INT DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE jw_school (id INT UNSIGNED AUTO_INCREMENT NOT NULL, name VARCHAR(100) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci` COMMENT \'学校名称\', address VARCHAR(200) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci` COMMENT \'学校地址\', descr TEXT CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci` COMMENT \'描叙\', state_code VARCHAR(64) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci` COMMENT \'省份\', city_id INT DEFAULT NULL COMMENT \'城市id\', linkin VARCHAR(100) CHARACTER SET utf8mb4 DEFAULT \'\' COLLATE `utf8mb4_unicode_ci` COMMENT \'联系方式\', status TINYINT(1) DEFAULT \'1\' COMMENT \'是否有效\', created_at INT DEFAULT NULL, updated_at INT DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE jw_teacher (id INT UNSIGNED AUTO_INCREMENT NOT NULL, name VARCHAR(200) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, descr TEXT CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, pinyin VARCHAR(200) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci` COMMENT \'名称拼音\', type TINYINT(1) DEFAULT NULL COMMENT \'1-网课老师，2-分校老师，3-全部\', school_id INT DEFAULT NULL COMMENT \'所属分校\', status TINYINT(1) DEFAULT \'1\' COMMENT \'是否有效\', created_at INT DEFAULT NULL, updated_at INT DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE mall_coupon (id INT UNSIGNED AUTO_INCREMENT NOT NULL COMMENT \'优惠码id\', coupon_group_id INT UNSIGNED NOT NULL COMMENT \'优惠码管理id\', coupon_sn VARCHAR(20) CHARACTER SET utf8mb4 DEFAULT \'\' NOT NULL COLLATE `utf8mb4_unicode_ci` COMMENT \'优惠码编号\', uid INT UNSIGNED DEFAULT NULL COMMENT \'使用会员\', used_time INT UNSIGNED DEFAULT NULL COMMENT \'使用时间\', send_time INT UNSIGNED DEFAULT NULL COMMENT \'发送时间\', coupon_status TINYINT(1) DEFAULT \'0\' NOT NULL COMMENT \'优惠码状态:0未使用,1已使用\', export_status TINYINT(1) DEFAULT \'0\' NOT NULL COMMENT \'导出状态0：不导出1：导出\', created_at INT DEFAULT NULL, updated_at INT DEFAULT NULL, INDEX coupon_manage_id (coupon_group_id), INDEX coupon_status (coupon_status), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'优惠码表\' ');
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE mall_coupon_group (id INT UNSIGNED AUTO_INCREMENT NOT NULL COMMENT \'优惠码组id\', coupon_name VARCHAR(20) CHARACTER SET utf8mb4 DEFAULT \'\' NOT NULL COLLATE `utf8mb4_unicode_ci` COMMENT \'优惠码名称\', coupon_type TINYINT(1) DEFAULT \'0\' NOT NULL COMMENT \'类型:0金额优惠,1折扣优惠\', discount INT UNSIGNED DEFAULT 0 NOT NULL COMMENT \'优惠码折扣(百分数)/优惠码金额(乘以100)\', order_lower_limit INT UNSIGNED DEFAULT 0 NOT NULL COMMENT \'订单下限售价,乘以100\', count_num INT UNSIGNED DEFAULT 0 NOT NULL COMMENT \'发放数量\', used_num INT UNSIGNED DEFAULT 0 NOT NULL COMMENT \'已使用的数量\', expiration_start INT UNSIGNED NOT NULL COMMENT \'开始有效日期\', expiration_end INT UNSIGNED NOT NULL COMMENT \'结束有效日期\', is_show TINYINT(1) DEFAULT \'1\' NOT NULL COMMENT \'上架1，下架0\', create_uid INT UNSIGNED NOT NULL COMMENT \'创建人\', coupon_description VARCHAR(200) CHARACTER SET utf8mb4 DEFAULT \'\' NOT NULL COLLATE `utf8mb4_unicode_ci` COMMENT \'优惠码描述\', category_id VARCHAR(250) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci` COMMENT \'配套产品，分类id\', teaching_method VARCHAR(50) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci` COMMENT \'授课方式,配套班型:1面授课,2直播课,3面授课+直播课,4网课,5VIP\', goods_id VARCHAR(50) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci` COMMENT \'商品id\', created_at INT DEFAULT NULL, updated_at INT DEFAULT NULL, INDEX coupon_type (coupon_type), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'优惠码组表\' ');
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE mall_goods (id INT AUTO_INCREMENT NOT NULL, goods_name VARCHAR(120) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci` COMMENT \'课程名称\', subhead VARCHAR(250) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci` COMMENT \'副标题\', cate_id INT DEFAULT 0 COMMENT \'分类id\', brand_id INT DEFAULT NULL COMMENT \'品类id\', product_id INT DEFAULT NULL COMMENT \'产品id\', study_plan_id INT DEFAULT NULL COMMENT \'学习计划,可为空，如果不为空，直接分班\', type TINYINT(1) DEFAULT \'1\' COMMENT \'1-直播,2-录播,3-混合\', teaching_method TINYINT(1) DEFAULT NULL COMMENT \'授课方式1.面授2.直播3.面授+直播4录播5.VIP\', teaching_teacher VARCHAR(250) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci` COMMENT \'授课教师,多个\', course_hour INT DEFAULT NULL COMMENT \'课时，乘以10\', course_count INT DEFAULT NULL COMMENT \'课次数\', market_price INT DEFAULT NULL COMMENT \'成本价,乘以100\', shop_price INT DEFAULT NULL COMMENT \'售价,乘以100\', buy_number INT DEFAULT NULL COMMENT \'购买人数\', goods_img VARCHAR(250) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci` COMMENT \'商品海报\', recommended_img VARCHAR(250) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci` COMMENT \'推荐图\', goods_small_img VARCHAR(250) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci` COMMENT \'商品默认小图对应的标签\', is_show TINYINT(1) DEFAULT \'0\' COMMENT \'是否上架,0-下家,1-上架\', creater_uid INT DEFAULT NULL COMMENT \'后台创建人\', recommended_position TINYINT(1) DEFAULT \'0\' COMMENT \'5个位置，每个位置只能有一个\', is_group TINYINT(1) DEFAULT \'0\' NOT NULL COMMENT \'是否是组合0：否，1：是\', group_type TINYINT(1) DEFAULT \'0\' COMMENT \'1-选择,2-全部\', agreement_id INT UNSIGNED DEFAULT 0 NOT NULL COMMENT \'协议id\', created_at INT DEFAULT NULL, updated_at INT DEFAULT NULL, INDEX idx_category_id (cate_id), INDEX idx_teaching_method (teaching_method), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE mall_goods_group (id INT AUTO_INCREMENT NOT NULL, goods_id INT DEFAULT NULL COMMENT \'商品id\', name VARCHAR(100) CHARACTER SET utf8mb4 DEFAULT \'\' NOT NULL COLLATE `utf8mb4_unicode_ci` COMMENT \'组名\', group_goods__id VARCHAR(100) CHARACTER SET utf8mb4 DEFAULT \'\' NOT NULL COLLATE `utf8mb4_unicode_ci` COMMENT \'组名下商品id\', created_at INT DEFAULT NULL, updated_at INT DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE mall_goods_introduce (id INT AUTO_INCREMENT NOT NULL, goods_id INT DEFAULT NULL, introduce_type TINYINT(1) DEFAULT \'0\' COMMENT \'课程介绍类型,1-版型介绍， 2-特色服务，3-适用人群，4-学习目标，0-图文介绍\', content TEXT CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci` COMMENT \'课程介绍内容\', created_at INT DEFAULT NULL, updated_at INT DEFAULT NULL, href TEXT CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci` COMMENT \'视频ID\', PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE mall_goods_pay_type (id INT AUTO_INCREMENT NOT NULL, goods_id INT DEFAULT NULL COMMENT \'商品id\', pay_name VARCHAR(50) CHARACTER SET utf8mb4 DEFAULT \'\' NOT NULL COLLATE `utf8mb4_unicode_ci` COMMENT \'支付名称\', pay_price INT DEFAULT 0 NOT NULL COMMENT \'支付价格\', created_at INT DEFAULT NULL, updated_at INT DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE mall_mobile_sms_code (id INT AUTO_INCREMENT NOT NULL, mobile VARCHAR(13) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci` COMMENT \'手机号码\', type VARCHAR(25) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci` COMMENT \'验证码类型\', code VARCHAR(25) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci` COMMENT \'验证码\', created_at INT NOT NULL, updated_at INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE mall_order (id INT UNSIGNED AUTO_INCREMENT NOT NULL COMMENT \'订单表id\', order_no VARCHAR(20) CHARACTER SET utf8mb4 DEFAULT \'\' NOT NULL COLLATE `utf8mb4_unicode_ci` COMMENT \'订单号\', uid INT UNSIGNED NOT NULL COMMENT \'users表的用户id\', goods_id INT UNSIGNED NOT NULL COMMENT \'goods表的商品id\', order_amount INT DEFAULT 0 NOT NULL COMMENT \'订单原价\', discount_amount INT DEFAULT 0 NOT NULL COMMENT \'优惠金额\', order_time INT UNSIGNED DEFAULT 0 NOT NULL COMMENT \'下单时间\', order_status TINYINT(1) DEFAULT \'0\' NOT NULL COMMENT \'订单状态:0待支付,1已支付,2已取消\', user_notes VARCHAR(200) CHARACTER SET utf8mb4 DEFAULT \'\' NOT NULL COLLATE `utf8mb4_unicode_ci` COMMENT \'用户备注\', referer VARCHAR(20) CHARACTER SET utf8mb4 DEFAULT \'CRM后台创建\' NOT NULL COLLATE `utf8mb4_unicode_ci` COMMENT \'订单来源\', coupon_sn VARCHAR(20) CHARACTER SET utf8mb4 DEFAULT \'\' NOT NULL COLLATE `utf8mb4_unicode_ci` COMMENT \'订单使用优惠码编号\', goods_all VARCHAR(100) CHARACTER SET utf8mb4 DEFAULT \'\' NOT NULL COLLATE `utf8mb4_unicode_ci` COMMENT \'多个goodsid\', goods_name VARCHAR(1000) CHARACTER SET utf8mb4 DEFAULT \'\' NOT NULL COLLATE `utf8mb4_unicode_ci` COMMENT \'订单商品名称\', created_at INT DEFAULT NULL, updated_at INT DEFAULT NULL, UNIQUE INDEX order_no (order_no), INDEX order_status (order_status), INDEX order_time (order_time), INDEX user_id (uid), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'订单表\' ');
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE mall_pay (id INT UNSIGNED AUTO_INCREMENT NOT NULL COMMENT \'支付表id\', transaction_id VARCHAR(200) CHARACTER SET utf8mb4 DEFAULT \'\' NOT NULL COLLATE `utf8mb4_unicode_ci` COMMENT \'支付流水号\', order_id INT UNSIGNED NOT NULL COMMENT \'订单表id\', pay_time INT UNSIGNED NOT NULL COMMENT \'支付时间\', payment_type TINYINT(1) DEFAULT \'0\' NOT NULL COMMENT \'支付方式:0支付宝,1微信支付\', pay_status TINYINT(1) DEFAULT \'0\' NOT NULL COMMENT \'付款状态:0支付失败,1支付成功\', amount INT UNSIGNED NOT NULL COMMENT \'乘以100\', updated_at INT DEFAULT NULL, created_at INT DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'支付表\' ');
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE teach_agreement (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(100) CHARACTER SET utf8mb4 DEFAULT \'\' NOT NULL COLLATE `utf8mb4_unicode_ci` COMMENT \'协议名称\', content MEDIUMTEXT CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci` COMMENT \'协议内容\', is_show TINYINT(1) DEFAULT \'1\', created_at INT DEFAULT NULL, updated_at INT DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE teach_brand (id INT UNSIGNED AUTO_INCREMENT NOT NULL, name VARCHAR(90) CHARACTER SET utf8mb4 DEFAULT \'\' NOT NULL COLLATE `utf8mb4_unicode_ci` COMMENT \'品类名称\', is_show TINYINT(1) DEFAULT \'1\' NOT NULL COMMENT \'是否显示\', sort INT DEFAULT NULL COMMENT \'排序\', is_delete TINYINT(1) DEFAULT \'0\' COMMENT \'是否删除\', created_at INT DEFAULT NULL, updated_at INT DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE teach_category (id INT UNSIGNED AUTO_INCREMENT NOT NULL, name VARCHAR(90) CHARACTER SET utf8mb4 DEFAULT \'\' NOT NULL COLLATE `utf8mb4_unicode_ci` COMMENT \'分类名称\', parent_id INT UNSIGNED DEFAULT 0 NOT NULL COMMENT \'父id\', brand_id INT DEFAULT NULL COMMENT \'品类id\', sort INT DEFAULT NULL COMMENT \'排序\', is_delete TINYINT(1) DEFAULT \'0\' COMMENT \'是否删除\', is_show TINYINT(1) DEFAULT \'1\' NOT NULL COMMENT \'是否显示\', created_at INT DEFAULT NULL, updated_at INT DEFAULT NULL, INDEX parent_id (parent_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE teach_course (id INT UNSIGNED AUTO_INCREMENT NOT NULL, name VARCHAR(100) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci` COMMENT \'课程名称\', type TINYINT(1) DEFAULT NULL COMMENT \'1-线上,2-线下,3-混合\', big_img VARCHAR(100) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci` COMMENT \'封面图\', descr VARCHAR(200) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci` COMMENT \'简介\', cate_id INT DEFAULT NULL COMMENT \'类目id\', brand_id INT DEFAULT NULL COMMENT \'品类id\', create_uid INT DEFAULT NULL COMMENT \'创建人uid，自己创建的自己可见\', status TINYINT(1) DEFAULT \'0\' COMMENT \'0-下架，1-上架\', course_hour INT DEFAULT NULL COMMENT \'课时，乘以100\', school_id VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci` COMMENT \'线下分校,id以,分割\', created_at INT DEFAULT NULL, updated_at INT DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE teach_course_chapter (id INT UNSIGNED AUTO_INCREMENT NOT NULL, course_id INT DEFAULT NULL, name VARCHAR(200) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci` COMMENT \'章节名称\', open_time INT DEFAULT NULL COMMENT \'开课时间\', parent_id INT DEFAULT NULL COMMENT \'父章节\', is_free TINYINT(1) DEFAULT \'0\' COMMENT \'1-是，0-否\', is_del TINYINT(1) DEFAULT \'0\' COMMENT \'1-是，0-否\', fsort INT DEFAULT 0 COMMENT \'排序\', created_at INT DEFAULT NULL, updated_at INT DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE teach_course_homework (id INT UNSIGNED AUTO_INCREMENT NOT NULL, course_id INT DEFAULT NULL, chapter_id INT DEFAULT NULL, name INT DEFAULT NULL, test_id INT DEFAULT NULL, created_at INT DEFAULT NULL, updated_at INT DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE teach_course_materials (id INT UNSIGNED AUTO_INCREMENT NOT NULL, course_id INT DEFAULT NULL, chapter_id INT DEFAULT NULL COMMENT \'章节id\', name VARCHAR(100) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci` COMMENT \'资料名称\', path VARCHAR(254) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci` COMMENT \'资料下载路径\', created_at INT DEFAULT NULL, updated_at INT DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE teach_course_teachers (id INT UNSIGNED AUTO_INCREMENT NOT NULL, teacher_id INT DEFAULT NULL COMMENT \'老师id\', course_id INT DEFAULT NULL COMMENT \'课程id\', chapter_id INT DEFAULT NULL COMMENT \'章节id\', created_at INT DEFAULT NULL, updated_at INT DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE teach_course_test (id INT UNSIGNED AUTO_INCREMENT NOT NULL, course_id INT DEFAULT NULL, name VARCHAR(100) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci` COMMENT \'试卷名称\', test_id INT DEFAULT NULL, created_at INT DEFAULT NULL, update_at INT DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE teach_course_videos (id INT UNSIGNED AUTO_INCREMENT NOT NULL, course_id INT DEFAULT NULL, chapter_id INT DEFAULT NULL COMMENT \'章节id\', type TINYINT(1) DEFAULT NULL COMMENT \'1-直播,2-录播\', video_channel TINYINT(1) DEFAULT NULL COMMENT \'1-cc视频，2-百度云\', channel_data TEXT CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci` COMMENT \'渠道数据\', created_at INT DEFAULT NULL, updated_at INT DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE teach_products (id INT UNSIGNED AUTO_INCREMENT NOT NULL, name VARCHAR(100) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, cate_id INT DEFAULT NULL COMMENT \'分类id\', descr TEXT CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci` COMMENT \'简介\', agreement_id INT DEFAULT NULL COMMENT \'协议id\', status TINYINT(1) DEFAULT NULL COMMENT \'0-草稿，2-上架,1-下架\', created_at INT DEFAULT NULL, updated_at INT DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE teach_study_plan (id INT UNSIGNED AUTO_INCREMENT NOT NULL, name VARCHAR(100) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci` COMMENT \'名称\', is_default TINYINT(1) DEFAULT NULL COMMENT \'是否默认计划\', is_block TINYINT(1) DEFAULT NULL COMMENT \'是否挡板\', created_at INT DEFAULT NULL, updated_at INT DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE teach_study_plan_sub (id INT UNSIGNED AUTO_INCREMENT NOT NULL, study_plan_id INT DEFAULT NULL, course_id INT DEFAULT NULL COMMENT \'课程\', created_at INT DEFAULT NULL, updated_at INT DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE admin_action_log');
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE admin_user_role');
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE base_access');
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE base_backup');
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE base_file');
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE base_file_group');
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE base_file_used');
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE base_option');
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE base_option_group');
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE base_role');
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE base_user');
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE jw_classes');
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE jw_classes_members');
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE jw_classes_teachers');
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE jw_school');
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE jw_teacher');
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE mall_coupon');
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE mall_coupon_group');
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE mall_goods');
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE mall_goods_group');
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE mall_goods_introduce');
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE mall_goods_pay_type');
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE mall_mobile_sms_code');
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE mall_order');
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE mall_pay');
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE teach_agreement');
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE teach_brand');
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE teach_category');
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE teach_course');
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE teach_course_chapter');
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE teach_course_homework');
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE teach_course_materials');
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE teach_course_teachers');
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE teach_course_test');
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE teach_course_videos');
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE teach_products');
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE teach_study_plan');
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE teach_study_plan_sub');
    }
}
