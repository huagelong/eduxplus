<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * MallGoodsIntroduce
 *
 * @ORM\Table(name="mall_goods_introduce")
 * @ORM\Entity
 */
class MallGoodsIntroduce
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
     * @ORM\Column(name="goods_id", type="integer", nullable=true)
     */
    private $goodsId;

    /**
     * @var bool|null
     *
     * @ORM\Column(name="introduce_type", type="boolean", nullable=true, options={"comment"="课程介绍类型,1-版型介绍， 2-特色服务，3-适用人群，4-学习目标，0-图文介绍"})
     */
    private $introduceType = '0';

    /**
     * @var string|null
     *
     * @ORM\Column(name="content", type="text", length=65535, nullable=true, options={"comment"="课程介绍内容"})
     */
    private $content;

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

    /**
     * @var string|null
     *
     * @ORM\Column(name="href", type="text", length=65535, nullable=true, options={"comment"="视频ID"})
     */
    private $href;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getGoodsId(): ?int
    {
        return $this->goodsId;
    }

    public function setGoodsId(?int $goodsId): self
    {
        $this->goodsId = $goodsId;

        return $this;
    }

    public function getIntroduceType(): ?bool
    {
        return $this->introduceType;
    }

    public function setIntroduceType(?bool $introduceType): self
    {
        $this->introduceType = $introduceType;

        return $this;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(?string $content): self
    {
        $this->content = $content;

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

    public function getHref(): ?string
    {
        return $this->href;
    }

    public function setHref(?string $href): self
    {
        $this->href = $href;

        return $this;
    }


}
