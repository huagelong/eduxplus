<?php
/**
 * @Author: kaihui.wang
 * @Contact  hpuwang@gmail.com
 * @Version: 1.0.0
 * @Date: 2020/11/22 19:11
 */

namespace Eduxplus\WebsiteBundle\Service;


use Eduxplus\CoreBundle\Lib\Base\AppBaseService;
use Eduxplus\CoreBundle\Lib\Service\RedisService;
use Eduxplus\EduxBundle\Entity\MallMsg;
use Eduxplus\EduxBundle\Entity\MallMsgStatus;
use Knp\Component\Pager\PaginatorInterface;

class MsgService extends AppBaseService
{
    const MSG_QUEUE_KEY = "MSG_QUEUE_KEY";
    private $redisService;
    private $paginator;

    public function __construct(RedisService $redisService, PaginatorInterface $paginator)
    {
        $this->redisService = $redisService;
        $this->paginator = $paginator;
    }

    /**
     * 解析
     * @param $tplKey
     * @param $params
     */
    public function parseMsg($tplKey, $params){
        if(!is_array($params)) $params= unserialize($params);
        $tpl = $this->getOption($tplKey);
        if(!$tpl) return "";
        if($params){
            foreach ($params as $k=>$v){
                $kTmp = "{".$k."}";
                $tpl = str_replace($kTmp, $v, $tpl);
            }
        }
        return $tpl;
    }

    /**
     * 已读
     * @param $uid
     * @param $msgId
     * @return mixed
     */
    public function doRead($uid, $msgId){
        $sql = "SELECT a FROM Edux:MallMsg a WHERE a.id=:id";
        $msgInfo = $this->db()->fetchOne($sql, ["id"=>$msgId], 1);
        if($msgInfo->getUid()){
            $msgInfo->setStatus(1);
            return $this->db()->save($msgInfo);
        }else{
            $sql = "SELECT a FROM Edux:MallMsgStatus a WHERE a.uid=:uid AND a.msgId=:msgId ";
            $msgStatusInfo = $this->db()->fetchOne($sql, ["uid"=>$uid, "msgId"=>$msgId]);
            if(!$msgStatusInfo){
                $model = new MallMsgStatus();
                $model->setUid($uid);
                $model->setStatus(1);
                $model->setMsgId($msgId);
                return $this->db()->save($model);
            }
        }
    }

    /**
     * 全部已读
     * @param $uid
     */
    public function doAllMsgRead($uid){
        $updateSql = "UPDATE Edux:MallMsg a SET a.status=1 WHERE a.uid =:uid AND a.status =0";
        $this->db()->execute($updateSql, ["uid"=>$uid]);

        $dql = "SELECT a FROM Edux:MallMsg a WHERE a.uid =0";
        $row = $this->db()->fetchAll($dql);
        if($row){
            foreach ($row as $v){
                $msgId = $v["id"];
                $sql = "SELECT a FROM Edux:MallMsgStatus a WHERE a.uid=:uid AND a.msgId=:msgId ";
                $msgStatusInfo = $this->db()->fetchOne($sql, ["uid"=>$uid, "msgId"=>$msgId]);
                if(!$msgStatusInfo){
                    $model = new MallMsgStatus();
                    $model->setUid($uid);
                    $model->setStatus(1);
                    $model->setMsgId($msgId);
                    return $this->db()->save($model);
                }
            }
        }
    }

    /**
     * 未读消息数量
     *
     * @param $uid
     */
    public function msgUnReadCount($uid){
        $countSql = "SELECT count(a.id) as cnt FROM  Edux:MallMsg a WHERE a.uid =:uid AND a.status =0";
        $count = $this->db()->fetchField("cnt", $countSql, ["uid"=>$uid]);

        $sysTotalCountSql = "SELECT count(a.id) as cnt FROM  Edux:MallMsg a WHERE a.uid =0 AND a.status =0";
        $sysTotalcount = $this->db()->fetchField("cnt", $sysTotalCountSql);

        $sysTotalReadCountSql = "SELECT count(a.id) as cnt FROM  Edux:MallMsgStatus a WHERE a.uid =:uid AND a.status=1";
        $sysReadTotalcount = $this->db()->fetchField("cnt", $sysTotalReadCountSql, ["uid"=>$uid]);

        return $count+$sysTotalcount-$sysReadTotalcount;
    }

    /**
     * 保存到队列
     * @param $uid
     * @param $tplKey
     * @param $params
     */
    public function send($uids, $tplKey, $params){
        if(!is_array($uids)){
            $uids = [$uids];
        }
        foreach ($uids as $uid){
            $json = serialize([$uid, $tplKey, $params]);
            $this->redisService->rpush(self::MSG_QUEUE_KEY, $json);
        }
        return true;
    }

    /**
     * 处理队列
     */
    public function doQueue(){
        while($data = $this->redisService->lpop(self::MSG_QUEUE_KEY)){
            $data = unserialize($data);
            list($uid, $tplKey, $params) = $data;
            $this->send2db($uid, $tplKey, $params);
        }
    }

    /**
     * 发送消息
     * @param $uid
     * @param $tplKey
     * @param $params
     * @return mixed
     */
    public function send2db($uid, $tplKey, $params){
        $params = serialize($params);
        $model = new MallMsg();
        $model->setUid($uid);
        $model->setMsgTemplateKey($tplKey);
        $model->setParams($params);
        $model->setStatus(0);
        return $this->db()->save($model);
    }

    /**
     * 消息列表
     *
     * @param $uid
     * @param $page
     * @param $pageSize
     * @return array
     */
    public function msgList($uid, $page, $pageSize){
        $dql = "SELECT a FROM Edux:MallMsg a WHERE a.uid IN(:uid) ORDER BY a.createdAt DESC";
        $em = $this->getDoctrine()->getManager();
        $query = $em->createQuery($dql);
        $query= $query->setParameters(["uid"=>[$uid, 0]]);
        $pagination = $this->paginator->paginate(
            $query,
            $page,
            $pageSize
        );

        $items = $pagination->getItems();
        $itemsArr = [];
        if($items) {
            foreach ($items as $v) {
                $vArr = $this->toArray($v);

                if(!$vArr['uid']){
                    $sql = "SELECT a FROM Edux:MallMsgStatus a WHERE a.uid=:uid AND a.msgId=:msgId ";
                    $msgStatusInfo = $this->db()->fetchOne($sql, ["uid"=>$uid, "msgId"=>$vArr["id"]]);
                    if(!$msgStatusInfo){
                        $status = 0;
                    }else{
                        $status = $msgStatusInfo["status"];
                    }
                }else{
                    $status = $vArr["status"];
                }

                $vArr['content'] = $this->parseMsg($vArr["msgTemplateKey"], $vArr["params"]);
                $vArr['status'] = $status;
                $vArr['createdAtTime'] = $vArr["createdAt"]['timestamp'];
                $itemsArr[] = $vArr;
            }
        }

        return [$pagination, $itemsArr];
    }



}
