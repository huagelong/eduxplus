/**
 * Created by wangkaihui on 2020/4/11.
 */
$(function(){
    var clipboard = new Clipboard('.clipboard');
    clipboard.on('success', function(e) {
        layer.msg("复制成功", {
            time: 2000, //20s后自动关闭
            icon: 1,
            offset: '100px', //右下角弹出
        });
        e.clearSelection();
    });

    $(".search-btn-filter").click(function(){
        if($(".grid-search").hasClass('display-hide')){
            $(".grid-search").removeClass('display-hide')
        }else{
            $(".grid-search").addClass('display-hide')
        }
    });

    $(".reset").each(function(){
        var that = this;
        $(this).click(function(){
            var form = $(that).parents("form");
            $(form)[0].reset();
            $("input[data-type='hidden']", $(form)).val("");
        });
    });

    $(".daterange").each(function(){
        $(this).daterangepicker({
            autoUpdateInput:false,
            locale: {
                format: 'YYYY-MM-DD'
            }
        });
    })

    $(".datetimerange").each(function(){
        $(this).daterangepicker({
            autoUpdateInput:false,
            timePicker: true,
            timePickerIncrement: 30,
            locale: {
                format: 'YYYY-MM-DD hh:mm'
            }
        });
    })

    $("input[data-bootstrap-switch]").each(function(){
        $(this).bootstrapSwitch({
            size:'mini',
            onText: '开',
            offText: '关',
            onColor: 'success',
            offColor: 'danger'
        });
        $(this).bootstrapSwitch('state', $(this).prop('checked'));
    });

    $('input[data-bootstrap-switch-ajaxput]').each(function(){
        $(this).bootstrapSwitch({
            size:'mini',
            onText: '开',
            offText: '关',
            onColor: 'success',
            offColor: 'danger',
            onSwitchChange: function(event, state){
                var chref = $(this).attr("href");
                state = state?1:0;
                var isconfirm = $(this).data("confirm");
                if(isconfirm){
                    layer.msg(isconfirm, {
                        time: 0 //不自动关闭
                        ,btn: ['是', '否']
                        ,yes: function(index){
                            layer.close(index);
                            requestPost(chref,JSON.stringify({'state':state}));
                        }
                    });

                }else{
                    requestPost(chref,JSON.stringify({'state':state}));
                }
                return true;
            }
        });
        $(this).bootstrapSwitch('state', $(this).prop('checked'));
    });

    $('.select').select2({ theme: 'bootstrap4', "allowClear":true});

    $("input[name^='hidden-']").each(function(){
        var name = $(this).attr('name');
        var vl =  $(this).val();
        name = name.substr(name.lastIndexOf("-")+1);
        $("select[name='values["+name+"]']").val(vl).trigger("change");
    });

    $("input[name^='formHidden-']").each(function(){
        var name = $(this).attr('name');
        var vl =  $(this).val();
        name = name.substr(name.lastIndexOf("-")+1);
        if(vl) $("select[name='"+name+"']").val(JSON.parse(vl)).trigger("change");
    });

    $("input.ufile[type=file]").each(function(){
        var field = $(this).data("field");
        $(this).fileinput({
            "theme": "fa",
            "language":"zh",
            "browseClass": "btn btn-primary",
            "allowedFileExtensions": ['jpg', 'png', 'gif', 'jpeg', 'zip', 'rar', 'txt', 'doc', 'ppt', 'xls', 'pdf', 'docx', 'pptx', 'xlsx'],
            "overwriteInitial":true,
            "initialPreviewAsData":true,
            // "uploadExtraData": function(previewId, index) {
            //     var realvl = $("input[name="+field+"]").val();
            //     return {exists: realvl};
            // },
            "showCaption":false,
            "layoutTemplates": {
                actionUpload: "",
                actionDelete:""
            },
            "showRemove":true,
            "showUpload":true,
            "showCancel":false,
            "previewFileIconSettings": {
                'docx': '<i class="fa fa-file-word-o text-primary"></i>',
                'xlsx': '<i class="fa fa-file-excel-o text-success"></i>',
                'pptx': '<i class="fa fa-file-powerpoint-o text-danger"></i>',
                'pdf': '<i class="fa fa-file-pdf-o text-danger"></i>',
                'zip': '<i class="fa fa-file-archive-o text-muted"></i>',
            },
            "dropZoneEnabled":false,
            "fileActionSettings":{"showRemove":true,"showDrag":true}
        });

        $(this).on('filebatchuploadcomplete',function (event,files,extra) {
            var plugin = $(this).data('fileinput');
            var initialPreview = plugin.initialPreview;
            $("input[name="+field+"]").val(JSON.stringify(initialPreview));
        });
    })

})
