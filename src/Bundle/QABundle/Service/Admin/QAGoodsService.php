<?php
/**
 * @Author: kaihui.wang
 * @Contact  hpuwang@gmail.com
 * @Version: 1.0.0
 * @Date: 2021/4/13 10:30
 */

namespace App\Bundle\QABundle\Service\Admin;


use App\Bundle\AdminBundle\Service\Mall\GoodsService;

class QAGoodsService extends GoodsService
{


    public function getList($request, $page, $pageSize){
        return $this->_getList($request, $page, $pageSize,2);
    }

    public function addQA($uid,
                         $name,
                         $productId,
                         $goodsId,
                         $categoryId,
                         $subhead,
                         $year,
                         $marketPrice,
                         $shopPrice,
                         $buyNumberFalse,
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
                         $recommendValue){
        $teachingMethod = 0;
        $courseHour = 0;
        $courseCount = 0;
        $teachers="";
        $goodsImg="";
        $goodsSmallImg="";
        $id = $this->add(
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
            2
        );

        $sql = "SELECT a FROM App:MallGoods a WHERE a.id=:id";
        $model = $this->fetchOne($sql, ['id' => $id], 1);
        $model->setParameter1($year);
        $this->save($model);
        return $id;
    }

    public function editQA(
        $id,
        $name,
        $productId,
        $goodsId,
        $categoryId,
        $year,
        $subhead,
        $marketPrice,
        $shopPrice,
        $buyNumberFalse,
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
    ){
        $teachingMethod = 0;
        $courseHour = 0;
        $courseCount = 0;
        $teachers = "";
        $goodsImg="";
        $goodsSmallImg="";
        $this->edit(
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
        );

        $sql = "SELECT a FROM App:MallGoods a WHERE a.id=:id";
        $model = $this->fetchOne($sql, ['id' => $id], 1);
        $model->setParameter1($year);
        $this->save($model);
        return $id;
    }

}
