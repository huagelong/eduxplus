<?php
/**
 * @Author: kaihui.wang
 * @Contact  hpuwang@gmail.com
 * @Version: 1.0.0
 * @Date: 2020/9/28 16:58
 */

namespace Eduxplus\CmsBundle\Service;


use Eduxplus\CoreBundle\Lib\Base\AdminBaseService;
use Eduxplus\CmsBundle\Entity\CmsPage;
use Eduxplus\CmsBundle\Entity\CmsPageMain;
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

        $dql = "SELECT a FROM Cms:CmsPage a " . $sql . " ORDER BY a.id DESC";
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
        $sql = "SELECT a FROM Cms:CmsPage a WHERE a.id=:id";
        $model = $this->db()->fetchOne($sql, ['id' => $id], 1);
        $model->setStatus($state);
        return $this->db()->save($model);
    }

    public function edit($id, $name, $content, $status){
        $sql = "SELECT a FROM Cms:CmsPage a WHERE a.id=:id";
        $model = $this->db()->fetchOne($sql, ['id' => $id], 1);
        $model->setName($name);
        $model->setStatus($status);
        $this->db()->save($model);

        $sql = "SELECT a FROM Cms:CmsPageMain a WHERE a.pageId=:pageId";
        $mainModel = $this->db()->fetchOne($sql, ['pageId' => $id], 1);
        $mainModel->setContent($content);
        $this->db()->save($mainModel);

    }

    public function add($name, $content, $status){
        $model = new CmsPage();
        $model->setName($name);
        $model->setStatus($status);
        $id = $this->db()->save($model);
        if(!$id) return $this->error()->add("添加单页失败");
        $mainModel = new CmsPageMain();
        $mainModel->setPageId($id);
        $mainModel->setContent($content);
        $this->db()->save($mainModel);
        return $id;
    }

    public function getById($id){
        $sql = "SELECT a FROM Cms:CmsPage a WHERE a.id=:id";
        $info = $this->db()->fetchOne($sql, ['id' => $id]);
        $info["main"] = [];
        if($info){
            $sql = "SELECT a FROM Cms:CmsPageMain a WHERE a.pageId=:pageId";
            $main = $this->db()->fetchOne($sql, ['pageId' => $info['id']]);
            $info["main"] = $main;
        }
        return $info;
    }

    public function del($id){
        $sql = "SELECT a FROM Cms:CmsPage a WHERE a.id=:id";
        $model = $this->db()->fetchOne($sql, ["id" => $id], 1);
        return $this->db()->delete($model);
    }

}
