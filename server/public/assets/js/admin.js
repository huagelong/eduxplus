/**
 * Created by wangkaihui on 2020/4/11.
 */
$(function(){

    var activeName = "";
    $(".nav-link").each(function(){
        var href = $(this).attr("href");
        if($(this).hasClass("active")){
            activeName = href
        }
        $(this).click(function(){
            $.Cookie("menu_current", href);
            return true;
        });
    })

    if(!activeName){
        activeName = $.Cookie("menu_current");
    }

    //导航选中处理
    if(activeName){
        $(".nav-link[href='"+activeName+"']").parents(".nav-item").find(".nav-link:first").addClass("active");
        $(".nav-link[href='"+activeName+"']").parents(".nav-item").addClass("menu-open");
    }


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

    $('.select').select2({ language:'zh-CN', theme: 'bootstrap4', "allowClear":true});

    $('.search_select').each(function(){
        var url = $(this).data("url")
        $(this).select2({
            language:'zh-CN',
            theme: 'bootstrap4',
            "allowClear":true,
            placeholder: '',
            ajax: {
                url: url,
                dataType: 'json',
                delay: 250,
                data: function (params) {
                    return {
                        kw: params.term,
                    };
                },
                processResults: function (data) {
                    return {
                        results: data.data
                    };
                },
                cache: true
            },
            minimumInputLength: 2
        });
    })

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
                'doc': '<i class="fa fa-file-word-o text-primary"></i>',
                'xls': '<i class="fa fa-file-excel-o text-success"></i>',
                'ppt': '<i class="fa fa-file-powerpoint-o text-danger"></i>',
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

    //富文本
    $(".rich_text").each(function(){
        var id = $(this).attr("id");
        var width = $(this).data('width');
        var height = $(this).data('height');
        width = width?width:"800";
        height = height?height:"200";
        tinymce.init({
            selector: "#"+id,
            convert_urls : false,
            document_base_url:globOption.appDomain+"assets/plugins/tinymce",
            width:width,
            min_height: height,
            plugins :  'print preview searchreplace autolink directionality visualblocks visualchars fullscreen image link media template code codesample table charmap hr pagebreak nonbreaking anchor insertdatetime advlist lists wordcount imagetools textpattern help emoticons autosave bdmap indent2em autoresize lineheight',
            language:'zh_CN',
            fontsize_formats: '12px 14px 16px 18px 24px 36px 48px 56px 72px',
            toolbar: 'fontsizeselect forecolor link blockquote | alignleft aligncenter alignright | \
                     table image media emoticons bdmap|fullscreen preview',
            toolbar_mode : 'wrap',
            images_upload_handler: function (blobInfo, succFun, failFun) {
                var xhr, formData;
                var file = blobInfo.blob();
                xhr = new XMLHttpRequest();
                xhr.withCredentials = false;
                xhr.open('POST', '/admin/glob/upload/rich_editor_img');
                xhr.onload = function() {
                    var json;
                    if (xhr.status != 200) {
                        json = JSON.parse(xhr.responseText);
                        failFun(json.error);
                        return;
                    }
                    json = JSON.parse(xhr.responseText);
                    if (!json || typeof json.initialPreview[0] != 'string') {
                        failFun(json.error);
                        return;
                    }
                    succFun(json.initialPreview[0]);
                };
                formData = new FormData();
                formData.append('file', file, file.name );//此处与源文档不一样
                xhr.send(formData);
            }

        });
    })

})
