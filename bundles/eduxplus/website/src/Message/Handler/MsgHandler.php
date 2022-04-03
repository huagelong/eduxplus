<?php
/**
 * @Author: kaihui.wang
 * @Contact  hpuwang@gmail.com
 * @Version: 1.0.0
 * @Date: 2020/3/19 10:02
 */

namespace Eduxplus\WebsiteBundle\Message\Handler;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;
use Eduxplus\WebsiteBundle\Message\Msg;
use Eduxplus\CoreBundle\Lib\Base\AppBaseService;
use Eduxplus\EduxBundle\Entity\MallMsg;

class MsgHandler extends AppBaseService implements MessageHandlerInterface {

    public function __invoke(Msg $message)
    {
       $data = $message->getContent();
       $data = unserialize($data);
       list($uid, $tplKey, $params) = $data;
       $params = serialize($params);
       $model = new MallMsg();
       $model->setUid($uid);
       $model->setMsgTemplateKey($tplKey);
       $model->setParams($params);
       $model->setStatus(0);
       return $this->db()->save($model);
    }
}
