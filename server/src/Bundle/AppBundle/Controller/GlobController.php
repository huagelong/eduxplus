<?php
/**
 * @Author: kaihui.wang
 * @Contact  hpuwang@gmail.com
 * @Version: 1.0.0
 * @Date: 2020/3/26 14:07
 */

namespace App\Bundle\AppBundle\Controller;

use App\Bundle\AppBundle\Lib\Base\BaseHtmlController;
use App\Bundle\AppBundle\Lib\Service\CaptchaService;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class GlobController extends BaseHtmlController
{

    /**
     * @Rest\Get("/recaptcha/{type}", name="app_glob_recaptcha")
     */
    public function recaptchaAction($type, CaptchaService $captchaService, Request $request){
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

    /**
     * 点播回调网址
     * @Rest\Get("/vodnotify/{type}", name="app_glob_vodnotify")
     */
    public function vodNotifyAction($type='cc', Request $request){
        if($type == 'cc'){
            $data = $request->get("data");
            $kernelSecret = $this->getParameter("kernel.secret");
            if($data!=$kernelSecret){
                $str = '<?xml version="1.0" encoding="UTF-8"?><result>ERROR</result>';
            }else{
                $str = '<?xml version="1.0" encoding="UTF-8"?><result>OK</result>';
            }

            $response = new Response($str);
            $response->headers->set("Content-Type", "text/xml");
            return $response;
        }
    }

}
