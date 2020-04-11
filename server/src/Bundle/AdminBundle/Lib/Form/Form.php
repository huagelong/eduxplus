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
    protected $disableSubmit = 0;

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
    public function setFormField($title, $type, $field, $isRequire=0, $defaultValue='', $initData=null, $placeholder='', $options=[]){
        $options = [];
        if($isRequire) $options['data-required']=1;
        if($placeholder) $options['placeholder']= $placeholder;
        if($field){
            $options['id']= $field;
            $options['name']= $field;
        }

        return $this->setFormAdvanceField($title, $type, $field, $options, $defaultValue, $initData);
    }

    public function setFormAdvanceField($title, $type, $field, $options=[], $defaultValue="",$initData=null){

        if($field){
            $options['id']= $field;
            $options['name']= $field;
        }
        $this->formField[$title] = [
            "type"=>$type,
            "field"=>$field,
            "initData"=>$initData,
            "options"=>$options,
            "defaultValue"=>$defaultValue,
        ];
        return $this;
    }

    public function disableSubmit(){
        $this->disableSubmit = 1;
    }

    public function create($url, $tpl='default')
    {
        $data = [];
        $data['formField'] = $this->formField;
        $data['url'] =  $url;
        $data['disableSubmit'] = $this->disableSubmit;

//        dump($this->formField);
        $result = $this->twig->render("@Form/".$tpl.".html.twig", $data);

        return $result;
    }

}
