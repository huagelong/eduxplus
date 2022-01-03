<?php
/**
 * @Author: kaihui.wang
 * @Contact  hpuwang@gmail.com
 * @Version: 1.0.0
 * @Date: 2020/11/28 19:41
 */

namespace Eduxplus\QaBundle\Controller\Admin;


use Eduxplus\CoreBundle\Lib\Form\Form;
use Eduxplus\CoreBundle\Lib\Grid\Grid;
use Eduxplus\CoreBundle\Lib\View\View;
use Eduxplus\CoreBundle\Service\UserService;
use Eduxplus\CoreBundle\Lib\Base\BaseAdminController;
use Eduxplus\QaBundle\Service\Admin\QAChapterService;
use Eduxplus\QaBundle\Service\Admin\QAChapterSubService;
use Eduxplus\QaBundle\Service\Admin\QANodeService;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

class NodeController extends BaseAdminController
{

    /**
     * @Route("/node/index/{chapterId}/{chapterSubId}", name="qa_admin_node_index", defaults={"chapterSubId":0})
     */
    public function indexAction($chapterId, $chapterSubId, Request $request, Grid $grid, QAChapterSubService $chapterSubService,
                                QANodeService $nodeService, QAChapterService $chapterService, UserService $userService){

        $pageSize = 40;
        $grid->setListService($nodeService, "getList", $chapterId);
        $grid->text("#")->field("id")->sort("a.id");
        $grid->text("试题类型")->field("type")->options([0=>"单项选择",1=>"多项选择",2=>"不定项选择题",3=>"判断题",4=>"填空题",5=>"问答题",6=>"理解题"]);
        $grid->text("试题")->field("topic");
        $grid->boole2("上架？")->field("status")->sort("a.status")->actionCall("qa_admin_node_switchStatus", function ($obj) use($nodeService) {
            $id = $nodeService->getPro($obj, "id");
            $defaultValue = $nodeService->getPro($obj, "status");
            $url = $this->generateUrl('qa_admin_node_switchStatus', ['id' => $id]);
            $checkStr = $defaultValue ? "checked" : "";
            $confirmStr = $defaultValue ? "确认要下架吗？" : "确认要上架吗?";
            $str = "<input type=\"checkbox\" data-bootstrap-switch-ajaxput href=\"{$url}\" data-confirm=\"{$confirmStr}\" {$checkStr} >";
            return $str;
        });
        $grid->text("章节点")->field("chapterSubName");
        $grid->text("试题难度")->field("level")->options([0=>"容易",1=>"一般",2=>"困难"]);
        $grid->text("试题标签")->field("nodeType")->options([0=>"常考题",1=>"易错题",2=>"好题",3=>"压轴题"]);
        $grid->text("分数")->field("score");
        $grid->text("年份")->field("year");
        $grid->text("来源")->field("source");
        $grid->text("创建人")->field("creater");
        $grid->datetime("创建时间")->field("createdAt")->sort("a.createdAt");

        //添加

        $grid->gbButton("添加")->route("qa_admin_node_add")
            ->url($this->generateUrl("qa_admin_node_add", ["chapterId"=>$chapterId, "chapterSubId"=>$chapterSubId]))
            ->styleClass("btn-success")->iconClass("fas fa-plus");


        $grid->setTableAction('qa_admin_node_view', function ($obj) {
            $id = $obj["id"];
            $url = $this->generateUrl('qa_admin_node_view', ['id' => $id]);
            $str = '<a href=' . $url . ' data-title="查看" title="查看" class=" btn btn-default btn-xs poppage"><i class="fas fa-eye"></i></a>';
            return  $str;
        });

        $grid->setTableAction('qa_admin_node_edit', function ($obj) {
            $id = $obj['id'];
            $url = $this->generateUrl('qa_admin_node_edit', ['id' => $id]);
            $str = '<a href=' . $url . ' data-width="1000px" data-title="编辑" title="编辑" class=" btn btn-info btn-xs poppage"><i class="mdi mdi-file-document-edit"></i></a>';
            return  $str;
        });

        $grid->setTableAction('qa_admin_node_delete', function ($obj) {
            $id = $obj['id'];
            $url = $this->generateUrl('qa_admin_node_delete', ['id' => $id]);
            return '<a href=' . $url . ' data-confirm="确认要删除吗?" title="删除" class=" btn btn-danger btn-xs ajaxDelete"><i class="mdi mdi-delete"></i></a>';
        });

        //批量删除
        $bathDelUrl = $this->genUrl("qa_admin_node_bathdelete");
        $grid->setBathDelete("qa_admin_node_bathdelete", $bathDelUrl);

        //搜索
        $grid->snumber("ID")->field("a.id");
        $grid->stext("题目")->field("a.topic");
        $select = $chapterSubService->chapterSelect($chapterId);
        $grid->sselect("章节点")->field("a.chapterSubId")->options($select);
        $grid->sselect("试题类型")->field("a.type")->options(["全部" => -1,"单项选择"=>0,"多项选择"=>1,"不定项选择题"=>2,"判断题"=>3,"填空题"=>4,"问答题"=>5,"理解题"=>6]);
        $grid->sdaterange("创建时间")->field("a.createdAt");
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

        $data = [];
        $data['chapter'] = $chapterService->getById($chapterId);
        $data['chapterId'] = $chapterId;
        $data['chapterSubId'] = $chapterSubId;
        $data['list'] = $grid->create($request, $pageSize);

        return $this->render("@QaBundleAdmin/node/index.html.twig", $data);
    }


    /**
     * @Route("/node/add/{chapterId}/{chapterSubId}", name="qa_admin_node_add", defaults={"chapterSubId":0})
     */
    public function addAction($chapterId, $chapterSubId, Form $form, QAChapterSubService $chapterSubService){

        $select = $chapterSubService->chapterSelect($chapterId);

        $year = date("Y");
        $years = [];
        $start = $year-10;
        for ($i=$start;$i<($year+2);$i++){
            $years[$i] = $i;
        }

        $form->select("试题类型")->field("type")->options(["单项选择"=>0,"多项选择"=>1,"不定项选择题"=>2,"判断题"=>3,"填空题"=>4,"问答题"=>5,"理解题"=>6]);
        $form->boole("上架？")->field("status")->isRequire(1);
        $form->select("章节点")->field("chapterSubId")->isRequire(1)->options($select)->defaultValue($chapterSubId);
        $form->select("试题难度")->field("level")->options(["容易"=>0,"一般"=>1,"困难"=>2]);
        $form->select("试题标签")->field("nType")->options(["常考题"=>0,"易错题"=>1,"好题"=>2,"压轴题"=>3]);
        $form->text("分数")->field("score")->defaultValue(10);
        $form->select("年份")->field("year")->options($years);
//
        $form->text("知识点")->isRequire(1)->field("knowledge");
        $form->text("来源")->isRequire(1)->field("source");
        $form->richEditor("试题")->isRequire(1)->field("topic");
        $form->richEditor("解析")->isRequire(1)->field("analysis");
        $form->disableSubmit();


        $formData = $form->create($this->generateUrl("qa_admin_node_do_add"));
        $data = [];
        $data["formData"] = $formData;
        $data["chapterId"] = $chapterId;
        $data["chapterSubId"] = $chapterSubId;

        return $this->render("@QaBundleAdmin/node/add.html.twig", $data);
    }

    /**
     *
     * @Route("/node/add/do", name="qa_admin_node_do_add")
     */
    public function addDoAction(Request $request, QANodeService $nodeService, QAChapterSubService $chapterSubService)
    {
        $type = (int) $request->get("type");
        $status = $request->get("status");
        $level = (int) $request->get("level");
        $nodeType = (int) $request->get("nType");
        $year = (int) $request->get("year");
        $knowledge = $request->get("knowledge");
        $source = $request->get("source");
        $topic = $request->get("topic");
        $analysis = $request->get("analysis");
        $chapterSubId = $request->get("chapterSubId");
        $choose = $request->get("choose");
        $answer = $request->get("answer");
        $score = (int) $request->get("score");
        $status = $status == "on" ? 1 : 0;
        $topic = strip_tags($topic, "<img> <p>");
        $analysis = strip_tags($analysis, "<img> <p>");

        if (!$topic) return $this->responseError("试题不能为空!");
        if (!$analysis) return $this->responseError("解析不能为空!");
        if(!$answer) return $this->responseError("答案不能为空!");
        if(!$knowledge) return $this->responseError("知识点不能为空!");
        if($score<=0) return $this->responseError("分数不能小于或者等于0!");
        if(mb_strlen($topic, "utf-8") > 500) return $this->responseError("试题不能大于500!");
        if(mb_strlen($analysis, "utf-8") > 500) return $this->responseError("解析不能大于500!");
        if(mb_strlen($knowledge, "utf-8") > 500) return $this->responseError("知识点不能大于500!");

        if(!$chapterSubId)  return $this->responseError("章节点不能为空!");

        if($type < 3){
            if(!count($choose)) return $this->responseError("选择项不能为空!");
        }

        $chapterSubInfo = $chapterSubService->getById($chapterSubId);
        $chapterId = $chapterSubInfo['chapterId'];
        $uid = $this->getUid();
        $nodeService->add($choose,$answer,$uid,$chapterId, $chapterSubId,$type, $status, $level, $nodeType, $year, $knowledge, $source,$topic,$analysis,$score);
        return $this->responseMsgRedirect("添加成功!", $this->generateUrl("qa_admin_node_index",["chapterId"=>$chapterId, "chapterSubId"=>$chapterSubId]));
    }


    /**
     * @Route("/node/edit/{id}", name="qa_admin_node_edit")
     */
    public function editAction($id, Form $form, QANodeService $nodeService, QAChapterSubService $chapterSubService){

        $info = $nodeService->getById($id);

        $chapterId = $info['chapterId'];
        $chapterSubId = $info['chapterSubId'];

        $select = $chapterSubService->chapterSelect($chapterId);

        $year = date("Y");
        $years = [];
        $start = $year-10;
        for ($i=$start;$i<($year+2);$i++){
            $years[$i] = $i;
        }

        $form->select("试题类型")->field("type")->defaultValue($info['type'])->options(["单项选择"=>0,"多项选择"=>1,"不定项选择题"=>2,"判断题"=>3,"填空题"=>4,"问答题"=>5,"理解题"=>6]);
        $form->boole("上架？")->field("status")->defaultValue($info['status'])->isRequire(1);
        $form->select("章节点")->field("chapterSubId")->defaultValue($info['chapterSubId'])->isRequire(1)->options($select)->defaultValue($chapterSubId);
        $form->select("试题难度")->field("level")->defaultValue($info['level'])->options(["容易"=>0,"一般"=>1,"困难"=>2]);
        $form->select("试题标签")->field("nType")->defaultValue($info['nodeType'])->options(["常考题"=>0,"易错题"=>1,"好题"=>2,"压轴题"=>3]);
        $form->text("分数")->field("score")->defaultValue($info["sub"]['score']);
        $form->select("年份")->field("year")->defaultValue($info['year'])->options($years);

        $form->text("知识点")->isRequire(1)->defaultValue($info['sub']['knowledge'])->field("knowledge");
        $form->text("来源")->isRequire(1)->defaultValue($info['source'])->field("source");
        $form->richEditor("试题")->isRequire(1)->defaultValue($info['topic'])->field("topic");
        $form->richEditor("解析")->isRequire(1)->defaultValue($info['sub']['analysis'])->field("analysis");
        $form->disableSubmit();


        $formData = $form->create($this->generateUrl("qa_admin_node_do_edit",["id"=>$id]));
        $data = [];
        $data["formData"] = $formData;
        $data["chapterId"] = $chapterId;
        $data["chapterSubId"] = $chapterSubId;
        $data["id"] = $id;
        $data["info"] = $info;

        return $this->render("@QaBundleAdmin/node/edit.html.twig", $data);
    }

    /**
     * @Route("/node/view/{id}", name="qa_admin_node_view")
     */
    public function viewAction($id, View $view, QANodeService $nodeService, QAChapterSubService $chapterSubService){

        $info = $nodeService->getById($id);

        $chapterId = $info['chapterId'];
        $chapterSubId = $info['chapterSubId'];

        $select = $chapterSubService->chapterSelect($chapterId);

        $year = date("Y");
        $years = [];
        $start = $year-10;
        for ($i=$start;$i<($year+11);$i++){
            $years[$i] = $i;
        }

        $view->select("试题类型")->field("type")->defaultValue($info['type'])->options(["单项选择"=>0,"多项选择"=>1,"不定项选择题"=>2,"判断题"=>3,"填空题"=>4,"问答题"=>5,"理解题"=>6]);
        $view->boole("上架？")->field("status")->defaultValue($info['status']);
        $view->select("章节点")->field("chapterSubId")->defaultValue($info['chapterSubId'])->options($select)->defaultValue($chapterSubId);
        $view->select("试题难度")->field("level")->defaultValue($info['level'])->options(["容易"=>0,"一般"=>1,"困难"=>2]);
        $view->select("试题标签")->field("nType")->defaultValue($info['nodeType'])->options(["常考题"=>0,"易错题"=>1,"好题"=>2,"压轴题"=>3]);
        $view->text("分数")->field("score")->defaultValue($info["sub"]['score']);
        $view->select("年份")->field("year")->defaultValue($info['year'])->options($years);

        $view->text("知识点")->defaultValue($info['sub']['knowledge'])->field("knowledge");
        $view->text("来源")->defaultValue($info['source'])->field("source");
        $view->richEditor("试题")->defaultValue($info['topic'])->field("topic");
        $view->richEditor("解析")->defaultValue($info['sub']['analysis'])->field("analysis");
        $view->disableSubmit();


        $formData = $view->create();
        $data = [];
        $data["formData"] = $formData;
        $data["chapterId"] = $chapterId;
        $data["chapterSubId"] = $chapterSubId;
        $data["id"] = $id;
        $data["info"] = $info;

        return $this->render("@QaBundleAdmin/node/view.html.twig", $data);
    }

    /**
     *
     * @Route("/node/edit/do/{id}", name="qa_admin_node_do_edit")
     */
    public function editDoAction($id, Request $request, QANodeService $nodeService, QAChapterSubService $chapterSubService)
    {
        $type = (int) $request->get("type");
        $status = $request->get("status");
        $level = (int) $request->get("level");
        $nodeType = (int) $request->get("nType");
        $year = (int) $request->get("year");
        $knowledge = $request->get("knowledge");
        $source = $request->get("source");
        $topic = $request->get("topic");
        $analysis = $request->get("analysis");
        $chapterSubId = $request->get("chapterSubId");
        $choose = $request->get("choose");
        $answer = $request->get("answer");
        $score = $request->get("score");
        $status = $status == "on" ? 1 : 0;
        $topic = strip_tags($topic, "<img> <p>");
        $analysis = strip_tags($analysis, "<img> <p>");

        if (!$topic) return $this->responseError("试题不能为空!");
        if (!$analysis) return $this->responseError("解析不能为空!");
        if(!$answer) return $this->responseError("答案不能为空!");
        if(!$knowledge) return $this->responseError("知识点不能为空!");

        if(mb_strlen($topic, "utf-8") > 500) return $this->responseError("试题不能大于500!");
        if(mb_strlen($analysis, "utf-8") > 500) return $this->responseError("解析不能大于500!");
        if(mb_strlen($knowledge, "utf-8") > 500) return $this->responseError("知识点不能大于500!");
        if($score<=0) return $this->responseError("分数不能小于或者等于0!");
        if(!$chapterSubId)  return $this->responseError("章节点不能为空!");

        if($type < 3){
            if(!count($choose)) return $this->responseError("选择项不能为空!");
        }

        $chapterSubInfo = $chapterSubService->getById($chapterSubId);
        $chapterId = $chapterSubInfo['chapterId'];

        $nodeService->edit($id, $choose,$answer,$chapterId, $chapterSubId,$type, $status, $level, $nodeType, $year, $knowledge, $source,$topic,$analysis,$score);
        // return $this->responseSuccess("编辑成功!");
        return $this->responseMsgRedirect("编辑成功!", $this->generateUrl("qa_admin_node_index",["chapterId"=>$chapterId, "chapterSubId"=>$chapterSubId]));
    }


    /**
     *
     * @Route("/node/delete/do/{id}", name="qa_admin_node_delete")
     */
    public function deleteDoAction($id, QANodeService $nodeService)
    {
        $info = $nodeService->getById($id);

        $nodeService->del($id);

        $chapterSubId = $info['chapterSubId'];
        $chapterId = $info['chapterId'];

        return $this->responseMsgRedirect("删除成功!", $this->generateUrl("qa_admin_node_index",["chapterId"=>$chapterId, "chapterSubId"=>$chapterSubId]));
    }


    /**
     *
     * @Route("/node/bathdelete/do", name="qa_admin_node_bathdelete")
     */
    public function bathdeleteDoAction(Request $request, QANodeService $nodeService)
    {
        $ids = $request->get("ids");
        if($ids){
            foreach ($ids as $id){
                $nodeService->del($id);
            }
        }

        if($this->error()->has()){
            return $this->responseError($this->error()->getLast());
        }

        $id = current($ids);
        $info = $nodeService->getById($id);
        $chapterSubId = $info['chapterSubId'];
        $chapterId = $info['chapterId'];
        return $this->responseMsgRedirect("删除成功!", $this->generateUrl("qa_admin_node_index",["chapterId"=>$chapterId, "chapterSubId"=>$chapterSubId]));
    }

    /**
     * @Route("/node/switchStatus/do/{id}", name="qa_admin_node_switchStatus")
     */
    public function switchStatusAction($id,Request $request, QANodeService $nodeService)
    {
        $state = (int) $request->get("state");
        $nodeService->switchStatus($id, $state);
        return $this->responseMsgRedirect("操作成功!");
    }

}
