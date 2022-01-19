<?php
/**
 * @Author: kaihui.wang
 * @Contact  hpuwang@gmail.com
 * @Version: 1.0.0
 * @Date: 2020/3/4 11:47
 */

namespace Eduxplus\CoreBundle\Lib\Base;
use Eduxplus\CoreBundle\Lib\Content\ContentService;

class BaseAdminController extends BaseController
{
    protected $adminBaseService;
    private $contentService;

    public function __construct(AdminBaseService $adminBaseService, ContentService $contentService)
    {
        $this->adminBaseService = $adminBaseService;
        $this->contentService = $contentService;
    }

    public function content(){
        return $this->contentService;
    }

    public function getUid()
    {
        $user = $this->getUser();
        if($user) return $user->getId();
        return 0;
    }

    public function getUserInfo()
    {
        $uid = $this->getUid();
        $sql = "SELECT a FROM Core:BaseUser a WHERE a.id = :id";
        $model = $this->adminBaseService->db()->fetchOne($sql, ["id"=>$uid]);
        return $model;
    }
}
