<?php
/**
 * @Author: kaihui.wang
 * @Contact  hpuwang@gmail.com
 * @Version: 1.0.0
 * @Date: 2020/5/7 11:17
 */

namespace App\Bundle\AdminBundle\Service\Mall;


use App\Bundle\AppBundle\Lib\Base\BaseService;
use Knp\Component\Pager\PaginatorInterface;
use App\Bundle\AdminBundle\Service\Teach\CategoryService;
use App\Bundle\AdminBundle\Service\UserService;

class OrderService extends BaseService
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

        $dql = "SELECT a FROM App:MallOrder a " . $sql;
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

}
