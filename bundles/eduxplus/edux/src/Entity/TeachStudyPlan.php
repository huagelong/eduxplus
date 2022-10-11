<?php

namespace Eduxplus\EduxBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * TeachStudyPlan
 *
 * @ORM\Table(name="teach_study_plan", indexes={@ORM\Index(name="product_id_idx", columns={"product_id"})})
 * @Gedmo\SoftDeleteable(fieldName="deletedAt", timeAware=false, hardDelete=true)
 * @ORM\Entity(repositoryClass="Eduxplus\EduxBundle\Repository\TeachStudyPlanRepository")
 * @ORM\Cache(usage="NONSTRICT_READ_WRITE")
 */
class TeachStudyPlan
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
     * @var string|null
     *
     * @ORM\Column(name="name", type="string", length=100, nullable=true, options={"comment"="名称"})
     */
    private $name;

    /**
     * @var int|null
     *
     * @ORM\Column(name="product_id", type="bigint", nullable=true, options={"comment"="产品id"})
     */
    private $productId;

    /**
     * @var int|null
     * @ORM\Column(name="applyed_at", type="bigint", nullable=true, options={"comment"="预计报名时间，程序根据预计报名时间给出预警"})
     */
    private $applyedAt;

    /**
     * @var int|null
     *
     * @ORM\Column(name="create_uid", type="bigint", nullable=true, options={"comment"="创建人uid，自己创建的自己可见"})
     */
    private $createUid;

    /**
     * @var bool|null
     *
     * @ORM\Column(name="is_default", type="boolean", nullable=true, options={"comment"="是否当前默认计划"})
     */
    private $isDefault=0;


    /**
     * @var bool|null
     *
     * @ORM\Column(name="status", type="boolean", nullable=true, options={"comment"="1-上架,0-下架"})
     */
    private $status=0;

    /**
     * @var bool|null
     *
     * @ORM\Column(name="is_block", type="boolean", nullable=true, options={"comment"="是否有挡板，必须一节节往下看"})
     */
    private $isBlock='0';

    /**
     * @var string|null
     *
     * @ORM\Column(name="descr", type="text", length=65535, nullable=true, options={"comment"="简介"})
     */
    private $descr;

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

    public function setName(?string $name): self
    {
        $this->name = $name;

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

    public function getApplyedAt(): ?int
    {
        return $this->applyedAt;
    }

    public function setApplyedAt(?int $applyedAt): self
    {
        $this->applyedAt = $applyedAt;

        return $this;
    }

    public function getCreateUid(): ?int
    {
        return $this->createUid;
    }

    public function setCreateUid(?int $createUid): self
    {
        $this->createUid = $createUid;

        return $this;
    }

    public function getIsDefault(): ?bool
    {
        return $this->isDefault;
    }

    public function setIsDefault(?bool $isDefault): self
    {
        $this->isDefault = $isDefault;

        return $this;
    }

    public function getIsBlock(): ?bool
    {
        return $this->isBlock;
    }

    public function setIsBlock(?bool $isBlock): self
    {
        $this->isBlock = $isBlock;

        return $this;
    }

    public function getDescr(): ?string
    {
        return $this->descr;
    }

    public function setDescr(?string $descr): self
    {
        $this->descr = $descr;

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

    public function getStatus(): ?bool
    {
        return $this->status;
    }

    public function setStatus(?bool $status): self
    {
        $this->status = $status;

        return $this;
    }

}
