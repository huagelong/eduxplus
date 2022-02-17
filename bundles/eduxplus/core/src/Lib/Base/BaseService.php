<?php

namespace Eduxplus\CoreBundle\Lib\Base;

use Doctrine\Persistence\ManagerRegistry;
use Eduxplus\CoreBundle\Entity\BaseUser;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Serializer\Normalizer\DateTimeNormalizer;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ContainerBagInterface;
use Symfony\Component\PropertyAccess\PropertyAccess;
use Symfony\Component\PropertyAccess\PropertyAccessorInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\UsageTrackingTokenStorage;
use Psr\Container\ContainerInterface;
use Symfony\Component\Security\Core\Security;
use Psr\Log\LoggerInterface;
use Symfony\Contracts\Cache\CacheInterface;

class BaseService
{

    /**
     * @var ManagerRegistry
     */
    private $em;
    /**
     * @var SerializerInterface
     */
    private $serializer;
    /**
     * @var RequestStack
     */
    private $requestStack;
    /**
     * @var UrlGeneratorInterface
     */
    private $router;
    /**
     * @var ContainerBagInterface
     */
    private $params;
    /**
     * @var PropertyAccessorInterface
     */
    private $propertyAccessor;
    /**
     * @var UsageTrackingTokenStorage
     */
    private $tokenStorage;
    /**
     * @var ContainerInterface
     */
    private $container;
    /**
     * @var Security
     */
    private $security;
    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @var DbService
     */
    private $db;

    /**
     *
     * @var CacheInterface
     */
    private $cache;

    public function inject(ManagerRegistry $em,
                         SerializerInterface $serializer,
                         RequestStack $requestStack,
                         UrlGeneratorInterface $router,
                         ContainerBagInterface $params,
                         PropertyAccessorInterface   $propertyAccessor,
                           UsageTrackingTokenStorage $tokenStorage,
                           ContainerInterface $container,
                           Security $security,
                           LoggerInterface $logger,
                           DbService $db,
                           CacheInterface $cache

    ){
        $this->em = $em;
        $this->serializer = $serializer;
        $this->requestStack = $requestStack;
        $this->router = $router;
        $this->params = $params;
        $this->propertyAccessor = $propertyAccessor ?: PropertyAccess::createPropertyAccessor();
        $this->tokenStorage= $tokenStorage;
        $this->container =$container;
        $this->security = $security;
        $this->logger = $logger;
        $this->db = $db;
        $this->cache = $cache;
    }

    public final function isGranted(mixed $attributes, mixed $subject = null): bool
    {
        return $this->security->isGranted($attributes, $subject);
    }

    public final function get(string $id): object
    {
        return $this->container->get($id);
    }

    /**
     * @var DbService
     */
    public final function db()
    {
//        $this->logger()->info("db:".get_called_class());
        return $this->db->setDoctrine($this->em);
    }

    public final function error()
    {
        return new Error();
    }

    public final function cache(){
        return $this->cache;
    }

    public final function getDoctrine(){
        return $this->em;
    }

    public final function logger(){
        return $this->logger;
    }

    public final function log($log){
        $log = json_encode($log, JSON_UNESCAPED_UNICODE);
        clock()->info($log);
        return $this->logger()->info($log);
    }

    public final function getSecurity(){
        return $this->security;
    }

    public final function genUrl(string $route, array $parameters = [], int $referenceType = UrlGeneratorInterface::ABSOLUTE_PATH)
    {
        return $this->router->generate($route, $parameters, $referenceType);
    }

    public final function getUser():?UserInterface
    {
        $token = $this->tokenStorage->getToken();
        if(!$token)  return null;
        return $token->getUser();
    }

    /**
     * @return Request
     */
    public final function request()
    {
        return $this->requestStack->getCurrentRequest();
    }

    public final function session()
    {
        return $this->request()->getSession();
    }

    public final function getConfig($str)
    {
        return $this->params->get($str);
    }

    public final function getBasePath()
    {
        return $this->getConfig("kernel.project_dir");
    }

    public final function getPro($obj, $name)
    {
        return $this->propertyAccessor->getValue($obj, $name);
    }

    public final function getEnv()
    {
        $env = $_SERVER['APP_ENV'];
        return $env;
    }

    public final function jsonGet($json, $key = 0)
    {
        if (!$json) return "";
        $arr = json_decode($json, true);
        return isset($arr[$key]) ? $arr[$key] : "";
    }

    public final function toArray($entity, $group=null)
    {
        $groupConfig = [];
        if($group){
            $groupConfig['groups'] = $group;
        }
        $groupConfig[DateTimeNormalizer::FORMAT_KEY]='Y-m-d H:i:s';
        $json = $this->serializer->serialize($entity, 'json', $groupConfig);
        return json_decode($json, true);
    }

    /**
     * @param $token
     * @param $clientId
     * @return BaseUser
     */
    public final function getUserByToken($token, $clientId)
    {
        if ($clientId == 'ios' || $clientId == 'android') {
            $sql = "SELECT a FROM Core:BaseUser a WHERE a.appToken=:appToken";
            return $this->db()->fetchOne($sql, ["appToken" => $token], 1);
        } elseif ($clientId == 'html') {
            $sql = "SELECT a FROM Core:BaseUser a WHERE a.htmlToken=:htmlToken";
            return $this->db()->fetchOne($sql, ["htmlToken" => $token], 1);
        } elseif ($clientId == 'wxmini') {
            $sql = "SELECT a FROM Core:BaseUser a WHERE a.wxminiToken=:wxminiToken";
            return $this->db()->fetchOne($sql, ["wxminiToken" => $token], 1);
        }
    }

    public final function getOption($k, $isJson = 0, $index = null, $default = null)
    {
        $sql = "SELECT a.optionValue FROM Core:BaseOption a WHERE a.optionKey =:optionKey";
        $rs = $this->db()->fetchField("optionValue", $sql, ['optionKey' => $k]);

        if ($rs) {
            if ($isJson) {
                $arr =  json_decode($rs, 1);
                if($index === null){
                    return $arr;
                }
                return isset($arr[$index]) ? $arr[$index] : "";
            }
            return $rs;
        } else {
            return $default;
        }
    }


}
