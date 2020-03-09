<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Entity(repositoryClass="App\Repository\BaseUserRepository")
 */
class BaseUser implements UserInterface
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(name="mobile",type="string", length=12, unique=true)
     */
    private $mobile;

    /**
     * @var string|null
     *
     * @ORM\Column(name="full_name", type="string", length=100, nullable=true, options={"comment"="姓名"})
     */
    private $fullName;

    /**
     * @var string|null
     *
     * @ORM\Column(name="display_name", type="string", length=100, nullable=true, options={"comment"="昵称"})
     */
    private $displayName;

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
     * @var int|null
     *
     * @ORM\Column(name="report_uid", type="integer", nullable=true, options={"default"="1","comment"="汇报上级"})
     */
    private $reportUid = '1';


    /**
     * @var bool|null
     *
     * @ORM\Column(name="is_lock", type="boolean", nullable=true, options={"comment"="是否被锁定,1-是，0-否"})
     */
    private $isLock = '0';


    /**
     * @var string|null
     *
     * @ORM\Column(name="reg_source", type="string", length=11, nullable=true, options={"comment"="pc,ios,android"})
     */
    private $regSource;

    /**
     * @ORM\Column(type="json")
     */
    private $roles = [];

    /**
     * @var string The hashed password
     * @ORM\Column(type="string")
     */
    private $password;



    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUsername(): string
    {
        return (string) $this->mobile;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getPassword(): string
    {
        return (string) $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getSalt()
    {
        // not needed when using the "bcrypt" algorithm in security.yaml
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getMobile(): ?string
    {
        return $this->mobile;
    }

    public function setMobile(string $mobile): self
    {
        $this->mobile = $mobile;

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

    public function getDisplayName(): ?string
    {
        return $this->displayName;
    }

    public function setDisplayName(?string $displayName): self
    {
        $this->displayName = $displayName;

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

    public function getReportUid(): ?int
    {
        return $this->reportUid;
    }

    public function setReportUid(?int $reportUid): self
    {
        $this->reportUid = $reportUid;

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

    public function getRegSource(): ?string
    {
        return $this->regSource;
    }

    public function setRegSource(?string $regSource): self
    {
        $this->regSource = $regSource;

        return $this;
    }
}
