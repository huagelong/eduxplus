<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * MallPay
 *
 * @ORM\Table(name="mall_pay")
 * @Gedmo\SoftDeleteable(fieldName="deletedAt", timeAware=false, hardDelete=true)
 * @ORM\Entity(repositoryClass="App\Repository\MallPayRepository")
 */
class MallPay
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer", nullable=false, options={"unsigned"=true,"comment"="支付表id"})
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="transaction_id", type="string", length=200, nullable=false, options={"comment"="支付流水号"})
     */
    private $transactionId = '';

    /**
     * @var int
     *
     * @ORM\Column(name="order_no", type="string",length=200, nullable=false, options={"unsigned"=true,"comment"="订单号"})
     */
    private $orderNo;

    /**
     * @var int
     *
     * @ORM\Column(name="uid", type="integer", nullable=false, options={"unsigned"=true,"comment"="下单人"})
     */
    private $uid;

    /**
     * @var int
     *
     * @ORM\Column(name="pay_time", type="integer", nullable=true, options={"unsigned"=true,"comment"="支付完成时间"})
     */
    private $payTime;

    /**
     * @var bool
     *
     * @ORM\Column(name="payment_type", type="boolean", nullable=false, options={"comment"="支付方式:1支付宝,2微信支付"})
     */
    private $paymentType = '0';

    /**
     * @var bool
     *
     * @ORM\Column(name="pay_status", type="boolean", nullable=false, options={"comment"="付款状态:0支付过期,1待支付,2支付成功,3已取消"})
     */
    private $payStatus = '0';

    /**
     * @var int
     *
     * @ORM\Column(name="amount", type="integer", nullable=false, options={"unsigned"=true,"comment"="金额,乘以100"})
     */
    private $amount;

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

    public function getTransactionId(): ?string
    {
        return $this->transactionId;
    }

    public function setTransactionId(string $transactionId): self
    {
        $this->transactionId = $transactionId;

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

    public function getPayTime(): ?int
    {
        return $this->payTime;
    }

    public function setPayTime(int $payTime): self
    {
        $this->payTime = $payTime;

        return $this;
    }

    public function getPaymentType(): ?bool
    {
        return $this->paymentType;
    }

    public function setPaymentType(bool $paymentType): self
    {
        $this->paymentType = $paymentType;

        return $this;
    }

    public function getPayStatus(): ?bool
    {
        return $this->payStatus;
    }

    public function setPayStatus(bool $payStatus): self
    {
        $this->payStatus = $payStatus;

        return $this;
    }

    public function getAmount(): ?int
    {
        return $this->amount;
    }

    public function setAmount(int $amount): self
    {
        $this->amount = $amount;

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
