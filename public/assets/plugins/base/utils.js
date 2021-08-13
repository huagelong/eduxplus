function isMobile() {
  var userAgentInfo = navigator.userAgent;
  var Agents = [
    "Android",
    "iPhone",
    "SymbianOS",
    "Windows Phone",
    "iPad",
    "iPod",
  ];
  var flag = false;
  for (var v = 0; v < Agents.length; v++) {
    if (userAgentInfo.indexOf(Agents[v]) > 0) {
      flag = true;
      break;
    }
  }
  return flag;
}

function timeago(dateTimeStamp){   //dateTimeStamp是一个时间毫秒。
  dateTimeStamp = dateTimeStamp*1000;
  var minute = 1000 * 60;      //把分，时，天，周，半个月，一个月用毫秒表示
  var hour = minute * 60;
  var day = hour * 24;
  var week = day * 7;
  var halfamonth = day * 15;
  var month = day * 30;
  var now = new Date().getTime();   //获取当前时间毫秒
  var diffValue = now - dateTimeStamp;//时间差

  if(diffValue < 0){
    return;
  }
  var minC = diffValue/minute;  //计算时间差的分，时，天，周，月
  var hourC = diffValue/hour;
  var dayC = diffValue/day;
  var weekC = diffValue/week;
  var monthC = diffValue/month;
  if(monthC >= 1 && monthC <= 3){
    result = " " + parseInt(monthC) + "月前"
  }else if(weekC >= 1 && weekC <= 3){
    result = " " + parseInt(weekC) + "周前"
  }else if(dayC >= 1 && dayC <= 6){
    result = " " + parseInt(dayC) + "天前"
  }else if(hourC >= 1 && hourC <= 23){
    result = " " + parseInt(hourC) + "小时前"
  }else if(minC >= 1 && minC <= 59){
    result =" " + parseInt(minC) + "分钟前"
  }else if(diffValue >= 0 && diffValue <= minute){
    result = "刚刚"
  }else {
    var datetime = new Date();
    datetime.setTime(dateTimeStamp);
    var Nyear = datetime.getFullYear();
    var Nmonth = datetime.getMonth() + 1 < 10 ? "0" + (datetime.getMonth() + 1) : datetime.getMonth() + 1;
    var Ndate = datetime.getDate() < 10 ? "0" + datetime.getDate() : datetime.getDate();
    var Nhour = datetime.getHours() < 10 ? "0" + datetime.getHours() : datetime.getHours();
    var Nminute = datetime.getMinutes() < 10 ? "0" + datetime.getMinutes() : datetime.getMinutes();
    var Nsecond = datetime.getSeconds() < 10 ? "0" + datetime.getSeconds() : datetime.getSeconds();
    result = Nyear + "-" + Nmonth + "-" + Ndate
  }
  return result;
}

function showMsg(code, msg) {
  var icontype = 4;
  code = code + "";
  var msgType = code.substr(0, 1);
  msgType = parseInt(msgType);
  switch (msgType) {
    case 2:
      icontype = 1;
      break;
    case 5:
      icontype = 2;
      break;
    case 4:
      icontype = 7;
      break;
    default:
      icontype = 7;
  }
  layer.msg(msg, {
    time: 2500, //20s后自动关闭
    icon: icontype,
  });
}

/**
 * 获取数据ajax-get请求
 * @author laixm
 */
$.getJSON = function (url, data, callback) {
  $.ajax({
    url: url,
    type: "get",
    contentType: "application/json",
    dataType: "json",
    timeout: 10000,
    data: data,
    success: function (data) {
      callback(data);
    },
  });
};

/**
 * 提交json数据的post请求
 * @author laixm
 */
$.postJSON = function (url, data, callback) {
  $.ajax({
    url: url,
    type: "post",
    contentType: "application/json",
    dataType: "json",
    data: data,
    timeout: 60000,
    success: function (msg) {
      callback(msg);
    },
    error: function (xhr, textstatus, thrown) {},
  });
};

/**
 * 修改数据的ajax-put请求
 * @author laixm
 */
$.putJSON = function (url, data, callback) {
  $.ajax({
    url: url,
    type: "put",
    contentType: "application/json",
    dataType: "json",
    data: data,
    timeout: 20000,
    success: function (msg) {
      callback(msg);
    },
    error: function (xhr, textstatus, thrown) {},
  });
};
/**
 * 删除数据的ajax-delete请求
 * @author laixm
 */
$.deleteJSON = function (url, data, callback) {
  $.ajax({
    url: url,
    type: "delete",
    contentType: "application/json",
    dataType: "json",
    data: data,
    success: function (msg) {
      callback(msg);
    },
    error: function (xhr, textstatus, thrown) {},
  });
};

function requestPost(url, data) {
  dataStr = JSON.stringify(data);
  $.postJSON(
    url,
    dataStr,
    function (responseText) {
      if (typeof responseText == "string")
        var responseText = $.parseJSON(responseText);

      if (
        responseText._url != "undefined" &&
        !$.isEmptyObject(responseText._url) &&
        !$.isPlainObject(responseText._url)
      ) {
        if (responseText.message) {
          showMsg(responseText.code, responseText.message);
        }
        setTimeout(function () {
          // if (window.frames.length != parent.frames.length){
          // if (self.frameElement && self.frameElement.tagName == "IFRAME") {
          //   parent.location.assign(responseText._url);
          // } else {
            location.assign(responseText._url);
          // }
        }, 1000);
      } else {
        if (responseText.message) {
          showMsg(responseText.code, responseText.message);
        }
      }
    },
    "json"
  );
}

function requestGet(url, data) {
  $.getJSON(
    url,
    data,
    function (responseText) {
      if (typeof responseText == "string")
        var responseText = $.parseJSON(responseText);
      if (
        responseText._url != "undefined" &&
        !$.isEmptyObject(responseText._url) &&
        !$.isPlainObject(responseText._url)
      ) {
        if (responseText.message) {
          showMsg(responseText.code, responseText.message);
        }
        setTimeout(function () {
          // if (window.frames.length != parent.frames.length){
          // if (self.frameElement && self.frameElement.tagName == "IFRAME") {
          //   parent.location.assign(responseText._url);
          // } else {
            location.assign(responseText._url);
          // }
        }, 1000);
      } else {
        if (responseText.message) {
          showMsg(responseText.code, responseText.message);
        }
      }
    },
    "json"
  );
}
