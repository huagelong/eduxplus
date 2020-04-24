<?php
/**
 * @Author: kaihui.wang
 * @Contact  hpuwang@gmail.com
 * @Version: 1.0.0
 * @Date: 2020/4/22 17:06
 */

namespace App\Bundle\AppBundle\Lib\Service\Vod;


use App\Bundle\AppBundle\Lib\Base\BaseService;

class BokeccService extends BaseService
{

    public function searchVideo($kw,$cateGoryId, $page=1, $pageSize=20){
        /**
         * userid	用户id，不可为空
        q	查询条件，不可为空
        格式：查询字段:查询内容
        查询字段：目前只支持TITLE
        查询内容：查询关键字
        注：格式中的”:”为英文半角
        Example：q=TITLE:test
        sort	查询结果排序，不可为空
        格式：排序字段:排序方式
        排序字段：CREATION_DATE或FILE_SIZE
        排序方式：ASC或DESC
        注：格式中的”:”为英文半角
        Example：sort=CREATION_DATE:DESC
        categoryid	视频子分类 id，如果不查询指定分类下的视频，请勿加入此参数
        num_per_page	返回信息时，每页包含的视频个数.注:阈值为 1~100
        page	当前页码
         */

        $params = [];
        if($cateGoryId) $params['categoryid'] = $cateGoryId;
        $params['userid'] = $this->getCCUID();
        $params["q"] = "TITLE:".$kw;
        $params["sort"] = "CREATION_DATE:DESC";
        $params['num_per_page'] = $pageSize;
        $params['page'] = $page;
        return $this->ccReq("videos/search", $params);
    }

    /**
     * 获取分类下视频信息
     *
     * @param $cateGoryId
     */
    public function getVideosByCateGoryId($cateGoryId, $page=1, $pageSize=20){
        $params = [];
        $params['categoryid'] = $cateGoryId;
        $params['userid'] = $this->getCCUID();
        $params['num_per_page'] = $pageSize;
        $params['page'] = $page;
        return $this->ccReq("videos/category/v2", $params);
    }

    /**
     * 创建视频分类
     * @param $name
     * @param int $pid
     * @return array|mixed
     */
    public function categoryCreate($name, $pid=0){
        $params = [];
        $params['userid'] = $this->getCCUID();
        $params['name'] = $name;
        if($pid) $params['super_categoryid'] = $pid;
        return $this->ccReq("category/create", $params);
    }


    public function getCCUID()
    {
        $key = $this->getOption("app.bokecc.uid");
        return $key;
    }

    public function getKey(){
        $key = $this->getOption("app.bokecc.key");
        return $key;
    }

    /**
     * 发出请求
     *
     * @param $apiStr
     * @param array $params
     * @param string $method
     * @return array|mixed
     * @throws \Exception
     */
    public function ccReq($apiStr, $params=[], $method="GET"){
        $url = "http://spark.bokecc.com/api/".trim($apiStr, "/");
        $params['format'] = "json";
        $query = $this->getThqs($params);
        $urlstr = $url."?".$query;
        $client = new \GuzzleHttp\Client();
        $res = $client->request($method, $urlstr);
        $status = $res->getStatusCode();
        $json = $res->getBody();
        if($status === 200){
            $arr = json_decode($json, true);
            if(isset($arr['error'])) throw new \Exception($arr['error']);
            return $arr;
        }else{
            return [];
        }
    }

    protected function getThqs($params){
        ksort($params);
        $qs = http_build_query($params);
        $time = time();
        $key = $this->getKey();
        $qf = $qs."&time=".$time."&salt=".$key;
        $hash = md5($qf);
        return $qs."&time=".$time."&hash=".$hash;
    }

}
