<?php
/**
 * @Author: kaihui.wang
 * @Contact  hpuwang@gmail.com
 * @Version: 1.0.0
 * @Date: 2020/5/25 17:21
 */

namespace App\Bundle\AppBundle\Controller;


use Eduxplus\CoreBundle\Lib\Base\BaseHtmlController;
use Eduxplus\CoreBundle\Lib\Service\HelperService;
use Eduxplus\CoreBundle\Lib\Service\UploadService;
use Eduxplus\CoreBundle\Lib\Service\ValidateService;
use App\Bundle\AppBundle\Service\GlobService;
use App\Bundle\AppBundle\Service\GoodsService;
use App\Bundle\AppBundle\Service\MsgService;
use App\Bundle\AppBundle\Service\UserService;
use Eduxplus\CoreBundle\Exception\NeedLoginException;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Http\Logout\LogoutHandlerInterface;

class UserController extends BaseHtmlController
{

    /**
     * @Rest\Get("/login", name="app_login")
     */
    public function loginAction(Request $request){
        $mobile = $request->cookies->get("site_login_mobile");
        $goto = $request->get("goto");
        $gotoCookie = $request->cookies->get("site_login_goto");
        $url = $goto?$goto:($gotoCookie?$gotoCookie:"/");
        $data = [];
        $data['mobile'] = $mobile;
        $data['goto'] = $url;
        return $this->render('@AppBundle/user/login.html.twig', $data);
    }

    /**
     * @Rest\Post("/login", name="app_logindo")
     */
    public function logininAction(){}


    /**
     * @Rest\Get("/logout", name="app_logout")
     */
    public function logoutAction(){}

    /**
     * @Rest\Get("/userMenu", name="app_user_userMenu")
     */
    public function userMenuAction(MsgService $msgService){
        $user = $this->getUser();
        $data = [];
        $data['userinfo'] = $user;
        $data['msgUnreadCount'] = 0;
        if($user){
            $uid = $this->getUid();
            $count =  $msgService->msgUnReadCount($uid);
            $data['msgUnreadCount'] = $count>99?"99+":$count;
        }

        return $this->render('@AppBundle/user/userMenu.html.twig', $data);
    }

    public function userNavAction($route, MsgService $msgService){
        $user = $this->getUser();
        $data = [];
        $data['userinfo'] = $user;
        $data['route'] = $route;

        $uid = $this->getUid();
        $count =  $msgService->msgUnReadCount($uid);
        $data['msgUnreadCount'] = $count>99?"99+":$count;
        return $this->render('@AppBundle/user/userNav.html.twig', $data);
    }

    /**
     * 个人主页
     * @Rest\Get("/my", name="app_user_home")
     */
    public function indexAction(Request $request){
        $page = $request->get("page");
        $user = $this->getUser();
        $route = $request->get("_route");
        $data = [];
        $data['userinfo'] = $user;
        $data['route'] = $route;
        $data['page'] = $page;
        return $this->render('@AppBundle/user/index.html.twig', $data);
    }


    /**
     * @Rest\Get("/my/info", name="app_user_info")
     */
    public function infoAction(UserService $userService){
        $uid = $this->getUid();
        $userInfo = $userService->getUserById($uid);
        $data = [];
        $data["userInfo"] = $userInfo;
        $data['route'] = "app_user_info";
        return $this->render('@AppBundle/user/info.html.twig', $data);
    }

    /**
     * @Rest\Post("/my/info/do", name="app_user_info_do")
     */
    public function doinfoAction(Request $request, ValidateService $validateService, UserService $userService){
        $avatar = $request->get("avatar");
        $displayName = $request->get("displayName");
        $fullName = $request->get("fullName");
        $birthday = $request->get("birthday");
        $sex = (int) $request->get("sex");

        if(!$displayName) return $this->responseError("昵称不能为空!");
        if(!$fullName) return $this->responseError("姓名不能为空!");
        if(!$birthday) return $this->responseError("生日不能为空!");

        $validateService->nicknameValidate($displayName);

        if($this->error()->has()){
            return $this->responseError($this->error()->getLast());
        }

        $uid = $this->getUid();
        $userService->edit($uid, $displayName, $fullName, $birthday, $sex, $avatar);

        return $this->responseMsgRedirect("操作成功！", $this->generateUrl("app_user_info"));
    }

    /**
     * @Rest\Post("/my/uploadimg/do/{type}", name="app_my_uploadimg", defaults={"type":"img"})
     */
    public function uploadavatarAction($type = "img", Request $request, UploadService $uploadService)
    {
        $files = $request->files->all();

        if (!$files) {
            return $this->responseError("没有文件上传!");
        }

        try {
            $filePaths = [];
            foreach ($files as $file) {
                $size = $file->getFileInfo()->getSize();
                if($size > 1024*1024*2){
                    throw new \Exception("图片大小不超过2M");
                }
                $mimeType =$file->getClientMimeType();
                //image/gif
                if(stristr($mimeType, "image") === FALSE){
                    throw new \Exception("请上传图片");
                }
                $filePath = $uploadService->upload($file, $type);
                $filePaths[] = $filePath;
            }
            return $filePaths;
        } catch (\Exception $e) {
            return $this->responseError($e->getMessage());
        }
    }

    /**
     * @Rest\Get("/my/secure", name="app_user_secure")
     */
    public function secureAction(UserService $userService){
        $uid = $this->getUid();
        $userInfo = $userService->getUserById($uid);
        $data = [];
        $data["userInfo"] = $userInfo;
        $data['route'] = "app_user_secure";
        return $this->render('@AppBundle/user/secure.html.twig', $data);
    }

    /**
     * @Rest\Get("/my/fav", name="app_user_fav")
     */
    public function favAction(Request $request, GoodsService $goodsService){
        $uid = $this->getUid();
        $page = $request->get("page", 1);
        $pageSize = 40;
        list($pagination, $list) = $goodsService->favList($uid, $page, $pageSize);
        $data = [];
        $data['list'] = $list;
        $data['pagination'] = $pagination;
        $data['route'] = "app_user_fav";
        return $this->render('@AppBundle/user/fav.html.twig', $data);
    }

    /**
     * @Rest\Get("/my/msg", name="app_user_msg")
     */
    public function msgAction(Request $request , MsgService $msgService){
        $uid = $this->getUid();
        $page = $request->get("page", 1);
        $pageSize = 40;
        list($pagination, $list) = $msgService->msgList($uid, $page, $pageSize);
        $data = [];
        $data['list'] = $list;
        $data['pagination'] = $pagination;
        $data['route'] = "app_user_msg";
        return $this->render('@AppBundle/user/msg.html.twig', $data);
    }

    /**
     * 标记为已读
     * @Rest\Get("/my/do/msgread/{id}", name="app_user_msg_read_do")
     */
    public function msgReadAction($id, MsgService $msgService){
        $uid = $this->getUid();
        $msgService->doRead($uid, $id);
        return $this->responseRedirect($this->generateUrl("app_user_msg"));
    }

    /**
     * 全部标记为已读
     * @Rest\Get("/my/do/allmsgread", name="app_user_allmsg_read_do")
     */
    public function allmsgReadAction(MsgService $msgService){
        $uid = $this->getUid();
        $msgService->doAllMsgRead($uid);
        return $this->responseRedirect($this->generateUrl("app_user_msg"));
    }

    /**
     * 未读消息数量
     * @Rest\Get("/my/do/msgUnreadCount", name="app_user_msg_unread_count")
     */
    public function unreadMsgCountAction(MsgService $msgService){
        $uid = $this->getUid();
        $count =  $msgService->msgUnReadCount($uid);
        return $this->responseSuccess($count);
    }

    /**
     * @Rest\Get("/my/changeMobile/first", name="app_user_changeMobile_first")
     */
    public function changeMobileFirstAction(HelperService $helperService){
        $data = [];
        $user = $this->getUserInfo();
        $mobileView = $helperService->formatMobile($user['mobile']);
        $data['user'] = $user;
        $data['mobileView'] = $mobileView;
        $data['route'] = "app_user_secure";
        return $this->render('@AppBundle/user/changeMobileFirst.html.twig', $data);
    }


    /**
     * 检查手机验证码
     *
     * @Rest\Post("/my/checkMobileCode/do", name="app_user_checkMobileCode_do")
     */
    public function doCheckMobileCode(Request $request, GlobService $globService){
        $user = $this->getUserInfo();
        $mobile = $user["mobile"];
        $code = $request->get("code");

        if(!$code) return $this->responseError("短信验证码不能为空!");
        $check =  $globService->checkSmsCaptcha($code, $mobile, "changeMobileFirst");
        if(!$check) return $this->responseError("短信验证码验证失败!");
        $this->session()->set("mobileChecked", 1);

        return $this->responseRedirect($this->generateUrl('app_user_changeMobile_second'));
    }

    /**
     * @Rest\Get("/my/changeMobile/second", name="app_user_changeMobile_second")
     */
    public function changeMobileSecondAction(){
        $mobileChecked = $this->session()->get("mobileChecked");
        if(!$mobileChecked) return $this->redirectToRoute("app_user_changeMobile_first");
        $data = [];
        $data['route'] = "app_user_secure";
        return $this->render('@AppBundle/user/changeMobileSecond.html.twig', $data);
    }

    /**
     * 检查手机验证码
     *
     * @Rest\Post("/my/changeMobile/do", name="app_user_changeMobile_do")
     */
    public function doChangeMobileCode(Request $request,UserService $userService, GlobService $globService, ValidateService $validateService){
        $mobile = $request->get("mobile");
        $code = $request->get("code");

        if(!$mobile) return $this->responseError("手机号码不能为空!");
        if(!$validateService->mobileValidate($mobile)) return $this->responseError("手机号码格式错误!");
        if($userService->checkMobileExist($mobile)) return $this->responseError("手机号码已被注册!");
        if(!$code) return $this->responseError("短信验证码不能为空!");
        $check =  $globService->checkSmsCaptcha($code, $mobile, "changeMobile");
        if(!$check) return $this->responseError("短信验证码验证失败!");

        $uid = $this->getUid();
        $userService->updateMobile($uid, $mobile);

        return $this->responseMsgRedirect("操作成功!", $this->generateUrl('app_user_secure'));
    }

}
