<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * MallCoupon
 *
 * @ORM\Table(name="mall_coupon", indexes={@ORM\Index(name="coupon_manage_id", columns={"coupon_group_id"}), @ORM\Index(name="coupon_status", columns={"coupon_status"})})
 * @ORM\Entity
 */
class MallCoupon
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer", nullable=false, options={"unsigned"=true,"comment"="优惠码id"})
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var int
     *
     * @ORM\Column(name="coupon_group_id", type="integer", nullable=false, options={"unsigned"=true,"comment"="优惠码管理id"})
     */
    private $couponGroupId;

    /**
     * @var string
     *
     * @ORM\Column(name="coupon_sn", type="string", length=20, nullable=false, options={"comment"="优惠码编号"})
     */
    private $couponSn = '';

    /**
     * @var int|null
     *
     * @ORM\Column(name="uid", type="integer", nullable=true, options={"unsigned"=true,"comment"="使用会员"})
     */
    private $uid;

    /**
     * @var int|null
     *
     * @ORM\Column(name="used_time", type="integer", nullable=true, options={"unsigned"=true,"comment"="使用时间"})
     */
    private $usedTime;

    /**
     * @var int|null
     *
     * @ORM\Column(name="send_time", type="integer", nullable=true, options={"unsigned"=true,"comment"="发送时间"})
     */
    private $sendTime;

    /**
     * @var bool
     *
     * @ORM\Column(name="coupon_status", type="boolean", nullable=false, options={"comment"="优惠码状态:0未使用,1已使用"})
     */
    private $couponStatus = '0';

    /**
     * @var bool
     *
     * @ORM\Column(name="export_status", type="boolean", nullable=false, options={"comment"="导出状态0：不导出1：导出"})
     */
    private $exportStatus = '0';

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

    public function getCouponGroupId(): ?int
    {
        return $this->couponGroupId;
    }

    public function setCouponGroupId(int $couponGroupId): self
    {
        $this->couponGroupId = $couponGroupId;

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

    public function getUid(): ?int
    {
        return $this->uid;
    }

    public function setUid(?int $uid): self
    {
        $this->uid = $uid;

        return $this;
    }

    public function getUsedTime(): ?int
    {
        return $this->usedTime;
    }

    public function setUsedTime(?int $usedTime): self
    {
        $this->usedTime = $usedTime;

        return $this;
    }

    public function getSendTime(): ?int
    {
        return $this->sendTime;
    }

    public function setSendTime(?int $sendTime): self
    {
        $this->sendTime = $sendTime;

        return $this;
    }

    public function getCouponStatus(): ?bool
    {
        return $this->couponStatus;
    }

    public function setCouponStatus(bool $couponStatus): self
    {
        $this->couponStatus = $couponStatus;

        return $this;
    }

    public function getExportStatus(): ?bool
    {
        return $this->exportStatus;
    }

    public function setExportStatus(bool $exportStatus): self
    {
        $this->exportStatus = $exportStatus;

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
