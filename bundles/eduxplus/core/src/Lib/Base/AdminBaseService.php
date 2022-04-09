<?php

/**
 * @Author: kaihui.wang
 * @Contact  hpuwang@gmail.com
 * @Version: 1.0.0
 * @Date: 2020/5/20 16:20
 */

namespace Eduxplus\CoreBundle\Lib\Base;

class AdminBaseService extends BaseService
{
    public function getUid()
    {
        $user = $this->getUser();
        if ($user) return $user->getId();
        return 0;
    }

    public function getFormatRequestSql($request, $excludes=[])
    {
        $fields = $request->query->all();
        if (!isset($fields['operates']) || !isset($fields['types']) || !isset($fields['values'])) return "";
        $operates = $fields['operates'];
        $types = $fields['types'];
        $values = $fields['values'];

        if($excludes){
            foreach ($excludes as $exk){
                unset($values[$exk]);
            }
        }

        $sql = "";
        if ($values) {
            $sql .= " WHERE ";
            foreach ($values as $k => $v) {
                if (($v === "") || (substr($k, 0, 1) == "_") || ($v == -1)) {
                    continue;
                }

                if ($types[$k] === "text") {
                    if ($operates[$k] == "like") {
                        $sql .= $k . " like '%" . $v . "%'";
                    } else {
                        $sql .= $k . " = '" . $v . "' ";
                    }
                } elseif ($types[$k] === "number") {
                    $sql .= $k . " {$operates[$k]} '" . $v . "' ";
                } elseif ($types[$k] === "daterange" || $types[$k] === "datetimerange") {
                    list($startDate, $endDate) = explode(" - ", $v);
                    $sql .= $k . " {$operates[$k]} '{$startDate}' AND '{$endDate}' ";
                } elseif ($types[$k] === "daterange2" || $types[$k] === "datetimerange2") {
                    list($startDate, $endDate) = explode(" - ", $v);
                    $startDateStr = strtotime($startDate);
                    $endDateStr = strtotime($endDate);
                    $sql .= $k . " {$operates[$k]} '{$startDateStr}' AND '{$endDateStr}' ";
                } else {
                    $sql .= $k . " = '" . $v . "' ";
                }

                $sql .= " AND ";
            }
        }
        if ($sql == " WHERE ") return "";
        $sql = rtrim($sql, " AND ");
        return $sql;
    }

    public function isAuthorized($uid, $routeName)
    {
        if (!$uid) return false;
        $sql = "SELECT a FROM Core:BaseMenu a WHERE a.url=:url";
        $menuInfo = $this->db()->fetchOne($sql, ['url' => $routeName]);
        if (!$menuInfo) return false;
        $menuId = $menuInfo['id'];

        $userSql = "SELECT a.roleId FROM Core:BaseRoleUser a WHERE a.uid=:uid";
        $roleIds = $this->db()->fetchFields("roleId", $userSql, ['uid' => $uid]);

        if (!$roleIds) return false;

        $sql = "SELECT a.menuId FROM Core:BaseRoleMenu a WHERE a.roleId IN(:roleId) ";
        $menuIds = $this->db()->fetchFields("menuId", $sql, ['roleId' => $roleIds]);
        if (!$menuIds) return false;
        return in_array($menuId, $menuIds);
    }

    public function getInitialPreviewConfig($path)
    {
        if (!$path) return "";
        $initialPreviewConfig = [];
        $path = \GuzzleHttp\json_decode($path, 1);
        foreach ($path as $v) {
            $fileInfo = $this->getFileInfo($v);
            $type = stristr($fileInfo['mime'], "image") ? "image" : "other";
            $tmp = [];
            $tmp['type'] = $type;
            $tmp['caption'] = $fileInfo['filename'];
            $tmp['size'] = $fileInfo['length'];
            $initialPreviewConfig[] = $tmp;
        }
        //        dump($initialPreviewConfig);
        return \GuzzleHttp\json_encode($initialPreviewConfig);
    }

    protected function getFileInfo($strUrl)
    {
        if (!stristr($strUrl, "http")) {
            $domain = $this->getOption("app.domain");
            $strUrl = trim($domain, "/") . $strUrl;
        }

        $arrRet = [];
        if (($arrTmp = get_headers($strUrl, true))) {
            //            dump($arrTmp);
            $arrRet = array("length" => $arrTmp['Content-Length'], "mime" => $arrTmp['Content-Type']);
            $arrRet["filename"] = pathinfo($strUrl, PATHINFO_FILENAME) . "." . pathinfo($strUrl, PATHINFO_EXTENSION);
            if (preg_match('/\s(\d+)\s/', $arrTmp[0], $arr)) {
                $arrRet["status"] = $arr[1];
            }
        }

        return $arrRet;
    }
}
