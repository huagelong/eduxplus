<?php
/**
 * @Author: kaihui.wang
 * @Contact  hpuwang@gmail.com
 * @Version: 1.0.0
 * @Date: 2020/5/26 09:57
 */

namespace App\Bundle\AppBundle\Service;


use App\Bundle\AppBundle\Lib\Base\AppBaseService;

class TeacherService extends AppBaseService
{

    public function getTeachers($teacherIds){
        $sql = "SELECT a FROM App:JwTeacher a WHERE a.id IN (:id)";
        $result = $this->fetchAll($sql, ["id"=>$teacherIds]);
        return $result;
    }

}
