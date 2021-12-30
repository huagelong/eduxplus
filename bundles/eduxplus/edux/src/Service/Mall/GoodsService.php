<?php

/**
 * @Author: kaihui.wang
 * @Contact  hpuwang@gmail.com
 * @Version: 1.0.0
 * @Date: 2020/4/12 16:06
 */

namespace Eduxplus\EduxBundle\Service\Mall;


use Eduxplus\CoreBundle\Lib\Base\AdminBaseService;
use Eduxplus\EduxBundle\Service\Teach\AgreementService;
use Eduxplus\EduxBundle\Service\Teach\CategoryService;
use Eduxplus\EduxBundle\Service\Teach\ProductService;
use Eduxplus\CoreBundle\Service\UserService;
use Eduxplus\CoreBundle\Lib\Base\BaseService;
use Eduxplus\CoreBundle\Lib\Service\Base\EsService;
use Eduxplus\CoreBundle\Lib\Service\HelperService;
use Eduxplus\EduxBundle\Entity\MallGoods;
use Eduxplus\EduxBundle\Entity\MallGoodsGroup;
use Eduxplus\EduxBundle\Entity\MallGoodsIntroduce;
use Knp\Component\Pager\PaginatorInterface;

class GoodsService extends AdminBaseService
{
    protected $paginator;
    protected $userService;
    protected $productService;
    protected $categoryService;
    protected $agreementService;
    protected $helperService;
    protected $esService;

    public function __construct(
        PaginatorInterface $paginator,
        UserService $userService,
        ProductService $productService,
        CategoryService $categoryService,
        AgreementService $agreementService,
        HelperService $helperService,
        EsService $esService
    ) {
        $this->paginator = $paginator;
        $this->userService = $userService;
        $this->productService = $productService;
        $this->categoryService = $categoryService;
        $this->agreementService = $agreementService;
        $this->helperService = $helperService;
        $this->esService = $esService;
    }


    public function getList($request, $page, $pageSize){
        return $this->_getList($request, $page, $pageSize,1);
    }

    public function _getList($request, $page, $pageSize, $goodType)
    {
        $sql = $this->getFormatRequestSql($request);

        if($sql){
            $sql = $sql." AND a.goodType=". $goodType;
        }else{
            $sql = " WHERE a.goodType=". $goodType;
        }

        $dql = "SELECT a FROM Edux:MallGoods a " . $sql . " ORDER BY a.id DESC";

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
                $createrUid = $vArr['createrUid'];
                $createrUser = $this->userService->getById($createrUid);
                $vArr['creater'] = $createrUser['fullName'];
                $categoryId = $vArr["categoryId"];
                $cate = $this->categoryService->getById($categoryId);
                $path = trim($cate['findPath'], ',');
                $pathArr = explode(",", $path);
                $brandId = end($pathArr);
                $vArr['category'] = $cate["name"];
                $brandInfo = $this->categoryService->getById($brandId);
                $vArr['brand'] = $brandInfo["name"];
                $vArr['marketPrice'] = $vArr['marketPrice'] / 100;
                $vArr['shopPrice'] = $vArr['shopPrice'] / 100;
                $agreementId = $vArr['agreementId'];
                $agreement = $this->agreementService->getById($agreementId);
                $vArr['agreement'] = $agreement['name'];

                $goodsImg = $vArr['goodsImg'];
                $goodsImgArr = $goodsImg ? current(json_decode($goodsImg, true)) : "";
                $vArr['goodsImg'] = $goodsImgArr;

                $goodsSmallImg = $vArr['goodsSmallImg'];
                $goodsSmallImgArr = $goodsSmallImg ? current(json_decode($goodsSmallImg, true)) : "";
                $vArr['goodsSmallImg'] = $goodsSmallImgArr;

                $itemsArr[] = $vArr;
            }
        }
        return [$pagination, $itemsArr];
    }

    public function checkName($name, $id = 0, $goodType=1)
    {
        $sql = "SELECT a FROM Edux:MallGoods a where a.name =:name AND a.goodType=".$goodType;
        $params = [];
        $params['name'] = $name;
        if ($id) {
            $sql = $sql . " AND a.id !=:id ";
            $params['id'] = $id;
        }
        return $this->fetchOne($sql, $params);
    }


    public function add(
        $uid,
        $name,
        $productId,
        $goodsId,
        $categoryId,
        $subhead,
        $teachingMethod,
        $teachers,
        $courseHour,
        $courseCount,
        $marketPrice,
        $shopPrice,
        $buyNumberFalse,
        $goodsImg,
        $goodsSmallImg,
        $status,
        $sort,
        $agreementId,
        $groupType,
        $descr,
        $seoDescr,
        $seoKeyWord,
        $tags,
        $aliasName,
        $topValue,
        $recommendValue,
        $goodType=1
    ) {
        $cate = $this->categoryService->getById($categoryId);
        $path = trim($cate['findPath'], ',');
        $pathArr = explode(",", $path);
        $brandId = end($pathArr);
        $uuid = $this->helperService->getUuid();
        $model = new MallGoods();
        $model->setCreaterUid($uid);
        $model->setUuid($uuid);
        $model->setName($name);
        $model->setSort($sort);
        $model->setTopValue($topValue);
        $model->setRecommendValue($recommendValue);
        $model->setGoodType($goodType);
        if ($productId) $model->setProductId($productId);
        $model->setFirstCategoryId($brandId);
        $model->setCategoryId($categoryId);
        $model->setAgreementId($agreementId);
        if ($subhead) $model->setSubhead($subhead);
        $model->setTeachingMethod($teachingMethod);
        $model->setTeachingTeacher(json_encode($teachers));
        $model->setCourseHour($courseHour);
        $model->setCourseCount($courseCount);
        $model->setMarketPrice($marketPrice * 100);
        $model->setShopPrice($shopPrice * 100);
        $model->setBuyNumberFalse($buyNumberFalse);
        $model->setBuyNumber(0);
        if ($tags) $model->setTags($tags);
        if ($seoDescr) $model->setSeoDescr($seoDescr);
        if ($seoKeyWord) $model->setSeoKeyWord($seoKeyWord);

        if ($goodsImg) $model->setGoodsImg($goodsImg);
        if ($goodsSmallImg) $model->setGoodsSmallImg($goodsSmallImg);
        $model->setStatus($status);
        //
        if ($aliasName) $model->setAliasName($aliasName);
        if ($goodsId) {
            $model->setGroupType($groupType);
            $model->setIsGroup(1);
        } else {
            $model->setGroupType(0);
            $model->setIsGroup(0);
        }
        $id = $this->save($model);

        if ($goodsId && $id) {
            foreach ($goodsId as $gid) {
                $goodsModel = new MallGoodsGroup();
                $goodsModel->setGoodsId($id);
                $goodsModel->setGroupGoodsId($gid);
                $this->save($goodsModel);
            }
        }

        if ($descr) {
            $descrModel = new MallGoodsIntroduce();
            $descrModel->setGoodsId($id);
            $descrModel->setContent($descr);
            $descrModel->setIntroduceType(1);
            $this->save($descrModel);
        }

        if($status){
            $this->saveEs($id, $name);
        }

        return $id;
    }

    public function saveEs($id, $name){
        $this->esService->esUpdate("goods", $id, ["name"=>$name]);
    }

    public function delEs($id){
        $this->esService->esDel("goods", $id);
    }

    public function getYearRange(){
        $n = 20;
        $arr = [];
        $year = (int) date('Y');
        $i = 0;
        while ($i<$n){
            $arr[$year-$i]=$year-$i;
            $i++;
        }

        return $arr;
    }

    public function getById($id)
    {
        $sql = "SELECT a FROM Edux:MallGoods a WHERE a.id=:id";
        $info = $this->fetchOne($sql, ['id' => $id]);
        if (!$info) return [];
        $sql2 = "SELECT a FROM Edux:MallGoodsIntroduce a WHERE a.goodsId=:goodsId AND a.introduceType=1";
        $introduce = $this->fetchOne($sql2, ['goodsId' => $id]);
        $info["introduce"] = $introduce;
        return $info;
    }

    public function getByIds($ids)
    {
        $sql = "SELECT a FROM Edux:MallGoods a where a.id IN(:id) ";
        $params = [];
        $params['id'] = $ids;
        return $this->fetchAll($sql, $params);
    }

    public function getSelectByIds($ids)
    {
        $courses = $this->getByIds($ids);
        if (!$courses) return [];
        $rs = [];
        foreach ($courses as $v) {
            $rs[$v['name']] = $v['id'];
        }
        return $rs;
    }

    public function getByName($name, $id = 0, $goodType=1)
    {
        $sql = "SELECT a FROM Edux:MallGoods a WHERE a.name=:name AND a.goodType=".$goodType;
        $params = [];
        $params['name'] = $name;
        if ($id) {
            $sql = $sql . " AND a.id !=:id ";
            $params['id'] = $id;
        }
        return $this->fetchOne($sql, $params);
    }

    public function getGroupGoods($id)
    {
        $sql = "SELECT a FROM Edux:MallGoodsGroup a WHERE a.goodsId=:goodsId";
        $params = [];
        $params['goodsId'] = $id;
        $allGoodIds = $this->fetchFields("groupGoodsId", $sql, $params);
        if (!$allGoodIds) return [];
        return $allGoodIds;
    }

    public function getSelectGoods($id)
    {
        $sql = "SELECT a FROM Edux:MallGoodsGroup a WHERE a.goodsId=:goodsId";
        $params = [];
        $params['goodsId'] = $id;
        $allGoodIds = $this->fetchFields("groupGoodsId", $sql, $params);
        if (!$allGoodIds) return [];
        $sql2 = "SELECT a FROM Edux:MallGoods a WHERE a.id IN(:id)";
        $params2 = [];
        $params2['id'] = $allGoodIds;
        $allGoods =  $this->fetchAll($sql2, $params2);
        $tmp = [];
        foreach ($allGoods as $v) {
            $tmp[$v["name"]] = $v["id"];
        }
        return $tmp;
    }

    public function edit(
        $id,
        $name,
        $productId,
        $goodsId,
        $categoryId,
        $subhead,
        $teachingMethod,
        $teachers,
        $courseHour,
        $courseCount,
        $marketPrice,
        $shopPrice,
        $buyNumberFalse,
        $goodsImg,
        $goodsSmallImg,
        $status,
        $sort,
        $agreementId,
        $groupType,
        $descr,
        $seoDescr,
        $seoKeyWord,
        $tags,
        $aliasName,
        $topValue,
        $recommendValue
    ) {
        $cate = $this->categoryService->getById($categoryId);
        $path = trim($cate['findPath'], ',');
        $pathArr = explode(",", $path);
        $brandId = end($pathArr);

        $sql = "SELECT a FROM Edux:MallGoods a WHERE a.id=:id";
        $model = $this->fetchOne($sql, ['id' => $id], 1);

        $model->setName($name);
        $model->setSort($sort);
        if ($productId) $model->setProductId($productId);
        $model->setFirstCategoryId($brandId);
        $model->setCategoryId($categoryId);
        $model->setAgreementId($agreementId);
        if ($subhead) $model->setSubhead($subhead);
        $model->setTeachingMethod($teachingMethod);
        $model->setTeachingTeacher(json_encode($teachers));
        $model->setCourseHour($courseHour);
        $model->setCourseCount($courseCount);
        $model->setMarketPrice($marketPrice * 100);
        $model->setShopPrice($shopPrice * 100);
        $model->setBuyNumberFalse($buyNumberFalse);
        $model->setBuyNumber(0);
        $model->setSeoDescr($seoDescr);
        $model->setSeoKeyWord($seoKeyWord);
        $model->setTags($tags);
        $model->setTopValue($topValue);
        $model->setRecommendValue($recommendValue);

        if ($goodsImg) $model->setGoodsImg($goodsImg);
        if ($goodsSmallImg) $model->setGoodsSmallImg($goodsSmallImg);
        $model->setStatus($status);
        //
        if ($aliasName)  $model->setAliasName($aliasName);
        if ($goodsId) {
            $model->setGroupType($groupType);
            $model->setIsGroup(1);
        } else {
            $model->setGroupType(0);
            $model->setIsGroup(0);
        }
        $this->save($model);

        if ($goodsId && $id) {
            $dql = "DELETE FROM Edux:MallGoodsGroup a WHERE a.goodsId=:goodsId";
            $this->hardExecute($dql, ["goodsId" => $id]);
            foreach ($goodsId as $gid) {
                $goodsModel = new MallGoodsGroup();
                $goodsModel->setGoodsId($id);
                $goodsModel->setGroupGoodsId($gid);
                $this->save($goodsModel);
            }
        }

        if ($descr) {
            $dql = "SELECT a FROM Edux:MallGoodsIntroduce a WHERE a.goodsId=:goodsId AND a.introduceType=1";
            $descrModel = $this->fetchOne($dql, ["goodsId" => $id], 1);
            if ($descrModel) {
                $descrModel->setContent($descr);
                $this->save($descrModel);
            } else {
                $descrModel = new MallGoodsIntroduce();
                $descrModel->setGoodsId($id);
                $descrModel->setContent($descr);
                $descrModel->setIntroduceType(1);
                $this->save($descrModel);
            }
        }


        if($status){
            $this->saveEs($id, $name);
        }else{
            $this->delEs($id);
        }

        return $id;
    }

    public function del($id)
    {
        //先删除说明
        $sql = "DELETE FROM Edux:MallGoodsIntroduce a WHERE a.goodsId=:goodsId";
        $this->execute($sql, ["goodsId" => $id]);
        //删除group
        $sql = "DELETE FROM Edux:MallGoodsGroup a WHERE a.goodsId=:goodsId";
        $this->execute($sql, ["goodsId" => $id]);

        $sql = "DELETE FROM Edux:MallGoods a WHERE a.id=:id";
        $this->execute($sql, ["id" => $id]);

        $this->delEs($id);

        return true;
    }

    /**
     * 如果被人使用的话，不让删除
     *
     * @param $id
     * @return mixed
     */
    public function hasGroup($id)
    {
        $sql = "SELECT a FROM Edux:MallGoodsGroup a WHERE a.groupGoodsId=:groupGoodsId";
        return $this->fetchOne($sql, ['groupGoodsId' => $id]);
    }

    public function searchGoodsName($name, $goodType=1)
    {
        $sql = "SELECT a FROM Edux:MallGoods a WHERE a.name like :name AND a.status=1 AND a.goodType=".$goodType." ORDER BY a.id DESC";
        $params = [];
        $params['name'] = "%" . $name . "%";
        $all = $this->fetchAll($sql, $params);
        if (!$all) return [];
        $rs = [];
        foreach ($all as $v) {
            $tmp = [];
            $tmp['id'] = $v['id'];
            $tmp['text'] = $v['name'];
            $rs[] = $tmp;
        }
        return $rs;
    }

    public function switchStatus($id, $state)
    {
        $sql = "SELECT a FROM Edux:MallGoods a WHERE a.id=:id";
        $model = $this->fetchOne($sql, ['id' => $id], 1);
        $model->setStatus($state);
        $result = $this->save($model);

        if($state){
            $this->saveEs($id, $model->getName());
        }else{
            $this->delEs($id);
        }

        return $result;
    }

    public function getStudyPlan($id, &$studyPlans = [])
    {
        $sql = "SELECT a FROM Edux:MallGoods a WHERE a.id=:id AND a.goodType=1";
        $info = $this->fetchOne($sql, ['id' => $id]);
        if (!$info) return [];
        //非组合商品
        if ($info["productId"]) {
            //判断产品是否存在
            $sql = "SELECT a.id FROM Edux:TeachProducts a WHERE a.id=:id AND a.status=1";
            $productInfo = $this->fetchOne($sql, ['id' => $info["productId"]]);
            if (!$productInfo) return;
            //获取默认的开课计划
            $sql = "SELECT a FROM Edux:TeachStudyPlan a WHERE a.productId=:productId AND a.isDefault=1 AND a.status=1";
            $studyPlan = $this->fetchOne($sql, ["productId" => $info["productId"]]);

            if (!$studyPlan) {  //如果没有默认的开课计划,按照最新创建时间处理
                $sql = "SELECT a FROM Edux:TeachStudyPlan a WHERE a.productId=:productId AND a.status=1 ORDER BY a.createdAt DESC";
                $studyPlan = $this->fetchOne($sql, ["productId" => $info["productId"]]);
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
            $allGoodIds = $this->fetchFields("groupGoodsId", $sql3, $params);
            if ($allGoodIds) {
                foreach ($allGoodIds as $av) {
                    $this->getStudyPlan($av['groupGoodsId'], $studyPlans);
                }
            }
        }
    }
}
