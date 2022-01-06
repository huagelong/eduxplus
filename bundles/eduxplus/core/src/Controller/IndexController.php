<?php
/**
 * @Author: kaihui.wang
 * @Contact  hpuwang@gmail.com
 * @Version: 1.0.0
 * @Date: 2020/3/26 19:43
 */

namespace Eduxplus\CoreBundle\Controller;


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
    public function indexAction(Request $request,MenuService $menuService){
        $route = $request->getSession()->get("_route");
        $uid = $this->getUid();

        $menu = $menuService->getMyMenu($uid, 1);
//        var_dump($menu);
        $pmenuId = $menuService->getParentMenuId($route);
        $data = [];
        $data['menus'] = $menu;
        $data['route'] = $route;
        $data['pmenuId'] = $pmenuId;
        return $this->render('@CoreBundle/index/index.html.twig', $data);
    }


    /**
     * 导航用
     * @Route("/mymenu", name="admin_mymenu")
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
