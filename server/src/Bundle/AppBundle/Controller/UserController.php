<?php

namespace App\Bundle\AppBundle\Controller;

use App\Bundle\CenterBundle\Lib\Base\BaseController;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Controller\Annotations\View as ViewAnnotations;
use Swagger\Annotations as SWG;
use Nelmio\ApiDocBundle\Annotation\Model;
use Nelmio\ApiDocBundle\Annotation\Security;
use Symfony\Component\HttpFoundation\Request;

/**
 * @package App\Bundle\AppBundle\Controller
 */
class UserController extends BaseController
{
    /**
     * @Rest\Post("/login")
     * @ViewAnnotations()
     *
     * @SWG\Response(
     *     response=200,
     *     description="Returned when the register is successful",
     *     @SWG\Schema(
     *         type="object",
     *         example={"name":"wang-v1.0"}
     *     )
     * ),
     * @SWG\Parameter(
     *     name="X-Accept-Version",
     *     in="header",
     *     type="string",
     *     description="X-Accept-Version",
     *     required=true
     * ),
     *
     * @SWG\Parameter(
     *     name="login api",
     *     in="body",
     *     type="string",
     *     description="user login",
     *     required=true,
     *     @SWG\Schema(type="object",
     *          @SWG\Property(property="testName", description="testName", type="string"),
     *          required={
     *               "testName"
     *          }
     *     ),
     * )
     * @SWG\Tag(name="Login")
     */
    public function login(Request $request)
    {
        $name = $request->get("testName");
        $version = $request->headers->get("X-Accept-Version");
        $data=["name"=>$name."-".$version];
        return $data;
    }
}
