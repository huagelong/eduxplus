<?php
/**
 * @Author: kaihui.wang
 * @Contact  hpuwang@gmail.com
 * @Version: 1.0.0
 * @Date: 2020/4/4 11:07
 */

namespace App\Bundle\AdminBundle\Service;


use App\Bundle\AppBundle\Lib\Base\BaseService;
use App\Entity\BaseRole;
use App\Entity\BaseRoleMenu;
use Knp\Component\Pager\PaginatorInterface;

class AdminlogService extends BaseService
{

    protected $paginator;
    protected $userService;

    public function __construct(PaginatorInterface $paginator, UserService $userService)
    {
        $this->paginator = $paginator;
        $this->userService = $userService;
    }

    public function getList($request, $page, $pageSize){
        $sql = $this->getFormatRequestSql($request);

        $dql = "SELECT a FROM App:BaseAdminLog a " . $sql;
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
                $uid = $vArr['uid'];
                $user = $this->userService->getById($uid);
                $vArr['fullName'] = $user['fullName'];
                $itemsArr[] = $vArr;
            }
        }
        return [$pagination, $itemsArr];

        return $pagination;
    }
}
