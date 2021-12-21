<?php
/**
 * @Author: kaihui.wang
 * @Contact  hpuwang@gmail.com
 * @Version: 1.0.0
 * @Date: 2020/11/2 16:08
 */

namespace App\Bundle\AppBundle\Service;


use Eduxplus\CoreBundle\Lib\Base\AppBaseService;

class HelpService extends AppBaseService
{

    public function getCategoryAndHelp($parentId=0){
        $sql = "SELECT a FROM Core:MallHelpCategory a WHERE a.parentId =:parentId AND a.isShow=1  ORDER BY a.sort ASC";
        $list = $this->fetchAll($sql, ["parentId"=>$parentId]);
        if(!$list) return $list;
        $result = [];
        foreach ($list as &$v) {
            $tmp = $v;
            $child =  $this->getCategoryAndHelp($v['id']);
            $tmp['help'] = [];
            //如果没有子分类
            if(($parentId == 0) && (!$child)){
                $sql = "SELECT a FROM Core:MallHelp a WHERE a.categoryId =:categoryId AND a.status=1 ORDER BY a.createdAt ASC";
                $tmp['help'] = $this->fetchAll($sql, ["categoryId"=>$v['id']]);
            }
            $tmp['childs'] = $child;
            $result[] = $tmp;
        }
        return $result;
    }

    public function getById($id){
        $sql = "SELECT a FROM Core:MallHelp a WHERE a.id=:id AND a.status=1";
        $detail = $this->fetchOne($sql, ["id"=>$id]);
        if(!$detail) return $detail;
        $mainSql = "SELECT a FROM Core:MallHelpMain a WHERE a.helpId =:helpId ";
        $mainDetail = $this->fetchOne($mainSql, ["helpId"=>$id]);
        $detail['main'] = $mainDetail;
        return $detail;
    }

    public function getByCategoryId($categoryId){
        $sql = "SELECT a FROM Core:MallHelp a WHERE a.categoryId =:categoryId AND a.status=1 ORDER BY a.createdAt ASC";
        $helps = $this->fetchAll($sql, ["categoryId"=>$categoryId]);
        if(!$helps) return $helps;
        foreach ($helps as &$v){
            $id = $v['id'];
            $mainSql = "SELECT a FROM Core:MallHelpMain a WHERE a.helpId =:helpId ";
            $mainDetail = $this->fetchOne($mainSql, ["helpId"=>$id]);
            $v['main'] = $mainDetail;
        }
        return $helps;
    }

    public function getCategoryById($id){
        $sql = "SELECT a FROM Core:MallHelpCategory a WHERE a.id =:id AND a.isShow=1 ";
        $list = $this->fetchOne($sql, ["id"=>$id]);
        return $list;
    }

    public function getAllTopValueHelps(){
        $sql = "SELECT a FROM Core:MallHelp a WHERE a.topValue >0 AND a.status=1 ORDER BY a.sort ASC";
        $helps = $this->fetchAll($sql);
        return $helps;
    }

}
