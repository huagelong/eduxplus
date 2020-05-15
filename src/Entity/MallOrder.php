<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * MallOrder
 *
 * @ORM\Table(name="mall_order")
 * @Gedmo\SoftDeleteable(fieldName="deletedAt", timeAware=false, hardDelete=true)
 * @ORM\Entity(repositoryClass="App\Repository\MallOrderRepository")
 */
class MallOrder
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer", nullable=false, options={"unsigned"=true,"comment"="订单表id"})
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="order_no", type="string", length=20, nullable=false, options={"comment"="订单号"})
     */
    private $orderNo = '';

    /**
     * @var int
     *
     * @ORM\Column(name="uid", type="integer", nullable=false, options={"unsigned"=true,"comment"="下单人"})
     */
    private $uid;

    /**
     * @var int
     *
     * @ORM\Column(name="goods_id", type="integer", nullable=false, options={"unsigned"=true,"comment"="商品id"})
     */
    private $goodsId;

    /**
     * @var int
     *
     * @ORM\Column(name="order_amount", type="integer", nullable=false, options={"comment"="订单实际支付价格，已减去优惠价格"})
     */
    private $orderAmount = '0';

    /**
     * @var int
     *
     * @ORM\Column(name="discount_amount", type="integer", nullable=false, options={"comment"="优惠金额"})
     */
    private $discountAmount = '0';

    /**
     * @var bool
     *
     * @ORM\Column(name="order_status", type="boolean", nullable=false, options={"comment"="订单状态:0待支付,1已支付,2已取消"})
     */
    private $orderStatus = '0';

    /**
     * @var string
     *
     * @ORM\Column(name="user_notes", type="string", length=200, nullable=false, options={"comment"="用户备注"})
     */
    private $userNotes = '';

    /**
     * @var string
     *
     * @ORM\Column(name="referer", type="string", length=20, nullable=false, options={"default"="CRM后台创建","comment"="订单来源"})
     */
    private $referer = '前台创建';

    /**
     * @var string
     *
     * @ORM\Column(name="coupon_sn", type="string", length=26, nullable=false, options={"comment"="订单使用优惠码编号"})
     */
    private $couponSn = '';

    /**
     * @var string
     *
     * @ORM\Column(name="goods_all", type="string", length=100, nullable=false, options={"comment"="多个goodsid"})
     */
    private $goodsAll = '';

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=1000, nullable=false, options={"comment"="订单名称"})
     */
    private $name = '';

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

}
