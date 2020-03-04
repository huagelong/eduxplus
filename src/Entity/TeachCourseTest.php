<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * TeachCourseTest
 *
 * @ORM\Table(name="teach_course_test")
 * @ORM\Entity
 */
class TeachCourseTest
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
     * @ORM\Column(name="name", type="string", length=100, nullable=true, options={"comment"="试卷名称"})
     */
    private $name;

    /**
     * @var int|null
     *
     * @ORM\Column(name="test_id", type="integer", nullable=true)
     */
    private $testId;

    /**
     * @var int|null
     *
     * @ORM\Column(name="created_at", type="integer", nullable=true)
     */
    private $createdAt;

    /**
     * @var int|null
     *
     * @ORM\Column(name="update_at", type="integer", nullable=true)
     */
    private $updateAt;

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

    public function getTestId(): ?int
    {
        return $this->testId;
    }

    public function setTestId(?int $testId): self
    {
        $this->testId = $testId;

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

    public function getUpdateAt(): ?int
    {
        return $this->updateAt;
    }

    public function setUpdateAt(?int $updateAt): self
    {
        $this->updateAt = $updateAt;

        return $this;
    }


}
