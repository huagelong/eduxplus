<?php

/**
 * @Author: kaihui.wang
 * @Contact  hpuwang@gmail.com
 * @Version: 1.0.0
 * @Date: 2020/5/19 11:02
 */

namespace App\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use App\Bundle\AppBundle\Lib\Base\BaseService;
use App\Entity\BaseUser;
use Symfony\Component\BrowserKit\Cookie;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;

class WebTestCaseBase extends WebTestCase
{
    protected $client = null;
    protected $sfcontainer = null;

    public function setUp()
    {
        $this->client = static::createClient();
        self::bootKernel();
        $this->sfcontainer = self::$container;
    }

    protected function loginIn()
    {
        // $session = $this->sfcontainer->get('session');
        // $sql = "SELECT a FROM App:BaseUser a WHERE a.mobile = :mobile";
        // $service = $this->sfcontainer->get(\App\Bundle\AppBundle\Lib\Base\BaseService::class);
        // $user = $service->fetchOne($sql, ["mobile"=>"17621487000"], 1);
        // $firewallName = 'admin';
        // $firewallContext = 'admin';

        // $token = new UsernamePasswordToken($user, null, $firewallName, $user->getRoles());
        // $session->set('_security_'.$firewallContext, serialize($token));
        // $session->save();

        // $cookie = new Cookie($session->getName(), $session->getId());
        // $this->client->getCookieJar()->set($cookie);

        
    }
}
