<?php

namespace App\Controller;

use App\CenterBundle\Base\BaseController;
use Psr\Log\LoggerInterface;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends BaseController
{
    /**
     * @Route("/", name="default")
     */
    public function index()
    {
        //db
        $this->logger->info("hello world");
        return $this->render('default/index.html.twig', [
            'controller_name' => 'DefaultController',
        ]);
    }
}
