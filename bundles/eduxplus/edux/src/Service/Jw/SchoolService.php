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
use Eduxplus\EduxBundle\Entity\JwSchool;
use Knp\Component\Pager\PaginatorInterface;

class SchoolService extends AdminBaseService
{
    protected $paginator;

    public function __construct(PaginatorInterface $paginator)
    {
        $this->paginator = $paginator;
    }

    public function getList($request, $page, $pageSize)
    {
        $sql = $this->getFormatRequestSql($request);
        $dql = "SELECT a FROM Edux:JwSchool a " . $sql . " ORDER BY a.id DESC";
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
                $vArr['stateCity'] = $vArr['state'] . "-" . $vArr['city'] . "-" . $vArr['region'];
                $itemsArr[] = $vArr;
            }
        }
        return [$pagination, $itemsArr];
    }

    public function getAll()
    {
        $dql = "SELECT a FROM Edux:JwSchool a ";
        return $this->db()->fetchAll($dql);
    }

    public function add($name, $descr, $address, $linkin, $state, $city, $region)
    {
        $model = new JwSchool();
        $model->setName($name);
        if ($descr) $model->setDescr($descr);
        $model->setAddress($address);
        $model->setLinkin($linkin);
        $model->setState($state);
        $model->setCity($city);
        $model->setRegion($region);
        return $this->db()->save($model);
    }

    public function getById($id)
    {
        $sql = "SELECT a FROM Edux:JwSchool a WHERE a.id=:id";
        return $this->db()->fetchOne($sql, ['id' => $id]);
    }

    public function getByName($name, $id = 0)
    {
        $sql = "SELECT a FROM Edux:JwSchool a WHERE a.name=:name";
        $params = [];
        $params['name'] = $name;
        if ($id) {
            $sql = $sql . " AND a.id !=:id ";
            $params['id'] = $id;
        }
        return $this->db()->fetchOne($sql, $params);
    }

    public function edit($id, $name, $descr, $address, $linkin, $state, $city, $region)
    {
        $sql = "SELECT a FROM Edux:JwSchool a WHERE a.id=:id";
        $model = $this->db()->fetchOne($sql, ['id' => $id], 1);
        $model->setName($name);
        $model->setDescr($descr);
        $model->setAddress($address);
        $model->setLinkin($linkin);
        $model->setState($state);
        $model->setCity($city);
        $model->setRegion($region);
        return $this->db()->save($model);
    }

    public function del($id)
    {
        $sql = "SELECT a FROM Edux:JwSchool a WHERE a.id=:id";
        $model = $this->db()->fetchOne($sql, ['id' => $id], 1);
        return $this->db()->delete($model);
    }
}
