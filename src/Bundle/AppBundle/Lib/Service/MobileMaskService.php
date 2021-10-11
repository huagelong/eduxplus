<?php

namespace App\Bundle\AppBundle\Lib\Service;
use App\Bundle\AppBundle\Lib\Base\BaseService;

class MobileMaskService extends BaseService
{
    protected $aesService;

    public function __construct(AesService $aesService)
    {
        $this->aesService = $aesService;
    }


    public function encrypt($mobile){
        return $this->aesService->encrypt($mobile);
    }

    public function decrypt($decrypt){
        return $this->aesService->decrypt($decrypt);
    }
}
