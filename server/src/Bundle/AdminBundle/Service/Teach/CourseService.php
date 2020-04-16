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
use App\Entity\TeachAgreement;
use Knp\Component\Pager\PaginatorInterface;

class CourseService extends BaseService
{
    protected $paginator;
    protected $categroyService;
    protected $userService;
    protected $schoolService;

    public function __construct(PaginatorInterface $paginator, CategroyService $categroyService, UserService $userService, SchoolService $schoolService)
    {
        $this->paginator = $paginator;
        $this->categroyService = $categroyService;
        $this->userService = $userService;
        $this->schoolService = $schoolService;
    }

    public function getList($request, $page, $pageSize){
        $sql = $this->getFormatRequestSql($request);
        $dql = "SELECT a FROM App:TeachCourse a " . $sql;
        $em = $this->getDoctrine()->getManager();
        $query = $em->createQuery($dql);
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
                $firstCateGoryId = $vArr['first_category_id'];
                $cateGoryId = $vArr['category_id'];
                $schooleId = $vArr['school_id'];
                $createrUid = $vArr['create_uid'];
                $firstCateGory = $this->categroyService->getById($firstCateGoryId);
                $vArr['brand'] = $firstCateGory['name'];
                $cateGroy = $this->categroyService->getById($cateGoryId);
                $vArr['category'] = $cateGroy['name'];
                $createrUser = $this->userService->getById($createrUid);
                $vArr['creater'] = $createrUser['full_name'];
                $vArr['courseHourView'] = $vArr['course_hour']/100;
                $school = $this->schoolService->getById($schooleId);
                $vArr['school'] = $school['name'];
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

    public function getSchools(){
        $schools = $this->schoolService->getAll();
        if(!$schools) return [];
        $result = [];
        foreach ($schools as $v){
            $result[$v['name']] = $v['id'];
        }
        return $result;
    }

    public function add($name, $content, $isShow){
        $model = new TeachAgreement();
        $model->setName($name);
        $model->setIsShow($isShow);
        $model->setContent($content);
        return $this->save($model);
    }

    public function getById($id){
        $sql = "SELECT a FROM App:TeachCourse a WHERE a.id=:id";
        return $this->fetchOne($sql, ['id'=>$id]);
    }

    public function getByName($name, $id=0){
        $sql = "SELECT a FROM App:TeachAgreement a WHERE a.name=:name";
        $params = [];
        $params['name'] = $name;
        if($id){
            $sql = $sql." AND a.id !=:id ";
            $params['id'] = $id;
        }
        return $this->fetchOne($sql, $params);
    }

    public function edit($id, $name, $content, $isShow){
        $sql = "SELECT a FROM App:TeachAgreement a WHERE a.id=:id";
        $model = $this->fetchOne($sql, ['id'=>$id] ,1 );
        $model->setName($name);
        $model->setIsShow($isShow);
        $model->setContent($content);
        return $this->save($model);
    }

    public function del($id){
        $sql = "SELECT a FROM App:TeachAgreement a WHERE a.id=:id";
        $model = $this->fetchOne($sql, ['id'=>$id] ,1 );
        return $this->delete($model);
    }

}
