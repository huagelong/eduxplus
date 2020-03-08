<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * TeachStudyPlanSub
 *
 * @ORM\Table(name="teach_study_plan_sub")
 * @ORM\Entity(repositoryClass="App\Repository\TeachStudyPlanSubRepository")
 */
class TeachStudyPlanSub
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
     * @ORM\Column(name="study_plan_id", type="integer", nullable=true)
     */
    private $studyPlanId;

    /**
     * @var int|null
     *
     * @ORM\Column(name="course_id", type="integer", nullable=true, options={"comment"="课程"})
     */
    private $courseId;

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

    public function getStudyPlanId(): ?int
    {
        return $this->studyPlanId;
    }

    public function setStudyPlanId(?int $studyPlanId): self
    {
        $this->studyPlanId = $studyPlanId;

        return $this;
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
