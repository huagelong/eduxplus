<?php
/**
 * @Author: kaihui.wang
 * @Contact  hpuwang@gmail.com
 * @Version: 1.0.0
 * @Date: 2020/11/2 15:59
 */

namespace Eduxplus\WebsiteBundle\Controller;


use Eduxplus\CoreBundle\Lib\Base\BaseHtmlController;
use Eduxplus\WebsiteBundle\Service\HelpService;
use Symfony\Component\Routing\Annotation\Route;

class HelpController extends BaseHtmlController
{

    
    public function indexAction($type, $id, HelpService $helpService){
        $data = [];
        $data['id'] = $id;
        $data['type'] = $type;
        $data['route'] = "app_help";
        //0-详情,1-文章列表
        if($type == 0){
            $detail = $helpService->getById($id);
            $data['detail'] = $detail;
            if($id == 0){
                $topValue = $helpService->getAllTopValueHelps();
                $data['topValue'] = $topValue;
            }
            return $this->render('@WebsiteBundle/help/detail.html.twig', $data);
        }
        $details = $helpService->getByCategoryId($id);
        $data['details'] = $details;
        $data['category'] = $helpService->getCategoryById($id);
        return $this->render('@WebsiteBundle/help/index.html.twig', $data);
    }

    
    public function helpNavAction($id,$type, HelpService $helpService){
        $category = $helpService->getCategoryAndHelp();
        $data = [];
        $data['id'] = $id;
        $data['type'] = $type;
        $data['category'] = $category;
        return $this->render('@WebsiteBundle/help/helpNav.html.twig', $data);
    }
}
