<?php
/**
 * @Author: kaihui.wang
 * @Contact  hpuwang@gmail.com
 * @Version: 1.0.0
 * @Date: 2020/3/26 19:43
 */

namespace App\Bundle\AdminBundle\Controller;


use App\Bundle\AppBundle\Lib\Base\BaseAdminController;
use FOS\RestBundle\Controller\Annotations as Rest;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

/**
 * Class IndexController
 * @package App\Bundle\AdminBundle\Controller
 */
class IndexController extends BaseAdminController
{

    /**
     * @Rest\Get("/", name="admin_dashboard")
     */
    public function dashboard(){

        return $this->render('@AdminBundle/index/dashboard.html.twig');
    }

}
