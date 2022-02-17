<?php

namespace Eduxplus\CoreBundle\DataFixtures\Fixtures;

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
        //        -----网站基础配置---
        $this->addOption("app.name", "eduxplus课堂", "网站名称", 1, 1, "网站配置");
        $this->addOption("app.cdn.domain", '', "cdn域名", 1, 1, "网站配置");
        $this->addOption("app.seo.title", '【eduxplus课堂官方网站】职业教育在线_移动学习、职达未来！', "全站seo标题", 1, 1, "网站配置");
        $this->addOption("app.seo.homepage.descr", '', "全站首页seo描述", 1, 1, "网站配置");
        $this->addOption("app.seo.homepage.keyword", '网校,职业教育在线,职业教育,职业教育在线', "首页seo关键字", 1, 1, "网站配置");
        $this->addOption("app.beian.number", '沪ICP备18036000号', "网站备案号", 1, 1, "网站配置");
        $this->addOption("app.copyright", '@2008-2019 上海xxxx有限公司版权所有', "网站copyright", 1, 1, "网站配置");
        $this->addOption("app.sms.times", '5', "当天准许同一手机发送短信的最大次数", 1, 1, "网站配置");
        $this->addOption("app.logo", '["/assets/images/logo.png"]', "网站logo", 2, 1, "网站配置");
        $this->addOption("app.user.default.gravatar", '["/assets/images/gravatar.jpeg"]', "用户默认头像", 2, 1, "网站配置");
        $this->addOption("app.icon", '["/assets/favicon.ico"]', "网站icon", 2, 1, "网站配置");
        $this->addOption("app.domain", 'http://dev.eduxplus.com/', "网站域名网址", 1, 1, "网站配置");
        $this->addOption("app.initpwd", '111111', "账号初始化密码", 1, 1, "网站配置");
        //adapter
        $this->addOption("app.upload.adapter", '3', "文件上传方式,1-本地，2-阿里云oss，3-腾讯云cos", 1, 1, "网站配置");
        $this->addOption("app.vod.adapter", '1', "点播服务商，1-腾讯云，2-阿里云", 1, 1, "网站配置");
        $this->addOption("app.live.adapter", '1', "直播服务商，1-腾讯云，2-阿里云", 1, 1, "网站配置");
        $this->addOption("app.sms.adapter", '1', "短信服务商，1-腾讯云，2-阿里云", 1, 1, "网站配置");
        $this->addOption("app.search.adapter", '1', "搜索方式，1-本地数据库，2-elasticsearch", 1, 1, "网站配置");
        //        -------云服务基础配置---
        //腾讯云
        $this->addOption("app.tengxunyun.appId", '', "腾讯云 appId", 1, 1, "腾讯云基础配置");
        $this->addOption("app.tengxunyun.secretId", '', "腾讯云 accesskeyId", 1, 1, "腾讯云基础配置");
        $this->addOption("app.tengxunyun.secretKey", '', "腾讯云 accesskeySecret ", 1, 1, "腾讯云基础配置");
        $this->addOption("app.tengxunyun.region", 'ap-shanghai', "腾讯云地域接入域名", 1, 1, "腾讯云基础配置");

        //阿里云
        $this->addOption("app.aliyun.userId", '37030', "阿里云 账号id", 1, 1, "阿里云基础配置");
        $this->addOption("app.aliyun.accesskeyId", '', "阿里云 accesskeyId", 1, 1, "阿里云基础配置");
        $this->addOption("app.aliyun.accesskeySecret", '', "阿里云 accesskeySecret", 1, 1, "阿里云基础配置");
        //https://help.aliyun.com/document_detail/98194.html?spm=a2c4g.11186623.6.612.1fb73ecbvK52qE 点播中心和访问域名, 直播，点播都要在同一个地域
        $this->addOption("app.aliyun.region", 'cn-shenzhen', "阿里云地域接入域名", 1, 1, "阿里云基础配置");
//        ------短信配置---
        //腾讯云短信
        $this->addOption("app.tengxunyun.sms.appid", '', "腾讯云 短信应用id ", 1, 1, "腾讯云短信配置");
        $this->addOption("app.tengxunyun.sms.appkey", '', "腾讯云 短信应用key ", 1, 1, "腾讯云短信配置");
        $this->addOption("app.tengxunyun.sms.recaptcha.templateId", '516278', "腾讯云 验证码短信模板id ", 1, 1, "腾讯云短信配置");
        //腾讯云cos
        $this->addOption("app.tengxunyun.bucket", '', "腾讯云cos桶名称", 1, 1, "腾讯云COS存储配置");
        //腾讯云点播
        $this->addOption("app.tengxunyun.vod.encryptionkey", '', "腾讯云点播Key防盗链密钥", 1, 1, "腾讯云点播配置");
        $this->addOption("app.tengxunyun.vod.procedure", '', "腾讯云点播任务流模板名字", 1, 1, "腾讯云点播配置");
        //腾讯云直播
        $this->addOption("app.tengxunyun.live.pushDomain", '', "腾讯云直播推流域名", 1, 1, "腾讯云直播配置");
        $this->addOption("app.tengxunyun.live.playDomain", '', "腾讯云直播播放域名", 1, 1, "腾讯云直播配置");
        $this->addOption("app.tengxunyun.live.pushDomainKey", '', "腾讯云直播推流鉴权签名KEy", 1, 1, "腾讯云直播配置");
        $this->addOption("app.tengxunyun.live.playDomainKey", '', "腾讯云直播播流鉴权签名KEy", 1, 1, "腾讯云直播配置");
        $this->addOption("app.tengxunyun.live.transcodeTemplateId", '["default", "sd", "hd"]', "腾讯云直播转码模板名称", 1, 1, "腾讯云直播配置");
        //腾讯云im
        $this->addOption("app.tengxunyun.im.sdkAppid", '', "腾讯云IM SdkAppid", 1, 1, "腾讯云IM配置");
        $this->addOption("app.tengxunyun.im.key", '', "腾讯云IM密钥", 1, 1, "腾讯云IM配置");
        $this->addOption("app.tengxunyun.im.identifier", '', "腾讯云IM账号管理员，默认’administrator‘", 1, 1, "腾讯云IM配置");

        //阿里云点播
        $this->addOption("app.aliyun.vod.kms.keyId", '', "阿里云kms vod 加密主密钥，可以用别名", 1, 1, "阿里云点播配置");
        $this->addOption("app.aliyun.vod.templateGroupId", '', "阿里云点播转码模板组ID", 1, 1, "阿里云点播配置");
        $this->addOption("app.aliyun.vod.callbackSignPrivateKey", '', "阿里云点播回调鉴权私有key", 1, 1, "阿里云点播配置");
        //阿里云直播
        $this->addOption("app.aliyun.live.pushDomain", '', "阿里云直播推流域名", 1, 1, "阿里云直播配置");
        $this->addOption("app.aliyun.live.playDomain", '', "阿里云直播播流域名", 1, 1, "阿里云直播配置");
        $this->addOption("app.aliyun.live.pushDomainKey", '', "阿里云直播推流签名KEy", 1, 1, "阿里云直播配置");
        $this->addOption("app.aliyun.live.playDomainKey", '', "阿里云直播播放签名KEy", 1, 1, "阿里云直播配置");
        $this->addOption("app.aliyun.live.transcodeTemplateId", '{"FD":"lld","LD":"lsd","SD":"lhd","HD":"lud"}', "阿里云直播转码模板id OD:原画,FD:流畅,LD:标清,SD:高清,HD:超清,2K:2K,4K:4K", 1, 1, "阿里云直播配置");
        //阿里云短信
        $this->addOption("app.aliyun.smsSign", '学多多', "阿里云 短信签名名称 ", 1, 1,"阿里云短信配置");
        $this->addOption("app.aliyun.sms.captcha.code", '', "阿里云 验证码短信模板Code ", 1, 1,"阿里云短信配置");
        //阿里云oss
        $this->addOption("app.aliyun.oss.bucket", '', "阿里云oss Bucket ", 1, 1,"阿里云OSS存储配置");
        $this->addOption("app.aliyun.oss.endpoint", '', "阿里云oss endpoint ", 1, 1,"阿里云OSS存储配置");

        //微信小程序
        $this->addOption("app.wxmini.appid", '', "微信小程序app id", 1, 1, "微信小程序配置");
        $this->addOption("app.wxmini.secret", '', "微信小程序secret key", 1, 1, "微信小程序配置");
        //支付宝
        $this->addOption("app.alipay.appid", '', "支付宝app id", 1, 1, "支付宝支付配置");
        $this->addOption("app.alipay.isSandbox", '0', "是否支付宝沙箱模式, 1-是，0-否", 1, 1, "支付宝支付配置");
        $this->addOption("app.alipay.notifyUrl", 'http://dev.eduxplus.com/pay/alipayCallback', "授权回调地址", 1, 1, "支付宝支付配置");
        $this->addOption("app.alipay.merchantPrivateKey", '', "应用私钥", 1, 1, "支付宝支付配置");
        $this->addOption("app.alipay.encryptKey", '', "接口内容加密方式AES密钥", 1, 1, "支付宝支付配置");
        $this->addOption("app.alipay.alipayPublicKey", '', "支付宝公钥字符串，如果采用非证书模式，则无需赋值三个证书路径，改为赋值如下的支付宝公钥字符串即可", 1, 1, "支付宝支付配置");
        //微信支付
        $this->addOption("app.wxpay.appid", '', "微信支付app id", 1, 1, "微信支付配置");
        $this->addOption("app.wxpay.mchid", '', "微信支付商户 id", 1, 1, "微信支付配置");
        $this->addOption("app.wxpay.key", '', "微信支付api 密钥", 1, 1, "微信支付配置");
        $this->addOption("app.wxpay.notifyrl", 'http://dev.eduxplus.com/pay/wxpayCallback', "微信支付默认的订单回调地址", 1, 1, "微信支付配置");
        $this->addOption("app.wxpay.sandbox", '', "是否沙盒模式,0-否，1-是", 1, 1, "微信支付配置");

        //elasticsearch搜索配置
        $this->addOption("app.es.host", '', "elasticsearch 服务器host配置，例如https://username:password!#$?*abc@foo.com:9200/", 1, 1, "elasticsearch搜索配置");
        $this->addOption("app.es.index.pre", 'eduxplus_', "elasticsearch 索引前缀", 1, 1, "elasticsearch搜索配置");
        $this->addOption("app.es.index.shardsNumber", '1', "elasticsearch 索引分片数目", 1, 1, "elasticsearch搜索配置");
        $this->addOption("app.es.index.replicasNumber", '0', "elasticsearch 索引副本数目", 1, 1, "elasticsearch搜索配置");
        //消息
        $this->addOption("app.msgtpl.welcome", '注册成功！欢迎注册本站，有问题请先查看<a href="{href}">帮助中心</a>', "注册欢迎消息模板", 1, 1, "消息模板配置");

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
