<?php
/**
 * @Author: kaihui.wang
 * @Contact  hpuwang@gmail.com
 * @Version: 1.0.0
 * @Date: 2020/3/4 11:47
 */

namespace Eduxplus\CoreBundle\Lib\Base;


class BaseHtmlController extends BaseController
{
    protected $appBaseService;

    public function __construct(AppBaseService $appBaseService)
    {
        $this->appBaseService = $appBaseService;
    }

    public function getUserByToken($token='', $source='html'){
       return $this->appBaseService->getUserByToken($token, $source);
    }

    public function getUid(){
        $user = $this->getUser();
        if($user) return $user->getId();
        return 0;
    }

    public function showMsg($msg, $redirect=""){
        $data = [];
        $data['msg'] = $msg;
        $data['redirect'] = $redirect;
        return $this->render('@WebsiteBundle/layout/msg.html.twig', $data);
    }
}
