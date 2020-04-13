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
     * @ORM\Column(name="type", type="boolean", nullable=true, options={"default"="1","comment"="1-直播,2-录播,3-混合"})
     */
    private $type = 1;

    /**
     * @var bool|null
     *
     * @ORM\Column(name="teaching_method", type="boolean", nullable=true, options={"comment"="授课方式 1.面授 2.直播 4.录播 5.面授+直播 6.直播+录播"})
     */
    private $teachingMethod;

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
     * @ORM\Column(name="is_show", type="boolean", nullable=true, options={"comment"="是否上架,0-下架,1-上架"})
     */
    private $isShow = '0';

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

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getGoodsName(): ?string
    {
        return $this->goodsName;
    }

    public function setGoodsName(?string $goodsName): self
    {
        $this->goodsName = $goodsName;

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

    public function getType(): ?bool
    {
        return $this->type;
    }

    public function setType(?bool $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getTeachingMethod(): ?bool
    {
        return $this->teachingMethod;
    }

    public function setTeachingMethod(?bool $teachingMethod): self
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

    public function getIsShow(): ?bool
    {
        return $this->isShow;
    }

    public function setIsShow(?bool $isShow): self
    {
        $this->isShow = $isShow;

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

    public function getGroupType(): ?bool
    {
        return $this->groupType;
    }

    public function setGroupType(?bool $groupType): self
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
