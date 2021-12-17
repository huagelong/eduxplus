<?php

/**
 * @Author: kaihui.wang
 * @Contact  hpuwang@gmail.com
 * @Version: 1.0.0
 * @Date: 2020/4/12 16:06
 */

namespace Eduxplus\CoreBundle\Service\Teach;


use Eduxplus\CoreBundle\Lib\Base\AdminBaseService;
use Eduxplus\CoreBundle\Service\Jw\SchoolService;
use Eduxplus\CoreBundle\Service\UserService;
use Eduxplus\CoreBundle\Lib\Base\BaseService;
use Eduxplus\CoreBundle\Lib\Service\Vod\AliyunVodService;
use Eduxplus\CoreBundle\Lib\Service\Vod\TengxunyunVodService;
use Eduxplus\CoreBundle\Entity\TeachCourse;
use Knp\Component\Pager\PaginatorInterface;

class CourseService extends AdminBaseService
{
    protected $paginator;
    protected $categoryService;
    protected $userService;
    protected $schoolService;
    protected $tengxunyunVodService;
    protected $aliyunVodService;

    public function __construct(
        PaginatorInterface $paginator,
        CategoryService $categoryService,
        UserService $userService,
        SchoolService $schoolService,
        TengxunyunVodService $tengxunyunVodService,
        AliyunVodService $aliyunVodService
    ) {
        $this->paginator = $paginator;
        $this->categoryService = $categoryService;
        $this->userService = $userService;
        $this->schoolService = $schoolService;
        $this->TengxunyunVodService = $tengxunyunVodService;
        $this->AliyunVodService = $aliyunVodService;
    }

    public function getList($request, $page, $pageSize)
    {
        $sql = $this->getFormatRequestSql($request);
        $dql = "SELECT a FROM App:TeachCourse a " . $sql . " ORDER BY a.id DESC";
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
                $firstCateGoryId = $vArr['firstCategoryId'];
                $cateGoryId = $vArr['categoryId'];
                $schooleId = $vArr['schoolId'];
                $createrUid = $vArr['createUid'];
                $firstCateGory = $this->categoryService->getById($firstCateGoryId);
                $vArr['brand'] = $firstCateGory['name'];
                $bigImg = $vArr['bigImg'];
                $bigImgArr = $bigImg ? current(json_decode($bigImg, true)) : "";
                $vArr['bigImg'] = $bigImgArr;
                $cateGroy = $this->categoryService->getById($cateGoryId);
                $vArr['category'] = $cateGroy['name'];
                $createrUser = $this->userService->getById($createrUid);
                $vArr['creater'] = $createrUser['fullName'];
                $vArr['courseHourView'] = $vArr['courseHour'] / 100;
                $vArr['school'] = "";
                if ($schooleId) {
                    $school = $this->schoolService->getById($schooleId);
                    $vArr['school'] = $school['name'];
                }
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

    public function getSchools()
    {
        $schools = $this->schoolService->getAll();
        if (!$schools) return [];
        $result = [];
        foreach ($schools as $v) {
            $result[$v['name']] = $v['id'];
        }
        return $result;
    }

    public function add($uid, $name, $type, $bigImg, $descr, $categoryId, $schoolId, $courseHour, $status = 0)
    {
        $cate = $this->categoryService->getById($categoryId);
        $path = trim($cate['findPath'], ',');
        $pathArr = explode(",", $path);
        $brandId = end($pathArr);
        $model = new TeachCourse();
        $model->setName($name);
        $model->setDescr($descr);
        $model->setType($type);
        if (!$bigImg) {
            $bigImg = json_encode([trim($this->getOption("app.domain"),"/")."/assets/images/course.jpg"]);
        }
        $model->setBigImg($bigImg);
        $model->setFirstCategoryId($brandId);
        $model->setStatus($status);
        $model->setCategoryId($categoryId);
        $model->setSchoolId($schoolId);
        $model->setCourseHour($courseHour * 100);
        $model->setCreateUid($uid);
        $courseId =  $this->save($model);
        return $courseId;
    }


    public function getById($id)
    {
        $sql = "SELECT a FROM App:TeachCourse a WHERE a.id=:id";
        return $this->fetchOne($sql, ['id' => $id]);
    }

    public function getByName($name, $id = 0)
    {
        $sql = "SELECT a FROM App:TeachCourse a WHERE a.name=:name";
        $params = [];
        $params['name'] = $name;
        if ($id) {
            $sql = $sql . " AND a.id !=:id ";
            $params['id'] = $id;
        }
        return $this->fetchOne($sql, $params);
    }

    public function edit($id, $name, $type, $bigImg, $descr, $categoryId, $schoolId, $courseHour)
    {
        $cate = $this->categoryService->getById($categoryId);
        $path = trim($cate['findPath'], ',');
        $pathArr = explode(",", $path);
        $brandId = end($pathArr);
        $courceSql = "SELECT a FROM App:TeachCourse a WHERE a.id=:id";
        $model = $this->fetchOne($courceSql, ['id' => $id], 1);
        $model->setName($name);
        $model->setDescr($descr);
        $model->setType($type);
        if (!$bigImg) {
            $bigImg = json_encode([trim($this->getOption("app.domain"),"/")."/assets/images/course.jpg"]);
        }
        $model->setBigImg($bigImg);
        $model->setFirstCategoryId($brandId);
        $model->setCategoryId($categoryId);
        $model->setSchoolId($schoolId);
        $model->setCourseHour($courseHour * 100);
        return $this->save($model);
    }

    public function del($id)
    {
        $sql = "SELECT a FROM App:TeachCourse a WHERE a.id=:id";
        $model = $this->fetchOne($sql, ['id' => $id], 1);
        return $this->delete($model);
    }

    public function switchStatus($id, $state)
    {
        $sql = "SELECT a FROM App:TeachCourse a WHERE a.id=:id";
        $model = $this->fetchOne($sql, ['id' => $id], 1);
        $model->setStatus($state);
        return $this->save($model);
    }

    public function hasChapter($id)
    {
        $sql = "SELECT a FROM App:TeachCourseChapter a WHERE a.courseId=:courseId";
        return $this->fetchOne($sql, ["courseId" => $id]);
    }

    public function getByIds($ids)
    {
        $sql = "SELECT a FROM App:TeachCourse a where a.id IN(:id) ";
        $params = [];
        $params['id'] = $ids;
        return $this->fetchAll($sql, $params);
    }

    public function getSelectByIds($ids){
        $sql = "SELECT a FROM App:TeachCourse a where a.id IN(:id) ";
        $params = [];
        $params['id'] = $ids;
        $list = $this->fetchAll($sql, $params);
        if(!$list) return [];
        $tmp = [];
        foreach ($list as $v) {
            $tmp[$v["name"]] = $v["id"];
        }
        return $tmp;
    }

    public function searchName($name)
    {
        $sql = "SELECT a FROM App:TeachCourse a where a.name like :name AND a.status=1 ORDER BY a.id DESC";
        $params = [];
        $params['name'] = "%" . $name . "%";
        return $this->fetchAll($sql, $params);
    }
}
