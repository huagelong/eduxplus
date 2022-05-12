<?php


namespace Eduxplus\CoreBundle\Controller;


use Eduxplus\CoreBundle\Lib\Base\BaseAdminController;
use Eduxplus\CoreBundle\Lib\Grid\Grid;
use Eduxplus\CoreBundle\Service\ScheduleLogService;
use Symfony\Component\HttpFoundation\Request;

class ScheduleLogController extends BaseAdminController
{

    public function indexAction($taskId, Request $request, Grid $grid, ScheduleLogService $scheduleLogService){
        $pageSize = 40;
        $grid->setListService($scheduleLogService, "scheduleLogList",$taskId);
        $grid->text("开始运行时间")->field("startTime")->sort("a.startTime");
        $grid->datetime("运行结束时间")->field("createdAt")->sort("a.createdAt");
        $grid->text("执行信息")->field("runInfo");
        $grid->text("运行类型")->field("runType");
        $grid->text("ip")->field("ip");
        $grid->textarea("任务结果")->field("result");
        $grid->sdaterange("运行结束时间")->field("a.createdAt");

        return $this->content()->renderList($grid->create($request, $pageSize));
    }

}
