<?php
/**
 * @Author: kaihui.wang
 * @Contact  hpuwang@gmail.com
 * @Version: 1.0.0
 * @Date: 2020/4/12 16:06
 */

namespace App\Bundle\AdminBundle\Service\Mall;


use App\Bundle\AdminBundle\Service\Teach\ProductService;
use App\Bundle\AdminBundle\Service\UserService;
use App\Bundle\AppBundle\Lib\Base\BaseService;
use App\Entity\TeachStudyPlan;
use App\Entity\TeachStudyPlanSub;
use Knp\Component\Pager\PaginatorInterface;

class GoodsService extends BaseService
{
    protected $paginator;
    protected $userService;
    protected $productService;

    public function __construct(PaginatorInterface $paginator,UserService $userService, ProductService $productService)
    {
        $this->paginator = $paginator;
        $this->userService = $userService;
        $this->productService = $productService;
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
                    foreach ($subArr as &$sub){
                        $courseId = $sub['courseId'];
                        $course = $this->productService->getById($courseId);
                        $sub['course'] = $course;
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
        $info = $this->fetchOne($sql, ['id'=>$id]);
        $sqlSub = "SELECT a.courseId FROM App:TeachStudyPlanSub a WHERE a.studyPlanId=:studyPlanId ORDER BY a.sort";
        $sub=$this->fetchFields("courseId", $sqlSub, ['studyPlanId'=>$id]);
        if(!$sub){
            $info['sub'] = [];
        }else{
            $info['sub'] = $sub;
        }
        return $info;
    }

    public function getSubById($subId){
        $sqlSub = "SELECT a FROM App:TeachStudyPlanSub a WHERE a.id=:id ORDER BY a.sort";
        $sub=$this->fetchOne( $sqlSub, ['id'=>$subId]);
        if($sub){
            $studyPlanId = $sub['studyPlanId'];
            $sql = "SELECT a FROM App:TeachStudyPlan a WHERE a.id=:id";
            $info = $this->fetchOne($sql, ['id'=>$studyPlanId]);
            $sub['study_plan'] = $info;
            return $sub;
        }else{
            return [];
        }
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

    public function edit($id, $name, $isDefault, $isBlock, $applyedAt, $courseIds, $descr){
        $sql= "SELECT a FROM App:TeachStudyPlan a WHERE a.id=:id";
        $model = $this->fetchOne($sql, ['id'=>$id], 1);
        $model->setName($name);
        $model->setDescr($descr);
        $model->setIsDefault($isDefault);
        $model->setIsBlock($isBlock);
        $applyedAt = strtotime($applyedAt);
        $model->setApplyedAt($applyedAt);
        $this->save($model);
        if($courseIds) {
            $sqlSub = "SELECT a FROM App:TeachStudyPlanSub a WHERE a.studyPlanId=:studyPlanId";
            $models = $this->fetchAll($sqlSub, ["studyPlanId"=>$id], 1);
            if($models) $this->hardDelete($models);
            $sort = 0;
            foreach ($courseIds as $courseId) {
                $modelSub = new TeachStudyPlanSub();
                $modelSub->setCourseId($courseId);
                $modelSub->setSort($sort);
                $modelSub->setStudyPlanId($id);
                $this->save($modelSub);
                $sort++;
            }
        }
    }

    public function delsub($id){
        $sql = "SELECT a FROM App:TeachStudyPlanSub a WHERE a.id=:id";
        $model = $this->fetchOne($sql, ['id'=>$id] ,1 );
        return $model?$this->hardDelete($model):false;
    }

    public function del($id){
        $sql = "SELECT a FROM App:TeachStudyPlan a WHERE a.id=:id";
        $model = $this->fetchOne($sql, ['id'=>$id] ,1 );
        return $model?$this->delete($model):false;
    }

   public function updateSort($id, $data){

   }

   public function hasSub($id){
       $sql = "SELECT a FROM App:TeachStudyPlanSub a WHERE a.studyPlanId=:studyPlanId";
       return $this->fetchOne($sql, ['studyPlanId'=>$id] );
   }

   public function searchCourseName($kw){
       $courses = $this->productService->searchName($kw);
       if(!$courses) return [];
       $rs = [];
       foreach ($courses as $v){
           $tmp = [];
           $tmp['id'] = $v['id'];
           $tmp['text'] = $v['name'];
           $rs[] = $tmp;
       }
       return $rs;
   }

    public function getCourseByIds($courseIds){
        $courses = $this->productService->getByIds($courseIds);
        if(!$courses) return [];
        $rs = [];
        foreach ($courses as $v){
            $rs[$v['name']] = $v['id'];
        }
        return $rs;
    }

}
