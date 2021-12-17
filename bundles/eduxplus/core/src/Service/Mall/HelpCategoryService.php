<?php
/**
 * @Author: kaihui.wang
 * @Contact  hpuwang@gmail.com
 * @Version: 1.0.0
 * @Date: 2020/10/9 10:51
 */

namespace Eduxplus\CoreBundle\Service\Mall;


use Eduxplus\CoreBundle\Lib\Base\AdminBaseService;
use Eduxplus\CoreBundle\Entity\MallHelpCategory;

class HelpCategoryService extends AdminBaseService
{
    public function getCategoryTree($parentId)
    {
        $sql = "SELECT a FROM App:MallHelpCategory a WHERE a.parentId = :parentId ORDER BY a.sort ASC";
        $items = $this->fetchAll($sql, ['parentId' => $parentId]);
        if (!$items) return [];
        $result = [];
        foreach ($items as &$v) {
            $tmp = $v;
            $child =  $this->getCategoryTree($v['id']);
            $tmp['childs'] = $child;
            $result[] = $tmp;
        }
        return $result;
    }

    public function checkDeposit($id)
    {
        $str = $this->findPath($id);
        if (!$str) return 1;
        $path = trim($str, ",");
        if(!$path) return 1;
        $pathArr = explode(",", $path);
        return count($pathArr) + 1;
    }

    public function findPath($id)
    {
        if (!$id) return "";
        $sql = "SELECT a.parentId FROM App:MallHelpCategory a WHERE a.id = :id";
        $pid = $this->fetchField("parentId", $sql, ['id' => $id]);
        if (!$pid) return ",{$id},";
        $str = ",{$id},";
        $str .= ltrim($this->findPath($pid), ",");
        return $str;
    }

    public function hasChild($id)
    {
        $sql = "SELECT a FROM App:MallHelpCategory a WHERE a.parentId=:parentId";
        return $this->fetchOne($sql, ['parentId' => $id]);
    }


    public function add($name, $parentId, $sort, $isShow)
    {
        $model = new MallHelpCategory();
        $findPath = $this->findPath($parentId);
        $model->setName($name);
        $model->setFindPath($findPath);
        $model->setParentId($parentId);
        $model->setIsShow($isShow);
        $model->setSort($sort);
        return $this->save($model);
    }


    public function getById($id)
    {
        $sql = "SELECT a FROM App:MallHelpCategory a WHERE a.id=:id";
        return $this->fetchOne($sql, ['id' => $id]);
    }

    public function edit($id, $parentId, $name, $sort, $isShow)
    {
        $sql = "SELECT a FROM App:MallHelpCategory a WHERE a.id=:id";
        $findPath = $this->findPath($parentId);
        $model = $this->fetchOne($sql, ['id' => $id], 1);
        $model->setName($name);
        $model->setFindPath($findPath);
        $model->setParentId($parentId);
        $model->setIsShow($isShow);
        $model->setSort($sort);
        return $this->save($model);
    }

    public function del($id)
    {
        $sql = "SELECT a FROM App:MallHelpCategory a WHERE a.id=:id";
        $model = $this->fetchOne($sql, ['id' => $id], 1);
        return $this->delete($model);
    }

    public function updateSort($data, $pid = 0)
    {
        if ($data) {
            $sort = 0;
            foreach ($data as $k => $v) {
                $id = $v['id'];
                $sql = "SELECT a FROM App:MallHelpCategory a WHERE a.id=:id";
                $model = $this->fetchOne($sql, ['id' => $id], 1);
                $model->setSort($sort);
                $model->setParentId($pid);
                $this->update($model);
                if (isset($v['children'])) {
                    $this->updateSort($v['children'], $id);
                }
                $sort++;
            }
        }
    }

    public function getAllCategory()
    {
        $sql = "SELECT a FROM App:MallHelpCategory a ORDER BY a.sort ASC";
        $list = $this->fetchAll($sql);
        if (!$list) return [];

        $rs = [];
        foreach ($list as $v) {
            $pid = $v['parentId'];
            $rs[$pid][] = $v;
        }
        return $rs;
    }

    public function categorySelect()
    {
        $all = $this->getAllCategory();
        //        dump($all);
        $rs = [];
        $rs['root'] = 0;
        if ($all) {
            foreach ($all[0] as $vv) {
                $id = $vv['id']; //1
                $name = "┝&nbsp;" . $vv['name'];
                $rs[$name] = $id;
                if (isset($all[$id])) {
                    $pre = "&nbsp;&nbsp;&nbsp;&nbsp;";
                    $this->_select($all, $id, $pre, $rs);
                }
            }
        }
        //        dump($rs);
        //        exit;
        return $rs;
    }

    protected function _select($all, $id, $pre, &$rs)
    {
        if ($all[$id]) {
            foreach ($all[$id] as $v) {
                $id = $v['id']; //3
                $name = $pre . "┝&nbsp;" . $v['name'];
                $rs[$name] = $id;
                if (isset($all[$id])) {
                    $preTmp = $pre . "&nbsp;&nbsp;&nbsp;&nbsp;";
                    $this->_select($all, $id, $preTmp, $rs);
                }
            }
        }
    }
}
