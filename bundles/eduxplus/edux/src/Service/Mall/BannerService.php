<?php
/**
 * @Author: kaihui.wang
 * @Contact  hpuwang@gmail.com
 * @Version: 1.0.0
 * @Date: 2020/9/28 16:58
 */

namespace Eduxplus\EduxBundle\Service\Mall;


use Eduxplus\CoreBundle\Service\UserService;
use Eduxplus\CoreBundle\Lib\Base\AdminBaseService;
use Eduxplus\EduxBundle\Entity\MallBanner;
use Eduxplus\EduxBundle\Entity\MallBannerMain;
use Knp\Component\Pager\PaginatorInterface;

class BannerService extends AdminBaseService
{
    protected $paginator;
    protected $userService;

    public function __construct(PaginatorInterface $paginator, UserService $userService)
    {
        $this->paginator = $paginator;
        $this->userService = $userService;
    }

    public function getList($request, $page, $pageSize)
    {
        $sql = $this->getFormatRequestSql($request);
        $dql = "SELECT a FROM Edux:MallBanner a  ". $sql . "  ORDER BY a.id DESC";
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

    public function edit($id, $name, $position){
        $sql = "SELECT a FROM Edux:MallBanner a WHERE a.id=:id";
        $model = $this->db()->fetchOne($sql, ['id' => $id], 1);
        $model->setName($name);
        $model->setPosition($position);
        $this->db()->save($model);
    }

    public function add($name,$position){
        $model = new MallBanner();
        $model->setName($name);
        $model->setPosition($position);
        $id = $this->db()->save($model);
        return $id;
    }

    public function getByPosition($position, $id=0){
        if(!$id){
            $sql = "SELECT a FROM Edux:MallBanner a WHERE a.position=:position";
            $info = $this->db()->fetchOne($sql, ['position' => $position]);
            return $info;
        }else{
            $sql = "SELECT a FROM Edux:MallBanner a WHERE a.position=:position AND a.id !=:id";
            $info = $this->db()->fetchOne($sql, ['position' => $position, "id"=>$id]);
            return $info;
        }
    }

    public function getById($id){
        $sql = "SELECT a FROM Edux:MallBanner a WHERE a.id=:id";
        $info = $this->db()->fetchOne($sql, ['id' => $id]);
        return $info;
    }

    public function del($id){
        $sql = "SELECT a FROM Edux:MallBanner a WHERE a.id=:id";
        $model = $this->db()->fetchOne($sql, ["id" => $id], 1);
        return $this->db()->delete($model);
    }

    public function getMainList($request, $page, $pageSize, $pid)
    {
        $sql = $this->getFormatRequestSql($request);
        $strSql = "";
        if($pid){
            if($sql){
                $strSql = " AND a.bannerId = :bannerId";
            }else{
                $strSql = " WHERE a.bannerId = :bannerId";
            }
        }

        $dql = "SELECT a FROM Edux:MallBannerMain a ".$strSql."  " . $sql . " ORDER BY a.id DESC";
        $em = $this->getDoctrine()->getManager();
        $query = $em->createQuery($dql);

        if($pid) $query = $query->setParameters(["bannerId"=>$pid]);

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
                $vArr =  $this->toArray($v);
                $createrUid = $vArr['uid'];
                $createrUser = $this->userService->getById($createrUid);
                $vArr['creater'] = $createrUser['fullName'];
                $itemsArr[] = $vArr;
            }
        }

        return [$pagination, $itemsArr];
    }

    public function switchMainStatus($id, $state)
    {
        $sql = "SELECT a FROM Edux:MallBannerMain a WHERE a.id=:id";
        $model = $this->db()->fetchOne($sql, ['id' => $id], 1);
        $model->setStatus($state);
        return $this->db()->save($model);
    }

    public function editMain($id,$uid, $bannerImg,$url,$sort, $status){
        $sql = "SELECT a FROM Edux:MallBannerMain a WHERE a.id=:id";
        $model = $this->db()->fetchOne($sql, ['id' => $id], 1);
        $model->setUid($uid);
        $model->setBannerImg($bannerImg);
        if($url) $model->setUrl($url);
        $model->setSort($sort);
        $model->setStatus($status);
        $this->db()->save($model);
    }

    public function addMain($bannerId,$uid, $bannerImg,$url,$sort, $status){
        $model = new MallBannerMain();
        $model->setBannerId($bannerId);
        $model->setUid($uid);
        $model->setBannerImg($bannerImg);
        if($url) $model->setUrl($url);
        $model->setSort($sort);
        $model->setStatus($status);
        $id = $this->db()->save($model);
        return $id;
    }

    public function getMainById($id){
        $sql = "SELECT a FROM Edux:MallBannerMain a WHERE a.id=:id";
        $info = $this->db()->fetchOne($sql, ['id' => $id]);
        return $info;
    }

    public function delMain($id){
        $sql = "SELECT a FROM Edux:MallBannerMain a WHERE a.id=:id";
        $model = $this->db()->fetchOne($sql, ["id" => $id], 1);
        return $this->db()->delete($model);
    }

}
