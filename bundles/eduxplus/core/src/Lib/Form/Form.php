<?php

/**
 * @Author: kaihui.wang
 * @Contact  hpuwang@gmail.com
 * @Version: 1.0.0
 * @Date: 2020/4/7 11:17
 */

namespace Eduxplus\CoreBundle\Lib\Form;

use Twig\Environment;

class Form
{

    protected $url = null;
    protected $formField = [];
    protected $disableSubmit = 0;
    private $twig;
    /**
     * @var Bind
     */
    private $bind = null;


    public function __construct(Environment $twig)
    {
        $this->twig = $twig;
    }

    public function disableSubmit()
    {
        $this->disableSubmit = 1;
    }

    public function create($url, $tpl = 'default')
    {
        $this->bindData2form();
        $data = [];
        $data['formField'] = $this->formField;
        $data['url'] =  $url;
        $data['disableSubmit'] = $this->disableSubmit;
        $result = $this->twig->render("@Form/" . $tpl . ".html.twig", $data);

        return $result;
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
    protected function setFormField($title, $type, $field, $isRequire = 0, $defaultValue = '', $initData = null, $placeholder = '', $options = [])
    {
        if ($isRequire) $options['data-required'] = 1;
        if ($placeholder) $options['placeholder'] = $placeholder;
        if ($field) {
            $options['id'] = $field;
            $options['name'] = $field;
        }

        return $this->setFormAdvanceField($title, $type, $field, $options, $defaultValue, $initData);
    }

    protected function setFormAdvanceField($title, $type, $field, $attributes = [], $defaultValue = "", $options = null)
    {

        if ($field) {
            $attributes['id'] = $field;
            $attributes['name'] = $field;
        }

        $this->bindData2form();

        $this->formField[$title] = [
            "type" => $type,
            "field" => $field,
            "options" => $options,
            "attributes" => $attributes,
            "defaultValue" => $defaultValue,
        ];
        return $this;
    }

    protected function bindData2form()
    {
        if ($this->bind) {
            $bindFormField = $this->bind->getFormField();
            if ($bindFormField) {
                $this->formField = $this->formField ? array_merge($this->formField, $bindFormField) : $bindFormField;
            }
            $this->bind = null;
        }
    }

    protected  function bind($title, $type)
    {
        $this->bindData2form();
        $this->bind =  new Bind();
        $this->bind->setType($type, $title);
        return $this->bind;
    }

    /**
     * input 文本
     *
     * @param $title
     * @return Bind
     */
    public function text($title)
    {
        return $this->bind($title, "text");
    }


    /**
     * 初始值展示
     * @param $title
     * @return Bind
     */
    public  function string($title)
    {
        return $this->bind($title, "string");
    }


    /**
     * 日期范围，不含时分秒
     * @param $title
     * @return Bind
     */
    public  function daterange($title)
    {
        return $this->bind($title, "daterange");
    }

    /**
     * 时间范围，含时分秒
     * @param $title
     * @return Bind
     */
    public  function datetimerange($title)
    {
        return $this->bind($title, "datetimerange");
    }

    /**
     * 时间
     * @param $title
     * @return Bind
     */
    public  function datetime($title)
    {
        return $this->bind($title, "datetime");
    }

    /**
     * 日期
     * @param $title
     * @return Bind
     */
    public  function date($title)
    {
        return $this->bind($title, "date");
    }

    /**
     * 文件上传
     * @param $title
     * @return Bind
     */
    public  function file($title)
    {
        return $this->bind($title, "file");
    }

    /**
     * 密码文本
     *
     * @param $title
     * @return Bind
     */
    public  function password($title)
    {
        return $this->bind($title, "password");
    }

    /**
     * 大文本
     *
     * @param $title
     * @return Bind
     */
    public  function textarea($title)
    {
        return $this->bind($title, "textarea");
    }

    /**
     * 布尔选择
     *
     * @param $title
     * @return Bind
     */
    public  function boole($title)
    {
        return $this->bind($title, "boole");
    }

    /**
     * 单选
     * @param $title
     * @return Bind
     */
    public  function select($title)
    {
        return $this->bind($title, "select");
    }

    /**
     * 多选
     * @param $title
     * @return Bind
     */
    public  function multiSelect($title)
    {
        return $this->bind($title, "multiSelect");
    }

    public  function searchMultipleSelect($title)
    {
        return $this->bind($title, "search_multiple_select");
    }

    /**
     * 搜索+单选
     * @param $title
     * @return Bind
     */
    public  function searchSelect($title)
    {
        return $this->bind($title, "search_select");
    }

    /**
     * 隐藏域
     * @param $title
     * @return Bind
     */
    public  function hidden($title)
    {
        return $this->bind($title, "hidden");
    }

    /**
     * 富文本
     *
     * @param $title
     * @return Bind
     */
    public  function richEditor($title)
    {
        return $this->bind($title, "rich_editor");
    }

    public  function notice($title)
    {
        return $this->bind("", "notice")->defaultValue($title);
    }
}
