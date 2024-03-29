<?php

/**
 * @Author: kaihui.wang
 * @Contact  hpuwang@gmail.com
 * @Version: 1.0.0
 * @Date: 2020/5/7 11:17
 */

namespace Eduxplus\EduxBundle\Service\Mall;


use Eduxplus\CoreBundle\Lib\Base\AdminBaseService;
use Eduxplus\EduxBundle\Entity\MallPay;
use Knp\Component\Pager\PaginatorInterface;
use Eduxplus\EduxBundle\Service\Teach\CategoryService;
use Eduxplus\CoreBundle\Service\UserService;

class PayService extends AdminBaseService
{


    protected $paginator;
    protected $userService;
    protected $categoryService;
    protected $orderService;

    public function __construct(
        PaginatorInterface $paginator,
        UserService $userService,
        CategoryService $categoryService,
        OrderService $orderService
    ) {
        $this->paginator = $paginator;
        $this->userService = $userService;
        $this->categoryService = $categoryService;
        $this->orderService = $orderService;
    }

    public function getList($request, $page, $pageSize)
    {
        $sql = $this->getFormatRequestSql($request);

        $dql = "SELECT a FROM Edux:MallPay a " . $sql . " ORDER BY a.id DESC";
        $em = $this->getDoctrine()->getManager();
        $query = $em->createQuery($dql);
        $pagination = $this->paginator->paginate(
            $query,
            $page,
            $pageSize
        );

        $items = $pagination->getItems();
        $itemsArr = [];
        if ($items) {
            foreach ($items as $v) {
                $vArr =  $this->toArray($v);
                $createrUid = $vArr['uid'];
                $createrUser = $this->userService->getById($createrUid);
                $vArr['creater'] = $createrUser['fullName'];
                $vArr['amount'] = $vArr['amount'] / 100;
                $vArr['payTime'] = $vArr['payTime'] ? date('Y-m-d H:i:s', $vArr['payTime']) : "-";
                $orderInfo = $this->orderService->getById($vArr["orderId"]);
                $vArr['orderNo'] = $orderInfo["orderNo"];
                $vArr['orderStatus'] =  $orderInfo['orderStatus'];
                $vArr['paymentType'] =  $orderInfo['paymentType'];

                $itemsArr[] = $vArr;
            }
        }
//        dump($itemsArr);exit;
        return [$pagination, $itemsArr];
    }


    public function add($uid, $amount, $orderId)
    {
        $transactionId = date('Ymd') . "p" . session_create_id("");
        $model = new MallPay();
        $model->setUid($uid);
        $model->setTransactionId($transactionId);
        $model->setAmount($amount * 100);
        $model->setOrderId($orderId);
        return $this->db()->save($model);
    }

    public function completedPay($payId)
    {
        $sql = "SELECT a FROM Edux:MallPay a WHERE a.id=:id";
        $detail = $this->db()->fetchOne($sql, ["id" => $payId], 1);
        if (!$detail) return false;
        $detail->setPayTime(time());
        $this->db()->save($detail);
    }
}
