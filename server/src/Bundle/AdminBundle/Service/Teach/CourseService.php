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
use App\Entity\TeachCourse;
use Knp\Component\Pager\PaginatorInterface;

class CourseService extends BaseService
{
    protected $paginator;
    protected $categoryService;
    protected $userService;
    protected $schoolService;

    public function __construct(PaginatorInterface $paginator, CategoryService $categoryService, UserService $userService, SchoolService $schoolService)
    {
        $this->paginator = $paginator;
        $this->categoryService = $categoryService;
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
                $firstCateGoryId = $vArr['firstCategoryId'];
                $cateGoryId = $vArr['categoryId'];
                $schooleId = $vArr['schoolId'];
                $createrUid = $vArr['createUid'];
                $firstCateGory = $this->categoryService->getById($firstCateGoryId);
                $vArr['brand'] = $firstCateGory['name'];
                $bigImg = $vArr['bigImg'];
                $bigImgArr = $bigImg?current(json_decode($bigImg, true)):"";
                $vArr['bigImg'] = $bigImgArr;
                $cateGroy = $this->categoryService->getById($cateGoryId);
                $vArr['category'] = $cateGroy['name'];
                $createrUser = $this->userService->getById($createrUid);
                $vArr['creater'] = $createrUser['fullName'];
                $vArr['courseHourView'] = $vArr['courseHour']/100;
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

    public function add($uid,$name, $type, $bigImg, $descr, $categoryId, $schoolId, $courseHour){
        $cate = $this->categoryService->getById($categoryId);
        $path = trim($cate['findPath'], ',');
        $pathArr = explode(",", $path);
        $brandId = end($pathArr);
        $model = new TeachCourse();
        $model->setName($name);
        $model->setDescr($descr);
        $model->setType($type);
        if(!$bigImg){
            $bigImg = json_encode(["/assets/images/course.jpg"]);
        }
        $model->setBigImg($bigImg);
        $model->setFirstCategoryId($brandId);
        $model->setStatus(0);
        $model->setCategoryId($categoryId);
        $model->setSchoolId($schoolId);
        $model->setCourseHour($courseHour*100);
        $model->setCreateUid($uid);
        return $this->save($model);
    }

    public function getById($id){
        $sql = "SELECT a FROM App:TeachCourse a WHERE a.id=:id";
        return $this->fetchOne($sql, ['id'=>$id]);
    }

    public function getByName($name, $id=0){
        $sql = "SELECT a FROM App:TeachCourse a WHERE a.name=:name";
        $params = [];
        $params['name'] = $name;
        if($id){
            $sql = $sql." AND a.id !=:id ";
            $params['id'] = $id;
        }
        return $this->fetchOne($sql, $params);
    }

    public function edit($id, $name, $type, $bigImg, $descr, $categoryId, $schoolId, $courseHour){
        $cate = $this->categoryService->getById($categoryId);
        $path = trim($cate['findPath'], ',');
        $pathArr = explode(",", $path);
        $brandId = end($pathArr);
        $courceSql= "SELECT a FROM App:TeachCourse a WHERE a.id=:id";
        $model = $this->fetchOne($courceSql, ['id'=>$id], 1);
        $model->setName($name);
        $model->setDescr($descr);
        $model->setType($type);
        if(!$bigImg){
            $bigImg = json_encode(["/assets/images/course.jpg"]);
        }
        $model->setBigImg($bigImg);
        $model->setFirstCategoryId($brandId);
        $model->setCategoryId($categoryId);
        $model->setSchoolId($schoolId);
        $model->setCourseHour($courseHour*100);
        return $this->save($model);
    }

    public function del($id){
        $sql = "SELECT a FROM App:TeachCourse a WHERE a.id=:id";
        $model = $this->fetchOne($sql, ['id'=>$id] ,1 );
        return $this->delete($model);
    }

    public function switchStatus($id, $state){
        $sql = "SELECT a FROM App:TeachCourse a WHERE a.id=:id";
        $model = $this->fetchOne($sql, ['id'=>$id], 1);
        $model->setStatus($state);
        return $this->save($model);
    }

    public function hasChapter($id){
        $sql = "SELECT a FROM App:TeachCourseChapter a WHERE a.courseId=:courseId";
        return $this->fetchOne($sql, ["courseId"=>$id]);
    }

}
