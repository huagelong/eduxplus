<?php

namespace Eduxplus\EduxBundle\Entity;

use Eduxplus\EduxBundle\Repository\MallOrderStudyPlanRepository;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Table(name="mall_order_study_plan", indexes={@ORM\Index(name="order_id_idx", columns={"order_id"}),@ORM\Index(name="uid_idx", columns={"uid"})})
 * @Gedmo\SoftDeleteable(fieldName="deletedAt", timeAware=false, hardDelete=true)
 * @ORM\Entity(repositoryClass=MallOrderStudyPlanRepository::class)
 */
class MallOrderStudyPlan
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
     * @var int
     *
     * @ORM\Column(name="order_id", type="bigint", nullable=false, options={"unsigned"=true,"comment"="订单id"})
     */
    private $orderId='0';

    
    /**
     * @var bool
     *
     * @ORM\Column(name="order_status", type="bigint", length=1,nullable=false, options={"comment"="订单状态:0支付过期,1待支付,2支付成功,3已取消"})
     */
    private $orderStatus = '0';

    /**
     * @var int
     *
     * @ORM\Column(name="uid", type="bigint", nullable=false, options={"unsigned"=true,"comment"="下单用户uid"})
     */
    private $uid='0';

    /**
     * @var int
     *
     * @ORM\Column(name="study_plan_id", type="bigint", nullable=false, options={"unsigned"=true,"comment"="开课计划ID"})
     */
    private $studyPlanId='0';

    /**
     * @var int
     *
     * @ORM\Column(name="course_id", type="bigint", nullable=false, options={"unsigned"=true,"comment"="课程id"})
     */
    private $courseId='0';

    /**
     * @var int|null
     *
     * @ORM\Column(name="open_time", type="bigint", nullable=true, options={"comment"="开课时间"})
     */
    private $openTime;

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

    public function getOrderId(): ?int
    {
        return $this->orderId;
    }

    public function setOrderId(int $orderId): self
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

    public function setUid(int $uid): self
    {
        $this->uid = $uid;

        return $this;
    }

    public function getStudyPlanId(): ?int
    {
        return $this->studyPlanId;
    }

    public function setStudyPlanId(int $studyPlanId): self
    {
        $this->studyPlanId = $studyPlanId;

        return $this;
    }

    public function getCourseId(): ?int
    {
        return $this->courseId;
    }

    public function setCourseId(int $courseId): self
    {
        $this->courseId = $courseId;

        return $this;
    }

    public function getOpenTime(): ?int
    {
        return $this->openTime;
    }

    public function setOpenTime(?int $openTime): self
    {
        $this->openTime = $openTime;

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
