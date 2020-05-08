<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * MallOrder
 *
 * @ORM\Table(name="mall_order")
 * @Gedmo\SoftDeleteable(fieldName="deletedAt", timeAware=false, hardDelete=true)
 * @ORM\Entity(repositoryClass="App\Repository\MallOrderRepository")
 */
class MallOrder
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer", nullable=false, options={"unsigned"=true,"comment"="订单表id"})
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="order_no", type="string", length=20, nullable=false, options={"comment"="订单号"})
     */
    private $orderNo = '';

    /**
     * @var int
     *
     * @ORM\Column(name="uid", type="integer", nullable=false, options={"unsigned"=true,"comment"="users表的用户id"})
     */
    private $uid;

    /**
     * @var int
     *
     * @ORM\Column(name="goods_id", type="integer", nullable=false, options={"unsigned"=true,"comment"="商品id"})
     */
    private $goodsId;

    /**
     * @var int
     *
     * @ORM\Column(name="order_amount", type="integer", nullable=false, options={"comment"="订单原价"})
     */
    private $orderAmount = '0';

    /**
     * @var int
     *
     * @ORM\Column(name="discount_amount", type="integer", nullable=false, options={"comment"="优惠金额"})
     */
    private $discountAmount = '0';

    /**
     * @var int
     *
     * @ORM\Column(name="order_time", type="integer", nullable=false, options={"unsigned"=true,"comment"="下单时间"})
     */
    private $orderTime = '0';

    /**
     * @var bool
     *
     * @ORM\Column(name="order_status", type="boolean", nullable=false, options={"comment"="订单状态:0待支付,1已支付,2已取消"})
     */
    private $orderStatus = '0';

    /**
     * @var string
     *
     * @ORM\Column(name="user_notes", type="string", length=200, nullable=false, options={"comment"="用户备注"})
     */
    private $userNotes = '';

    /**
     * @var string
     *
     * @ORM\Column(name="referer", type="string", length=20, nullable=false, options={"default"="CRM后台创建","comment"="订单来源"})
     */
    private $referer = '前台创建';

    /**
     * @var string
     *
     * @ORM\Column(name="coupon_sn", type="string", length=20, nullable=false, options={"comment"="订单使用优惠码编号"})
     */
    private $couponSn = '';

    /**
     * @var string
     *
     * @ORM\Column(name="goods_all", type="string", length=100, nullable=false, options={"comment"="多个goodsid"})
     */
    private $goodsAll = '';

    /**
     * @var string
     *
     * @ORM\Column(name="goods_name", type="string", length=1000, nullable=false, options={"comment"="订单商品名称"})
     */
    private $goodsName = '';

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

    public function getOrderTime(): ?int
    {
        return $this->orderTime;
    }

    public function setOrderTime(int $orderTime): self
    {
        $this->orderTime = $orderTime;

        return $this;
    }

    public function getOrderStatus(): ?bool
    {
        return $this->orderStatus;
    }

    public function setOrderStatus(bool $orderStatus): self
    {
        $this->orderStatus = $orderStatus;

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

    public function getGoodsAll(): ?string
    {
        return $this->goodsAll;
    }

    public function setGoodsAll(string $goodsAll): self
    {
        $this->goodsAll = $goodsAll;

        return $this;
    }

    public function getGoodsName(): ?string
    {
        return $this->goodsName;
    }

    public function setGoodsName(string $goodsName): self
    {
        $this->goodsName = $goodsName;

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
