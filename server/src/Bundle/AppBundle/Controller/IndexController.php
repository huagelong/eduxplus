<?php

namespace App\Bundle\AppBundle\Controller;

use App\Bundle\CenterBundle\Lib\Base\BaseApiController;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Controller\Annotations\View as ViewAnnotations;

/**
 * @Rest\Route("/api")
 * @package App\Bundle\AppBundle\Controller
 */
class IndexController extends BaseApiController
{
    /**
     * @Rest\Post("/")
     * @ViewAnnotations()
     */
    public function index()
    {
        return ["hello app!"];
    }

}
