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
