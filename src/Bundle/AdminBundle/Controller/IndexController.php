<?php
/**
 * @Author: kaihui.wang
 * @Contact  hpuwang@gmail.com
 * @Version: 1.0.0
 * @Date: 2020/3/26 19:43
 */

namespace App\Bundle\AdminBundle\Controller;


use App\Bundle\AdminBundle\Service\DashboardService;
use App\Bundle\AdminBundle\Service\MenuService;
use App\Bundle\AppBundle\Lib\Base\BaseAdminController;
use FOS\RestBundle\Controller\Annotations as Rest;
use Psr\Log\LoggerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class IndexController
 * @package App\Bundle\AdminBundle\Controller
 */
class IndexController extends BaseAdminController
{


    /**
     * @Rest\Get("/", name="admin_index")
     */
    public function indexAction(){
        return $this->render('@AdminBundle/index/index.html.twig');
    }

    /**
     * @Rest\Get("/dashboard", name="admin_dashboard")
     */
    public function dashboardAction(DashboardService $dashboardService){
        list($todayIncome, $todayRegUserCount, $todayOrderCount, $totalRegUserCount) = $dashboardService->dashboardStat();

        $data = [];
        $data["todayIncome"] = number_format($todayIncome, 2);
        $data["todayRegUserCount"] = $todayRegUserCount;
        $data["todayOrderCount"] = $todayOrderCount;
        $data["totalRegUserCount"] = $totalRegUserCount;
        $data['lastOrder'] = $dashboardService->lastOrder();

        return $this->render('@AdminBundle/index/dashboard.html.twig', $data);
    }


    /**
     * 导航用
     * @param Request $request
     * @param MenuService $menuService
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function mymenuAction(Request $request,MenuService $menuService){
        $route = $request->getSession()->get("_route");
        $uid = $this->getUid();

        $menu = $menuService->getMyMenu($uid, 1);
//        var_dump($menu);
        $pmenuId = $menuService->getParentMenuId($route);
        $data = [];
        $data['menus'] = $menu;
        $data['route'] = $route;
        $data['pmenuId'] = $pmenuId;
        return $this->render('@AdminBundle/index/mymenu.html.twig', $data);
    }

}
