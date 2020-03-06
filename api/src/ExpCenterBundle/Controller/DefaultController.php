<?php

namespace App\ExpCenterBundle\Controller;

use App\ExpCenterBundle\Lib\Base\BaseController;
use App\ExpCenterBundle\Service\TestService;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends BaseController
{
    /**
     * @Route("/", name="default")
     */
    public function index($projectDir, TestService $testService)
    {
//        $appName = $testService->test();
        $appName = $this->getParameter("exp_center.app.name");
        $this->logger->info($projectDir);
        return $this->render('default/index.html.twig', [
            'controller_name' => $appName,
        ]);
    }
}
