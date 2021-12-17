<?php
/**
 * @Author: kaihui.wang
 * @Contact  hpuwang@gmail.com
 * @Version: 1.0.0
 * @Date: 2020/5/21 17:11
 */

namespace Eduxplus\CoreBundle\Lib\Grid;


class SearchBind
{

    protected $struct=["type"=>"", "field"=>"", "values"=>[], "operates"=>[], "types"=>[], "option"=>[]];
    private $title=null;

    public function getBindData(){
        $new = [$this->title=>$this->struct];
        return $new;
    }

    public function setType($type, $title){
        $this->title = $title;
        $this->struct['type']=$type;
        return $this;
    }

    public function field($value){
        $operateArr = "operates[{$value}]";
        $typeArr = "types[{$value}]";
        $valueArr = "values[{$value}]";
        $this->struct["field"] = $value;
        $this->struct["values"] = $valueArr;
        $this->struct["operates"] = $operateArr;
        $this->struct["types"] = $typeArr;
        return $this;
    }

    /**
     * 供选择的数据
     *
     * @param $value
     * @return $this
     */
    public function options($value){
        $this->struct["option"] = $value;
        return $this;
    }


}
