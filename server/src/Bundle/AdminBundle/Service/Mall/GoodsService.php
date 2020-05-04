<?php
/**
 * @Author: kaihui.wang
 * @Contact  hpuwang@gmail.com
 * @Version: 1.0.0
 * @Date: 2020/4/12 16:06
 */

namespace App\Bundle\AdminBundle\Service\Mall;


use App\Bundle\AdminBundle\Service\Teach\AgreementService;
use App\Bundle\AdminBundle\Service\Teach\CategoryService;
use App\Bundle\AdminBundle\Service\Teach\ProductService;
use App\Bundle\AdminBundle\Service\UserService;
use App\Bundle\AppBundle\Lib\Base\BaseService;
use App\Entity\MallGoods;
use App\Entity\MallGoodsGroup;
use Knp\Component\Pager\PaginatorInterface;

class GoodsService extends BaseService
{
    protected $paginator;
    protected $userService;
    protected $productService;
    protected $categoryService;
    protected $agreementService;

    public function __construct(PaginatorInterface $paginator,UserService $userService,
                                ProductService $productService, CategoryService $categoryService,
                                AgreementService $agreementService
    )
    {
        $this->paginator = $paginator;
        $this->userService = $userService;
        $this->productService = $productService;
        $this->categoryService = $categoryService;
        $this->agreementService = $agreementService;
    }

    public function getList($request, $page, $pageSize){
        $sql = $this->getFormatRequestSql($request);

        $dql = "SELECT a FROM App:MallGoods a " . $sql;
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
                $createrUid = $vArr['createrUid'];
                $createrUser = $this->userService->getById($createrUid);
                $vArr['creater'] = $createrUser['fullName'];
                $categoryId = $vArr["categoryId"];
                $cate = $this->categoryService->getById($categoryId);
                $path = trim($cate['findPath'], ',');
                $pathArr = explode(",", $path);
                $brandId = end($pathArr);
                $vArr['category'] = $cate["name"];
                $brandInfo = $this->categoryService->getById($brandId);
                $vArr['brand'] = $brandInfo["name"];
                $vArr['marketPrice'] = $vArr['marketPrice']/100;
                $vArr['shopPrice'] = $vArr['shopPrice']/100;
                $agreementId = $vArr['agreementId'];
                $agreement = $this->agreementService->getById($agreementId);
                $vArr['agreement'] = $agreement['name'];

                $goodsImg = $vArr['goodsImg'];
                $goodsImgArr = $goodsImg?current(json_decode($goodsImg, true)):"";
                $vArr['goodsImg'] = $goodsImgArr;

                $goodsSmallImg = $vArr['goodsSmallImg'];
                $goodsSmallImgArr = $goodsSmallImg?current(json_decode($goodsSmallImg, true)):"";
                $vArr['goodsSmallImg'] = $goodsSmallImgArr;

                $itemsArr[] = $vArr;
            }
        }
        return [$pagination, $itemsArr];
    }

    public function checkName($name, $id=0){
        $sql = "SELECT a FROM App:MallGoods a where a.name =:name ";
        $params = [];
        $params['name'] = $name;
        if($id){
            $sql = $sql." AND a.id !=:id ";
            $params['id'] = $id;
        }
        return $this->fetchOne($sql, $params);
    }


    public function add($uid, $name, $productId, $goodsId, $categoryId, $subhead,
                        $teachingMethod, $teachers, $courseHour, $courseCount,
                        $marketPrice,$shopPrice,$buyNumberFalse, $goodsImg,
                        $goodsSmallImg, $status, $sort, $agreementId, $groupType){
        $cate = $this->categoryService->getById($categoryId);
        var_dump($cate);
        $path = trim($cate['findPath'], ',');
        $pathArr = explode(",", $path);
        $brandId = end($pathArr);

        $model = new MallGoods();
        $model->setCreaterUid($uid);
        $model->setName($name);
        $model->setSort($sort);
        if($productId) $model->setProductId($productId);
        $model->setFirstCategoryId($brandId);
        $model->setCategoryId($categoryId);
        $model->setAgreementId($agreementId);
        if($subhead) $model->setSubhead($subhead);
        $model->setTeachingMethod($teachingMethod);
        $model->setTeachingTeacher(json_encode($teachers));
        $model->setCourseHour($courseHour);
        $model->setCourseCount($courseCount);
        $model->setMarketPrice($marketPrice*100);
        $model->setShopPrice($shopPrice*100);
        $model->setBuyNumberFalse($buyNumberFalse);
        $model->setBuyNumber(0);
        if($goodsImg) $model->setGoodsImg($goodsImg);
        if($goodsSmallImg) $model->setGoodsSmallImg($goodsSmallImg);
        $model->setStatus($status);
        //
        if($goodsId){
            $model->setGroupType($groupType);
            $model->setIsGroup(1);
        }else{
            $model->setGroupType(0);
            $model->setIsGroup(0);

        }
        $id = $this->save($model);

        if($goodsId && $id){
            foreach ($goodsId as $gid){
                $goodsModel = new MallGoodsGroup();
                $goodsModel->setGoodsId($id);
                $goodsModel->setGroupGoodsId($gid);
                $this->save($goodsModel);
            }
        }

        return $id;
    }

    public function getById($id){
        $sql = "SELECT a FROM App:MallGoods a WHERE a.id=:id";
        $info = $this->fetchOne($sql, ['id'=>$id]);
        return $info;
    }

    public function getByName($name, $id=0){
        $sql = "SELECT a FROM App:MallGoods a WHERE a.name=:name";
        $params = [];
        $params['name'] = $name;
        if($id){
            $sql = $sql." AND a.id !=:id ";
            $params['id'] = $id;
        }
        return $this->fetchOne($sql, $params);
    }

    public function getGroupGoods($id){
        $sql = "SELECT a FROM App:MallGoodsGroup a WHERE a.goodsId=:goodsId";
        $params = [];
        $params['goodsId'] = $id;
        $allGoodIds = $this->fetchFields("groupGoodsId", $sql, $params);
        if(!$allGoodIds) return [];
        return $allGoodIds;
    }

    public function getSelectGoods($id){
        $sql = "SELECT a FROM App:MallGoodsGroup a WHERE a.goodsId=:goodsId";
        $params = [];
        $params['goodsId'] = $id;
        $allGoodIds = $this->fetchFields("groupGoodsId", $sql, $params);
        if(!$allGoodIds) return [];
        $sql2 = "SELECT a FROM App::MallGoods a WHERE a.id IN(:id)";
        $params2 = [];
        $params2['id'] = $allGoodIds;
        $allGoods =  $this->fetchAll($sql2, $params2);
        $tmp = [];
        foreach ($allGoods as $v){
            $tmp[$v["name"]] = $v["id"];
        }
        return $tmp;
    }

    public function edit($id, $name, $productId, $goodsId, $categoryId, $subhead,
                         $teachingMethod, $teachers, $courseHour, $courseCount,
                         $marketPrice,$shopPrice,$buyNumberFalse, $goodsImg,
                         $goodsSmallImg, $status, $sort, $agreementId, $groupType){
        $cate = $this->categoryService->getById($categoryId);
        $path = trim($cate['findPath'], ',');
        $pathArr = explode(",", $path);
        $brandId = end($pathArr);

        $sql= "SELECT a FROM App:MallGoods a WHERE a.id=:id";
        $model = $this->fetchOne($sql, ['id'=>$id], 1);

        $model->setName($name);
        $model->setSort($sort);
        if($productId) $model->setProductId($productId);
        $model->setFirstCategoryId($brandId);
        $model->setCategoryId($categoryId);
        $model->setAgreementId($agreementId);
        if($subhead) $model->setSubhead($subhead);
        $model->setTeachingMethod($teachingMethod);
        $model->setTeachingTeacher(json_encode($teachers));
        $model->setCourseHour($courseHour);
        $model->setCourseCount($courseCount);
        $model->setMarketPrice($marketPrice*100);
        $model->setShopPrice($shopPrice*100);
        $model->setBuyNumberFalse($buyNumberFalse);
        $model->setBuyNumber(0);
        if($goodsImg) $model->setGoodsImg($goodsImg);
        if($goodsSmallImg) $model->setGoodsSmallImg($goodsSmallImg);
        $model->setStatus($status);
        //
        if($goodsId){
            $model->setGroupType($groupType);
            $model->setIsGroup(1);
        }else{
            $model->setGroupType(0);
            $model->setIsGroup(0);

        }
        $this->save($model);

        if($goodsId && $id){
            $dql = "DELETE FROM App:MallGoodsGroup a WHERE a.goodsId=:goodsId";
            $this->hardExecute($dql, ["goodsId"=>$id]);
            foreach ($goodsId as $gid){
                $goodsModel = new MallGoodsGroup();
                $goodsModel->setGoodsId($id);
                $goodsModel->setGroupGoodsId($gid);
                $this->save($goodsModel);
            }
        }

        return $id;

    }

    public function del($id){
        $sql = "SELECT a FROM App:MallGoods a WHERE a.id=:id";
        $model = $this->fetchOne($sql, ['id'=>$id] ,1 );
        return $model?$this->delete($model):false;
    }

    /**
     * 如果被人使用的话，不让删除
     *
     * @param $id
     * @return mixed
     */
   public function hasGroup($id){
       $sql = "SELECT a FROM App:MallGoodsGroup a WHERE a.groupGoodsId=:groupGoodsId";
       return $this->fetchOne($sql, ['groupGoodsId'=>$id] );
   }

   public function searchGoodsName($kw){
       $sql = "SELECT a FROM App:MallGoods a WHERE a.name like :goodsName AND a.status=1 ";
       $params = [];
       $params['goodsName'] = "%".$name."%";
       $all = $this->fetchAll($sql, $params);
       if(!$all) return [];
       $rs = [];
       foreach ($all as $v){
           $tmp = [];
           $tmp['id'] = $v['id'];
           $tmp['text'] = $v['name'];
           $rs[] = $tmp;
       }
       return $rs;
   }

}
