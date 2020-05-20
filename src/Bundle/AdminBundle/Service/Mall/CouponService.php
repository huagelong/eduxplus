<?php
/**
 * @Author: kaihui.wang
 * @Contact  hpuwang@gmail.com
 * @Version: 1.0.0
 * @Date: 2020/5/7 11:17
 */

namespace App\Bundle\AdminBundle\Service\Mall;


use App\Bundle\AdminBundle\Service\AdminBaseService;
use App\Bundle\AppBundle\Lib\Base\BaseService;
use Knp\Component\Pager\PaginatorInterface;
use App\Bundle\AdminBundle\Service\Teach\CategoryService;
use App\Bundle\AdminBundle\Service\UserService;
use App\Entity\MallCoupon;
use App\Entity\MallCouponGroup;
use Doctrine\DBAL\ParameterType;
use XLSXWriter;

class CouponService extends AdminBaseService
{


    protected $paginator;
    protected $userService;
    protected $categoryService;

    public function __construct(PaginatorInterface $paginator,UserService $userService,
                                 CategoryService $categoryService
    )
    {
        $this->paginator = $paginator;
        $this->userService = $userService;
        $this->categoryService = $categoryService;
    }

    public function getList($request, $page, $pageSize){
        $sql = $this->getFormatRequestSql($request);

        $dql = "SELECT a FROM App:MallCouponGroup a " . $sql;
        $em = $this->getDoctrine()->getManager();
        $query = $em->createQuery($dql);
        $pagination = $this->paginator->paginate(
            $query,
            $page,
            $pageSize
        );

        $items = $pagination->getItems();
        $itemsArr = [];
        if($items){
            foreach ($items as $v){
                $vArr =  $this->toArray($v);
                $createrUid = $vArr['createUid'];
                $createrUser = $this->userService->getById($createrUid);
                $vArr['creater'] = $createrUser['fullName'];
                $categoryId = $vArr["categoryId"];
                $cate = $this->categoryService->getById($categoryId);
                $vArr['category'] = $cate["name"];
                $vArr['discount'] = $vArr['discount']/100;

                $itemsArr[] = $vArr;
            }
        }
        return [$pagination, $itemsArr];
    }


    public function add($uid, $name, $couponType, $discount,$countNum, $expirationStartTime,
                        $expirationEndTime, $status, $categoryId, $teachingMethod, $goodsIds, $descr){
        $model = new MallCouponGroup();
        $model->setCreateUid($uid);
        $model->setName($name);
        $model->setCouponType($couponType);
        $model->setDiscount($discount);
        $model->setCountNum($countNum);
        $model->setExpirationStart($expirationStartTime);
        $model->setExpirationEnd($expirationEndTime);
        $model->setStatus($status);
        $model->setCategoryId($categoryId);
        $model->setTeachingMethod($teachingMethod);
        if($goodsIds){
            $goodsIdstr = implode(",", $goodsIds);
            $model->setGoodsIds($goodsIdstr);
        }
        
        $model->setDescr($descr);
        $id = $this->save($model);
        return $id;
    }

    public function switchStatus($id, $state){
       $sql = "SELECT a FROM App:MallCouponGroup a WHERE a.id=:id";
       $model = $this->fetchOne($sql, ['id'=>$id], 1);
       $model->setStatus($state);
       return $this->save($model);
    }

    public function getById($id){
        $sql = "SELECT a FROM App:MallCouponGroup a WHERE a.id=:id";
        $info = $this->fetchOne($sql, ['id'=>$id]);
        if(!$info) return [];
        return $info;
    }

    public function getByName($name, $id=0){
        $sql = "SELECT a FROM App:MallCouponGroup a WHERE a.name=:name";
        $params = [];
        $params['name'] = $name;
        if($id){
            $sql = $sql." AND a.id !=:id ";
            $params['id'] = $id;
        }
        return $this->fetchOne($sql, $params);
    }

    

    public function edit($id, $name, $couponType, $discount,$countNum, $expirationStartTime,
                        $expirationEndTime, $status, $categoryId, $teachingMethod, $goodsIds, $descr){
        $sql = "SELECT a FROM App:MallCouponGroup a WHERE a.id=:id";
        $model = $this->fetchOne($sql, ['id'=>$id], 1);
        $model->setName($name);
        $model->setCouponType($couponType);
        $model->setDiscount($discount);
        $model->setCountNum($countNum);
        $model->setExpirationStart($expirationStartTime);
        $model->setExpirationEnd($expirationEndTime);
        $model->setStatus($status);
        $model->setCategoryId($categoryId);
        $model->setTeachingMethod($teachingMethod);
        if($goodsIds){
            $goodsIdstr = implode(",", $goodsIds);
            $model->setGoodsIds($goodsIdstr);
        }
        $model->setDescr($descr);
        $id = $this->save($model);
        return $id;      
    }

    public function del($id){
        $sql = "DELETE FROM App:MallCoupon a WHERE a.couponGroupId=:couponGroupId";
        $this->execute($sql, ["couponGroupId"=>$id]);

        $sql = "DELETE FROM App:MallCouponGroup a WHERE a.id=:id";
        $this->execute($sql, ["id"=>$id]);
        return true;
    }

    public function getSubList($request, $page, $pageSize, $id){
        $sql = $this->getFormatRequestSql($request);

        $sqlStr = "a.couponGroupId = :couponGroupId";
        if($sql){
            $sqlStr = " AND ". $sqlStr;
        }else{
            $sqlStr = " WHERE ". $sqlStr;
        }
        $dql = "SELECT a FROM App:MallCoupon a " . $sql.$sqlStr;

        $em = $this->getDoctrine()->getManager();
        $query = $em->createQuery($dql);
        $query= $query->setParameters(['couponGroupId'=>$id]);
        $pagination = $this->paginator->paginate(
            $query,
            $page,
            $pageSize
        );

        $items = $pagination->getItems();
        $itemsArr = [];
        if($items){
            foreach ($items as $v){
                $vArr =  $this->toArray($v);
                $vArr['usedTime'] = !$vArr['usedTime']?"-":date('Y-m-d H:i:s', $vArr['usedTime']);
                $vArr['sendTime'] = !$vArr['sendTime']?"-":date('Y-m-d H:i:s', $vArr['sendTime']);
                $itemsArr[] = $vArr;
            }
        }
        return [$pagination, $itemsArr];
    }

    public function sendCoupon($uid, $id){
        $sql = "SELECT a FROM App:MallCoupon a WHERE a.couponGroupId=:couponGroupId AND a.status=:status";
        $detail = $this->fetchOne($sql, ["couponGroupId"=>$id, "status"=>0], 1);
        if($detail){
            $detail->setUid($uid);
            $detail->setSendTime(time());
            $this->save($detail);
            return $detail->getCouponSn();
        }
        return "";
    }

    public function useCoupon($uid, $couponSn){
        $sql = "SELECT a FROM App:MallCoupon a WHERE a.couponSn=:couponSn";
        $detail = $this->fetchOne($sql, ["couponSn"=>$couponSn], 1);
        if(!$detail) return false;
        if($detail->getUid() != $uid) return false;
        if($detail->getStatus() != 0) return false;
        $detail->setUsedTime(time());
        $detail->setStatus(1);
        $this->save($detail);
        return true;
    }

    public function createCoupon($id){
        $sql = "SELECT a FROM App:MallCouponGroup a WHERE a.id=:id";
        $couponGroup = $this->fetchOne($sql, ["id"=>$id]); 
        $countNum = (int) $couponGroup["countNum"];
        $createdNum =  (int) $couponGroup["createdNum"];
        //检查是否已经生成完成
       if($countNum<=$createdNum) return true;
        $this->conn()->getConfiguration()->setSQLLogger(null);
        $em = $this->getDoctrine()->getManager();
        $batchSize = 50;
        $diffNum = $countNum-$createdNum;
        for($i=1;$i<=$diffNum;++$i){
            $setCouponSn = session_create_id("");
            $model = new MallCoupon();
            $model->setCouponGroupId($id);
            $model->setCouponSn($setCouponSn);
            $em->persist($model);
            if (($i % $batchSize) === 0) {
                $em->flush();
                //更新生成数量
                $sql = "UPDATE App:MallCouponGroup a SET a.createdNum=:createdNum WHERE a.id=:id";
                $this->execute($sql,["id"=>$id, "createdNum"=>$i]);
                $em->clear();
            }
           
        }
        $em->flush();
        $sql = "UPDATE App:MallCouponGroup a SET a.createdNum=:createdNum WHERE a.id=:id";
        $n = $i-1;
        $this->execute($sql,["id"=>$id, "createdNum"=>$n]);
        $em->clear();
        return true;
    }

    public function export($id){
        $info = $this->getById($id);
        $sql = "SELECT a FROM App:MallCoupon a WHERE a.couponGroupId=:couponGroupId";
        $list = $this->fetchAll($sql, ["couponGroupId"=>$id]);
        $headers = [
            "优惠券"=>"string",
            "使用时间"=>"YYYY-MM-DD HH:MM:SS",
            "赠送时间"=>"YYYY-MM-DD HH:MM:SS"
        ];
        $styles1 = array( 'font'=>'Arial','font-size'=>10, 'halign'=>'center', 'border'=>'left,right,top,bottom');
        $writer = new XLSXWriter();
        $writer->writeSheetHeader('优惠券', $headers);
        if($list){
            foreach($list as $v){
                $row = [$v["couponSn"], $v['usedTime']?date('Y-m-d H:i:s', $v['usedTime']):"", $v['sendTime']?date('Y-m-d H:i:s', $v['sendTime']):""];
                $writer->writeSheetRow('优惠券', $row, $styles1);
            }
        }
        $basePath = $this->getBasePath()."/var/tmp";
        if(!is_dir($basePath)){
            mkdir($basePath, 0777, true);
        }
        $path = $basePath."/".$info["name"].date("YmdHis").".xlsx";
        $writer->writeToFile($path);
        return  $path;
    }

}
