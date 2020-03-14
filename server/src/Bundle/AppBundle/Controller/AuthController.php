<?php

namespace App\Bundle\AppBundle\Controller;

use App\Entity\AdminActionLog;
use App\Lib\Base\BaseController;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Controller\Annotations\View as ViewAnnotations;
use Swagger\Annotations as SWG;
use Nelmio\ApiDocBundle\Annotation\Model;
use Nelmio\ApiDocBundle\Annotation\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/auth")
 * @package App\Bundle\AppBundle\Controller
 */
class AuthController extends BaseController
{
    /**
     * @Rest\Get("/login")
     * @ViewAnnotations()
     *
     * @SWG\Tag(name="用户")
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
     */
    public function login(Request $request)
    {
        $model = new AdminActionLog();
        $model->setIp(time());
        $model->setUid(11);
        $model->setInputData(time());
        $model->setRoute("/");
        $entityManage = $this->getDoctrine()->getManager();
        $entityManage->persist($model);
        $entityManage->flush();

        $name = $request->get("testName");
        $version = $request->headers->get("X-Accept-Version");
        $data=["name"=>$name."-".$version];
        return $data;
    }


    /**
     * 登录检查
     * @Rest\Post("/loginCheck")
     * @ViewAnnotations()
     */
    public function loginCheck(){

    }

    /**
     * 退出
     * @Rest\Get("/logout")
     * @ViewAnnotations()
     */
    public function logout(){

    }




}
