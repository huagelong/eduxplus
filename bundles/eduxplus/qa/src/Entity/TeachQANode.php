<?php

namespace Eduxplus\QABundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * TeachQANode
 *
 * @ORM\Table(name="teach_qa_node")
 * @Gedmo\SoftDeleteable(fieldName="deletedAt", timeAware=false, hardDelete=true)
 * @ORM\Entity(repositoryClass="Eduxplus\QABundle\Repository\TeachQANodeRepository")
 */
class TeachQANode
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
     * @var integer|null
     *
     * @ORM\Column(name="type", type="integer",length=1, nullable=true, options={"comment"="试题类型, 0-单项选择,1-多项选择,2-不定项选择题，3-判断题，4-填空题，5-问答题，6-理解题"})
     */
    private $type;


    /**
     * @var integer|null
     *
     * @ORM\Column(name="level", type="integer",length=1, nullable=true, options={"comment"="试题难度, 0-容易,1-一般,2-困难"})
     */
    private $level;

    /**
     * @var int|null
     *
     * @ORM\Column(name="chapter_id", type="integer", nullable=true, options={"comment"="章节点集合id"})
     */
    private $chapterId;

    /**
     * @var int|null
     *
     * @ORM\Column(name="chapter_sub_id", type="integer", nullable=true, options={"comment"="章节点id"})
     */
    private $chapterSubId;

    /**
     * @var int|null
     *
     * @ORM\Column(name="node_type", type="integer", nullable=true, options={"comment"="题类，类似 0-常考题，1-易错题，2-好题，3-压轴题"})
     */
    private $nodeType;

    /**
     * @var string|null
     *
     * @ORM\Column(name="year", type="string", length=25, nullable=true, options={"comment"="年份"})
     */
    private $year;

    /**
     * @var string|null
     *
     * @ORM\Column(name="source", type="string", length=255, nullable=true, options={"comment"="来源,比如历年试题试卷"})
     */
    private $source;

    /**
     * @var int|null
     *
     * @ORM\Column(name="create_uid", type="integer", nullable=true, options={"comment"="后台添加人"})
     */
    private $createUid = '0';

    /**
     * @var string|null
     *
     * @ORM\Column(name="topic", type="text", length=16777215, nullable=true, options={"comment"="试题"})
     */
    private $topic;

    /**
     * @var bool|null
     *
     * @ORM\Column(name="status", type="boolean", nullable=true, options={"default"="0", "comment"="0-发布，1-未发布"})
     */
    private $status = '0';

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

    public function getType(): ?int
    {
        return $this->type;
    }

    public function setType(?int $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getLevel(): ?int
    {
        return $this->level;
    }

    public function setLevel(?int $level): self
    {
        $this->level = $level;

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

    public function getChapterSubId(): ?int
    {
        return $this->chapterSubId;
    }

    public function setChapterSubId(?int $chapterSubId): self
    {
        $this->chapterSubId = $chapterSubId;

        return $this;
    }

    public function getNodeType(): ?int
    {
        return $this->nodeType;
    }

    public function setNodeType(?int $nodeType): self
    {
        $this->nodeType = $nodeType;

        return $this;
    }

    public function getYear(): ?string
    {
        return $this->year;
    }

    public function setYear(?string $year): self
    {
        $this->year = $year;

        return $this;
    }

    public function getSource(): ?string
    {
        return $this->source;
    }

    public function setSource(?string $source): self
    {
        $this->source = $source;

        return $this;
    }

    public function getCreateUid(): ?int
    {
        return $this->createUid;
    }

    public function setCreateUid(?int $createUid): self
    {
        $this->createUid = $createUid;

        return $this;
    }

    public function getTopic(): ?string
    {
        return $this->topic;
    }

    public function setTopic(?string $topic): self
    {
        $this->topic = $topic;

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

}
