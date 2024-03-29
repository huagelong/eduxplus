<?php

namespace Eduxplus\EduxBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * TeachCategory
 *
 * @ORM\Table(name="teach_category", indexes={@ORM\Index(name="parent_id", columns={"parent_id"})})
 * @Gedmo\SoftDeleteable(fieldName="deletedAt",timeAware=false, hardDelete=true)
 * @ORM\Entity(repositoryClass="Eduxplus\EduxBundle\Repository\TeachCategoryRepository")
 * @ORM\Cache(usage="NONSTRICT_READ_WRITE")
 */
class TeachCategory
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
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=90, nullable=false, options={"comment"="分类名称"})
     */
    private $name = '';


    /**
     * @var string
     *
     * @ORM\Column(name="find_path", type="string", length=90, nullable=false, options={"comment"="分类路由,以,隔开"})
     */
    private $findPath = '';

    /**
     * @var int
     *
     * @ORM\Column(name="parent_id", type="bigint", nullable=false, options={"unsigned"=true,"comment"="父id"})
     */
    private $parentId = '0';

    /**
     * @var int|null
     *
     * @ORM\Column(name="sort", type="integer", nullable=true, options={"comment"="排序"})
     */
    private $sort;

    /**
     * @var bool
     *
     * @ORM\Column(name="is_show", type="boolean", nullable=false, options={"default"="1","comment"="是否显示"})
     */
    private $isShow ='1';

    /**
     * @var string|null
     *
     * @ORM\Column(name="mobile_icon", type="string", length=400, nullable=true, options={"comment"="移动端分类图标"})
     */
    private $mobileIcon;

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

    public function getFindPath(): ?string
    {
        return $this->findPath;
    }

    public function setFindPath(string $findPath): self
    {
        $this->findPath = $findPath;

        return $this;
    }

    public function getParentId(): ?int
    {
        return $this->parentId;
    }

    public function setParentId(int $parentId): self
    {
        $this->parentId = $parentId;

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

    public function getIsShow(): ?bool
    {
        return $this->isShow;
    }

    public function setIsShow(bool $isShow): self
    {
        $this->isShow = $isShow;

        return $this;
    }

    public function getMobileIcon(): ?string
    {
        return $this->mobileIcon;
    }

    public function setMobileIcon(?string $mobileIcon): self
    {
        $this->mobileIcon = $mobileIcon;

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
