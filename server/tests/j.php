<?php
/**
 * @Author: kaihui.wang
 * @Contact  hpuwang@gmail.com
 * @Version: 1.0.0
 * @Date: 2020/4/11 22:01
 */

$str = '["/assets/fav.png"]';
$arr = ["/assets/fav.png"];
var_dump(json_encode($arr));
var_dump(json_decode($str, 1));
