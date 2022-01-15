<?php
/**
 * @Author: kaihui.wang
 * @Contact  hpuwang@gmail.com
 * @Version: 1.0.0
 * @Date: 2020/10/21 14:14
 */

namespace Eduxplus\WebsiteBundle\Service;


use Eduxplus\CoreBundle\Lib\Base\AppBaseService;

class PageService extends AppBaseService
{
    public function getById($id){
        $sql = "SELECT a FROM Cms:CmsPage a WHERE a.id=:id AND a.status=1 ";
        $result = $this->db()->fetchOne($sql, ["id"=>$id]);
        if(!$result) return $result;
        $sqlMain = "SELECT a FROM Cms:CmsPageMain a WHERE a.pageId=:pageId";
        $main = $this->db()->fetchOne($sqlMain, ["pageId"=>$id]);
        $result['main'] = $main;
        return $result;
    }
}
