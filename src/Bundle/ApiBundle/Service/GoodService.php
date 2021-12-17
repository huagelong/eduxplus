<?php

/**
 * @Author: kaihui.wang
 * @Contact  hpuwang@gmail.com
 * @Version: 1.0.0
 * @Date: 2020/3/28 19:38
 */

namespace App\Bundle\ApiBundle\Service;

use Eduxplus\CoreBundle\Lib\Base\ApiBaseService;

class GoodService extends ApiBaseService
{

    public function getHomeData()
    {
        //获取类目
        $sql = "SELECT a FROM App:TeachCategory a WHERE a.parentId = 0 AND a.isShow=1 ORDER BY a.sort ASC";
        $cateArr = $this->fetchAll($sql);
        $cateList = [];
        if ($cateArr) {
            foreach ($cateArr as $k => $v) {
                $tmp = [];
                $tmp['id'] = $v["id"];
                $tmp['name'] = $v["name"];
                $tmp['mobileIcon'] = $this->jsonGet($v["mobileIcon"]);
                $cateList[] = $tmp;
            }
        }
        //推荐商品
        $topSql = "SELECT a FROM App:MallGoods a WHERE a.topValue >0 AND a.goodType=1 ORDER BY a.topValue DESC ";
        $goodArr = $this->fetchAll($topSql, [], 0, 10);
        $goodList = [];
        if ($goodArr) {
            foreach ($goodArr as $k => $v) {
                $tmp = [];
                $tmp['uuid'] = $v["uuid"];
                $tmp['name'] = $v["name"];
                $tmp['teachingTeacher'] = [];
                $teachStr = $v["teachingTeacher"];
                if ($teachStr) {
                    $teacherIds = json_decode($teachStr, true);
                    $tmp['teachingTeacher'] = $this->getTeachers($teacherIds);
                }
                $tmp['tags'] = $v["tags"] ? explode(",", $v["tags"]) : [];
                $tmp['courseCount'] = $v["courseCount"];
                $tmp['courseHour'] = $v["courseHour"] / 10;
                $tmp['buyNumberFalse'] = $v["buyNumberFalse"];
                $tmp['marketPrice'] = $v["marketPrice"] / 100;
                $tmp['shopPrice'] = $v["shopPrice"] / 100;
                $goodList[] = $tmp;
            }
        }
        return ["cateList" => $cateList, "goodList" => $goodList];
    }

    public function getTeachers($teacherIds)
    {
        $sql = "SELECT a.id, a.name,a.gravatar FROM App:JwTeacher a WHERE a.id IN (:id)";
        $result = $this->fetchAll($sql, ["id" => $teacherIds]);
        if ($result) {
            foreach ($result as &$v) {
                $v['gravatar'] = $this->jsonGet($v["gravatar"]);
            }
        }
        return $result;
    }

    /**
     * 获取子类信息
     */
    public function getChildCate($cateId)
    {
        $sql = "SELECT a FROM App:TeachCategory a WHERE a.parentId=:parentId AND a.isShow=1 ORDER BY a.sort ASC";
        $list = $this->fetchAll($sql, ["parentId" => $cateId]);
        return $list;
    }

    /**
     *
     * 获取分类下商品
     *
     * @return void
     */
    public function getCateGoods($cateId)
    {
        $cateId = (int) $cateId;
        $sql = "SELECT a FROM App:TeachCategory a WHERE a.findPath LIKE :findPath AND a.isShow=1 ORDER BY a.sort ASC";
        $subCates = $this->fetchAll($sql, ["findPath" => "%," . $cateId . ",%"]);
        $categoryIds = $subCates ? array_column($subCates, "id") : [];
        if ($categoryIds) {
            array_push($categoryIds, $cateId);
        } else {
            $categoryIds = [$cateId];
        }

        $sql = "SELECT a FROM App:MallGoods a WHERE a.categoryId IN (:categoryId) AND a.status=1 AND  a.goodType=1  ORDER BY a.sort ASC";
        $goodArr = $this->fetchAll($sql, ["categoryId" => $categoryIds]);
        if (!$goodArr) return [];

        $goodList = [];
        if ($goodArr) {
            foreach ($goodArr as $k => $v) {
                $tmp = [];
                $tmp['uuid'] = $v["uuid"];
                $tmp['name'] = $v["name"];
                $tmp['teachingTeacher'] = [];
                $teachStr = $v["teachingTeacher"];
                if ($teachStr) {
                    $teacherIds = json_decode($teachStr, true);
                    $tmp['teachingTeacher'] = $this->getTeachers($teacherIds);
                }
                $tmp['tags'] = $v["tags"] ? explode(",", $v["tags"]) : [];
                $tmp['courseCount'] = $v["courseCount"];
                $tmp['courseHour'] = $v["courseHour"] / 10;
                $tmp['buyNumberFalse'] = $v["buyNumberFalse"];
                $tmp['marketPrice'] = $v["marketPrice"] / 100;
                $tmp['shopPrice'] = $v["shopPrice"] / 100;
                $goodList[] = $tmp;
            }
        }

        return $goodList;
    }


    public function getSubCate($cateId)
    {
        $sql = "SELECT a FROM App:TeachCategory a WHERE a.parentId = :parentId AND a.isShow=1 ORDER BY a.sort ASC";
        $subCates = $this->fetchAll($sql, ["parentId" => $cateId]);
        if (!$subCates) return [];
        $list = [];
        $tmp = [];
        $tmp["name"] = "全部";
        $tmp['id'] = 0;
        $list[] = $tmp;
        foreach ($subCates as $v) {
            $tmp = [];
            $tmp["name"] = $v['name'];
            $tmp['id'] = $v['id'];
            $list[] = $tmp;
        }
        return $list;
    }

    /**
     * 获取用户购买学习的课程
     *
     * @param $uid
     * @param int $id
     */
    public function getUidCourse($uid, $page=1, $pageSize=15)
    {
        $offset = ($page-1)*$pageSize;

        $time = time();
        $dqlCount = "SELECT count(a.courseId) as cnt FROM App:MallOrderStudyPlan a WHERE a.uid=:uid";
        $countInfo = $this->fetchOne($dqlCount, ["uid"=>$uid]);
        $totalCount = $countInfo['cnt'];

        $dql = "SELECT a.courseId, abs({$time}-a.openTime) as diffTime FROM App:MallOrderStudyPlan a
                WHERE a.uid=:uid ORDER BY diffTime ASC";
        $items = $this->fetchAll($dql, ["uid"=>$uid],0,$pageSize, $offset);
        $itemsArr = [];

        if ($items) {
            foreach ($items as $vArr) {
                $courseId = $vArr['courseId'];
                $sql = "SELECT a FROM App:TeachCourse a WHERE a.id=:id";
                $tmp = $this->fetchOne($sql, ['id' => $courseId]);
                $tmp['courseHour'] = $tmp['courseHour']/100;
                $tmp['bigImg'] = $this->jsonGet($tmp['bigImg']);
                $tmp['courseId'] = $courseId;
                $itemsArr[] = $tmp;
            }
        }

        $totalPage = ceil($totalCount/$pageSize);

        return [$totalCount,$totalPage, $itemsArr];
    }
}
