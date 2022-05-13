<?php

namespace Eduxplus\EduxBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * MallGoodsFav
 * 商品收藏
 * @ORM\Table(name="mall_goods_fav", indexes={@ORM\Index(name="uid_idx", columns={"uid"})})
 * @Gedmo\SoftDeleteable(fieldName="deletedAt", timeAware=false, hardDelete=true)
 * @ORM\Entity(repositoryClass="Eduxplus\EduxBundle\Repository\MallGoodsFavRepository")
 */
class MallGoodsFav
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
     * @var int
     *
     * @ORM\Column(name="uid", type="bigint", nullable=false, options={"unsigned"=true,"comment"="用户uid"})
     */
    private $uid = '0';


    /**
     * @var int
     *
     * @ORM\Column(name="goods_id", type="bigint", nullable=false, options={"unsigned"=true,"comment"="商品id"})
     */
    private $goodsId = '0';


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

    public function getUid(): ?int
    {
        return $this->uid;
    }

    public function setUid(int $uid): self
    {
        $this->uid = $uid;

        return $this;
    }

    public function getGoodsId(): ?int
    {
        return $this->goodsId;
    }

    public function setGoodsId(int $goodsId): self
    {
        $this->goodsId = $goodsId;

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
