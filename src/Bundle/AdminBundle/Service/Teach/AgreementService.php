<?php

/**
 * @Author: kaihui.wang
 * @Contact  hpuwang@gmail.com
 * @Version: 1.0.0
 * @Date: 2020/4/12 16:06
 */

namespace App\Bundle\AdminBundle\Service\Teach;


use App\Bundle\AppBundle\Lib\Base\AdminBaseService;
use App\Bundle\AppBundle\Lib\Base\BaseService;
use App\Entity\TeachAgreement;
use Knp\Component\Pager\PaginatorInterface;

class AgreementService extends AdminBaseService
{
    protected $paginator;

    public function __construct(PaginatorInterface $paginator)
    {
        $this->paginator = $paginator;
    }

    public function agreementList($request, $page, $pageSize)
    {
        $sql = $this->getFormatRequestSql($request);
        $dql = "SELECT a FROM App:TeachAgreement a " . $sql . " ORDER BY a.id DESC";
        $em = $this->getDoctrine()->getManager();
        $query = $em->createQuery($dql);
        $pagination = $this->paginator->paginate(
            $query,
            $page,
            $pageSize
        );
        return $pagination;
    }

    public function getAll()
    {
        $dql = "SELECT a FROM App:TeachAgreement a WHERE a.isShow=1";
        return $this->fetchAll($dql);
    }

    public function add($name, $content, $isShow)
    {
        $model = new TeachAgreement();
        $model->setName($name);
        $model->setIsShow($isShow);
        $model->setContent($content);
        return $this->save($model);
    }

    public function getById($id)
    {
        $sql = "SELECT a FROM App:TeachAgreement a WHERE a.id=:id";
        return $this->fetchOne($sql, ['id' => $id]);
    }

    public function getByName($name, $id = 0)
    {
        $sql = "SELECT a FROM App:TeachAgreement a WHERE a.name=:name";
        $params = [];
        $params['name'] = $name;
        if ($id) {
            $sql = $sql . " AND a.id !=:id ";
            $params['id'] = $id;
        }
        return $this->fetchOne($sql, $params);
    }

    public function edit($id, $name, $content, $isShow)
    {
        $sql = "SELECT a FROM App:TeachAgreement a WHERE a.id=:id";
        $model = $this->fetchOne($sql, ['id' => $id], 1);
        $model->setName($name);
        $model->setIsShow($isShow);
        $model->setContent($content);
        return $this->save($model);
    }

    public function del($id)
    {
        $sql = "SELECT a FROM App:TeachAgreement a WHERE a.id=:id";
        $model = $this->fetchOne($sql, ['id' => $id], 1);
        return $this->delete($model);
    }
}
