<?php
/**
 * @Author: kaihui.wang
 * @Contact  hpuwang@gmail.com
 * @Version: 1.0.0
 * @Date: 2020/12/6 20:24
 */

namespace App\Bundle\QABundle\Controller\Admin;


use App\Bundle\AdminBundle\Lib\Form\Form;
use App\Bundle\AdminBundle\Lib\Grid\Grid;
use App\Bundle\AdminBundle\Service\Teach\CategoryService;
use App\Bundle\AppBundle\Lib\Base\BaseAdminController;
use App\Bundle\QABundle\Service\Admin\QAChapterService;
use App\Bundle\QABundle\Service\Admin\QAChapterSubService;
use App\Bundle\QABundle\Service\Admin\QATestService;
use App\Bundle\QABundle\Service\Admin\QATestSubService;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\HttpFoundation\Request;

class TestSubController extends BaseAdminController
{

    /**
     * @Rest\Get("/test/sub/index/{id}", name="qa_admin_test_sub_index")
     */
    public function indexAction($id, Request $request, Grid $grid, QATestSubService $testSubService, QATestService $testService){
        $pageSize = 40;
        $grid->setListService($testSubService, "getList", $id);
        $grid->text("#")->field("id")->sort("a.id");
        $grid->text("试题类型")->field("type")->options([0=>"单项选择",1=>"多项选择",2=>"不定项选择题",3=>"判断题",4=>"填空题",5=>"问答题",6=>"理解题"]);
        $grid->text("试题id")->field("qaNodeId");
        $grid->textarea("试题题目")->field("topic");
        $grid->text("排序")->field("sort")->sort("a.sort");
        $grid->datetime("创建时间")->field("createdAt")->sort("a.createdAt");

        $grid->gbButton("试题管理")->route("qa_admin_test_sub_mg")
            ->url($this->generateUrl("qa_admin_test_sub_mg", ['type'=>0,"isFirst"=>1,'id'=>$id]))
            ->styleClass("btn-success")->iconClass("fas fa-edit");


        $grid->setTableAction('qa_admin_node_view', function ($obj) {
            $id = $obj["qaNodeId"];
            $url = $this->generateUrl('qa_admin_node_view', ['id' => $id]);
            $str = '<a href=' . $url . ' data-title="查看" title="查看" class=" btn btn-default btn-xs poppage"><i class="fas fa-eye"></i></a>';
            return  $str;
        });


        $grid->snumber("ID")->field("a.id");
        $grid->sselect("试题类型")->field("a.type")->options(["全部" => -1,"单项选择"=>0,"多项选择"=>1,"不定项选择题"=>2,"判断题"=>3,"填空题"=>4,"问答题"=>5,"理解题"=>6]);
        $grid->stext("试题id")->field("a.qaNodeId");
        $grid->sdaterange("创建时间")->field("a.createdAt");

        $testInfo = $testService->getById($id);
        $data = [];
        $data['testInfo'] = $testInfo;
        $data['list'] = $grid->create($request, $pageSize);
        return $this->render("@QABundleAdmin/test/subindex.html.twig", $data);
    }

    /**
     * @Rest\Get("/test/sub/mg/{id}/{type}/{isFirst}", name="qa_admin_test_sub_mg")
     */
    public function mgAction($id,$type=0,$isFirst=1, Request $request,QATestService $testService, QATestSubService $testSubService,  CategoryService $categoryService, QAChapterSubService $chapterSubService, QAChapterService $chapterService){


        $testNodeIds = $testSubService->getAllNodeIds($id);
        $testNodeIdsStr = $testNodeIds?implode(",", $testNodeIds):"";
        $testInfo = $testService->getById($id);

        //是否有做题,有做题的话，试题不能从试卷删除
        if($type == 0) {
            $page = $request->get("page", 1);
            $pageSize = 40;
            $nodeTypes = ["全部" => -1, "单项选择" => 0, "多项选择" => 1, "不定项选择题" => 2, "判断题" => 3, "填空题" => 4, "问答题" => 5, "理解题" => 6];
            $pathinfo = $request->getPathInfo();
            $select = $categoryService->categorySelect();

            $values = $request->get("values");
            $chapterId = ($values && isset($values["a.chapterId"])) ? $values["a.chapterId"] : 0;
            $chapterSubId = ($values && isset($values["a.chapterSubId"])) ? $values["a.chapterSubId"] : 0;

            $chapter = $chapterService->searchResultByid($chapterId);
            $chapterSub = $chapterSubService->searchResultByid($chapterSubId);

            $year = date("Y");
            $years = ["全部" => -1];
            $start = $year - 10;
            for ($i = $start; $i < ($year + 2); $i++) {
                $years[$i] = $i;
            }


            //获取试题列表
            list($pagination, $nodeList) = $testSubService->getNodeList($request, $page, $pageSize, $testInfo["categoryId"]);

            $data = [];
            $data['id'] = $id;
            $data["type"] = $type;
            $data['nodeTypes'] = $nodeTypes;
            $data['pathinfo'] = $pathinfo;
            $data['category'] = $select;
            $data['chapter'] = $chapter;
            $data['chapterSub'] = $chapterSub;
            $data['level'] = ["全部" => -1, "容易" => 0, "一般" => 1, "困难" => 2];
            $data['nodeType'] = ["全部" => -1, "常考题" => 0, "易错题" => 1, "好题" => 2, "压轴题" => 3];
            $data['years'] = $years;
            $data['pagination'] = $pagination;
            $data['nodeList'] = $nodeList;

        }else{
            $nodeIdStr = $request->get("nodeIds");
            $nodeIds = $nodeIdStr?explode(",", $nodeIdStr):[];
            $nodeList = $testSubService->getNodeByIds($nodeIds);
            $data = [];
            $data['id'] = $id;
            $data["type"] = $type;
            $data['list'] = $nodeList;
        }
        $data['testNodeIdsStr'] = $testNodeIdsStr;
        $data["isFirst"] = $isFirst;
        $data["testInfo"] = $testInfo;
        return $this->render("@QABundleAdmin/test/submg.html.twig", $data);
    }

    /**
     *
     * @Rest\Post("/test/sub/mg/do/{id}", name="qa_admin_test_sub_do_mg")
     */
    public function mgDoAction($id, Request $request, QATestSubService $testSubService){
        $nodeIds = $request->get("ids");
        $sorts = $request->get("sorts");
        $types = $request->get("types");
        if(!$nodeIds) $this->responseError("请先选择试题!");

        $testSubService->mgNode($id, $nodeIds, $types, $sorts);

        if($this->error()->has()){
            return $this->responseError($this->error()->getLast());
        }

        return $this->responseMsgRedirect("操作成功!", $this->generateUrl("qa_admin_test_sub_index",["id"=>$id]));
    }


    /**
     * @Rest\Get("/test/sub/edit/{id}", name="qa_admin_test_sub_edit")
     */
    public function editAction(Form $form){

    }

    /**
     *
     * @Rest\Post("/test/sub/edit/do/{id}", name="qa_admin_test_sub_do_edit")
     */
    public function editDoAction(Request $request){

    }

    /**
     *
     * @Rest\Post("/test/sub/delete/do/{id}", name="qa_admin_test_sub_delete")
     */
    public function deleteDoAction($id)
    {

    }


    /**
     *
     * @Rest\Post("/test/sub/bathdelete/do", name="qa_admin_test_sub_bathdelete")
     */
    public function bathdeleteDoAction(Request $request)
    {

    }

}
