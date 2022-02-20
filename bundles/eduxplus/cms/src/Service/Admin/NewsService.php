<?php
/**
 * @Author: kaihui.wang
 * @Contact  hpuwang@gmail.com
 * @Version: 1.0.0
 * @Date: 2020/10/9 10:51
 */

namespace Eduxplus\CmsBundle\Service\Admin;


use Eduxplus\CoreBundle\Service\UserService;
use Eduxplus\CoreBundle\Lib\Base\AdminBaseService;
use Eduxplus\CoreBundle\Lib\Service\Base\EsService;
use Eduxplus\CmsBundle\Entity\CmsNews;
use Eduxplus\CmsBundle\Entity\CmsNewsMain;
use Knp\Component\Pager\PaginatorInterface;

class NewsService extends AdminBaseService
{
    protected $paginator;
    protected $newsCategoryService;
    protected $userService;
    protected $esService;

    public function __construct(PaginatorInterface $paginator, NewsCategoryService $newsCategoryService, UserService $userService,EsService $esService)
    {
        $this->paginator = $paginator;
        $this->newsCategoryService = $newsCategoryService;
        $this->userService = $userService;
        $this->esService = $esService;
    }

    public function getList($request, $page, $pageSize)
    {
        $sql = $this->getFormatRequestSql($request);

        $dql = "SELECT a FROM Cms:CmsNews a " . $sql . " ORDER BY a.id DESC";
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
                $createrUid = $vArr['uid'];
                $createrUser = $this->userService->getById($createrUid);
                $vArr['creater'] = $createrUser['fullName'];
                $categoryId = $vArr["categoryId"];
                $categoryInfo =  $this->newsCategoryService->getById($categoryId);
                $vArr["categoryName"] =$categoryInfo?$categoryInfo["name"]:"-";
                $itemsArr[] = $vArr;
            }
        }

        return [$pagination, $itemsArr];
    }

    public function saveEs($id, $title){
        $this->esService->esUpdate("news", $id, ["title"=>$title]);
    }

    public function delEs($id){
        $this->esService->esDel("news", $id);
    }

    public function switchStatus($id, $state)
    {
        $sql = "SELECT a FROM Cms:CmsNews a WHERE a.id=:id";
        $model = $this->db()->fetchOne($sql, ['id' => $id], 1);
        $model->setStatus($state);

        if($state){
            $this->saveEs($id, $model->getTitle());
        }else{
            $this->delEs($id);
        }

        return $this->db()->save($model);
    }

    public function edit($id, $name, $content, $status, $categoryId, $uid, $topValue, $sort, $img){
        $sql = "SELECT a FROM Cms:CmsNews a WHERE a.id=:id";
        $model = $this->db()->fetchOne($sql, ['id' => $id], 1);
        $model->setTitle($name);
        $model->setStatus($status);
        $model->setSort($sort);
        $model->setImg($img);
        $model->setCategoryId($categoryId);
        $model->setUid($uid);
        $model->setTopValue($topValue);
        $this->db()->save($model);

        $sql = "SELECT a FROM Cms:CmsNewsMain a WHERE a.newsId=:newsId";
        $mainModel = $this->db()->fetchOne($sql, ['newsId' => $id], 1);
        $mainModel->setContent($content);
        $this->db()->save($mainModel);

        if($status){
            $this->saveEs($id, $name);
        }else{
            $this->delEs($id);
        }

    }

    public function add($name, $content, $status, $categoryId, $uid, $topValue, $sort, $img){
        $model = new CmsNews();
        $model->setTitle($name);
        $model->setStatus($status);
        $model->setUid($uid);
        $model->setTopValue($topValue);
        $model->setSort($sort);
        $model->setImg($img);
        $model->setCategoryId($categoryId);
        $id = $this->db()->save($model);
        if(!$id) return $this->error()->add("添加失败");
        $mainModel = new CmsNewsMain();
        $mainModel->setNewsId($id);
        $mainModel->setContent($content);
        $this->db()->save($mainModel);

        if($status) {
            $this->saveEs($id, $name);
        }

        return $id;
    }

    public function getById($id){
        $sql = "SELECT a FROM Cms:CmsNews a WHERE a.id=:id";
        $info = $this->db()->fetchOne($sql, ['id' => $id]);
        $info["main"] = [];
        if($info){
            $sql = "SELECT a FROM Cms:CmsNewsMain a WHERE a.newsId=:newsId";
            $main = $this->db()->fetchOne($sql, ['newsId' => $info['id']]);
            $info["main"] = $main;
        }
        return $info;
    }

    public function del($id){
        $sql = "SELECT a FROM Cms:CmsNews a WHERE a.id=:id";
        $model = $this->db()->fetchOne($sql, ["id" => $id], 1);
        $result = $this->db()->delete($model);
        $this->delEs($id);
        return $result;
    }
}
