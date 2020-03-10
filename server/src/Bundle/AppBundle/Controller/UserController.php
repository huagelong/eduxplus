<?php

namespace App\Bundle\AppBundle\Controller;

use App\Bundle\CenterBundle\Lib\Base\BaseController;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Controller\Annotations\View;
use Symfony\Component\HttpFoundation\Request;

/**
 * @package App\Bundle\AppBundle\Controller
 */
class UserController extends BaseController
{
    /**
     * @Rest\Post("/login")
     * @View()
     */
    public function login(Request $request)
    {
        $name = $request->get("testName");

        $data=["name"=>$name];
        return $data ;
    }
}
