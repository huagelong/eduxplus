<?php

namespace Eduxplus\QaBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * TeachTestOrder 试卷订单
 *
 * @ORM\Table(name="teach_test_order", indexes={@ORM\Index(name="uid_idx", columns={"uid"}),@ORM\Index(name="test_id_idx", columns={"test_id"})})
 * @Gedmo\SoftDeleteable(fieldName="deletedAt", timeAware=false, hardDelete=true)
 * @ORM\Entity(repositoryClass="Eduxplus\QaBundle\Repository\TeachTestOrderRepository")
 * @ORM\Cache(usage="NONSTRICT_READ_WRITE")
 */
class TeachTestOrder
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
     * @var int|null
     *
     * @ORM\Column(name="test_id", type="bigint", nullable=true, options={"comment"="试卷id"})
     */
    private $testId;


    /**
     * @var int|null
     *
     * @ORM\Column(name="order_id", type="bigint", nullable=true, options={"comment"="订单id"})
     */
    private $orderId;


        /**
     * @var bool
     *
     * @ORM\Column(name="order_status", type="bigint", length=1,nullable=false, options={"comment"="订单状态:0支付过期,1待支付,2支付成功,3已取消"})
     */
    private $orderStatus = '0';

    /**
     * @var int|null
     *
     * @ORM\Column(name="uid", type="bigint", nullable=true, options={"comment"="uid"})
     */
    private $uid;

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

    public function getTestId(): ?int
    {
        return $this->testId;
    }

    public function setTestId(?int $testId): self
    {
        $this->testId = $testId;

        return $this;
    }

    public function getOrderId(): ?int
    {
        return $this->orderId;
    }

    public function setOrderId(?int $orderId): self
    {
        $this->orderId = $orderId;

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

    public function getUid(): ?int
    {
        return $this->uid;
    }

    public function setUid(?int $uid): self
    {
        $this->uid = $uid;

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
