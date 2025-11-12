<?php
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
include_once("inc/utility_file.php");
include_once("inc/utility_field.php");

/**
 * 附件操作类
 * @author ysr
 */
class AttachModel
{
    /**
     * 更新附件关联relatedId
     * 用于保存附件数据使用
     */
    public function updateAttach($ids, $relateId)
    {
        $sql = "update inhe_attachment set relatedId = '" . $relateId . "' where id in(" . $ids . ")";
        $re = exequery(TD::conn(), $sql);
        return $re;
    }

     /**
     * 
     * 用于添加变更附件数据使用，之前使用updateAttach会造成原单据附件丢失
     */
    public function changeAddAttach($ids, $relateId)
    {
        $sql = "insert into inhe_attachment(filename,path,size,relatedId,type) 
            select filename,path,size,'$relateId' relatedId,type from inhe_attachment
            where id in(" . $ids . ")";
        $re = exequery(TD::conn(), $sql);
        return $re;
    }

    /**
     * 批量删除附件
     * 条件： 功能模块、 附加栏位ID
     */
    function batchDeleteAttach($relatedId, $type)
    {
        $where = " where relatedId='" . $relatedId . "' ";
        if (isset($type) && !empty($type)) {
            // 当type不为空时，只适用页面单个附件控件情况使用
            $where .= " and type='" . $type . "' ";
        }
        $sql = "SELECT * FROM inhe_attachment " . $where;
        $attachs = exequery(TD::conn(), $sql);
        $result = array();
        while ($i = mysql_fetch_assoc($attachs)) {
            $result[] = $i;
        }
        mysql_free_result($attachs);

        foreach ($result as $a) {
            $sql2 = 'DELETE FROM inhe_attachment WHERE id = ' . $a['id'];
            exequery(TD::conn(), $sql2);
            if (file_exists($a['path'])) {
                @unlink($a['path']);
            }
        }
    }

    /**
     * 获取附件列表，适用单个附件控件
     * 建议使用getAttachById方法更通用
     */
    public function attachList($informationId, $type)
    {
        $attachs = array();
        $sql = 'SELECT * FROM inhe_attachment WHERE  type ="' . $type . '" AND relatedId = "' . $informationId . '" ORDER BY id ASC';
        $res = exequery(TD::conn(), $sql);
        while ($a = mysql_fetch_assoc($res)) {
            $a['size'] = number_format($a['size'] / 1024, 2) . 'KB';
            $attachs[] = $a;
        }
        mysql_free_result($res);

        return $attachs;
    }

    /**
     * 通过relatedId查询对应所有附件集合
     * 适用页面存在多个附件控件时取数
     * 要求：附件控件栏位ID与数据库type值相同
     */
    public function getAttachById($id)
    {
        $sql = "select * from inhe_attachment where relatedId = '" . $id . "' ";
        $res = exequery(TD::conn(), $sql);
        $result = array();
        while ($item = mysql_fetch_assoc($res)) {
            $ext = strtolower(substr($item['filename'], strrpos($item['filename'], '.') + 1));
            $item['isImage'] = false;
            if (in_array($ext, array('jpg', 'jpeg', 'png', 'gif', 'bmp', 'tif'))) {
                $item['isImage'] = true;
            }
            $item['size'] = number_format($item['size'] / 1024, 1) . 'KB';
            $item['name'] = $item['filename'];
            $result[$item['type']][] = $item;
        }
        $attachObj = json_encode($this->array_iconv($result));

        return $attachObj;
    }

    /**
     * 将含有GBK的中文数组转为utf-8
     *
     * @param array $arr   数组
     * @param string $in_charset 原字符串编码
     * @param string $out_charset 输出的字符串编码
     * @param string $returnType   返回值类型：数组；json字符串
     * @return array
     */
    public function array_iconv($arr, $in_charset = "gbk", $out_charset = "utf-8", $returnType = 'json')
    {
        $ret = eval('return ' . iconv($in_charset, $out_charset, var_export($arr, true) . ';'));
        // 返回值类型判断
        if ($returnType = "array") {
            return $ret;
        }

        // 这里转码之后可以输出json,默认输出json字符串
        return json_encode($ret);
    }
}
