<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * BaseFile
 *
 * @ORM\Table(name="base_file")
 * @Gedmo\SoftDeleteable(fieldName="deletedAt", timeAware=false, hardDelete=true)
 * @ORM\Entity(repositoryClass="App\Repository\BaseFileRepository")
 */
class BaseFile
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer", nullable=false, options={"unsigned"=true,"comment"="上传文件ID"})
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var int
     *
     * @ORM\Column(name="group_id", type="integer", nullable=false, options={"unsigned"=true,"comment"="上传文件组ID"})
     */
    private $groupId = '0';

    /**
     * @var int
     *
     * @ORM\Column(name="uid", type="integer", nullable=false, options={"unsigned"=true,"comment"="上传人ID"})
     */
    private $uid = '0';

    /**
     * @var string
     *
     * @ORM\Column(name="obj", type="string", length=255, nullable=false, options={"comment"="文件对象"})
     */
    private $obj = '';

    /**
     * @var string|null
     *
     * @ORM\Column(name="originalz_name", type="string", length=100, nullable=true, options={"comment"="文件原始名称"})
     */
    private $originalzName;

    /**
     * @var string|null
     *
     * @ORM\Column(name="mime", type="string", length=255, nullable=true, options={"comment"="文件MIME"})
     */
    private $mime;

    /**
     * @var int
     *
     * @ORM\Column(name="size", type="integer", nullable=false, options={"unsigned"=true,"comment"="文件大小"})
     */
    private $size = '0';

    /**
     * @var string|null
     *
     * @ORM\Column(name="thirdpart_data", type="text", length=65535, nullable=true, options={"comment"="第三方数据"})
     */
    private $thirdpartData;

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

    public function getGroupId(): ?int
    {
        return $this->groupId;
    }

    public function setGroupId(int $groupId): self
    {
        $this->groupId = $groupId;

        return $this;
    }

    public function getUid(): ?int
    {
        return $this->uid;
    }

    public function setUid(int $uid): self
    {
        $this->uid = $uid;

        return $this;
    }

    public function getObj(): ?string
    {
        return $this->obj;
    }

    public function setObj(string $obj): self
    {
        $this->obj = $obj;

        return $this;
    }

    public function getOriginalzName(): ?string
    {
        return $this->originalzName;
    }

    public function setOriginalzName(?string $originalzName): self
    {
        $this->originalzName = $originalzName;

        return $this;
    }

    public function getMime(): ?string
    {
        return $this->mime;
    }

    public function setMime(?string $mime): self
    {
        $this->mime = $mime;

        return $this;
    }

    public function getSize(): ?int
    {
        return $this->size;
    }

    public function setSize(int $size): self
    {
        $this->size = $size;

        return $this;
    }

    public function getThirdpartData(): ?string
    {
        return $this->thirdpartData;
    }

    public function setThirdpartData(?string $thirdpartData): self
    {
        $this->thirdpartData = $thirdpartData;

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
