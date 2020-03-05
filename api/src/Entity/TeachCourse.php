<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * TeachCourse
 *
 * @ORM\Table(name="teach_course")
 * @ORM\Entity(repositoryClass="App\Repository\TeachCourseRepository")
 */
class TeachCourse
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
     * @ORM\Column(name="name", type="string", length=100, nullable=true, options={"comment"="课程名称"})
     */
    private $name;

    /**
     * @var bool|null
     *
     * @ORM\Column(name="type", type="boolean", nullable=true, options={"comment"="1-线上,2-线下,3-混合"})
     */
    private $type;

    /**
     * @var string|null
     *
     * @ORM\Column(name="big_img", type="string", length=100, nullable=true, options={"comment"="封面图"})
     */
    private $bigImg;

    /**
     * @var string|null
     *
     * @ORM\Column(name="descr", type="string", length=200, nullable=true, options={"comment"="简介"})
     */
    private $descr;

    /**
     * @var int|null
     *
     * @ORM\Column(name="cate_id", type="integer", nullable=true, options={"comment"="类目id"})
     */
    private $cateId;

    /**
     * @var int|null
     *
     * @ORM\Column(name="brand_id", type="integer", nullable=true, options={"comment"="品类id"})
     */
    private $brandId;

    /**
     * @var int|null
     *
     * @ORM\Column(name="create_uid", type="integer", nullable=true, options={"comment"="创建人uid，自己创建的自己可见"})
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
     * @ORM\Column(name="course_hour", type="integer", nullable=true, options={"comment"="课时，乘以100"})
     */
    private $courseHour;

    /**
     * @var string|null
     *
     * @ORM\Column(name="school_id", type="string", length=255, nullable=true, options={"comment"="线下分校,id以,分割"})
     */
    private $schoolId;

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

    public function getType(): ?bool
    {
        return $this->type;
    }

    public function setType(?bool $type): self
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

    public function getCateId(): ?int
    {
        return $this->cateId;
    }

    public function setCateId(?int $cateId): self
    {
        $this->cateId = $cateId;

        return $this;
    }

    public function getBrandId(): ?int
    {
        return $this->brandId;
    }

    public function setBrandId(?int $brandId): self
    {
        $this->brandId = $brandId;

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

    public function getSchoolId(): ?string
    {
        return $this->schoolId;
    }

    public function setSchoolId(?string $schoolId): self
    {
        $this->schoolId = $schoolId;

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
