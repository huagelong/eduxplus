<?php
/**
 * @Author: kaihui.wang
 * @Contact  hpuwang@gmail.com
 * @Version: 1.0.0
 * @Date: 2020/4/12 16:06
 */

namespace App\Bundle\AdminBundle\Service\Teach;


use App\Bundle\AdminBundle\Service\Jw\SchoolService;
use App\Bundle\AdminBundle\Service\UserService;
use App\Bundle\AppBundle\Lib\Base\BaseService;
use App\Entity\TeachStudyPlan;
use App\Entity\TeachStudyPlanSub;
use Knp\Component\Pager\PaginatorInterface;

class StudyPlanService extends BaseService
{
    protected $paginator;
    protected $userService;
    protected $courseService;

    public function __construct(PaginatorInterface $paginator,UserService $userService, CourseService $courseService)
    {
        $this->paginator = $paginator;
        $this->userService = $userService;
        $this->courseService = $courseService;
    }

    public function getList($id, $page, $pageSize){
        $dql = "SELECT a FROM App:TeachStudyPlan a WHERE a.productId=:productId";
        $em = $this->getDoctrine()->getManager();
        $query = $em->createQuery($dql);
        $query= $query->setParameters(['productId'=>$id]);
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
                $createrUid = $vArr['createUid'];
                $createrUser = $this->userService->getById($createrUid);
                $vArr['creater'] = $createrUser['fullName'];
                $studyPlanId = $vArr['id'];
                $sql2 = "SELECT a FROM App:TeachStudyPlanSub a WHERE a.studyPlanId=:studyPlanId ORDER BY a.sort ASC";
                $subArr = $this->fetchAll($sql2, ["studyPlanId"=>$studyPlanId]);

                if($subArr){
                    foreach ($subArr as $sub){
                        $courseId = $sub['id'];
                        $course = $this->courseService->getById($courseId);
                        $subArr['courses'][] = $course;
                    }
                }
                $vArr['sub'] = $subArr;
                $itemsArr[] = $vArr;
            }
        }
        return [$pagination, $itemsArr];
    }

    public function searchAdminFullName($name){
        $users = $this->userService->searchAdminFullName($name);
        if(!$users) return [];
        $rs = [];
        foreach ($users as $v){
            $tmp = [];
            $tmp['id'] = $v['id'];
            $tmp['text'] = $v['fullName'];
            $rs[] = $tmp;
        }
        return $rs;
    }


    public function add($id, $name, $isDefault, $isBlock, $applyedAt, $courseIds, $createdUid, $descr){
        $model = new TeachStudyPlan();
        $model->setName($name);
        $model->setDescr($descr);
        $model->setProductId($id);
        $model->setIsDefault($isDefault);
        $model->setIsBlock($isBlock);
        $applyedAt = strtotime($applyedAt);
        $model->setApplyedAt($applyedAt);
        $model->setCreateUid($createdUid);
        $planId = $this->save($model);
        if($courseIds) {
            $sort = 0;
            foreach ($courseIds as $courseId) {
                $modelSub = new TeachStudyPlanSub();
                $modelSub->setCourseId($courseId);
                $modelSub->setSort($sort);
                $modelSub->setStudyPlanId($planId);
                $this->save($modelSub);
                $sort++;
            }
        }
        return $planId;
    }

    public function getById($id){
        $sql = "SELECT a FROM App:TeachStudyPlan a WHERE a.id=:id";
        return $this->fetchOne($sql, ['id'=>$id]);
    }

    public function getByName($name, $id=0){
        $sql = "SELECT a FROM App:TeachStudyPlan a WHERE a.name=:name";
        $params = [];
        $params['name'] = $name;
        if($id){
            $sql = $sql." AND a.id !=:id ";
            $params['id'] = $id;
        }
        return $this->fetchOne($sql, $params);
    }

    public function edit($id, $name, $agreementId, $status, $maxMemberNumber, $categoryId, $studyPlanAuto,$descr){
        $cate = $this->categoryService->getById($categoryId);
        $path = trim($cate['findPath'], ',');
        $pathArr = explode(",", $path);
        $brandId = end($pathArr);

        $courceSql= "SELECT a FROM App:TeachStudyPlan a WHERE a.id=:id";
        $model = $this->fetchOne($courceSql, ['id'=>$id], 1);
        $model->setName($name);
        $model->setDescr($descr);
        $model->setStatus($status);
        $model->setFirstCategoryId($brandId);
        $model->setCategoryId($categoryId);
        $model->setAgreementId($agreementId);
        $model->setMaxMemberNumber($maxMemberNumber);
        $model->setStudyPlanAuto($studyPlanAuto);
        return $this->save($model);
    }

    public function del($id){
        $sql = "SELECT a FROM App:TeachStudyPlan a WHERE a.id=:id";
        $model = $this->fetchOne($sql, ['id'=>$id] ,1 );
        return $this->delete($model);
    }

   public function updateSort($id, $data){

   }

   public function hasSub($id){
       $sql = "SELECT a FROM App:TeachStudyPlanSub a WHERE a.studyPlanId=:studyPlanId";
       return $this->fetchOne($sql, ['studyPlanId'=>$id] );
   }

   public function searchCourseName($kw){
       $users = $this->courseService->searchName($kw);
       if(!$users) return [];
       $rs = [];
       foreach ($users as $v){
           $tmp = [];
           $tmp['id'] = $v['id'];
           $tmp['text'] = $v['name'];
           $rs[] = $tmp;
       }
       return $rs;
   }

}
