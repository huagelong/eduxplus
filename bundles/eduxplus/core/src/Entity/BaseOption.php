<?php

namespace Eduxplus\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * BaseOption
 *
 * @ORM\Table(name="base_option")
 * @Gedmo\SoftDeleteable(fieldName="deletedAt", timeAware=false, hardDelete=true)
 * @ORM\Entity(repositoryClass="Eduxplus\CoreBundle\Repository\BaseOptionRepository")
 */
class BaseOption
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
     * @var string|null
     *
     * @ORM\Column(name="option_key", type="string", length=50, nullable=true, options={"comment"="配置key"})
     */
    private $optionKey;

    /**
     * @var string|null
     *
     * @ORM\Column(name="option_value", type="text", length=65535, nullable=true, options={"comment"="配置值"})
     */
    private $optionValue;

    /**
     * @var string|null
     *
     * @ORM\Column(name="option_group", type="string", length=50, nullable=true, options={"comment"="配置组"})
     */
    private $optionGroup;

    /**
     * @var bool|null
     *
     * @ORM\Column(name="is_lock", type="boolean", nullable=true, options={"comment"="是否被锁定,1-是，0-否"})
     */
    private $isLock = '0';

    /**
     * @var bool|null
     *
     * @ORM\Column(name="type", type="integer", length=1, nullable=true, options={"comment"="配置类型, 1-文本, 2-文件链接"})
     */
    private $type = '1';

    /**
     * @ORM\Column(type="string", length=255, nullable=true, options={"comment"="描述"})
     */
    private $descr;

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

    public function getOptionKey(): ?string
    {
        return $this->optionKey;
    }

    public function setOptionKey(?string $optionKey): self
    {
        $this->optionKey = $optionKey;

        return $this;
    }

    public function getOptionValue(): ?string
    {
        return $this->optionValue;
    }

    public function setOptionValue(?string $optionValue): self
    {
        $this->optionValue = $optionValue;

        return $this;
    }

    public function getOptionGroup(): ?string
    {
        return $this->optionGroup;
    }

    public function setOptionGroup(?string $optionGroup): self
    {
        $this->optionGroup = $optionGroup;

        return $this;
    }

    public function getIsLock(): ?bool
    {
        return $this->isLock;
    }

    public function setIsLock(?bool $isLock): self
    {
        $this->isLock = $isLock;

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

    public function getDescr(): ?string
    {
        return $this->descr;
    }

    public function setDescr(?string $descr): self
    {
        $this->descr = $descr;

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
