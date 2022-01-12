<?php
/**
 * @Author: kaihui.wang
 * @Contact  hpuwang@gmail.com
 * @Version: 1.0.0
 * @Date: 2020/6/15 12:01
 */

namespace Eduxplus\WebsiteBundle\Service;


use Eduxplus\CoreBundle\Lib\Base\AppBaseService;
use Eduxplus\CoreBundle\Lib\Service\Base\Pay\AlipayService;
use Eduxplus\CoreBundle\Lib\Service\Base\Pay\WxpayService;
use Eduxplus\QaBundle\Entity\TeachTestOrder;
use Eduxplus\EduxBundle\Entity\JwClasses;
use Eduxplus\EduxBundle\Entity\JwClassesMembers;
use Eduxplus\EduxBundle\Entity\MallOrder;
use Eduxplus\EduxBundle\Entity\MallOrderStudyPlan;
use Eduxplus\EduxBundle\Entity\MallPay;
use Knp\Component\Pager\PaginatorInterface;

class OrderService extends AppBaseService
{

    protected $paginator;
    protected $goodsService;
    protected $alipayService;
    protected $wxpayService;

    public function __construct(PaginatorInterface $paginator, GoodsService $goodsService, AlipayService $alipayService, WxpayService $wxpayService)
    {
        $this->paginator = $paginator;
        $this->goodsService = $goodsService;
        $this->alipayService = $alipayService;
        $this->wxpayService = $wxpayService;
    }

    public function getList($uid, $page, $pageSize){
        $dql = "SELECT a FROM Edux:MallOrder a WHERE a.uid = :uid";
        $em = $this->getDoctrine()->getManager();
        $query = $em->createQuery($dql);
        $query= $query->setParameters(["uid"=>$uid]);
        $pagination = $this->paginator->paginate(
            $query,
            $page,
            $pageSize
        );

        $items = $pagination->getItems();
        $itemsArr = [];
        if($items){
            foreach ($items as $v){
                $vArr =  $this->toArray($v);
                $vArr['orderAmount'] = $vArr['orderAmount']/100;
                $vArr['discountAmount'] = $vArr['discountAmount']/100;
                $vArr['originalAmount'] = $vArr['originalAmount']/100;
                $vArr['orderStatusView'] = $this->getOrderStatusView($vArr['orderStatus']);
                $itemsArr[] = $vArr;
            }
        }
        return [$pagination, $itemsArr];
    }

    /**
     * @param $status 0支付过期,1待支付,2支付成功,3已取消
     * @return string
     */
    protected function getOrderStatusView($status){
        switch ($status){
            case 0:return "支付过期";
            case 1:return "待支付";
            case 2: return "支付成功";
            case 3: return "已取消";
        }
        return "未知";
    }

    protected function getPayWayView($payWay){
        switch ($payWay){
            case 1: return "支付宝";
            case 2: return "微信支付";
        }
        return "未知";
    }

    public function getSimpleByOrderNo($orderNo){
        $dql = "SELECT a FROM Edux:MallOrder a WHERE a.orderNo = :orderNo";
        $detail = $this->db()->fetchOne($dql, ["orderNo"=>$orderNo]);
        return $detail;
    }

    /**
     * 详情
     * @param $orderNo
     */
    public function getByOrderNo($orderNo){
        $dql = "SELECT a FROM Edux:MallOrder a WHERE a.orderNo = :orderNo";
        $detail = $this->db()->fetchOne($dql, ["orderNo"=>$orderNo]);
        if(!$detail) return [];

        if($detail['goodsAll']){
            $goodsAllIds = explode(",", $detail['goodsAll']);
            $sql4 = "SELECT a FROM Edux:MallGoods a where a.id IN(:id) ";
            $params = [];
            $params['id'] = $goodsAllIds;
            $goodsAllInfos =  $this->db()->fetchAll($sql4, $params);
            $detail['goods'] = $goodsAllInfos;
        }else{
            $sql4 = "SELECT a FROM Edux:MallGoods a where a.id=:id ";
            $goodsInfo = $this->db()->fetchOne($sql4, ['id'=>$detail['goodsId']]);
            $detail['goods'] = [$goodsInfo];
        }

        $detail['orderAmount'] = $detail['orderAmount']/100;
        $detail['discountAmount'] = $detail['discountAmount']/100;
        $detail['originalAmount'] = $detail['originalAmount']/100;
        $detail['orderStatusView'] = $this->getOrderStatusView($detail['orderStatus']);
        $detail['paywayView'] = $this->getPayWayView($detail['paymentType']);

        $dql = "SELECT a FROM Edux:MallPay a WHERE a.orderId = :orderId";
        $payDetail = $this->db()->fetchOne($dql, ["orderId"=>$detail['id']]);
        $detail['pay']=[];
        if($payDetail){
            $detail['pay'] = $payDetail;
        }
        return $detail;
    }

    public function useCoupon($uid, $couponSn, $status=0)
    {
        $sql = "SELECT a FROM Edux:MallCoupon a WHERE a.couponSn=:couponSn";
        $detail = $this->db()->fetchOne($sql, ["couponSn" => $couponSn], 1);
        if (!$detail) return false;
        if($detail->getUid()){
            if ($detail->getUid() != $uid) return false;
        }
        if ($detail->getStatus() != 0) return false;
        if($status){
            $detail->setUsedTime(time());
        }else{
            $detail->setSendTime(time());
        }
        $detail->setStatus($status);
        $this->db()->save($detail);
        return $detail->getCouponGroupId();
    }

    /**
     * 解析优惠券价格
     * @param $uid
     * @param $couponSn
     * @param $goodIds
     */
    public function checkCoupon($couponSn, $uid){
        //检查优惠券
        $sql = "SELECT a FROM Edux:MallCoupon a WHERE a.couponSn=:couponSn";
        $detail = $this->db()->fetchOne($sql, ["couponSn" => $couponSn]);
        if (!$detail) return $this->error()->add("优惠券不存在!");
        if ($detail['status'] != 0) return $this->error()->add("优惠券已被使用!");
        $couponUid = $detail["uid"];
        if($couponUid && ($couponUid !=$uid)){
            return $this->error()->add("优惠券不存在!");
        }
        $couponGroupId = $detail['couponGroupId'];

        $sqlGroup = "SELECT a FROM Edux:MallCouponGroup a WHERE a.id=:id ";
        $groupInfo = $this->db()->fetchOne($sqlGroup, ["id"=>$couponGroupId]);
        //判断
        if($groupInfo['status'] == 0) return $this->error()->add("优惠券不存在");
        $expirationStart = $groupInfo['expirationStart'];
        $expirationEnd = $groupInfo['expirationEnd'];
        if($expirationStart && ($expirationStart>time())){
            return $this->error()->add("优惠券未到期!");
        }
        if($expirationEnd && ($expirationEnd<time())){
            return $this->error()->add("优惠券已过期!");
        }
        return $groupInfo;
    }

    /**
     * 解析单个商品优惠价格
     * @param $goodId
     */
    public function parseDiscount($goodId,$groupCouponInfo, $shopPrice=0){
        $couponType = $groupCouponInfo["couponType"];
        $discount = $groupCouponInfo["discount"];
        $categoryId  = $groupCouponInfo["categoryId"];
        $teachingMethod  = $groupCouponInfo["teachingMethod"];
        $goodsIdstr  = $groupCouponInfo["goodsIds"];
        if(!$shopPrice){
            $sql = "SELECT a FROM Edux:MallGoods a where a.id=:id ";
            $goodsInfo = $this->db()->fetchOne($sql, ['id'=>$goodId]);
            $shopPrice = $goodsInfo['shopPrice'];
        }
        $checkRange = $this->checkRange($categoryId, $teachingMethod, $goodsIdstr, $goodId);
        if(!$checkRange) return 0;
        //1金额优惠,2折扣优惠
        if($couponType == 1){
            return $discount;
        }else if($couponType == 2){
            return $discount*($shopPrice/100);
        }
        return 0;
    }

    /**
     * 检查是否在范围内
     * @param $categoryId
     * @param $teachingMethod
     * @param $goodsIds
     * @param $checkGoodId
     */
    protected function checkRange($categoryId, $teachingMethod, $goodsIdstr, $checkGoodId){
        $teachingMethodCheck = 0;
        $categoryIdCheck = 0;
        $goodsIdsCheck = 0;
        if($goodsIdstr){
            $goodsIds = explode(",", $goodsIdstr);
            if(in_array($checkGoodId, $goodsIds)) $goodsIdsCheck = 1;
        }else{
            $goodsIdsCheck = 1;
        }

        $sql = "SELECT a FROM Edux:MallGoods a where a.id=:id ";
        $goodsInfo = $this->db()->fetchOne($sql, ['id'=>$checkGoodId]);
        $teachingMethodTmp = $goodsInfo['teachingMethod'];
        if($teachingMethod){
            if($teachingMethod == $teachingMethodTmp){
                $teachingMethodCheck = 1;
            }
        }else{
            $teachingMethodCheck = 1;
        }

        if($categoryId){
            $categoryIdTmp = $goodsInfo['categoryId'];
            $sql = "SELECT a.findPath FROM Edux:TeachCategory a where a.id=:id ";
            $findPath = $this->db()->fetchField("findPath", $sql, ["id"=>$categoryIdTmp]);
            $findPath = trim( $findPath, ',');
            if($findPath){
                $cateIds = explode(",", $findPath);
            }else{
                $cateIds = [$categoryIdTmp];
            }

            if(in_array($categoryId, $cateIds)){
                $categoryIdCheck = 1;
            }
        }else{
            $categoryIdCheck = 1;
        }

        //如果有值的时候，都满足条件
        if( ($teachingMethodCheck==1) && ($goodsIdsCheck == 1) && ($categoryIdCheck == 1) ) return true;
        return false;
    }

    /**
     * 添加订单
     * @param $uid
     * @param $paymentType
     * @param $name
     * @param $goodsId
     * @param $goodsAll
     * @param $orderAmount
     * @param $originalAmount
     * @param $discountAmount
     * @param $couponSn
     * @param $orderStatus
     * @param $referer
     * @param $userNotes
     * @return bool|mixed
     */
    public function add($uid, $paymentType, $name, $goodsId, $goodsAll, $orderAmount, $originalAmount, $discountAmount, $couponSn,$groupCouponId, $orderStatus, $referer, $userNotes)
    {
        try {
            $this->db()->beginTransaction();
            $userNotes = $userNotes?$userNotes:"";
            $sql = "SELECT a FROM Edux:MallGoods a WHERE a.id=:id";

            $goodInfo = $this->db()->fetchOne($sql, ['id' => $goodsId]);

            $agreementId = $goodInfo["agreementId"];
            $goodType = $goodInfo["goodType"];
            $orderNo = date('Ymd') . "o" . $paymentType.substr(md5(session_create_id("")),   8,   16);

            $model = new MallOrder();
            $model->setOrderNo($orderNo);
            $model->setUid($uid);
            $model->setName($name);
            $model->setGoodsId($goodsId);
            $model->setOrderAgreementId($agreementId);
            $model->setPaymenttype($paymentType);
            if ($goodsAll){
                $goodsAllStr = implode(",", $goodsAll);
                $model->setGoodsAll($goodsAllStr);
            }

            $model->setOrderAmount($orderAmount);
            $model->setDiscountAmount($discountAmount );
            $model->setOriginalAmount($originalAmount);
            if($couponSn){
                $model->setCouponSn($couponSn);
                //优惠券待使用
                $this->useCoupon($uid, $couponSn, 0);
            }

            if($groupCouponId){
                $model->setCouponGroupId($groupCouponId);
            }

            $model->setOrderStatus($orderStatus);
            $model->setReferer($referer);
            $model->setUserNotes($userNotes);
            $orderId = $this->db()->save($model);
            if ($orderId) {
                if ($goodType == 1) {
                    //获取开课计划
                    $studyPlanIds = [];
                    if ($goodsAll) {
                        foreach ($goodsAll as $gId) {
                            $this->getStudyPlan($gId, $studyPlanIds);
                        }
                    } else {
                        $this->getStudyPlan($goodsId, $studyPlanIds);
                    }
                    //去重
                    if ($studyPlanIds) {
                        $studyPlanIds = array_unique($studyPlanIds);
                        $sql = "SELECT a FROM Edux:TeachStudyPlanSub a WHERE a.studyPlanId IN(:studyPlanId)";
                        $studyPlanSubs = $this->db()->fetchAll($sql, ["studyPlanId" => $studyPlanIds]);
                        if ($studyPlanSubs) {
                            foreach ($studyPlanSubs as $sv) {
                                $sid = $sv['studyPlanId'];
                                $courseId = $sv['courseId'];
                                $sql = "SELECT a FROM Edux:TeachCourse a WHERE a.id=:id AND a.status=1 ";
                                $courseInfos = $this->db()->fetchAll($sql, ["id" => $courseId]);
                                if ($courseInfos) {
                                    foreach ($courseInfos as $course) {
                                        $sModel = new MallOrderStudyPlan();
                                        $sModel->setStudyPlanId($sid);
                                        $sModel->setUid($uid);
                                        $sModel->setOrderId($orderId);
                                        $sModel->setCourseId($course['id']);
                                        $sModel->setOrderStatus($orderStatus);
                                        $sModel->setOpenTime($course['openTime']);
                                        $this->db()->save($sModel);
                                    }
                                }
                            }
                        }
                    } else {
                        throw new \Exception("错误，目前课程开课计划为空，暂时无法添加订单!");
                    }
                }else{//试卷处理, todo
                    //获取testIds
                    if (!$goodsAll) {
                        array_push($goodsAll, $goodsId);
                    }
                    $sql = "SELECT a FROM Edux:MallGoods a WHERE a.id in(:id)";
                    $productIds = $this->db()->fetchFields("productId", $sql, ["id"=>$goodsAll]);
                    if($productIds){
                        foreach ($productIds as $testId){
                            $teachTestOrderModel = new TeachTestOrder();
                            $teachTestOrderModel->setTestId($testId);
                            $teachTestOrderModel->setUid($uid);
                            $teachTestOrderModel->setOrderId($orderId);
                            $teachTestOrderModel->setOrderStatus($orderStatus);
                            $this->db()->save($teachTestOrderModel);
                        }

                    }
                }
                $this->db()->commit();
            }
            return [$orderId, $orderNo];
        } catch (\Exception $e) {
            $this->db()->rollback();
            return $this->error()->add($e->getMessage());
        }
    }

    /**
     * 自动分班
     */
    public function autoClasses($orderId, $uid){
        $dql = "SELECT a FROM Edux:MallOrder a WHERE a.id = :id";
        $detail = $this->db()->fetchOne($dql, ["id"=>$orderId]);
        if(!$detail) return [];
        $goodsAll = $detail['goodsAll'];
        $goodsId = $detail['goodsId'];
        $studyPlanIds = [];
        if ($goodsAll) {
            foreach ($goodsAll as $gId) {
                $this->getStudyPlan($gId, $studyPlanIds);
            }
        } else {
            $this->getStudyPlan($goodsId, $studyPlanIds);
        }

        if($studyPlanIds){
            $studyPlanIds = array_unique($studyPlanIds);
            foreach ($studyPlanIds as $studyPlanId){
                $sql = "SELECT a FROM Edux:TeachStudyPlan a WHERE a.id=:id";
                $planInfo = $this->db()->fetchOne($sql, ["id"=>$studyPlanId]);
                if(!$planInfo) return $planInfo;
                $productId = $planInfo["productId"];

                $productSql = "SELECT a FROM Edux:TeachProducts a WHERE a.id=:id";
                $productInfo = $this->db()->fetchOne($productSql, ["id"=>$productId]);
                if(!$productInfo) return $productInfo;
                $productName = $productInfo["name"];
                $maxMemberNumber = $productInfo["maxMemberNumber"];//最大分班人数
                //判断人数
                $classSql = "SELECT a FROM Edux:JwClasses a WHERE a.studyPlanId = :studyPlanId ORDER BY a.createdAt DESC";
                $classInfo = $this->db()->fetchOne($classSql, ["studyPlanId"=>$studyPlanId]);
                if($classInfo){
                    $classesId = $classInfo["id"];
                    if(!$maxMemberNumber){//如果不限制人数
                        $classMemberModel = new JwClassesMembers();
                        $classMemberModel->setUid($uid);
                        $classMemberModel->setClassesId($classesId);
                        $classMemberModel->setType(1);
                        $this->db()->save($classMemberModel);
                    }else{
                        //判断人数
                        $memberSql = "SELECT count(a.id) as cnt FROM Edux:JwClassesMembers a WHERE a.classesId = :classesId";
                        $memberCount = $this->db()->fetchField("cnt", $memberSql, ["classesId"=>$classesId]);
                        if($memberCount >= $maxMemberNumber){//大于等于限制人数,新增班级
                            //获取产品现有班级
                            $this->newClassAndMember($productId, $productName, $studyPlanId, $uid);
                        }else{
                            //小于限制人数，添加人员
                            $classMemberModel = new JwClassesMembers();
                            $classMemberModel->setUid($uid);
                            $classMemberModel->setClassesId($classesId);
                            $classMemberModel->setType(1);
                            $this->db()->save($classMemberModel);
                        }
                    }
                }else{
                    $this->newClassAndMember($productId, $productName, $studyPlanId, $uid);
                }
            }
        }


    }


    public function newClassAndMember($productId, $productName, $studyPlanId, $uid){
        $classProductSql = "SELECT a FROM Edux:JwClasses a WHERE a.productId = :productId ORDER BY a.createdAt DESC";
        $classProductInfo = $this->db()->fetchOne($classProductSql, ["productId"=>$productId]);
        if($classProductInfo){
            $classesNo = $classProductInfo["classesNo"]+1;
        }else{
            $classesNo = 1;
        }
        $name = $productName."-(".date("Y").$classesNo.")班";
        $jwClassesModel = new JwClasses();
        $jwClassesModel->setName($name);
        $jwClassesModel->setClassesNo($classesNo);
        $jwClassesModel->setProductId($productId);
        $jwClassesModel->setStudyPlanId($studyPlanId);
        $newClassesId = $this->db()->save($jwClassesModel);
        if($newClassesId){
            $classMemberModel = new JwClassesMembers();
            $classMemberModel->setUid($uid);
            $classMemberModel->setClassesId($newClassesId);
            $classMemberModel->setType(1);
            $this->db()->save($classMemberModel);
        }
        return $newClassesId;
    }

    /**
     * 获取学习计划
     * @param $id
     * @param array $studyPlans
     * @return array|void
     */
    public function getStudyPlan($id, &$studyPlans = [])
    {
        $sql = "SELECT a FROM Edux:MallGoods a WHERE a.id=:id";
        $info = $this->db()->fetchOne($sql, ['id' => $id]);
        if (!$info) return [];
        //非组合商品
        if ($info["productId"]) {
            //判断产品是否存在
            $sql = "SELECT a.id FROM Edux:TeachProducts a WHERE a.id=:id AND a.status=1";
            $productInfo = $this->db()->fetchOne($sql, ['id' => $info["productId"]]);
            if (!$productInfo) return;
            //获取默认的开课计划
            $sql = "SELECT a FROM Edux:TeachStudyPlan a WHERE a.productId=:productId AND a.isDefault=1 AND a.status=1";
            $studyPlan = $this->db()->fetchOne($sql, ["productId" => $info["productId"]]);

            if (!$studyPlan) {  //如果没有默认的开课计划,按照最新创建时间处理
                $sql = "SELECT a FROM Edux:TeachStudyPlan a WHERE a.productId=:productId AND a.status=1 ORDER BY a.createdAt DESC";
                $studyPlan = $this->db()->fetchOne($sql, ["productId" => $info["productId"]]);
            }
            if ($studyPlan) {
                $studyPlanId = $studyPlan["id"];
                array_push($studyPlans, $studyPlanId);
            }
        } else {
            //组合商品，获取所有相关商品
            $sql3 = "SELECT a FROM Edux:MallGoodsGroup a WHERE a.goodsId=:goodsId ORDER BY a.createdAt ASC";
            $params = [];
            $params['goodsId'] = $id;
            $allGoodIds = $this->db()->fetchFields("groupGoodsId", $sql3, $params);
            if ($allGoodIds) {
                foreach ($allGoodIds as $av) {
                    $this->getStudyPlan($av, $studyPlans);
                }
            }
        }
    }

    /**
     * 获取订单最后的价格
     * @param $uuid
     * @param $goodsIds
     * @param $couponSn
     */
    public function getOrderRealPrice($detail, $goodsIds, $couponSn, $uid){
        $discount = 0;
        $groupCouponId = 0;
        $couponWarn = "";
        $shopPrice = $detail['shopPrice'];
        $goodsList = [];
        if(!$goodsIds){
            //单个商品
            $goodsIds = [$detail['id']];
            $goodsList = $this->goodsService->getByIds($goodsIds);
        }else{
            $goodsList = $this->goodsService->getByIds($goodsIds);
            $shopPrice = 0;
            if($goodsList){
                foreach ($goodsList as $v){
                    $shopPrice = $shopPrice+$v['shopPrice'];
                }
            }
        }

        if($couponSn){
            $groupCouponInfo = $this->checkCoupon($couponSn, $uid);
            if($groupCouponInfo){
                $groupCouponId = $groupCouponInfo["id"];
                $discount = $this->parseDiscount($detail['id'], $groupCouponInfo, $shopPrice);
                $shopPrice = $shopPrice-$discount;
            }else{
                $couponWarn = $this->error()->getLast();
            }
        }else{
            list($discount, $groupCouponId) = $this->formatGoodCouponNoCode($detail['id'], $shopPrice);
            $shopPrice = $shopPrice-$discount;
        }

        return [$shopPrice, $discount, $goodsList, $couponWarn, $groupCouponId];
    }

    /**
     * 匹配非优惠码优惠
     * @param $goodId
     */
    public function formatGoodCouponNoCode($goodId, $shopPrice){
        $now = time();
        $sql = "SELECT a FROM Edux:MallCouponGroup a WHERE a.status=1 AND a.hasCode=0 AND a.expirationStart<{$now} AND a.expirationEnd >{$now}";
        $all = $this->db()->fetchAll($sql);
        if(!$all) return [0,0];
        //寻找最优惠模式
        $groupCouponId = 0;
        $lastDiscount = 0;
        foreach ($all as $groupCouponInfo){
            $discount = $this->parseDiscount($goodId, $groupCouponInfo, $shopPrice);
            if($lastDiscount<$discount){
                $lastDiscount = $discount;
                $groupCouponId = $groupCouponInfo["id"];
            }
        }
        return [$discount, $groupCouponId];
    }

    /**
     * 更新订单状态
     * @param $orderId
     * @param int $status
     * @return array
     */
    public function updateOrderStatus($orderId, $status=2){
        $dql = "SELECT a FROM Edux:MallOrder a WHERE a.id = :id";
        $detail = $this->db()->fetchOne($dql, ["id"=>$orderId], 1);
        if(!$detail) return [];
        $detail->setOrderStatus($status);
        $this->db()->save($detail);
        
        //更新课程或者试卷状态
        $planSql = "UPDATE Edux:MallOrderStudyPlan a SET a.orderStatus =:orderStatus WHERE a.orderId =:orderId";
        $this->db()->execute($planSql, ["orderStatus"=>$status, "orderId"=>$orderId]);
        
        $testSql = "UPDATE Qa:TeachTestOrder a SET a.orderStatus =:orderStatus WHERE a.orderId =:orderId";
        $this->db()->execute($testSql, ["orderStatus"=>$status, "orderId"=>$orderId]);

    }

    /**
     * 支付跳转
     *
     * @param $paymentType
     * @param $orderNo
     * @param $name
     * @param $orderAmount
     * @return bool|string
     */
    public function toPay($paymentType, $orderNo, $subject, $orderAmount){
        //支付宝
        if($paymentType == 1){
            $siteDomain = $this->getOption("app.domain");
            $returnUrl = trim($siteDomain, "/").$this->generateUrl("app_order_buyreturn", ["orderNo"=>$orderNo]);
            $orderAmount = $orderAmount/100;
            $body = $this->alipayService->pagePay($subject, $orderNo, $orderAmount, $returnUrl);
            return $body;
        }

        //微信
        if($paymentType == 2){
            $siteDomain = $this->getOption("app.domain");
            $returnUrl = trim($siteDomain, "/").$this->generateUrl("app_glob_pay_wxpayCallback");
            $result = $this->wxpayService->pagePay($subject, $orderNo, $orderAmount, $returnUrl, $tradeType="NATIVE", $orderNo);
//            $result['code_url'] 得到二维码内容
            return $result;
        }

    }


    /**
     * 支持多次支付的情况
     * @param $uid
     * @param $amount
     * @param $orderId
     * @return mixed
     */
    public function completePay($uid, $amount, $orderId)
    {
        $transactionId = date('Ymd') . "p" . session_create_id("");
        $model = new MallPay();
        $model->setUid($uid);
        $model->setTransactionId($transactionId);
        $model->setAmount($amount);
        $model->setOrderId($orderId);
        $model->setPayTime(time());
        return $this->db()->save($model);
    }

    public function orderSuccessFreeOrder($orderNo){
        $orderInfo = $this->getSimpleByOrderNo($orderNo);
        if(!$orderInfo) return $this->error()->add("订单不存在!");
        if($orderInfo['orderStatus'] == 2){
            return true;
        }
        $uid = $orderInfo['uid'];
        $payType = $orderInfo['paymentType'];
        $amount = $orderInfo['orderAmount'];
        //支付成功，更新订单状态，更新优惠码状态
        $this->updateOrderStatus($orderInfo["id"],2);
//        $this->useCoupon($uid, $orderInfo['couponSn'], 1);
        //自动分班
        $this->autoClasses($orderInfo["id"], $uid);
        //生成支付信息
        $amount = $amount*100;
        $this->completePay($uid, $amount, $orderInfo["id"]);
        return true;
    }

    public function orderSuccess($orderNo){
        $orderInfo = $this->getSimpleByOrderNo($orderNo);
        if(!$orderInfo) return $this->error()->add("订单不存在!");
        if($orderInfo['orderStatus'] == 2){
            return true;
        }
        $uid = $orderInfo['uid'];
        $payType = $orderInfo['paymentType'];
        $amount = $orderInfo['orderAmount'];
        if($payType == 1){ //支付宝
            $result = $this->alipayService->query($orderNo);
            if(!$result){
               return false;
            }
            $trade = $result["trade_status"];
            if(($trade == "TRADE_SUCCESS") || ($trade == "TRADE_FINISHED")){
                //支付成功，更新订单状态，更新优惠码状态
                $this->updateOrderStatus($orderInfo["id"],2);
                $this->useCoupon($uid, $orderInfo['couponSn'], 1);
                //自动分班
                $this->autoClasses($orderInfo["id"], $uid);
                //生成支付信息
                $amount = $amount*100;
                $this->completePay($uid, $amount, $orderInfo["id"]);
                return true;
            }
        }

        if($payType == 2) { //微信
            $result = $this->wxpayService->query($orderNo);
            if (!$result) {
                return false;
            }
            if (($result['return_code'] == "SUCCESS") && ($result['trade_state'] == "SUCCESS")) {
                $this->updateOrderStatus($orderInfo["id"],2);
                $this->useCoupon($uid, $orderInfo['couponSn'], 1);
                //自动分班
                $this->autoClasses($orderInfo["id"], $uid);
                //生成支付信息
                $amount = $amount * 100;
                $this->completePay($uid, $amount, $orderInfo["id"]);
                return true;
            }
        }
    }

    /**
     * 验证签名
     *
     * @param array $requestData
     * @param int $paymentType 1-支付宝，2-微信
     */
    public function checkSign($requestData, $paymentType=1){
        if($paymentType == 1){
            return $this->alipayService->checkSign($requestData);
        }
    }

}
