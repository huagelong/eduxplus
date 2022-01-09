<?php
/**
 * @Author: kaihui.wang
 * @Contact  hpuwang@gmail.com
 * @Version: 1.0.0
 * @Date: 2021/1/16 10:40
 */

namespace Eduxplus\QaBundle\Controller\Admin;


use Eduxplus\CoreBundle\Lib\Form\Form;
use Eduxplus\CoreBundle\Lib\Grid\Grid;
use Eduxplus\CoreBundle\Lib\View\View;
use Eduxplus\QaBundle\Service\Admin\QAGoodsService;
use Eduxplus\EduxBundle\Service\Teach\CategoryService;
use Eduxplus\EduxBundle\Service\Teach\ChapterService;
use Eduxplus\EduxBundle\Service\Teach\ProductService;
use Eduxplus\CoreBundle\Service\UserService;
use Eduxplus\CoreBundle\Lib\Base\BaseAdminController;
use Eduxplus\QaBundle\Service\Admin\QATestService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class MallGoodsController extends BaseAdminController
{

    /**
     *
     * @Route("/mall/goods/index", name="admin_qa_mall_goods_index")
     */
    public function indexAction(
        Request $request,
        Grid $grid,
        QAGoodsService $goodsService,
        CategoryService $categoryService,
        UserService $userService
    ) {
        $pageSize = 40;
        $grid->setListService($goodsService, "getList");
        $grid->text("ID")->field("id")->sort("a.id");
        $grid->text("试卷商品名称")->field("name");
        $grid->text("试卷商品别名")->field("aliasName");
        $grid->boole("组合试卷商品?")->field("isGroup")->sort("a.isGroup");
        $grid->boole2("上架？")->field("status")->sort("a.status")->actionCall("admin_api_mall_goods_switchStatus", function ($obj) use($goodsService) {
            $id = $goodsService->getPro($obj, "id");
            $defaultValue = $goodsService->getPro($obj, "status");
            $url = $this->generateUrl('admin_qa_api_mall_goods_switchStatus', ['id' => $id]);
            $checkStr = $defaultValue ? "checked" : "";
            $confirmStr = $defaultValue ? "确认要下架吗？" : "确认要上架吗?";
            $str = "<input type=\"checkbox\" data-bootstrap-switch-ajaxput href=\"{$url}\" data-confirm=\"{$confirmStr}\" {$checkStr} >";
            return $str;
        });
        $grid->text("品类")->field("brand");
        $grid->text("类目")->field("category")->sort("a.categoryId");
        $grid->text("年份")->field("parameter1");
        $grid->text("成本价")->field("marketPrice");
        $grid->text("售价")->field("shopPrice");
        $grid->text("真实购买人数")->field("buyNumber");
        $grid->text("创建人")->field("creater")->sort("a.createUid");
        $grid->text("组合试卷商品类型")->field("groupType")->sort("a.groupType")->options([0 => "未知", 1 => "子试卷商品可选择", 2 => "子试卷商品不可选择"]);
        $grid->text("协议")->field("agreement")->sort("a.agreementId");
        $grid->text("置顶")->field("topValue")->sort("a.topValue");
        $grid->text("热门试卷排序")->field("topValue")->sort("a.topValue");
        $grid->text("推荐试卷排序")->field("recommendValue")->sort("a.recommendValue");
        $grid->datetime("创建时间")->field("createdAt")->sort("a.createdAt");


        //编辑等
        $grid->setTableAction('admin_qa_mall_goods_view', function ($obj) {
            $id = $obj['id'];
            if($obj['isGroup']){
                $url = $this->generateUrl('admin_qa_mall_goods_viewgroup', ['id' => $id]);
            }else{
                $url = $this->generateUrl('admin_qa_mall_goods_view', ['id' => $id]);
            }
            $str = '<a href=' . $url . ' data-title="查看" title="查看" class=" btn btn-default btn-xs poppage"><i class="mdi mdi-eye"></i></a>';
            return  $str;
        });

        $grid->setTableAction('admin_qa_mall_goods_edit', function ($obj) {
            $id = $obj['id'];
            if($obj['isGroup']) {
                $url = $this->generateUrl('admin_qa_mall_goods_editgroup', ['id' => $id]);
            }else{
                $url = $this->generateUrl('admin_qa_mall_goods_edit', ['id' => $id]);
            }
            $str = '<a href=' . $url . ' data-width="1000px" data-title="编辑" title="编辑" class=" btn btn-info btn-xs poppage"><i class="mdi mdi-file-document-edit"></i></a>';
            return  $str;
        });

        $grid->deleteAction("admin_qa_api_mall_goods_delete");

        //批量删除
        $grid->setBathDelete("admin_qa_api_mall_goods_bathdelete");

        $grid->gbAddButton("admin_qa_mall_goods_add",[],"添加单个试卷商品");
        $grid->gbAddButton("admin_qa_mall_group_goods_add",[],"添加组合试卷商品");
        //搜索
        $select = $categoryService->categorySelect();
        $grid->snumber("ID")->field("a.id");
        $grid->stext("试卷商品名称")->field("a.name");
        $grid->sselect("组合试卷商品？")->field("a.isGroup")->options(["全部" => -1, "否" => 0, "是" => 1]);
        $grid->sselect("上架？")->field("a.status")->options(["全部" => -1, "下架" => 0, "上架" => 1]);
        $grid->ssearchselect("创建人")->field("a.createUid")->options(function () use ($request, $userService) {
            $values = $request->get("values");
            $createUid = ($values && isset($values["a.createUid"])) ? $values["a.createUid"] : 0;
            if ($createUid) {
                $users = $userService->searchResult($createUid);
            } else {
                $users = [];
            }
            return [$this->generateUrl("admin_api_glob_searchAdminUserDo"), $users];
        });

        $grid->sselect("类别")->field("a.categoryId")->options(function () use ($select) {
            return $select;
        });
        $grid->sdaterange("创建时间")->field("a.createdAt");

        $data = [];

        $data['list'] = $grid->create($request, $pageSize);

        return $this->render("@QaBundleAdmin/mall/goods/index.html.twig", $data);
    }

    /**
     *
     * @Route("/mall/goods/add", name="admin_qa_mall_goods_add")
     */
    public function addAction(
        Form $form,
        ProductService $productService,
        CategoryService $categoryService,
        QAGoodsService $goodsService
    ) {
        $form->text("试卷商品名称")->field("name")->isRequire();
        $form->text("试卷商品别名")->field("aliasName")->placeholder("试卷商品别名,可为空,作为组合试卷商品的子试卷商品的别名用");
        $form->text("副标题")->field("subhead");
        $form->searchSelect("产品")->field("productId")->isRequire(1)->options([$this->generateUrl("admin_qa_api_glob_searchProductDo"), []]);

        $form->text("标签")->field("tags")->isRequire(1)->placeholder("多个标签用,隔开");

        $form->select("类目")->field("categoryId")->isRequire(1)->options($categoryService->categorySelect());
        $form->select("年份")->field("parameter1")->options($goodsService->getYearRange());
        $form->text("成本价")->field("marketPrice")->isRequire(1);
        $form->text("售价")->field("shopPrice")->isRequire(1);
        $form->text("购买人数(假数据)")->field("buyNumberFalse")->isRequire(1);
        $form->select("购买协议")->field("agreementId")->isRequire(1)->options($productService->getAgreements());

        $form->boole("上架？")->field("status")->isRequire(1);
        $form->text("排序")->field("sort")->isRequire(1);
        $form->text("热门试卷排序")->field("topValue")->isRequire(1)->defaultValue(1)->placeholder("数字越大排在越前");
        $form->text("推荐试卷排序")->field("recommendValue")->isRequire(1)->defaultValue(1)->placeholder("数字越大排在越前");
        $form->richEditor("试卷介绍")->field("descr")->isRequire(0)->attr(['data-width' => 800, 'data-height' => 200]);

        $form->text("seo描述")->field("seoDescr");
        $form->text("seo关键字")->field("seoKeyWord");

        $formData = $form->create($this->generateUrl("admin_qa_api_mall_goods_add"));
        $data = [];
        $data["formData"] = $formData;$data["breadcrumb"] = 1;
        return $this->render("@QaBundleAdmin/mall/goods/add.html.twig", $data);
    }


    /**
     *
     * @Route("/mall/groupGoods/add", name="admin_qa_mall_group_goods_add")
     */
    public function addGroupAction(
        Form $form,
        ProductService $productService,
        CategoryService $categoryService,
        ChapterService $chapterService,
        QAGoodsService $goodsService
    ) {
        $form->text("试卷商品名称")->field("name")->isRequire();
        $form->text("试卷商品别名")->field("aliasName")->placeholder("试卷商品别名,可为空,作为组合试卷商品的子试卷商品的别名用");
        $form->text("副标题")->field("subhead");

        $form->text("标签")->field("tags")->isRequire(1)->placeholder("多个标签用,隔开");

        $form->searchMultipleSelect("组合试卷商品子试卷商品")->field("goodsId[]")->isRequire()->options([$this->generateUrl("admin_qa_api_glob_searchGoodsDo"), []]);
        $form->select("组合试卷商品购买方式")->field("groupType")->isRequire()->options(["请选择" => 0, "子试卷商品可选择" => 1, "子试卷商品不可选择" => 2]);
        $form->select("类目")->field("categoryId")->isRequire(1)->options($categoryService->categorySelect());
        $form->select("年份")->field("parameter1")->options($goodsService->getYearRange());
        $form->text("成本价")->field("marketPrice")->isRequire(1);
        $form->text("售价")->field("shopPrice")->isRequire(1);
        $form->text("购买人数(假数据)")->field("buyNumberFalse")->isRequire(1);
        $form->select("购买协议")->field("agreementId")->isRequire(1)->options($productService->getAgreements());

        $form->boole("上架？")->field("status")->isRequire(1);
        $form->text("排序")->field("sort")->isRequire(1);
        $form->text("热门课程排序")->field("topValue")->isRequire(1)->defaultValue(1)->placeholder("数字越大排在越前");
        $form->text("推荐课程排序")->field("recommendValue")->isRequire(1)->defaultValue(1)->placeholder("数字越大排在越前");
        $form->richEditor("试卷介绍")->field("descr")->isRequire(0)->attr(['data-width' => 800, 'data-height' => 200]);

        $form->text("seo描述")->field("seoDescr");
        $form->text("seo关键字")->field("seoKeyWord");

        $formData = $form->create($this->generateUrl("admin_qa_api_mall_goods_add"));
        $data = [];
        $data["formData"] = $formData;$data["breadcrumb"] = 1;
        return $this->render("@QaBundleAdmin/mall/goods/addgroup.html.twig", $data);
    }

    /**
     *
     * @Route("/mall/goods/add/do", name="admin_qa_api_mall_goods_add")
     */
    public function addDoAction(Request $request, QAGoodsService $goodsService, CategoryService $categoryService)
    {
        $name = $request->get("name");
        $productId = $request->get("productId");
        $goodsId = $request->get("goodsId");
        $categoryId = $request->get("categoryId");
        $subhead = $request->get("subhead");
        $marketPrice = $request->get("marketPrice");
        $shopPrice = $request->get("shopPrice");
        $buyNumberFalse = (int)  $request->get("buyNumberFalse");
        $status = $request->get("status");
        $sort = (int) $request->get("sort");
        $agreementId = (int) $request->get("agreementId");
        $groupType =  (int) $request->get("groupType");
        $year = $request->get("parameter1");
        $descr = $request->get("descr");
        $seoDescr = $request->get("seoDescr");
        $seoKeyWord = $request->get("seoKeyWord");
        $tags = $request->get("tags");
        $aliasName = $request->get("aliasName");
        $topValue = (int) $request->get("topValue");
        $recommendValue = (int) $request->get("recommendValue");

        $status = $status == "on" ? 1 : 0;

        if (!$name) return $this->responseError("试卷商品名称不能为空!");
        if (mb_strlen($name, 'utf-8') > 50) return $this->responseError("试卷商品名称不能大于50字!");

        if ($goodsService->checkName($name,0,2)) return $this->responseError("试卷商品名称已存在!");

        if (!$productId && !$goodsId) return $this->responseError("产品或者试卷商品必须选其一填写!");

        if ($seoDescr) {
            if (mb_strlen($seoDescr, 'utf-8') > 120) return $this->responseError("seo描述不能大于120字!");
        }

        if ($seoKeyWord) {
            if (mb_strlen($seoKeyWord, 'utf-8') > 120) return $this->responseError("seo关键字不能大于120字!");
        }

        if ($goodsId) {//组合试卷商品
            if ($productId) return $this->responseError("组合试卷商品不能添加产品!");
        } else {
            if (!$shopPrice) return $this->responseError("试卷商品售价不能为空!");
        }

        if ($subhead) {
            if (mb_strlen($subhead, 'utf-8') > 100) return $this->responseError("副标题不能大于100字!");
        }


        if(!$categoryId){
            return $this->responseError("类目必须选择!");
        }

        if ($aliasName) {
            if (mb_strlen($aliasName, 'utf-8') > 40) return $this->responseError("试卷商品别名不能大于40字!");
        }

        if(!is_numeric($year)){
            return $this->responseError("年份必须是数字!");
        }

        if(!$tags){
            return $this->responseError("标签不能为空!");
        }

        $cateGoryInfo = $categoryService->getById($categoryId);
        if (!$cateGoryInfo['parentId']) return $this->responseError("不能选择一级类目!");

        $uid = $this->getUid();
        $goodsService->addQA($uid,
            $name,
            $productId,
            $goodsId,
            $categoryId,
            $subhead,
            $year,
            $marketPrice,
            $shopPrice,
            $buyNumberFalse,
            $status,
            $sort,
            $agreementId,
            $groupType,
            $descr,
            $seoDescr,
            $seoKeyWord,
            $tags,
            $aliasName,
            $topValue,
            $recommendValue
        );

        return $this->responseMsgRedirect("操作成功!", $this->generateUrl('admin_qa_mall_goods_index'));
    }

    /**
     *
     * @Route("/mall/goods/edit/{id}", name="admin_qa_mall_goods_edit")
     */
    public function editAction(
        $id,
        Form $form,
        QAGoodsService $goodsService,
        CategoryService $categoryService,
        ProductService $productService,
        QATestService $testService
    ) {
        $info = $goodsService->getById($id);
        $form->text("试卷商品名称")->field("name")->isRequire()->defaultValue($info['name']);
        $form->text("试卷商品别名")->field("aliasName")->placeholder("试卷商品别名,可为空,作为组合试卷商品的子试卷商品的别名用")->defaultValue($info["aliasName"]);
        $form->text("副标题")->field("subhead")->defaultValue($info['subhead']);
        $form->searchSelect("产品")->field("productId")->isRequire()->defaultValue($info['productId'])->options(function () use ($info, $testService) {
            $productInfo = $testService->getById($info['id']);
            $tmp = [];
            if ($productInfo) {
                $tmp[$productInfo['name']] = $productInfo["id"];
            }
            return [$this->generateUrl("admin_qa_api_glob_searchProductDo"), $tmp];
        });

        $form->text("标签")->field("tags")->defaultValue($info['tags'])->isRequire(1)->placeholder("多个标签用,隔开");

        $form->select("类目")->field("categoryId")->isRequire(1)->defaultValue($info["categoryId"])->options($categoryService->categorySelect());

        $form->select("年份")->field("parameter1")->defaultValue($info['parameter1'])->isRequire(1)->options($goodsService->getYearRange());

        $form->text("成本价")->field("marketPrice")->isRequire(1)->defaultValue($info["marketPrice"] / 100);
        $form->text("售价")->field("shopPrice")->isRequire(1)->defaultValue($info["shopPrice"] / 100);
        $form->text("购买人数(假数据)")->field("buyNumberFalse")->isRequire(1)->defaultValue($info["buyNumberFalse"]);
        $form->select("购买协议")->field("agreementId")->isRequire(1)->options($productService->getAgreements())->defaultValue($info["agreementId"]);

        $form->boole("上架？")->field("status")->isRequire(1)->defaultValue($info['status']);
        $form->text("排序")->field("sort")->isRequire(1)->defaultValue($info['sort']);
        $form->text("热门课程排序")->field("topValue")->isRequire(1)->defaultValue($info['topValue'])->placeholder("数字越大排在越前");
        $form->text("推荐课程排序")->field("recommendValue")->isRequire(1)->defaultValue($info['recommendValue'])->placeholder("数字越大排在越前");
        $descr = isset($info["introduce"]["content"]) ? $info["introduce"]["content"] : "";
        $form->richEditor("试卷介绍")->field("descr")->defaultValue($descr)->isRequire(0)->attr(['data-width' => 800, 'data-height' => 200]);

        $form->text("seo描述")->field("seoDescr")->defaultValue($info['seoDescr']);
        $form->text("seo关键字")->field("seoKeyWord")->defaultValue($info['seoKeyWord']);

        $formData = $form->create($this->generateUrl("admin_qa_api_mall_goods_edit", [
            'id' => $id
        ]));
        $data = [];
        $data["formData"] = $formData;$data["breadcrumb"] = 1;
        return $this->render("@QaBundleAdmin/mall/goods/edit.html.twig", $data);
    }

    /**
     *
     * @Route("/mall/goods/editgroup/{id}", name="admin_qa_mall_goods_editgroup")
     */
    public function editgroupAction(
        $id,
        Form $form,
        QAGoodsService $goodsService,
        CategoryService $categoryService,
        ChapterService $chapterService,
        ProductService $productService
    ) {
        $info = $goodsService->getById($id);
        $form->text("试卷商品名称")->field("name")->isRequire()->defaultValue($info['name']);
        $form->text("试卷商品别名")->field("aliasName")->placeholder("试卷商品别名,可为空,作为组合试卷商品的子试卷商品的别名用")->defaultValue($info["aliasName"]);
        $form->text("副标题")->field("subhead")->defaultValue($info['subhead']);

        $form->text("标签")->field("tags")->defaultValue($info['tags'])->isRequire(1)->placeholder("多个标签用,隔开");

        $groupGoods = $goodsService->getGroupGoods($id);
        $form->searchMultipleSelect("组合试卷商品子试卷商品")->field("goodsId[]")->isRequire()->defaultValue($groupGoods)->options(function () use ($id, $goodsService) {
            $tmp = $goodsService->getSelectGoods($id);
            return [$this->generateUrl("admin_qa_api_glob_searchGoodsDo"), $tmp];
        });

        $form->select("组合试卷商品购买方式")->field("groupType")->isRequire()->defaultValue($info['groupType'])->options(["请选择" => 0, "子试卷商品可选择" => 1, "子试卷商品不可选择" => 2]);
        $form->select("类目")->field("categoryId")->isRequire(1)->defaultValue($info["categoryId"])->options($categoryService->categorySelect());

        $form->select("年份")->field("parameter1")->defaultValue($info['parameter1'])->isRequire(1)->options($goodsService->getYearRange());

        $form->text("成本价")->field("marketPrice")->isRequire(1)->defaultValue($info["marketPrice"] / 100);
        $form->text("售价")->field("shopPrice")->isRequire(1)->defaultValue($info["shopPrice"] / 100);
        $form->text("购买人数(假数据)")->field("buyNumberFalse")->isRequire(1)->defaultValue($info["buyNumberFalse"]);
        $form->select("购买协议")->field("agreementId")->isRequire(1)->options($productService->getAgreements())->defaultValue($info["agreementId"]);

        $form->boole("上架？")->field("status")->isRequire(1)->defaultValue($info['status']);
        $form->text("排序")->field("sort")->isRequire(1)->defaultValue($info['sort']);
        $form->text("热门课程排序")->field("topValue")->isRequire(1)->defaultValue($info['topValue'])->placeholder("数字越大排在越前");
        $form->text("推荐课程排序")->field("recommendValue")->isRequire(1)->defaultValue($info['recommendValue'])->placeholder("数字越大排在越前");

        $descr = isset($info["introduce"]["content"]) ? $info["introduce"]["content"] : "";
        $form->richEditor("试卷介绍")->field("descr")->defaultValue($descr)->isRequire(0)->attr(['data-width' => 800, 'data-height' => 200]);

        $form->text("seo描述")->field("seoDescr")->defaultValue($info['seoDescr']);
        $form->text("seo关键字")->field("seoKeyWord")->defaultValue($info['seoKeyWord']);

        $formData = $form->create($this->generateUrl("admin_qa_api_mall_goods_edit", [
            'id' => $id
        ]));
        $data = [];
        $data["formData"] = $formData;$data["breadcrumb"] = 1;
        return $this->render("@QaBundleAdmin/mall/goods/editgroup.html.twig", $data);
    }

    /**
     *
     * @Route("/mall/goods/view/{id}", name="admin_qa_mall_goods_view")
     */
    public function viewAction(
        $id,
        View $view,
        QAGoodsService $goodsService,
        CategoryService $categoryService,
        ChapterService $chapterService,
        ProductService $productService,
        QATestService $testService
    ) {
        $info = $goodsService->getById($id);

        $view->text("试卷商品名称")->field("name")->defaultValue($info['name']);
        $view->text("试卷商品别名")->field("aliasName")->defaultValue($info["aliasName"]);
        $view->text("副标题")->field("subhead")->defaultValue($info['subhead']);
        $view->searchSelect("产品")->field("productId")->defaultValue($info['productId'])->options(function () use ($info, $testService) {
            $productInfo = $testService->getById($info['id']);
            $tmp = [];
            if ($productInfo) {
                $tmp[$productInfo['name']] = $productInfo["id"];
            }
            return [$this->generateUrl("admin_qa_api_glob_searchProductDo"), $tmp];
        });

        $view->text("标签")->field("tags")->defaultValue($info['tags']);

        $view->select("类目")->field("categoryId")->defaultValue($info["categoryId"])->options($categoryService->categorySelect());
        $view->select("年份")->field("parameter1")->defaultValue($info['parameter1'])->options($goodsService->getYearRange());
        $view->text("成本价")->field("marketPrice")->defaultValue($info["marketPrice"] / 100);
        $view->text("售价")->field("shopPrice")->defaultValue($info["shopPrice"] / 100);
        $view->text("购买人数(假数据)")->field("buyNumberFalse")->defaultValue($info["buyNumberFalse"]);
        $view->select("购买协议")->field("agreementId")->options($productService->getAgreements())->defaultValue($info["agreementId"]);

        $view->boole("上架？")->field("status")->defaultValue($info['status']);
        $view->text("排序")->field("sort")->defaultValue($info['sort']);
        $view->text("热门试卷排序")->field("topValue")->defaultValue($info['topValue']);
        $view->text("推荐试卷排序")->field("recommendValue")->defaultValue($info['recommendValue']);

        $descr = isset($info["introduce"]["content"]) ? $info["introduce"]["content"] : "";
        $view->richEditor("试卷介绍")->field("descr")->defaultValue($descr)->attr(['data-width' => 800, 'data-height' => 200]);

        $view->text("seo描述")->field("seoDescr")->defaultValue($info['seoDescr']);
        $view->text("seo关键字")->field("seoKeyWord")->defaultValue($info['seoKeyWord']);

        $formData = $view->create();
        $data = [];
        $data["formData"] = $formData;$data["breadcrumb"] = 1;
        return $this->render("@QaBundleAdmin/mall/goods/view.html.twig", $data);
    }

    /**
     *
     * @Route("/mall/goods/viewgroup/{id}", name="admin_qa_mall_goods_viewgroup")
     */
    public function viewgroupAction(
        $id,
        View $view,
        QAGoodsService $goodsService,
        CategoryService $categoryService,
        ChapterService $chapterService,
        ProductService $productService
    ) {
        $info = $goodsService->getById($id);

        $view->text("试卷商品名称")->field("name")->defaultValue($info['name']);
        $view->text("试卷商品别名")->field("aliasName")->defaultValue($info["aliasName"]);
        $view->text("副标题")->field("subhead")->defaultValue($info['subhead']);

        $view->text("标签")->field("tags")->defaultValue($info['tags']);

        $groupGoods = $goodsService->getGroupGoods($id);
        $view->searchMultipleSelect("组合试卷商品子试卷商品")->field("goodsId[]")->defaultValue($groupGoods)->options(function () use ($id, $goodsService) {
            $tmp = $goodsService->getSelectGoods($id);
            return [$this->generateUrl("admin_api_glob_searchGoodsDo"), $tmp];
        });

        $view->select("组合试卷商品购买方式")->field("groupType")->defaultValue($info['groupType'])->options(["请选择" => 0, "子试卷商品可选择" => 1, "子试卷商品不可选择" => 2]);
        $view->select("类目")->field("categoryId")->defaultValue($info["categoryId"])->options($categoryService->categorySelect());
        $view->text("年份")->field("parameter1")->defaultValue($info['parameter1'])->options($goodsService->getYearRange());

        $view->text("成本价")->field("marketPrice")->defaultValue($info["marketPrice"] / 100);
        $view->text("售价")->field("shopPrice")->defaultValue($info["shopPrice"] / 100);
        $view->text("购买人数(假数据)")->field("buyNumberFalse")->defaultValue($info["buyNumberFalse"]);
        $view->select("购买协议")->field("agreementId")->options($productService->getAgreements())->defaultValue($info["agreementId"]);

        $view->boole("上架？")->field("status")->defaultValue($info['status']);
        $view->text("排序")->field("sort")->defaultValue($info['sort']);
        $view->text("热门试卷排序")->field("topValue")->defaultValue($info['topValue']);
        $view->text("推荐试卷排序")->field("recommendValue")->defaultValue($info['recommendValue']);

        $descr = isset($info["introduce"]["content"]) ? $info["introduce"]["content"] : "";
        $view->richEditor("试卷介绍")->field("descr")->defaultValue($descr)->attr(['data-width' => 800, 'data-height' => 200]);

        $view->text("seo描述")->field("seoDescr")->defaultValue($info['seoDescr']);
        $view->text("seo关键字")->field("seoKeyWord")->defaultValue($info['seoKeyWord']);

        $formData = $view->create();
        $data = [];
        $data["formData"] = $formData;$data["breadcrumb"] = 1;
        return $this->render("@QaBundleAdmin/mall/goods/viewgroup.html.twig", $data);
    }

    /**
     *
     * @Route("/mall/goods/edit/do/{id}", name="admin_qa_api_mall_goods_edit")
     */
    public function editDoAction($id, Request $request, QAGoodsService $goodsService)
    {
        $name = $request->get("name");
        $productId = $request->get("productId");
        $goodsId = $request->get("goodsId");
        $categoryId = $request->get("categoryId");
        $subhead = $request->get("subhead");

        $year = $request->get("parameter1");
        $marketPrice = $request->get("marketPrice");
        $shopPrice = $request->get("shopPrice");
        $buyNumberFalse = (int)  $request->get("buyNumberFalse");
        $status = $request->get("status");
        $sort = (int) $request->get("sort");
        $agreementId = (int) $request->get("agreementId");
        $groupType =  (int) $request->get("groupType");
        $descr = $request->get("descr");
        $seoDescr = $request->get("seoDescr");
        $seoKeyWord = $request->get("seoKeyWord");
        $tags = $request->get("tags");
        $aliasName = $request->get("aliasName");
        $topValue = (int) $request->get("topValue");
        $recommendValue = (int) $request->get("recommendValue");

        $status = $status == "on" ? 1 : 0;

        if (!$name) return $this->responseError("试卷商品名称不能为空!");
        if (mb_strlen($name, 'utf-8') > 50) return $this->responseError("试卷商品名称不能大于50字!");

        if ($goodsService->checkName($name, $id, 2)) return $this->responseError("试卷商品名称已存在!");

        if ($seoDescr) {
            if (mb_strlen($seoDescr, 'utf-8') > 120) return $this->responseError("seo描述不能大于120字!");
        }

        if ($seoKeyWord) {
            if (mb_strlen($seoKeyWord, 'utf-8') > 120) return $this->responseError("seo关键字不能大于120字!");
        }

        if(!$categoryId){
            return $this->responseError("类目必须选择!");
        }

        if (!$productId && !$goodsId) return $this->responseError("产品或者试卷商品必须选其一!");

        if ($goodsId) {
            if ($productId) return $this->responseError("组合试卷商品不能添加产品!");
            if(($groupType == 2) && (!$shopPrice)){
                return $this->responseError("整体售卖的组合试卷商品商品售价不能为空!");
            }
        } else {
            if (!$shopPrice) return $this->responseError("非组合试卷商品商品售价不能为空!");
        }

        if ($aliasName) {
            if (mb_strlen($aliasName, 'utf-8') > 40) return $this->responseError("试卷商品别名不能大于40字!");
        }

        if ($subhead) {
            if (mb_strlen($subhead, 'utf-8') > 100) return $this->responseError("副标题不能大于100字!");
        }

        if(!$tags){
            return $this->responseError("标签不能为空!");
        }

        if(!is_numeric($year)){
            return $this->responseError("年份必须是数字!");
        }


        $goodsService->editQA(
            $id,
            $name,
            $productId,
            $goodsId,
            $categoryId,
            $year,
            $subhead,
            $marketPrice,
            $shopPrice,
            $buyNumberFalse,
            $status,
            $sort,
            $agreementId,
            $groupType,
            $descr,
            $seoDescr,
            $seoKeyWord,
            $tags,
            $aliasName,
            $topValue,
            $recommendValue
        );

        return $this->responseMsgRedirect("操作成功!", $this->generateUrl('admin_qa_mall_goods_index'));
    }

    /**
     *
     * @Route("/mall/goods/delete/do/{id}", name="admin_qa_api_mall_goods_delete")
     */
    public function deleteDoAction($id, QAGoodsService $goodsService)
    {
        $goodsService->del($id);
        return $this->responseMsgRedirect("删除成功!", $this->generateUrl("admin_qa_mall_goods_index"));
    }

    /**
     *
     * @Route("/mall/goods/bathdelete/do", name="admin_qa_api_mall_goods_bathdelete")
     */
    public function bathdeleteDoAction(Request $request, QAGoodsService $goodsService)
    {
        $ids = $request->get("ids");
        if($ids){
            foreach ($ids as $id){
                $goodsService->del($id);
            }
        }

        if($this->error()->has()){
            return $this->responseError($this->error()->getLast());
        }

        return $this->responseMsgRedirect("删除成功!", $this->generateUrl("admin_qa_mall_goods_index"));
    }

    /**
     * @Route("/mall/goods/switchStatus/do/{id}", name="admin_qa_api_mall_goods_switchStatus")
     */
    public function switchStatusAction($id, QAGoodsService $goodsService, Request $request)
    {
        $state = (int) $request->get("state");
        $goodsService->switchStatus($id, $state);
        return $this->responseMsgRedirect("操作成功!");
    }
}
