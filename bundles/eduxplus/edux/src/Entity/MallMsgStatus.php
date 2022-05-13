<?php

namespace Eduxplus\EduxBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * MallMsgStatus
 * 消息状态
 *
 * @ORM\Table(name="mall_msg_status", indexes={@ORM\Index(name="msg_id_idx", columns={"msg_id"})})
 * @Gedmo\SoftDeleteable(fieldName="deletedAt", timeAware=false, hardDelete=true)
 * @ORM\Entity(repositoryClass="Eduxplus\EduxBundle\Repository\MallMsgStatusRepository")
 */
class MallMsgStatus
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
     * @var int
     *
     * @ORM\Column(name="msg_id", type="bigint", nullable=false, options={"unsigned"=true,"comment"="消息id"})
     */
    private $msgId = '0';

    /**
     * @var bool|null
     *
     * @ORM\Column(name="status", type="boolean", nullable=true, options={"default"="0","comment"="是否已读,0-未读,1-已读"})
     */
    private $status = '0';

    /**
     * @var int
     *
     * @ORM\Column(name="uid", type="bigint", nullable=false, options={"unsigned"=true,"comment"="用户uid"})
     */
    private $uid = '0';

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

    public function getMsgId(): ?int
    {
        return $this->msgId;
    }

    public function setMsgId(int $msgId): self
    {
        $this->msgId = $msgId;

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

    public function getUid(): ?int
    {
        return $this->uid;
    }

    public function setUid(int $uid): self
    {
        $this->uid = $uid;

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
