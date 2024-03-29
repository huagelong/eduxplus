<?php
/**
 * @Author: kaihui.wang
 * @Contact  hpuwang@gmail.com
 * @Version: 1.0.0
 * @Date: 2020/3/28 19:38
 */

namespace Eduxplus\CoreBundle\Controller;


use Eduxplus\CoreBundle\Lib\Grid\Grid;
use Eduxplus\CoreBundle\Service\AdminlogService;
use Eduxplus\CoreBundle\Service\UserService;
use Eduxplus\CoreBundle\Lib\Base\BaseAdminController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

class AdminLogController extends BaseAdminController
{


    
    public function indexAction(Request $request, AdminlogService $adminLogService, Grid $grid, UserService $userService){
        $pageSize = 40;
        $grid->setListService($adminLogService, "getList");
//        $grid->text("ID")->field("id")->sort("a.id");
        $grid->badgeInfo("动作")->field("descr");
//        $grid->text("路由")->field("route");
        $grid->badgeMuted("操作人")->field("fullName");
        $grid->badgePurple("ip")->field("ip");
        $grid->badgeBrown("ip地理")->field("cityName");
        $grid->tip("参数")->field("inputData");
        $grid->datetime("操作时间")->field("createdAt")->sort("a.createdAt");

//        $grid->snumber("ID")->field("a.id");
        $grid->stext("动作")->field("a.descr");
//        $grid->stext("路由")->field("a.route");
        $grid->ssearchselect("操作人")->field("a.uid")->options(function()use($request, $userService){
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


        return $this->content()->renderList($grid->create($request, $pageSize));
    }
}
