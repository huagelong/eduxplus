<?php
/**
 * @Author: kaihui.wang
 * @Contact  hpuwang@gmail.com
 * @Version: 1.0.0
 * @Date: 2020/11/2 18:35
 */

namespace Eduxplus\WebsiteBundle\Service;


use Eduxplus\CoreBundle\Lib\Base\AppBaseService;
use Eduxplus\CoreBundle\Lib\Service\Base\EsService;
use Knp\Component\Pager\PaginatorInterface;

class SearchService extends AppBaseService
{

    protected $paginator;
    protected $esService;

    public function __construct(PaginatorInterface $paginator, EsService $esService)
    {
        $this->paginator = $paginator;
        $this->esService = $esService;
    }

    public function getEsList($kw, $type, $scrollId, $pageSize){
        if($type == 1){//课程商品
            return $this->goodsEsList($kw, $scrollId, $pageSize);
        }else{//$type =2 资讯
            return $this->newsEsList($kw, $scrollId, $pageSize);
        }
    }

    public function goodsEsList($kw, $scrollId, $pageSize){
        list($total, $ids, $scrollId, $highlights) = $this->esService->esSearch($kw, "goods","name", $scrollId, $pageSize);
        $totalPage = ceil($total/$pageSize);
        $idsTmp = implode(",", $ids);
        if($ids){
            $sql = "SELECT a FROM Edux:MallGoods a WHERE a.id IN(:id) AND a.status=1 ORDER BY FIELD(a.id,".$idsTmp.")";
            $items = $this->fetchAll($sql, ["id"=>$ids]);
            if ($items) {
                foreach ($items as &$vArr) {
                    $vArr['shopPriceView'] = number_format($vArr['shopPrice'] / 100, 2);
                    $vArr['name'] = $vArr['aliasName']?$vArr['aliasName']:$vArr['name'];
                }
            }
            return [$totalPage, $items, $scrollId, $highlights];
        }else{
            return [$totalPage, [], $scrollId, $highlights];
        }
    }

    public function newsEsList($kw, $scrollId, $pageSize){
        list($total, $ids, $scrollId, $highlights) = $this->esService->esSearch($kw, "news","title", $scrollId, $pageSize);
        $totalPage = ceil($total/$pageSize);
        $idsTmp = implode(",", $ids);
        if($ids){
            $sql = "SELECT a FROM Edux:MallNews a WHERE a.id IN(:id) AND a.status=1 ORDER BY FIELD(a.id,".$idsTmp.")";
            $items = $this->fetchAll($sql, ["id"=>$ids]);
            if ($items) {
                foreach ($items as &$vArr) {
                    $vArr['createdAtTime'] = $vArr["createdAt"]->getTimestamp();
                }
            }
            return [$totalPage, $items, $scrollId, $highlights];
        }else{
            return [$totalPage, [], $scrollId, $highlights];
        }
    }

    public function getList($kw, $type, $page, $pageSize){
        if($type == 1){//课程商品
            return $this->goodsList($kw, $page, $pageSize);
        }else{//$type =2 资讯
            return $this->newsList($kw, $page, $pageSize);
        }
    }

    public function goodsList($kw, $page, $pageSize){
        if($kw){
            $sql = "SELECT a FROM Edux:MallGoods a WHERE a.name LIKE :name AND a.status=1  AND  a.goodType=1  ORDER BY a.createdAt DESC";
            $em = $this->getDoctrine()->getManager();
            $em = $this->enableSoftDeleteable($em);
            $query = $em->createQuery($sql);
            $query = $query->setParameters(["name"=>"%".$kw."%"]);
        }else{
            $sql = "SELECT a FROM Edux:MallGoods a WHERE a.status=1  AND  a.goodType=1 ORDER BY a.createdAt DESC";
            $em = $this->getDoctrine()->getManager();
            $em = $this->enableSoftDeleteable($em);
            $query = $em->createQuery($sql);
        }

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
                $vArr['shopPriceView'] = number_format($vArr['shopPrice'] / 100, 2);
                $vArr['name'] = $vArr['aliasName']?$vArr['aliasName']:$vArr['name'];
                $itemsArr[] = $vArr;
            }
        }
        return [$pagination, $itemsArr];
    }

    public function newsList($kw, $page, $pageSize){
        if($kw) {
            $sql = "SELECT a FROM Edux:MallNews a WHERE a.title LIKE :title AND a.status=1 ORDER BY a.createdAt DESC";
            $em = $this->getDoctrine()->getManager();
            $em = $this->enableSoftDeleteable($em);
            $query = $em->createQuery($sql);
            $query = $query->setParameters(["title" => "%" . $kw . "%"]);
        }else{
            $sql = "SELECT a FROM Edux:MallNews a WHERE a.status=1 ORDER BY a.createdAt DESC";
            $em = $this->getDoctrine()->getManager();
            $em = $this->enableSoftDeleteable($em);
            $query = $em->createQuery($sql);
        }

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
                $vArr['createdAtTime'] = $vArr["createdAt"]['timestamp'];
                $itemsArr[] = $vArr;
            }
        }
        return [$pagination, $itemsArr];
    }




}
