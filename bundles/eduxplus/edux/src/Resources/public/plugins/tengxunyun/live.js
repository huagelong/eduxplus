function livePlay(id, urls, width, height, poster) {
  var tcplayer = new TcPlayer(id,{
    // id 为播放器容器 ID，必须与 html 中一致
    m3u8: urls.default, // 请传入需要播放的视频 filID（必须）
    m3u8_sd:urls.sd,
    m3u8_sd:urls.hd,
    x5_player:false,
    live: true,
    poster:poster,
    autoplay: true, // 请传入点播账号的 appID（必须）
    width :  width,//视频的显示宽度，请尽量使用视频分辨率宽度
    height : height,//视频的显示高度，请尽量使用视频分辨率高度
    wording: {
      1:"网络错误，请刷新页面重试",
      2:"	网络错误，请刷新页面重试",
      3:"	网络错误，请刷新页面重试",
      4:"	网络错误，请刷新页面重试",
      5:"当前浏览器不支持 MSE 或者 Flash 插件未启用。",
      10:"网络错误，请刷新页面重试",
      11:"网络错误，请刷新页面重试",
      12:"网络错误，请刷新页面重试",
      13:"直播已结束，请稍后再来。",
      1001:"网络错误，请刷新页面重试",
      1002:"网络错误，请刷新页面重试",
      2032: "网络错误，请刷新页面重试",
      2048: "网络错误，请刷新页面重试"
    },
    clarity:"sd",
    clarityLabel:{od:'流畅',hd:'高清',sd:'标清'}
  });
  return tcplayer;
}
