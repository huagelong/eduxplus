<?php

namespace App\Bundle\CenterBundle\Controller;

use App\Entity\AdminActionLog;
use App\Bundle\CenterBundle\Lib\Base\BaseController;
use App\Bundle\CenterBundle\Service\TestService;
use App\Repository\AdminActionLogRepository;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends BaseController
{
    /**
     * @Route("/", name="default")
     */
    public function index(AdminActionLogRepository $adminActionLogRep)
    {
        $entityMana = $this->getDoctrine()->getManager();
        $adminActionLog = new AdminActionLog();
        $adminActionLog->setRoute("test");
        $adminActionLog->setInputData(time());
        $adminActionLog->setIp("127.0.0.1");
        $adminActionLog->setUid(1);
        $entityMana->persist($adminActionLog);
        $entityMana->flush();
//        $model = $adminActionLogRep->createQueryBuilder("p")
//            ->where("p.id=:id")->setParameter("id", 1)->getQuery()->getOneOrNullResult();
//        $this->logger->info($model->getIp());
//        $model = $adminActionLogRep->find(1);
//        $model->setIp(time());
//        $this->save($model);

        $appName = $this->getParameter("app.path");
        return $this->render('default/index.html.twig', [
            'controller_name' => $appName,
        ]);
    }
}
