<?php

/**
 * @Author: kaihui.wang
 * @Contact  hpuwang@gmail.com
 * @Version: 1.0.0
 * @Date: 2020/4/13 21:52
 */

namespace Eduxplus\EduxBundle\Service\Jw;


use Eduxplus\CoreBundle\Lib\Base\AdminBaseService;
use Eduxplus\CoreBundle\Lib\Base\BaseService;
use Eduxplus\EduxBundle\Entity\JwTeacher;
use Knp\Component\Pager\PaginatorInterface;
use Overtrue\Pinyin\Pinyin;

class TeacherService extends AdminBaseService
{
    protected $paginator;
    protected $schoolService;

    public function __construct(PaginatorInterface $paginator, SchoolService $schoolService)
    {
        $this->paginator = $paginator;
        $this->schoolService = $schoolService;
    }

    public function getList($request, $page, $pageSize)
    {
        $sql = $this->getFormatRequestSql($request);
        $dql = "SELECT a FROM Edux:JwTeacher a " . $sql . " ORDER BY a.id DESC";
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
                $schoolArr = $this->schoolService->getById($vArr['schoolId']);
                $vArr['school'] = $schoolArr['name'];
                $itemsArr[] = $vArr;
            }
        }
        return [$pagination, $itemsArr];
    }

    public function schoolSelect()
    {
        $all = $this->schoolService->getAll();
        if (!$all) return [];
        $rs = [];
        foreach ($all as $v) {
            $rs[$v['name']] = $v['id'];
        }

        return $rs;
    }

    public function getAll()
    {
        $dql = "SELECT a FROM Edux:JwTeacher a ";
        return $this->db()->fetchAll($dql);
    }

    public function add($name, $descr, $type, $schoolId, $status, $gravatar = '')
    {
        $pinyin = new Pinyin();
        $pinyinArr = $pinyin->convert($name);
        $pinyinStr = implode('', $pinyinArr);

        $model = new JwTeacher();
        $model->setName($name);
        if ($descr) $model->setDescr($descr);
        $model->setType($type);
        $model->setPinyin($pinyinStr);
        $model->setSchoolId($schoolId);
        $model->setStatus($status);
        if ($gravatar) $model->setGravatar($gravatar);
        return $this->db()->save($model);
    }

    public function getById($id)
    {
        $sql = "SELECT a FROM Edux:JwTeacher a WHERE a.id=:id";
        return $this->db()->fetchOne($sql, ['id' => $id]);
    }

    public function getByName($name, $id = 0)
    {
        $sql = "SELECT a FROM Edux:JwTeacher a WHERE a.name=:name";
        $params = [];
        $params['name'] = $name;
        if ($id) {
            $sql = $sql . " AND a.id !=:id ";
            $params['id'] = $id;
        }
        return $this->db()->fetchOne($sql, $params);
    }

    public function edit($id, $name, $descr, $type, $schoolId, $status, $gravatar = '')
    {
        $pinyin = new Pinyin();
        $pinyinArr = $pinyin->convert($name);
        $pinyinStr = implode('', $pinyinArr);

        $sql = "SELECT a FROM Edux:JwTeacher a WHERE a.id=:id";
        $model = $this->db()->fetchOne($sql, ['id' => $id], 1);
        $model->setName($name);
        if ($descr) $model->setDescr($descr);
        $model->setType($type);
        $model->setPinyin($pinyinStr);
        $model->setSchoolId($schoolId);
        $model->setStatus($status);
        if ($gravatar) $model->setGravatar($gravatar);
        return $this->db()->save($model);
    }

    public function del($id)
    {
        $sql = "SELECT a FROM Edux:JwTeacher a WHERE a.id=:id";
        $model = $this->db()->fetchOne($sql, ['id' => $id], 1);
        return $this->db()->delete($model);
    }

    public function switchStatus($id, $state)
    {
        $sql = "SELECT a FROM Edux:JwTeacher a WHERE a.id=:id";
        $model = $this->db()->fetchOne($sql, ['id' => $id], 1);
        $model->setStatus($state);
        return $this->db()->save($model);
    }
}
