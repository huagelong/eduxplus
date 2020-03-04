<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * JwTeacher
 *
 * @ORM\Table(name="jw_teacher")
 * @ORM\Entity
 */
class JwTeacher
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
     * @ORM\Column(name="name", type="string", length=200, nullable=true)
     */
    private $name;

    /**
     * @var string|null
     *
     * @ORM\Column(name="descr", type="text", length=65535, nullable=true)
     */
    private $descr;

    /**
     * @var string|null
     *
     * @ORM\Column(name="pinyin", type="string", length=200, nullable=true, options={"comment"="名称拼音"})
     */
    private $pinyin;

    /**
     * @var bool|null
     *
     * @ORM\Column(name="type", type="boolean", nullable=true, options={"comment"="1-网课老师，2-分校老师，3-全部"})
     */
    private $type;

    /**
     * @var int|null
     *
     * @ORM\Column(name="school_id", type="integer", nullable=true, options={"comment"="所属分校"})
     */
    private $schoolId;

    /**
     * @var bool|null
     *
     * @ORM\Column(name="status", type="boolean", nullable=true, options={"default"="1","comment"="是否有效"})
     */
    private $status = true;

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

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): self
    {
        $this->name = $name;

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

    public function getPinyin(): ?string
    {
        return $this->pinyin;
    }

    public function setPinyin(?string $pinyin): self
    {
        $this->pinyin = $pinyin;

        return $this;
    }

    public function getType(): ?bool
    {
        return $this->type;
    }

    public function setType(?bool $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getSchoolId(): ?int
    {
        return $this->schoolId;
    }

    public function setSchoolId(?int $schoolId): self
    {
        $this->schoolId = $schoolId;

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
