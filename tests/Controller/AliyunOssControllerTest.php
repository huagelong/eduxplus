<?php
/**
 * @Author: kaihui.wang
 * @Contact  hpuwang@gmail.com
 * @Version: 1.0.0
 * @Date: 2020/5/19 11:02
 */

namespace App\Tests\Controller;

use Eduxplus\CoreBundle\Lib\Base\BaseService;
use Eduxplus\CoreBundle\Entity\BaseUser;
use App\Tests\WebTestCaseBase;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\BrowserKit\Cookie;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;

class AliyunOssControllerTest extends WebTestCaseBase
{


    public function testShowPost()
    {
        $this->loginIn();
        $service = $this->sfcontainer->get(\Eduxplus\CoreBundle\Lib\Base\BaseService::class);

        $imgContent = "https://www.baidu.com/img/flexible/logo/pc/result@2.png";
        $filePath = $service->getBasePath()."/var/tmp/upload/result@2.png";
        file_put_contents($filePath, file_get_contents($imgContent));
        if(is_file($filePath)){
            $fileObj = new UploadedFile($filePath, "result@2.png",  'image/png');
            $crawler = $this->client->request('POST', '/admin/glob/upload/arc',[],[$fileObj]);
            $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        }else{
            $this->assertIsNumeric("hello", "get imgerror");
        }
    }
}
