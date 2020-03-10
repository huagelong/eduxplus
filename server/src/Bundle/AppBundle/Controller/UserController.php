<?php

namespace App\Bundle\AppBundle\Controller;

use App\Bundle\CenterBundle\Lib\Base\BaseController;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Controller\Annotations\View as ViewAnnotations;
use FOS\RestBundle\View\View;
use FOS\RestBundle\Context\Context;
use Symfony\Component\HttpFoundation\Request;

/**
 * @package App\Bundle\AppBundle\Controller
 */
class UserController extends BaseController
{
    /**
     * @Rest\Post("/login")
     * @ViewAnnotations()
     */
    public function login(Request $request)
    {
        $name = $request->get("testName");
        $version = $request->headers->get("X-Accept-Version");
        $data=["name"=>$name."-".$version];

        $view = View::create();
        $context = new Context();
        $context->setVersion('1.0');
        $context->addGroup('user');
        $view->setContext($context);

        $view->setData($data)->setTemplateData($data);

        return $view ;
    }
}
