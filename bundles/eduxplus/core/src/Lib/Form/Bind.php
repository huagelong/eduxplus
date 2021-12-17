<?php

/**
 * @Author: kaihui.wang
 * @Contact  hpuwang@gmail.com
 * @Version: 1.0.0
 * @Date: 2020/5/21 14:49
 */

namespace Eduxplus\CoreBundle\Lib\Form;


class Bind
{
    private $tableTmp = [
        "type" => "",
        "field" => "",
        "attributes" => [],
        "options" => [],
        "defaultValue" => "",
    ];
    private $titleTmp = null;

    public function getFormField()
    {
        $new = [$this->titleTmp => $this->tableTmp];
        return $new;
    }

    public function setType($type, $title)
    {

        $this->titleTmp = $title;
        $this->tableTmp['type'] = $type;
        $this->tableTmp["attributes"] = ["data-required" => 0];
        return $this;
    }

    public function field($field)
    {
        $this->tableTmp["field"] = $field;
        $attributes = (array) $this->tableTmp["attributes"];
        if ($field) {
            $attributes['id'] = $field;
            $attributes['name'] = $field;
        }
        $this->tableTmp["attributes"] = $attributes;
        return $this;
    }

    /**
     * 默认值,一般编辑操作时用
     * @param $defaultValue
     * @return $this
     */
    public function defaultValue($defaultValue)
    {
        $this->tableTmp["defaultValue"] = $defaultValue;
        return $this;
    }

    /**
     * 可选择的数据集
     * @param $initData
     * @return $this
     */
    public function options($initData)
    {
        $this->tableTmp["options"] = $initData;
        return $this;
    }

    /**
     * 表单控件属性
     *
     * @param $newAttributes
     * @return $this
     */
    public function attr($newAttributes)
    {
        $attributes = (array) $this->tableTmp["attributes"];
        $attributes = array_merge($attributes, $newAttributes);
        $this->tableTmp["attributes"] = $attributes;
        return $this;
    }

    /**
     * 是否必填
     * @param int $isRequire
     * @return $this
     */
    public function isRequire($isRequire = 1)
    {
        $attributes = (array) $this->tableTmp["attributes"];
        $attributes["data-required"] = $isRequire;
        $this->tableTmp["attributes"] = $attributes;
        return $this;
    }

    /**
     * @param $placeholder
     * @return $this
     */
    public function placeholder($placeholder)
    {
        $attributes = (array) $this->tableTmp["attributes"];
        $attributes["placeholder"] = $placeholder;
        $this->tableTmp["attributes"] = $attributes;
        return $this;
    }
}
