<?php


namespace Eduxplus\CoreBundle\Service;


use Eduxplus\CoreBundle\Entity\BaseScheduleLog;
use Eduxplus\CoreBundle\Lib\Base\AdminBaseService;
use Knp\Component\Pager\PaginatorInterface;

class ScheduleLogService extends AdminBaseService
{

    protected $paginator;

    public function __construct(PaginatorInterface $paginator)
    {
        $this->paginator = $paginator;
    }

    public function scheduleLogList($request, $page, $pageSize, $taskId)
    {
        $sql = $this->getFormatRequestSql($request);
        if(!$sql){
            $sql = " WHERE a.taskId =:taskId ";
        }else{
            $sql .= " AND a.taskId =:taskId ";
        }
        $dql = "SELECT a FROM Core:BaseScheduleLog a " . $sql  . " ORDER BY a.id DESC";
        $em = $this->getDoctrine()->getManager();
        $query = $em->createQuery($dql);
        if($taskId) $query = $query->setParameters(["taskId"=>$taskId]);
        $pagination = $this->paginator->paginate(
            $query,
            $page,
            $pageSize
        );

        return $pagination;
    }
    public function add($taskId, $startTime, $result, $runType, $runInfo){
        $model = new BaseScheduleLog();
        $model->setResult($result);
        $model->setRunInfo($runInfo);
        $model->setRunType($runType);
        $model->setStartTime($startTime);
        $model->setTaskId($taskId);
        $ip = isset($_SERVER['SERVER_ADDR'])?$_SERVER['SERVER_ADDR']:"127.0.0.1";
        $model->setIp($ip);
        $this->db()->insert($model);
    }


}
