<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * MallGoodsGroup
 *
 * @ORM\Table(name="mall_goods_group")
 * @ORM\Entity
 */
class MallGoodsGroup
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
     * @ORM\Column(name="goods_id", type="integer", nullable=true, options={"comment"="商品id"})
     */
    private $goodsId;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=100, nullable=false, options={"comment"="组名"})
     */
    private $name = '';

    /**
     * @var string
     *
     * @ORM\Column(name="group_goods__id", type="string", length=100, nullable=false, options={"comment"="组名下商品id"})
     */
    private $groupGoodsId = '';

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

    public function getGoodsId(): ?int
    {
        return $this->goodsId;
    }

    public function setGoodsId(?int $goodsId): self
    {
        $this->goodsId = $goodsId;

        return $this;
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

    public function getGroupGoodsId(): ?string
    {
        return $this->groupGoodsId;
    }

    public function setGroupGoodsId(string $groupGoodsId): self
    {
        $this->groupGoodsId = $groupGoodsId;

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
