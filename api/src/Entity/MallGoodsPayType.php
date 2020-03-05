<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * MallGoodsPayType
 *
 * @ORM\Table(name="mall_goods_pay_type")
 * @ORM\Entity(repositoryClass="App\Repository\MallGoodsPayTypeRepository")
 */
class MallGoodsPayType
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
     * @ORM\Column(name="pay_name", type="string", length=50, nullable=false, options={"comment"="支付名称"})
     */
    private $payName = '';

    /**
     * @var int
     *
     * @ORM\Column(name="pay_price", type="integer", nullable=false, options={"comment"="支付价格"})
     */
    private $payPrice = '0';

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

    public function getPayName(): ?string
    {
        return $this->payName;
    }

    public function setPayName(string $payName): self
    {
        $this->payName = $payName;

        return $this;
    }

    public function getPayPrice(): ?int
    {
        return $this->payPrice;
    }

    public function setPayPrice(int $payPrice): self
    {
        $this->payPrice = $payPrice;

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
