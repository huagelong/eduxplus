<?php

/**
 * @Author: kaihui.wang
 * @Contact  hpuwang@gmail.com
 * @Version: 1.0.0
 * @Date: 2020/5/26 09:57
 */

namespace App\Bundle\AppBundle\Service;


use Eduxplus\CoreBundle\Lib\Base\AppBaseService;
use Eduxplus\CoreBundle\Entity\MallGoodsFav;
use Knp\Component\Pager\PaginatorInterface;

class GoodsService extends AppBaseService
{
    protected $teacherService;
    protected $categoryService;
    protected $paginator;

    public function __construct(TeacherService $teacherService, CategoryService $categoryService, PaginatorInterface $paginator)
    {
        $this->teacherService = $teacherService;
        $this->categoryService = $categoryService;
        $this->paginator = $paginator;
    }

    /**
     * 获取最近直播
     * @param int $limit
     */
    public function getRecentlyLiveCourse($limit=2){
        $end = time()+3600*24*2;
        $start = time()-10;
        $sql = "SELECT a FROM App:TeachCourseChapter a WHERE a.openTime < ".$end." AND a.openTime > ".$start." AND a.studyWay=1 ORDER BY a.openTime DESC";
        $result = $this->fetchAll($sql, [], 0, 200);
        if(!$result) return $result;
        $goods=[];
        $i=0;
        foreach ($result as $v){
            if($i>($limit-1)) continue;
            $chapterId = $v['id'];
            $courseId = $v['courseId'];
            $openTime = $v['openTime'];
            $sqlCourse = "SELECT a FROM App:TeachCourse a WHERE a.id=".$courseId." AND a.status=1";
            $courseResult = $this->fetchOne($sqlCourse);
            if(!$courseResult) continue;
            $planSql = "
                SELECT a FROM App:TeachStudyPlanSub a WHERE a.courseId = ".$courseId."
            ";
            $planResult = $this->fetchOne($planSql);
            if(!$planResult) continue;

            $studyPlanId = $planResult["studyPlanId"];
            $planSql = "
                SELECT a FROM App:TeachStudyPlan a WHERE a.id = ".$studyPlanId." AND a.status=1
            ";
            $planResult = $this->fetchOne($planSql);
            if(!$planResult) continue;

            $productId = $planResult['productId'];
            $sqlProduct = "SELECT a FROM App:TeachProducts a WHERE a.id=".$productId." AND a.status=1";
            $productResult = $this->fetchOne($sqlProduct);
            if(!$productResult) continue;
            $goodsSql = "SELECT a FROM App:MallGoods a WHERE a.productId=".$productId." AND a.status=1 AND  a.goodType=1 ";
            $goodsResult = $this->fetchOne($goodsSql);
            if(!$goodsResult) continue;
            $i++;
            $goodsResult['chapterId'] = $chapterId;
            $goodsResult['openTime'] = date('Y-m-d H:i:s', $openTime);
            $goodsResult['shopPriceView'] = number_format($goodsResult['shopPrice'] / 100, 2);
            $goodsResult['name'] = $goodsResult['aliasName']?$goodsResult['aliasName']:$goodsResult['name'];
            $goods[] = $goodsResult;
        }
        return $goods;
    }

    /**
     * 热门
     * @param int $limit
     * @return mixed
     */
    public function getGoodsByTopValue($limit=8){
        $sql = "SELECT a FROM App:MallGoods a WHERE a.topValue>0 AND a.status=1 AND  a.goodType=1  ORDER BY a.topValue DESC";
        $result = $this->fetchAll($sql, [], 0, $limit);
        if(!$result) return $result;
        foreach ($result as &$v) {
            $v['shopPriceView'] = number_format($v['shopPrice'] / 100, 2);
            $v['name'] = $v['aliasName']?$v['aliasName']:$v['name'];
        }
        return $result;
    }

    /**
     * 推荐
     * @param int $limit
     * @return mixed
     */
    public function getGoodsByRecommendValue($limit=4){
        $sql = "SELECT a FROM App:MallGoods a WHERE a.recommendValue>0 AND a.status=1 AND  a.goodType=1 ORDER BY a.recommendValue DESC";
        $result = $this->fetchAll($sql, [], 0, $limit);
        if(!$result) return $result;
        foreach ($result as &$v) {
            $v['shopPriceView'] = number_format($v['shopPrice'] / 100, 2);
            $v['name'] = $v['aliasName']?$v['aliasName']:$v['name'];
        }
        return $result;
    }

    public function getCategoryGoods($categoryId, $level, $isFree, $page=1,$pageSize=20)
    {
        $shopPriceStr = "";
        if($isFree==1){
            $shopPriceStr = " AND a.shopPrice=0";
        }else if($isFree==2){
            $shopPriceStr = " AND a.shopPrice>0";
        }

        if ($level == 2) {
            $sql = "SELECT a FROM App:MallGoods a WHERE a.categoryId=:categoryId ".$shopPriceStr." AND a.status=1  AND  a.goodType=1  ORDER BY a.sort DESC";
            $categoryIds = $categoryId;
        } else {
            $subCates = $this->categoryService->getSubsCategory($categoryId);
            $categoryIds = $subCates ? array_column($subCates, "id") : [];
            if ($categoryIds) {
                array_push($categoryIds, $categoryId);
            } else {
                $categoryIds = [$categoryId];
            }
            $sql = "SELECT a FROM App:MallGoods a WHERE a.categoryId IN (:categoryId)  ".$shopPriceStr." AND a.status=1 AND  a.goodType=1  ORDER BY a.sort DESC";
        }

        $em = $this->getDoctrine()->getManager();
        $em = $this->enableSoftDeleteable($em);
        $query = $em->createQuery($sql);
        $query = $query->setParameters(["categoryId" => $categoryIds]);
        $pagination = $this->paginator->paginate(
            $query,
            $page,
            $pageSize
        );

        $items = $pagination->getItems();
        if (!$items) return [$pagination, []];
        $itemsArr = [];
        foreach ($items as $v) {
            $vArr =  $this->toArray($v);
            $vArr['tagsArr'] = explode(",", $vArr['tags']);
            $vArr['shopPriceView'] = number_format($vArr['shopPrice'] / 100, 2);
            $itemsArr[]= $vArr;
        }

        return [$pagination,$itemsArr];
    }

    /**
     * 获取课程安排
     *
     * @param $id
     */
    public function getStudyPlan($id)
    {
        $sql = "SELECT a FROM App:MallGoods a WHERE a.id=:id";
        $info = $this->fetchOne($sql, ['id' => $id]);
        if (!$info) return [];
        $info['studyPlans'] = [];
        $info['child'] = [];
        //非组合商品
        if ($info["productId"]) {
            //判断产品是否存在
            $sql = "SELECT a.id FROM App:TeachProducts a WHERE a.id=:id AND a.status=1";
            $productInfo = $this->fetchOne($sql, ['id' => $info["productId"]]);
            if (!$productInfo) return $info;
            //            var_dump($productInfo);exit;
            //获取默认的开课计划
            $sql = "SELECT a FROM App:TeachStudyPlan a WHERE a.productId=:productId AND a.isDefault=1 AND a.status=1";
            $studyPlan = $this->fetchOne($sql, ["productId" => $info["productId"]]);
            //            var_dump($studyPlan);exit;
            if (!$studyPlan) {  //如果没有默认的开课计划,按照最新创建时间处理
                $sql = "SELECT a FROM App:TeachStudyPlan a WHERE a.productId=:productId AND a.status=1 ORDER BY a.createdAt DESC";
                $studyPlan = $this->fetchOne($sql, ["productId" => $info["productId"]]);
            }
            if (!$studyPlan) return $info;
            $studyPlanId = $studyPlan["id"];
            $sql = "SELECT a FROM App:TeachStudyPlanSub a WHERE a.studyPlanId=:studyPlanId ORDER BY a.sort ASC";
            $studyPlans = $this->fetchAll($sql, ["studyPlanId" => $studyPlanId]);
            if (!$studyPlans) return $info;
            foreach ($studyPlans as &$sv) {
                $sql = "SELECT a FROM App:TeachCourse a WHERE a.id=:id AND a.status=1";
                $courseInfo = $this->fetchOne($sql, ["id" => $sv["courseId"]]);
                if (!$courseInfo) continue;
                //获取章节等
                $rs = $this->getChapter($sv["courseId"], 0);
                $courseInfo['chapters'] = $rs;
                $sv["course"] = $courseInfo;
            }
            $info['studyPlans'] = $studyPlans;
            return $info;
        } else {
            //组合商品，获取所有相关商品
            $allGoodIds = [];
            $this->getGoodsGroupIds($id,$allGoodIds);
            if (!$allGoodIds) return $info;
            foreach ($allGoodIds as $av) {
                $info['child'][] = $this->getStudyPlan($av);
            }
            return $info;
        }
    }

    public function getChapter($courseId, $parentId)
    {
        $sql = "SELECT a FROM App:TeachCourseChapter a WHERE a.courseId=:courseId AND a.parentId=:parentId ORDER BY a.sort ASC";
        $list = $this->fetchAll($sql, ["courseId" => $courseId, "parentId" => $parentId]);
        if (!$list) [];
        $rs = [];
        foreach ($list as &$cv) {
            $pid = $cv['parentId'];
            $chapterId = $cv['id'];
            //其他内容
            $cv['video'] = $this->getVideoById($chapterId);
            $cv['teacher'] = $this->getTeacherIds($chapterId);
            $cv['Materials'] = $this->getMaterialsById($chapterId);
            $cv['child'] = $this->getChapter($courseId, $chapterId);
            $rs[$chapterId] = $cv;
        }
        return $rs;
    }

    public function getTeacherIds($chapterId)
    {
        $sql = "SELECT a FROM App:TeachCourseTeachers a WHERE a.chapterId=:chapterId";
        return $this->fetchFields('teacherId', $sql, ['chapterId' => $chapterId]);
    }

    public function getVideoById($id)
    {
        $sql = "SELECT a FROM App:TeachCourseVideos a WHERE a.chapterId=:chapterId";
        return $this->fetchOne($sql, ['chapterId' => $id]);
    }

    public function getMaterialsById($id)
    {
        $sql = "SELECT a FROM App:TeachCourseMaterials a WHERE a.chapterId=:chapterId";
        return $this->fetchOne($sql, ['chapterId' => $id]);
    }


    public function getByIds($ids){
        $sql = "SELECT a FROM App:MallGoods a WHERE a.id IN (:id) ";
        $infos = $this->fetchAll($sql, ['id' => $ids]);

        if($infos){
            foreach ($infos as &$v){
                $v['courseHour'] = $v['courseHour'] / 10;
                $v['marketPriceView'] = number_format($v['marketPrice']/100, 2);
                $v['shopPriceView'] = number_format($v['shopPrice']/100, 2);
            }
        }

        return $infos;
    }

    public function getSimpleByUuid($uuid){
        $sql = "SELECT a FROM App:MallGoods a WHERE a.uuid=:uuid ";
        $info = $this->fetchOne($sql, ['uuid' => $uuid]);
        if($info){
            $info['name'] = $info['aliasName']?$info['aliasName']:$info['name'];
        }
        return $info;
    }

    public function getSimpleById($id){
        $sql = "SELECT a FROM App:MallGoods a WHERE a.id=:id";
        $info = $this->fetchOne($sql, ['id' => $id]);
        if($info){
            $info['name'] = $info['aliasName']?$info['aliasName']:$info['name'];
        }
        return $info;
    }

    public function getByUuId($uuid)
    {
        $sql = "SELECT a FROM App:MallGoods a WHERE a.uuid=:uuid ";
        $info = $this->fetchOne($sql, ['uuid' => $uuid]);
        if (!$info) return [];
        $id = $info["id"];
        $teachingTeacherIds = $info['teachingTeacher']?json_decode($info['teachingTeacher'], true):[];
        $info['teachers'] =$teachingTeacherIds?$this->getTeachersByIds($teachingTeacherIds):[];
        $info['courseHour'] = $info['courseHour'] / 10;
        $info['marketPrice'] = $info['marketPrice'] / 100;
        $info['shopPrice'] = $info['shopPrice'] / 100;
        $info['marketPriceView'] = number_format($info['marketPrice'], 2);
        $info['shopPriceView'] = number_format($info['shopPrice'], 2);

        $sql2 = "SELECT a FROM App:MallGoodsIntroduce a WHERE a.goodsId=:goodsId AND a.introduceType=1";
        $introduce = $this->fetchOne($sql2, ['goodsId' => $id]);
        $info["introduce"] = $introduce;
        $info['childGoods'] = $this->getGoodsGroup($id);
        $level = 0;
        if($info['childGoods']){
            foreach ($info['childGoods'] as $v){
                if($v['isGroup']) $level=1;
            }
        }
        $info['level'] = $level;
        return $info;
    }

    public function getTeachersByIds($ids)
    {
        $sql = "SELECT a FROM App:JwTeacher a WHERE a.id IN (:id)";
        return $this->fetchAll($sql, ['id' => $ids]);
    }

    public function getById($id)
    {
        $sql = "SELECT a FROM App:MallGoods a WHERE a.id=:id";
        $info = $this->fetchOne($sql, ['id' => $id]);
        if (!$info) return [];
        $info['courseHour'] = $info['courseHour'] / 10;
        $info['marketPrice'] = $info['marketPrice'] / 100;
        $info['shopPrice'] = $info['shopPrice'] / 100;
        $info['marketPriceView'] = number_format($info['marketPrice'], 2);
        $info['shopPriceView'] = number_format($info['shopPrice'], 2);

        $sql2 = "SELECT a FROM App:MallGoodsIntroduce a WHERE a.goodsId=:goodsId AND a.introduceType=1";
        $introduce = $this->fetchOne($sql2, ['goodsId' => $id]);
        $info["introduce"] = $introduce;
        $info['childGoods'] = $this->getGoodsGroup($id);

        return $info;
    }

    /**
     * 获取所有商品id
     * @param $id
     * @param $goodIds
     * @return array
     */
    public function getGoodsGroupIds($id, &$goodIds){
        $sql3 = "SELECT a FROM App:MallGoodsGroup a WHERE a.goodsId=:goodsId";
        $params = [];
        $params['goodsId'] = $id;
        $allGoodIds = $this->fetchFields("groupGoodsId", $sql3, $params);
        if (!$allGoodIds) {
            return [];
        } else {
            $goodIds = array_merge($allGoodIds, $goodIds);
            foreach ($allGoodIds as &$v) {
                $this->getGoodsGroupIds($v, $goodIds);
            }
        }
    }


    /**
     * 获取商品组
     * @param $id
     * @return mixed
     */
    public function getGoodsGroup($id)
    {
        $sql3 = "SELECT a FROM App:MallGoodsGroup a WHERE a.goodsId=:goodsId";
        $params = [];
        $params['goodsId'] = $id;
        $allGoodIds = $this->fetchFields("groupGoodsId", $sql3, $params);
        if (!$allGoodIds) {
            return [];
        } else {
            $sql4 = "SELECT a FROM App:MallGoods a where a.id IN(:id) ";
            $params = [];
            $params['id'] = $allGoodIds;
            $goodsInfo =  $this->fetchAll($sql4, $params);
            if ($goodsInfo) {
                foreach ($goodsInfo as &$v) {
                    $childGoods = $this->getGoodsGroup($v['id']);
                    $v['name'] = $v['aliasName']?$v['aliasName']:$v['name'];
                    $v['childGoods'] = $childGoods;
                }
            }
            return $goodsInfo;
        }
    }

    /**
     * 获取收藏
     * @param $goodsId
     * @param $uid
     */
    public function getFav($goodsId, $uid){
        $sql = "SELECT a FROM App:MallGoodsFav a WHERE a.uid=:uid AND a.goodsId =:goodsId ";
        return $this->fetchOne($sql, ["uid"=>$uid, "goodsId"=>$goodsId]);
    }


    /**
     * 删除收藏
     *
     * @param $goodsId
     * @param $uid
     * @return bool
     */
    public function delFav($goodsId, $uid){
        $sql = "SELECT a FROM App:MallGoodsFav a WHERE a.uid=:uid AND a.goodsId =:goodsId ";
        $model =  $this->fetchOne($sql, ["uid"=>$uid, "goodsId"=>$goodsId], 1);
        return $this->hardDelete($model);
    }

    /**
     * 收藏商品
     * @param $goodsId
     * @param $uid
     */
    public function addFav($goodsId, $uid){
        $model = new MallGoodsFav();
        $model->setGoodsId($goodsId);
        $model->setUid($uid);
        return $this->save($model);
    }

    /**
     * 收藏列表
     * @param $uid
     * @param $page
     * @param $pageSize
     * @return array
     */
    public function favList($uid, $page, $pageSize){
        $dql = "SELECT a FROM App:MallGoodsFav a WHERE a.uid =:uid";
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
        if($items) {
            foreach ($items as $v) {
                $vArr = $this->toArray($v);
                $sql = "SELECT a FROM App:MallGoods a WHERE a.id =:id ";
                $goodsInfo = $this->fetchOne($sql, ["id"=>$vArr['goodsId']]);
                $goodsInfo['shopPriceView'] = number_format($goodsInfo['shopPrice'] / 100, 2);
                $goodsInfo['name'] = $goodsInfo['aliasName']?$goodsInfo['aliasName']:$goodsInfo['name'];
                $vArr['goodsInfo'] = $goodsInfo;
                $itemsArr[] = $vArr;
            }
        }
        return [$pagination, $itemsArr];
    }


}
