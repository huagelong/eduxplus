<?php
/**
 * @Author: kaihui.wang
 * @Contact  hpuwang@gmail.com
 * @Version: 1.0.0
 * @Date: 2020/10/21 14:14
 */

namespace Eduxplus\WebsiteBundle\Service;


use Eduxplus\CoreBundle\Lib\Base\AppBaseService;

class BannerService extends AppBaseService
{
    public function getBanners($position=0){
        $sql = "SELECT a FROM Edux:MallBanner a WHERE a.position=:position ";
        $result = $this->db()->fetchOne($sql, ["position"=>$position]);
        if(!$result) return $result;
        $bannerId = $result["id"];
        $sqlMain = "SELECT a FROM Edux:MallBannerMain a WHERE a.bannerId=:bannerId AND a.status=1 ORDER BY a.sort ASC";
        $result = $this->db()->fetchAll($sqlMain, ["bannerId"=>$bannerId]);
        return $result;
    }
}
