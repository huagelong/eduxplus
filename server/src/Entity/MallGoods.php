<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * MallGoods
 *
 * @ORM\Table(name="mall_goods")
 * @Gedmo\SoftDeleteable(fieldName="deletedAt", timeAware=false, hardDelete=true)
 * @ORM\Entity(repositoryClass="App\Repository\MallGoodsRepository")
 */
class MallGoods
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var string|null
     *
     * @ORM\Column(name="goods_name", type="string", length=120, nullable=true, options={"comment"="课程名称"})
     */
    private $goodsName;

    /**
     * @var string|null
     *
     * @ORM\Column(name="subhead", type="string", length=250, nullable=true, options={"comment"="副标题"})
     */
    private $subhead;

    /**
     * @var int|null
     *
     * @ORM\Column(name="category_id", type="integer", nullable=true, options={"comment"="类目id"})
     */
    private $categoryId;

    /**
     * @var int|null
     *
     * @ORM\Column(name="first_category_id", type="integer", nullable=true, options={"comment"="品类id"})
     */
    private $firstCategoryId;

    /**
     * @var int|null
     *
     * @ORM\Column(name="product_id", type="integer", nullable=true, options={"comment"="产品id"})
     */
    private $productId;

    /**
     * @var bool|null
     *
     * @ORM\Column(name="teaching_method", type="boolean", nullable=true, options={"comment"="授课方式 1.面授 2.直播 3.录播 4.面授+直播 5.直播+录播 6.录播+面授 7.直播+录播+面授"})
     */
    private $teachingMethod=3;

    /**
     * @var string|null
     *
     * @ORM\Column(name="teaching_teacher", type="string", length=250, nullable=true, options={"comment"="授课教师,多个"})
     */
    private $teachingTeacher;

    /**
     * @var int|null
     *
     * @ORM\Column(name="course_hour", type="integer", nullable=true, options={"comment"="课时，乘以10"})
     */
    private $courseHour = '0';

    /**
     * @var int|null
     *
     * @ORM\Column(name="course_count", type="integer", nullable=true, options={"comment"="课次数"})
     */
    private $courseCount = '0';

    /**
     * @var int|null
     *
     * @ORM\Column(name="market_price", type="integer", nullable=true, options={"comment"="成本价,乘以100"})
     */
    private $marketPrice = '0';

    /**
     * @var int|null
     *
     * @ORM\Column(name="shop_price", type="integer", nullable=true, options={"comment"="售价,乘以100"})
     */
    private $shopPrice = '0';

    /**
     * @var int|null
     *
     * @ORM\Column(name="buy_number", type="integer", nullable=true, options={"comment"="购买人数"})
     */
    private $buyNumber = '0';

    /**
     * @var string|null
     *
     * @ORM\Column(name="goods_img", type="string", length=250, nullable=true, options={"comment"="商品海报"})
     */
    private $goodsImg;

    /**
     * @var string|null
     *
     * @ORM\Column(name="goods_small_img", type="string", length=250, nullable=true, options={"comment"="商品默认小图对应的标签"})
     */
    private $goodsSmallImg;

    /**
     * @var bool|null
     *
     * @ORM\Column(name="status", type="boolean", nullable=true, options={"comment"="是否上架,0-下架,1-上架"})
     */
    private $status = '0';

    /**
     * @var int|null
     *
     * @ORM\Column(name="creater_uid", type="integer", nullable=true, options={"comment"="后台创建人"})
     */
    private $createrUid = '0';

    /**
     * @var int|null
     *
     * @ORM\Column(name="sort", type="integer", nullable=true, options={"comment"="排序"})
     */
    private $sort = '0';

    /**
     * @var bool
     *
     * @ORM\Column(name="is_group", type="boolean", nullable=false, options={"comment"="是否是组合0：否，1：是"})
     */
    private $isGroup = '0';

    /**
     * @var bool|null
     *
     * @ORM\Column(name="group_type", type="boolean", nullable=true, options={"comment"="1-可选,2-全部"})
     */
    private $groupType = '0';

    /**
     * @var int
     *
     * @ORM\Column(name="agreement_id", type="integer", nullable=false, options={"unsigned"=true,"comment"="协议id"})
     */
    private $agreementId = '0';

    /**
     * @var int|null
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(name="created_at", type="datetime", nullable=true)
     */
    private $createdAt;

    /**
     * @var int|null
     * @Gedmo\Timestampable(on="update")
     * @ORM\Column(name="updated_at", type="datetime", nullable=true)
     */
    private $updatedAt;

    /**
     * @ORM\Column(name="deleted_at", type="datetime", nullable=true)
     */
    private $deletedAt;



}
