<?php
/**
 * @Author: kaihui.wang
 * @Contact  hpuwang@gmail.com
 * @Version: 1.0.0
 * @Date: 2020/3/28 19:38
 */

namespace App\Bundle\AdminBundle\Controller;


use App\Bundle\AdminBundle\Lib\Grid\Grid;
use App\Bundle\AdminBundle\Service\AdminlogService;
use App\Bundle\AdminBundle\Service\UserService;
use App\Bundle\AppBundle\Lib\Base\BaseAdminController;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\HttpFoundation\Request;

class AdminLogController extends BaseAdminController
{


    /**
     * @Rest\Get("/adminlog/index", name="admin_adminlog_index")
     */
    public function indexAction(Request $request, AdminlogService $adminLogService, Grid $grid, UserService $userService){
        $pageSize = 40;
        $grid->setListService($adminLogService, "getList");
        $grid->text("#")->field("id")->sort("a.id");
        $grid->textarea("动作")->field("descr");
//        $grid->text("路由")->field("route");
        $grid->text("操作人")->field("fullName");
        $grid->text("Ip")->field("ip");
        $grid->tip("参数")->field("inputData");
        $grid->datetime("操作时间")->field("createdAt")->sort("a.createdAt");

        $grid->snumber("ID")->field("a.id");
        $grid->stext("动作")->field("a.descr");
        $grid->stext("路由")->field("a.route");
        $grid->ssearchselect("创建人")->field("a.uid")->options(function()use($request, $userService){
            $values = $request->get("values");
            $uid = ($values&&isset($values["a.uid"]))?$values["a.uid"]:0;
            if($uid){
                $users = $userService->searchResult($uid);
            }else{
                $users = [];
            }
            return [$this->generateUrl("admin_api_glob_searchAdminUserDo"),$users];
        });

        $grid->sdatetimerange("创建时间")->field("a.createdAt");

        $data = [];
        $data['list'] = $grid->create($request, $pageSize);
        return $this->render("@AdminBundle/adminlog/index.html.twig", $data);
    }
}
