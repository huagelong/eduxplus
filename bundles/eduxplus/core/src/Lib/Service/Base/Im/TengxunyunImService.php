<?php
/**
 * @Author: kaihui.wang
 * @Contact  hpuwang@gmail.com
 * @Version: 1.0.0
 * @Date: 2020/6/29 17:50
 */

namespace Eduxplus\CoreBundle\Lib\Service\Base\Im;

use Eduxplus\CoreBundle\Lib\Base\BaseService;
use Eduxplus\CoreBundle\Lib\Utils;
use Tencent\TLSSigAPIv2;
use Symfony\Contracts\Cache\ItemInterface;

class TengxunyunImService extends BaseService{

    /**
     *  获取用户sig
     */
    public function createUserSig($identifier, $expire=86400*180){
        $cachekey = "tengxunyunImService_UserSig_{$identifier}";
        $sdkappid = $this->getOption("app.tengxunyun.im.sdkAppid");
        $key = $this->getOption("app.tengxunyun.im.key");
        return $this->cache()->get($cachekey, function(ItemInterface $item) use($identifier,$expire,$sdkappid,$key){
            $item->expiresAfter($expire);
            $im = new TLSSigAPIv2($sdkappid, $key);
            return $im->genUserSig($identifier, $expire);
        });
    }

    /**
     * 设置/取消禁言
     *
     * @param [type] $groupId
     * @param array $uuids  
     * @param integer $shutUpTime  禁言时间， 为0时取消禁言
     * @return void
     */
    public function forbidSendMsg($groupId, $uuids, $shutUpTime=3600){
        $api="v4/group_open_http_svc/forbid_send_msg";
        $data = [];
        $data['GroupId'] = $groupId;
        $data['Members_Account'] = $uuids;
        $data['ShutUpTime'] = $shutUpTime;
        $errors = [
            10002=>"服务器内部错误，请重试",
            10003=>"请求命令字非法",
            10004=>"参数非法，请根据错误描述检查请求是否正确",
            10007=>"操作权限不足",
            10010=>"群组不存在，或者曾经存在过，但是目前已经被解散",
            10015=>"群组 ID 非法，请检查群组 ID 是否填写正确",
        ];
        $result = $this->reqApi($api, $data, $errors);
        return  $result;
    }

    /**
     * 修改群成员信息
     *
     * @param [type] $groupId
     * @param [type] $uuid
     * @param string $role  群内身份，包括 Owner 群主、Admin 群管理员以及 Member 群成员
     * @return void
     */
    public function modifyGroupMemberInfo($groupId,$uuid, $role='Member'){
        $api="v4/group_open_http_svc/modify_group_member_info";
        $data = [];
        $data['GroupId'] = $groupId;
        $data['Member_Account'] = $uuid;
        $data['Role'] = $role;
        $errors = [
            10002=>"服务器内部错误，请重试",
            10003=>"请求命令字非法",
            10004=>"参数非法，请根据错误描述检查请求是否正确",
            10007=>"操作权限不足，例如 Public 群组中普通成员尝试执行踢人操作，但只有 App 管理员才有权限",
            10010=>"群组不存在，或者曾经存在过，但是目前已经被解散",
            10015=>"群组 ID 非法，请检查群组 ID 是否填写正确",
            80001=>"文本安全打击;请检查修改的群成员资料中是否包含敏感词汇",
        ];
        $result = $this->reqApi($api, $data, $errors);
        return  $result;
    }

    /**
     * 加入群
     */
    public function addGroupMember($groupId, $userUUids){
        $api="v4/group_open_http_svc/add_group_member";
        $data = [];
        $data['GroupId'] = $groupId;
        if(!is_array($userUUids)){
            $userUUids = [$userUUids];
        }
        foreach($userUUids as $v){
            $data['MemberList'][] = ["Member_Account"=>$v];
        }
        $errors = [
            10002=>"服务器内部错误，请重试",
            10003=>"请求命令字非法",
            10004=>"参数非法，请根据错误描述检查请求是否正确",
            10005=>"请求包体中携带的 Member_Account 数量超过500",
            10007=>"操作权限不足，请确认该群组类型是否支持邀请加群。例如 AVChatRoom 和 BChatRoom 不允许任何人拉人入群",
            10014=>"群已满员，无法将请求中的用户加入群组，请尝试减少请求中 Member_Account 的数量或者修改该【群基础资料】的 MaxMemberNum 字段值。跳转到 群基础资料",
            10010=>"群组不存在，或者曾经存在过，但是目前已经被解散",
            10015=>"群组 ID 非法，请检查群组 ID 是否填写正确",
            10016=>"开发者后台通过第三方回调拒绝本次操作",
            10019=>"请求的 UserID 不存在，请检查 MemberList 中的所有 Member_Account 是否正确",
            10026=>"该 SDKAppID 请求的命令字已被禁用，请联系客服",
            10037=>"被邀请的成员加入群组数量超过了限制，请检查并删除群组数量超过限制的 Member_Account 或者按实际需要【购买升级】。跳转到 功能套餐包",
        ];
        $result = $this->reqApi($api, $data, $errors);
        return  $result;
    }

    /**
     * 创建直播聊天群
     *
     * @param [type] $userUuid 群主id
     * @param [type] $groupId 群id  用chapter_id 即可
     * @param mixed $number 群成员数目
     * @return void
     */
    public function createLiveChatRoom($userUuid, $groupId, $number=6000){
        $name = "lcr_".$groupId;
        $api="v4/group_open_http_svc/create_group";
        $data = [];
        if($userUuid) $data["Owner_Account"] = $userUuid;
        $data["Type"]= 'ChatRoom';
        $data["Name"] = $name;
        $data['GroupId'] = $groupId;
        $data['MaxMemberCount'] = $number;

        $errors = [
            40001=>"请求参数错误，请根据错误描述检查请求参数",
            40003=>"请求的用户帐号不存在",
            40004=>"请求需要 App 管理员权限",
            40005=>"资料字段中包含敏感词",
            40006=>"服务器内部错误，请稍后重试",
            40008=>"没有资料字段的写权限",
            40009=>"资料字段的 Tag 不存在",
            40601=>"资料字段的 Value 长度超过500字节",
            40605=>"标配资料字段的 Value错误，详情可参见 标配资料字段",
            40610=>"资料字段的 Value 类型不匹配，详情可参见 标配资料字段",
        ];
        $result = $this->reqApi($api, $data, $errors);
        if(!$result) return $result;
        return  $result['GroupId']?$result['GroupId']:false;
    }

    /**
     * 设置资料
     *
     * @param [type] $userUuid
     * @param [type] $displayName
     * @param [type] $gravatar
     * @param [type] $sex 0-未知，1-男，2-女
     * @param integer $role 角色 1-管理员 ，2-讲师，3-助教，4-班主任,5-学生
     * @return void
     */
    public function portraitSet($userUuid, $displayName, $gravatar, $sex, $role=5){
        $api="v4/profile/portrait_set";
        $data = [];
        $data["From_Account"] = $userUuid."";
        $data["ProfileItem"][] = ['Tag'=>'Tag_Profile_IM_Nick', "Value"=>$displayName];
        $sexStr = "Gender_Type_Unknown";
        if($sex == 1){
            $sexStr = "Gender_Type_Male";
        }else if ($sex == 2){
            $sexStr = "Gender_Type_Female";
        }
        $data["ProfileItem"][] = ['Tag'=>'Tag_Profile_IM_Gender', "Value"=>$sexStr];
        $data["ProfileItem"][] = ['Tag'=>'Tag_Profile_IM_Image', "Value"=>$gravatar];
        $data["ProfileItem"][] = ['Tag'=>'Tag_Profile_IM_Role', "Value"=>$role];

        $errors = [
            40001=>"请求参数错误，请根据错误描述检查请求参数",
            40003=>"请求的用户帐号不存在",
            40004=>"请求需要 App 管理员权限",
            40005=>"资料字段中包含敏感词",
            40006=>"服务器内部错误，请稍后重试",
            40008=>"没有资料字段的写权限",
            40009=>"资料字段的 Tag 不存在",
            40601=>"资料字段的 Value 长度超过500字节",
            40605=>"标配资料字段的 Value错误，详情可参见 标配资料字段",
            40610=>"资料字段的 Value 类型不匹配，详情可参见 标配资料字段",
        ];
        $result = $this->reqApi($api, $data, $errors);
        return  $result;
    }
    

    /**
     * 导入单个账号
     *
     * @param [type] $userUuid
     * @param [type] $displayName
     * @param [type] $gravatar
     * @return void
     */
    public function accountImport($userUuid, $displayName, $gravatar){
        $api="v4/im_open_login_svc/account_import";
        $data = [];
        $data["Identifier"] = $userUuid;
        $data["Nick"] = $displayName;
        $data["FaceUrl"] = $gravatar;
        $errors = [
            0005=>"资料字段中包含敏感词",
            40006=>"设置资料时服务器内部错误，请稍后重试",
            40601=>"资料字段的 Value 长度超过500字节",
        ];
        $result = $this->reqApi($api, $data, $errors);
        return  $result;
    }


    /**
     * 导入多个账号
     *
     * @param [type] $userUuIds
     * @return void
     */
    public function multiaccountImport($userUuIds){
        $api="v4/im_open_login_svc/multiaccount_import";
        $userUuIds = is_array($userUuIds)?$userUuIds:[$userUuIds.""];
        $data = [];
        $data['Accounts'] = $userUuIds;
        $errors = [];
        $result = $this->reqApi($api, $data, $errors);
        if(!$result) return false;
        return $result['FailAccounts']?$result['FailAccounts']:true;
    }

    /**
     * 检查用户
     *
     * @param [type] $userUUids
     * @return void
     */
    public function accountCheck($userUuIds){
        $api="v4/im_open_login_svc/account_check";
        $data=[];
        if(is_array($userUuIds)){
            foreach($userUuIds as $k=>$v){
                $data['CheckItem'][] = ["UserID"=> $v];
            }
        }else{
            $data['CheckItem'][] = ["UserID"=>$userUuIds];
        }

        $errors = [];
        $result = $this->reqApi($api, $data, $errors);
        $rs = [];
        if($result){
            foreach($result['ResultItem'] as $v){
                $rs[$v['UserID']] = $v['AccountStatus']=="NotImported"?0:1;
            }
        }
        return is_array($userUuIds)?$rs:$rs[$userUuIds];
    }

    /**
     * api 请求
     *
     * @param [type] $apiPath
     * @param [type] $params
     * @param array $errors
     * @return void
     */
    public function reqApi($apiPath, $params, $errors=[]){
            $sdkappid =  $this->getOption("app.tengxunyun.im.sdkAppid");
            $identifier = $this->getOption("app.tengxunyun.im.identifier");
            $usersig = $this->createUserSig($identifier, 600);
            $url = "https://console.tim.qq.com/{$apiPath}?sdkappid={$sdkappid}&identifier={$identifier}&usersig={$usersig}&random=".time()."&contenttype=json";
            $body = json_encode($params);
            $content = $this->baseCurlGet($url, "POST", $body);
            $result = json_decode($content, true);
            if($result['ActionStatus'] == "FAIL"){
                return $this->formatError($result['ErrorCode'], $errors, $result['ErrorInfo']);
            }
            return $result;
    }

    /**
     * 错误格式化
     *
     * @param [type] $code
     * @param [type] $errors
     * @param string $defaultErrorMsg
     * @return void
     */
    protected function formatError($code, $errors, $defaultErrorMsg="未知错误"){
        $cerrors = [
            60002=>"HTTP 解析错误 ，请检查 HTTP 请求 URL 格式",
            60003=>"HTTP 请求 JSON 解析错误，请检查 JSON 格式",
            60004=>"请求 URL 或 JSON 包体中帐号或签名错误",
            60005=>"请求 URL 或 JSON 包体中帐号或签名错误",
            60006=>"SDKAppID 失效，请核对 SDKAppID 有效性",
            60007=>"REST 接口调用频率超过限制，请降低请求频率",
            60008=>"服务请求超时或 HTTP 请求格式错误，请检查并重试",
            60009=>"请求资源错误，请检查请求 URL",
            60010=>"请求需要 App 管理员权限",
            60011=>"SDKAppID 请求频率超限，请降低请求频率",
            60012=>"REST 接口需要带 SDKAppID，请检查请求 URL 中的 SDKAppID",
            60013=>"HTTP 响应包 JSON 解析错误",
            60014=>"置换帐号超时",
            60015=>"请求包体帐号类型错误，请确认帐号为字符串格式",
            70169=>"服务端内部超时，请稍后重试",
            70202=>"服务端内部超时，请稍后重试",
            70402=>"参数非法，请检查必填字段是否填充，或者字段的填充是否符合协议要求",
            70403=>"请求失败，需要 App 管理员权限",
            70500=>"服务器内部错误，请稍后重试",
            80001=>"消息文本安全打击"
        ];
        $errors = array_merge($cerrors, $errors);
        $msg = isset($errors[$code])?$errors[$code]:$defaultErrorMsg;
        throw new \Exception($msg);
//        return $this->error()->add($msg);
    }

}
