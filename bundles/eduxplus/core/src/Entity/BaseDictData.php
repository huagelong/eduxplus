<?php

namespace Eduxplus\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * BaseDictData
 * @ORM\Table(name="base_dict_data", indexes={@ORM\Index(name="dict_type_id_idx", columns={"dict_type_id"}),@ORM\Index(name="dict_label_idx", columns={"dict_label"})})
 * @Gedmo\SoftDeleteable(fieldName="deletedAt", timeAware=false, hardDelete=true)
 * @ORM\Entity(repositoryClass="Eduxplus\CoreBundle\Repository\BaseDictDataRepository")
 */
class BaseDictData
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
     * @var int|null
     *
     * @ORM\Column(name="dict_type_id", type="bigint",nullable=true, options={"comment"="字典id"})
     */
    private $dictTypeId;

    /**
     * @var string|null
     *
     * @ORM\Column(name="dict_label", type="string", length=100, nullable=true, options={"comment"="字典标签"})
     */
    private $dictLabel;

    /**
     * @var string|null
     *
     * @ORM\Column(name="dict_value", type="text",length=65535, nullable=true, options={"comment"="字典数据值"})
     */
    private $dictValue;


    /**
     * @var int|null
     *
     * @ORM\Column(name="status", type="integer", length=1, nullable=true, options={"comment"="配置类型, 1-开启, 0-关闭"})
     */
    private $status = '1';


    /**
     * @var int|null
     *
     * @ORM\Column(name="fsort", type="integer", length=11, nullable=true, options={"comment"="排序"})
     */
    private $fsort = '0';

    /**
     * @ORM\Column(type="string", length=255, nullable=true, options={"comment"="描述"})
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
     * @return int|null
     */
    public function getDictTypeId(): ?int
    {
        return $this->dictTypeId;
    }

    /**
     * @param int|null $dictTypeId
     */
    public function setDictTypeId(?int $dictTypeId): void
    {
        $this->dictTypeId = $dictTypeId;
    }

    /**
     * @return string|null
     */
    public function getDictLabel(): ?string
    {
        return $this->dictLabel;
    }

    /**
     * @param string|null $dictLabel
     */
    public function setDictLabel(?string $dictLabel): void
    {
        $this->dictLabel = $dictLabel;
    }

    /**
     * @return string|null
     */
    public function getDictValue(): ?string
    {
        return $this->dictValue;
    }

    /**
     * @param string|null $dictValue
     */
    public function setDictValue(?string $dictValue): void
    {
        $this->dictValue = $dictValue;
    }

    /**
     * @return int|null
     */
    public function getFsort(): ?int
    {
        return $this->fsort;
    }

    /**
     * @param int|null $fsort
     */
    public function setFsort(?int $fsort): void
    {
        $this->fsort = $fsort;
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
