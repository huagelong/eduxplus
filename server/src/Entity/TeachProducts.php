<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * TeachProducts
 *
 * @ORM\Table(name="teach_products")
 * @Gedmo\SoftDeleteable(fieldName="deletedAt", timeAware=false, hardDelete=true)
 * @ORM\Entity(repositoryClass="App\Repository\TeachProductsRepository")
 */
class TeachProducts
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
     * @ORM\Column(name="name", type="string", length=100, nullable=true)
     */
    private $name;

    /**
     * @var int|null
     *
     * @ORM\Column(name="first_category_id", type="integer", nullable=true, options={"comment"="品类id"})
     */
    private $firstCategoryId;

    /**
     * @var int|null
     *
     * @ORM\Column(name="category_id", type="integer", nullable=true, options={"comment"="类目id"})
     */
    private $categoryId;

    /**
     * @var string|null
     *
     * @ORM\Column(name="descr", type="text", length=65535, nullable=true, options={"comment"="简介"})
     */
    private $descr;


    /**
     * @var int|null
     *
     * @ORM\Column(name="agreement_id", type="integer", nullable=true, options={"comment"="协议id"})
     */
    private $agreementId;

    /**
     * @var bool|null
     *
     * @ORM\Column(name="status", type="boolean", nullable=true, options={"comment"="1-上架,0-下架"})
     */
    private $status;

    /**
     * @var int|null
     *
     * @ORM\Column(name="create_uid", type="integer", nullable=true, options={"comment"="创建人uid，自己创建的自己可见"})
     */
    private $createUid;


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

    /**
     * @var int|null
     *
     * @ORM\Column(name="max_member_number", type="integer", nullable=true, options={"comment"="自动分班，最大班级人数"})
     */
    private $maxMemberNumber;

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

    public function getFirstCategoryId(): ?int
    {
        return $this->firstCategoryId;
    }

    public function setFirstCategoryId(?int $firstCategoryId): self
    {
        $this->firstCategoryId = $firstCategoryId;

        return $this;
    }

    public function getCategoryId(): ?int
    {
        return $this->categoryId;
    }

    public function setCategoryId(?int $categoryId): self
    {
        $this->categoryId = $categoryId;

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

    public function getAgreementId(): ?int
    {
        return $this->agreementId;
    }

    public function setAgreementId(?int $agreementId): self
    {
        $this->agreementId = $agreementId;

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

    public function getCreateUid(): ?int
    {
        return $this->createUid;
    }

    public function setCreateUid(?int $createUid): self
    {
        $this->createUid = $createUid;

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

    public function getMaxMemberNumber(): ?int
    {
        return $this->maxMemberNumber;
    }

    public function setMaxMemberNumber(?int $maxMemberNumber): self
    {
        $this->maxMemberNumber = $maxMemberNumber;

        return $this;
    }

}
