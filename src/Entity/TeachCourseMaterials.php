<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * TeachCourseMaterials
 *
 * @ORM\Table(name="teach_course_materials")
 * @ORM\Entity
 */
class TeachCourseMaterials
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
     * @var int|null
     *
     * @ORM\Column(name="chapter_id", type="integer", nullable=true, options={"comment"="章节id"})
     */
    private $chapterId;

    /**
     * @var string|null
     *
     * @ORM\Column(name="name", type="string", length=100, nullable=true, options={"comment"="资料名称"})
     */
    private $name;

    /**
     * @var string|null
     *
     * @ORM\Column(name="path", type="string", length=254, nullable=true, options={"comment"="资料下载路径"})
     */
    private $path;

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

    public function getChapterId(): ?int
    {
        return $this->chapterId;
    }

    public function setChapterId(?int $chapterId): self
    {
        $this->chapterId = $chapterId;

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

    public function getPath(): ?string
    {
        return $this->path;
    }

    public function setPath(?string $path): self
    {
        $this->path = $path;

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
