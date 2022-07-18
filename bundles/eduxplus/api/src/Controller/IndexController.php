<?php

/**
 * @Author: kaihui.wang
 * @Contact  hpuwang@gmail.com
 * @Version: 1.0.0
 * @Date: 2020/3/28 19:38
 */

namespace Eduxplus\ApiBundle\Controller;

use Eduxplus\CoreBundle\Lib\Base\BaseApiController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Nyholm\Psr7\Factory\Psr17Factory;
use Symfony\Bridge\PsrHttpMessage\Factory\PsrHttpFactory;
use Tqdev\PhpCrudApi\Api;
use Tqdev\PhpCrudApi\Config;
use Symfony\Bridge\PsrHttpMessage\Factory\HttpFoundationFactory;
use Eduxplus\CoreBundle\Lib\Base\ApiBaseService;

/**
 * @package Eduxplus\ApiBundle\Controller
 */
class IndexController extends BaseApiController
{
    
    public function indexAction(Request $symfonyRequest, ApiBaseService $apiBaseService)
    {
        $psr17Factory = new Psr17Factory();
        $psrHttpFactory = new PsrHttpFactory($psr17Factory, $psr17Factory,
                                             $psr17Factory, $psr17Factory);
        $psrRequest = $psrHttpFactory->createRequest($symfonyRequest);

        // PHP-CRUD-API takes a psrRequest and generates a psrResponse

        $databaseUrl = $apiBaseService->getConfig("database_url");
        $debug = $apiBaseService->getConfig("kernel.debug");
        $dataBaseUrlArr = [];
        $dataBaseUrlArr = parse_url($databaseUrl);
        // print_r($dataBaseUrlArr);exit;
        $config = new Config([
            "driver" => $dataBaseUrlArr['scheme'],
            "address" =>$dataBaseUrlArr['host'], 
            "port"=>    $dataBaseUrlArr['port'], 
            'username' => $dataBaseUrlArr['user'],
            'password' => $dataBaseUrlArr['pass'],
            'database' => ltrim($dataBaseUrlArr['path'], '/'),
            'debug'=> $debug,
            'basePath' => '/apiauto',
        ]);
        $api = new Api($config);
        $psrResponse = $api->handle($psrRequest);
        $httpFoundationFactory = new HttpFoundationFactory();
        $symfonyResponse = $httpFoundationFactory->createResponse($psrResponse);
        $contentArr = json_decode($symfonyResponse->getContent(), true, JSON_UNESCAPED_UNICODE);

        if(isset($contentArr["code"])){
            return $this->responseError($contentArr["message"]);
        }else{
            return $this->responseSuccess($contentArr);
        }
        
    }

    
    public function test()
    {
        return $this->responseSuccess(["hello world"]);
    }
}
