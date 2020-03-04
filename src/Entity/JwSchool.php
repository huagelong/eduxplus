<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * JwSchool
 *
 * @ORM\Table(name="jw_school")
 * @ORM\Entity(repositoryClass="App\Repository\JwSchoolRepository")
 */
class JwSchool
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
     * @ORM\Column(name="name", type="string", length=100, nullable=true, options={"comment"="学校名称"})
     */
    private $name;

    /**
     * @var string|null
     *
     * @ORM\Column(name="address", type="string", length=200, nullable=true, options={"comment"="学校地址"})
     */
    private $address;

    /**
     * @var string|null
     *
     * @ORM\Column(name="descr", type="text", length=65535, nullable=true, options={"comment"="描叙"})
     */
    private $descr;

    /**
     * @var string|null
     *
     * @ORM\Column(name="state_code", type="string", length=64, nullable=true, options={"comment"="省份"})
     */
    private $stateCode;

    /**
     * @var int|null
     *
     * @ORM\Column(name="city_id", type="integer", nullable=true, options={"comment"="城市id"})
     */
    private $cityId;

    /**
     * @var string|null
     *
     * @ORM\Column(name="linkin", type="string", length=100, nullable=true, options={"comment"="联系方式"})
     */
    private $linkin = '';

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

    public function getAddress(): ?string
    {
        return $this->address;
    }

    public function setAddress(?string $address): self
    {
        $this->address = $address;

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

    public function getStateCode(): ?string
    {
        return $this->stateCode;
    }

    public function setStateCode(?string $stateCode): self
    {
        $this->stateCode = $stateCode;

        return $this;
    }

    public function getCityId(): ?int
    {
        return $this->cityId;
    }

    public function setCityId(?int $cityId): self
    {
        $this->cityId = $cityId;

        return $this;
    }

    public function getLinkin(): ?string
    {
        return $this->linkin;
    }

    public function setLinkin(?string $linkin): self
    {
        $this->linkin = $linkin;

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
