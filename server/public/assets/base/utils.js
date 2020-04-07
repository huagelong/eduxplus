
    function show(code, msg){
        var icontype = 4;
        code = code+'';
        var msgType = code.substr(0,1);
        msgType = parseInt(msgType);
        switch(msgType){
            case 2:icontype=1;break;
            case 5:icontype=2;break;
            case 4:icontype=7;break;
            default :icontype = 4;
        }

        layer.msg(msg, {
            time: 2500, //20s后自动关闭
            icon: icontype
        });
    }

    /**
     * 获取数据ajax-get请求
     * @author laixm
     */
    $.getJSON = function (url,data,callback){
        $.ajax({
            url:url,
            type:"get",
            contentType:"application/json",
            dataType:"json",
            timeout:10000,
            data:data,
            success:function(data){
                callback(data);
            }
        });
    };

    /**
     * 提交json数据的post请求
     * @author laixm
     */
    $.postJSON = function(url,data,callback){
        $.ajax({
            url:url,
            type:"post",
            contentType:"application/json",
            dataType:"json",
            data:data,
            timeout:60000,
            success:function(msg){
                callback(msg);
            },
            error:function(xhr,textstatus,thrown){

            }
        });
    };

    /**
     * 修改数据的ajax-put请求
     * @author laixm
     */
    $.putJSON = function(url,data,callback){
        $.ajax({
            url:url,
            type:"put",
            contentType:"application/json",
            dataType:"json",
            data:data,
            timeout:20000,
            success:function(msg){
                callback(msg);
            },
            error:function(xhr,textstatus,thrown){

            }
        });
    };
    /**
     * 删除数据的ajax-delete请求
     * @author laixm
     */
    $.deleteJSON = function(url,data,callback){
        $.ajax({
            url:url,
            type:"delete",
            contentType:"application/json",
            dataType:"json",
            data:data,
            success:function(msg){
                callback(msg);
            },
            error:function(xhr,textstatus,thrown){

            }
        });
    };


    function requestPost(url, data){
        $.postJSON(url, data, function(responseText){
            if(typeof  responseText == 'string') var responseText = $.parseJSON(responseText);
            if(!$.isEmptyObject(responseText.data)){
                if(responseText.message){
                    show(responseText.code, responseText.message);
                }
                setTimeout(function(){
                    location.assign(responseText.data._url);
                }, 1000);
            }else{
                if(responseText.message){
                    show(responseText.code, responseText.message);
                }
            }
        },'json');
    }

    function requestGet(url, data){
        $.getJSON(url,data,function(responseText){
            if(typeof  responseText == 'string') var responseText = $.parseJSON(responseText);
            if(!$.isEmptyObject(responseText.data)){
                if(responseText.message){
                    show(responseText.code, responseText.message);
                }
                setTimeout(function(){
                    location.assign(responseText.data._url);
                }, 1000);
            }else{
                if(responseText.message){
                    show(responseText.code, responseText.message);
                }
            }
        },'json');
    }

