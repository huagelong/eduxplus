<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * BaseUser
 *
 * @ORM\Table(name="base_user")
 * @ORM\Entity(repositoryClass="App\Repository\BaseUserRepository")
 */
class BaseUser
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
     * @ORM\Column(name="mobile", type="string", length=12, nullable=true)
     */
    private $mobile;

    /**
     * @var string|null
     *
     * @ORM\Column(name="display_name", type="string", length=100, nullable=true, options={"comment"="昵称"})
     */
    private $displayName;

    /**
     * @var string|null
     *
     * @ORM\Column(name="full_name", type="string", length=100, nullable=true, options={"comment"="姓名"})
     */
    private $fullName;

    /**
     * @var string|null
     *
     * @ORM\Column(name="face_img", type="string", length=250, nullable=true, options={"comment"="人物头像"})
     */
    private $faceImg;

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="birthday", type="date", nullable=true, options={"comment"="生日"})
     */
    private $birthday;

    /**
     * @var bool|null
     *
     * @ORM\Column(name="sex", type="boolean", nullable=true, options={"comment"="1-男,2-女"})
     */
    private $sex;

    /**
     * @var string|null
     *
     * @ORM\Column(name="passwd", type="string", length=100, nullable=true)
     */
    private $passwd;

    /**
     * @var bool
     *
     * @ORM\Column(name="is_admin", type="boolean", nullable=false, options={"comment"="是否是管理员账号"})
     */
    private $isAdmin = '0';

    /**
     * @var bool|null
     *
     * @ORM\Column(name="is_lock", type="boolean", nullable=true, options={"comment"="是否被锁定,1-是，0-否"})
     */
    private $isLock = '0';

    /**
     * @var int|null
     *
     * @ORM\Column(name="last_login_time", type="integer", nullable=true)
     */
    private $lastLoginTime;

    /**
     * @var int|null
     *
     * @ORM\Column(name="report_uid", type="integer", nullable=true, options={"default"="1","comment"="汇报上级"})
     */
    private $reportUid = '1';

    /**
     * @var string|null
     *
     * @ORM\Column(name="reg_source", type="string", length=11, nullable=true, options={"comment"="pc,ios,android"})
     */
    private $regSource;

    /**
     * @var string|null
     *
     * @ORM\Column(name="pc_login_token", type="string", length=100, nullable=true)
     */
    private $pcLoginToken;

    /**
     * @var string|null
     *
     * @ORM\Column(name="app_login_token", type="string", length=100, nullable=true)
     */
    private $appLoginToken;

    /**
     * @var int|null
     *
     * @ORM\Column(name="updated_at", type="integer", nullable=true)
     */
    private $updatedAt;

    /**
     * @var int|null
     *
     * @ORM\Column(name="created_at", type="integer", nullable=true)
     */
    private $createdAt;

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

    public function getDisplayName(): ?string
    {
        return $this->displayName;
    }

    public function setDisplayName(?string $displayName): self
    {
        $this->displayName = $displayName;

        return $this;
    }

    public function getFullName(): ?string
    {
        return $this->fullName;
    }

    public function setFullName(?string $fullName): self
    {
        $this->fullName = $fullName;

        return $this;
    }

    public function getFaceImg(): ?string
    {
        return $this->faceImg;
    }

    public function setFaceImg(?string $faceImg): self
    {
        $this->faceImg = $faceImg;

        return $this;
    }

    public function getBirthday(): ?\DateTimeInterface
    {
        return $this->birthday;
    }

    public function setBirthday(?\DateTimeInterface $birthday): self
    {
        $this->birthday = $birthday;

        return $this;
    }

    public function getSex(): ?bool
    {
        return $this->sex;
    }

    public function setSex(?bool $sex): self
    {
        $this->sex = $sex;

        return $this;
    }

    public function getPasswd(): ?string
    {
        return $this->passwd;
    }

    public function setPasswd(?string $passwd): self
    {
        $this->passwd = $passwd;

        return $this;
    }

    public function getIsAdmin(): ?bool
    {
        return $this->isAdmin;
    }

    public function setIsAdmin(bool $isAdmin): self
    {
        $this->isAdmin = $isAdmin;

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

    public function getLastLoginTime(): ?int
    {
        return $this->lastLoginTime;
    }

    public function setLastLoginTime(?int $lastLoginTime): self
    {
        $this->lastLoginTime = $lastLoginTime;

        return $this;
    }

    public function getReportUid(): ?int
    {
        return $this->reportUid;
    }

    public function setReportUid(?int $reportUid): self
    {
        $this->reportUid = $reportUid;

        return $this;
    }

    public function getRegSource(): ?string
    {
        return $this->regSource;
    }

    public function setRegSource(?string $regSource): self
    {
        $this->regSource = $regSource;

        return $this;
    }

    public function getPcLoginToken(): ?string
    {
        return $this->pcLoginToken;
    }

    public function setPcLoginToken(?string $pcLoginToken): self
    {
        $this->pcLoginToken = $pcLoginToken;

        return $this;
    }

    public function getAppLoginToken(): ?string
    {
        return $this->appLoginToken;
    }

    public function setAppLoginToken(?string $appLoginToken): self
    {
        $this->appLoginToken = $appLoginToken;

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

    public function getCreatedAt(): ?int
    {
        return $this->createdAt;
    }

    public function setCreatedAt(?int $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }


}
