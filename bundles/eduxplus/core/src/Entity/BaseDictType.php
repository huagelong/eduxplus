<?php

namespace Eduxplus\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * BaseDictType
 *
 * @ORM\Table(name="base_dict_type")
 * @Gedmo\SoftDeleteable(fieldName="deletedAt", timeAware=false, hardDelete=true)
 * @ORM\Entity(repositoryClass="Eduxplus\CoreBundle\Repository\BaseDictTypeRepository")
 * @ORM\Cache(usage="NONSTRICT_READ_WRITE")
 */
class BaseDictType
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
     * @var string|null
     *
     * @ORM\Column(name="dict_name", type="string", length=100, nullable=true, options={"comment"="字典名称"})
     */
    private $dictName;

    /**
     * @var string|null
     *
     * @ORM\Column(name="dict_key", type="string", length=100, nullable=true, options={"comment"="字典key"})
     */
    private $dictKey;


    /**
     * @var int|null
     *
     * @ORM\Column(name="status", type="integer", length=1, nullable=true, options={"comment"="配置类型, 1-开启, 0-关闭"})
     */
    private $status = '1';

    /**
     * @ORM\Column(name="descr", type="string", length=255, nullable=true, options={"comment"="描述"})
     */
    private $descr;

    /**
     * @var string|null
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(name="created_at", type="datetime", nullable=true)
     */
    private $createdAt;

    /**
     * @var string|null
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

    /**
     * @return string|null
     */
    public function getDictName(): ?string
    {
        return $this->dictName;
    }

    /**
     * @param string|null $dictName
     */
    public function setDictName(?string $dictName): void
    {
        $this->dictName = $dictName;
    }

    /**
     * @return string|null
     */
    public function getDictKey(): ?string
    {
        return $this->dictKey;
    }

    /**
     * @param string|null $dictKey
     */
    public function setDictKey(?string $dictKey): void
    {
        $this->dictKey = $dictKey;
    }

    /**
     * @return int|null
     */
    public function getStatus(): ?int
    {
        return $this->status;
    }

    /**
     * @param int|null $status
     */
    public function setStatus(?int $status): void
    {
        $this->status = $status;
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
