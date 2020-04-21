<?php
/**
 * @Author: kaihui.wang
 * @Contact  hpuwang@gmail.com
 * @Version: 1.0.0
 * @Date: 2020/4/13 10:01
 */

namespace App\Bundle\AdminBundle\Service\Teach;


use App\Bundle\AppBundle\Lib\Base\BaseService;
use App\Entity\TeachCategory;

class CategoryService extends BaseService
{


    public function getCategoryTree($parentId)
    {
        $sql = "SELECT a FROM App:TeachCategory a WHERE a.parentId = :parentId ORDER BY a.sort ASC";
        $items = $this->fetchAll($sql, ['parentId'=>$parentId]);
        if(!$items) return [];
        $result = [];
        foreach ($items as &$v){
            $tmp=$v;
            $child =  $this->getCategoryTree($v['id']);
            $tmp['childs'] = $child;
            $result[] = $tmp;
        }
        return $result;
    }

    public function findPath($id){
        $sql = "SELECT a.parentId FROM App:TeachCategory a WHERE a.id = :id";
        $pid = $this->fetchField("parentId", $sql, ['id'=>$id]);
        if(!$pid) return "";
        $str = ",{$pid},";
        $str .= $this->findPath($id);
        return $str;
    }

    public function hasChild($id){
        $sql = "SELECT a FROM App:TeachCategory a WHERE a.parentId=:parentId";
        return $this->fetchOne($sql, ['parentId'=>$id]);
    }


    public function add($name, $parentId, $sort, $isShow){
        $model = new TeachCategory();
        $findPath = $this->findPath($parentId);
        $model->setName($name);
        $model->setFindPath($findPath);
        $model->setParentId($parentId);
        $model->setIsShow($isShow);
        $model->setSort($sort);
        return $this->save($model);
    }


    public function getById($id){
        $sql = "SELECT a FROM App:TeachCategory a WHERE a.id=:id";
        return $this->fetchOne($sql, ['id'=>$id]);
    }

    public function edit($id, $parentId, $name, $sort, $isShow){
        $sql = "SELECT a FROM App:TeachCategory a WHERE a.id=:id";
        $findPath = $this->findPath($parentId);
        $model = $this->fetchOne($sql, ['id'=>$id] ,1 );
        $model->setName($name);
        $model->setFindPath($findPath);
        $model->setParentId($parentId);
        $model->setIsShow($isShow);
        $model->setSort($sort);
        return $this->save($model);
    }

    public function del($id){
        $sql = "SELECT a FROM App:TeachCategory a WHERE a.id=:id";
        $model = $this->fetchOne($sql, ['id'=>$id] ,1 );
        return $this->delete($model);
    }

    public function updateSort($data, $pid=0){
        if($data){
            $sort = 0;
            foreach ($data as $k=>$v){
                $id = $v['id'];
                $sql = "SELECT a FROM App:TeachCategory a WHERE a.id=:id";
                $model = $this->fetchOne($sql, ['id'=>$id], 1);
                $model->setSort($sort);
                $model->setParentId($pid);
                $this->update($model);
                if(isset($v['children'])){
                    $this->updateSort($v['children'], $id);
                }
                $sort++;
            }
        }
    }

    public function getAllCategory(){
        $sql = "SELECT a FROM App:TeachCategory a ORDER BY a.sort ASC";
        $list = $this->fetchAll($sql);
        if(!$list) return [];

        $rs = [];
        foreach ($list as $v){
            $pid = $v['parentId'];
            $rs[$pid][] = $v;
        }
        return $rs;
    }

    public function categorySelect(){
        $all = $this->getAllCategory();
        $rs = [];
        $rs['root'] = 0;
        if($all){
            foreach ($all[0] as $vv){
                $id= $vv['id'];
                $name = "┝&nbsp;".$vv['name'];
                $rs[$name] = $id;
                if(isset($all[$id])){
                    $pre = "&nbsp;&nbsp;&nbsp;&nbsp;";
                    $this->_Select($all,$id, $pre, $rs);
                }
            }
        }
        return $rs;
    }

    protected function _Select($all, $id, $pre="", &$rs){
        if($all[$id]){
            foreach ($all[$id] as $v){
                $id= $v['id'];
                $name = $pre."┝&nbsp;".$v['name'];
                $rs[$name] = $id;
                if(isset($all[$id])){
                    $pre = $pre."&nbsp;&nbsp;&nbsp;&nbsp;";
                    $this->_Select($all, $id, $pre, $rs);
                }
            }
        }
    }


}
