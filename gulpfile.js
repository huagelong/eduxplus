var gulp = require('gulp');
var uglify = require('gulp-uglify'); // js压缩
var minifyCss = require('gulp-minify-css');
var concat = require('gulp-concat'); // 代码合并
let gulpCssAssets = require("edux-gulp-css-assets-ref");
var gulpif = require('gulp-if');

function isCssFile(file) {
  return file.path.indexOf(".css") !== -1;
}

// base 压缩
function baseJs(cb){
  gulp.src([
    'public/assets/plugins/base/jquery.js',
    'public/assets/plugins/base/utils.js',
    'public/assets/plugins/base/plugins/jquery-form/jquery.form.js',
    'public/assets/plugins/base/plugins/jquery-cookie/jquerycookie.js',
    'public/assets/plugins/base/plugins/layer/layer.js',
    'public/assets/plugins/base/plugins/underscore/underscore-min.js',
    'public/assets/plugins/base/modules/include.js',
    'public/assets/plugins/base/modules/ajaxform.js',
    'public/assets/plugins/base/modules/ajaxaction.js',
    'public/assets/plugins/base/modules/captcha.js',
    'public/assets/plugins/base/modules/widgets.js',
    'public/assets/plugins/base/base.js',
  ]) // 获取js文件
    .pipe(concat('base.min.js')) //合并
    .pipe(uglify()) // 压缩js代码
    .pipe(gulp.dest('public/assets/dist/base'));
  cb();
}

function baseCss(cb){
  //css
  let cssSrc = gulp.src(['public/assets/plugins/base/plugins/layer/layer.css']);
    cssSrc.pipe(gulpCssAssets({
      processAllFile:true
    }))
    .pipe(gulpif(isCssFile, concat("base.min.css")))
    .pipe(gulpif(isCssFile, minifyCss()))
    .pipe(gulp.dest('public/assets/dist/base'));

  cb();
}

// 后台base
function baseAdminJs(cb){
  gulp.src([
    'public/assets/plugins/bootstrap/js/bootstrap.bundle.js',
    'public/assets/plugins/overlayScrollbars/js/jquery.overlayScrollbars.js',
    'public/assets/plugins/jquery-ui/jquery-ui.js',
    'public/assets/plugins/admlte/js/adminlte.js',
    'public/assets/plugins/moment/moment.min.js',
    'public/assets/plugins/daterangepicker/daterangepicker.js',
    'public/assets/plugins/select2/js/select2.full.js',
    'public/assets/plugins/select2/js/i18n/zh-CN.js',
    'public/assets/plugins/bootstrap-switch/js/bootstrap-switch.js',
    'public/assets/plugins/bootstrap-fileinput/js/fileinput.js',
    'public/assets/plugins/bootstrap-fileinput/js/locales/zh.js',
    'public/assets/plugins/bootstrap-fileinput/js/plugins/piexif.js',
    'public/assets/plugins/clipboardjs/clipboard.js',
  ]) // 获取js文件
    .pipe(concat('base.min.js')) //合并
    .pipe(uglify()) // 压缩js代码
    .pipe(gulp.dest('public/assets/dist/baseAdmin'));
  cb();
}

//后台base
function baseAdminCss(cb){
  //css
  let cssSrc = gulp.src([
    'public/assets/plugins/fontawesome-free/css/all.css',
    'public/assets/plugins/fontawesome-free/css/v4-shims.css',
    'public/assets/admin/iconfont/iconfont.css',
    'public/assets/plugins/overlayScrollbars/css/OverlayScrollbars.css',
    'public/assets/plugins/admlte/css/adminlte.css',
    'public/assets/plugins/admlte/googlefonts/fonts.css',
    'public/assets/plugins/glyphicons/css/glyphicons.css',
    'public/assets/plugins/daterangepicker/daterangepicker.css',
    'public/assets/plugins/select2/css/select2.css',
    'public/assets/plugins/select2-bootstrap4-theme/select2-bootstrap4.css',
    'public/assets/plugins/icheck-bootstrap/icheck-bootstrap.css',
    'public/assets/plugins/bootstrap-fileinput/css/fileinput.css',
  ]);
  cssSrc.pipe(gulpCssAssets({
    processAllFile:true
  }))
    .pipe(gulpif(isCssFile, concat("base.min.css")))
    .pipe(gulpif(isCssFile, minifyCss()))
    .pipe(gulp.dest('public/assets/dist/baseAdmin'));

  cb();
}

function basePcCss(cb){
  //css
  let cssSrc = gulp.src([
    'public/assets/plugins/fontawesome-free/css/all.css',
    'public/assets/plugins/fontawesome-free/css/v4-shims.css',
    'public/assets/admin/iconfont/iconfont.css',
    'public/assets/plugins/admlte/googlefonts/fonts.css',
    'public/assets/plugins/glyphicons/css/glyphicons.css'
  ]);
  cssSrc.pipe(gulpCssAssets({
    processAllFile:true
  }))
    .pipe(gulpif(isCssFile, concat("base.min.css")))
    .pipe(gulpif(isCssFile, minifyCss()))
    .pipe(gulp.dest('public/assets/dist/basePc'));
  cb();
}

//pc base
function basePcJs(cb){
  gulp.src([
    // 'public/assets/plugins/moment/moment-with-locales.js',
  ]) // 获取js文件
    .pipe(concat('base.min.js')) //合并
    .pipe(uglify()) // 压缩js代码
    .pipe(gulp.dest('public/assets/dist/basePc'));
  cb();
}

exports.default = gulp.series(gulp.parallel(baseCss, baseJs, baseAdminCss,baseAdminJs, basePcCss));
