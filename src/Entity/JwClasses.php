<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * JwClasses
 *
 * @ORM\Table(name="jw_classes")
 * @ORM\Entity
 */
class JwClasses
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer", nullable=false, options={"unsigned"=true})
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var string|null
     *
     * @ORM\Column(name="name", type="string", length=100, nullable=true, options={"comment"="班级名称"})
     */
    private $name;

    /**
     * @var string|null
     *
     * @ORM\Column(name="classes_no", type="string", length=100, nullable=true, options={"comment"="班级编码"})
     */
    private $classesNo;

    /**
     * @var int|null
     *
     * @ORM\Column(name="study_plan_id", type="integer", nullable=true, options={"comment"="学习计划"})
     */
    private $studyPlanId;

    /**
     * @var int|null
     *
     * @ORM\Column(name="max_member_number", type="integer", nullable=true, options={"comment"="最大班级人数，自动分班用"})
     */
    private $maxMemberNumber;

    /**
     * @var int|null
     *
     * @ORM\Column(name="product_id", type="integer", nullable=true, options={"comment"="产品id"})
     */
    private $productId;

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

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getClassesNo(): ?string
    {
        return $this->classesNo;
    }

    public function setClassesNo(?string $classesNo): self
    {
        $this->classesNo = $classesNo;

        return $this;
    }

    public function getStudyPlanId(): ?int
    {
        return $this->studyPlanId;
    }

    public function setStudyPlanId(?int $studyPlanId): self
    {
        $this->studyPlanId = $studyPlanId;

        return $this;
    }

    public function getMaxMemberNumber(): ?int
    {
        return $this->maxMemberNumber;
    }

    public function setMaxMemberNumber(?int $maxMemberNumber): self
    {
        $this->maxMemberNumber = $maxMemberNumber;

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
