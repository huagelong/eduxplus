<?php

/**
 * @Author: kaihui.wang
 * @Contact  hpuwang@gmail.com
 * @Version: 1.0.0
 * @Date: 2020/5/21 14:49
 */

namespace App\Bundle\AdminBundle\Lib\View;


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
     * @param $optionData
     * @return $this
     */
    public function options($optionData)
    {
        $this->tableTmp["options"] = $optionData;
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
}
