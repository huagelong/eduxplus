<?php
/**
 * @Author: kaihui.wang
 * @Contact  hpuwang@gmail.com
 * @Version: 1.0.0
 * @Date: 2020/11/12 16:29
 */

namespace Eduxplus\EduxBundle\Controller\Admin\Jw;


use Eduxplus\CoreBundle\Lib\Grid\Grid;
use Eduxplus\EduxBundle\Service\Jw\ClassService;
use Eduxplus\CoreBundle\Service\UserService;
use Eduxplus\CoreBundle\Lib\Base\BaseAdminController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;


class ClassController extends BaseAdminController
{

    
    public function indexAction(Request $request, Grid $grid, ClassService $classService){
        $pageSize = 40;
        $grid->setListService($classService, "getClassList");
        $grid->text("ID")->field("id")->sort("a.id");
        $grid->text("班级名称")->field("name");
        $grid->text("产品名称")->field("product");
        $grid->text("学习计划")->field("studyPlan");
        $grid->datetime("创建时间")->field("createdAt")->sort("a.createdAt");

        $grid->stext("名称")->field("a.name");
        $grid->sdaterange("创建时间")->field("a.createdAt");

        $grid->setTableAction('admin_jw_class_members', function ($obj) {
            $id = $obj["id"];
            $url = $this->generateUrl('admin_jw_class_members', ['classesId' => $id]);
            $str = '<a href=' . $url . ' data-title="班级成员" title="班级成员" class=" btn btn-default btn-xs"><i class="mdi mdi-account-group"></i></a>';
            return  $str;
        });

        return $this->content()->renderList($grid->create($request, $pageSize));
    }

    
    public function membersAction($classesId, Request $request, Grid $grid, ClassService $classService, UserService $userService){
        $pageSize = 40;
        $grid->setListService($classService, "getMemberList", $classesId);
        $grid->text("ID")->field("id")->sort("a.id");
        $grid->text("学员")->field("user");
        $grid->text("学员类型")->field("type")->sort("a.type")->options([1 => "在学学员", 2 => "退学学员"]);
        $grid->datetime("创建时间")->field("createdAt")->sort("a.createdAt");

        $grid->ssearchselect("学员")->field("a.uid")->options(function () use ($request, $userService) {
            $values = $request->get("values");
            $createUid = ($values && isset($values["a.uid"])) ? $values["a.uid"] : 0;
            if ($createUid) {
                $users = $userService->searchResult($createUid);
            } else {
                $users = [];
            }
            return [$this->generateUrl("admin_api_glob_searchUserDo"), $users];
        });
        $grid->sselect("学员类型")->field("a.type")->options(["在学学员"=>1, "退学学员"=>2]);
        $grid->sdaterange("创建时间")->field("a.createdAt");
        $classInfo = $classService->getById($classesId);
        return $this->content()
            ->breadcrumb($classInfo['name'],"admin_jw_class_index")
            ->title("学员管理")->renderList($grid->create($request, $pageSize));
    }

}
