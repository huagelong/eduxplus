$(function(){
  var uuid = chatuuid;
  var sign = chatsign;
  var groupId = chatgroupId;
  var sdkAppID = chatsdkAppID;

  moment.locale("zh-cn");
  var facepath= "/bundles/eduxpluswebsite/default/images/common/face/";
  var ns=$.initNamespaceStorage('memberList');
  let memberList = ns.sessionStorage;
  var msgFirstInit=1;
  var init=1;

  $('.faceBtn').qqFace({
    assign: 'chatMsg', //给输入框赋值
    path: facepath //表情图片存放的路径
  });

  var options = {
    SDKAppID: sdkAppID
};

  // 创建 SDK 实例，`TIM.create()`方法对于同一个 `SDKAppID` 只会返回同一份实例
  var tim = TIM.create(options);
// 设置 SDK 日志输出级别，详细分级请参见 setLogLevel 接口的说明
//tim.setLogLevel(0); // 普通级别，日志量较多，接入时建议使用
  tim.setLogLevel(1); // release 级别，SDK 输出关键信息，生产环境时建议使用
// 注册 COS SDK 插件
  tim.registerPlugin({'cos-js-sdk': COS});

// 接下来可以通过 tim 进行事件绑定和构建 IM 应用
  tim.on(TIM.EVENT.SDK_READY,onReadyStateUpdate);
  tim.on(TIM.EVENT.SDK_NOT_READY,onReadyStateUpdate);

  function messageReadByPeer(event) {}
  tim.off(TIM.EVENT.MESSAGE_READ_BY_PEER, messageReadByPeer);

  function profileUpdated(event) {}
  tim.off(TIM.EVENT.PROFILE_UPDATED,profileUpdated);

  function netStateChange(event) {}
  tim.off(TIM.EVENT.NET_STATE_CHANGE, netStateChange);

  tim.on(TIM.EVENT.CONVERSATION_LIST_UPDATED, conversationListUpdated);
  tim.on(TIM.EVENT.MESSAGE_RECEIVED, messageReceive);
  tim.on(TIM.EVENT.MESSAGE_REVOKED, messageRevoked);
  tim.on(TIM.EVENT.GROUP_LIST_UPDATED, groupListUpdated);
  tim.on(TIM.EVENT.BLACKLIST_UPDATED, blackListUpdated);
  tim.on(TIM.EVENT.ERROR, eventError);
  tim.on(TIM.EVENT.KICKED_OUT, kickedOut);


  $("#chatBtn").click(function(){
    var disable = $(this).data("disabled");
    if(disable){
      showMsg(400, "当前还不能发言");
      return ;
    }
    var chatContent = $("#chatMsg").val();
    if(!chatContent) return false;
    let message = tim.createTextMessage({
      to: groupId,
      conversationType: TIM.TYPES.CONV_GROUP,
      payload: {
        text: chatContent
      }
    });
    let promise = tim.sendMessage(message);
    promise.then(function(imResponse) {
      // 发送成功
      $("#chatMsg").val('');
    }).catch(function(imError) {
      // 发送失败
      show(400, imError.message);
      console.warn('sendMessage error:', imError);
    });
  });

  function messageReceive(event) {
    // 收到推送的单聊、群聊、群提示、群系统通知的新消息，可通过遍历 event.data 获取消息列表数据并渲染到页面
    // event.name - TIM.EVENT.MESSAGE_RECEIVED
    // event.data - 存储 Message 对象的数组 - [Message]
    if(event.data){
      //todo
      var datalist = event.data;
      var tmp=[];
      for(index in  datalist){
        var msg = datalist[index].payload.text
        var uuuid =  datalist[index].lastMessage.fromAccount
        var lastTime = datalist[index].lastMessage.lastTime
        tmp.push({"msg":msg,"uuid":uuuid,"time":lastTime})
      }
      // console.log(tmp);
      setMsg(tmp, 1);
    }
  }

  function messageRevoked(event) {
    // 收到消息被撤回的通知
    // event.name - TIM.EVENT.MESSAGE_REVOKED
    // event.data - 存储 Message 对象的数组 - [Message] - 每个 Message 对象的 isRevoked 属性值为 true
    console.log(event.data);
  }


  function conversationListUpdated(event) {
    // 收到会话列表更新通知，可通过遍历 event.data 获取会话列表数据并渲染到页面
    // event.name - TIM.EVENT.CONVERSATION_LIST_UPDATED
    // event.data - 存储 Conversation 对象的数组 - [Conversation]
    if(init){
      return ;
    }
    if(event.data){
      var datalist = event.data;
      // console.log(datalist);
      var tmp=[];
      for(index in datalist){
        var msg = datalist[index].lastMessage.payload.text
        var fromAccountUUid =  datalist[index].lastMessage.fromAccount
        var lastTime = datalist[index].lastMessage.lastTime
        var realGroupid = datalist[index].conversationID
        if(("GROUP"+groupId) == realGroupid){
          tmp.push({"msg":msg,"uuid":fromAccountUUid,"time":lastTime});
        }
      }
      setMsg(tmp, 1);
    }
  }

  function groupListUpdated(event) {
    // 收到群组列表更新通知，可通过遍历 event.data 获取群组列表数据并渲染到页面
    // event.name - TIM.EVENT.GROUP_LIST_UPDATED
    // event.data - 存储 Group 对象的数组 - [Group]
    // console.log(event.data);
  }

  function blackListUpdated(event) {
    // 收到黑名单列表更新通知
    // event.name - TIM.EVENT.BLACKLIST_UPDATED
    // event.data - 存储 userID 的数组 - [userID]
    //console.log(event.data);
  }

  function kickedOut(event) {
    // 收到被踢下线通知
    // event.name - TIM.EVENT.KICKED_OUT
    // event.data.type - 被踢下线的原因，例如:
    //    - TIM.TYPES.KICKED_OUT_MULT_ACCOUNT 多实例登录被踢
    //    - TIM.TYPES.KICKED_OUT_MULT_DEVICE 多终端登录被踢
    //    - TIM.TYPES.KICKED_OUT_USERSIG_EXPIRED 签名过期被踢 （v2.4.0起支持）。
    console.log(event.data);
  }

  function eventError(event) {
    if(event.data.code>0){
      show(400, event.data.message);
    }
  }

// 开始登录
  let promise = tim.login({userID: uuid, userSig: sign});
  promise.then(function(imResponse) {
    if (imResponse.data.repeatLogin === true) {
      // 标识账号已登录，本次登录操作为重复登录。v2.5.1 起支持
      console.log(imResponse.data.errorInfo);

    }else{//第一次登陆
      //todo
    }
  }).catch(function(imError) {
    show(400, imError.message);
    console.warn('login error:', imError); // 登录失败的相关信息
  });

  function onReadyStateUpdate(event){
    var name = event.name
    const isSDKReady = name === TIM.EVENT.SDK_READY ? true : false
    //初始化信息
    if (isSDKReady) {
      getMsgList(groupId,0);
      // getGroupInfo(groupId);
      //  getGroupMember(groupId);
      $("#chatBtn").removeAttr("disabled");
    }else{
      $("#chatBtn").attr("disabled", "disabled");
    }
  }

  function getGroupInfo(groupId){
    let promise = tim.getGroupProfile({ groupID: groupId});
    promise.then(function(imResponse) {
      // console.log(imResponse.data.group);
      getGroupMember(groupId, imResponse.data.group.memberNum);
    }).catch(function(imError) {
      console.warn('getGroupProfile error:', imError); // 获取群详细资料失败的相关信息
    });
  }

  function getGroupMember(groupId, count){
    let promiseMember = tim.getGroupMemberList({ groupID: groupId, count: count, offset:0 }); // 从0开始拉取30个群成员
    promiseMember.then(function(imResponse) {
      // var mlist = imResponse.data.memberList;
      // for(index in mlist){
      //   memberList.set(mlist[index].userID, mlist[index]);
      // }
      // console.log(memberList);
    }).catch(function(imError) {
      console.warn('getGroupMemberList error:', imError);
    });
  }

//获取消息列表
  function getMsgList(groupId, nextReqMessageID){
    let promiseMsg;
    if(nextReqMessageID){
      promiseMsg = tim.getMessageList({conversationID: 'GROUP'+groupId, count: 10, nextReqMessageID:nextReqMessageID});
    }else{
      promiseMsg = tim.getMessageList({conversationID: 'GROUP'+groupId, count: 10});
    }
    promiseMsg.then(function(imResponse) {
      const messageList = imResponse.data.messageList; // 消息列表。
      const nextReqMessageID = imResponse.data.nextReqMessageID; // 用于续拉，分页续拉时需传入该字段。
      const isCompleted = imResponse.data.isCompleted; // 表示是否已经拉完所有消息。
      //处理消息列表
      // console.log(messageList);
      if(messageList){
        var datalist = messageList;
        var tmp=[];
        for(index in  datalist){
          var msg = datalist[index].payload.text
          var uuid = datalist[index].from
          var time = datalist[index].time
          tmp.push({"msg":msg,"uuid":uuid,"time":time})
        }
        if(msgFirstInit){
          setMsg(tmp, 1);
        }else{
          setMsg(tmp, 0);
        }

      }
      if(!isCompleted){
        msgFirstInit = 0;
        setTimeout(function(){
          getMsgList(groupId, nextReqMessageID);
        }, 3000);
      }else{
        init = 0;
      }

    });
  }


  async function setMsg(tmp, isAppend){
    var str="";
    for (index in tmp){
      var uuid = tmp[index].uuid;
      var msg = tmp[index].msg;
      var time = tmp[index].time;

      if(memberList.isSet(uuid)){
        var userInfo = memberList.get(uuid);
        var nickname = userInfo.nick;
        var role = userInfo.role;
      }else{
        console.log(uuid);
        let imResponse = await tim.getUserProfile({
          userIDList: [uuid] // 请注意：即使只拉取一个用户的资料，也需要用数组类型，例如：userIDList: ['user1']
        });
        // console.log(imResponse.data); // 存储用户资料的数组 - [Profile]
        var userInfo = imResponse.data[0];
        console.log(userInfo);
        if(!userInfo) return ;
        var nickname = userInfo.nick;
        var role = userInfo.role;
        memberList.set(userInfo.userID, userInfo);
      }

      var roleStr = "";
      if(role < 5){
        roleStr = '<span class="layui-badge layui-bg-gray">老师</span>';
      }
      var msgStr = ' <li class="list_not_teacher">' +
        '            <div class="personName expClear">' +
        '                <span class="li_timer">'+timeFromNow(time)+'</span>' +
        '                <div class="personUsername">' +
        '                    <p>'+nickname+'</p>'+roleStr+
        '                </div>' +
        '            </div>' +
        '            <div class="newsMsg">'+formatContent(msg)+'</div>' +
        '        </li>\r\n';
      str = str+msgStr;
    }
    // console.log(str);
    if(isAppend){
      $("#chatBox").append(str);
    }else{
      $("#chatBox").prepend(str);
    }
    tagListBottom();
  }

  function msgToFace (msg) { //处理消息图标表情
    if(!msg) {
      return '';
    }
    var em_reg = /\[em2\_([0-9]+)\]/g,
      imgs = msg.match(em_reg) || [];
    imgs.forEach(function(item) {
      var old_i = item;
      var _name = old_i.replace('[', '').replace(']', '');
      var _i = _name.split('_')[1]*1;
      if(_i < 21) {
        _i = _i<10? '0'+_i : ''+_i;
        _i = facepath + _i + '.png';
        _i = '<img src="' + _i + '" />';
        msg = msg.replace(old_i, _i);
      }
    })
    return msg;
  }

  function formatContent(content){
    content = content || '';

    // 过滤转义的空格
    content = content.replace(/((&nbsp;)|^(\s*)|(\s*)$)/ig, '');
//          content = content.replace(/<(\/)?(?!img)[^>]+>/ig,'<$1span>');//20150720前,滚轴问题(<br>)
    content = content.replace(/<(\/)?(?!img)[^>]+>/ig, '');//20150720 滚轴修正
    // 处理A，img 标签自带的样式
    content = content.replace(/(id="[^"]+"|class="[^"]+"|style="[^"]+")/ig, '');
    // 给链接文本添加链接
    content = content.replace(/^(\s)*|(\s)*$/g, '');

    return msgToFace(content);
  }

  function timeFromNow(time) {
    time = time*1000;
    const format="YYYY-MM-DD HH:mm:ss";
    const formatDate="YYYY-MM-DD";
    const formatTime="HH:mm:ss";
    // console.log(time)
    let timeStr=moment(time).format(format);
    // console.log(timeStr)
    if(moment(time).format(formatDate)===moment().format(formatDate)){
      const fromNowStr=moment(time).fromNow(true);
      if(fromNowStr.indexOf("小时")>0&&parseInt(fromNowStr)>5){
        timeStr="今天 "+moment(time).format(formatTime);
      }else {
        timeStr=fromNowStr+"前"
      }
    }
    return timeStr
  }

  function tagListBottom(){ // 内容列表滚动到底部
    var tag = $('.chatBox');
    var _h = tag.height();
    tag.parents('.chatContain').animate({ //讨论区向下滚动
      'scrollTop': _h + 'px'
    }, 100 );
  }

})
