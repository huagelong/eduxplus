<?php
/**
 * @Author: kaihui.wang
 * @Contact  hpuwang@gmail.com
 * @Version: 1.0.0
 * @Date: 2020/3/22 20:44
 */

namespace App\Bundle\AdminBundle\Controller;


use App\Lib\Base\BaseAdminController;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

/**
 * Class AuthController
 * @package App\Bundle\AdminBundle\Controller
 */
class AuthController extends BaseAdminController
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

        return $this->render('@AdminBundle/auth/login.html.twig', [
            'error' => $error,
            'last_username' => $lastUsername,
        ]);
    }

    /**
     * @Rest\Route("/logout", name="admin_logout")
     */
    public function logout(){
        throw new \Exception('This method can be blank - it will be intercepted by the logout key on your firewall');
    }
}
