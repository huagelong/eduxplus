<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * BaseOption
 *
 * @ORM\Table(name="base_option")
 * @Gedmo\SoftDeleteable(fieldName="deletedAt", timeAware=false, hardDelete=true)
 * @ORM\Entity(repositoryClass="App\Repository\BaseOptionRepository")
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
     * @ORM\Column(name="option_name", type="string", length=50, nullable=true, options={"comment"="配置名称"})
     */
    private $optionName;

    /**
     * @var string|null
     *
     * @ORM\Column(name="option_key", type="string", length=50, nullable=true, options={"comment"="配置key"})
     */
    private $optionKey;

    /**
     * @var int|null
     *
     * @ORM\Column(name="group_id", type="integer", nullable=true, options={"comment"="group id"})
     */
    private $groupId;

    /**
     * @var string|null
     *
     * @ORM\Column(name="option_value", type="text", length=65535, nullable=true, options={"comment"="配置值"})
     */
    private $optionValue;

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

    public function getOptionName(): ?string
    {
        return $this->optionName;
    }

    public function setOptionName(?string $optionName): self
    {
        $this->optionName = $optionName;

        return $this;
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

    public function getGroupId(): ?int
    {
        return $this->groupId;
    }

    public function setGroupId(?int $groupId): self
    {
        $this->groupId = $groupId;

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
