<?php

namespace Eduxplus\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * BaseScheduleLog
 *
 * @ORM\Table(name="base_schedule_log", indexes={@ORM\Index(name="task_id_idx", columns={"task_id"})})
 * @Gedmo\SoftDeleteable(fieldName="deletedAt", timeAware=false, hardDelete=true)
 * @ORM\Entity(repositoryClass="Eduxplus\CoreBundle\Repository\BaseScheduleLogRepository")
 */
class BaseScheduleLog
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
     * @ORM\Column(name="run_info", type="string", length=255, nullable=true, options={"comment"="执行信息"})
     */
    private $runInfo;

    /**
     * @var string|null
     *
     * @ORM\Column(name="task_id", type="string", length=100, nullable=true, options={"comment"="任务ID"})
     */
    private $taskId;

    /**
     * @ORM\Column(name="result", type="text", length=65535, nullable=true, options={"comment"="任务结果"})
     */
    private $result;

    /**
     * @ORM\Column(name="start_time", type="string", length=100, nullable=true, options={"comment"="开始运行时间"})
     */
    private $startTime;

    /**
     * @ORM\Column(name="run_type", type="string", length=100, nullable=true, options={"comment"="运行类型"})
     */
    private $runType;

    /**
     * @var string|null
     *
     * @ORM\Column(name="ip", type="string", length=20, nullable=true)
     */
    private $ip;

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
    public function getRunInfo(): ?string
    {
        return $this->runInfo;
    }

    /**
     * @param string|null $runInfo
     */
    public function setRunInfo(?string $runInfo): void
    {
        $this->runInfo = $runInfo;
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
     * @return mixed
     */
    public function getResult()
    {
        return $this->result;
    }

    /**
     * @param mixed $result
     */
    public function setResult($result): void
    {
        $this->result = $result;
    }

    /**
     * @return mixed
     */
    public function getStartTime()
    {
        return $this->startTime;
    }

    /**
     * @param mixed $startTime
     */
    public function setStartTime($startTime): void
    {
        $this->startTime = $startTime;
    }

    /**
     * @return mixed
     */
    public function getRunType()
    {
        return $this->runType;
    }

    /**
     * @param mixed $runType
     */
    public function setRunType($runType): void
    {
        $this->runType = $runType;
    }

    /**
     * @return string|null
     */
    public function getIp(): ?string
    {
        return $this->ip;
    }

    /**
     * @param string|null $ip
     */
    public function setIp(?string $ip): void
    {
        $this->ip = $ip;
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
