<?php
/**
 * @Author: kaihui.wang
 * @Contact  hpuwang@gmail.com
 * @Version: 1.0.0
 * @Date: 2020/4/19 19:53
 */

namespace App\Bundle\AdminBundle\Controller\Teach;


use App\Bundle\AdminBundle\Service\Teach\ChapterService;
use App\Bundle\AppBundle\Lib\Base\BaseAdminController;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\HttpFoundation\Request;
use App\Bundle\AdminBundle\Lib\Form\Form;
use App\Bundle\AdminBundle\Lib\Grid\Grid;

class ChapterController extends BaseAdminController
{

    /**
     * @Rest\Get("/teach/chapter/index/{id}", name="admin_teach_chapter_index")
     */
    public function indexAction($id, ChapterService $chapterService){

        $data = [];
        $data['all'] = $chapterService->getChapterTree(0);
        $data['id'] = $id;
        return $this->render("@AdminBundle/teach/chapter/index.html.twig", $data);
    }

    /**
     * @Rest\Get("/teach/chapter/add/{id}", name="admin_teach_chapter_add")
     */
    public function addAction($id, Form $form, Request $request, ChapterService $chapterService)
    {
        $select = $chapterService->chapterSelect();
        $parentId = $request->get("pid");

        $form->setFormField("名称", 'text', 'name' ,1);
        $form->setFormField("父章节", 'select', 'parentId' ,1,  $parentId, function()use($select){
            return $select;
        });
        $form->setFormField("上课时间", 'datetime', 'openTime' ,1);
        $form->setFormField("学习方式", "select", "studyWay", 1,"", function(){
            return ["线上"=>1, "线下"=>2, "混合"=>3];
        });
        $form->setFormField("是否免费", 'boole', 'isFree', 1);

        $form->setFormField("排序", 'text', 'sort' ,1,  0);

        $formData = $form->create($this->generateUrl("admin_api_teach_chapter_add", ['id'=>$id]));
        $data = [];
        $data["formData"] = $formData;
        $data['id'] = $id;
        return $this->render("@AdminBundle/teach/chapter/add.html.twig", $data);
    }

    /**
     * @Rest\Post("/api/teach/chapter/addDo/{id}", name="admin_api_teach_chapter_add")
     */
    public function addDoAction($id, Request $request){
        $name = $request->get("name");
        $parentId = $request->get("parentId");
        $openTime = $request->get("openTime");
        $studyWay = $request->get("studyWay");

    }

    /**
     * @Rest\Get("/teach/chapter/edit/{id}", name="admin_teach_chapter_edit")
     */
    public function editAction($id, Form $form)
    {

    }

    /**
     * @Rest\Post("/api/teach/chapter/editDo/{id}", name="admin_api_teach_chapter_edit")
     */
    public function editDoAction($id, Request $request){

    }

    /**
     * @Rest\Post("/api/teach/chapter/deleteDo/{id}", name="admin_api_teach_chapter_delete")
     */
    public function deleteDoAction($id){

    }

        /**
     * @Rest\Post("/api/teach/chapter/updateSort/{id}", name="admin_api_teach_chapter_updateSort")
     */
    public function searchUserDoAction($id){
        $data = [];

        return $data;
    }




}
