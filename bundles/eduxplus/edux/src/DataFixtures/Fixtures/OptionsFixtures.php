<?php

namespace Eduxplus\EduxBundle\DataFixtures\Fixtures;

use Eduxplus\CoreBundle\Entity\BaseOption;
use Eduxplus\CoreBundle\Lib\Base\BaseService;

class OptionsFixtures 
{

    protected $baseService;

    public function __construct(BaseService $baseService)
    {
        $this->baseService = $baseService;
    }

    public function load()
    {
        $this->addOption("app.tengxunyun.im.group.keepdays", '', "腾讯云IM群组保留时间", 1, 1, "腾讯云IM配置");
    }

    protected function addOption($key, $value, $descr, $type = 1, $isLock = 1, $group='')
    {
        $optionModel = new BaseOption();
        $optionModel->setOptionKey($key);
        $optionModel->setOptionValue($value);
        $optionModel->setDescr($descr);
        $optionModel->setIsLock($isLock);
        $optionModel->setOptionGroup($group);
        $optionModel->setType($type);
        $this->baseService->getDoctrine()->getManager()->persist($optionModel);
        $this->baseService->getDoctrine()->getManager()->flush();
    }
}
