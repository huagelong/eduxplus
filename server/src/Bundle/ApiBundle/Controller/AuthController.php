<?php

namespace App\Bundle\ApiBundle\Controller;

use App\Entity\BaseUser;
use App\Bundle\CenterBundle\Lib\Base\BaseApiController;
use App\Bundle\CenterBundle\Lib\Service\HelperService;
use App\Repository\BaseUserRepository;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Controller\Annotations\View as ViewAnnotations;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * @Rest\Route("/api")
 * @package App\Bundle\ApiBundle\Controller
 */
class AuthController extends BaseApiController
{
    /**
     * @Rest\Post("/login")
     * @ViewAnnotations()
     *
     */
    public function login(Request $request,  UserPasswordEncoderInterface $passwordEncoder, BaseUserRepository $baseUserRepository)
    {

        $mobile  = $request->get("mobile");

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
    public function test(Request $request,
                         UserPasswordEncoderInterface $passwordEncoder,
                        HelperService $helperService
    )
    {
        $model = new BaseUser();
        $uuid = $helperService->getUuid();
        $originaRefreshToken = $helperService->getUuid();
        $refreshToken = $passwordEncoder->encodePassword($model, $originaRefreshToken);
        $pwd = $passwordEncoder->encodePassword($model, "111111");

        $model->setSex(1);
        $model->setBirthday(date('Y-m-d'));
        $model->setRegSource("web");
        $model->setMobile("17621487072");
        $model->setRefreshToken($refreshToken);
        $model->setOriginalRefreshToken($originaRefreshToken);
        $model->setReportUid(0);
        $model->setUuid($uuid);
        $model->setDisplayName("超级管梨园");
        $model->setFullName("汪鑫远");
        $model->setRoles(["ROLE_ADMIN"]);
        $model->setPassword($pwd);
        $this->insert($model);
        $rs = $this->fetchOneByDql("SELECT p FROM App:BaseUser p WHERE p.id >= :id",["id"=>1]);
        var_dump($rs);

        $name = $request->get("testName");
//        $version = $request->headers->get("X-Accept-Version");
        $version = $helperService->getUuid();
        $data=["name"=>$name."-".$version];
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
