<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * MallCouponGroup
 *
 * @ORM\Table(name="mall_coupon_group")
 * @Gedmo\SoftDeleteable(fieldName="deletedAt", timeAware=false, hardDelete=true)
 * @ORM\Entity(repositoryClass="App\Repository\MallCouponGroupRepository")
 */
class MallCouponGroup
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer", nullable=false, options={"unsigned"=true,"comment"="优惠码组id"})
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="coupon_name", type="string", length=20, nullable=false, options={"comment"="优惠码名称"})
     */
    private $couponName = '';

    /**
     * @var bool
     *
     * @ORM\Column(name="coupon_type", type="boolean", nullable=false, options={"comment"="类型:1金额优惠,2折扣优惠"})
     */
    private $couponType = '0';

    /**
     * @var int
     *
     * @ORM\Column(name="discount", type="integer", nullable=false, options={"unsigned"=true,"comment"="优惠码折扣(百分数)/优惠码金额(乘以100)"})
     */
    private $discount = '0';

    /**
     * @var int
     *
     * @ORM\Column(name="count_num", type="integer", nullable=false, options={"unsigned"=true,"comment"="发放数量"})
     */
    private $countNum = '0';

    /**
     * @var int
     *
     * @ORM\Column(name="used_num", type="integer", nullable=false, options={"unsigned"=true,"comment"="已使用的数量"})
     */
    private $usedNum = '0';

    /**
     * @var int
     *
     * @ORM\Column(name="expiration_start", type="integer", nullable=false, options={"unsigned"=true,"comment"="开始有效日期"})
     */
    private $expirationStart;

    /**
     * @var int
     *
     * @ORM\Column(name="expiration_end", type="integer", nullable=false, options={"unsigned"=true,"comment"="结束有效日期"})
     */
    private $expirationEnd;

    /**
     * @var bool
     *
     * @ORM\Column(name="status", type="boolean", nullable=false, options={"default"="1","comment"="上架1，下架0"})
     */
    private $status = true;

    /**
     * @var int
     *
     * @ORM\Column(name="create_uid", type="integer", nullable=false, options={"unsigned"=true,"comment"="创建人"})
     */
    private $createUid;

    /**
     * @var string
     *
     * @ORM\Column(name="descr", type="string", length=200, nullable=false, options={"comment"="描述"})
     */
    private $descr = '';

    /**
     * @var string|null
     *
     * @ORM\Column(name="category_id", type="string", length=250, nullable=true, options={"comment"="配套产品，分类id"})
     */
    private $categoryId;

    /**
     * @var string|null
     *
     * @ORM\Column(name="teaching_method", type="string", length=50, nullable=true, options={"comment"="授课方式 1.面授 2.直播 3.录播 4.面授+直播 5.直播+录播 6.录播+面授 7.直播+录播+面授"})
     */
    private $teachingMethod;

    /**
     * @var string|null
     *
     * @ORM\Column(name="goods_ids", type="text", length=65535, nullable=true, options={"comment"="商品id,以','隔开"})
     */
    private $goodsIds;

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
