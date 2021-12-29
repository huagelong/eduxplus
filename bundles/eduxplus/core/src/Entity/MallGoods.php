<?php

namespace Eduxplus\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * MallGoods
 *
 * @ORM\Table(name="mall_goods")
 * @Gedmo\SoftDeleteable(fieldName="deletedAt", timeAware=false, hardDelete=true)
 * @ORM\Entity(repositoryClass="Eduxplus\CoreBundle\Repository\MallGoodsRepository")
 */
class MallGoods
{
    /**
     * @var int
     *
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="CUSTOM")
     * @ORM\CustomIdGenerator(class="Eduxplus\CoreBundle\Doctrine\Generator\SnowflakeGenerator")
     * @ORM\Column(type="bigint", unique=true)
     */
    private $id;

    /**
     * @ORM\Column(name="uuid", type="guid", unique=true,nullable=false, options={"comment"="唯一码"})
     */
    private $uuid;

    /**
     * @var string|null
     *
     * @ORM\Column(name="name", type="string", length=120, nullable=true, options={"comment"="课程名称"})
     */
    private $name;

    /**
     * @var string|null
     *
     * @ORM\Column(name="subhead", type="string", length=250, nullable=true, options={"comment"="副标题"})
     */
    private $subhead = '';

    /**
     * @var string|null
     *
     * @ORM\Column(name="alias_name", type="string", length=250, nullable=true, options={"comment"="别名"})
     */
    private $aliasName;

    /**
     * @var int|null
     *
     * @ORM\Column(name="category_id", type="bigint", nullable=true, options={"comment"="类目id"})
     */
    private $categoryId;

    /**
     * @var int|null
     *
     * @ORM\Column(name="first_category_id", type="bigint", nullable=true, options={"comment"="品类id"})
     */
    private $firstCategoryId;

    /**
     * @var int|null
     *
     * @ORM\Column(name="product_id", type="bigint", nullable=true, options={"comment"="产品id"})
     */
    private $productId;

    /**
     * @var int|null
     *
     * @ORM\Column(name="top_value", type="bigint", nullable=true, options={"comment"="热门课程"})
     */
    private $topValue;

    /**
     * @var int|null
     *
     * @ORM\Column(name="recommend_value", type="bigint", nullable=true, options={"comment"="推荐课程"})
     */
    private $recommendValue;

    /**
     * @var int|null
     *
     * @ORM\Column(name="teaching_method", type="bigint", nullable=true, options={"comment"="授课方式 1.面授 2.直播 3.点播 4.面授+直播 5.直播+点播 6.点播+面授 7.直播+点播+面授"})
     */
    private $teachingMethod;

    /**
     * @var string|null
     *
     * @ORM\Column(name="teaching_teacher", type="string", length=250, nullable=true, options={"comment"="授课教师,多个,json格式"})
     */
    private $teachingTeacher;


    /**
     * @var string|null
     *
     * @ORM\Column(name="tags", type="string", length=250, nullable=true, options={"comment"="标签，用,隔开"})
     */
    private $tags;

    /**
     * @var string|null
     *
     * @ORM\Column(name="seo_descr", type="string", length=250, nullable=true, options={"comment"="seo描述"})
     */
    private $seoDescr;


    /**
     * @var string|null
     *
     * @ORM\Column(name="seo_keyword", type="string", length=250, nullable=true, options={"comment"="seo关键字"})
     */
    private $seoKeyWord;

    /**
     * @var int|null
     *
     * @ORM\Column(name="course_hour", type="bigint", nullable=true, options={"comment"="课时，乘以10"})
     */
    private $courseHour = '0';

    /**
     * @var int|null
     *
     * @ORM\Column(name="course_count", type="bigint", nullable=true, options={"comment"="课次数"})
     */
    private $courseCount = '0';

    /**
     * @var int|null
     *
     * @ORM\Column(name="market_price", type="bigint", nullable=true, options={"comment"="成本价,乘以100"})
     */
    private $marketPrice = '0';

    /**
     * @var int|null
     *
     * @ORM\Column(name="shop_price", type="bigint", nullable=true, options={"comment"="售价,乘以100"})
     */
    private $shopPrice = '0';

    /**
     * @var int|null
     *
     * @ORM\Column(name="buy_number", type="bigint", nullable=true, options={"comment"="购买人数"})
     */
    private $buyNumber = '0';

    /**
     * @var int|null
     *
     * @ORM\Column(name="buy_number_false", type="bigint", nullable=true, options={"comment"="购买人数真假数据之和"})
     */
    private $buyNumberFalse = '0';

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
     * @ORM\Column(name="creater_uid", type="bigint", nullable=true, options={"comment"="后台创建人"})
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
     * @var int|null
     *
     * @ORM\Column(name="group_type", type="bigint", nullable=true, options={"comment"="0-未知，1-可选,2-全部"})
     */
    private $groupType = '0';

    /**
     * @var int
     *
     * @ORM\Column(name="agreement_id", type="bigint", nullable=false, options={"unsigned"=true,"comment"="协议id"})
     */
    private $agreementId = '0';

    /**
     * @var int
     *
     * @ORM\Column(name="good_type", type="bigint", nullable=false, options={"unsigned"=true,"comment"="产品类型,1-视频课程,2-试卷"})
     */
    private $goodType = '1';


    /**
     * @var string|null
     *
     * @ORM\Column(name="parameter1", type="string", length=250, nullable=true, options={"comment"="扩展参数1"})
     */
    private $parameter1;

    /**
     * @var string|null
     *
     * @ORM\Column(name="parameter2", type="string", length=250, nullable=true, options={"comment"="扩展参数2"})
     */
    private $parameter2;


    /**
     * @var string|null
     *
     * @ORM\Column(name="parameter3", type="string", length=250, nullable=true, options={"comment"="扩展参数3"})
     */
    private $parameter3;


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

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUuid(): ?string
    {
        return $this->uuid;
    }

    public function setUuid(string $uuid): self
    {
        $this->uuid = $uuid;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getSubhead(): ?string
    {
        return $this->subhead;
    }

    public function setSubhead(?string $subhead): self
    {
        $this->subhead = $subhead;

        return $this;
    }

    public function getAliasName(): ?string
    {
        return $this->aliasName;
    }

    public function setAliasName(?string $aliasName): self
    {
        $this->aliasName = $aliasName;

        return $this;
    }

    public function getCategoryId(): ?int
    {
        return $this->categoryId;
    }

    public function setCategoryId(?int $categoryId): self
    {
        $this->categoryId = $categoryId;

        return $this;
    }

    public function getFirstCategoryId(): ?int
    {
        return $this->firstCategoryId;
    }

    public function setFirstCategoryId(?int $firstCategoryId): self
    {
        $this->firstCategoryId = $firstCategoryId;

        return $this;
    }

    public function getProductId(): ?int
    {
        return $this->productId;
    }

    public function setProductId(?int $productId): self
    {
        $this->productId = $productId;

        return $this;
    }

    public function getTopValue(): ?int
    {
        return $this->topValue;
    }

    public function setTopValue(?int $topValue): self
    {
        $this->topValue = $topValue;

        return $this;
    }

    public function getRecommendValue(): ?int
    {
        return $this->recommendValue;
    }

    public function setRecommendValue(?int $recommendValue): self
    {
        $this->recommendValue = $recommendValue;

        return $this;
    }

    public function getTeachingMethod(): ?int
    {
        return $this->teachingMethod;
    }

    public function setTeachingMethod(?int $teachingMethod): self
    {
        $this->teachingMethod = $teachingMethod;

        return $this;
    }

    public function getTeachingTeacher(): ?string
    {
        return $this->teachingTeacher;
    }

    public function setTeachingTeacher(?string $teachingTeacher): self
    {
        $this->teachingTeacher = $teachingTeacher;

        return $this;
    }

    public function getTags(): ?string
    {
        return $this->tags;
    }

    public function setTags(?string $tags): self
    {
        $this->tags = $tags;

        return $this;
    }

    public function getSeoDescr(): ?string
    {
        return $this->seoDescr;
    }

    public function setSeoDescr(?string $seoDescr): self
    {
        $this->seoDescr = $seoDescr;

        return $this;
    }

    public function getSeoKeyWord(): ?string
    {
        return $this->seoKeyWord;
    }

    public function setSeoKeyWord(?string $seoKeyWord): self
    {
        $this->seoKeyWord = $seoKeyWord;

        return $this;
    }

    public function getCourseHour(): ?int
    {
        return $this->courseHour;
    }

    public function setCourseHour(?int $courseHour): self
    {
        $this->courseHour = $courseHour;

        return $this;
    }

    public function getCourseCount(): ?int
    {
        return $this->courseCount;
    }

    public function setCourseCount(?int $courseCount): self
    {
        $this->courseCount = $courseCount;

        return $this;
    }

    public function getMarketPrice(): ?int
    {
        return $this->marketPrice;
    }

    public function setMarketPrice(?int $marketPrice): self
    {
        $this->marketPrice = $marketPrice;

        return $this;
    }

    public function getShopPrice(): ?int
    {
        return $this->shopPrice;
    }

    public function setShopPrice(?int $shopPrice): self
    {
        $this->shopPrice = $shopPrice;

        return $this;
    }

    public function getBuyNumber(): ?int
    {
        return $this->buyNumber;
    }

    public function setBuyNumber(?int $buyNumber): self
    {
        $this->buyNumber = $buyNumber;

        return $this;
    }

    public function getBuyNumberFalse(): ?int
    {
        return $this->buyNumberFalse;
    }

    public function setBuyNumberFalse(?int $buyNumberFalse): self
    {
        $this->buyNumberFalse = $buyNumberFalse;

        return $this;
    }

    public function getGoodsImg(): ?string
    {
        return $this->goodsImg;
    }

    public function setGoodsImg(?string $goodsImg): self
    {
        $this->goodsImg = $goodsImg;

        return $this;
    }

    public function getGoodsSmallImg(): ?string
    {
        return $this->goodsSmallImg;
    }

    public function setGoodsSmallImg(?string $goodsSmallImg): self
    {
        $this->goodsSmallImg = $goodsSmallImg;

        return $this;
    }

    public function getStatus(): ?bool
    {
        return $this->status;
    }

    public function setStatus(?bool $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function getCreaterUid(): ?int
    {
        return $this->createrUid;
    }

    public function setCreaterUid(?int $createrUid): self
    {
        $this->createrUid = $createrUid;

        return $this;
    }

    public function getSort(): ?int
    {
        return $this->sort;
    }

    public function setSort(?int $sort): self
    {
        $this->sort = $sort;

        return $this;
    }

    public function getIsGroup(): ?bool
    {
        return $this->isGroup;
    }

    public function setIsGroup(bool $isGroup): self
    {
        $this->isGroup = $isGroup;

        return $this;
    }

    public function getGroupType(): ?int
    {
        return $this->groupType;
    }

    public function setGroupType(?int $groupType): self
    {
        $this->groupType = $groupType;

        return $this;
    }

    public function getAgreementId(): ?int
    {
        return $this->agreementId;
    }

    public function setAgreementId(int $agreementId): self
    {
        $this->agreementId = $agreementId;

        return $this;
    }

    public function getGoodType(): ?int
    {
        return $this->goodType;
    }

    public function setGoodType(int $goodType): self
    {
        $this->goodType = $goodType;

        return $this;
    }

    public function getParameter1(): ?string
    {
        return $this->parameter1;
    }

    public function setParameter1(?string $parameter1): self
    {
        $this->parameter1 = $parameter1;

        return $this;
    }

    public function getParameter2(): ?string
    {
        return $this->parameter2;
    }

    public function setParameter2(?string $parameter2): self
    {
        $this->parameter2 = $parameter2;

        return $this;
    }

    public function getParameter3(): ?string
    {
        return $this->parameter3;
    }

    public function setParameter3(?string $parameter3): self
    {
        $this->parameter3 = $parameter3;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(?\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(?\DateTimeInterface $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    public function getDeletedAt(): ?\DateTimeInterface
    {
        return $this->deletedAt;
    }

    public function setDeletedAt(?\DateTimeInterface $deletedAt): self
    {
        $this->deletedAt = $deletedAt;

        return $this;
    }


}
