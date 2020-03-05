<?php

namespace App\CenterBundle\Controller;

use App\CenterBundle\Lib\Base\BaseController;
use App\CenterBundle\Service\TestService;
use Negotiation\Tests\TestCase;
use Psr\Log\LoggerInterface;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends BaseController
{
    /**
     * @Route("/", name="default")
     */
    public function index($projectDir, TestService $testService)
    {
        $appName = $testService->test();
        $this->logger->info($projectDir);
        return $this->render('default/index.html.twig', [
            'controller_name' => $appName,
        ]);
    }
}
