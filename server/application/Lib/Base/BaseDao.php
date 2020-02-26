<?php
/**
 * User: Peter Wang
 * Date: 16/9/23
 * Time: 下午2:04
 */

namespace Lib\Base;

use Trensy\DaoAbstract;

class BaseDao extends DaoAbstract
{
    public function __construct()
    {
        $storageConfig = $this->config()->get("storage.server.pdo");

        parent::__construct($storageConfig);
    }

    /**
     * 自动添加
     *
     * @param $data
     * @param $daoObj
     * @return bool
     */
    public function autoAdd($data)
    {
        $allData = [];
        if(!$data) return false;
        foreach ($data as $k=>$v){
            $allData[$k] = $v;
        }
        $allData['created_at'] = time();
        $allData['updated_at'] = time();
        return $this->insert($allData);
    }

    /**
     * 自动更新
     * 
     * @param $data
     * @param $where
     * @param $daoObj
     * @return bool
     */
    public function autoUpdate($data, $where)
    {
        $allData = [];
        if(!$data) return false;
        foreach ($data as $k=>$v){
            $allData[$k] = $v;
        }
        $allData['updated_at'] = time();
        return $this->update($allData, $where);
    }

    /**
     * 自动增加
     *
     * @param $field
     * @param array $where
     * @param int $number
     * @param null $tableName
     * @return bool
     */
    public function autoIncrease($field, $where = array(), $number = 1, $tableName = null)
    {
        $tableName = $tableName ? self::$prefix . $tableName : $this->getTableName();

        $whereSql = $this->parseWhere($where);
        $whereSql = $whereSql ? " WHERE " . $whereSql : "";

        $sql = "UPDATE `{$tableName}` SET {$field} = {$field} +{$number}, `updated_at`='".time()."' " . $whereSql;

        $this->exec($sql, self::CONN_MASTER);

        return true;
    }
}