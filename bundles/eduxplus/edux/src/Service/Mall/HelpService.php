<?php
/**
 * @Author: kaihui.wang
 * @Contact  hpuwang@gmail.com
 * @Version: 1.0.0
 * @Date: 2020/10/9 10:51
 */

namespace Eduxplus\EduxBundle\Service\Mall;


use Eduxplus\CoreBundle\Lib\Base\AdminBaseService;
use Eduxplus\EduxBundle\Entity\MallHelp;
use Eduxplus\EduxBundle\Entity\MallHelpMain;
use Knp\Component\Pager\PaginatorInterface;

class HelpService extends AdminBaseService
{
    protected $paginator;
    protected $helpCategoryService;

    public function __construct(PaginatorInterface $paginator, HelpCategoryService $helpCategoryService)
    {
        $this->paginator = $paginator;
        $this->helpCategoryService = $helpCategoryService;
    }

    public function getList($request, $page, $pageSize)
    {
        $sql = $this->getFormatRequestSql($request);

        $dql = "SELECT a FROM Edux:MallHelp a " . $sql . " ORDER BY a.id DESC";
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
                $vArr =  $this->toArray($v);
                $categoryId = $vArr["categoryId"];
                $categoryInfo =  $this->helpCategoryService->getById($categoryId);
                $vArr["categoryName"] =$categoryInfo?$categoryInfo["name"]:"-";
                $itemsArr[] = $vArr;
            }
        }

        return [$pagination, $itemsArr];
    }

    public function switchStatus($id, $state)
    {
        $sql = "SELECT a FROM Edux:MallHelp a WHERE a.id=:id";
        $model = $this->db()->fetchOne($sql, ['id' => $id], 1);
        $model->setStatus($state);
        return $this->db()->save($model);
    }

    public function edit($id, $name, $content, $status, $categoryId, $sort, $topValue){
        $sql = "SELECT a FROM Edux:MallHelp a WHERE a.id=:id";
        $model = $this->db()->fetchOne($sql, ['id' => $id], 1);
        $model->setName($name);
        $model->setStatus($status);
        $model->setCategoryId($categoryId);
        $model->setSort($sort);
        $model->setTopValue($topValue);
        $this->db()->save($model);

        $sql = "SELECT a FROM Edux:MallHelpMain a WHERE a.helpId=:helpId";
        $mainModel = $this->db()->fetchOne($sql, ['helpId' => $id], 1);
        $mainModel->setContent($content);
        $this->db()->save($mainModel);

    }

    public function add($name, $content, $status, $categoryId,$sort, $topValue){
        $model = new MallHelp();
        $model->setName($name);
        $model->setStatus($status);
        $model->setCategoryId($categoryId);
        $model->setSort($sort);
        $model->setTopValue($topValue);
        $id = $this->db()->save($model);
        if(!$id) return $this->error()->add("添加失败");
        $mainModel = new MallHelpMain();
        $mainModel->setHelpId($id);
        $mainModel->setContent($content);
        $this->db()->save($mainModel);
        return $id;
    }

    public function getById($id){
        $sql = "SELECT a FROM Edux:MallHelp a WHERE a.id=:id";
        $info = $this->db()->fetchOne($sql, ['id' => $id]);
        $info["main"] = [];
        if($info){
            $sql = "SELECT a FROM Edux:MallHelpMain a WHERE a.helpId=:helpId";
            $main = $this->db()->fetchOne($sql, ['helpId' => $info['id']]);
            $info["main"] = $main;
        }
        return $info;
    }

    public function del($id){
        $sql = "SELECT a FROM Edux:MallHelp a WHERE a.id=:id";
        $model = $this->db()->fetchOne($sql, ["id" => $id], 1);
        return $this->db()->delete($model);
    }
}
