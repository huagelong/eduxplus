<?php
/**
 * @Author: kaihui.wang
 * @Contact  hpuwang@gmail.com
 * @Version: 1.0.0
 * @Date: 2020/11/3 10:37
 */

namespace Eduxplus\CoreBundle\Lib\Service;

use Eduxplus\CoreBundle\Lib\Base\BaseService;
use Elasticsearch\ClientBuilder;

class EsService extends BaseService
{
    protected $scroll = "120s";

    public function esDel($type, $id){
        $adapter = $this->getOption("app.search.adapter");
        if($adapter !=2 ) return ;
        $indexName = $this->getEsIndexPre($type);
        $params = [
            'index' => $indexName,
            "type"=>"_doc",
            'id' => $id
        ];
        $client = $this->getEsClient();
        $response = $client->delete($params);
        return $response;
    }

    protected function getEsIndexPre($type){
        $indexName = $this->getOption("app.es.index.pre");
        $indexName = $indexName?$indexName:"eduxplus";
        return $indexName.$type;
    }

    /**
     * es 客户端
     * @return \Elasticsearch\Client
     */
    public function getEsClient(){
        $hosts = $this->getOption("app.es.host");
        $client = ClientBuilder::create()->setHosts([$hosts])->build();
        return $client;
    }

    public function esUpdate($type, $id, $bodyParams)
    {
        $adapter = $this->getOption("app.search.adapter");
        if($adapter !=2 ) return ;
        $indexName = $this->getEsIndexPre($type);
        $client = $this->getEsClient();
        $params = [
            'index' => $indexName,
            "type"=>"_doc",
            "id"=>$id,
            'body' => $bodyParams
        ];
        $response = $client->index($params);
        return $response;
    }

    public function existIndex($type)
    {
        $adapter = $this->getOption("app.search.adapter");
        if($adapter !=2 ) return ;
        $indexName = $this->getEsIndexPre($type);
        $client = $this->getEsClient();
        $params = ['index' => $indexName];
        return $client->indices()->exists($params);
    }

   public function delindex($type)
    {
        $adapter = $this->getOption("app.search.adapter");
        if($adapter !=2 ) return ;
        $indexName = $this->getEsIndexPre($type);
        $client = $this->getEsClient();
        $params = ['index' => $indexName];
        $response = $client->indices()->delete($params);
        return $response;
    }

    /**
     * 插件 ik_smart，ik_max_word
     * 创建索引
     * @param $index
     */
    public function esCreateIndex($type, $field, $usePinyin=1)
    {
        $adapter = $this->getOption("app.search.adapter");
        if($adapter !=2 ) return ;

        $numberOfShards = $this->getOption("app.es.index.shardsNumber");
        $numberOfReplicas = $this->getOption("app.es.index.replicasNumber");

        $client = $this->getEsClient();
        $index = $this->getEsIndexPre($type);
        if($usePinyin){
            $params = [
                'index' => $index,
                'body' => [
                    'settings' => [
                        'number_of_shards' => $numberOfShards,
                        'number_of_replicas' => $numberOfReplicas,
                        'analysis' => [
                            'analyzer' => [
                                'ik_syno_max_word' => [
                                    'type' => 'custom',
                                    'tokenizer' => 'ik_max_word',
                                    'filter' => ['my_stop_filter', 'pinyin_filter'],
                                    'char_filter' => ['my_char_filter']
                                ]
                            ],
                            'filter' => [
                                'my_stop_filter' => [
                                    'type' => 'stop',
                                    'stopwords' => [' ']
                                ],
                                "pinyin_filter"=>[
                                    "type"=>"pinyin",
                                    "keep_first_letter"=>false,
                                    "keep_full_pinyin"=>true,
                                    "keep_joined_full_pinyin"=>true,
                                    "keep_none_chinese"=>true,
                                    "none_chinese_pinyin_tokenize"=>true,
                                    "keep_none_chinese_in_joined_full_pinyin"=>true,
                                    "keep_original"=>true,
                                    "limit_first_letter_length"=>16,
                                    "lowercase"=>true,
                                    "trim_whitespace"=>true,
                                    "remove_duplicated_term"=>true
                                ],
                            ],
                            'char_filter' => [
                                'my_char_filter' => [
                                    'type' => 'mapping',
                                    'mappings' => ['| => |']
                                ]
                            ]
                        ]
                    ],
                    'mappings' => [
                        '_doc' => [
                            '_source' => [
                                'enabled' => true
                            ],
                            'properties' => [
                                "start_time"=>[
                                    "type"=>"date",
                                    "format"=>"yyyy-MM-dd HH:mm:ss||yyyy-MM-dd||epoch_millis",
                                    "ignore_malformed"=>true
                                ],
                                $field => [
                                    'type' => 'text',
                                    "fields"=>[
                                        "ksearch"=>[
                                            "type"=>"text",
                                            "store"=>false,
                                            "term_vector"=>"with_positions_offsets",
                                            'analyzer' => 'ik_syno_max_word',
                                            'search_analyzer' => 'ik_syno_max_word',
                                            "boost"=>10
                                        ]
                                    ]
                                ]
                            ]
                        ]
                    ]
                ]
            ];
        }else{
            $params = [
                'index' => $index,
                'body' => [
                    'settings' => [
                        'number_of_shards' => $numberOfShards,
                        'number_of_replicas' => $numberOfReplicas,
                        'analysis' => [
                            'analyzer' => [
                                'ik_syno_smart' => [
                                    'type' => 'custom',
                                    'tokenizer' => 'ik_smart',
                                    'filter' => ['my_stop_filter'],
                                    'char_filter' => ['my_char_filter']
                                ]
                            ],
                            'filter' => [
                                'my_stop_filter' => [
                                    'type' => 'stop',
                                    'stopwords' => [' ']
                                ],
                            ],
                            'char_filter' => [
                                'my_char_filter' => [
                                    'type' => 'mapping',
                                    'mappings' => ['| => |']
                                ]
                            ]
                        ]
                    ],
                    'mappings' => [
                        '_doc' => [
                            '_source' => [
                                'enabled' => true
                            ],
                            'properties' => [
                                "start_time"=>[
                                    "type"=>"date",
                                    "format"=>"yyyy-MM-dd HH:mm:ss||yyyy-MM-dd||epoch_millis",
                                    "ignore_malformed"=>true
                                ],
                                $field => [
                                    'type' => 'text',
                                    "fields"=>[
                                        "ksearch"=>[
                                            "type"=>"text",
                                            "store"=>false,
                                            "term_vector"=>"with_positions_offsets",
                                            'analyzer' => 'ik_syno_smart',
                                            'search_analyzer' => 'ik_syno_smart',
                                            "boost"=>10
                                        ]
                                    ]
                                ]
                            ]
                        ]
                    ]
                ]
            ];
        }
        $response = $client->indices()->create($params);
        return $response;
    }

    /**
     * 搜索
     *
     * @param $kw
     * @param $type
     * @param $scrollId
     * @param $limit
     * @return array
     */
    public function esSearch($kw, $type,$field, $scrollId, $limit){
        try{
            $client = $this->getEsClient();
            $indexName = $this->getEsIndexPre($type);
            $params = [
                "scroll" => $this->scroll,
                "from"=>0,
                'size'   => $limit,
                'index' => $indexName,
                "type"=>"_doc",
                'body' => [
                    'query' => [
                        "bool"=>[
                            "must"=>[
                                'match' => [
                                    $field.'.ksearch' => $kw
                                ],
                            ]
                        ]
                    ],
                    "highlight"=>[
                        "fields"=>[
                            $field.'.ksearch'=>[
                                "pre_tags"=>["<em>"],
                                "post_tags"=>["</em>"],
                                "require_field_match"=>true,
                                "type"=>"unified",
                            ]
                        ],
                    ],
                ]
            ];
            if(!$scrollId){
                $response = $client->search($params);
            }else{
                $response = $client->scroll([
                        "scroll_id" => $scrollId,  // 使用上个请求获取到的  _scroll_id
                        "scroll" => $this->scroll           // 时间窗口保持一致
                    ]
                );
            }
//            var_dump($response);
            $total = isset($response['hits']['total'])?$response['hits']['total']:0;

            if(!$total) return [0, [], "", []];

            $data = $response['hits']['hits'];
            $scrollId = $response["_scroll_id"];
            $ids = array_column($data, "_id");
            $highlights = [];
            if($data){
                foreach ($data as $v){
                    $highlight = $v["highlight"][$field.'.ksearch'][0];
                    $highlights[$v['_id']] = $highlight;
                }
            }
            return [$total, $ids, $scrollId, $highlights];
        }catch (\Exception $e){
            return [0, [], "", []];
        }
    }
}
