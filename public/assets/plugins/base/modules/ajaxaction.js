(function ($) {
    $.fn.ajaxDelete = function () {
        $(this).each(function () {
            var chref = $(this).attr("href");
            $(this).click(function () {
                var isconfirm = $(this).data("confirm");
                layer.msg(isconfirm, {
                    time: 0 //不自动关闭
                    , btn: ['是', '否']
                    , yes: function (index) {
                        layer.close(index);
                        todoDelete(chref);
                    }
                });
                return false;
            });
        });

        function todoDelete(chref) {
            $.postJSON(chref, {}, function (responseText) {
                if (typeof responseText == 'string') var responseText = $.parseJSON(responseText);

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
                        //     parent.location.assign(responseText._url);
                        // } else {
                            location.assign(responseText._url);
                        // }
                    }, 1000);
                } else {
                    if (responseText.message) {
                        showMsg(responseText.code, responseText.message);
                    }
                }


            }, 'json');
        }

    };

    $.fn.ajaxPut = function () {
        $(this).each(function () {
            var chref = $(this).attr("href");
            $(this).click(function () {
                var isconfirm = $(this).data("confirm");
                if (isconfirm) {
                    layer.msg(isconfirm, {
                        time: 0 //不自动关闭
                        , btn: ['是', '否']
                        , yes: function (index) {
                            layer.close(index);
                            todoPut(chref);
                        }
                    });

                } else {
                    todoPut(chref);
                }
                return false;
            });
        });

        function todoPut(chref) {
            $.postJSON(chref, {}, function (responseText) {
                if (typeof responseText == 'string') var responseText = $.parseJSON(responseText);

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
                        //     parent.location.assign(responseText._url);
                        // } else {
                            location.assign(responseText._url);
                        // }
                    }, 1000);
                } else {
                    if (responseText.message) {
                        showMsg(responseText.code, responseText.message);
                    }
                }

            }, 'json');
        }

    };


  $.fn.ajaxGet = function () {
    $(this).each(function () {
      var chref = $(this).attr("href");
      $(this).click(function () {
        var isconfirm = $(this).data("confirm");
        if (isconfirm) {
          layer.msg(isconfirm, {
            time: 0 //不自动关闭
            , btn: ['是', '否']
            , yes: function (index) {
              layer.close(index);
              todoGet(chref);
            }
          });

        } else {
          todoGet(chref);
        }
        return false;
      });
    });

    function todoGet(chref) {
      $.getJSON(chref, {}, function (responseText) {
        if (typeof responseText == 'string') var responseText = $.parseJSON(responseText);

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

      }, 'json');
    }

  };


}(jQuery));
