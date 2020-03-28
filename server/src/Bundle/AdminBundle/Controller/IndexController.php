<?php
/**
 * @Author: kaihui.wang
 * @Contact  hpuwang@gmail.com
 * @Version: 1.0.0
 * @Date: 2020/3/26 19:43
 */

namespace App\Bundle\AdminBundle\Controller;


use App\Bundle\AdminBundle\Service\MenuService;
use App\Bundle\AppBundle\Lib\Base\BaseAdminController;
use FOS\RestBundle\Controller\Annotations as Rest;
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
     * @Rest\Get("/", name="admin_dashboard")
     */
    public function dashboardAction(){

        return $this->render('@AdminBundle/index/dashboard.html.twig');
    }


    /**
     * 导航用
     * @param Request $request
     * @param MenuService $menuService
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function mymenuAction(Request $request,MenuService $menuService){
        $route = $request->getSession()->get("_route");
        $user = $this->getUser();
        $uid = $user->getId();

        $menu = $menuService->getMyMenu($uid);
        $pmenuId = $menuService->getParentMenuId($route);
        $data = [];
        $data['menus'] = $menu;
        $data['route'] = $route;
        $data['pmenuId'] = $pmenuId;
        dump($data);
        return $this->render('@AdminBundle/index/mymenu.html.twig', $data);
    }

}
