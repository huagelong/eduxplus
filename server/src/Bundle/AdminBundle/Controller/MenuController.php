<?php
/**
 * @Author: kaihui.wang
 * @Contact  hpuwang@gmail.com
 * @Version: 1.0.0
 * @Date: 2020/3/28 19:38
 */

namespace App\Bundle\AdminBundle\Controller;


use App\Bundle\AdminBundle\Service\MenuService;
use App\Bundle\AppBundle\Lib\Base\BaseAdminController;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\HttpFoundation\Request;

class MenuController extends BaseAdminController
{

    /**
     * @Rest\Get("/menu/index", name="admin_menu_index")
     */
    public function indexAction(Request $request, MenuService $menuService){

        $data = [];
        $data['allMenu'] = $menuService->getAllMenu();


        return $this->render("@AdminBundle/menu/index.html.twig", $data);
    }
}
