<?php
/**
 * @Author: kaihui.wang
 * @Contact  hpuwang@gmail.com
 * @Version: 1.0.0
 * @Date: 2020/4/19 19:53
 */

namespace App\Bundle\AdminBundle\Controller\Teach;


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
    public function indexAction($id){

        $data = [];
        $data['id'] = $id;
        return $this->render("@AdminBundle/teach/chapter/index.html.twig", $data);
    }

    /**
     * @Rest\Get("/teach/chapter/add/{id}", name="admin_teach_chapter_add")
     */
    public function addAction($id, Form $form)
    {

    }

    /**
     * @Rest\Post("/api/teach/chapter/addDo/{id}", name="admin_api_teach_chapter_add")
     */
    public function addDoAction($id, Request $request){

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
     * @Rest\Post("/api/teach/chapter/updateSortDo/{id}", name="admin_api_teach_chapter_updateSortDo")
     */
    public function searchUserDoAction($id){
        $data = [];

        return $data;
    }




}
