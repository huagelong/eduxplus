<?php
/**
 * @Author: kaihui.wang
 * @Contact  hpuwang@gmail.com
 * @Version: 1.0.0
 * @Date: 2020/6/15 12:01
 */

namespace App\Bundle\AppBundle\Service;


use App\Bundle\AppBundle\Lib\Base\AppBaseService;
use App\Bundle\AppBundle\Lib\Service\CacheService;
use App\Bundle\AppBundle\Lib\Service\Im\TengxunyunImService;
use App\Entity\TeachLiveChatLog;

class ImService extends AppBaseService
{
    const GROUPOWNERID = 1;

    protected $tengxunyunImService;
    protected $cacheService;
    protected $userService;
    
    public function __construct(TengxunyunImService $tengxunyunImService, 
                        CacheService $cacheService,
                        UserService $userService
                        )
    {
        $this->tengxunyunImService = $tengxunyunImService;
        $this->cacheService = $cacheService;
        $this->userService = $userService;
    }

    /**
     * 初始化用户
     *
     * @param int $uid
     * @return void
     */
    public function initUser($uid){
        $sql = "SELECT a FROM App:BaseUser a WHERE a.id=:id ";
        $userInfo = $this->fetchOne($sql, ["id"=>$uid], 1);
        if(!$userInfo) return $this->error()->add("用户不存在!");
        $imImported = $userInfo->getImImported();
        if($imImported) return true;
        $uuid = $userInfo->getUuid();
        $displayName = $userInfo->getFullName()?$userInfo->getFullName():$userInfo->getDisplayName();
        $gravatar = $userInfo->getGravatar();
        $sex = $userInfo->getSex();
        $role = $userInfo->getRealRole();
        //初始化导入
        $rs = $this->tengxunyunImService->multiaccountImport($uuid);
        if(!$rs) return $rs;
        //更新资料
        $upRs = $this->tengxunyunImService->portraitSet($uuid, $displayName, $gravatar, $sex, $role);
        if($upRs){
            //更新状态
            $userInfo->setImImported(1);
            $this->save($userInfo);
        }
        return $upRs;
    }

    /**
     * 生成用户sign
     *
     * @param string $uuid
     * @param int $expire
     * @return void
     */
    public function createUserSig($uuid, $expire=86400*180){
        return $this->tengxunyunImService->createUserSig($uuid, $expire);
    }

    /**
     * 获取 SDKAppID
     *
     * @return void
     */
    public function getSDKAppID(){
        return $this->getOption("app.tengxunyun.im.sdkAppid");
    }

    /**
     * 
     * 初始化groupId
     *
     * @param [type] $userUuid 管理员id
     * @param [type] $groupId 章节id
     * @param integer $number
     * @return void
     */
    public function initGroup($chapterId, $number=6000){
        $sql = "SELECT a FROM App:TeachCourseChapter a WHERE a.id=:id";
        $chapterInfo = $this->fetchOne($sql, ["id"=>$chapterId], 1);
        if(!$chapterInfo) return $this->error()->add("群组不存在!");
        $groupId = $chapterInfo->getImGroupId();
        if($groupId) return $groupId;
         //群主信息
         $userUuid = $this->userService->getUserById(self::GROUPOWNERID);
        $rs = $this->tengxunyunImService->createLiveChatRoom($userUuid, $chapterId, $number);
        if(!$rs) return $rs;
        $chapterInfo->setImGroupId($rs);
        $this->save($chapterInfo);
        return $rs;
    }

    /**
     * 加入群
     *
     * @param [type] $groupId
     * @param [type] $userUUid
     * @return void
     */
    public function addGroupMember($groupId, $userUUid){
        $key = "ImService_addGroupMember_".$groupId."_".$userUUid;
        $check = $this->cacheService->get($key);
        if($check) return true;
        $sql = "SELECT a FROM App:BaseUser a WHERE a.uuid=:uuid ";
        $userInfo = $this->fetchOne($sql, ["uuid"=>$userUUid]);
        $realUser = $userInfo['realRole'];
        $rs = $this->tengxunyunImService->addGroupMember($groupId, $userUUid);
        if($realUser<5){
            //更新为管理员
            $this->tengxunyunImService->modifyGroupMemberInfo($groupId, $userUUid, "Admin");  
        }
        //一个小时过期
        $this->cacheService->set($key, 1, 3600);
        return $rs;
    }

    /**
     * 设置/取消禁言
     *
     * @param [type] $groupId
     * @param [type] $uuids
     * @param integer $shutUpTime
     * @return void
     */
    public function forbidSendMsg($groupId, $uuids, $shutUpTime=3600){
        return $this->tengxunyunImService->forbidSendMsg($groupId, $uuids, $shutUpTime);
    }

    /**
     * 保存聊天日志
     *
     * @param [type] $groupId
     * @param string $ip
     * @param string $platform
     * @param int $msgTime
     * @param string $jsonContent
     * @return void
     */
    public function saveChatLog($groupId, $ip, $platform, $msgTime, $jsonContent){
        $sql = "SELECT a FROM App:TeachCourseChapter a WHERE a.imGroupId=:imGroupId";
        $chapterInfo = $this->fetchOne($sql, ["imGroupId"=>$groupId]);
        if(!$chapterInfo) return ;
        $chapterId = $chapterInfo['id'];
        $model = new TeachLiveChatLog();
        $model->setChapterId($chapterId);
        $model->setMsgTime($msgTime);
        $model->setIp($ip);
        $model->setPlatform($platform);
        $model->setContent($jsonContent);
        return $this->save($model);
    }

}