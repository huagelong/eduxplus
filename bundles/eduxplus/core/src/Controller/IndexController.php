<?php
/**
 * @Author: kaihui.wang
 * @Contact  hpuwang@gmail.com
 * @Version: 1.0.0
 * @Date: 2020/3/26 19:43
 */

namespace Eduxplus\CoreBundle\Controller;


use DateTime;
use Eduxplus\CoreBundle\Service\MenuService;
use Eduxplus\CoreBundle\Lib\Base\BaseAdminController;
use Illuminate\Support\Arr;
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


    
    public function indexAction(Request $request,MenuService $menuService){
        $route = $request->getSession()->get("_route");
        $uid = $this->getUid();
        $userInfo = $this->getUserInfo();

        $menu = $menuService->getMyMenu($uid, 1);
//        var_dump($menu);
        $pmenuId = $menuService->getParentMenuId($route);
        $data = [];
        $data['menus'] = $menu;
        $data['route'] = $route;
        $data['pmenuId'] = $pmenuId;
        $data['needChangepwd'] = $userInfo["needChangepwd"];
        
        return $this->render('@CoreBundle/index/index.html.twig', $data);
    }


    public function aboutAction(){
        $now = new DateTime();
        $envs = [
            ['name' => 'PHP version',       'value' => 'PHP/'.PHP_VERSION],
            ['name' => 'Symfony version',   'value' => \Symfony\Component\HttpKernel\Kernel::VERSION],
            ['name' => 'CGI',               'value' => php_sapi_name()],
            ['name' => 'Uname',             'value' => php_uname()],
            ['name' => 'Server',            'value' => isset($_SERVER['SERVER_SOFTWARE'])?$_SERVER['SERVER_SOFTWARE']:"-"],
            ['name' => 'Cache driver',      'value' => "redis"],
            ['name' => 'Timezone',          'value' => $now->getTimezone()->getName()],
            ['name' => 'Env',               'value' => $_SERVER['APP_ENV']],
        ];

        $data = [];
        $data["envs"] = $envs;
        return $this->render("@CoreBundle/index/about.html.twig", $data);
    }

}
