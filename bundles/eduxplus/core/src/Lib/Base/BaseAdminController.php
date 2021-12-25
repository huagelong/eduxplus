<?php
/**
 * @Author: kaihui.wang
 * @Contact  hpuwang@gmail.com
 * @Version: 1.0.0
 * @Date: 2020/3/4 11:47
 */

namespace Eduxplus\CoreBundle\Lib\Base;

class BaseAdminController extends BaseController
{
    protected $adminBaseService;

    public function __construct(AdminBaseService $adminBaseService)
    {
        $this->adminBaseService = $adminBaseService;
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
        $model = $this->adminBaseService->fetchOne($sql, ["id"=>$uid]);
        return $model;
    }
}
