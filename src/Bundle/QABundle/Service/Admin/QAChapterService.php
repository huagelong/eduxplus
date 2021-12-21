<?php
/**
 * @Author: kaihui.wang
 * @Contact  hpuwang@gmail.com
 * @Version: 1.0.0
 * @Date: 2020/11/29 18:41
 */

namespace App\Bundle\QABundle\Service\Admin;


use Eduxplus\CoreBundle\Service\Teach\CategoryService;
use Eduxplus\CoreBundle\Lib\Base\AdminBaseService;
use App\Bundle\QABundle\Entity\TeachQAChapter;
use Knp\Component\Pager\PaginatorInterface;

class QAChapterService extends AdminBaseService
{

    protected $paginator;
    protected $categoryService;

    public function __construct(PaginatorInterface $paginator, CategoryService $categoryService)
    {
        $this->paginator = $paginator;
        $this->categoryService = $categoryService;
    }

    public function getCollectionList($request, $page, $pageSize){
        $sql = $this->getFormatRequestSql($request);
        $dql = "SELECT a FROM QA:TeachQAChapter a " . $sql . " ORDER BY a.id DESC";
        $em = $this->getDoctrine()->getManager();
        $query = $em->createQuery($dql);
        $pagination = $this->paginator->paginate(
            $query,
            $page,
            $pageSize
        );

        $items = $pagination->getItems();
        $itemsArr = [];
        if ($items) {
            foreach ($items as $v) {
                $vArr = $this->toArray($v);
                $sql = "SELECT a FROM Core:TeachCategory a WHERE a.id=:id";
                $categoryInfo = $this->fetchOne($sql, ['id' => $vArr['categoryId']]);
                $vArr['category'] = $categoryInfo['name'];
                $itemsArr[] = $vArr;
            }
        }

        return [$pagination, $itemsArr];
    }

    public function checkName($name, $id =0){
        $sql = "SELECT a FROM QA:TeachQAChapter a where a.name =:name ";
        $params = [];
        $params['name'] = $name;
        if ($id) {
            $sql = $sql . " AND a.id !=:id ";
            $params['id'] = $id;
        }
        return $this->fetchOne($sql, $params);
    }


    public function add($name, $categoryId, $status){
        $model = new TeachQAChapter();
        $model->setName($name);
        $model->setCategoryId($categoryId);
        $model->setStatus($status);
        return $this->save($model);
    }

    public function edit($id, $name, $categoryId, $status){
        $sql = "SELECT a FROM QA:TeachQAChapter a WHERE a.id=:id";
        $model = $this->fetchOne($sql, ['id' => $id], 1);
        if($model){
            $model->setName($name);
            $model->setCategoryId($categoryId);
            $model->setStatus($status);
            return $this->save($model);
        }
    }

    public function getById($id){
        $sql = "SELECT a FROM QA:TeachQAChapter a WHERE a.id=:id";
        return $this->fetchOne($sql, ['id' => $id]);
    }

    public function searchResultByid($chapterId){
        if(!$chapterId) return [];
        $info = $this->getById($chapterId);
        return [$info['name'] => $info['id']];
    }

    public function del($id){
        $sql = "SELECT count(a.id) as cnt FROM QA:TeachQAChapterSub a WHERE a.chapterId = :chapterId";
        $sub = $this->fetchField("cnt", $sql, ["chapterId"=>$id]);
        if($sub>0) return $this->error()->add("请先删除章节点数据");
        $sql = "DELETE FROM QA:TeachQAChapter a WHERE a.id=:id";
        $this->execute($sql, ["id" => $id]);
        return true;
    }

    /**
     * 状态切换
     *
     * @param $id
     * @param $state
     * @return mixed
     */
    public function switchStatus($id, $state){
        $sql = "SELECT a FROM QA:TeachQAChapter a WHERE a.id=:id";
        $model = $this->fetchOne($sql, ['id' => $id], 1);
        $model->setStatus($state);

        return $this->save($model);
    }

    public function searchChapterByName($name, $categoryId=0){
        $params = [];
        $params['name'] = "%" . $name . "%";

        if(!$categoryId){
            $sql = "SELECT a FROM QA:TeachQAChapter a where a.name like :name AND a.status=1 ";
        }else{
            $categoryIds = $this->categoryService->getSubCategoryIds($categoryId);
            array_push($categoryIds, $categoryId);
            $sql = "SELECT a FROM QA:TeachQAChapter a where a.name like :name AND a.status=1 AND a.categoryId IN(:categoryId) ";
            $params['categoryId'] = $categoryIds;
        }

        $list = $this->fetchAll($sql, $params);
        if (!$list) return [];
        $rs = [];
        foreach ($list as $v) {
            $tmp = [];
            $tmp['id'] = $v['id'];
            $tmp['text'] = $v['name'];
            $rs[] = $tmp;
        }
        return $rs;
    }



}
