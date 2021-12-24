<?php
/**
 * @Author: kaihui.wang
 * @Contact  hpuwang@gmail.com
 * @Version: 1.0.0
 * @Date: 2020/3/26 19:43
 */

namespace Eduxplus\CoreBundle\Controller;


use Eduxplus\CoreBundle\Service\DashboardService;
use Eduxplus\CoreBundle\Service\MenuService;
use Eduxplus\CoreBundle\Lib\Base\BaseAdminController;
use Symfony\Component\Routing\Annotation\Route;
use Psr\Log\LoggerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class IndexController
 * @package Eduxplus\CoreBundle\Controller
 */
class IndexController extends BaseAdminController
{


    /**
     * @Route("/", name="admin_index")
     */
    public function indexAction(){
        return $this->render('@CoreBundle/index/index.html.twig');
    }

    /**
     * @Route("/dashboard", name="admin_dashboard")
     */
    public function dashboardAction(DashboardService $dashboardService){
        list($todayIncome, $todayRegUserCount, $todayOrderCount, $totalRegUserCount) = $dashboardService->dashboardStat();

        $data = [];
        $data["todayIncome"] = number_format($todayIncome, 2);
        $data["todayRegUserCount"] = $todayRegUserCount;
        $data["todayOrderCount"] = $todayOrderCount;
        $data["totalRegUserCount"] = $totalRegUserCount;
        $data['lastOrder'] = $dashboardService->lastOrder();

        return $this->render('@CoreBundle/index/dashboard.html.twig', $data);
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
        return $this->render('@CoreBundle/index/mymenu.html.twig', $data);
    }

}
