
function livePlay(playerId, vid, playauth,source, width, height){
    var player = new Aliplayer({
            "id": playerId,
            "vid": vid,
            "source":source,
            "playauth": playauth,
            "qualitySort": "asc",
            "format": "mp4",
            "mediaType": "video",
            "width": width||"100%",
            "height": height||"500px",
            "autoplay": true,
            "isLive": true,
            "rePlay": false,
            "playsinline": true,
            "preload": true,
            "controlBarVisibility": "hover",
            "useH5Prism": true,
            "encryptType":'1'
        }, function (player) {
            console.log("The player is created");
        }
    );
}
