<?php
/**
 * @Author: kaihui.wang
 * @Contact  hpuwang@gmail.com
 * @Version: 1.0.0
 * @Date: 2020/3/26 19:43
 */

namespace Eduxplus\CoreBundle\Controller;


use Eduxplus\EduxBundle\Service\DashboardService;
use Eduxplus\CoreBundle\Service\MenuService;
use Eduxplus\CoreBundle\Lib\Base\BaseAdminController;
use Symfony\Component\Routing\Annotation\Route;
use Psr\Log\LoggerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class IndexController
 * @package Eduxplus\CoreBundle\Controller
 */
class IndexController extends BaseAdminController
{


    /**
     * @Route("/dashboard", name="admin_dashboard")
     */
    public function dashboardAction(DashboardService $dashboardService){
        list($todayIncome, $todayRegUserCount, $todayOrderCount, $totalRegUserCount) = $dashboardService->dashboardStat();

        $data = [];
        $data["todayIncome"] = number_format($todayIncome, 2);
        $data["todayRegUserCount"] = $todayRegUserCount;
        $data["todayOrderCount"] = $todayOrderCount;
        $data["totalRegUserCount"] = $totalRegUserCount;
        $data['lastOrder'] = $dashboardService->lastOrder();

        return $this->render('@EduxBundle/index/dashboard.html.twig', $data);
    }


}
