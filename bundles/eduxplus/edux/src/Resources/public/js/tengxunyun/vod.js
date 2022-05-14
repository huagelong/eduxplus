/**
 * 腾讯云视频上传
 */

var progressId = "txUploadProgress"; // 上传进度id

function vodPlay(id, videoId, appid, token) {
  var tcplayer =  TCPlayer(id, {
    // id 为播放器容器 ID，必须与 html 中一致
    fileID: videoId, // 请传入需要播放的视频 filID（必须）
    appID: appid, // 请传入点播账号的 appID（必须）
    psign: token, //加密
    controlBar: {
      QualitySwitcherMenuButton: true,
    },
    controls: true,
    plugins: {
      ContinuePlay: {
        // 开启续播功能
        auto: true, //[可选] 是否在视频播放后自动续播
        text: "上次播放至 ", //[可选] 提示文案
        btnText: "恢复播放", //[可选] 按钮文案
      },
    },
  });
  return tcplayer;
}

/**
 * 加载和上传视频: 页面初始化时调用, 可以直接通过视频url加载视频到页面
 * 除maxSize字段非必传外, 其他字段均必传, 否则后果自负
 * @param appId 腾讯云appID
 * @param uploadVideoDivId 视频上传相关的div块id
 * @param videoFileId 视频文件id, 此字段作用是解决vue无法再次上传相同文件的问题, 将此id值清空即可再次上传
 * @param maxSize 上传最大容量，单位M
 * @param getUploadSignUrl 获取上传令牌url
 * @param getAndvancePlaySignUrl 获取超级播放器加密令牌
 */
function uploadVideo(
  appId,
  name,
  uploadVideoDivId,
  videoFileId,
  maxSize,
  getUploadSignUrl,
  getAndvancePlaySignUrl
) {
  // 获取签名, 腾讯云要求直接上传视频的客户端必须获取签名
  function getSignature() {
    return axios.get(getUploadSignUrl).then(function (response) {
      if (response.data.code != 200) {
        return showMsg(400, response.data.message);
      }

      return response.data.data;
    });
  }
  var app = new Vue({
    el: "#" + uploadVideoDivId,
    delimiters: ["${", "}"],
    props: {
      // 文件选取限制
      accept: {
        type: String,
        default: "*",
      },
    },
    data: {
      uploaderInfos: [],
    },
    created: function () {
      this.tcVod = new TcVod.default({
        getSignature: getSignature,
      });
    },
    methods: {
      vVodAdd: function () {
        this.$refs.vVodFile.click();
      },
      vVodUpload: function (event) {
        if (!checkVideo(event, maxSize, videoFileId)) {
          return;
        }
        onVideoSelected();
        var self = this;
        var videoFile = this.$refs.vVodFile.files[0];
        var uploadFileName = videoFile.name;
        var index = uploadFileName.lastIndexOf(".");
        if (index > 0) {
          var suffix = uploadFileName.substr(index + 1);
        } else {
          var suffix = "mp4";
        }

        var uploader = this.tcVod.upload({
          mediaFile: videoFile,
          mediaName: name + "." + suffix,
        });
        uploader.on("video_progress", function (info) {
          uploaderInfo.progress = info.percent;
          // 上传进度
          var percent = Math.floor(uploaderInfo.progress * 100) + "%";
          $("#" + progressId).text("正在上传 : " + percent);
        });
        uploader.on("video_upload", function (info) {
          uploaderInfo.isVideoUploadSuccess = true;
        });
        var uploaderInfo = {
          videoInfo: uploader.videoInfo,
          isVideoUploadSuccess: false,
          isVideoUploadCancel: false,
          progress: 0,
          fileId: "",
          videoUrl: "",
          cancel: function () {
            uploaderInfo.isVideoUploadCancel = true;
            uploader.cancel();
          },
        };
        this.uploaderInfos.push(uploaderInfo);

        uploader
          .done()
          .then(function (doneResult) {
            uploaderInfo.fileId = doneResult.fileId;
            return doneResult.video.url;
          })
          .then(function (videoUrl) {
            uploaderInfo.videoUrl = videoUrl;
            onVideoUploaded(
              appId,
              getAndvancePlaySignUrl,
              videoFileId,
              uploaderInfo
            );
          });
      },
    },
  });
}

/**
 * 选择视频后显示上传进度
 */
function onVideoSelected() {
  $("#" + progressId).text("正在上传 : 0%");
  $("#" + progressId).show();
}

/**
 * 视频上传完成
 * @param formId
 * @param name
 * @param videoUrl
 * @param videoFileId
 */
function onVideoUploaded(
  appId,
  getAndvancePlaySignUrl,
  videoFileId,
  uploaderInfo
) {
  //取消遮挡
  $("#" + progressId).hide();
  showMsg(200, "上传完成");
  // 上传完成后清空fileId, 否则vue无法再次选择此文件
  $("#" + videoFileId).val("");

  $("#videoId").val(uploaderInfo.fileId);
  $("form:first").submit();
  //预览
  // axios
  //   .get(getAndvancePlaySignUrl + "?videoId=" + uploaderInfo.fileId)
  //   .then(function (response) {
  //     if (response.data.code != 200) {
  //       return showMsg(400, response.data.message);
  //     }
  //     var token = response.data.data;
  //     //     console.log(token);
  //     // console.log(uploaderInfo.fileId);
  //     // console.log(appId);

  //     var videostr =
  //       '<video id="vreview"  width="400" height="250" preload="meta" playsinline webkit-playsinline></video>';
  //     $("#vodDiv").html(videostr);

  //     vodPlay("vreview", uploaderInfo.fileId, appId, token);

  //     $("#vreview").show();
  //     $("#vodDiv").show();
  //   });
}

/**
 * 校验视频格式和大小
 * @param event
 * @returns {boolean}
 */
function checkVideo(event, maxSize, videoFileId) {
  var flag = true;
  var file = event.target.files[0];
  // console.log(event.target.files);
  if (!file.type.includes("video")) {
    showMsg(400, "上传文件必须是视频！！！");
    return false;
  }

  if (maxSize != undefined) {
    if (file.size > 1024 * 1024 * maxSize) {
      showMsg(400, "文件不能大于" + maxSize + "GB");
      $("#" + videoFileId).val("");
      return false;
    }
  }

  return flag;
}
