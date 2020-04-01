<?php

namespace App\Bundle\ApiBundle\Controller;

use App\Entity\BaseUser;
use App\Bundle\AppBundle\Lib\Base\BaseApiController;
use App\Bundle\AppBundle\Lib\Service\HelperService;
use App\Repository\BaseUserRepository;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Controller\Annotations\View as ViewAnnotations;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
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
     * 退出
     * @Rest\Get("/logout")
     * @ViewAnnotations()
     */
    public function logout(){

    }




}
