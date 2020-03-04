<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * MallMobileSmsCode
 *
 * @ORM\Table(name="mall_mobile_sms_code")
 * @ORM\Entity(repositoryClass="App\Repository\MallMobileSmsCodeRepository")
 */
class MallMobileSmsCode
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
     * @ORM\Column(name="mobile", type="string", length=13, nullable=true, options={"comment"="手机号码"})
     */
    private $mobile;

    /**
     * @var string|null
     *
     * @ORM\Column(name="type", type="string", length=25, nullable=true, options={"comment"="验证码类型"})
     */
    private $type;

    /**
     * @var string|null
     *
     * @ORM\Column(name="code", type="string", length=25, nullable=true, options={"comment"="验证码"})
     */
    private $code;

    /**
     * @var int
     *
     * @ORM\Column(name="created_at", type="integer", nullable=false)
     */
    private $createdAt;

    /**
     * @var int
     *
     * @ORM\Column(name="updated_at", type="integer", nullable=false)
     */
    private $updatedAt;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMobile(): ?string
    {
        return $this->mobile;
    }

    public function setMobile(?string $mobile): self
    {
        $this->mobile = $mobile;

        return $this;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(?string $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getCode(): ?string
    {
        return $this->code;
    }

    public function setCode(?string $code): self
    {
        $this->code = $code;

        return $this;
    }

    public function getCreatedAt(): ?int
    {
        return $this->createdAt;
    }

    public function setCreatedAt(int $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUpdatedAt(): ?int
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(int $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }


}
