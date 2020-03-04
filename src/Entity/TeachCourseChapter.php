<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * TeachCourseChapter
 *
 * @ORM\Table(name="teach_course_chapter")
 * @ORM\Entity
 */
class TeachCourseChapter
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
     * @var int|null
     *
     * @ORM\Column(name="course_id", type="integer", nullable=true)
     */
    private $courseId;

    /**
     * @var string|null
     *
     * @ORM\Column(name="name", type="string", length=200, nullable=true, options={"comment"="章节名称"})
     */
    private $name;

    /**
     * @var int|null
     *
     * @ORM\Column(name="open_time", type="integer", nullable=true, options={"comment"="开课时间"})
     */
    private $openTime;

    /**
     * @var int|null
     *
     * @ORM\Column(name="parent_id", type="integer", nullable=true, options={"comment"="父章节"})
     */
    private $parentId;

    /**
     * @var bool|null
     *
     * @ORM\Column(name="is_free", type="boolean", nullable=true, options={"comment"="1-是，0-否"})
     */
    private $isFree = '0';

    /**
     * @var bool|null
     *
     * @ORM\Column(name="is_del", type="boolean", nullable=true, options={"comment"="1-是，0-否"})
     */
    private $isDel = '0';

    /**
     * @var int|null
     *
     * @ORM\Column(name="fsort", type="integer", nullable=true, options={"comment"="排序"})
     */
    private $fsort = '0';

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

    public function getCourseId(): ?int
    {
        return $this->courseId;
    }

    public function setCourseId(?int $courseId): self
    {
        $this->courseId = $courseId;

        return $this;
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

    public function getOpenTime(): ?int
    {
        return $this->openTime;
    }

    public function setOpenTime(?int $openTime): self
    {
        $this->openTime = $openTime;

        return $this;
    }

    public function getParentId(): ?int
    {
        return $this->parentId;
    }

    public function setParentId(?int $parentId): self
    {
        $this->parentId = $parentId;

        return $this;
    }

    public function getIsFree(): ?bool
    {
        return $this->isFree;
    }

    public function setIsFree(?bool $isFree): self
    {
        $this->isFree = $isFree;

        return $this;
    }

    public function getIsDel(): ?bool
    {
        return $this->isDel;
    }

    public function setIsDel(?bool $isDel): self
    {
        $this->isDel = $isDel;

        return $this;
    }

    public function getFsort(): ?int
    {
        return $this->fsort;
    }

    public function setFsort(?int $fsort): self
    {
        $this->fsort = $fsort;

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
