<?php
/**
 * @Author: kaihui.wang
 * @Contact  hpuwang@gmail.com
 * @Version: 1.0.0
 * @Date: 2020/10/9 10:51
 */

namespace App\Bundle\AdminBundle\Service\Mall;


use App\Bundle\AppBundle\Lib\Base\AdminBaseService;
use App\Entity\MallHelp;
use App\Entity\MallHelpMain;
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

        $dql = "SELECT a FROM App:MallHelp a " . $sql . " ORDER BY a.id DESC";
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
        $sql = "SELECT a FROM App:MallHelp a WHERE a.id=:id";
        $model = $this->fetchOne($sql, ['id' => $id], 1);
        $model->setStatus($state);
        return $this->save($model);
    }

    public function edit($id, $name, $content, $status, $categoryId, $sort, $topValue){
        $sql = "SELECT a FROM App:MallHelp a WHERE a.id=:id";
        $model = $this->fetchOne($sql, ['id' => $id], 1);
        $model->setName($name);
        $model->setStatus($status);
        $model->setCategoryId($categoryId);
        $model->setSort($sort);
        $model->setTopValue($topValue);
        $this->save($model);

        $sql = "SELECT a FROM App:MallHelpMain a WHERE a.helpId=:helpId";
        $mainModel = $this->fetchOne($sql, ['helpId' => $id], 1);
        $mainModel->setContent($content);
        $this->save($mainModel);

    }

    public function add($name, $content, $status, $categoryId,$sort, $topValue){
        $model = new MallHelp();
        $model->setName($name);
        $model->setStatus($status);
        $model->setCategoryId($categoryId);
        $model->setSort($sort);
        $model->setTopValue($topValue);
        $id = $this->save($model);
        if(!$id) return $this->error()->add("添加失败");
        $mainModel = new MallHelpMain();
        $mainModel->setHelpId($id);
        $mainModel->setContent($content);
        $this->save($mainModel);
        return $id;
    }

    public function getById($id){
        $sql = "SELECT a FROM App:MallHelp a WHERE a.id=:id";
        $info = $this->fetchOne($sql, ['id' => $id]);
        $info["main"] = [];
        if($info){
            $sql = "SELECT a FROM App:MallHelpMain a WHERE a.helpId=:helpId";
            $main = $this->fetchOne($sql, ['helpId' => $info['id']]);
            $info["main"] = $main;
        }
        return $info;
    }

    public function del($id){
        $sql = "SELECT a FROM App:MallHelp a WHERE a.id=:id";
        $model = $this->fetchOne($sql, ["id" => $id], 1);
        return $this->delete($model);
    }
}
