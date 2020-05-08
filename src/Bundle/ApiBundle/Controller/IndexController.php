<?php

namespace App\Bundle\ApiBundle\Controller;

use App\Bundle\AppBundle\Lib\Base\BaseApiController;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Controller\Annotations\View as ViewAnnotations;

/**
 * @package App\Bundle\ApiBundle\Controller
 */
class IndexController extends BaseApiController
{
    /**
     * @Rest\Post("/")
     */
    public function index()
    {
        return ["hello app!"];
    }

}
