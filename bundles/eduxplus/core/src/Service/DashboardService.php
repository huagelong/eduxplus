<?php
/**
 * @Author: kaihui.wang
 * @Contact  hpuwang@gmail.com
 * @Version: 1.0.0
 * @Date: 2020/9/28 14:51
 */

namespace Eduxplus\CoreBundle\Service;


use Eduxplus\CoreBundle\Service\Mall\GoodsService;
use Eduxplus\CoreBundle\Lib\Base\BaseService;

class DashboardService extends BaseService
{

    protected $goodsService;

    public function __construct(GoodsService $goodsService)
    {
        $this->goodsService = $goodsService;
    }

    public function dashboardStat(){
        //今日收入
        $todayDate = date('Y-m-d');
        $sql = "SELECT SUM(a.orderAmount) as sum FROM App:MallOrder a WHERE a.orderStatus=2 AND a.createdAt LIKE '".$todayDate."%'";
        $todayIncome =$this->fetchField("sum", $sql);
        $todayIncome = $todayIncome?$todayIncome/100:0;
        //今日注册用户
        $sql = "SELECT COUNT(a.id) as cnt FROM App:BaseUser a WHERE a.createdAt LIKE '".$todayDate."%'";
        $todayRegUserCount =$this->fetchField("cnt", $sql);
        $todayRegUserCount = $todayRegUserCount?$todayRegUserCount:0;
        //今日订单数
        $sql = "SELECT COUNT(a.id) as cnt FROM App:MallOrder a WHERE a.createdAt LIKE '".$todayDate."%'";
        $todayOrderCount =$this->fetchField("cnt", $sql);
        $todayOrderCount = $todayOrderCount?$todayOrderCount:0;
        //总注册用户数
        $sql = "SELECT COUNT(a.id) as cnt FROM App:BaseUser a ";
        $totalRegUserCount =$this->fetchField("cnt", $sql);
        $totalRegUserCount = $totalRegUserCount?$totalRegUserCount:0;

        return [$todayIncome, $todayRegUserCount, $todayOrderCount, $totalRegUserCount];
    }

    public function lastOrder(){
        $pageSize = 10;
        $sql = "SELECT a FROM App:MallOrder a ORDER BY a.createdAt DESC";
        $items =$this->fetchAll($sql, [], 0, $pageSize);
        $itemsArr = [];
        if ($items) {
            foreach ($items as $vArr) {
                $vArr['orderAmount'] = $vArr['orderAmount'] / 100;
                $goodsInfo = $this->goodsService->getById($vArr['goodsId']);
                $vArr["goodName"] = $goodsInfo["name"];
                //'订单状态:0支付过期,1待支付,2支付成功,3已取消',
                if($vArr['orderStatus'] == 0){
                    $vArr["orderStatusView"] = "已过期";
                }else if($vArr['orderStatus'] == 1){
                    $vArr["orderStatusView"] = "待支付";
                }else if($vArr['orderStatus'] == 2){
                    $vArr["orderStatusView"] = "支付成功";
                }else if($vArr['orderStatus'] == 3){
                    $vArr["orderStatusView"] = "已取消";
                }
                $vArr['createdAt'] = date('Y-m-d H:i:s', $vArr["createdAt"]->getTimestamp());

                $itemsArr[] = $vArr;
            }
        }
        return $itemsArr;
    }

}
