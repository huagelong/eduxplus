<?php


namespace Eduxplus\CoreBundle\Service;


use Eduxplus\CoreBundle\Entity\BaseDictType;
use Eduxplus\CoreBundle\Lib\Base\AdminBaseService;
use Knp\Component\Pager\PaginatorInterface;

class DictTypeService extends AdminBaseService
{
    protected $paginator;

    public function __construct(PaginatorInterface $paginator)
    {
        $this->paginator = $paginator;
    }

    public function dictTypeList($request, $page, $pageSize)
    {
        $sql = $this->getFormatRequestSql($request);
        $dql = "SELECT a FROM Core:BaseDictType a " . $sql  . " ORDER BY a.id ASC";
        $em = $this->getDoctrine()->getManager();
        $query = $em->createQuery($dql);
        $pagination = $this->paginator->paginate(
            $query,
            $page,
            $pageSize
        );

        return $pagination;
    }

    public function getById($id)
    {
        $sql = "SELECT a FROM Core:BaseDictType a where a.id =:id ";
        return $this->db()->fetchOne($sql, ["id" => $id]);
    }

    public function add($dictName, $dictKey, $status, $descr){
        $model = new BaseDictType();
        $model->setDictName($dictName);
        $model->setDictKey($dictKey);
        $model->setStatus($status);
        $model->setDescr($descr);
        return $this->db()->save($model);
    }

    public function edit($id, $dictName, $dictKey, $status, $descr){
        $sql = "SELECT a FROM Core:BaseDictType a where a.id =:id ";
        $model =  $this->db()->fetchOne($sql, ["id" => $id], 1);
        $model->setDictName($dictName);
        $model->setDictKey($dictKey);
        $model->setStatus($status);
        $model->setDescr($descr);
        return $this->db()->save($model);
    }

    public function del($id){
        $sql = "SELECT a FROM Core:BaseDictType a where a.id =:id ";
        $model =  $this->db()->fetchOne($sql, ["id" => $id], 1);
        $this->db()->hardDelete($model);
        return true;
    }

    public function switchStatus($id, $state)
    {
        $sql = "SELECT a FROM Core:BaseDictType a WHERE a.id=:id";
        $model = $this->db()->fetchOne($sql, ['id' => $id], 1);
        $model->setStatus($state);
        return $this->db()->save($model);
    }
}
