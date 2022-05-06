<?php


namespace Eduxplus\CoreBundle\Service;


use Eduxplus\CoreBundle\Entity\BaseDictData;
use Eduxplus\CoreBundle\Entity\BaseDictType;
use Eduxplus\CoreBundle\Lib\Base\AdminBaseService;
use Knp\Component\Pager\PaginatorInterface;

class DictDataService extends AdminBaseService
{
    protected $paginator;

    public function __construct(PaginatorInterface $paginator)
    {
        $this->paginator = $paginator;
    }

    public function dictDataList($request, $page, $pageSize, $dictTypeId)
    {
        $sql = $this->getFormatRequestSql($request);
        if(!$sql){
            $sql = " WHERE a.dictTypeId =:dictTypeId ";
        }else{
            $sql .= " AND a.dictTypeId =:dictTypeId ";
        }
        $dql = "SELECT a FROM Core:BaseDictData a " . $sql  . " ORDER BY a.id ASC";
        $em = $this->getDoctrine()->getManager();
        $query = $em->createQuery($dql);
        if($dictTypeId) $query = $query->setParameters(["dictTypeId"=>$dictTypeId]);
        $pagination = $this->paginator->paginate(
            $query,
            $page,
            $pageSize
        );

        return $pagination;
    }

    public function getById($id)
    {
        $sql = "SELECT a FROM Core:BaseDictData a where a.id =:id ";
        return $this->db()->fetchOne($sql, ["id" => $id]);
    }


    public function getByTypeId($typeId)
    {
        $sql = "SELECT a FROM Core:BaseDictData a where a.dictTypeId =:dictTypeId ";
        return $this->db()->fetchAll($sql, ["dictTypeId" => $typeId]);
    }


    public function add($typeId,$dictLabel, $dictValue, $fsort, $status, $descr){
        $model = new BaseDictData();
        $model->setDictTypeId($typeId);
        $model->setDictLabel($dictLabel);
        $model->setDictValue($dictValue);
        $model->setStatus($status);
        $model->setFsort($fsort);
        $model->setDescr($descr);
        return $this->db()->save($model);
    }

    public function edit($id, $dictLabel, $dictValue, $fsort, $status, $descr){
        $sql = "SELECT a FROM Core:BaseDictData a where a.id =:id ";
        $model =  $this->db()->fetchOne($sql, ["id" => $id], 1);
        $model->setDictLabel($dictLabel);
        $model->setDictValue($dictValue);
        $model->setStatus($status);
        $model->setFsort($fsort);
        $model->setDescr($descr);
        return $this->db()->save($model);
    }

    public function del($id){
        $sql = "SELECT a FROM Core:BaseDictData a where a.id =:id ";
        $model =  $this->db()->fetchOne($sql, ["id" => $id], 1);
        $this->db()->hardDelete($model);
        return true;
    }

    public function switchStatus($id, $state)
    {
        $sql = "SELECT a FROM Core:BaseDictData a WHERE a.id=:id";
        $model = $this->db()->fetchOne($sql, ['id' => $id], 1);
        $model->setStatus($state);
        return $this->db()->save($model);
    }
}
