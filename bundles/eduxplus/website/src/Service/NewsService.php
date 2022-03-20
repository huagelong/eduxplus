<?php
/**
 * @Author: kaihui.wang
 * @Contact  hpuwang@gmail.com
 * @Version: 1.0.0
 * @Date: 2020/10/21 14:14
 */

namespace Eduxplus\WebsiteBundle\Service;


use Eduxplus\CoreBundle\Lib\Base\AppBaseService;
use Eduxplus\CoreBundle\Lib\Service\HelperService;
use Knp\Component\Pager\PaginatorInterface;

class NewsService extends AppBaseService
{
    protected $helperService;
    protected $paginator;

    public function __construct(HelperService $helperService, PaginatorInterface $paginator)
    {
        $this->helperService = $helperService;
        $this->paginator = $paginator;
    }

    public function getTopNews($limit=9){
        $sql = "SELECT a FROM Cms:CmsNews a WHERE a.status=1 ORDER BY a.createdAt DESC";
        $result = $this->db()->fetchAll($sql, [],0,$limit);
        if(!$result) return $result;
        foreach ($result as &$v){
            $v['createdAtTime'] = $v["createdAt"]->getTimestamp();
        }
        return $result;
    }


    public function getAllNewsCategory(){
        $sql = "SELECT a FROM Cms:CmsNewsCategory a WHERE a.isShow =1 AND a.parentId = 0 ORDER BY a.sort DESC";
        $category = $this->db()->fetchAll($sql);
        return $category;
    }

    public function viewNumIncre($id){
        $sql = "UPDATE Cms:CmsNews a SET a.viewNumber = a.viewNumber+1 WHERE a.id = :id ";
        $this->db()->execute($sql, ["id"=>$id]);
    }

    /**
     *
     * @param $topValue 0-公告，1-热门
     */
    public function getNewsByTopValue($topValue, $limit=5){
        $sql = "SELECT a FROM Cms:CmsNews a WHERE a.status=1 AND a.topValue = :topValue ORDER BY a.sort DESC, a.createdAt DESC";
        $result = $this->db()->fetchAll($sql, ["topValue"=>$topValue],0,$limit);
        if(!$result) return $result;
        foreach ($result as &$v){
            $v['createdAtTime'] = $v["createdAt"]->getTimestamp();
        }
        return $result;
    }

    public function getNewsList($category, $page, $pageSize){
        if($category){
            $dql = "SELECT a FROM Cms:CmsNews a WHERE a.categoryId = :categoryId ORDER BY a.createdAt DESC";
        }else{
            $dql = "SELECT a FROM Cms:CmsNews a ORDER BY a.createdAt DESC ";
        }

        $em = $this->getDoctrine()->getManager();
        $em = $this->db()->enableSoftDeleteable($em);
        $query = $em->createQuery($dql);
       if($category) $query = $query->setParameters(["categoryId" => $category]);
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
                $vArr['createdAtTime'] = strtotime($vArr["createdAt"]);
                $itemsArr[] = $vArr;
            }
        }
        return [$pagination, $itemsArr];
    }


    public function getById($id){
        $dql = "SELECT a FROM Cms:CmsNews a WHERE a.id =:id ";
        $detail = $this->db()->fetchOne($dql, ["id"=>$id]);
        if(!$detail) return $detail;
        $detail['createdAtTime'] = $detail["createdAt"]->getTimestamp();
        $mainSql = "SELECT a FROM Cms:CmsNewsMain a WHERE a.newsId =:newsId ";
        $mainDetail = $this->db()->fetchOne($mainSql, ["newsId"=>$id]);
        $detail['main'] = $mainDetail;
        return $detail;
    }

}
