<?php
/**
 * @Author: kaihui.wang
 * @Contact  hpuwang@gmail.com
 * @Version: 1.0.0
 * @Date: 2020/11/30 19:22
 */

namespace Eduxplus\QaBundle\Service\Admin;


use Eduxplus\CoreBundle\Lib\Base\AdminBaseService;
use Eduxplus\QaBundle\Entity\TeachQAChapterSub;
use Knp\Component\Pager\PaginatorInterface;

class QAChapterSubService extends AdminBaseService
{

    protected $paginator;

    public function __construct(PaginatorInterface $paginator)
    {
        $this->paginator = $paginator;
    }

    public function updateSort($data, $pid = 0){
        if ($data) {
            $sort = 0;
            foreach ($data as $k => $v) {
                $id = $v['id'];
                $sql = "SELECT a FROM Qa:TeachQAChapterSub a WHERE a.id=:id";
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

    public function del($id)
    {
        $sql = "SELECT a FROM Qa:TeachQAChapterSub a WHERE a.id=:id";
        $model = $this->fetchOne($sql, ['id' => $id], 1);
        return $this->delete($model);
    }

    public function getById($id)
    {
        $sql = "SELECT a FROM Qa:TeachQAChapterSub a WHERE a.id=:id";
        return $this->fetchOne($sql, ['id' => $id]);
    }

    public function searchResultByid($chapterSubId){
        if(!$chapterSubId) return [];
        $info = $this->getById($chapterSubId);
        return [$info['name'] => $info['id']];
    }

    public function hasChild($id){
        $sql = "SELECT a FROM Qa:TeachQAChapterSub a WHERE a.parentId=:parentId";
        return $this->fetchOne($sql, ['parentId' => $id]);
    }

    public function add($name, $parentId, $sort, $status, $chapterId){
        $model = new TeachQAChapterSub();
        $findPath = $this->findPath($parentId);
        $model->setName($name);
        $model->setFindPath($findPath);
        $model->setParentId($parentId);
        $model->setStatus($status);
        $model->setSort($sort);
        $model->setChapterId($chapterId);
        return $this->save($model);
    }

    public function findPath($id)
    {
        if (!$id) return "";
        $sql = "SELECT a.parentId FROM Qa:TeachQAChapterSub a WHERE a.id = :id";
        $pid = $this->fetchField("parentId", $sql, ['id' => $id]);
        if (!$pid) return ",{$id},";
        $str = ",{$id},";
        $str .= ltrim($this->findPath($pid), ",");
        return $str;
    }

    public function edit($name, $parentId, $sort, $status, $id){
        $sql = "SELECT a FROM Qa:TeachQAChapterSub a WHERE a.id=:id";
        $model =  $this->fetchOne($sql, ['id' => $id], 1);
        $findPath = $this->findPath($parentId);
        $model->setName($name);
        $model->setFindPath($findPath);
        $model->setParentId($parentId);
        $model->setStatus($status);
        $model->setSort($sort);
        return $this->save($model);
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

    public function getChapterTree($parentId, $chapterId)
    {
        $sql = "SELECT a FROM Qa:TeachQAChapterSub a WHERE a.parentId = :parentId AND a.chapterId =:chapterId ORDER BY a.sort ASC";
        $items = $this->fetchAll($sql, ['parentId' => $parentId, "chapterId"=>$chapterId]);
        if (!$items) return [];
        $result = [];
        foreach ($items as &$v) {
            $tmp = $v;
            $child =  $this->getChapterTree($v['id'], $chapterId);
            $tmp['childs'] = $child;
            $result[] = $tmp;
        }
        return $result;
    }

    public function chapterSelect2($chapterId)
    {
        $rs = $this->chapterSelect($chapterId);
        $result = [];
        if($rs){
            foreach ($rs as $k=>$v){
                $tmp = [];
                $tmp['id'] = $v;
                $tmp['text'] = str_replace('&nbsp;',"　",$k);
                $result[] = $tmp;
            }
        }
        return $result;
    }

    public function chapterSelect($chapterId)
    {
        $all = $this->getAllChapter($chapterId);
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

    public function getAllChapter($chapterId)
    {
        $sql = "SELECT a FROM Qa:TeachQAChapterSub a WHERE a.chapterId =:chapterId ORDER BY a.sort ASC";
        $list = $this->fetchAll($sql, ["chapterId"=>$chapterId]);
        if (!$list) return [];

        $rs = [];
        foreach ($list as $v) {
            $pid = $v['parentId'];
            $rs[$pid][] = $v;
        }
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

    public function getChapterSubIds($id)
    {
        $sql = "SELECT a FROM Qa:TeachQAChapterSub a WHERE a.findPath like :findPath AND a.status=1 ORDER BY a.sort ASC";
        return $this->fetchFields("id", $sql, ["findPath" => '%,'.$id.',%']);
    }

}
