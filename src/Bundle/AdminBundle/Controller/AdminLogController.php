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
        $pageSize = 20;
        $grid->setListService($adminLogService, "getList");
        $grid->setTableColumn("#", "text", "id","a.id");
        $grid->setTableColumn("动作", "textarea", "descr");
        $grid->setTableColumn("路由", "text", "route");
        $grid->setTableColumn("操作人", "text", "fullName");
        $grid->setTableColumn("Ip", "text", "ip");
        $grid->setTableColumn("参数", "tip", "inputData");
        $grid->setTableColumn("操作时间", "datetime", "createdAt", "a.createdAt");
        
        $grid->setSearchField("ID", "number", "a.id");
        $grid->setSearchField("动作", "text", "a.descr");
        $grid->setSearchField("路由", "text", "a.route");
        
        $grid->setSearchField("创建人", "search_select", "a.uid", function()use($request, $userService){
            $values = $request->get("values");
            $uid = ($values&&isset($values["a.uid"]))?$values["a.uid"]:0;
            if($uid){
                $users = $userService->searchResult($uid);
            }else{
                $users = [];
            }
            return [$this->generateUrl("admin_api_glob_searchUserDo"),$users];
        });

        $grid->setSearchField("创建时间", "datetimerange", "a.createdAt");

        $data = [];
        $data['list'] = $grid->create($request, $pageSize);
        return $this->render("@AdminBundle/adminlog/index.html.twig", $data);
    }
}
