//防止高版本jquery废弃$.browser后报错：Cannot read property 'msie' of undefined
jQuery.browser={};(function(){jQuery.browser.msie=false; jQuery.browser.version=0;if(navigator.userAgent.match(/MSIE ([0-9]+)./)){ jQuery.browser.msie=true;jQuery.browser.version=RegExp.$1;}})();

// var qqFaceArr = ["hehe","haha","tushe","a","ku","lu","kaixin","han","lei","heixian","bishi","bugaoxing","zhenbang","qian","yiwen","yinxian","tu","yi","weiqu","huaxin","hu","xiaonian","neng","taikaixin","huaji","mianqiang","kuanghan","guai","shuijiao","jinku","shengqi","jinya","pen","aixin","xinsui","meigui","liwu","caihong","xxyl","taiyang","qianbi","dnegpao","chabei","dangao","yinyue","shenli","damuzhi","OK"];




// QQ表情插件
(function($){
    $.fn.qqFace = function(options){
        var defaults = {
            id : 'facebox',
            path : 'face/',
            assign : 'content',
            len: 20,
            box: '.faceDiv',
            tip : ''//默认 em_
        };
        var option = $.extend(defaults, options);
        var assign = $('#'+option.assign);
        var id = option.id;
        var path = option.path;
        // var tip = option.tip;
        var len = option.len;
        var box = option.box;

        if(assign.length<=0){
            alert('缺少表情赋值对象。');
            return false;
        }

        $(this).click(function(e){
            hasFace = true;
            var strFace, labFace;
            if($('#'+id).length<=0){
                strFace = '<div id="'+id+'" class="qqFace">' +
                            '<table border="0" cellspacing="0" cellpadding="0"><tr>';
                for(var i=1; i<=len; i++){
                    var _i = i;
                    if(_i<10){
                        _i = '0'+_i;
                    }else{
                        _i = _i+'';
                    }
                    //labFace = '['+tip+i+']';//默认
                    labFace = '[em2_'+_i+']';//获取自定义名称
                    // labFace = '[em_'+i+']';
                    strFace += '<td><img src="'+path+_i+'.png" style="width:24px;margin: 2px;" onclick="$(\'#'+option.assign+'\').setCaret();$(\'#'+option.assign+'\').insertAtCaret(\'' + labFace + '\');" /></td>';
                    if( i % 7 == 0 ) strFace += '</tr><tr>';
                }
                strFace += '</tr></table></div>';
                $(box).html(strFace);
            }

            // var offset = $(this).position();
            // var top = offset.top + $(this).outerHeight();
// 			$('#'+id).css('top',top);
// 	    	$('#'+id).css('left',offset.left);
            $('#'+id).show();
            e.stopPropagation();
        });

        $(document).click(function(){
            $('#'+id).hide();
            // $('#'+id).remove();
        });
    };

})(jQuery);

jQuery.extend({
unselectContents: function(){
    if(window.getSelection)
        window.getSelection().removeAllRanges();
    else if( document.selection )
        document.selection.empty();
    }
});
jQuery.fn.extend({
    selectContents: function(){
        $(this).each(function(i){
            var node = this;
            var selection, range, doc, win;
            if ((doc = node.ownerDocument) && (win = doc.defaultView) && typeof win.getSelection != 'undefined' && typeof doc.createRange != 'undefined' && (selection = window.getSelection()) && typeof selection.removeAllRanges != 'undefined'){
                range = doc.createRange();
                range.selectNode(node);
                if(i == 0){
                    selection.removeAllRanges();
                }
                selection.addRange(range);
            } else if (document.body && typeof document.body.createTextRange != 'undefined' && (range = document.body.createTextRange())){
                range.moveToElementText(node);
                range.select();
            }
        });
    },

    setCaret: function(){
        if(!$.browser.msie) return;
        var initSetCaret = function(){
            var textObj = $(this).get(0);
            textObj.caretPos = document.selection.createRange().duplicate();
        };
        $(this).click(initSetCaret).select(initSetCaret).keyup(initSetCaret);
    },

    insertAtCaret: function(textFeildValue){
        var textObj = $(this).get(0);
        if(document.all && textObj.createTextRange && textObj.caretPos){
            var caretPos=textObj.caretPos;
            caretPos.text = caretPos.text.charAt(caretPos.text.length-1) == '' ?
            textFeildValue+'' : textFeildValue;
        } else if(textObj.setSelectionRange){
            var rangeStart=textObj.selectionStart;
            var rangeEnd=textObj.selectionEnd;
            var tempStr1=textObj.value.substring(0,rangeStart);
            var tempStr2=textObj.value.substring(rangeEnd);
            textObj.value=tempStr1+textFeildValue+tempStr2;
//          textObj.focus();
            var len=textFeildValue.length;
            textObj.setSelectionRange(rangeStart+len,rangeStart+len);
            textObj.blur();
        }else{
            textObj.value+=textFeildValue;
        }
    }
});

