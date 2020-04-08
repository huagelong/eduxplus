<?php
/**
 * @Author: kaihui.wang
 * @Contact  hpuwang@gmail.com
 * @Version: 1.0.0
 * @Date: 2020/4/7 11:17
 */

namespace App\Bundle\AdminBundle\Lib\Form;

use Twig\Environment;

class Form
{

    protected $url=null;
    protected $formService=null;
    protected $formField=null;

    private $twig;

    public function __construct(Environment $twig)
    {
        $this->twig = $twig;
    }

    /**
     * @param $title
     * @param $type text,textarea,boole
     * @param $datakey
     * @param int $isRequire
     * @param null $initData
     * @param string $defaultValue
     * @param string $placeholder
     * @return $this
     */
    public function setFormField($title, $type, $datakey, $isRequire=0, $defaultValue='', $initData=null, $placeholder=''){
        $this->formField[$title] = [
            "type"=>$type,
            "datakey"=>$datakey,
            "isRequire"=>$isRequire,
            "defaultValue"=>$defaultValue,
            "placeholder"=>$placeholder,
            "initData"=>$initData
        ];
        return $this;
    }


    public function create($url, $tpl='default')
    {
        $data = [];
        $data['formField'] = $this->formField;
        $data['url'] =  $url;

        $result = $this->twig->render("@Form/".$tpl.".html.twig", $data);

        return $result;
    }

}
