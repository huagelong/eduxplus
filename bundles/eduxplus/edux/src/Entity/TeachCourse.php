<?php

namespace Eduxplus\EduxBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * TeachCourse
 *
 * @ORM\Table(name="teach_course")
 * @Gedmo\SoftDeleteable(fieldName="deletedAt", timeAware=false, hardDelete=true)
 * @ORM\Entity(repositoryClass="Eduxplus\EduxBundle\Repository\TeachCourseRepository")
 */
class TeachCourse
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
     * @ORM\Column(name="name", type="string", length=100, nullable=true, options={"comment"="课程名称"})
     */
    private $name;

    /**
     * @var integer|null
     *
     * @ORM\Column(name="type", type="integer",length=1, nullable=true, options={"comment"="1-线上,2-线下,3-混合"})
     */
    private $type;

    /**
     * @var string|null
     *
     * @ORM\Column(name="big_img", type="text", length=500, nullable=true, options={"comment"="封面图"})
     */
    private $bigImg;

    /**
     * @var string|null
     *
     * @ORM\Column(name="descr", type="text", length=500, nullable=true, options={"comment"="简介"})
     */
    private $descr;

    /**
     * @var int|null
     *
     * @ORM\Column(name="category_id", type="bigint", nullable=true, options={"comment"="类目id"})
     */
    private $categoryId;

    /**
     * @var int|null
     *
     * @ORM\Column(name="first_category_id", type="bigint", nullable=true, options={"comment"="品类id"})
     */
    private $firstCategoryId;

    /**
     * @var int|null
     *
     * @ORM\Column(name="create_uid", type="bigint", nullable=true, options={"comment"="创建人uid，自己创建的自己可见"})
     */
    private $createUid;

    /**
     * @var bool|null
     *
     * @ORM\Column(name="status", type="boolean", nullable=true, options={"comment"="0-下架，1-上架"})
     */
    private $status = '0';

    /**
     * @var int|null
     *
     * @ORM\Column(name="course_hour", type="bigint", nullable=true, options={"comment"="课时，乘以100"})
     */
    private $courseHour;

    /**
     * @var int|null
     *
     * @ORM\Column(name="open_time", type="bigint", nullable=true, options={"comment"="最早开课时间"})
     */
    private $openTime = '0';

    /**
     * @var string|null
     *
     * @ORM\Column(name="school_id", type="string", length=255, nullable=true, options={"comment"="线下分校,id以,分割"})
     */
    private $schoolId;

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

    public function getType(): ?int
    {
        return $this->type;
    }

    public function setType(?int $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getBigImg(): ?string
    {
        return $this->bigImg;
    }

    public function setBigImg(?string $bigImg): self
    {
        $this->bigImg = $bigImg;

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

    public function getCategoryId(): ?int
    {
        return $this->categoryId;
    }

    public function setCategoryId(?int $categoryId): self
    {
        $this->categoryId = $categoryId;

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

    public function getCreateUid(): ?int
    {
        return $this->createUid;
    }

    public function setCreateUid(?int $createUid): self
    {
        $this->createUid = $createUid;

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

    public function getCourseHour(): ?int
    {
        return $this->courseHour;
    }

    public function setCourseHour(?int $courseHour): self
    {
        $this->courseHour = $courseHour;

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

    public function getSchoolId(): ?string
    {
        return $this->schoolId;
    }

    public function setSchoolId(?string $schoolId): self
    {
        $this->schoolId = $schoolId;

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
