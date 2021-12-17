<?php
/**
 * @Author: kaihui.wang
 * @Contact  hpuwang@gmail.com
 * @Version: 1.0.0
 * @Date: 2020/11/12 16:29
 */

namespace Eduxplus\CoreBundle\Controller\Jw;


use Eduxplus\CoreBundle\Lib\Grid\Grid;
use Eduxplus\CoreBundle\Service\Jw\ClassService;
use Eduxplus\CoreBundle\Service\UserService;
use Eduxplus\CoreBundle\Lib\Base\BaseAdminController;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\HttpFoundation\Request;


class ClassController extends BaseAdminController
{

    /**
     * @Rest\Get("/jw/class/index", name="admin_jw_class_index")
     */
    public function indexAction(Request $request, Grid $grid, ClassService $classService){
        $pageSize = 40;
        $grid->setListService($classService, "getClassList");
        $grid->text("#")->field("id")->sort("a.id");
        $grid->text("班级名称")->field("name");
        $grid->text("产品名称")->field("product");
        $grid->text("学习计划")->field("studyPlan");
        $grid->datetime("创建时间")->field("createdAt")->sort("a.createdAt");

        $grid->stext("名称")->field("a.name");
        $grid->sdaterange("创建时间")->field("a.createdAt");

        $grid->setTableAction('admin_jw_class_members', function ($obj) {
            $id = $obj["id"];
            $url = $this->generateUrl('admin_jw_class_members', ['classesId' => $id]);
            $str = '<a href=' . $url . ' data-title="班级成员" title="班级成员" class=" btn btn-default btn-xs"><i class="fas fa-user-friends"></i></a>';
            return  $str;
        });

        $data = [];
        $data['list'] = $grid->create($request, $pageSize);
        return $this->render("@CoreBundle/jw/class/index.html.twig", $data);
    }

    /**
     * @Rest\Get("/jw/class/members/{classesId}", name="admin_jw_class_members")
     */
    public function membersAction($classesId, Request $request, Grid $grid, ClassService $classService, UserService $userService){
        $pageSize = 40;
        $grid->setListService($classService, "getMemberList", $classesId);
        $grid->text("#")->field("id")->sort("a.id");
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

        $data = [];
        $data['list'] = $grid->create($request, $pageSize);
        $data["classInfo"] = $classService->getById($classesId);
        return $this->render("@CoreBundle/jw/class/members.html.twig", $data);
    }

}
