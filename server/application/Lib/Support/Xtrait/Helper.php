<?php
/**
 * Created by PhpStorm.
 * User: wangkaihui
 * Date: 2017/4/13
 * Time: 15:45
 */

namespace Lib\Support\Xtrait;

trait Helper
{

    /**
     *
     * $this->fetchCached("id:{$id}", $id, 10*60, function ($id) use ($that) {
        $sql = "SELECT * FROM {$that->getTable()} WHERE id = ? LIMIT 1";
        return $that->getConnection()->fetchAssoc($sql, array($id)) ?: null;
        })
     *
     * @return mixed|null
     */
    public static function fetchCached()
    {
        $args     = func_get_args();
        $callback = array_pop($args);

        $key = array_shift($args);

        $redis = self::cache();

        if ($redis) {
            $data = $redis->get($key);
            if ($data !== null) {
                return $data;
            }
        }

        $expire = array_pop($args);

        $ret = call_user_func_array($callback, $args);

        if ($redis) {
            $redis->set($key,  $ret, $expire);
        }
        return $ret;
    }

    /**
     *
     * $this->fetchFileCached("id:{$id}", $id, 600, function ($id) use ($that) {
    $sql = "SELECT * FROM {$that->getTable()} WHERE id = ? LIMIT 1";
    return $that->getConnection()->fetchAssoc($sql, array($id)) ?: null;
    })
     *
     * @return mixed|null
     */
    public static function fetchFileCached()
    {
        $args     = func_get_args();
        $callback = array_pop($args);

        $key = array_shift($args);

        $cacheObj = self::fileCache();

        if ($cacheObj) {
            $data = $cacheObj->get($key);
            if ($data !== null) {
                return $data;
            }
        }

        $expire = array_pop($args);
        $ret = call_user_func_array($callback, $args);

        if ($cacheObj) {
            $cacheObj->set($key,  $ret, $expire);
        }
        return $ret;
    }

    /**
     * 字符串截取
     *
     * @param $str
     * @param $str1
     * @param $str2
     * @return string
     */
    public static function getBetween($str, $str1, $str2)
    {
        $kw=$str;
        $st =mb_strpos($kw,$str1,null,'utf-8');
        $ed =mb_strpos($kw,$str2,null,'utf-8');
        if(($st==false||$ed==false)||$st>=$ed) return '';

        $len1 = mb_strlen($str1, 'utf-8');

        $kw= mb_substr($kw,$st+$len1,$ed-($st+$len1), 'utf-8');
        return $kw;
    }

    public static function getFileSizeText($size)
    {
        if($size < 0x400) // Bytes - 1024
            return $size.' Bytes';
        else if($size < 0x100000) // KB - 1024
            return (round($size/0x400*100)/100).'KB';
        else if($size < 0x40000000) // MB - 1024 * 1024
            return (round($size/0x100000*100)/100).' MB';
        else // GB - 1024 * 1024 * 1024
            return (round($size/0x40000000*100)/100).' GB';
    }

    public static function getImgPath($content){
        //取出图片路径
        $content = str_replace("alt=\"\"","",$content);
        $content = str_replace("alt=\'\'","",$content);
        $content = str_replace("alt=","",$content);
        $content = str_replace("alt","",$content);
        preg_match_all("/<img.*?src\s*=\s*.*?([^\"'>]+\.(gif|jpg|jpeg|bmp|png))/i",$content,$match);
        $result= isset($match[1]) ? $match[1] : array();
        if($result) return $result;
        preg_match_all("/<img.*?src=[\"|\'|\s]?(.*?)[\"|\'|\s]/i",$content,$match1);
        return isset($match1[1]) ? $match1[1]:array();
    }

    /**
     * 获取随机字符串
     * @static
     * @param int $len 随机字符串长度
     * @param bool $bNumber 是否包括数字，默认 true
     * @param bool $bLower  是否包括小写字母，默认 false
     * @param bool $bUpper  是否包括大写字母，默认 false
     * @return string 返回随机字符串
     */
    public static function getRandomString($len=6, $bNumber=true, $bLower=false, $bUpper=false){
        $tmp = '';

        if($bNumber)
            $tmp .= '0123456789';
        if($bLower)
            $tmp .= 'abcdefghijklmnopqrstuvwxyz';
        if($bUpper)
            $tmp .= 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';

        $str = '';
        $min = 0;
        $max = strlen($tmp) - 1;
        for($i=0; $i<$len; $i++)
        {
            $str .= substr($tmp, rand($min, $max), 1);
        }

        return $str;
    }


    /**
     * php 切割html字符串 自动配完整标签
     *
     * @param $s 字符串
     * @param $zi 长度
     * @param $ne 没有结束符的html标签
     */
    public static function htmlCut($s,$zi,$ne=',br,hr,input,img,'){
        $s = stripslashes($s);
        $s=preg_replace('/\s{2,}/',' ',$s);
        $os=preg_split('/<[\S\s]+?>/',$s);
        preg_match_all('/<[\S\s]+?>/',$s,$or);
        if(!$or[0]) return mb_substr($s, 0, $zi, "UTF-8");
        $s='';
        $tag=array();
        $n=0;
        $m = count($or[0])-1;
        foreach($os as $k => $v){
            $n = $k>$m ? $m :$k;
            if($v!='' && $v!=' '){
                $l=strlen($v);
                for($i=0;$i<$l;$i++){
                    if(ord($v[$i]) > 127){
                        $s.=$v[$i].$v[++$i].$v[++$i];
                    }else{
                        $s.=$v[$i];
                    }
                    $zi--;
                    if($zi < 1){
                        break 2;
                    }
                }
            }

            preg_match('/<\/?([^\s>]+)[\s>]{1}/',$or[0][$n],$t);
            $s.=$or[0][$n];
            if(strpos($ne,','.strtolower($t[1]).',')===false && $t[1]!='' && $t[1]!=' '){
                $n=array_search('</'.$t[1].'>',$tag);
                if($n!==false){
                    unset($tag[$n]);
                }else{
                    array_unshift($tag,'</'.$t[1].'>');
                }
            }
        }
        return $s.implode('',$tag);
    }

    public static function safeEncoding($string,$outEncoding = 'UTF-8'){
        $encoding = "UTF-8";
        for($i=0;$i<strlen($string);$i++)
        {
            if(ord($string{$i})<128)
                continue;

            if((ord($string{$i})&224)==224)
            {
                //第一个字节判断通过
                $char = $string{++$i};
                if((ord($char)&128)==128)
                {
                    //第二个字节判断通过
                    $char = $string{++$i};
                    if((ord($char)&128)==128)
                    {
                        $encoding = "UTF-8";
                        break;
                    }
                }
            }
            if((ord($string{$i})&192)==192)
            {
                //第一个字节判断通过
                $char = $string{++$i};
                if((ord($char)&128)==128)
                {
                    //第二个字节判断通过
                    $encoding = "GBK";
                    break;
                }
            }
        }
        if(strtoupper($encoding) == strtoupper($outEncoding))
            return $string;
        else
            return iconv($encoding,$outEncoding."//ignore",$string);
    }

    public static function isSerialized( $data ) {
        if(!is_string($data))
            return false;
        $data = trim($data);
        if ('N;' == $data)
            return true;
        if (!preg_match('/^([adObis]):/', $data, $badions))
            return false;
        switch ($badions[1]) {
            case 'a' :
            case 'O' :
            case 's' :
                if (preg_match( "/^{$badions[1]}:[0-9]+:.*[;}]\$/s", $data ))
                    return true;
                break;
            case 'b' :
            case 'i' :
            case 'd' :
                if (preg_match( "/^{$badions[1]}:[0-9.E-]+;\$/", $data ))
                    return true;
                break;
        }
        return false;
    }

     /**
     *
     * 加密解密
     * @param string $string
     * @param string $operation
     * @param string $key
     * @param string $expiry
     */
   public static function authcode($string,  $key = '', $operation = 'DECODE', $expiry = 0) {
        $ckey_length = 4;
        if($key == "") return false;
        $key = md5($key ? $key : "ad^%FFGFFFF");
        $keya = md5(substr($key, 0, 16));
        $keyb = md5(substr($key, 16, 16));
        $keyc = $ckey_length ? ($operation == 'DECODE' ? substr($string, 0, $ckey_length): substr(md5(microtime()), -$ckey_length)) : '';

        $cryptkey = $keya.md5($keya.$keyc);
        $key_length = strlen($cryptkey);

        $string = $operation == 'DECODE' ? base64_decode(substr($string, $ckey_length)) : sprintf('%010d', $expiry ? $expiry + time() : 0).substr(md5($string.$keyb), 0, 16).$string;
        $string_length = strlen($string);

        $result = '';
        $box = range(0, 255);

        $rndkey = array();
        for($i = 0; $i <= 255; $i++) {
            $rndkey[$i] = ord($cryptkey[$i % $key_length]);
        }

        for($j = $i = 0; $i < 256; $i++) {
            $j = ($j + $box[$i] + $rndkey[$i]) % 256;
            $tmp = $box[$i];
            $box[$i] = $box[$j];
            $box[$j] = $tmp;
        }

        for($a = $j = $i = 0; $i < $string_length; $i++) {
            $a = ($a + 1) % 256;
            $j = ($j + $box[$a]) % 256;
            $tmp = $box[$a];
            $box[$a] = $box[$j];
            $box[$j] = $tmp;
            $result .= chr(ord($string[$i]) ^ ($box[($box[$a] + $box[$j]) % 256]));
        }

        if($operation == 'DECODE') {
            if((substr($result, 0, 10) == 0 || substr($result, 0, 10) - time() > 0) && substr($result, 10, 16) == substr(md5(substr($result, 26).$keyb), 0, 16)) {
                return substr($result, 26);
            } else {
                return '';
            }
        } else {
            return $keyc.str_replace('=', '', base64_encode($result));
        }
    }

    #复制目录
   public static function xCopy($source, $destination, $child = 1){
        if (!is_dir($source)) {
            return false;
        }
        if (!is_dir($destination)) {
            mkdir($destination, 0777, true );
        }

        $handle = dir ($source);
        while ( $entry = $handle->read() ) {
            if (($entry != ".") && ($entry != "..")) {
                if (is_dir ( $source . "/" . $entry )) {
                    if ($child)
                        self::xCopy( $source . "/" . $entry, $destination . "/" . $entry, $child);
                } else {
                    copy($source . "/" . $entry, $destination . "/" . $entry );
                }
            }
        }
        return true;
    }


    #目录下文件正则批量替换
    public static function batchReplace($sourcePath, $reg, $replaceTo, $ext="tpl,php,ini,phtml") {
        if (!is_dir($sourcePath)) {
            return false;
        }

        $handle = dir ($sourcePath);
        while ( $entry = $handle->read() ) {
            if (($entry != ".") && ($entry != "..")) {
                $tmpPath = $sourcePath . "/" . $entry;
                if (is_dir ($tmpPath)) {
                    self::batchReplace($tmpPath, $reg, $replaceTo, $ext);
                } else {
                    //开始替换
                    $pathinfo = pathinfo($tmpPath);
                    $extArr = explode(",", $ext);
                    if(isset($pathinfo['extension']) && in_array($pathinfo['extension'], $extArr)){
                        $tmpData = file_get_contents($tmpPath);
                        $tmpData = str_replace($reg, $replaceTo, $tmpData);
                        file_put_contents($tmpPath, $tmpData);
                    }
                }
            }
        }
        return true;
    }

    #目录下文件夹，文件名正则批量替换
    public static function batchDirNameReplace($sourcePath, $reg, $replaceTo) {
        if (!is_dir($sourcePath)) {
            return false;
        }
        $handle = dir ($sourcePath);
        while ( $entry = $handle->read() ) {
            if (($entry != ".") && ($entry != "..")) {
                $tmpPath = $sourcePath . "/" . $entry;
                $newEntry = preg_replace($reg, $replaceTo, $entry);
                $newPath = $sourcePath . "/" . $newEntry;
                if($newPath != $tmpPath) rename($tmpPath, $newPath);
                if (is_dir ($newPath)) {
                    self::batchDirNameReplace($newPath, $reg, $replaceTo);
                }
            }
        }
        return true;
    }

    //过滤xss
    public static function removeXss($val) {
        $val = stripslashes($val);
        $result = self::dealWithXss($val);
        return $result;
    }

    /**
     * 处理xss
     * @param unknown $html
     * @param unknown $allow_tag
     * @param unknown $allow_tag_attr
     * @return unknown
     */
    public static function dealWithXss($html,$allow_tag=array(),$allow_tag_attr=array()){
        if(!$allow_tag){
            $allowStr = "p,strong,a,em,table,td,tr,h1,h2,h3,h4,h5,hr,br,u,ul,ol,li,center,code,div,font,blockquote,small,caption,img,span,strike,sup,sub,b,dl,dt,dd,embed,object,param,pre,tbody";
            $allow_tag = explode(',',$allowStr);
        }
        if(!$allow_tag_attr){
            $allow_tag_attr = array(
                '*' => array (
                    'style'=>'/.*/i',
                    'alt'=>'/.*/i',
                    'width'=>'/^[\w_-]+$/i',
                    'height'=>'/^[\w_-]+$/i',
                    'class'=>'/.*/i',
                    'name'=>'/^.*$/i',
                    'value'=>'/.*/i',
                ),
                "object"=>array("data"=>'/.*/i',
                ),
                "embed"=>array(
                    "type"=>'/.*/i',
                    'src'=>'/.*/i',
                ),
                "font"=>array(
                    "color"=>'/^[\w_-]+$/i',
                    "size"=>'/^[\w_-]+$/i',
                ),
                'a'=>array(
                    'href'=>'/.*/i',
                    'title'=>'/.*/i',
                    'target'=>'/^[\w_-]+$/i',
                ),
                'img' => array (
                    'src'=>'/.*/i',
                ),
            );
        }
        //匹配出所有尖括号包含的字符串
        preg_match_all('/<[^>]*>/s',$html,$matches);

        if($matches[0]){
            $tags = $matches[0];
            $search = [];
            $replace = [];
            foreach($tags as $tag_k=>$tag){

                //匹配出标签名 比如 a, br, html, li, script
                preg_match_all('/^<\s{0,}\/{0,}\s{0,}([\w]+)/i',$tag,$tag_name);
                if(!isset($tag_name[1][0]))  continue;
                $tags[$tag_k] = array('name'=>$tag_name[1][0],'html'=>$tag);
                if($tag_name && in_array($tags[$tag_k]['name'],$allow_tag)){

                    //匹配出含等于号的属性，注，当前版本不支持readonly等无等于号的属性
                    preg_match_all('/\s{0,}([a-z]+)\s{0,}=\s{0,}["\']{0,}([^\'"]+)["\']{0,}[^>]/i',$tag,$tag_matches);
                    if($tag_matches[0]){
                        $tags[$tag_k]['attr'] = $tag_matches;
                        foreach($tags[$tag_k]['attr'][1] as $k => $v){
                            $attr = $tags[$tag_k]['attr'][1][$k];
                            $value = $tags[$tag_k]['attr'][2][$k];
                            $preg_attr_all = isset($allow_tag_attr['*'][$attr]) ? $allow_tag_attr['*'][$attr] : "";
                            $preg_attr = isset($allow_tag_attr[$tags[$tag_k]['name']][$attr]) ? $allow_tag_attr[$tags[$tag_k]['name']][$attr]:"";

                            //判断该属性是否允许，如不允许，则unset。
                            if(($preg_attr && preg_match($preg_attr,$value)) || ($preg_attr_all && preg_match($preg_attr_all,$value))){
                                $tags[$tag_k]['attr'][0][$k] = "{$attr}='{$value}'";
                            }else{
                                unset($tags[$tag_k]['attr'][0][$k]);
                            }
                        }
                        $tags[$tag_k]['replace'] = '<'.$tags[$tag_k]['name'];
                        if(is_array($tags[$tag_k]['attr'][0])) $tags[$tag_k]['replace'] .= ' '.implode(' ',$tags[$tag_k]['attr'][0]);
                        $tags[$tag_k]['replace'] .= '>';
                    }else{
                        $tags[$tag_k]['replace'] = $tags[$tag_k]['html'];
                    }
                }else{
                    $tags[$tag_k]['replace'] = htmlentities($tags[$tag_k]['html']);
                }
                $search[$tag_k] = $tags[$tag_k]['html'];
                $replace[$tag_k] = $tags[$tag_k]['replace'];
            }
            $html = str_replace($search,$replace,$html);
        }
        return $html;
    }

    public static function array_format($arr,$fields){
        if(!$arr || !$fields) return array();
        $fieldsTmp = array_flip($fields);
        $arrTmp = array_intersect_key($arr,$fieldsTmp);
        return $arrTmp;
    }

    /**
     * 字符串中间打星号
     *
     * @param $str
     * @param int $left_length  左边保留长度
     * @param int $right_length 右边保留长度
     * @return string
     */
    public static function marskName($str,$left_length=1,$right_length=1){
        $s = mb_strlen($str,'utf-8');
        if($s< $left_length + $right_length) {
            return $str;
        }
        $left = mb_substr($str, 0, $left_length, 'utf-8');
        if($s == $left_length + $right_length) {
            return $left.str_repeat('*', $right_length);
        }
        $right = mb_substr($str, $s - $right_length, $right_length, 'utf-8');
        return $left.str_repeat('*', $s - $left_length - $right_length).$right;
    }

    //投票算法分数
    public static function getVoteScore($vote,$devote){
        $voteDiff = $vote - $devote;
        if($voteDiff > 0) {
            $pos = 1;
        } elseif($voteDiff < 0) {
            $pos = -1;
        } else {
            $pos = 0;
        }
        $voteDispute = $voteDiff != 0 ? abs($voteDiff) : 1;
        $fund = strtotime('2012-12-12');
        $created = strtotime('-3 days');
        $time = $created - $fund;
        $socre = log10($voteDispute) + $pos * $time / 45000;
        return $socre;
    }

    public  static function parseHost($httpurl){
        $host = strtolower ($httpurl);
        if (strpos ( $host, '/' ) !== false) {
            $parse = @parse_url ( $host );
            $host = $parse ['host'];
        }
        $topleveldomaindb = array (
            'com', 'edu', 'gov', 'net', 'org', 'biz', 'info',
            'io', 'name','mobi', 'cc', 'me' ,'test', 'cn');
        $str = '';
        foreach ( $topleveldomaindb as $v ) {
            $str .= ($str ? '|' : '') . $v;
        }

        $matchstr = "[^\.]+\.(?:(" . $str . ")|\w{2}|((" . $str . ")\.\w{2}))$";
        if (preg_match ( "/" . $matchstr . "/ies", $host, $matchs )) {
            $domain = ".".$matchs ['0'];
        } else {
            $domain = $host;
        }
        return $domain;
    }


}
