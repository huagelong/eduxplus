<?php
/**
 * @Author: kaihui.wang
 * @Contact  hpuwang@gmail.com
 * @Version: 1.0.0
 * @Date: 2020/5/20 16:10
 */

namespace App\Bundle\ApiBundle\Service;


use App\Bundle\AppBundle\Lib\Base\BaseService;
use App\Repository\BaseUserRepository;

class ApiBaseService extends BaseService
{

    /**
     * @var BaseUserRepository
     */
    protected $userRepository;

    public function __construct(BaseUserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function getUserByToken($token, $clientId){
        if($clientId=='ios' || $clientId=='android'){
            return $this->userRepository->findOneBy(["appToken"=>$token]);
        }elseif($clientId == 'html') {
            return $this->userRepository->findOneBy(["htmlToken" => $token]);
        }

        return null;
    }
}
