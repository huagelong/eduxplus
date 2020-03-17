<?php

namespace App\Bundle\AppBundle\Controller;

use App\Entity\AdminActionLog;
use App\Entity\BaseUser;
use App\Lib\Base\BaseController;
use App\Lib\Service\TestService;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Controller\Annotations\View as ViewAnnotations;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Swagger\Annotations as SWG;
use Nelmio\ApiDocBundle\Annotation\Model;
use Nelmio\ApiDocBundle\Annotation\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Validator\Constraints\Uuid;

/**
 * @package App\Bundle\AppBundle\Controller
 */
class AuthController extends BaseController
{
    /**
     * @Rest\Post("/login")
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
        $mobile = "17621487072";
        $password="111111";
        return $this->redirectToRoute('appApi_loginCheck', [
            'mobile' => $mobile,
            'password' => $password
        ], 307);
    }


    /**
     * @Rest\Post("/register")
     * @ViewAnnotations()
     * @return bool
     */
    public function register(){
        return true;
    }

    /**
     * @Rest\Get("/test")
     * @ViewAnnotations()
     * @param Request $request
     * @param TestService $testService
     * @return array
     */
    public function test(Request $request, TestService $testService,  UserPasswordEncoderInterface $passwordEncoder)
    {
        $model = new BaseUser();
        $pwd = $passwordEncoder->encodePassword($model, "111111");
        $model->setSex(1);
        $model->setBirthday(date('Y-m-d'));
        $model->setRegSource("web");
        $model->setMobile("17621487077");
        $model->setReportUid(0);
        $model->setDisplayName("超级管梨园");
        $model->setFullName("汪鑫远");
        $model->setRoles(["ROLE_ADMIN"]);
        $model->setPassword($pwd);
        $this->insert($model);
        $rs = $this->fetchOneByDql("SELECT p FROM App:BaseUser p WHERE p.id >= :id",["id"=>1]);
        var_dump($rs);
        var_dump($this->getSQL());

        $name = $request->get("testName");
//        $version = $request->headers->get("X-Accept-Version");
        $version = $testService->show();
        $data=["name"=>$name."-".$version];
//        echo 1/0;
//        return $this->render("bundle/app_bundle/controller/user/test.html.twig", $data);
        return $data;
    }


    /**
     * 退出
     * @Rest\Get("/logout")
     * @ViewAnnotations()
     */
    public function logout(){

    }




}
