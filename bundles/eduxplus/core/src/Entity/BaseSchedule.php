<?php

namespace Eduxplus\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * BaseSchedule
 *
 * @ORM\Table(name="base_schedule")
 * @Gedmo\SoftDeleteable(fieldName="deletedAt", timeAware=false, hardDelete=true)
 * @ORM\Entity(repositoryClass="Eduxplus\CoreBundle\Repository\BaseScheduleRepository")
 */
class BaseSchedule
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
     * @ORM\Column(name="descr", type="string", length=255, nullable=true, options={"comment"="任务描述"})
     */
    private $descr;

    /**
     * @var string|null
     *
     * @ORM\Column(name="task_id", type="string", length=100, nullable=true, options={"comment"="任务ID"})
     */
    private $taskId;


    /**
     * @var int|null
     *
     * @ORM\Column(name="status", type="integer", length=1, nullable=true, options={"comment"="配置类型, 1-开启, 0-关闭"})
     */
    private $status = '0';

    /**
     * @ORM\Column(name="type", type="string", length=100, nullable=true, options={"comment"="任务类型"})
     */
    private $type;

    /**
     * @ORM\Column(name="expression", type="string", length=100, nullable=true, options={"comment"="任务运行时间表达式"})
     */
    private $expression;

    /**
     * @ORM\Column(name="next_run", type="string", length=100, nullable=true, options={"comment"="下一次运行时间"})
     */
    private $nextRun;

    /**
     * @ORM\Column(name="time_zone", type="string", length=100, nullable=true, options={"comment"="时区"})
     */
    private $timeZone;

    /**
     * @var string|null
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(name="created_at", type="datetime", nullable=true)
     */
    private $createdAt;

    /**
     * @var string|null
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

    /**
     * @return string|null
     */
    public function getDescr(): ?string
    {
        return $this->descr;
    }

    /**
     * @param string|null $descr
     */
    public function setDescr(?string $descr): void
    {
        $this->descr = $descr;
    }

    /**
     * @return string|null
     */
    public function getTaskId(): ?string
    {
        return $this->taskId;
    }

    /**
     * @param string|null $taskId
     */
    public function setTaskId(?string $taskId): void
    {
        $this->taskId = $taskId;
    }

    /**
     * @return int|null
     */
    public function getStatus(): ?int
    {
        return $this->status;
    }

    /**
     * @param int|null $status
     */
    public function setStatus(?int $status): void
    {
        $this->status = $status;
    }

    /**
     * @return mixed
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param mixed $type
     */
    public function setType($type): void
    {
        $this->type = $type;
    }

    /**
     * @return mixed
     */
    public function getExpression()
    {
        return $this->expression;
    }

    /**
     * @param mixed $expression
     */
    public function setExpression($expression): void
    {
        $this->expression = $expression;
    }

    /**
     * @return mixed
     */
    public function getNextRun()
    {
        return $this->nextRun;
    }

    /**
     * @param mixed $nextRun
     */
    public function setNextRun($nextRun): void
    {
        $this->nextRun = $nextRun;
    }

    /**
     * @return mixed
     */
    public function getTimeZone()
    {
        return $this->timeZone;
    }

    /**
     * @param mixed $timeZone
     */
    public function setTimeZone($timeZone): void
    {
        $this->timeZone = $timeZone;
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
