<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * BaseOptionGroup
 *
 * @ORM\Table(name="base_option_group")
 * @ORM\Entity
 */
class BaseOptionGroup
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer", nullable=false, options={"unsigned"=true})
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var string|null
     *
     * @ORM\Column(name="group_name", type="string", length=50, nullable=true, options={"comment"="组名称"})
     */
    private $groupName;

    /**
     * @var string|null
     *
     * @ORM\Column(name="group_key", type="string", length=50, nullable=true, options={"comment"="组key"})
     */
    private $groupKey;

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

    public function getGroupName(): ?string
    {
        return $this->groupName;
    }

    public function setGroupName(?string $groupName): self
    {
        $this->groupName = $groupName;

        return $this;
    }

    public function getGroupKey(): ?string
    {
        return $this->groupKey;
    }

    public function setGroupKey(?string $groupKey): self
    {
        $this->groupKey = $groupKey;

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
