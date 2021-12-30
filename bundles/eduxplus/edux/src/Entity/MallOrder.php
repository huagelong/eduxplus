<?php

namespace Eduxplus\EduxBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * MallOrder
 *
 * @ORM\Table(name="mall_order")
 * @Gedmo\SoftDeleteable(fieldName="deletedAt", timeAware=false, hardDelete=true)
 * @ORM\Entity(repositoryClass="Eduxplus\EduxBundle\Repository\MallOrderRepository")
 */
class MallOrder
{
    /**
     * @var int
     *
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="CUSTOM")
     * @ORM\CustomIdGenerator(class="Eduxplus\EduxBundle\Doctrine\Generator\SnowflakeGenerator")
     * @ORM\Column(type="bigint", unique=true)
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=1000, nullable=false, options={"comment"="订单名称"})
     */
    private $name = '';

    /**
     * @var string
     *
     * @ORM\Column(name="order_no", type="string", length=100, nullable=false, options={"comment"="订单号"})
     */
    private $orderNo = '';

    /**
     * @var int
     *
     * @ORM\Column(name="uid", type="bigint", nullable=false, options={"unsigned"=true,"comment"="下单人"})
     */
    private $uid;

    /**
     * @var int
     *
     * @ORM\Column(name="goods_id", type="bigint", nullable=false, options={"unsigned"=true,"comment"="商品id"})
     */
    private $goodsId;

    /**
     * @var int
     *
     * @ORM\Column(name="order_agreement_id", type="bigint", nullable=false, options={"unsigned"=true,"comment"="订单协议"})
     */
    private $orderAgreementId= '0';

    /**
     * @var int
     *
     * @ORM\Column(name="order_amount", type="bigint", nullable=false, options={"comment"="订单实际支付价格，已减去优惠价格"})
     */
    private $orderAmount = '0';

    /**
     * @var int
     *
     * @ORM\Column(name="discount_amount", type="bigint", nullable=false, options={"comment"="优惠金额"})
     */
    private $discountAmount = '0';

    /**
     * @var int
     *
     * @ORM\Column(name="original_amount", type="bigint", nullable=false, options={"comment"="原价"})
     */
    private $originalAmount = '0';

    /**
     * @var bool
     *
     * @ORM\Column(name="order_status", type="bigint", length=1,nullable=false, options={"comment"="订单状态:0支付过期,1待支付,2支付成功,3已取消"})
     */
    private $orderStatus = '0';


    /**
     * @var bool
     *
     * @ORM\Column(name="payment_type", type="bigint", length=1,nullable=false, options={"comment"="支付方式:1支付宝,2微信支付"})
     */
    private $paymentType = '0';

    /**
     * @var string
     *
     * @ORM\Column(name="user_notes", type="string", length=200, nullable=false, options={"comment"="用户备注"})
     */
    private $userNotes = '';

    /**
     * @var string
     *
     * @ORM\Column(name="referer", type="string", length=20, nullable=false, options={"default"="CRM","comment"="订单来源"})
     */
    private $referer = 'pcweb';

    /**
     * @var string
     *
     * @ORM\Column(name="coupon_sn", type="string", length=100, nullable=false, options={"comment"="订单使用优惠码编号"})
     */
    private $couponSn = '';

    /**
     * @var int
     *
     * @ORM\Column(name="coupon_group_id", type="bigint", nullable=false, options={"unsigned"=true,"comment"="优惠码组"})
     */
    private $couponGroupId= '0';

    /**
     * @var string
     *
     * @ORM\Column(name="goods_all", type="string", length=100, nullable=false, options={"comment"="多个goodsid"})
     */
    private $goodsAll = '';

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

    public function getOrderNo(): ?string
    {
        return $this->orderNo;
    }

    public function setOrderNo(string $orderNo): self
    {
        $this->orderNo = $orderNo;

        return $this;
    }

    public function getUid(): ?int
    {
        return $this->uid;
    }

    public function setUid(int $uid): self
    {
        $this->uid = $uid;

        return $this;
    }

    public function getGoodsId(): ?int
    {
        return $this->goodsId;
    }

    public function setGoodsId(int $goodsId): self
    {
        $this->goodsId = $goodsId;

        return $this;
    }

    public function getOrderAgreementId(): ?int
    {
        return $this->orderAgreementId;
    }

    public function setOrderAgreementId(int $orderAgreementId): self
    {
        $this->orderAgreementId = $orderAgreementId;

        return $this;
    }

    public function getOrderAmount(): ?int
    {
        return $this->orderAmount;
    }

    public function setOrderAmount(int $orderAmount): self
    {
        $this->orderAmount = $orderAmount;

        return $this;
    }

    public function getDiscountAmount(): ?int
    {
        return $this->discountAmount;
    }

    public function setDiscountAmount(int $discountAmount): self
    {
        $this->discountAmount = $discountAmount;

        return $this;
    }

    public function getOriginalAmount(): ?int
    {
        return $this->originalAmount;
    }

    public function setOriginalAmount(int $originalAmount): self
    {
        $this->originalAmount = $originalAmount;

        return $this;
    }

    public function getOrderStatus(): ?int
    {
        return $this->orderStatus;
    }

    public function setOrderStatus(int $orderStatus): self
    {
        $this->orderStatus = $orderStatus;

        return $this;
    }

    public function getPaymentType(): ?int
    {
        return $this->paymentType;
    }

    public function setPaymentType(int $paymentType): self
    {
        $this->paymentType = $paymentType;

        return $this;
    }

    public function getUserNotes(): ?string
    {
        return $this->userNotes;
    }

    public function setUserNotes(string $userNotes): self
    {
        $this->userNotes = $userNotes;

        return $this;
    }

    public function getReferer(): ?string
    {
        return $this->referer;
    }

    public function setReferer(string $referer): self
    {
        $this->referer = $referer;

        return $this;
    }

    public function getCouponSn(): ?string
    {
        return $this->couponSn;
    }

    public function setCouponSn(string $couponSn): self
    {
        $this->couponSn = $couponSn;

        return $this;
    }

    public function getCouponGroupId(): ?int
    {
        return $this->couponGroupId;
    }

    public function setCouponGroupId(int $couponGroupId): self
    {
        $this->couponGroupId = $couponGroupId;

        return $this;
    }

    public function getGoodsAll(): ?string
    {
        return $this->goodsAll;
    }

    public function setGoodsAll(string $goodsAll): self
    {
        $this->goodsAll = $goodsAll;

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
