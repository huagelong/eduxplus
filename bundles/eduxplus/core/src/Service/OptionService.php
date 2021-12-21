<?php

/**
 * @Author: kaihui.wang
 * @Contact  hpuwang@gmail.com
 * @Version: 1.0.0
 * @Date: 2020/4/10 10:57
 */

namespace Eduxplus\CoreBundle\Service;


use Eduxplus\CoreBundle\Lib\Base\BaseService;
use Eduxplus\CoreBundle\Entity\BaseOption;
use Knp\Component\Pager\PaginatorInterface;
use Eduxplus\CoreBundle\Lib\Base\AdminBaseService;

class OptionService extends AdminBaseService
{
    protected $paginator;

    public function __construct(PaginatorInterface $paginator)
    {
        $this->paginator = $paginator;
    }


    public function getOptionGroup(){
        $sql = "SELECT distinct a.optionGroup FROM Core:BaseOption a GROUP BY a.optionGroup ";
        $groups = $this->fetchFields("optionGroup", $sql);
        return $groups;
    }

    public function optionList($request, $page, $pageSize)
    {
        $sql = $this->getFormatRequestSql($request);
        $dql = "SELECT a FROM Core:BaseOption a " . $sql  . " ORDER BY a.id ASC";
        $em = $this->getDoctrine()->getManager();
        $query = $em->createQuery($dql);
        $pagination = $this->paginator->paginate(
            $query,
            $page,
            $pageSize
        );

        return $pagination;
    }

    public function getAllOptionGroup(){
        $result = [];
        $result["全部"]="";
        $groups = $this->getOptionGroup();
        foreach ($groups as $v){
            $result[$v] = $v;
        }
        return $result;
    }

    public function checkOptionKey($key, $id = 0)
    {
        $sql = "SELECT a FROM Core:BaseOption a where a.optionKey =:optionKey ";
        $params = [];
        $params['optionKey'] = $key;
        if ($id) {
            $sql = $sql . " AND a.id !=:id ";
            $params['id'] = $id;
        }
        return $this->fetchOne($sql, $params);
    }

    public function getById($id)
    {
        $sql = "SELECT a FROM Core:BaseOption a where a.id =:id ";
        return $this->fetchOne($sql, ["id" => $id]);
    }

    public function add($type, $optionKey, $optionValue, $descr, $isLock, $optionGroup)
    {
        $model = new BaseOption();
        $model->setDescr($descr);
        $model->setType($type);
        $model->setOptionKey($optionKey);
        $model->setOptionValue($optionValue);
        $model->setIsLock($isLock);
        $model->setOptionGroup($optionGroup);
        return $this->save($model);
    }

    public function edit($id, $optionKey, $optionValue, $descr, $isLock)
    {
        $sql = "SELECT a FROM Core:BaseOption a where a.id =:id ";
        $model =  $this->fetchOne($sql, ["id" => $id], 1);
        $isLock = $model->getIsLock();
        $model->setDescr($descr);
        if (!$isLock) $model->setOptionKey($optionKey);
        $model->setOptionValue($optionValue);
        if (!$isLock) $model->setIsLock($isLock);
        return $this->save($model);
    }

    public function deleteOption($id)
    {
        $sql = "SELECT a FROM Core:BaseOption a where a.id =:id ";
        $model =  $this->fetchOne($sql, ["id" => $id], 1);
        $this->hardDelete($model);
        return true;
    }
}
