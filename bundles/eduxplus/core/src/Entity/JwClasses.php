<?php

namespace Eduxplus\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * JwClasses
 *
 * @ORM\Table(name="jw_classes")
 * @Gedmo\SoftDeleteable(fieldName="deletedAt", timeAware=false, hardDelete=true)
 * @ORM\Entity(repositoryClass="Eduxplus\CoreBundle\Repository\JwClassesRepository")
 */
class JwClasses
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
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
     * @ORM\Column(name="study_plan_id", type="integer", nullable=true, options={"comment"="开课计划id"})
     */
    private $studyPlanId;

    /**
     * @var int|null
     *
     * @ORM\Column(name="product_id", type="integer", nullable=true, options={"comment"="产品id"})
     */
    private $productId;

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

    public function getProductId(): ?int
    {
        return $this->productId;
    }

    public function setProductId(?int $productId): self
    {
        $this->productId = $productId;

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
