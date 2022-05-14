<?php

/**
 * @Author: kaihui.wang
 * @Contact  hpuwang@gmail.com
 * @Version: 1.0.0
 * @Date: 2020/3/26 14:07
 */

namespace Eduxplus\WebsiteBundle\Controller;

use AlibabaCloud\Imm\Imm;
use Eduxplus\CoreBundle\Lib\Base\BaseHtmlController;
use Eduxplus\CoreBundle\Lib\Service\CaptchaService;
use Eduxplus\CoreBundle\Lib\Service\Base\Live\TengxunyunLiveService;
use Eduxplus\CoreBundle\Lib\Service\ValidateService;
use Eduxplus\CoreBundle\Lib\Service\Base\Vod\AliyunVodService;
use Eduxplus\CoreBundle\Lib\Service\Base\Vod\TengxunyunVodService;
use Eduxplus\WebsiteBundle\Service\GlobService;
use Eduxplus\WebsiteBundle\Service\LearnService;
use Eduxplus\WebsiteBundle\Service\OrderService;
use Firebase\JWT\JWT;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Eduxplus\CoreBundle\Lib\Service\Base\UploadService;
use Eduxplus\EduxBundle\Service\Teach\ImService;
use Psr\Log\LoggerInterface;
use EasyWeChat\Kernel\Support\XML;

class GlobController extends BaseHtmlController
{

    
    public function recaptchaAction($type, CaptchaService $captchaService, Request $request)
    {
        $session = $request->getSession();
        list($source, $header) = $captchaService->get($session, $type);

        $response = new Response($source);
        if ($header) {
            foreach ($header as $v) {
                list($k, $v) = explode(":", $v);
                $response->headers->set($k, $v);
            }
        }
        return $response;
    }

    
    public function sendCaptchaAction(Request $request, ValidateService $validateService, GlobService $globService)
    {
        $type = $request->get('type');
        $imgCode = $request->get('imgCode');
        $mobile = $request->get('mobile');
        if (!$type || !$imgCode || !$mobile) return $this->responseError("参数缺失!");
        if (!$validateService->mobileValidate($mobile)) return $this->responseError("手机号码格式错误!");
        //检查图片验证码
        //        exit('aaaa');
        $check = $globService->checkImgCaptcha($imgCode, $type);

        if (!$check) return $this->responseError("图片验证码错误!");

        $globService->sendCaptcha($mobile, $type);
        if ($this->error()->has()) {
            return $this->responseError($this->error()->getLast());
        }
        return $this->responseMsgRedirect("验证码发送成功!");
    }

    
    public function uploadAction($type, Request $request, UploadService $uploadService)
    {
        $file = $request->files->all();

        if (!$file) {
            return $this->responseError("没有文件上传!");
        }

        try {
            $filePaths = [];
            foreach ($file as $v) {
                $filePath = $uploadService->upload($v, $type);
                $filePaths[] = $filePath;
            }
            return $filePaths;
        } catch (\Exception $e) {
            return $this->responseError($e->getMessage());
        }
    }


    public function alipayCallbackAction(Request $request, OrderService $orderService, LoggerInterface $logger){
        /**
         *  {"gmtCreate":"2020-10-27 17:28:29","charset":"UTF-8","gmtPayment":"2020-10-27 17:28:38","notifyTime":"2020-10-27 17:28:39","subject":"\u7ec4\u5408\u5546\u54c1test","sign":"u8OCd8OG\/q0BciE5zneTid1Ee0sx3G\/pRLDANO\/WxDWphz1fRzGgjr2+rMR8fXZjE6e1ffkRcDsQB+hqlRlon+1vThWapWZvGTnOMc5apigiLmEhsBSb9yRfkGm1VXzt9YxAZgtnmdfPAKzzlHj808rld7UDH90BtE59upLeMRS4vA4PVUPPpIjSOl68xRSN6EVfVJriapeb6uIfTNY3e4yNDl1GNsNoObsLb6pHpYZ30A2riKheRopwJ1R6ZCumDA5Wiijw4xDxbsACZL8Y\/nxzpx3QP6qNPPx83wlU0\/E8uuPqjKz+W2qvEA+xk1i64z8Ta7Sod6ERBodHAg9FEQ==","buyerId":"2088622954897569","invoiceAmount":"111.00","version":"1.0","notifyId":"2020102700222172839097560508610326","fundBillList":"[{\"amount\":\"111.00\",\"fundChannel\":\"ALIPAYACCOUNT\"}]","notifyType":"trade_status_sync","outTradeNo":"20201027o1f69c042ecd620eab33abe236b89b0361","totalAmount":"111.00","tradeStatus":"TRADE_SUCCESS","tradeNo":"2020102722001497560501333300","authAppId":"2016102700768653","receiptAmount":"111.00","pointAmount":"0.00","appId":"2016102700768653","buyerPayAmount":"111.00","signType":"RSA2","sellerId":"2088102181220832"}
         */

        $outTradeNo = $request->get("outTradeNo");
        $tradeStatus = $request->get("tradeStatus");
        if(!$outTradeNo || !$tradeStatus){
            $logger->debug("alipayCallbackAction:参数错误!");
            return new Response("error", 500);
        }
        $requestData = $request->request->all();
        $check = $orderService->orderSuccess($outTradeNo);
        if(!$check){
            $logger->debug("alipayCallbackAction:".$this->error()->getLast());
            return new Response($this->error()->getLast(), 500);
        }
        return new Response("success");
    }

    
    public function wxpayCallbackAction(Request $request, OrderService $orderService, LoggerInterface $logger){

        $xmlcontent = $request->getContent();
        $logger->info($xmlcontent);
//        $xmlcontent = '<xml><appid><![CDATA[wxde0c292600278a0d]]></appid> <bank_type><![CDATA[OTHERS]]></bank_type> <cash_fee><![CDATA[1]]></cash_fee> <fee_type><![CDATA[CNY]]></fee_type> <is_subscribe><![CDATA[Y]]></is_subscribe> <mch_id><![CDATA[1605226225]]></mch_id> <nonce_str><![CDATA[5ffe9eb541f88]]></nonce_str> <openid><![CDATA[oggHj6Hx-hfNBdPQdFM9TO6whVhk]]></openid> <out_trade_no><![CDATA[20210113o26f83ebf357b46130]]></out_trade_no> <result_code><![CDATA[SUCCESS]]></result_code> <return_code><![CDATA[SUCCESS]]></return_code> <sign><![CDATA[F621953C634E3BCCB2702E831DDCFC48]]></sign> <time_end><![CDATA[20210113151824]]></time_end> <total_fee>1</total_fee> <trade_type><![CDATA[NATIVE]]></trade_type> <transaction_id><![CDATA[4200000789202101134059812199]]></transaction_id> </xml>';

        if($xmlcontent) {
            $result = XML::parse($xmlcontent);
            if(($result['result_code'] == "SUCCESS") && ($result['return_code'] == "SUCCESS")){
                $outTradeNo = $result["out_trade_no"];
                $check = $orderService->orderSuccess($outTradeNo);
                if(!$check){
                    $logger->debug("alipayCallbackAction:".$this->error()->getLast());
                    $arr=["return_code"=>"FAIL", "return_msg"=>$this->error()->getLast()];
                    $xm = XML::build($arr);
                    $logger->info($xm);
                    return new Response($xm);
                }
                $arr=["return_code"=>"SUCCESS", "return_msg"=>"OK"];
                $xm = XML::build($arr);
            }
        }else{
            $arr=["return_code"=>"FAIL", "return_msg"=>"数据为空!"];
            $xm = XML::build($arr);
        }
        $logger->info($xm);
        return new Response($xm);
    }
}
