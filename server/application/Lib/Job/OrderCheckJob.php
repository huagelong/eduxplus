<?php

namespace Lib\Job;

use Trensy\Log;
use Trensy\TaskAbstract;

/**
 * 检测支付宝订单是否已取消
 * Class OrderCheckJob
 * @package Lib\Job
 */
final class OrderCheckJob extends TaskAbstract
{
    /**
     * @var \Site\Dao\OrderDao
     */
    public $orderDao;

    /**
     * @var \Lib\Service\PayService
     */
    public $payService;

    /**
     * @var \Site\Service\OldOrderService
     */
    public $oldOrderService;

    public function perform()
    {
        Log::show('order check task start ...');
        $now = time();
        $time = $now - 60 * 10; //十分钟以内
        $where = array(
            'order_status' => '0',
            'updated_at' => array('>', date('Y-m-d H:i:s', $time)),
        );
        $update_data = array(
            'order_status' => '2',
            'updated_at' => date('Y-m-d H:i:s', $now),
        );
        $order_arr = $this->orderDao->gets($where);
        foreach ($order_arr as $item) {
            $res = $this->payService->ali_web_query($item['order_no']);
            if ($res) {
                $where = array(
                    'order_no' => $item['order_no'],
                );
                $this->orderDao->update($update_data, $where);
                $this->oldOrderService->modify_old_order($item['order_no']);
                Log::show('取消订单:' . $item['order_no']);
            }
        }
        Log::show('order check task end');
        return true;
    }
}
