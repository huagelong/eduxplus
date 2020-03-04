<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * MallCouponGroup
 *
 * @ORM\Table(name="mall_coupon_group", indexes={@ORM\Index(name="coupon_type", columns={"coupon_type"})})
 * @ORM\Entity
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
     * @ORM\Column(name="coupon_type", type="boolean", nullable=false, options={"comment"="类型:0金额优惠,1折扣优惠"})
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
     * @ORM\Column(name="order_lower_limit", type="integer", nullable=false, options={"unsigned"=true,"comment"="订单下限售价,乘以100"})
     */
    private $orderLowerLimit = '0';

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
     * @ORM\Column(name="is_show", type="boolean", nullable=false, options={"default"="1","comment"="上架1，下架0"})
     */
    private $isShow = true;

    /**
     * @var int
     *
     * @ORM\Column(name="create_uid", type="integer", nullable=false, options={"unsigned"=true,"comment"="创建人"})
     */
    private $createUid;

    /**
     * @var string
     *
     * @ORM\Column(name="coupon_description", type="string", length=200, nullable=false, options={"comment"="优惠码描述"})
     */
    private $couponDescription = '';

    /**
     * @var string|null
     *
     * @ORM\Column(name="category_id", type="string", length=250, nullable=true, options={"comment"="配套产品，分类id"})
     */
    private $categoryId;

    /**
     * @var string|null
     *
     * @ORM\Column(name="teaching_method", type="string", length=50, nullable=true, options={"comment"="授课方式,配套班型:1面授课,2直播课,3面授课+直播课,4网课,5VIP"})
     */
    private $teachingMethod;

    /**
     * @var string|null
     *
     * @ORM\Column(name="goods_id", type="string", length=50, nullable=true, options={"comment"="商品id"})
     */
    private $goodsId;

    /**
     * @var int|null
     *
     * @ORM\Column(name="created_at", type="integer", nullable=true)
     */
    private $createdAt;

    /**
     * @var int|null
     *
     * @ORM\Column(name="updated_at", type="integer", nullable=true)
     */
    private $updatedAt;

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

    public function getOrderLowerLimit(): ?int
    {
        return $this->orderLowerLimit;
    }

    public function setOrderLowerLimit(int $orderLowerLimit): self
    {
        $this->orderLowerLimit = $orderLowerLimit;

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

    public function getIsShow(): ?bool
    {
        return $this->isShow;
    }

    public function setIsShow(bool $isShow): self
    {
        $this->isShow = $isShow;

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

    public function getCouponDescription(): ?string
    {
        return $this->couponDescription;
    }

    public function setCouponDescription(string $couponDescription): self
    {
        $this->couponDescription = $couponDescription;

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

    public function getGoodsId(): ?string
    {
        return $this->goodsId;
    }

    public function setGoodsId(?string $goodsId): self
    {
        $this->goodsId = $goodsId;

        return $this;
    }

    public function getCreatedAt(): ?int
    {
        return $this->createdAt;
    }

    public function setCreatedAt(?int $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUpdatedAt(): ?int
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(?int $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }


}
