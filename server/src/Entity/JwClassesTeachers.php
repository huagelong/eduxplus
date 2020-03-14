<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * JwClassesTeachers
 *
 * @ORM\Table(name="jw_classes_teachers")
 * @Gedmo\SoftDeleteable(fieldName="deletedAt", timeAware=false, hardDelete=true)
 * @ORM\Entity(repositoryClass="App\Repository\JwClassesTeachersRepository")
 */
class JwClassesTeachers
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
     * @ORM\Column(name="classes_id", type="integer", nullable=true, options={"comment"="班级id"})
     */
    private $classesId;

    /**
     * @var int|null
     *
     * @ORM\Column(name="teacher_id", type="integer", nullable=true, options={"comment"="老师id"})
     */
    private $teacherId;

    /**
     * @var bool|null
     *
     * @ORM\Column(name="teacher_type", type="boolean", nullable=true, options={"comment"="1-班主任"})
     */
    private $teacherType;

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

    public function getClassesId(): ?int
    {
        return $this->classesId;
    }

    public function setClassesId(?int $classesId): self
    {
        $this->classesId = $classesId;

        return $this;
    }

    public function getTeacherId(): ?int
    {
        return $this->teacherId;
    }

    public function setTeacherId(?int $teacherId): self
    {
        $this->teacherId = $teacherId;

        return $this;
    }

    public function getTeacherType(): ?bool
    {
        return $this->teacherType;
    }

    public function setTeacherType(?bool $teacherType): self
    {
        $this->teacherType = $teacherType;

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
