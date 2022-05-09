<?php


namespace Eduxplus\CoreBundle\EventSubscriber;


use Eduxplus\CoreBundle\Service\MenuService;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Stopwatch\Stopwatch;
use Zenstruck\ScheduleBundle\Event\AfterTaskEvent;
use Zenstruck\ScheduleBundle\Event\BeforeScheduleEvent;
use Zenstruck\ScheduleBundle\Event\BeforeTaskEvent;
use Zenstruck\ScheduleBundle\Event\BuildScheduleEvent;
use Zenstruck\ScheduleBundle\Event\ScheduleEvent;

class ScheduleSubscriber implements EventSubscriberInterface
{

    protected $stopwatch;

    public function __construct(Stopwatch $stopwatch)
    {
        $this->stopwatch = $stopwatch;
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
                $type = $task->getType();
                $descr = $task->getDescription();
                $taskId = $task->getId();
                $expression = $task->getExpression();
                $nextRun = $task->getNextRun()->format('y-m-d H:i:s');
                $timeZone = $task->getTimezone()->getName();
                //保存task信息到数据库
                //是否关闭
//                $task->skip("task close", true);
            }
        }
    }

    public function onBeforeTaskEvent(BeforeTaskEvent $event){

    }

    public function onAfterTaskEvent(AfterTaskEvent $event){
        $taskId = $event->runContext()->getTask()->getId();
        $startTime = $event->runContext()->getStartTime()->format("y-m-d H:i:s");
        $duration = $event->runContext()->getFormattedDuration();//耗时
        $memory = $event->runContext()->getFormattedMemory();//占用内存
        $result = $event->runContext()->getResult()->getOutput();
        $runType = $event->runContext()->getResult()->getType();
        $exception = $event->runContext()->getResult()->getException();

        $runInfo = "耗时: ".$duration.", 占用内存: ".$memory;
//        var_dump($runType);
    }
}
