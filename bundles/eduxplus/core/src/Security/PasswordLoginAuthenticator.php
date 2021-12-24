<?php
/**
 * @Author: kaihui.wang
 * @Contact  hpuwang@gmail.com
 * @Version: 1.0.0
 * @Date: 2020/3/22 20:37
 */

namespace Eduxplus\CoreBundle\Security;

use Aes;
use Eduxplus\CoreBundle\Lib\Helper\AesHelper;
use Eduxplus\CoreBundle\Lib\Base\BaseService;
use Eduxplus\CoreBundle\Lib\Service\CaptchaService;
use Eduxplus\CoreBundle\Entity\BaseUser;
use Eduxplus\CoreBundle\Exception\NeedLoginException;
use Doctrine\ORM\EntityManagerInterface;
use Eduxplus\CoreBundle\Repository\BaseUserRepository;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException;
use Symfony\Component\Security\Core\Exception\InvalidCsrfTokenException;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\User\PasswordUpgraderInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Csrf\CsrfToken;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;
use Symfony\Component\Security\Http\Authenticator\AbstractLoginFormAuthenticator;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\PasswordUpgradeBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Credentials\PasswordCredentials;
use Symfony\Component\Security\Http\Authenticator\Passport\Passport;
use Symfony\Component\Security\Http\Util\TargetPathTrait;
use Eduxplus\CoreBundle\Lib\Service\MobileMaskService;

class PasswordLoginAuthenticator extends AbstractLoginFormAuthenticator
{
    use TargetPathTrait;

    private $entityManager;
    private $urlGenerator;
    private $csrfTokenManager;
    private $passwordEncoder;
    private $captchaService;
    private $baseService;
    private $mobileMaskService;
    protected $userRepository;

    public function __construct(EntityManagerInterface $entityManager,
                                UrlGeneratorInterface $urlGenerator,
                                CsrfTokenManagerInterface $csrfTokenManager,
                                UserPasswordHasherInterface $passwordEncoder,
                                CaptchaService $captchaService,
                                MobileMaskService $mobileMaskService,
                                BaseService $baseService,
                                BaseUserRepository $userRepository
    )
    {
        $this->entityManager = $entityManager;
        $this->urlGenerator = $urlGenerator;
        $this->csrfTokenManager = $csrfTokenManager;
        $this->passwordEncoder = $passwordEncoder;
        $this->captchaService = $captchaService;
        $this->baseService = $baseService;
        $this->mobileMaskService = $mobileMaskService;
        $this->userRepository = $userRepository;
    }

    public function start(Request $request, AuthenticationException $authException = null): Response
    {
        if(($request->getRequestFormat() == 'json') || (in_array("application/json", $request->getAcceptableContentTypes()))){
                throw new NeedLoginException();
        }else{
            $url = $this->getLoginUrl($request);
            return new RedirectResponse($url);
        }
    }

    public function supports(Request $request): bool
    {
        return 'admin_login' === $request->attributes->get('_route') && $request->isMethod('POST');
    }

    public function getCredentials(Request $request)
    {
        $credentials = [
            'mobile' => $request->request->get('mobile'),
            'password' => $request->request->get('password'),
            'csrf_token' => $request->request->get('CsrfToken'),
            'recaptcha'=> $request->request->get('recaptcha'),
        ];

        $this->passwdCheck($credentials, $request);

        $request->getSession()->set(
            Security::LAST_USERNAME,
            $credentials['mobile']
        );

        return $credentials;
    }

    protected function passwdCheck($credentials, Request $request){

        $token = new CsrfToken('authenticate', $credentials['csrf_token']);
        if (!$this->csrfTokenManager->isTokenValid($token)) {
            throw new InvalidCsrfTokenException();
        }

        $recaptcha = $credentials['recaptcha'];
        if(!$recaptcha){
            throw new CustomUserMessageAuthenticationException('验证码不能为空！');
        }
        $session = $request->getSession();
        if(!$this->captchaService->check($session, $recaptcha, "adminLogin")){
            throw new CustomUserMessageAuthenticationException('验证码错误！');
        }
    }


    public function onAuthenticationSuccess(Request $request, TokenInterface $token, $providerKey): Response
    {
        if ($targetPath = $request->request->get('TargetPath')) {
            return new RedirectResponse($targetPath);
        }
        $response = new RedirectResponse($this->urlGenerator->generate('admin_index'));
        return $response;
    }

    protected function getLoginUrl(Request $request):string
    {
        $goto = $request->getRequestUri();
        $gotoStr = $goto?"?goto=".$goto:"";
        return $this->urlGenerator->generate('admin_login').$gotoStr;
    }

    public function authenticate(Request $request): Passport
    {
        $credentials = $this->getCredentials($request);

        $mobileMask =  $this->mobileMaskService->encrypt($credentials['mobile']);

        $user = $this->entityManager->getRepository(BaseUser::class)->findOneBy(['mobileMask' => $mobileMask]);

        if (!$user) {
            // fail authentication with a custom error
            throw new CustomUserMessageAuthenticationException('手机号码或者密码错误！');
        }

        if(!$user->getIsAdmin()){
            throw new CustomUserMessageAuthenticationException('没有权限登入后台！');
        }

        if($user->getIsLock()){
            throw new CustomUserMessageAuthenticationException('该用户已被锁定，没有权限登入！');
        }

        $passport = new Passport(
            new UserBadge($user->getUuid(), [$this->userRepository, "loadUserByIdentifier"]),
            new PasswordCredentials($credentials['password'])
        );
        if ($this->userRepository instanceof PasswordUpgraderInterface) {
            $passport->addBadge(new PasswordUpgradeBadge($credentials['password'], $this->userRepository));
        }

        return $passport;

    }
}
