<?php

/**
 * @Author: kaihui.wang
 * @Contact  hpuwang@gmail.com
 * @Version: 1.0.0
 * @Date: 2020/5/7 11:17
 */

namespace Eduxplus\CoreBundle\Service\Mall;


use Eduxplus\CoreBundle\Lib\Base\AdminBaseService;
use Eduxplus\CoreBundle\Lib\Base\BaseService;
use Eduxplus\CoreBundle\Entity\MallOrderStudyPlan;
use Knp\Component\Pager\PaginatorInterface;
use Eduxplus\CoreBundle\Service\UserService;
use Eduxplus\CoreBundle\Entity\MallOrder;

class OrderService extends AdminBaseService
{


    protected $paginator;
    protected $userService;
    protected $goodsService;
    protected $couponService;

    public function __construct(
        PaginatorInterface $paginator,
        UserService $userService,
        GoodsService $goodsService,
        CouponService $couponService
    ) {
        $this->paginator = $paginator;
        $this->userService = $userService;
        $this->goodsService = $goodsService;
        $this->couponService = $couponService;
    }

    public function getList($request, $page, $pageSize)
    {
        $sql = $this->getFormatRequestSql($request);

        $dql = "SELECT a FROM Core:MallOrder a " . $sql . " ORDER BY a.id DESC";
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
                if(!$vArr) continue;
                $createrUid = $vArr['uid'];
                $createrUser = $this->userService->getById($createrUid);
                if(!$vArr["couponSn"]){
                    $couponGroupInfo = $this->couponService->getById($vArr['couponGroupId']);
                    $vArr["couponSn"] = $couponGroupInfo?$couponGroupInfo["name"]:"";
                }
                $vArr['creater'] = $createrUser['fullName'];
                $vArr['discountAmount'] = number_format($vArr['discountAmount'] / 100, 2);
                $vArr['originalAmount'] = number_format($vArr['originalAmount'] / 100, 2);
                $vArr["orderAmount"] =  number_format($vArr['orderAmount'] / 100, 2);
                $goodsInfo = $this->goodsService->getById($vArr['goodsId']);
                $vArr["goodName"] = $goodsInfo["name"];
                if ($vArr['goodsAll']) {
                    $goodAllIds = explode(",", $vArr['goodsAll']);
                    $goodsInfos = $this->goodsService->getByIds($goodAllIds);
                    $allGoodNamesStr = "";
                    if ($goodsInfos) {
                        foreach ($goodsInfos as $v) {
                            $allGoodNamesStr .= $v["name"] . ",";
                        }
                    }
                    $vArr["allGoodNames"] = trim($allGoodNamesStr, ",");
                } else {
                    $vArr["allGoodNames"] = "";
                }

                $itemsArr[] = $vArr;
            }
        }
        return [$pagination, $itemsArr];
    }

    public function add($uid, $paymentType, $name, $goodsId, $goodsAll, $orderAmount, $originalAmount, $discountAmount, $couponSn, $orderStatus, $referer, $userNotes)
    {
        try {
            $this->beginTransaction();
            $sql = "SELECT a FROM Core:MallGoods a WHERE a.id=:id";
            $agreementId = $this->fetchField("agreementId", $sql, ['id' => $goodsId]);

            $orderNo = date('Ymd') . "o" . session_create_id("");
            $model = new MallOrder();
            $model->setOrderNo($orderNo);
            $model->setUid($uid);
            $model->setName($name);
            $model->setGoodsId($goodsId);
            $model->setOrderAgreementId($agreementId);
            $model->setPaymenttype($paymentType);
            $goodsAllStr = implode(",", $goodsAll);
            if ($goodsAll) $model->setGoodsAll($goodsAllStr);
            $model->setOrderAmount($orderAmount * 100);
            $model->setDiscountAmount($discountAmount * 100);
            $model->setOriginalAmount($originalAmount * 100);
            if ($couponSn) $model->setCouponSn($couponSn);
            $model->setOrderStatus($orderStatus);
            $model->setReferer($referer);
            $model->setUserNotes($userNotes);
            $orderId = $this->save($model);
            if ($orderId) {
                //获取开课计划
                $studyPlanIds = [];
                if ($goodsAll) {
                    foreach ($goodsAll as $gId) {
                        $this->goodsService->getStudyPlan($gId, $studyPlanIds);
                    }
                } else {
                    $this->goodsService->getStudyPlan($goodsId, $studyPlanIds);
                }
                if ($studyPlanIds) {
                    $sql = "SELECT a FROM Core:TeachStudyPlanSub a WHERE a.studyPlanId IN(:studyPlanId)";
                    $studyPlanSubs = $this->fetchAll($sql, ["studyPlanId" => $studyPlanIds]);
                    if ($studyPlanSubs) {
                        foreach ($studyPlanSubs as $sv) {
                            $sid = $sv['studyPlanId'];
                            $courseId = $sv['courseId'];
                            $sql = "SELECT a FROM Core:TeachCourse a WHERE a.id=:id AND a.status=1 ";
                            $courseInfos = $this->fetchAll($sql, ["id" => $courseId]);
                            if ($courseInfos) {
                                foreach ($courseInfos as $course) {
                                    $sModel = new MallOrderStudyPlan();
                                    $sModel->setStudyPlanId($sid);
                                    $sModel->setUid($uid);
                                    $sModel->setOrderId($orderId);
                                    $sModel->setCourseId($course['id']);
                                    $sModel->setOpenTime($course['openTime']);
                                    $this->save($sModel);
                                }
                            }
                        }
                    }
                } else {
                    throw new \Exception("错误，目前课程开课计划为空，暂时无法添加订单!");
                }
            }
            $this->commit();
            return $orderId;
        } catch (\Exception $e) {
            $this->rollback();
            return $this->error()->add($e->getMessage());
        }
    }

    public function getById($id){
        $sql = "SELECT a FROM Core:MallOrder a WHERE a.id=:id";
        $model = $this->fetchOne($sql, ["id" => $id]);
        return $model;
    }


    public function getMallAgreementId($agreementId)
    {
        // todo 获取协议原本
        return 1;
    }

    public function setOrderStatus($orderId, $orderStatus)
    {
        $sql = "SELECT a FROM Core:MallOrder a WHERE a.id=:id";
        $model = $this->fetchOne($sql, ["id" => $orderId], 1);
        if (!$model) return false;
        $model->setOrderStatus($orderStatus);
        $this->save($model);
    }
}
