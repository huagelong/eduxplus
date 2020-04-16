<?php
/**
 * @Author: kaihui.wang
 * @Contact  hpuwang@gmail.com
 * @Version: 1.0.0
 * @Date: 2020/4/13 21:52
 */

namespace App\Bundle\AdminBundle\Service\Jw;


use App\Bundle\AppBundle\Lib\Base\BaseService;
use App\Entity\JwSchool;
use Knp\Component\Pager\PaginatorInterface;

class SchoolService extends BaseService
{
    protected $paginator;

    public function __construct(PaginatorInterface $paginator)
    {
        $this->paginator = $paginator;
    }

    public function getList($request, $page, $pageSize){
        $sql = $this->getFormatRequestSql($request);
        $dql = "SELECT a FROM App:JwSchool a " . $sql;
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
                $vArr['stateCity'] = $vArr['state']."-".$vArr['city']."-".$vArr['region'];
                $itemsArr[] = $vArr;
            }
        }
        return [$pagination, $itemsArr];
    }

    public function getAll(){
        $dql = "SELECT a FROM App:JwSchool a ";
        return $this->fetchAll($dql);
    }

    public function add($name, $descr, $address, $linkin, $state, $city, $region){
        $model = new JwSchool();
        $model->setName($name);
        if($descr) $model->setDescr($descr);
        $model->setAddress($address);
        $model->setLinkin($linkin);
        $model->setState($state);
        $model->setCity($city);
        $model->setRegion($region);
        return $this->save($model);
    }

    public function getById($id){
        $sql = "SELECT a FROM App:JwSchool a WHERE a.id=:id";
        return $this->fetchOne($sql, ['id'=>$id]);
    }

    public function getByName($name, $id=0){
        $sql = "SELECT a FROM App:JwSchool a WHERE a.name=:name";
        $params = [];
        $params['name'] = $name;
        if($id){
            $sql = $sql." AND a.id !=:id ";
            $params['id'] = $id;
        }
        return $this->fetchOne($sql, $params);
    }

    public function edit($id, $name, $descr, $address, $linkin, $state, $city, $region){
        $sql = "SELECT a FROM App:JwSchool a WHERE a.id=:id";
        $model = $this->fetchOne($sql, ['id'=>$id] ,1 );
        $model->setName($name);
        $model->setDescr($descr);
        $model->setAddress($address);
        $model->setLinkin($linkin);
        $model->setState($state);
        $model->setCity($city);
        $model->setRegion($region);
        return $this->save($model);
    }

    public function del($id){
        $sql = "SELECT a FROM App:JwSchool a WHERE a.id=:id";
        $model = $this->fetchOne($sql, ['id'=>$id] ,1 );
        return $this->delete($model);
    }

}
