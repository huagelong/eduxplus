<?php

namespace Eduxplus\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * TeachCourseChapter
 *
 * @ORM\Table(name="teach_course_chapter")
 * @Gedmo\SoftDeleteable(fieldName="deletedAt", timeAware=false, hardDelete=true)
 * @ORM\Entity(repositoryClass="Eduxplus\CoreBundle\Repository\TeachCourseChapterRepository")
 */
class TeachCourseChapter
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
     * @var string|null
     *
     * @ORM\Column(name="path", type="string", length=200, nullable=true, options={"comment"="子树路径"})
     */
    private $path;

    /**
     * @var bool|null
     *
     * @ORM\Column(name="is_free", type="boolean", nullable=true, options={"comment"="是否免费（试听课），1-是，0-否"})
     */
    private $isFree = '0';

    /**
     * @var bool|null
     *
     * @ORM\Column(name="study_way", type="integer", nullable=true, options={"comment"="学习方式, 1-直播，2-点播，3-面授"})
     */
    private $studyWay = '1';

    /**
     * @var int|null
     *
     * @ORM\Column(name="sort", type="integer", nullable=true, options={"comment"="排序"})
     */
    private $sort = '0';

    /**
     * @var string|null
     *
     * @ORM\Column(name="im_group_id", type="string", length=40, nullable=true, options={"comment"="腾讯云im"})
     */
    private $imGroupId;

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

    public function getPath(): ?string
    {
        return $this->path;
    }

    public function setPath(?string $path): self
    {
        $this->path = $path;

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

    public function getStudyWay(): ?int
    {
        return $this->studyWay;
    }

    public function setStudyWay(?int $studyWay): self
    {
        $this->studyWay = $studyWay;

        return $this;
    }

    public function getSort(): ?int
    {
        return $this->sort;
    }

    public function setSort(?int $sort): self
    {
        $this->sort = $sort;

        return $this;
    }

    public function getImGroupId(): ?string
    {
        return $this->imGroupId;
    }

    public function setImGroupId(?string $imGroupId): self
    {
        $this->imGroupId = $imGroupId;

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
