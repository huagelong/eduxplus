<?php

/**
 * @Author: kaihui.wang
 * @Contact  hpuwang@gmail.com
 * @Version: 1.0.0
 * @Date: 2020/5/26 09:57
 */

namespace App\Bundle\AppBundle\Service;


use Eduxplus\CoreBundle\Lib\Base\AppBaseService;

class CategoryService extends AppBaseService
{

    public function getHomeCategory()
    {
        $sql = "SELECT a FROM Core:TeachCategory a WHERE a.parentId=0 AND a.isShow=1 ORDER BY a.sort ASC";
        $homeCateGory = $this->fetchAll($sql);
        if($homeCateGory){
            foreach ($homeCateGory as &$v){
                $id = $v['id'];
                $subs = $this->getSubsCategory($id);
                $v['subs'] = $subs;
            }
        }
        return $homeCateGory;
    }

    public function getBrands()
    {
        $sql = "SELECT a FROM Core:TeachCategory a WHERE a.parentId=0 AND a.isShow=1 ORDER BY a.sort ASC";
        return $this->fetchAll($sql);
    }

    public function getCategory($id)
    {
        $sql = "SELECT a FROM Core:TeachCategory a WHERE a.id=:id AND a.isShow=1";
        return $this->fetchOne($sql, ["id" => $id]);
    }

    public function getSubsCategory($id)
    {
        if (!$id){
            $sql = "SELECT a FROM Core:TeachCategory a WHERE a.parentId > 0 AND a.isShow=1 ORDER BY a.sort ASC";
            return $this->fetchAll($sql);
        }
        $sql = "SELECT a FROM Core:TeachCategory a WHERE a.findPath like :findPath AND a.isShow=1 ORDER BY a.sort ASC";
        return $this->fetchAll($sql, ["findPath" => '%,'.$id.',%']);
    }

    public function getSubCategory($id)
    {
        if (!$id) return [];
        $sql = "SELECT a FROM Core:TeachCategory a WHERE a.parentId=:parentId AND a.isShow=1 ORDER BY a.sort ASC";
        return $this->fetchAll($sql, ["parentId" => $id]);
    }

}
