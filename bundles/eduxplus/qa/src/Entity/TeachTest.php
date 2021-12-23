<?php

namespace Eduxplus\QaBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * TeachTest 试卷
 *
 * @ORM\Table(name="teach_test")
 * @Gedmo\SoftDeleteable(fieldName="deletedAt", timeAware=false, hardDelete=true)
 * @ORM\Entity(repositoryClass="Eduxplus\QaBundle\Repository\TeachTestRepository")
 */
class TeachTest
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
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=254, nullable=false, options={"comment"="试卷名称"})
     */
    private $name = '';

    /**
     * @var int|null
     *
     * @ORM\Column(name="create_uid", type="integer", nullable=true, options={"comment"="后台添加人"})
     */
    private $createUid = '0';

    /**
     * @var int|null
     *
     * @ORM\Column(name="score", type="integer", nullable=true, options={"comment"="分数"})
     */
    private $score = '0';

        /**
     * @var int|null
     *
     * @ORM\Column(name="view_number", type="integer", nullable=true, options={"comment"="浏览数"})
     */
    private $viewNumber = '0';

    /**
     * @var int|null
     *
     * @ORM\Column(name="expire_time", type="integer", nullable=true, options={"comment"="考试时长"})
     */
    private $expireTime = '0';


    /**
     * @var bool|null
     *
     * @ORM\Column(name="status", type="boolean", nullable=true, options={"default"="0", "comment"="0-发布，1-未发布"})
     */
    private $status = '0';

    /**
     * @var int|null
     *
     * @ORM\Column(name="category_id", type="integer", nullable=true, options={"comment"="类目id"})
     */
    private $categoryId;

    /**
     * @var int|null
     *
     * @ORM\Column(name="sort", type="integer", nullable=true, options={"comment"="排序"})
     */
    private $sort;

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

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

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

    public function getScore(): ?int
    {
        return $this->score;
    }

    public function setScore(?int $score): self
    {
        $this->score = $score;

        return $this;
    }

    public function getViewNumber(): ?int
    {
        return $this->viewNumber;
    }

    public function setViewNumber(?int $viewNumber): self
    {
        $this->viewNumber = $viewNumber;

        return $this;
    }

    public function getExpireTime(): ?int
    {
        return $this->expireTime;
    }

    public function setExpireTime(?int $expireTime): self
    {
        $this->expireTime = $expireTime;

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

    public function getCategoryId(): ?int
    {
        return $this->categoryId;
    }

    public function setCategoryId(?int $categoryId): self
    {
        $this->categoryId = $categoryId;

        return $this;
    }

    public function getSort(): ?int
    {
        return $this->sort;
    }

    public function setSort(?int $sort): self
    {
        $this->sort = $sort;

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
