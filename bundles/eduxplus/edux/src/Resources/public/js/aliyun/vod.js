/**
 * @Author: kaihui.wang
 * @Contact  hpuwang@gmail.com
 * @Version: 1.0.0
 * @Date: 2020/6/22 11:13
 */

 //保存记忆播放时间  TODO
 function saveTime(memoryVideo,currentTime) {
    console.log(memoryVideo)
  }

  //获取记忆时间, TODO
  function getTime(memoryVideo){
      return 10;
  }

function vodPlay(playerId, vid, playauth,format, width, height){
    var player = new Aliplayer({
            "id": playerId,
            "vid": vid,
            "playauth": playauth,
            "format": format||"mp4",
            "mediaType": "video",
            "width": width||"100%",
            "height": height||"500px",
            "autoplay": false,
            "isLive": false,
            "rePlay": false,
            "playsinline": true,
            "preload": false,
            "useH5Prism": true,
            "encryptType":'1',
            components: [
                {//清晰度切换
                name: 'QualityComponent',
                type: AliPlayerComponent.QualityComponent
               },
               {//记忆播放
                name: 'MemoryPlayComponent',
                type: AliPlayerComponent.MemoryPlayComponent,
                args:[true,getTime,saveTime]
              },
              {//倍速播放
                name: 'RateComponent',
                type: AliPlayerComponent.RateComponent
              }
            ]
        }, function (player) {
            player.on('sourceloaded', function(params) {
                // console.log(params);
                var paramData = params.paramData
                var desc = paramData.desc
                var definition = paramData.definition
                player.getComponent('QualityComponent').setCurrentQuality(desc, definition)
              })
        }
    );
}

function uploadVideo(region, uploadVideoDivId, userId, fileName,cateId,createUrl,refreshUrl){
    var app = new Vue({
        el: '#' + uploadVideoDivId,
        delimiters:['${','}'],
        data:{
            timeout: '',
            partSize: '',
            parallel: '',
            retryCount: '',
            retryDuration: '',
            region: region,
            userId: userId,
            file: null,
            authProgress: 0,
            uploadDisabled: true,
            resumeDisabled: true,
            pauseDisabled: true,
            uploader: null,
            statusText: ''
        },
        methods: {
            fileChange: function (e) {
                this.file = e.target.files[0]
                if (!this.file) {
                    alert("请先选择需要上传的文件!")
                    return
                }
                var Title = this.file.name
                var userData = '{"Vod":{}}'
                if (this.uploader) {
                    this.uploader.stopUpload()
                    this.authProgress = 0
                    this.statusText = ""
                }
                this.uploader = this.createUploader()
                this.uploader.addFile(this.file, null, null, null, userData)
                this.uploadDisabled = false
                this.pauseDisabled = true
                this.resumeDisabled = true
            },
            authUpload: function() {
                // 然后调用 startUpload 方法, 开始上传
                if (this.uploader !== null) {
                    this.uploader.startUpload()
                    this.uploadDisabled = true
                    this.pauseDisabled = false
                }
            },
            // 暂停上传
            pauseUpload: function () {
                if (this.uploader !== null) {
                    this.uploader.stopUpload()
                    this.resumeDisabled = false
                    this.pauseDisabled = true
                }
            },
            // 恢复上传
            resumeUpload: function () {
                if (this.uploader !== null) {
                    this.uploader.startUpload()
                    this.resumeDisabled = true
                    this.pauseDisabled = false
                }
            },
            createUploader: function (type) {
                var self = this
                var uploader = new AliyunUpload.Vod({
                    timeout: self.timeout || 60000,
                    partSize: self.partSize || 1048576,
                    parallel: self.parallel || 5,
                    retryCount: self.retryCount || 3,
                    retryDuration: self.retryDuration || 2,
                    region: self.region,
                    userId: self.userId,
                    // 添加文件成功
                    addFileSuccess: function (uploadInfo) {
                        self.uploadDisabled = false
                        self.resumeDisabled = false
                        self.statusText = '添加文件成功, 等待上传...'
                        console.log("addFileSuccess: " + uploadInfo.file.name)
                    },
                    // 开始上传
                    onUploadstarted: function (uploadInfo) {
                        var uploadFileName = uploadInfo.file.name;
                        var index = uploadFileName.lastIndexOf(".");
                        if(index>0){
                            var suffix = uploadFileName.substr(index+1);
                        }else{
                            var suffix = "mp4";
                        }
                        var realFilename = fileName+"."+suffix;
                        // 如果是 UploadAuth 上传方式, 需要调用 uploader.setUploadAuthAndAddress 方法
                        // 如果是 UploadAuth 上传方式, 需要根据 uploadInfo.videoId是否有值，调用点播的不同接口获取uploadauth和uploadAddress
                        // 如果 uploadInfo.videoId 有值，调用刷新视频上传凭证接口，否则调用创建视频上传凭证接口
                        // 注意: 这里是测试 demo 所以直接调用了获取 UploadAuth 的测试接口, 用户在使用时需要判断 uploadInfo.videoId 存在与否从而调用 openApi
                        // 如果 uploadInfo.videoId 存在, 调用 刷新视频上传凭证接口(https://help.aliyun.com/document_detail/55408.html)
                        // 如果 uploadInfo.videoId 不存在,调用 获取视频上传地址和凭证接口(https://help.aliyun.com/document_detail/55407.html)
                        if (!uploadInfo.videoId) {
                            var realcreateUrl = createUrl+"?title="+fileName+"&fileName="+realFilename+"&cateId="+cateId
                            axios.get(realcreateUrl).then(function (rdata){
                                var data = rdata.data;
                                if(data.code != 200){
                                    return showMsg(400, data.message);
                                }
                                var uploadAuth = data.data.UploadAuth
                                var uploadAddress = data.data.UploadAddress
                                var videoId = data.data.VideoId
                                uploader.setUploadAuthAndAddress(uploadInfo, uploadAuth, uploadAddress,videoId)
                            })
                            self.statusText = '文件开始上传...'
                            console.log("onUploadStarted:" + uploadInfo.file.name + ", endpoint:" + uploadInfo.endpoint + ", bucket:" + uploadInfo.bucket + ", object:" + uploadInfo.object)
                        } else {
                            // 如果videoId有值，根据videoId刷新上传凭证
                            // https://help.aliyun.com/document_detail/55408.html?spm=a2c4g.11186623.6.630.BoYYcY
                            var realRefreshUrl = refreshUrl+'?videoId=' + uploadInfo.videoId
                            axios.get(realRefreshUrl).then(function (rdata){
                                var data = rdata.data;
                                if(data.code != 200){
                                    return showMsg(400, data.message);
                                }
                                var uploadAuth = data.data.UploadAuth
                                var uploadAddress = data.data.UploadAddress
                                var videoId = data.data.VideoId
                                uploader.setUploadAuthAndAddress(uploadInfo, uploadAuth, uploadAddress,videoId)
                            })
                        }
                    },
                    // 文件上传成功
                    onUploadSucceed: function (uploadInfo) {
                        $("#videoId").val(uploadInfo.videoId);
                        console.log("onUploadSucceed: " + uploadInfo.file.name + ", endpoint:" + uploadInfo.endpoint + ", bucket:" + uploadInfo.bucket + ", object:" + uploadInfo.object)
                        self.statusText = '文件上传成功!'
                        $("form:first").submit();
                    },
                    // 文件上传失败
                    onUploadFailed: function (uploadInfo, code, message) {
                        console.log("onUploadFailed: file:" + uploadInfo.file.name + ",code:" + code + ", message:" + message)
                        self.statusText = '文件上传失败!'
                    },
                    // 取消文件上传
                    onUploadCanceled: function (uploadInfo, code, message) {
                        console.log("Canceled file: " + uploadInfo.file.name + ", code: " + code + ", message:" + message)
                        self.statusText = '文件已暂停上传'
                    },
                    // 文件上传进度，单位：字节, 可以在这个函数中拿到上传进度并显示在页面上
                    onUploadProgress: function (uploadInfo, totalSize, progress) {
                        console.log("onUploadProgress:file:" + uploadInfo.file.name + ", fileSize:" + totalSize + ", percent:" + Math.ceil(progress * 100) + "%")
                        var progressPercent = Math.ceil(progress * 100)
                        self.authProgress = progressPercent
                        self.statusText = '文件上传中...'
                    },
                    // 上传凭证超时
                    onUploadTokenExpired: function (uploadInfo) {
                        // 上传大文件超时, 如果是上传方式一即根据 UploadAuth 上传时
                        // 需要根据 uploadInfo.videoId 调用刷新视频上传凭证接口(https://help.aliyun.com/document_detail/55408.html)重新获取 UploadAuth
                        // 然后调用 resumeUploadWithAuth 方法, 这里是测试接口, 所以我直接获取了 UploadAuth
                        var realRefreshUrl = refreshUrl+'?videoId=' + uploadInfo.videoId
                        axios.get(realRefreshUrl).then(function (rdata){
                            var data = rdata.data;
                            if(data.code != 200){
                                return showMsg(400, data.message);
                            }
                            var uploadAuth = data.data.UploadAuth
                            uploader.resumeUploadWithAuth(uploadAuth)
                            console.log('upload expired and resume upload with uploadauth ' + uploadAuth)
                        })
                        self.statusText = '文件超时...'
                    },
                    // 全部文件上传结束
                    onUploadEnd: function (uploadInfo) {
                        console.log("onUploadEnd: uploaded all the files")
                        self.statusText = '文件上传完毕'
                    }
                })
                return uploader
            }
        }
    });
}
