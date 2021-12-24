<?php

namespace Eduxplus\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Table(name="base_user")
 * @Gedmo\SoftDeleteable(fieldName="deletedAt", timeAware=false, hardDelete=true)
 * @ORM\Entity(repositoryClass="Eduxplus\CoreBundle\Repository\BaseUserRepository")
 */
class BaseUser implements PasswordAuthenticatedUserInterface,UserInterface
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer", nullable=false, options={"comment"="用户id"})
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @ORM\Column(name="uuid", type="guid", unique=true,nullable=false, options={"comment"="唯一码"})
     */
    private $uuid;


    /**
     * @ORM\Column(name="mobile", type="string", length=12, unique=false, nullable=false, options={"comment"="手机码"})
     */
    private $mobile;


        /**
     * @ORM\Column(name="mobile_mask", type="string", length=100, unique=false, nullable=false, options={"comment"="手机掩码"})
     */
    private $mobileMask;

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
     * @ORM\Column(name="gravatar", type="string", length=400, nullable=true, options={"comment"="人物头像"})
     */
    private $gravatar;

    /**
     *
     * @ORM\Column(name="birthday", type="string", length=10, nullable=true, options={"comment"="生日"})
     */
    private $birthday;

    /**
     * @var bool|null
     *
     * @ORM\Column(name="sex", type="integer", length=1, nullable=true, options={"comment"="1-男,2-女"})
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
     * @ORM\Column(name="real_role", type="integer", length=1, nullable=true, options={"comment"="真实角色 1-管理员 ，2-讲师，3-助教，4-班主任,5-学生"})
     */
    private $realRole = '5';


    /**
     * @var bool|null
     *
     * @ORM\Column(name="is_lock", type="boolean", nullable=true, options={"comment"="是否被锁定,1-是，0-否"})
     */
    private $isLock = '0';


    /**
     * @var bool|null
     *
     * @ORM\Column(name="im_imported", type="boolean", nullable=true, options={"comment"="是否已经导入腾讯云im中,1-是，0-否"})
     */
    private $imImported = '0';

    /**
     * @var bool|null
     *
     * @ORM\Column(name="is_admin", type="boolean", nullable=true, options={"comment"="是否是管理员,1-是，0-否"})
     */
    private $isAdmin = '0';

    /**
     * @var string|null
     *
     * @ORM\Column(name="reg_source", type="string", length=11, nullable=true, options={"comment"="pc,ios,android"})
     */
    private $regSource;

    /**
     * @var string The hashed password
     * @ORM\Column(name="password",type="string", nullable=true, options={"comment"="密码"})
     */
    private $password;

    /**
     * @ORM\Column(name="password_change_date", type="integer", nullable=true, options={"comment"="密码修改时间"})
     */
    private $passwordChangeDate;

    /**
     * @ORM\Column(name="app_token",type="string",length=50,nullable=true,unique=true, options={"comment"="ios,android token"})
     */
    private $appToken;

    /**
     * @ORM\Column(name="html_token",type="string",length=50,nullable=true,unique=true, options={"comment"="m站，pc站 token"})
     */
    private $htmlToken;

    /**
     * @ORM\Column(name="wxmini_token",type="string",length=50,nullable=true,unique=true, options={"comment"="微信小程序 token"})
     */
    private $wxminiToken;

    /**
     * @ORM\Column(type="json", options={"comment"="角色-占位"})
     */
    private $roles = ['ROLE_USER'];

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


    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUsername(): string
    {
        return (string) $this->uuid;
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


    /**
     * Checks whether the user's credentials (password) has expired.
     *
     * Internally, if this method returns false, the authentication system
     * will throw a CredentialsExpiredException and prevent login.
     *
     * @return bool true if the user's credentials are non expired, false otherwise
     *
     * @see CredentialsExpiredException
     */
    public function isCredentialsNonExpired()
    {
        return !$this->passwordChangeDate;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUuid(): ?string
    {
        return $this->uuid;
    }

    public function setUuid(?string $uuid): self
    {
        $this->uuid = $uuid;

        return $this;
    }

    public function getMobile(): ?string
    {
        return $this->mobile;
    }

    public function setMobile(string $mobile): self
    {
        $mobile = substr_replace($mobile, '****', 3, 4);
        $this->mobile = $mobile;
        return $this;
    }

    public function getMobileMask(): ?string
    {
        return $this->mobileMask;
    }

    public function setMobileMask(string $mobileMask): self
    {
        $this->mobileMask = $mobileMask;

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

    public function getGravatar(): ?string
    {
        return $this->gravatar;
    }

    public function setGravatar(?string $gravatar): self
    {
        $this->gravatar = $gravatar;

        return $this;
    }

    public function getBirthday(): ?string
    {
        return $this->birthday;
    }

    public function setBirthday(?string $birthday): self
    {
        $this->birthday = $birthday;

        return $this;
    }

    public function getSex(): ?int
    {
        return $this->sex;
    }

    public function setSex(?int $sex): self
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

    public function getPasswordChangeDate(): ?int
    {
        return $this->passwordChangeDate;
    }

    public function setPasswordChangeDate(?int $passwordChangeDate): self
    {
        $this->passwordChangeDate = $passwordChangeDate;

        return $this;
    }

    public function getAppToken(): ?string
    {
        return $this->appToken;
    }

    public function setAppToken(?string $appToken): self
    {
        $this->appToken = $appToken;

        return $this;
    }

    public function getHtmlToken(): ?string
    {
        return $this->htmlToken;
    }

    public function setHtmlToken(?string $htmlToken): self
    {
        $this->htmlToken = $htmlToken;

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

    public function getIsAdmin(): ?bool
    {
        return $this->isAdmin;
    }

    public function setIsAdmin(?bool $isAdmin): self
    {
        $this->isAdmin = $isAdmin;

        return $this;
    }

    /**
     * Returns the roles granted to the user.
     *
     *     public function getRoles()
     *     {
     *         return ['ROLE_USER'];
     *     }
     *
     * Alternatively, the roles might be stored on a ``roles`` property,
     * and populated in any number of different ways when the user object
     * is created.
     *
     * @return (Role|string)[] The user roles
     */
    public function getRoles(): array
    {
        return $this->roles;
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    public function getImImported(): ?bool
    {
        return $this->imImported;
    }

    public function setImImported(?bool $imImported): self
    {
        $this->imImported = $imImported;

        return $this;
    }

    public function getRealRole(): ?int
    {
        return $this->realRole;
    }

    public function setRealRole(?int $realRole): self
    {
        $this->realRole = $realRole;

        return $this;
    }

    public function getWxminiToken(): ?string
    {
        return $this->wxminiToken;
    }

    public function setWxminiToken(?string $wxminiToken): self
    {
        $this->wxminiToken = $wxminiToken;

        return $this;
    }

    public function getUserIdentifier(): string
    {
        return $this->getUsername();
    }
}
