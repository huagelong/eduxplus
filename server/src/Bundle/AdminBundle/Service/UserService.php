<?php
/**
 * @Author: kaihui.wang
 * @Contact  hpuwang@gmail.com
 * @Version: 1.0.0
 * @Date: 2020/4/8 13:43
 */

namespace App\Bundle\AdminBundle\Service;


use App\Bundle\AppBundle\Lib\Base\BaseService;
use Knp\Component\Pager\PaginatorInterface;


class UserService extends BaseService
{

    protected $paginator;

    public function __construct(PaginatorInterface $paginator)
    {
        $this->paginator = $paginator;
    }

    public function userList($request, $page, $pageSize){
        $sql = $this->getFormatRequestSql($request);
        $values = $request->get("values");
        $isAdmin = $values['_isAdmin'];
        if($isAdmin == 1){
            if($sql){
                $sql .= " AND a.roles LIKE '%ROLE_ADMIN%'";
            }else{
                $sql = " WHERE a.roles LIKE '%ROLE_ADMIN%'";
            }
        }elseif($isAdmin == 0){
            if($sql) {
                $sql .= " AND a.roles NOT LIKE '%ROLE_ADMIN%'";
            }else{
                $sql .= " WHERE a.roles NOT LIKE '%ROLE_ADMIN%'";
            }
        }

        $dql = "SELECT a FROM App:BaseUser a " . $sql;
        dump($dql);
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
                $roles = $vArr['roles'];
                $isAdmin  =  in_array("ROLE_ADMIN", $roles);
                $vArr['isAdmin'] = $isAdmin;
                $itemsArr[] = $vArr;
            }
        }
        return [$pagination, $itemsArr];
    }
}
