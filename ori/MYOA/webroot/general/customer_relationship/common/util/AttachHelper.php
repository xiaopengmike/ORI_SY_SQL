<?php

/**
 * 附件控件
 * 
 * 使用layui的附件附件插件，适用与新版的layui前端页面
 * 
 * @author Tsao 4306
 * @copyright   Copyright (c) 2003-2018 中国银行深圳市分行
 * @version     AttachHelper.php 2018年6月28日
 * 
 */

class AttachHelper
{
    public function attachFormInfo($informationId = 0, $type = 'normal', $attachType = "info")
    {
        global $db;

        $attachs = array();
        if ($informationId != 0 && $type == 'normal') {
            $rsAttach = $db->paramQuery('SELECT * from inhe_attachment where type="' . $attachType . '" AND relatedId = ' . $informationId);
            while ($a = $db->fetchArray($rsAttach, MYSQL_ASSOC)) {
                $ext = strtolower(substr($a['filename'], strrpos($a['filename'], '.') + 1));
                $a['isImage'] = false;
                if (in_array($ext, array('jpg', 'jpeg', 'png', 'gif', 'bmp', 'tif'))) {
                    $a['isImage'] = true;
                }
                $a['size'] = number_format($a['size'] / 1024, 1) . 'KB';
                $a['name'] = $a['filename'];
                $attachs[] = $a;
            }
            $db->freeResult($rsAttach);
        }
        $attachs = json_encode(gbk2utf8($attachs));

        $js = '<script type= "text/javascript"> 
               $(function () {
                    $("#attach_div_' . $attachType . $informationId . '").uploadlayui({id:"' . $attachType . $informationId . '", name: "myfile", attachType: "' . $attachType . '", action: "' . MYOA_ROOT_PATH . 'general/attachment/attach.php", defaults:' . $attachs . '});
               });
               </script>';
        $body = '<div id="attach_div_' . $attachType . $informationId . '"></div>';
        return array('js' => $js, 'body' => $body);
    }

    /**
     * 建造attach列表
     *
     * @param int $informationId 信息IDs
     */
    public function attachListInfo($informationId, $type = 'info')
    {
        global $db, $CACHE;
        $body = '';
        $attachs = false;
        $cacheKey = 'infoAttach' . $informationId;
        //$attachs = getMemcachedData($cacheKey);
        //if (!is_array($attachs)) {
        $attachs = array();

        $att = $db->paramQuery('SELECT * FROM inhe_attachment WHERE  type ="' . $type . '" AND relatedId = ' . $informationId . ' ORDER BY id ASC', true);
        while ($a = $db->fetchArray($att)) {
            $a['size'] = number_format($a['size'] / 1024, 2) . 'KB';
            $attachs[] = $a;
        }
        $db->freeResult($att);
        //    saveMemecachedData($cacheKey, $attachs);


        if (count($attachs) > 0) {
            $body = '<div style="margin: 10px"></div><ul>';
            foreach ($attachs as $a) {
                $body .= '<li><a href="' . MYOA_ROOT_PATH . 'general/attachment/attach.php?action=download&amp;id=' . $a['id'] . '" target="_blank" onclick="alert(\'温馨提示：您已签订保密协议，正在下载内部资料，请注意保密。\');">' . $a['filename'] . '</a> (大小: ' . $a['size'] . ')' . '</li>';
            }
            $body .= '</ul></div>';
        }



        return $body;
    }
}
