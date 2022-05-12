<?php


namespace Eduxplus\CoreBundle\Controller;


use Eduxplus\CoreBundle\Lib\Base\BaseAdminController;
use Eduxplus\CoreBundle\Lib\Grid\Grid;
use Eduxplus\CoreBundle\Service\ScheduleService;
use Symfony\Component\HttpFoundation\Request;

class ScheduleController extends BaseAdminController
{

    public function indexAction(Request $request, Grid $grid, ScheduleService $scheduleService){
        $pageSize = 40;
        $grid->setListService($scheduleService, "scheduleList");
        $grid->text("描述")->field("descr");
        $grid->text("任务类型")->field("type");
        $grid->text("表达式")->field("expression");
        $grid->text("下一次运行时间")->field("nextRun");
        $grid->text("时区")->field("timeZone");
        $grid->boole2("状态")->field("status")->actionCall("admin_api_schedule_switch_status", function ($obj) use($scheduleService) {
            $id = $scheduleService->getPro($obj, "id");
            $defaultValue = $scheduleService->getPro($obj, "status");
            $url = $this->generateUrl('admin_api_schedule_switch_status', ['id' => $id]);
            $checkStr = $defaultValue ? "checked" : "";
            $str = "<input type=\"checkbox\" data-bootstrap-switch-ajaxput href=\"{$url}\" data-confirm=\"确认切换当前状态吗?\" {$checkStr} >";
            return $str;
        });

        $grid->datetime("创建时间")->field("createdAt")->sort("a.createdAt");

        $grid->setTableAction('admin_schedule_log_index', function ($obj) use($scheduleService){
            $id = $scheduleService->getPro($obj, "taskId");
            $url = $this->generateUrl('admin_schedule_log_index', ['taskId' => $id]);
            $str = '<a href="javascript:;" data-url=' . $url . '  data-title="任务日志"  title="任务日志" class=" btn btn-info btn-xs newTab"><i class="mdi mdi-clipboard-text"></i>任务日志</a>';
            return  $str;
        });

        $grid->setGridBar("admin_schedule_import","导入任务",
            $this->generateUrl('admin_schedule_import'),"mdi mdi-database-import", "btn-warning ajaxGet");

        $grid->snumber("ID")->field("a.id");
        $grid->stext("描述")->field("a.descr");
        $grid->sselect("状态？")->field("a.isAdmin")->options(function () {
            return ["全部" => -1, "开启" => 1, "关闭" => 0];
        });
        $grid->sdaterange("创建时间")->field("a.createdAt");


        return $this->content()->renderList($grid->create($request, $pageSize));
    }


    public function switchStatusAction($id, Request $request, ScheduleService $scheduleService){
        $state = (int) $request->get("state");
        $scheduleService->switchStatus($id, $state);
        $info = $scheduleService->getById($id);
        $cacheKey = "scheduleService_hasClose_".$info['taskId'];
        $scheduleService->cache()->delete($cacheKey);
        return $this->responseMsgRedirect("操作成功!");
    }


    public function importAction(ScheduleService $scheduleService){
        $scheduleService->import();
        return $this->responseMsgRedirect("操作成功!");
    }


}
