<?php
/**
 * @Author: kaihui.wang
 * @Contact  hpuwang@gmail.com
 * @Version: 1.0.0
 * @Date: 2020/4/19 20:19
 */

namespace App\Bundle\AdminBundle\Service\Teach;


use App\Bundle\AppBundle\Lib\Base\BaseService;

class ChapterService extends BaseService
{


    public function getChapterTree($parentId)
    {
        $sql = "SELECT a FROM App:TeachCourseChapter a WHERE a.parentId = :parentId ORDER BY a.sort ASC";
        $items = $this->fetchAll($sql, ['parentId'=>$parentId]);
        if(!$items) return [];
        $result = [];
        foreach ($items as &$v){
            $tmp=$v;
            $child =  $this->getChapterTree($v['id']);
            $tmp['childs'] = $child;
            $result[] = $tmp;
        }
        return $result;
    }


    public function updateSort($data, $pid=0){
        if($data){
            $sort = 0;
            foreach ($data as $k=>$v){
                $id = $v['id'];
                $sql = "SELECT a FROM App:TeachCourseChapter a WHERE a.id=:id";
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

    public function getAllChapter(){
        $sql = "SELECT a FROM App:TeachCourseChapter a ORDER BY a.sort ASC";
        $list = $this->fetchAll($sql);
        if(!$list) return [];

        $rs = [];
        foreach ($list as $v){
            $pid = $v['parentId'];
            $rs[$pid][] = $v;
        }
        return $rs;
    }

    public function chapterSelect(){
        $all = $this->getAllChapter();
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
                    $this->_Select($all[$id], $id, $pre, $rs);
                }
            }
        }
    }

}
