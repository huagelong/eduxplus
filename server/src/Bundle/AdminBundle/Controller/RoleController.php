<?php
/**
 * @Author: kaihui.wang
 * @Contact  hpuwang@gmail.com
 * @Version: 1.0.0
 * @Date: 2020/3/28 19:38
 */

namespace App\Bundle\AdminBundle\Controller;


use App\Bundle\AppBundle\Lib\Base\BaseAdminController;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\HttpFoundation\Request;

class RoleController extends BaseAdminController
{


    /**
     * @Rest\Get("/role/index", name="admin_role_index")
     */
    public function indexAction(Request $request){
        return $this->render("@AdminBundle/role/index.html.twig");
    }

}
