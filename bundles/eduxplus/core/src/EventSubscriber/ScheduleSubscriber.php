<?php


namespace Eduxplus\CoreBundle\EventSubscriber;


use Eduxplus\CoreBundle\Service\ScheduleLogService;
use Eduxplus\CoreBundle\Service\ScheduleService;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Zenstruck\ScheduleBundle\Event\AfterTaskEvent;
use Zenstruck\ScheduleBundle\Event\BeforeTaskEvent;
use Zenstruck\ScheduleBundle\Event\BuildScheduleEvent;

class ScheduleSubscriber implements EventSubscriberInterface
{

    protected $scheduleService;
    private $scheduleLogService;

    public function __construct(ScheduleService $scheduleService, ScheduleLogService $scheduleLogService)
    {
        $this->scheduleService = $scheduleService;
        $this->scheduleLogService = $scheduleLogService;
    }

    public static function getSubscribedEvents()
    {
        return [
            BeforeTaskEvent::class=>["onBeforeTaskEvent", 9900],
            AfterTaskEvent::class=>["onAfterTaskEvent", -100],
            BuildScheduleEvent::class=>["onBuildScheduleEvent", -100]
        ];
    }

    public function onBuildScheduleEvent(BuildScheduleEvent $event){
        $schedule = $event->getSchedule();
        $allTask = $schedule->all();
        if($allTask){
            foreach ($allTask as $task){
                $taskId = $task->getId();
                $nextRun = $task->getNextRun()->format('Y-m-d H:i:s');
                $this->scheduleService->updateNextRun($taskId, $nextRun);
                //是否关闭
                if(!$this->scheduleService->hasClose($taskId)){
                    $task->skip("task close", true);
                }
            }
        }
    }

    public function onBeforeTaskEvent(BeforeTaskEvent $event){

    }

    public function onAfterTaskEvent(AfterTaskEvent $event){
        $taskId = $event->runContext()->getTask()->getId();
        $startTime = $event->runContext()->getStartTime()->format("Y-m-d H:i:s");
        $duration = $event->runContext()->getFormattedDuration();//耗时
        $memory = $event->runContext()->getFormattedMemory();//占用内存
        $result = $event->runContext()->getResult()->getOutput();
        $runType = $event->runContext()->getResult()->getType();
        $exception = $event->runContext()->getResult()->getException();
        $isException = $event->runContext()->getResult()->isException();

        if($runType == "skipped") return true;

        $runInfo = "耗时: ".$duration.", 占用内存: ".$memory;
        if($isException){
            $result = $exception;
        }
        $this->scheduleLogService->add($taskId,$startTime, $result, $runType, $runInfo);
    }
}
