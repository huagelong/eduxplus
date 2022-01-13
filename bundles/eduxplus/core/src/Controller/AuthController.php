<?php
/**
 * @Author: kaihui.wang
 * @Contact  hpuwang@gmail.com
 * @Version: 1.0.0
 * @Date: 2020/3/22 20:44
 */

namespace Eduxplus\CoreBundle\Controller;


use Eduxplus\CoreBundle\Lib\Base\BaseAdminController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

/**
 * Class AuthController
 * @package Eduxplus\CoreBundle\Controller
 */

class AuthController extends BaseAdminController
{


    
    public function loginAction(AuthenticationUtils $authenticationUtils, UrlGeneratorInterface $urlGenerator, Request $request){
         if ($this->getUser()) {
             return $this->redirect($urlGenerator->generate('admin_index'));
         }

        $error = $authenticationUtils->getLastAuthenticationError();
        $lastUsername = $authenticationUtils->getLastUsername();
        $cookieLastUserName = $request->cookies->get("last_username");
        $lastUsername = $lastUsername?$lastUsername:$cookieLastUserName;

        return $this->render('@CoreBundle/auth/login.html.twig', [
            'error' => $error,
            'last_username' => $lastUsername,
        ]);
    }

    
    public function logoutAction(){
        throw new \Exception('This method can be blank - it will be intercepted by the logout key on your firewall');
    }
}
