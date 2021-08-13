<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * TeachCourseVideos
 *
 * @ORM\Table(name="teach_course_videos")
 * @Gedmo\SoftDeleteable(fieldName="deletedAt", timeAware=false, hardDelete=true)
 * @ORM\Entity(repositoryClass="App\Repository\TeachCourseVideosRepository")
 */
class TeachCourseVideos
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
     * @var int|null
     *
     * @ORM\Column(name="chapter_id", type="integer", nullable=true, options={"comment"="章节id"})
     */
    private $chapterId;

    /**
     * @var bool|null
     *
     * @ORM\Column(name="type", type="integer", length=1,nullable=true, options={"comment"="1-直播,2-点播, 点播可以是直播转换过来"})
     */
    private $type;

    /**
     * @var bool|null
     *
     * @ORM\Column(name="video_channel", type="integer",length=1, nullable=true, options={"comment"="1-腾讯云,2-阿里云"})
     */
    private $videoChannel;

    /**
     * @var string|null
     *
     * @ORM\Column(name="video_id", type="string", length=60, nullable=true, options={"comment"="点播视频id"})
     */
    private $videoId;

    /**
     * @var string|null
     *
     * @ORM\Column(name="vod_data", type="text", length=65535, nullable=true, options={"comment"="点播数据"})
     */
    private $vodData;

    /**
     * @var string|null
     *
     * @ORM\Column(name="live_data", type="text", length=65535, nullable=true, options={"comment"="直播数据"})
     */
    private $liveData;

    /**
     * @var bool|null
     *
     * @ORM\Column(name="status", type="boolean", nullable=true, options={"comment"="点播是否可用，转码，审核已完成等，1-是,0-否"})
     */
    private $status=0;

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

    public function getChapterId(): ?int
    {
        return $this->chapterId;
    }

    public function setChapterId(?int $chapterId): self
    {
        $this->chapterId = $chapterId;

        return $this;
    }

    public function getType(): ?int
    {
        return $this->type;
    }

    public function setType(?int $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getVideoChannel(): ?int
    {
        return $this->videoChannel;
    }

    public function setVideoChannel(?int $videoChannel): self
    {
        $this->videoChannel = $videoChannel;

        return $this;
    }

    public function getVideoId(): ?string
    {
        return $this->videoId;
    }

    public function setVideoId(?string $videoId): self
    {
        $this->videoId = $videoId;

        return $this;
    }

    public function getVodData(): ?string
    {
        return $this->vodData;
    }

    public function setVodData(?string $vodData): self
    {
        $this->vodData = $vodData;

        return $this;
    }

    public function getStatus(): ?bool
    {
        return $this->status;
    }

    public function setStatus(?bool $status): self
    {
        $this->status = $status;

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

    public function getLiveData(): ?string
    {
        return $this->liveData;
    }

    public function setLiveData(?string $liveData): self
    {
        $this->liveData = $liveData;

        return $this;
    }


}
