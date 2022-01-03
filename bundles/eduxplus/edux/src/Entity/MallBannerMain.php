<?php

namespace Eduxplus\EduxBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * MallBannerMain
 *
 * @ORM\Table(name="mall_banner_main")
 * @Gedmo\SoftDeleteable(fieldName="deletedAt", timeAware=false, hardDelete=true)
 * @ORM\Entity(repositoryClass="Eduxplus\EduxBundle\Repository\MallHelpRepository")
 */
class MallBannerMain
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
     * @ORM\Column(name="banner_id", type="bigint", nullable=false, options={"unsigned"=true,"comment"="广告banner id"})
     */
    private $bannerId = '0';

    /**
     * @var int
     *
     * @ORM\Column(name="uid", type="bigint", nullable=false, options={"unsigned"=true,"comment"="最近操作人id"})
     */
    private $uid = '0';

    /**
     * @var string|null
     *
     * @ORM\Column(name="banner_img", type="string", length=255, nullable=true, options={"unsigned"=true,"comment"="banner图片"})
     */
    private $bannerImg;

    /**
     * @var string|null
     *
     * @ORM\Column(name="url", type="string", length=255, nullable=true,options={"unsigned"=true,"comment"="链接"})
     */
    private $url;

    /**
     * @var string|null
     *
     * @ORM\Column(name="sort", type="integer", nullable=true,options={"unsigned"=true,"comment"="排序"})
     */
    private $sort='0';

    /**
     * @var bool|null
     *
     * @ORM\Column(name="status", type="boolean", nullable=true, options={"comment"="0-未上架,1-已上架"})
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

    public function getBannerId(): ?int
    {
        return $this->bannerId;
    }

    public function setBannerId(int $bannerId): self
    {
        $this->bannerId = $bannerId;

        return $this;
    }

    public function getBannerImg(): ?string
    {
        return $this->bannerImg;
    }

    public function setBannerImg(?string $bannerImg): self
    {
        $this->bannerImg = $bannerImg;

        return $this;
    }

    public function getUrl(): ?string
    {
        return $this->url;
    }

    public function setUrl(?string $url): self
    {
        $this->url = $url;

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

    public function getUid(): ?int
    {
        return $this->uid;
    }

    public function setUid(int $uid): self
    {
        $this->uid = $uid;

        return $this;
    }
}
