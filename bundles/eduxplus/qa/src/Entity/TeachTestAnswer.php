<?php

namespace Eduxplus\QaBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * TeachTestAnswer 试卷回答快照
 *
 * @ORM\Table(name="teach_test_answer", indexes={@ORM\Index(name="test_id_idx", columns={"test_id"}),@ORM\Index(name="uid_idx", columns={"uid"})})
 * @Gedmo\SoftDeleteable(fieldName="deletedAt", timeAware=false, hardDelete=true)
 * @ORM\Entity(repositoryClass="Eduxplus\QaBundle\Repository\TeachTestAnswerRepository")
 * @ORM\Cache(usage="NONSTRICT_READ_WRITE")
 */
class TeachTestAnswer
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
     * @ORM\Column(name="test_id", type="bigint", nullable=true, options={"comment"="试卷id"})
     */
    private $testId;

    /**
     * @var int|null
     *
     * @ORM\Column(name="uid", type="bigint", nullable=true, options={"comment"="用户uid"})
     */
    private $uid;

    /**
     * @var string|null
     *
     * @ORM\Column(name="answer_snapshot", type="text", length=16777215, nullable=true, options={"comment"="回答快照"})
     */
    private $answerSnapshot;

    /**
     * @var int|null
     *
     * @ORM\Column(name="error_num", type="bigint", nullable=true, options={"comment"="错误数量"})
     */
    private $errorNum;

    /**
     * @var int|null
     *
     * @ORM\Column(name="right_num", type="bigint", nullable=true, options={"comment"="正确数量"})
     */
    private $rightNum;

        /**
     * @var int|null
     *
     * @ORM\Column(name="score", type="bigint", nullable=true, options={"comment"="得分"})
     */
    private $score;

    /**
     * @var int|null
     *
     * @ORM\Column(name="undo_num", type="bigint", nullable=true, options={"comment"="未做数量"})
     */
    private $undoNum;

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

    public function getTestId(): ?int
    {
        return $this->testId;
    }

    public function setTestId(?int $testId): self
    {
        $this->testId = $testId;

        return $this;
    }

    public function getUid(): ?int
    {
        return $this->uid;
    }

    public function setUid(?int $uid): self
    {
        $this->uid = $uid;

        return $this;
    }

    public function getAnswerSnapshot(): ?string
    {
        return $this->answerSnapshot;
    }

    public function setAnswerSnapshot(?string $answerSnapshot): self
    {
        $this->answerSnapshot = $answerSnapshot;

        return $this;
    }

    public function getErrorNum(): ?int
    {
        return $this->errorNum;
    }

    public function setErrorNum(?int $errorNum): self
    {
        $this->errorNum = $errorNum;

        return $this;
    }

    public function getRightNum(): ?int
    {
        return $this->rightNum;
    }

    public function setRightNum(?int $rightNum): self
    {
        $this->rightNum = $rightNum;

        return $this;
    }

    public function getScore(): ?int
    {
        return $this->score;
    }

    public function setScore(?int $score): self
    {
        $this->score = $score;

        return $this;
    }

    public function getUndoNum(): ?int
    {
        return $this->undoNum;
    }

    public function setUndoNum(?int $undoNum): self
    {
        $this->undoNum = $undoNum;

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
