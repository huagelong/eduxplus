<?php
/**
 * @Author: kaihui.wang
 * @Contact  hpuwang@gmail.com
 * @Version: 1.0.0
 * @Date: 2020/4/13 10:01
 */

namespace App\Bundle\AdminBundle\Service\Teach;


use App\Bundle\AppBundle\Lib\Base\BaseService;
use App\Entity\TeachBrand;
use Knp\Component\Pager\PaginatorInterface;

class BrandService extends BaseService
{

    protected $paginator;

    public function __construct(PaginatorInterface $paginator)
    {
        $this->paginator = $paginator;
    }


    public function getList($request, $page, $pageSize){
        $sql = $this->getFormatRequestSql($request);
        $dql = "SELECT a FROM App:TeachBrand a " . $sql;
        $em = $this->getDoctrine()->getManager();
        $query = $em->createQuery($dql);
        $pagination = $this->paginator->paginate(
            $query,
            $page,
            $pageSize
        );
        return $pagination;
    }

    public function add($name, $sort, $isShow){
        $model = new TeachBrand();
        $model->setName($name);
        $model->setIsShow($isShow);
        $model->setSort($sort);
        return $this->save($model);
    }

    public function getById($id){
        $sql = "SELECT a FROM App:TeachBrand a WHERE a.id=:id";
        return $this->fetchOne($sql, ['id'=>$id]);
    }

    public function getByName($name, $id=0){
        $sql = "SELECT a FROM App:TeachBrand a WHERE a.name=:name";
        $params = [];
        $params['name'] = $name;
        if($id){
            $sql = $sql." AND a.id !=:id ";
            $params['id'] = $id;
        }
        return $this->fetchOne($sql, $params);
    }

    public function edit($id, $name, $sort, $isShow){
        $sql = "SELECT a FROM App:TeachBrand a WHERE a.id=:id";
        $model = $this->fetchOne($sql, ['id'=>$id] ,1 );
        $model->setName($name);
        $model->setIsShow($isShow);
        $model->setSort($sort);
        return $this->save($model);
    }

    public function del($id){
        $sql = "SELECT a FROM App:TeachBrand a WHERE a.id=:id";
        $model = $this->fetchOne($sql, ['id'=>$id] ,1 );
        return $this->delete($model);
    }

}
