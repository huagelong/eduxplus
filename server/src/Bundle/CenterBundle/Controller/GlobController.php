<?php
/**
 * @Author: kaihui.wang
 * @Contact  hpuwang@gmail.com
 * @Version: 1.0.0
 * @Date: 2020/3/26 14:07
 */

namespace App\Bundle\CenterBundle\Controller;

use App\Bundle\CenterBundle\Lib\Base\BaseHtmlController;
use App\Bundle\CenterBundle\Lib\Service\CaptchaService;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class GlobController extends BaseHtmlController
{

    /**
     * @Rest\Route("/recaptcha/{type}")
     */
    public function recaptcha($type, CaptchaService $captchaService, Request $request){
        $session = $request->getSession();
        list($source, $header) = $captchaService->get($session, $type);

        $response = new Response($source);
        if($header){
            foreach ($header as $v){
                list($k, $v) = explode(":", $v);
                $response->headers->set($k, $v);
            }
        }
        return $response;
    }
}
