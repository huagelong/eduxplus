<?php
/**
 * @Author: kaihui.wang
 * @Contact  hpuwang@gmail.com
 * @Version: 1.0.0
 * @Date: 2020/9/28 16:56
 */

namespace Eduxplus\CoreBundle\Controller\Mall;


use Eduxplus\CoreBundle\Lib\Form\Form;
use Eduxplus\CoreBundle\Lib\Grid\Grid;
use Eduxplus\CoreBundle\Lib\View\View;
use Eduxplus\CoreBundle\Service\Mall\BannerService;
use Eduxplus\CoreBundle\Lib\Base\BaseAdminController;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\HttpFoundation\Request;

class BannerController extends BaseAdminController
{
    /**
     *
     * @Rest\Get("/mall/banner/index", name="admin_mall_banner_index")
     */
     public function indexAction( Request $request,Grid $grid, BannerService $bannerService){
         $pageSize = 40;
         $grid->setListService($bannerService, "getList");
         $grid->text("#")->field("id")->sort("a.id");
         $grid->text("名称")->field("name");
         $grid->text("位置")->field("position");
         $grid->datetime("创建时间")->field("createdAt")->sort("a.createdAt");

         //添加
         $grid->gbButton("添加")->route("admin_mall_banner_add")
             ->url($this->generateUrl("admin_mall_banner_add"))
             ->styleClass("btn-success")->iconClass("fas fa-plus");
         //搜索
         $grid->snumber("ID")->field("a.id");
         $grid->stext("名称")->field("a.name");

         //单个banner列表
         $grid->setTableAction('admin_mall_bannermain_index', function ($obj) {
             $id = $obj['id'];
             $url = $this->generateUrl('admin_mall_bannermain_index', ['pid' => $id]);
             $str = '<a href=' . $url . ' data-width="1000px" data-title="单个banner列表" title="单个banner列表" class=" btn btn-info btn-xs"><i class="fas fa-list-ol"></i></a>';
             return  $str;
         });
         //编辑
         $grid->setTableAction('admin_mall_banner_edit', function ($obj) {
             $id = $obj['id'];
             $url = $this->generateUrl('admin_mall_banner_edit', ['id' => $id]);
             $str = '<a href=' . $url . ' data-width="1000px" data-title="编辑" title="编辑" class=" btn btn-info btn-xs poppage"><i class="fas fa-edit"></i></a>';
             return  $str;
         });

         $grid->setTableAction('admin_api_mall_banner_delete', function ($obj) {
             $id = $obj['id'];
             $url = $this->generateUrl('admin_api_mall_banner_delete', ['id' => $id]);
             return '<a href=' . $url . ' data-confirm="确认要删除吗?" title="删除" class=" btn btn-danger btn-xs ajaxDelete"><i class="fas fa-trash"></i></a>';
         });

         //批量删除
         $bathDelUrl = $this->genUrl("admin_api_mall_banner_bathdelete");
         $grid->setBathDelete("admin_api_mall_banner_bathdelete", $bathDelUrl);

         $data = [];

         $data['list'] = $grid->create($request, $pageSize);

         return $this->render("@CoreBundle/mall/banner/index.html.twig", $data);
     }

    /**
     *
     * @Rest\Get("/mall/banner/add", name="admin_mall_banner_add")
     */
    public function addAction(Form $form) {
        $form->text("名称")->field("name")->isRequire(1);
        $form->text("位置")->field("position")->isRequire(1)->defaultValue(0);

        $formData = $form->create($this->generateUrl("admin_api_mall_banner_add"));
        $data = [];
        $data["formData"] = $formData;
        return $this->render("@CoreBundle/mall/banner/add.html.twig", $data);
    }


    /**
     *
     * @Rest\Post("/mall/banner/add/do", name="admin_api_mall_banner_add")
     */
    public function addDoAction(Request $request, BannerService $bannerService)
    {
        $name = $request->get("name");
        $position  = (int) $request->get("position");

        if(!$name) return $this->responseError("名称不能为空!");

        if(!mb_strlen($name, "utf-8")>100) return $this->responseError("单页名称不能大于100字!");

        if($bannerService->getByPosition($position)) return $this->responseError("banner位置已经被占用");

        $bannerService->add($name,$position);

        if($this->error()->has()){
            return $this->responseError($this->error()->getLast());
        }
        return $this->responseMsgRedirect("操作成功!", $this->generateUrl('admin_mall_banner_index'));
    }


    /**
     *
     * @Rest\Get("/mall/banner/edit/{id}", name="admin_mall_banner_edit")
     */
    public function editAction($id, Form $form, BannerService $bannerService) {
        $info = $bannerService->getById($id);

        $form->text("名称")->field("name")->isRequire(1)->defaultValue($info['name']);
        $form->text("位置")->field("position")->isRequire(1)->defaultValue($info['position']);

        $formData = $form->create($this->generateUrl("admin_api_mall_banner_edit", ["id"=>$id]));
        $data = [];
        $data["formData"] = $formData;
        return $this->render("@CoreBundle/mall/banner/edit.html.twig", $data);
    }

    /**
     *
     * @Rest\Post("/mall/banner/edit/do/{id}", name="admin_api_mall_banner_edit")
     */
    public function editDoAction($id, Request $request, BannerService $bannerService)
    {
        $name = $request->get("name");
        $position  = (int) $request->get("position");

        if(!$name) return $this->responseError("名称不能为空!");

        if(!mb_strlen($name, "utf-8")>100) return $this->responseError("单页名称不能大于100字!");

        if($bannerService->getByPosition($position, $id)) return $this->responseError("banner位置已经被占用");

        $bannerService->edit($id, $name,$position);

        if($this->error()->has()){
            return $this->responseError($this->error()->getLast());
        }
        return $this->responseMsgRedirect("操作成功!", $this->generateUrl('admin_mall_banner_index'));
    }


    /**
     *
     * @Rest\Post("/mall/banner/delete/do/{id}", name="admin_api_mall_banner_delete")
     */
    public function deleteDoAction($id, BannerService $bannerService)
    {
        $bannerService->del($id);
        return $this->responseMsgRedirect("删除成功!", $this->generateUrl("admin_mall_banner_index"));
    }


    /**
     *
     * @Rest\Post("/mall/banner/bathdelete/do", name="admin_api_mall_banner_bathdelete")
     */
    public function bathDeleteDoAction(Request $request, BannerService $bannerService)
    {
        $ids = $request->get("ids");
        if($ids){
            foreach ($ids as $id){
                $bannerService->del($id);
            }
        }
        if($this->error()->has()){
            return $this->responseError($this->error()->getLast());
        }

        return $this->responseMsgRedirect("删除成功!", $this->generateUrl("admin_mall_banner_index"));
    }

    /**
     *
     * @Rest\Get("/mall/bannermain/index/{pid}", name="admin_mall_bannermain_index")
     */
    public function indexMainAction( $pid, Request $request,Grid $grid, BannerService $bannerService){
        $pageSize = 40;
        $grid->setListService($bannerService, "getMainList", $pid);
        $grid->text("#")->field("id")->sort("a.id");
        $grid->image("图片")->field("bannerImg");
        $grid->text("操作人")->field("creater");
        $grid->tip("链接")->field("url");
        $grid->text("排序")->field("sort")->sort("a.sort");
        $grid->boole2("上架？")->field("status")->sort("a.status")->actionCall("admin_api_mall_bannermain_switchStatus", function ($obj) {
            $id = $this->getPro($obj, "id");
            $defaultValue = $this->getPro($obj, "status");
            $url = $this->generateUrl('admin_api_mall_bannermain_switchStatus', ['id' => $id]);
            $checkStr = $defaultValue ? "checked" : "";
            $confirmStr = $defaultValue ? "确认要下架吗？" : "确认要上架吗?";
            $str = "<input type=\"checkbox\" data-bootstrap-switch-ajaxput href=\"{$url}\" data-confirm=\"{$confirmStr}\" {$checkStr} >";
            return $str;
        });
        $grid->datetime("创建时间")->field("createdAt")->sort("a.createdAt");

        //添加
        $grid->gbButton("添加")->route("admin_mall_bannermain_add")
            ->url($this->generateUrl("admin_mall_bannermain_add", ["pid"=>$pid]))
            ->styleClass("btn-success")->iconClass("fas fa-plus");

        //编辑
        $grid->setTableAction('admin_mall_bannermain_edit', function ($obj) {
            $id = $obj['id'];
            $url = $this->generateUrl('admin_mall_bannermain_edit', ['id' => $id]);
            $str = '<a href=' . $url . ' data-width="1000px" data-title="编辑" title="编辑" class=" btn btn-info btn-xs poppage"><i class="fas fa-edit"></i></a>';
            return  $str;
        });

        $grid->setTableAction('admin_api_mall_bannermain_delete', function ($obj) {
            $id = $obj['id'];
            $url = $this->generateUrl('admin_api_mall_bannermain_delete', ['id' => $id]);
            return '<a href=' . $url . ' data-confirm="确认要删除吗?" title="删除" class=" btn btn-danger btn-xs ajaxDelete"><i class="fas fa-trash"></i></a>';
        });

        //批量删除
        $bathDelUrl = $this->genUrl("admin_api_mall_bannermain_bathdelete");
        $grid->setBathDelete("admin_api_mall_bannermain_bathdelete", $bathDelUrl);

        $data = [];

        $data['list'] = $grid->create($request, $pageSize);

        return $this->render("@CoreBundle/mall/bannermain/index.html.twig", $data);
    }

    /**
     *
     * @Rest\Get("/mall/bannermain/add/{pid}", name="admin_mall_bannermain_add")
     */
    public function addMainAction($pid, Form $form) {
        $options = [];
        $options["data-upload-url"] = $this->generateUrl("admin_glob_upload", ["type" => "img_banner"]);
        $options["data-min-file-count"] = 1;
        $options['data-max-total-file-count'] = 1;
        $options["data-max-file-size"] = 1024 * 5; //5m
        $options["data-required"] = 1;
        $form->file("banner图片")->field("bannerImg")->attr($options);
        $form->text("链接地址")->field("url")->isRequire(0);
        $form->text("排序")->field("sort")->isRequire(1)->defaultValue(0);
        $form->boole("上架？")->field("status")->isRequire(1);
        $form->hidden("pid")->field("pid")->defaultValue($pid);

        $formData = $form->create($this->generateUrl("admin_api_mall_bannermain_add"));
        $data = [];
        $data["formData"] = $formData;
        $data["pid"] = $pid;
        return $this->render("@CoreBundle/mall/bannermain/add.html.twig", $data);
    }


    /**
     *
     * @Rest\Post("/mall/bannermain/add/do", name="admin_api_mall_bannermain_add")
     */
    public function addMainDoAction(Request $request, BannerService $bannerService)
    {
        $pid = $request->get("pid");
        $bannerImg  = $request->get("bannerImg");
        $url =  $request->get("url");
        $sort =  (int) $request->get("sort");
        $status  = $request->get("status");

        $status = $status == "on" ? 1 : 0;

        if(!$bannerImg) return $this->responseError("banner图片不能为空!");
        $uid = $this->getUid();
        $bannerService->addMain($pid,$uid, $bannerImg,$url,$sort, $status);

        if($this->error()->has()){
            return $this->responseError($this->error()->getLast());
        }
        return $this->responseMsgRedirect("操作成功!", $this->generateUrl('admin_mall_bannermain_index',["pid"=>$pid]));
    }


    /**
     *
     * @Rest\Get("/mall/bannermain/edit/{id}", name="admin_mall_bannermain_edit")
     */
    public function editMainAction($id, Form $form, BannerService $bannerService) {
        $info = $bannerService->getMainById($id);

        $options = [];
        $options["data-upload-url"] = $this->generateUrl("admin_glob_upload", ["type" => "img_banner"]);
        $options["data-min-file-count"] = 1;
        $options['data-max-total-file-count'] = 1;
        $options["data-max-file-size"] = 1024 * 5; //5m
        $options["data-required"] = 1;
        if ($info) $options['data-initial-preview'] = $info['bannerImg'];
        if ($info) $options['data-initial-preview-config'] = $bannerService->getInitialPreviewConfig($info['bannerImg']);
        $form->file("banner图片")->field("bannerImg")->attr($options)->defaultValue($info['bannerImg']);

        $form->text("链接地址")->field("url")->isRequire(0)->defaultValue($info['url']);
        $form->text("排序")->field("sort")->isRequire(1)->defaultValue($info['sort']);
        $form->boole("上架？")->field("status")->isRequire(1)->defaultValue($info['status']);

        $formData = $form->create($this->generateUrl("admin_api_mall_bannermain_edit", ["id"=>$id]));
        $data = [];
        $data["formData"] = $formData;
        $data["pid"] = $info['bannerId'];
        return $this->render("@CoreBundle/mall/bannermain/edit.html.twig", $data);
    }

    /**
     *
     * @Rest\Post("/mall/bannermain/edit/do/{id}", name="admin_api_mall_bannermain_edit")
     */
    public function editMainDoAction($id, Request $request, BannerService $bannerService)
    {
        $bannerImg  = $request->get("bannerImg");
        $url =  $request->get("url");
        $sort =  (int) $request->get("sort");
        $status  = $request->get("status");

        $status = $status == "on" ? 1 : 0;

        if(!$bannerImg) return $this->responseError("banner图片不能为空!");
        $uid = $this->getUid();
        $bannerService->editMain($id,$uid, $bannerImg,$url,$sort, $status);

        if($this->error()->has()){
            return $this->responseError($this->error()->getLast());
        }
        $info = $bannerService->getMainById($id);
        return $this->responseMsgRedirect("操作成功!", $this->generateUrl('admin_mall_bannermain_index',["pid"=>$info['bannerId']]));
    }


    /**
     *
     * @Rest\Post("/mall/bannermain/delete/do/{id}", name="admin_api_mall_bannermain_delete")
     */
    public function deleteMainDoAction($id, BannerService $bannerService)
    {
        $info = $bannerService->getMainById($id);
        $bannerService->delMain($id);
        return $this->responseMsgRedirect("删除成功!", $this->generateUrl("admin_mall_bannermain_index",["pid"=>$info['bannerId']]));
    }


    /**
     *
     * @Rest\Post("/mall/bannermain/bathdelete/do", name="admin_api_mall_bannermain_bathdelete")
     */
    public function bathDeleteMainDoAction(Request $request, BannerService $bannerService)
    {
        $ids = $request->get("ids");
        if($ids){
            $idTmp = current($ids);
            $info = $bannerService->getMainById($idTmp);
            foreach ($ids as $id){
                $bannerService->delMain($id);
            }
        }else{
            return $this->responseError("参数错误");
        }
        if($this->error()->has()){
            return $this->responseError($this->error()->getLast());
        }
        return $this->responseMsgRedirect("删除成功!", $this->generateUrl("admin_mall_bannermain_index",["pid"=>$info['bannerId']]));
    }

    /**
     * @Rest\Post("/mall/bannermain/switchStatus/do/{id}", name="admin_api_mall_bannermain_switchStatus")
     */
    public function switchStatusMainAction($id, BannerService $bannerService, Request $request)
    {
        $state = (int) $request->get("state");
        $bannerService->switchStatus($id, $state);
        return $this->responseMsgRedirect("操作成功!");
    }

}
