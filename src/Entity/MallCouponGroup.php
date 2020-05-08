<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * MallCouponGroup
 *
 * @ORM\Table(name="mall_coupon_group")
 * @Gedmo\SoftDeleteable(fieldName="deletedAt", timeAware=false, hardDelete=true)
 * @ORM\Entity(repositoryClass="App\Repository\MallCouponGroupRepository")
 */
class MallCouponGroup
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer", nullable=false, options={"unsigned"=true,"comment"="优惠码组id"})
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="coupon_name", type="string", length=20, nullable=false, options={"comment"="优惠码名称"})
     */
    private $couponName = '';

    /**
     * @var bool
     *
     * @ORM\Column(name="coupon_type", type="boolean", nullable=false, options={"comment"="类型:1金额优惠,2折扣优惠"})
     */
    private $couponType = '0';

    /**
     * @var int
     *
     * @ORM\Column(name="discount", type="integer", nullable=false, options={"unsigned"=true,"comment"="优惠码折扣(百分数)/优惠码金额(乘以100)"})
     */
    private $discount = '0';

    /**
     * @var int
     *
     * @ORM\Column(name="count_num", type="integer", nullable=false, options={"unsigned"=true,"comment"="发放数量"})
     */
    private $countNum = '0';

    /**
     * @var int
     *
     * @ORM\Column(name="used_num", type="integer", nullable=false, options={"unsigned"=true,"comment"="已使用的数量"})
     */
    private $usedNum = '0';

    /**
     * @var int
     *
     * @ORM\Column(name="expiration_start", type="integer", nullable=false, options={"unsigned"=true,"comment"="开始有效日期"})
     */
    private $expirationStart;

    /**
     * @var int
     *
     * @ORM\Column(name="expiration_end", type="integer", nullable=false, options={"unsigned"=true,"comment"="结束有效日期"})
     */
    private $expirationEnd;

    /**
     * @var bool
     *
     * @ORM\Column(name="status", type="boolean", nullable=false, options={"default"="1","comment"="上架1，下架0"})
     */
    private $status = true;

    /**
     * @var int
     *
     * @ORM\Column(name="create_uid", type="integer", nullable=false, options={"unsigned"=true,"comment"="创建人"})
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
     * @ORM\Column(name="teaching_method", type="string", length=50, nullable=true, options={"comment"="授课方式 1.面授 2.直播 3.录播 4.面授+直播 5.直播+录播 6.录播+面授 7.直播+录播+面授"})
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

    public function getCouponName(): ?string
    {
        return $this->couponName;
    }

    public function setCouponName(string $couponName): self
    {
        $this->couponName = $couponName;

        return $this;
    }

    public function getCouponType(): ?bool
    {
        return $this->couponType;
    }

    public function setCouponType(bool $couponType): self
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

    public function getCountNum(): ?int
    {
        return $this->countNum;
    }

    public function setCountNum(int $countNum): self
    {
        $this->countNum = $countNum;

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
