<?php

namespace Eduxplus\EduxBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * MallCouponGroup
 *
 * @ORM\Table(name="mall_coupon_group", indexes={@ORM\Index(name="expiration_start_end_idx", columns={"expiration_start", "expiration_end"})})
 * @Gedmo\SoftDeleteable(fieldName="deletedAt", timeAware=false, hardDelete=true)
 * @ORM\Entity(repositoryClass="Eduxplus\EduxBundle\Repository\MallCouponGroupRepository")
 * @ORM\Cache(usage="NONSTRICT_READ_WRITE")
 */
class MallCouponGroup
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
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=20, nullable=false, options={"comment"="优惠码名称"})
     */
    private $name = '';

    /**
     * @var bool
     *
     * @ORM\Column(name="coupon_type", type="bigint",length=1, nullable=false, options={"comment"="类型:1金额优惠,2折扣优惠"})
     */
    private $couponType = '0';

    /**
     * @var int
     *
     * @ORM\Column(name="discount", type="bigint", nullable=false, options={"unsigned"=true,"comment"="优惠码折扣(百分数)/优惠码金额(乘以100)"})
     */
    private $discount = '0';

    /**
     * @var int
     *
     * @ORM\Column(name="has_code", type="bigint", nullable=false, options={"unsigned"=true,"comment"="是否生成优惠码"})
     */
    private $hasCode = '0';

    /**
     * @var int
     *
     * @ORM\Column(name="count_num", type="bigint", nullable=false, options={"unsigned"=true,"comment"="发放数量"})
     */
    private $countNum = '0';


    /**
     * @var int
     *
     * @ORM\Column(name="created_num", type="bigint", nullable=false, options={"unsigned"=true,"comment"="已生成数量"})
     */
    private $createdNum = '0';

    /**
     * @var int
     *
     * @ORM\Column(name="used_num", type="bigint", nullable=false, options={"unsigned"=true,"comment"="已使用的数量"})
     */
    private $usedNum = '0';

    /**
     * @var int
     *
     * @ORM\Column(name="expiration_start", type="bigint", nullable=false, options={"unsigned"=true,"comment"="开始有效日期"})
     */
    private $expirationStart;

    /**
     * @var int
     *
     * @ORM\Column(name="expiration_end", type="bigint", nullable=false, options={"unsigned"=true,"comment"="结束有效日期"})
     */
    private $expirationEnd;

    /**
     * @var bool
     *
     * @ORM\Column(name="status", type="boolean", nullable=false, options={"default"="1","comment"="上架1，下架0"})
     */
    private $status = '1';

    /**
     * @var int
     *
     * @ORM\Column(name="create_uid", type="bigint", nullable=false, options={"unsigned"=true,"comment"="创建人"})
     */
    private $createUid;

    /**
     * @var string
     *
     * @ORM\Column(name="descr", type="string", length=200, nullable=false, options={"comment"="描述"})
     */
    private $descr = '';

    /**
     * @var string|null
     *
     * @ORM\Column(name="category_id", type="string", length=250, nullable=true, options={"comment"="配套产品，分类id"})
     */
    private $categoryId;

    /**
     * @var string|null
     *
     * @ORM\Column(name="teaching_method", type="string", length=50, nullable=true, options={"comment"="授课方式 1.面授 2.直播 3.点播 4.面授+直播 5.直播+点播 6.点播+面授 7.直播+点播+面授"})
     */
    private $teachingMethod;

    /**
     * @var string|null
     *
     * @ORM\Column(name="goods_ids", type="text", length=65535, nullable=true, options={"comment"="商品id,以','隔开"})
     */
    private $goodsIds;

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

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getCouponType(): ?int
    {
        return $this->couponType;
    }

    public function setCouponType(int $couponType): self
    {
        $this->couponType = $couponType;

        return $this;
    }

    public function getDiscount(): ?int
    {
        return $this->discount;
    }

    public function setDiscount(int $discount): self
    {
        $this->discount = $discount;

        return $this;
    }

    public function getHasCode(): ?int
    {
        return $this->hasCode;
    }

    public function setHasCode(int $hasCode): self
    {
        $this->hasCode = $hasCode;

        return $this;
    }

    public function getCountNum(): ?int
    {
        return $this->countNum;
    }

    public function setCountNum(int $countNum): self
    {
        $this->countNum = $countNum;

        return $this;
    }

    public function getCreatedNum(): ?int
    {
        return $this->createdNum;
    }

    public function setCreatedNum(int $createdNum): self
    {
        $this->createdNum = $createdNum;

        return $this;
    }

    public function getUsedNum(): ?int
    {
        return $this->usedNum;
    }

    public function setUsedNum(int $usedNum): self
    {
        $this->usedNum = $usedNum;

        return $this;
    }

    public function getExpirationStart(): ?int
    {
        return $this->expirationStart;
    }

    public function setExpirationStart(int $expirationStart): self
    {
        $this->expirationStart = $expirationStart;

        return $this;
    }

    public function getExpirationEnd(): ?int
    {
        return $this->expirationEnd;
    }

    public function setExpirationEnd(int $expirationEnd): self
    {
        $this->expirationEnd = $expirationEnd;

        return $this;
    }

    public function getStatus(): ?bool
    {
        return $this->status;
    }

    public function setStatus(bool $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function getCreateUid(): ?int
    {
        return $this->createUid;
    }

    public function setCreateUid(int $createUid): self
    {
        $this->createUid = $createUid;

        return $this;
    }

    public function getDescr(): ?string
    {
        return $this->descr;
    }

    public function setDescr(string $descr): self
    {
        $this->descr = $descr;

        return $this;
    }

    public function getCategoryId(): ?string
    {
        return $this->categoryId;
    }

    public function setCategoryId(?string $categoryId): self
    {
        $this->categoryId = $categoryId;

        return $this;
    }

    public function getTeachingMethod(): ?string
    {
        return $this->teachingMethod;
    }

    public function setTeachingMethod(?string $teachingMethod): self
    {
        $this->teachingMethod = $teachingMethod;

        return $this;
    }

    public function getGoodsIds(): ?string
    {
        return $this->goodsIds;
    }

    public function setGoodsIds(?string $goodsIds): self
    {
        $this->goodsIds = $goodsIds;

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
