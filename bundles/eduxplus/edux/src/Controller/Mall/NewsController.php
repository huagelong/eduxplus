<?php
/**
 * @Author: kaihui.wang
 * @Contact  hpuwang@gmail.com
 * @Version: 1.0.0
 * @Date: 2020/5/7 11:01
 */

namespace Eduxplus\EduxBundle\Controller\Mall;

use Eduxplus\CoreBundle\Lib\View\View;
use Eduxplus\EduxBundle\Service\Mall\NewsCategoryService;
use Eduxplus\EduxBundle\Service\Mall\NewsService;
use Eduxplus\CoreBundle\Lib\Base\BaseAdminController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Eduxplus\CoreBundle\Lib\Form\Form;
use Eduxplus\CoreBundle\Lib\Grid\Grid;

class NewsController extends BaseAdminController
{

    /**
     *
     * @Route("/mall/news/index", name="admin_mall_news_index")
     */
    public function indexAction(Request $request, Grid $grid, NewsService $newsService){
        $pageSize = 40;
        $grid->setListService($newsService, "getList");
        $grid->text("#")->field("id")->sort("a.id");
        $grid->text("标题")->field("title");
        $grid->image("封面图片")->field("img");
        $grid->text("分类")->field("categoryName");
        $grid->boole2("上架？")->field("status")->sort("a.status")->actionCall("admin_api_mall_news_switchStatus", function ($obj) use($newsService) {
            $id = $newsService->getPro($obj, "id");
            $defaultValue = $newsService->getPro($obj, "status");
            $url = $this->generateUrl('admin_api_mall_news_switchStatus', ['id' => $id]);
            $checkStr = $defaultValue ? "checked" : "";
            $confirmStr = $defaultValue ? "确认要下架吗？" : "确认要上架吗?";
            $str = "<input type=\"checkbox\" data-bootstrap-switch-ajaxput href=\"{$url}\" data-confirm=\"{$confirmStr}\" {$checkStr} >";
            return $str;
        });
        $grid->text("添加人")->field("creater");
        $grid->text("浏览量")->field("viewNumber");
        $grid->text("置顶位置")->field("topValue");
        $grid->text("排序")->field("sort");
        $grid->datetime("创建时间")->field("createdAt")->sort("a.createdAt");

        //添加
        $grid->gbButton("添加")->route("admin_mall_news_add")
            ->url($this->generateUrl("admin_mall_news_add"))
            ->styleClass("btn-success")->iconClass("mdi mdi-plus");
        //搜索
        $grid->snumber("ID")->field("a.id");
        $grid->stext("标题")->field("a.title");

        //编辑等
        $grid->setTableAction('admin_mall_news_view', function ($obj) {
            $id = $obj['id'];
            $url = $this->generateUrl('admin_mall_news_view', ['id' => $id]);
            $str = '<a href=' . $url . ' data-title="查看" title="查看" class=" btn btn-default btn-xs poppage"><i class="mdi mdi-eye"></i></a>';
            return  $str;
        });

        $grid->setTableAction('admin_mall_news_edit', function ($obj) {
            $id = $obj['id'];
            $url = $this->generateUrl('admin_mall_news_edit', ['id' => $id]);
            $str = '<a href=' . $url . ' data-width="1000px" data-title="编辑" title="编辑" class=" btn btn-info btn-xs poppage"><i class="mdi mdi-file-document-edit"></i></a>';
            return  $str;
        });

        $grid->setTableAction('admin_api_mall_news_delete', function ($obj) {
            $id = $obj['id'];
            $url = $this->generateUrl('admin_api_mall_news_delete', ['id' => $id]);
            return '<a href=' . $url . ' data-confirm="确认要删除吗?" title="删除" class=" btn btn-danger btn-xs ajaxDelete"><i class="mdi mdi-delete"></i></a>';
        });

        //批量删除
        $bathDelUrl = $this->genUrl("admin_api_mall_news_bathdelete");
        $grid->setBathDelete("admin_api_mall_news_bathdelete", $bathDelUrl);

        $data = [];

        $data['list'] = $grid->create($request, $pageSize);

        return $this->render("@EduxBundle/mall/news/index.html.twig", $data);
    }

    /**
     *
     * @Route("/mall/news/view/{id}", name="admin_mall_news_view")
     */
    public function viewAction($id, View $view, NewsService $newsService, NewsCategoryService $helpCategoryService){
        $info = $newsService->getById($id);
        $select = $helpCategoryService->categorySelect();

        $view->text("标题")->field("title")->defaultValue($info['title']);
        $options = [];
        $options["data-upload-url"] = $this->generateUrl("admin_glob_upload", ["type" => "img_news_cover"]);
        $options["data-min-file-count"] = 1;
        $options['data-max-total-file-count'] = 1;
        $options["data-max-file-size"] = 1024 * 5; //5m
        $options["data-required"] = 1;
        if ($info) $options['data-initial-preview'] = $info['img'];
        if ($info) $options['data-initial-preview-config'] = $newsService->getInitialPreviewConfig($info['img']);
        $view->file("封面图片")->field("img")->attr($options)->defaultValue($info['img']);

        $view->select("帮助分类")->field("categoryId")->options($select)->defaultValue($info['categoryId']);
        $view->text("置顶位置")->field("topValue")->defaultValue($info['topValue']);
        $view->text("排序")->field("sort")->defaultValue($info['sort']);
        $view->richEditor("内容")->field("content")->defaultValue($info['main']['content']);
        $view->boole("上架？")->field("status")->defaultValue($info['status']);

        $formData = $view->create();
        $data = [];
        $data["formData"] = $formData;
        return $this->render("@EduxBundle/mall/news/view.html.twig", $data);
    }


    /**
     * @Route("/mall/news/add", name="admin_mall_news_add")
     */
    public function addAction(Form $form, NewsCategoryService $helpCategoryService)
    {
        $select = $helpCategoryService->categorySelect();
        $form->text("标题")->field("title")->isRequire(1);

        $options = [];
        $options["data-upload-url"] = $this->generateUrl("admin_glob_upload", ["type" => "img_news_cover"]);
        $options["data-min-file-count"] = 1;
        $options['data-max-total-file-count'] = 1;
        $options["data-max-file-size"] = 1024 * 5; //5m
        $options["data-required"] = 0;
        $form->file("封面图片")->field("img")->attr($options);

        $form->select("帮助分类")->field("categoryId")->isRequire()->options($select);
        $form->text("置顶位置")->field("topValue")->isRequire()->defaultValue(0)->placeholder("必须是数字,0-其他,1-公告，2-推荐，3-热门");
        $form->text("排序")->field("sort")->isRequire(1)->defaultValue(0)->placeholder("必须是数字,数字越大越靠前");

        $form->richEditor("内容")->field("content")->isRequire(1);
        $form->boole("上架？")->field("status")->isRequire(1);

        $formData = $form->create($this->generateUrl("admin_api_mall_news_add"));
        $data = [];
        $data["formData"] = $formData;
        return $this->render("@EduxBundle/mall/news/add.html.twig", $data);
    }

    /**
     * @Route("/mall/news/add/do", name="admin_api_mall_news_add")
     */
    public function addDoAction(Request $request, NewsService $newsService)
    {
        $title = $request->get("title");
        $content  = $request->get("content");
        $status  = $request->get("status");
        $categoryId  = $request->get("categoryId");
        $topValue =  (int) $request->get("topValue");
        $img  = $request->get("img");
        $sort  = $request->get("sort");

        $status = $status == "on" ? 1 : 0;

        if(!$title) return $this->responseError("标题不能为空!");
        if(!$content) return $this->responseError("内容不能为空!");
        if(!$categoryId) return $this->responseError("分类不能为空!");

        if(!mb_strlen($title, "utf-8")>100) return $this->responseError("标题不能大于100字!");

        $uid = $this->getUid();
        $newsService->add($title, $content, $status, $categoryId, $uid, $topValue, $sort, $img);

        if($this->error()->has()){
            return $this->responseError($this->error()->getLast());
        }
        return $this->responseMsgRedirect("操作成功!", $this->generateUrl('admin_mall_news_index'));
    }

    /**
     * @Route("/mall/news/edit/{id}", name="admin_mall_news_edit")
     */
    public function editAction($id, Form $form, NewsService $newsService, NewsCategoryService $newsCategoryService)
    {
        $info = $newsService->getById($id);
        $select = $newsCategoryService->categorySelect();
        $form->text("标题")->field("title")->isRequire(1)->defaultValue($info['title']);
        $options = [];
        $options["data-upload-url"] = $this->generateUrl("admin_glob_upload", ["type" => "img_news_cover"]);
        $options["data-min-file-count"] = 1;
        $options['data-max-total-file-count'] = 1;
        $options["data-max-file-size"] = 1024 * 5; //2m
        $options["data-required"] = 0;
        if ($info) $options['data-initial-preview'] = $info['img'];
        if ($info) $options['data-initial-preview-config'] = $newsService->getInitialPreviewConfig($info['img']);
        $form->file("封面图片")->field("img")->attr($options)->defaultValue($info['img']);

        $form->select("帮助分类")->field("categoryId")->isRequire()->options($select)->defaultValue($info['categoryId']);
        $form->text("置顶位置")->field("topValue")->isRequire(1)->defaultValue($info['topValue'])->placeholder("必须是数字,0-其他,1-公告，2-推荐，3-热门");
        $form->text("排序")->field("sort")->isRequire(1)->defaultValue($info['sort'])->placeholder("必须是数字,数字越大越靠前");
        $form->richEditor("内容")->field("content")->isRequire(1)->defaultValue($info['main']['content']);;
        $form->boole("上架？")->field("status")->isRequire(1)->defaultValue($info['status']);

        $formData = $form->create($this->generateUrl("admin_api_mall_news_edit", ["id"=>$id]));
        $data = [];
        $data["formData"] = $formData;
        return $this->render("@EduxBundle/mall/news/edit.html.twig", $data);
    }

    /**
     * @Route("/mall/news/edit/do/{id}", name="admin_api_mall_news_edit")
     */
    public function editDoAction($id, Request $request, NewsService $newsService)
    {
        $title = $request->get("title");
        $content  = $request->get("content");
        $status  = $request->get("status");
        $categoryId  = $request->get("categoryId");
        $topValue =   (int)  $request->get("topValue");
        $img  = $request->get("img");
        $sort  = $request->get("sort");

        $status = $status == "on" ? 1 : 0;

        if(!$title) return $this->responseError("标题不能为空!");
        if(!$content) return $this->responseError("内容不能为空!");
        if(!$categoryId) return $this->responseError("分类不能为空!");

        if(!mb_strlen($title, "utf-8")>100) return $this->responseError("标题不能大于100字!");
        $uid = $this->getUid();
        $newsService->edit($id, $title, $content, $status, $categoryId, $uid, $topValue, $sort, $img);

        if($this->error()->has()){
            return $this->responseError($this->error()->getLast());
        }
        return $this->responseMsgRedirect("操作成功!", $this->generateUrl('admin_mall_news_index'));
    }

    /**
     * @Route("/mall/news/delete/do/{id}", name="admin_api_mall_news_delete")
     */
    public function deleteDoAction($id, NewsService $newsService)
    {
        $newsService->del($id);
        return $this->responseMsgRedirect("删除成功!", $this->generateUrl("admin_mall_news_index"));
    }

    /**
     *
     * @Route("/mall/news/bathdelete/do", name="admin_api_mall_news_bathdelete")
     */
    public function bathdeleteDoAction(Request $request, NewsService $newsService)
    {
        $ids = $request->get("ids");
        if($ids){
            foreach ($ids as $id){
                $newsService->del($id);
            }
        }
        if($this->error()->has()){
            return $this->responseError($this->error()->getLast());
        }

        return $this->responseMsgRedirect("删除成功!", $this->generateUrl("admin_mall_news_index"));
    }



    /**
     * @Route("/mall/news/switchStatus/do/{id}", name="admin_api_mall_news_switchStatus")
     */
    public function switchStatusAction($id, NewsService $newsService, Request $request)
    {
        $state = (int) $request->get("state");
        $newsService->switchStatus($id, $state);
        return $this->responseMsgRedirect("操作成功!");
    }

}
