<?php

namespace Eduxplus\QaBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * TeachQANodeSub
 *
 * @ORM\Table(name="teach_qa_node_sub", indexes={@ORM\Index(name="qa_node_id_idx", columns={"qa_node_id"})})
 * @Gedmo\SoftDeleteable(fieldName="deletedAt", timeAware=false, hardDelete=true)
 * @ORM\Entity(repositoryClass="Eduxplus\QaBundle\Repository\TeachQANodeSubRepository")
 */
class TeachQANodeSub
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
     * @ORM\Column(name="qa_node_id", type="bigint", nullable=true, options={"comment"="试题节点id"})
     */
    private $qaNodeId;

    /**
     * @var string 知识点
     *
     * @ORM\Column(name="knowledge", type="text", length=16777215, nullable=true, options={"comment"="知识点"})
     */
    private $knowledge = '';

    /**
     * @var string|null
     *
     * @ORM\Column(name="options", type="text", length=16777215, nullable=true, options={"comment"="选择项json"})
     */
    private $options='';

    /**
     * @var string|null
     *
     * @ORM\Column(name="analysis", type="text", length=16777215, nullable=true, options={"comment"="解析"})
     */
    private $analysis='';

    /**
     * @var string|null
     *
     * @ORM\Column(name="answer", type="text", length=16777215, nullable=true, options={"comment"="答案"})
     */
    private $answer;

    /**
     * @var int|null
     *
     * @ORM\Column(name="score", type="bigint", nullable=true, options={"comment"="得分，少选取50%分数的四舍五入值"})
     */
    private $score;

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

    public function getQaNodeId(): ?int
    {
        return $this->qaNodeId;
    }

    public function setQaNodeId(?int $qaNodeId): self
    {
        $this->qaNodeId = $qaNodeId;

        return $this;
    }

    public function getKnowledge(): ?string
    {
        return $this->knowledge;
    }

    public function setKnowledge(?string $knowledge): self
    {
        $this->knowledge = $knowledge;

        return $this;
    }

    public function getOptions(): ?string
    {
        return $this->options;
    }

    public function setOptions(?string $options): self
    {
        $this->options = $options;

        return $this;
    }

    public function getAnalysis(): ?string
    {
        return $this->analysis;
    }

    public function setAnalysis(?string $analysis): self
    {
        $this->analysis = $analysis;

        return $this;
    }

    public function getAnswer(): ?string
    {
        return $this->answer;
    }

    public function setAnswer(?string $answer): self
    {
        $this->answer = $answer;

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
