/**
 * Created by huagelong on 2020/4/11.
 */
 (function() {
  'use strict';
  window.addEventListener('load', function() {
    // Fetch all the forms we want to apply custom Bootstrap validation styles to
    var forms = document.getElementsByClassName('needs-validation');
    // Loop over them and prevent submission
    var validation = Array.prototype.filter.call(forms, function(form) {
      form.addEventListener('submit', function(event) {
        if (form.checkValidity() === false) {
          event.preventDefault();
          event.stopPropagation();
        }
        form.classList.add('was-validated');
      }, false);
    });
  }, false);
})();

$(function () {
  
  $("button[type='submit']").click(function(){
    var l = $(this).lyearloading({
      opacity: 0.2,
      spinnerSize: 'nm'
    });
    setTimeout(function() {
      l.destroy(); // 可以使用hide，页面中如果有多个loading，最好用destroy，避免后面的loading设置不生效
    }, 1e3)
  });

  var clipboard = new Clipboard(".clipboard");
  clipboard.on("success", function (e) {
    layer.msg("复制成功", {
      time: 2000, //20s后自动关闭
      icon: 1,
      offset: "100px", //右下角弹出
    });
    e.clearSelection();
  });

  $(".search-btn-filter").click(function () {
    if ($(".grid-search").hasClass("display-hide")) {
      $(".grid-search").removeClass("display-hide");
    } else {
      $(".grid-search").addClass("display-hide");
    }
  });

  $(".reset").each(function () {
    var that = this;
    $(this).click(function () {
      var form = $(that).parents("form");
      $(form)[0].reset();
      $("input[data-type='hidden']", $(form)).val("");
    });
  });

  $(".daterange").each(function () {
    $(this).daterangepicker({
      autoUpdateInput: false,
      locale: {
        format: "YYYY-MM-DD",
      },
    });
  });

  $(".date").each(function () {
    $(this).daterangepicker({
      singleDatePicker: true,
      showDropdowns: true,
      autoUpdateInput: false,
      locale: {
        format: "YYYY-MM-DD",
      },
    });
  });

  $(".datetimerange").each(function () {
    $(this).daterangepicker({
      autoUpdateInput: false,
      timePicker: true,
      timePickerIncrement: 30,
      locale: {
        format: "YYYY-MM-DD HH:mm:ss",
      },
    });
  });

  $(".datetime").each(function () {
    $(this).daterangepicker({
      singleDatePicker: true,
      showDropdowns: true,
      autoUpdateInput: false,
      timePicker: true,
      locale: {
        format: "YYYY-MM-DD HH:mm:ss",
      },
    });
  });

  $("input[data-bootstrap-switch]").each(function () {
    $(this).bootstrapSwitch({
      size: "mini",
      onText: "开",
      offText: "关",
      onColor: "success",
      offColor: "danger",
    });
    $(this).bootstrapSwitch("state", $(this).prop("checked"));
  });

  $("input[data-bootstrap-switch-ajaxput]").each(function () {
    $(this).bootstrapSwitch({
      size: "mini",
      onText: "开",
      offText: "关",
      onColor: "success",
      offColor: "danger",
      onSwitchChange: function (event, state) {
        var chref = $(this).attr("href");
        state = state ? 1 : 0;
        requestPost(chref, { state: state });
        return true;
      },
    });
    $(this).bootstrapSwitch("state", $(this).prop("checked"));
  });

  $(".select").each(function(){
    var disabled =  $(this).attr("disabled");
    var allowClear = true;
    if(disabled == "disabled"){
      allowClear = false;
    }
      $(this).select2({
        language: "zh-CN",
        placeholder: "",
        allowClear: allowClear,
      });
  })

  $(".search_select").each(function () {
    var url = $(this).data("url");

    var disabled =  $(this).attr("disabled");
    var allowClear = true;
    if(disabled == "disabled"){
      allowClear = false;
    }

    $(this).select2({
      language: "zh-CN",
      allowClear: allowClear,
      placeholder: "",
      ajax: {
        url: url,
        dataType: "json",
        delay: 250,
        data: function (params) {
          return {
            kw: params.term,
          };
        },
        processResults: function (data) {
          return {
            results: data.data,
          };
        },
        cache: true,
      },
      minimumInputLength: 2,
    });
  });

  $("input[name^='hidden-']").each(function () {
    var name = $(this).attr("name");
    var vl = $(this).val();
    name = name.substr(name.lastIndexOf("-") + 1);
    $("select[name='values[" + name + "]']")
      .val(vl)
      .trigger("change");
  });

  function isJsonString(str) {
    try {
      if (typeof JSON.parse(str) == "object") {
        return true;
      }
    } catch(e) {

    }
    return false;
  }

  $("input[name^='formHidden-']").each(function () {
    var name = $(this).attr("name");
    var vl = $(this).val();
    name = name.substr(name.lastIndexOf("-") + 1);
    // console.log(vl);
    if(isJsonString(vl)) vl = JSON.parse(vl);
    if (vl){
      $("select[name='" + name + "']")
        .val(vl)
        .trigger("change");
    }

  });

  $("input.ufile[type=file]").each(function () {
    var field = $(this).data("field");

    var disabled =  $(this).data("disabled");
    var showRemove = true;
    if(disabled == 1){
      showRemove = false;
    }

    $(this).fileinput({
      theme: "fa",
      language: "zh",
      zoomModalHeight: 280,
      browseIcon: '<i class="mdi mdi-folder-open"></i>&nbsp;',
      browseClass: 'btn btn-sm btn-primary',
      removeIcon: '<i class="mdi mdi-trash-can"></i>',
      removeClass: 'btn btn-sm btn-default btn-secondary',
      cancelIcon: '<i class="mdi mdi-close-circle"></i>',
      cancelClass: 'btn btn-sm btn-default btn-secondary',
      pauseIcon: '<i class="mdi mdi-pause"></i>',
      pauseClass: 'btn btn-sm btn-default btn-secondary',
      uploadIcon: '<i class="mdi mdi-file-upload"></i>',
      uploadClass: 'btn btn-sm btn-default btn-secondary',
      previewZoomButtonIcons: {
        prev: '<i class="glyphicon glyphicon-triangle-left"></i>',
        next: '<i class="glyphicon glyphicon-triangle-right"></i>',
        toggleheader: '<i class="glyphicon glyphicon-resize-vertical"></i>',
        fullscreen: '<i class="glyphicon glyphicon-fullscreen"></i>',
        borderless: '<i class="glyphicon glyphicon-resize-full"></i>',
        close: '<i class="glyphicon glyphicon-remove"></i>'
      },
      msgValidationErrorIcon: '<i class="mdi mdi-exclamation"></i> ',
      allowedFileExtensions: [
        "jpg",
        "png",
        "gif",
        "jpeg",
        "zip",
        "rar",
        "txt",
        "doc",
        "ppt",
        "xls",
        "pdf",
        "docx",
        "pptx",
        "xlsx",
        "csv",
      ],
      overwriteInitial: true,
      initialPreviewAsData: true,
      // "uploadExtraData": function(previewId, index) {
      //     var realvl = $("input[name="+field+"]").val();
      //     return {exists: realvl};
      // },
      showCaption: false,
      layoutTemplates: {
        actionUpload: "",
        actionDelete: "",
      },
      showRemove: showRemove,
      showUpload: showRemove,
      showCancel: false,
      showClose: showRemove,
      // previewFileIcon: '<li class="mdi mdi-file"></li>',
      previewFileIconSettings: {
        docx: '<i class="mdi mdi-file-word text-primary"></i>',
        xlsx: '<i class="mdi mdi-file-excel text-success"></i>',
        csv: '<i class="mdi mdi-file-excel text-success"></i>',
        pptx: '<i class="mdi mdi-file-powerpoint text-danger"></i>',
        doc: '<i class="mdi mdi-file-word text-primary"></i>',
        xls: '<i class="mdi mdi-file-excel text-success"></i>',
        ppt: '<i class="mdi mdi-file-powerpoint text-danger"></i>',
        pdf: '<i class="mdi mdi-file-pdf text-danger"></i>',
        zip: '<i class="mdi mdi-folder-zip text-muted"></i>',
      },
      dropZoneEnabled: false,
      fileActionSettings: { showRemove: showRemove, showDrag: showRemove },
    });

    if(disabled == 1) {
      $(".btn-file").hide();
    }

    $(this).on("filebatchuploadcomplete", function (event, filePath) {
      var plugin = $(this).data("fileinput");
      var initialPreview = plugin.initialPreview;
      if (JSON.stringify(initialPreview) === "[]") {
        showMsg(400, "上传失败！");
        return true;
      }
      $("input[name=" + field + "]").val(JSON.stringify(initialPreview));
    });
  });

  $("form").each(function () {
    if (typeof tinymce == "undefined") return ;
    $(this).bind("form-pre-serialize", function (event, form, options, veto) {
      tinymce.triggerSave();
    });
  });

  //富文本
  $(".rich_text").each(function () {
    if (typeof tinymce == "undefined") return ;
    var id = $(this).attr("id");
    var width = $(this).data("width");
    var height = $(this).data("height");
    width = width ? width : "800";
    height = height ? height : "200";
    tinymce.init({
      selector: "#" + id,
      convert_urls: false,
      document_base_url: globOption.appDomain + "bundles/eduxpluscore/js/tinymce",
      width: width,
      min_height: height,
      plugins:
        "autosave print preview searchreplace autolink directionality visualblocks visualchars fullscreen image link media template code codesample table charmap hr pagebreak nonbreaking anchor insertdatetime advlist lists wordcount imagetools textpattern help emoticons autosave bdmap indent2em autoresize lineheight",
      language: "zh_CN",
      fontsize_formats: "12px 14px 16px 18px 24px 36px 48px 56px 72px",
      toolbar:
        "fontsizeselect forecolor link blockquote | alignleft aligncenter alignright | \
                     table image media emoticons bdmap|fullscreen preview",
      toolbar_mode: "wrap",
      images_upload_handler: function (blobInfo, succFun, failFun) {
        var xhr, formData;
        var file = blobInfo.blob();
        xhr = new XMLHttpRequest();
        xhr.withCredentials = false;
        xhr.open("POST", "/admin/glob/upload/rich_editor_img");
        xhr.onload = function () {
          var json;
          if (xhr.status != 200) {
            json = JSON.parse(xhr.responseText);
            failFun(json.error);
            return;
          }
          json = JSON.parse(xhr.responseText);
          if (!json || typeof json.initialPreview[0] != "string") {
            failFun(json.error);
            return;
          }
          succFun(json.initialPreview[0]);
        };
        formData = new FormData();
        formData.append("file", file, file.name); //此处与源文档不一样
        xhr.send(formData);
      },
    });
  });

  $(".imgpreview").click(function () {
    var imgSrc = $(this).attr("src"),
      width = $(this).width(),
      height = $(this).height(),
      scaleWH = width / height,
      bigH = $(window).height() - 60,
      bigW = scaleWH * bigH;
    if (bigW > 1000) {
      bigW = 1000;
      bigH = bigW / scaleWH;
    }
    layer.open({
      type: 1,
      title: false,
      closeBtn: 0,
      skin: "layui-layer-primary",
      shadeClose: true,
      area: [bigW + "px", bigH + "px"],
      content:
        '<img src="' +
        imgSrc +
        '" width="' +
        bigW +
        '" height="' +
        bigH +
        '"/>',
      scrollbar: true,
    });
  });

  $("#choose-all").click(function() {
    console.log(this.checked);
    if (this.checked) {
      $("input[name='ids[]']:checkbox").each(function() {
        $(this).prop("checked", true);
      })
    } else {   //反之 取消全选
      $("input[name='ids[]']:checkbox").each(function() {
        $(this).prop("checked", false);
      })
    }
  })


});
