<?php
/**
 * @Author: kaihui.wang
 * @Contact  hpuwang@gmail.com
 * @Version: 1.0.0
 * @Date: 2020/3/22 20:44
 */

namespace App\Bundle\AdminBundle\Controller;


use App\Lib\Base\BaseEsAdminController;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

/**
 * @Rest\Route("/admin")
 * Class AuthController
 * @package App\Bundle\AdminBundle\Controller
 */
class AuthController extends BaseEsAdminController
{


    /**
     * @Rest\Route("/login", name="admin_login")
     */
    public function login(Request $request,AuthenticationUtils $authenticationUtils){

         if ($this->getUser()) {
             return $this->redirect("/admin/");
         }

        $error = $authenticationUtils->getLastAuthenticationError();
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('@EasyAdmin/page/login.html.twig', [
            'error' => $error,
            'last_username' => $lastUsername,
            'csrf_token_intention' => 'authenticate',
            'username_label' => '手机号码',
            'password_label' => '密码',
            'sign_in_label' => '登录',
            'username_parameter' => 'mobile',
            'password_parameter' => 'password',
        ]);
    }

    /**
     * @Rest\Route("/logout", name="admin_logout")
     */
    public function logout(){
        throw new \Exception('This method can be blank - it will be intercepted by the logout key on your firewall');
    }
}
