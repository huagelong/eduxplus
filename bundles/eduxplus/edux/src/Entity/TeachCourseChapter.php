<?php

namespace Eduxplus\EduxBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * TeachCourseChapter
 *
 * @ORM\Table(name="teach_course_chapter", indexes={@ORM\Index(name="course_id_idx", columns={"course_id"}),@ORM\Index(name="open_time_idx", columns={"open_time"})})
 * @Gedmo\SoftDeleteable(fieldName="deletedAt", timeAware=false, hardDelete=true)
 * @ORM\Entity(repositoryClass="Eduxplus\EduxBundle\Repository\TeachCourseChapterRepository")
 */
class TeachCourseChapter
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
     * @ORM\Column(name="course_id", type="bigint", nullable=true)
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
     * @ORM\Column(name="parent_id", type="bigint", nullable=true, options={"comment"="父章节"})
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
     * @ORM\Column(name="study_way", type="bigint", nullable=true, options={"comment"="学习方式, 1-直播，2-点播，3-面授"})
     */
    private $studyWay = '1';

    /**
     * @var int|null
     *
     * @ORM\Column(name="sort", type="integer", nullable=true, options={"comment"="排序"})
     */
    private $sort = '0';

    /**
     * @var bool|null
     *
     * @ORM\Column(name="status", type="boolean", nullable=true, options={"comment"="0-下架，1-上架"})
     */
    private $status = '0';

    /**
     * @var string|null
     *
     * @ORM\Column(name="im_group_id", type="string", length=100, nullable=true, options={"comment"="腾讯云im群组id"})
     */
    private $imGroupId;

    /**
     * @var string|null
     *
     * @ORM\Column(name="im_group_status", type="boolean", nullable=true, options={"comment"="腾讯云im群组状态 0-已删除，1-未删除"})
     */
    private $imGroupStatus = '1';

    /**
     * @var string|null
     *
     * @ORM\Column(name="cover_img", type="text", length=500, nullable=true, options={"comment"="封面图"})
     */
    private $coverImg;

    /**
     * @var datetime|null
     *
     * @ORM\Column(name="open_time", type="datetime", nullable=true, options={"comment"="开课时间"})
     */
    private $openTime;


    /**
     * @var datetime|null
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(name="created_at", type="datetime", nullable=true)
     */
    private $createdAt;

    /**
     * @var datetime|null
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

    /**
     * @return string|null
     */
    public function getCoverImg(): ?string
    {
        return $this->coverImg;
    }

    /**
     * @param string|null $coverImg
     */
    public function setCoverImg(?string $coverImg): void
    {
        $this->coverImg = $coverImg;
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

    /**
     * @return bool|null
     */
    public function getStatus(): ?bool
    {
        return $this->status;
    }

    /**
     * @param bool|null $status
     */
    public function setStatus(?bool $status): void
    {
        $this->status = $status;
    }

        /**
     * @return bool|null
     */
    public function getImGroupStatus(): ?bool
    {
        return $this->imGroupStatus;
    }

    /**
     * @param bool|null $status
     */
    public function setImGroupStatus(?bool $imGroupStatus): void
    {
        $this->imGroupStatus = $imGroupStatus;
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

    public function getOpenTime(): ?\DateTimeInterface
    {
        return $this->openTime;
    }

    public function setOpenTime(?\DateTimeInterface $openTime): self
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
