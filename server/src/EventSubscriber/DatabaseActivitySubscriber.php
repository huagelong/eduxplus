<?php
/**
 * @Author: kaihui.wang
 * @Contact  hpuwang@gmail.com
 * @Version: 1.0.0
 * @Date: 2020/3/8 17:50
 */

namespace App\EventSubscriber;

use Doctrine\ORM\Event\PreUpdateEventArgs;
use Doctrine\Persistence\Event\LifecycleEventArgs;
use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Events;

class DatabaseActivitySubscriber implements EventSubscriber
{
    public function getSubscribedEvents()
    {
        return [
            Events::prePersist,
            Events::preUpdate,
        ];
    }

    public function prePersist(LifecycleEventArgs $args)
    {
        //created_at,updated_at 自动添加
//        $entity = $args->getObject();
//        if(!$entity->getCreatedAt()){
//            $now = date('Y-m-d');
//            $entity->setCreatedAt($now);
//            $entity->setUpdatedAt($now);
//        }
    }

    public function preUpdate(PreUpdateEventArgs $args)
    {
        //updated_at 自动添加
//        $entity = $args->getObject();
//        if(!$entity->getUpdatedAt()){
//            $now = date('Y-m-d');
//            $entity->setUpdatedAt($now);
//        }
    }

}
