<?php

/**
 * @Author: kaihui.wang
 * @Contact  hpuwang@gmail.com
 * @Version: 1.0.0
 * @Date: 2020/4/12 16:06
 */

namespace Eduxplus\EduxBundle\Service\Teach;


use Eduxplus\CoreBundle\Lib\Base\AdminBaseService;
use Eduxplus\EduxBundle\Service\Jw\SchoolService;
use Eduxplus\CoreBundle\Service\UserService;
use Eduxplus\CoreBundle\Lib\Base\BaseService;
use Eduxplus\EduxBundle\Entity\TeachStudyPlan;
use Eduxplus\EduxBundle\Entity\TeachStudyPlanSub;
use Knp\Component\Pager\PaginatorInterface;

class StudyPlanService extends AdminBaseService
{
    protected $paginator;
    protected $userService;
    protected $courseService;

    public function __construct(PaginatorInterface $paginator, UserService $userService, CourseService $courseService)
    {
        $this->paginator = $paginator;
        $this->userService = $userService;
        $this->courseService = $courseService;
    }

    public function getList($id, $page, $pageSize)
    {
        $dql = "SELECT a FROM Edux:TeachStudyPlan a WHERE a.productId=:productId ORDER BY a.id DESC";
        $em = $this->getDoctrine()->getManager();
        $query = $em->createQuery($dql);
        $query = $query->setParameters(['productId' => $id]);
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
                $createrUid = $vArr['createUid'];
                $createrUser = $this->userService->getById($createrUid);
                $vArr['creater'] = $createrUser['fullName'];
                $studyPlanId = $vArr['id'];
                $sql2 = "SELECT a FROM Edux:TeachStudyPlanSub a WHERE a.studyPlanId=:studyPlanId ORDER BY a.sort ASC";
                $subArr = $this->db()->fetchAll($sql2, ["studyPlanId" => $studyPlanId]);

                if ($subArr) {
                    foreach ($subArr as &$sub) {
                        $courseId = $sub['courseId'];
                        $course = $this->courseService->getById($courseId);
                        $sub['course'] = $course;
                    }
                }
                $vArr['sub'] = $subArr;
                $itemsArr[] = $vArr;
            }
        }
        return [$pagination, $itemsArr];
    }

    public function searchAdminFullName($name)
    {
        $users = $this->userService->searchAdminFullName($name);
        if (!$users) return [];
        $rs = [];
        foreach ($users as $v) {
            $tmp = [];
            $tmp['id'] = $v['id'];
            $tmp['text'] = $v['fullName'];
            $rs[] = $tmp;
        }
        return $rs;
    }


    public function add($id, $name, $isDefault, $isBlock, $applyedAt, $courseIds, $createdUid, $descr, $status = 0)
    {
        $model = new TeachStudyPlan();
        $model->setName($name);
        $model->setDescr($descr);
        $model->setProductId($id);
        $model->setIsDefault($isDefault);
        $model->setIsBlock($isBlock);
        $applyedAt = strtotime($applyedAt);
        $model->setApplyedAt($applyedAt);
        $model->setCreateUid($createdUid);
        $model->setStatus($status);
        $planId = $this->db()->save($model);
        if ($courseIds) {
            $sort = 0;
            foreach ($courseIds as $courseId) {
                $modelSub = new TeachStudyPlanSub();
                $modelSub->setCourseId($courseId);
                $modelSub->setSort($sort);
                $modelSub->setStudyPlanId($planId);
                $this->db()->save($modelSub);
                $sort++;
            }
        }
        return $planId;
    }

    public function getSimpleById($id)
    {
        $sql = "SELECT a FROM Edux:TeachStudyPlan a WHERE a.id=:id";
        $info = $this->db()->fetchOne($sql, ['id' => $id]);
        return $info;
    }


    public function getById($id)
    {
        $sql = "SELECT a FROM Edux:TeachStudyPlan a WHERE a.id=:id";
        $info = $this->db()->fetchOne($sql, ['id' => $id]);
        $sqlSub = "SELECT a.courseId FROM Edux:TeachStudyPlanSub a WHERE a.studyPlanId=:studyPlanId ORDER BY a.sort";
        $sub = $this->db()->fetchFields("courseId", $sqlSub, ['studyPlanId' => $id]);
        if (!$sub) {
            $info['sub'] = [];
        } else {
            $info['sub'] = $sub;
        }
        return $info;
    }

    public function getSubById($subId)
    {
        $sqlSub = "SELECT a FROM Edux:TeachStudyPlanSub a WHERE a.id=:id ORDER BY a.sort";
        $sub = $this->db()->fetchOne($sqlSub, ['id' => $subId]);
        if ($sub) {
            $studyPlanId = $sub['studyPlanId'];
            $sql = "SELECT a FROM Edux:TeachStudyPlan a WHERE a.id=:id";
            $info = $this->db()->fetchOne($sql, ['id' => $studyPlanId]);
            $sub['study_plan'] = $info;
            return $sub;
        } else {
            return [];
        }
    }

    public function getByName($name, $id = 0)
    {
        $sql = "SELECT a FROM Edux:TeachStudyPlan a WHERE a.name=:name";
        $params = [];
        $params['name'] = $name;
        if ($id) {
            $sql = $sql . " AND a.id !=:id ";
            $params['id'] = $id;
        }
        return $this->db()->fetchOne($sql, $params);
    }

    public function edit($id, $name, $isDefault, $isBlock, $applyedAt, $courseIds, $descr, $status = 0)
    {
        $sql = "SELECT a FROM Edux:TeachStudyPlan a WHERE a.id=:id";
        $model = $this->db()->fetchOne($sql, ['id' => $id], 1);
        $model->setName($name);
        $model->setDescr($descr);
        $model->setIsDefault($isDefault);
        $model->setIsBlock($isBlock);
        $model->setStatus($status);
        $applyedAt = strtotime($applyedAt);
        $model->setApplyedAt($applyedAt);
        $this->db()->save($model);
        if ($courseIds) {
            $sqlSub = "SELECT a FROM Edux:TeachStudyPlanSub a WHERE a.studyPlanId=:studyPlanId";
            $models = $this->db()->fetchAll($sqlSub, ["studyPlanId" => $id], 1);
            if ($models) $this->db()->hardDelete($models);
            $sort = 0;
            foreach ($courseIds as $courseId) {
                $modelSub = new TeachStudyPlanSub();
                $modelSub->setCourseId($courseId);
                $modelSub->setSort($sort);
                $modelSub->setStudyPlanId($id);
                $this->db()->save($modelSub);
                $sort++;
            }
        }
    }

    public function delsub($id)
    {
        $sql = "SELECT a FROM Edux:TeachStudyPlanSub a WHERE a.id=:id";
        $model = $this->db()->fetchOne($sql, ['id' => $id], 1);
        return $model ? $this->db()->hardDelete($model) : false;
    }

    public function del($id)
    {
        $sql = "SELECT a FROM Edux:TeachStudyPlan a WHERE a.id=:id";
        $model = $this->db()->fetchOne($sql, ['id' => $id], 1);
        return $model ? $this->db()->delete($model) : false;
    }

    public function updateSort($id, $data)
    {
        if ($data) {
            foreach ($data as $v) {
                $childrens = isset($v["children"]) ? $v["children"] : [];
                if (!$childrens) continue;
                $i = 0;
                foreach ($childrens as $cv) {
                    $childId = $cv["id"];
                    $sql = "SELECT a FROM Edux:TeachStudyPlanSub a WHERE a.id=:id";
                    $model = $this->db()->fetchOne($sql, ['id' => $childId], 1);
                    $model->setSort($i);
                    $this->db()->save($model);
                    $i++;
                }
            }
        }
    }

    public function hasSub($id)
    {
        $sql = "SELECT a FROM Edux:TeachStudyPlanSub a WHERE a.studyPlanId=:studyPlanId";
        return $this->db()->fetchOne($sql, ['studyPlanId' => $id]);
    }

    public function searchCourseName($kw)
    {
        $courses = $this->courseService->searchName($kw);
        if (!$courses) return [];
        $rs = [];
        foreach ($courses as $v) {
            $tmp = [];
            $tmp['id'] = $v['id'];
            $tmp['text'] = $v['name'];
            $rs[] = $tmp;
        }
        return $rs;
    }

    public function getCourseByIds($courseIds)
    {
        $courses = $this->courseService->getByIds($courseIds);
        if (!$courses) return [];
        $rs = [];
        foreach ($courses as $v) {
            $rs[$v['name']] = $v['id'];
        }
        return $rs;
    }

    public function switchStatus($id, $state)
    {
        $sql = "SELECT a FROM Edux:TeachStudyPlan a WHERE a.id=:id";
        $model = $this->db()->fetchOne($sql, ['id' => $id], 1);
        $model->setStatus($state);
        return $this->db()->save($model);
    }
}
