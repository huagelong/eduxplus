<?php


namespace Eduxplus\CoreBundle\Service;


use Eduxplus\CoreBundle\Entity\BaseSchedule;
use Eduxplus\CoreBundle\Lib\Base\AdminBaseService;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Contracts\Cache\ItemInterface;
use Zenstruck\ScheduleBundle\Schedule\ScheduleRunner;

class ScheduleService extends AdminBaseService
{
    /**
     * @var ScheduleRunner
     */
    private $scheduleRunner;

    protected $paginator;

    public function __construct(ScheduleRunner $scheduleRunner,PaginatorInterface $paginator)
    {
        $this->scheduleRunner = $scheduleRunner;
        $this->paginator = $paginator;
    }

    public function scheduleList($request, $page, $pageSize)
    {
        $sql = $this->getFormatRequestSql($request);
        $dql = "SELECT a FROM Core:BaseSchedule a " . $sql  . " ORDER BY a.id DESC";
        $em = $this->getDoctrine()->getManager();
        $query = $em->createQuery($dql);
        $pagination = $this->paginator->paginate(
            $query,
            $page,
            $pageSize
        );

        return $pagination;
    }

    public function import(){
        $schedule = $this->scheduleRunner->buildSchedule();
        $tasks = $schedule->all();
        if($tasks){
            foreach ($tasks as $v=>$task){
                $type = $task->getType();
                $descr = $task->getDescription();
                $taskId = $task->getId();
                $expression = $task->getExpression()->getRawValue();
                $nextRun = $task->getNextRun()->format('y-m-d H:i:s');
                $timeZone = $task->getTimezone()->getName();

                $sql = "SELECT a FROM Core:BaseSchedule a where a.taskId =:taskId";
                $info = $this->db()->fetchOne($sql, ["taskId"=>$taskId], 1);
                if(!$info){
                    $info = new BaseSchedule();
                }
                $info->setNextRun($nextRun);
                $info->setType($type);
                $info->setDescr($descr);
                $info->setTaskId($taskId);
                $info->setExpression($expression);
                $info->setTimeZone($timeZone);
                $this->db()->save($info);
            }
        }
    }

    public function updateNextRun($taskId, $nextRun){
        $sql = "UPDATE Core:BaseSchedule a SET a.nextRun=:nextRun WHERE a.taskId=:taskId";
        $this->db()->execute($sql, ["nextRun"=>$nextRun, "taskId"=>$taskId]);
    }

    /**
     * 是否已关闭
     *
     * @param $taskId
     * @return mixed
     * @throws \Psr\Cache\InvalidArgumentException
     */
    public function hasClose($taskId){
        $key = "scheduleService_hasClose_".$taskId;
        return $this->cache()->get($key, function(ItemInterface $item) use($taskId){
            $item->expiresAfter(3600);
            $sql = "SELECT a FROM Core:BaseSchedule a where a.taskId =:taskId";
            $info = $this->db()->fetchOne($sql, ["taskId"=>$taskId]);
            if($info){
                return $info["status"];
            }else{
                //默认关闭
                return 0;
            }
        });
    }

    public function getById($id)
    {
        $sql = "SELECT a FROM Core:BaseSchedule a where a.id =:id ";
        return $this->db()->fetchOne($sql, ["id" => $id]);
    }


    public function switchStatus($id, $state)
    {
        $sql = "SELECT a FROM Core:BaseSchedule a WHERE a.id=:id";
        $model = $this->db()->fetchOne($sql, ['id' => $id], 1);
        $model->setStatus($state);
        return $this->db()->save($model);
    }

}
