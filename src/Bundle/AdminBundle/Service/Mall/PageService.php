<?php
/**
 * @Author: kaihui.wang
 * @Contact  hpuwang@gmail.com
 * @Version: 1.0.0
 * @Date: 2020/9/28 16:58
 */

namespace App\Bundle\AdminBundle\Service\Mall;


use App\Bundle\AppBundle\Lib\Base\AdminBaseService;
use App\Entity\MallPage;
use App\Entity\MallPageMain;
use Knp\Component\Pager\PaginatorInterface;

class PageService extends AdminBaseService
{
    protected $paginator;

    public function __construct(PaginatorInterface $paginator)
    {
        $this->paginator = $paginator;

    }

    public function getList($request, $page, $pageSize)
    {
        $sql = $this->getFormatRequestSql($request);

        $dql = "SELECT a FROM App:MallPage a " . $sql . " ORDER BY a.id DESC";
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
                $itemsArr[] = $vArr;
            }
        }

        return [$pagination, $itemsArr];
    }

    public function switchStatus($id, $state)
    {
        $sql = "SELECT a FROM App:MallPage a WHERE a.id=:id";
        $model = $this->fetchOne($sql, ['id' => $id], 1);
        $model->setStatus($state);
        return $this->save($model);
    }

    public function edit($id, $name, $content, $status){
        $sql = "SELECT a FROM App:MallPage a WHERE a.id=:id";
        $model = $this->fetchOne($sql, ['id' => $id], 1);
        $model->setName($name);
        $model->setStatus($status);
        $this->save($model);

        $sql = "SELECT a FROM App:MallPageMain a WHERE a.pageId=:pageId";
        $mainModel = $this->fetchOne($sql, ['pageId' => $id], 1);
        $mainModel->setContent($content);
        $this->save($mainModel);

    }

    public function add($name, $content, $status){
        $model = new MallPage();
        $model->setName($name);
        $model->setStatus($status);
        $id = $this->save($model);
        if(!$id) return $this->error()->add("添加单页失败");
        $mainModel = new MallPageMain();
        $mainModel->setPageId($id);
        $mainModel->setContent($content);
        $this->save($mainModel);
        return $id;
    }

    public function getById($id){
        $sql = "SELECT a FROM App:MallPage a WHERE a.id=:id";
        $info = $this->fetchOne($sql, ['id' => $id]);
        $info["main"] = [];
        if($info){
            $sql = "SELECT a FROM App:MallPageMain a WHERE a.pageId=:pageId";
            $main = $this->fetchOne($sql, ['pageId' => $info['id']]);
            $info["main"] = $main;
        }
        return $info;
    }

    public function del($id){
        $sql = "SELECT a FROM App:MallPage a WHERE a.id=:id";
        $model = $this->fetchOne($sql, ["id" => $id], 1);
        return $this->delete($model);
    }

}
