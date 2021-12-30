<?php
/**
 * @Author: kaihui.wang
 * @Contact  hpuwang@gmail.com
 * @Version: 1.0.0
 * @Date: 2020/11/12 16:38
 */

namespace Eduxplus\EduxBundle\Service\Jw;


use Eduxplus\CoreBundle\Lib\Base\AdminBaseService;
use Knp\Component\Pager\PaginatorInterface;

class ClassService extends AdminBaseService
{
    protected $paginator;

    public function __construct(PaginatorInterface $paginator)
    {
        $this->paginator = $paginator;
    }

    public function getClassList($request, $page, $pageSize)
    {
        $sql = $this->getFormatRequestSql($request);
        $dql = "SELECT a FROM Edux:JwClasses a " . $sql . " ORDER BY a.id DESC";
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
                //学习计划
                $studyPlanId = $vArr["studyPlanId"];
                $sql = "SELECT a FROM Edux:TeachStudyPlan a WHERE a.id=:id";
                $studyPlanInfo = $this->fetchOne($sql, ['id' => $studyPlanId]);
                //产品
                $productId = $vArr["productId"];
                $sql = "SELECT a FROM Edux:TeachProducts a WHERE a.id=:id";
                $productInfo = $this->fetchOne($sql, ['id' => $productId]);

                $vArr["studyPlan"] = $studyPlanInfo?$studyPlanInfo['name']:"";
                $vArr["product"] = $productInfo?$productInfo['name']:"";

                $itemsArr[] = $vArr;
            }
        }
        return [$pagination, $itemsArr];
    }

    public function getMemberList($request, $page, $pageSize, $classesId){
        $sql = $this->getFormatRequestSql($request);
        //$classesId
        if(!$sql){
            $sql = " WHERE a.classesId =:classesId ";
        }else{
            $sql .= " AND a.classesId =:classesId ";
        }
        $dql = "SELECT a FROM Edux:JwClassesMembers a " . $sql . " ORDER BY a.id DESC";
        $em = $this->getDoctrine()->getManager();
        $query = $em->createQuery($dql);

        if($classesId) $query = $query->setParameters(["classesId"=>$classesId]);

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
                //学习计划
                $uid = $vArr["uid"];
                $sql = "SELECT a FROM Edux:BaseUser a WHERE a.id=:id";
                $userInfo = $this->fetchOne($sql, ['id' => $uid]);
                $vArr["user"] = $userInfo?($userInfo['fullName']."/".$userInfo['displayName']):"";
                $itemsArr[] = $vArr;
            }
        }
        return [$pagination, $itemsArr];
    }

    public function getById($id)
    {
        $sql = "SELECT a FROM Edux:JwClasses a WHERE a.id=:id";
        return $this->fetchOne($sql, ['id' => $id]);
    }

}
