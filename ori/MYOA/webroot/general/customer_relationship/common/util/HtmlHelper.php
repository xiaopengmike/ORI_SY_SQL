<?php

/**
 * 组装页面工具类
 */
require_once 'SelectEnum.php';


define('ATT_URL', "/general/attachment/attach.php?type=");
class HtmlHelper
{
    protected $_isAdd;
    protected $_isDelete;
    protected $_isModify;
    protected $_isDetail;
    /**
     * DBMySQL constructor.
     * @param string $userName DB username
     * @param string $password DB password
     * @param string $host DB hostname / IP, recommended using IP for better performance
     * @param string $db DB name (schema)
     * @param bool $debug set true to enable debugging
     * @param string $encoding database encoding, default gbk
     */
    public function __construct($isDetail = false, $isAdd = false, $isDelete = false, $isModify = false)
    {
        $this->_isDetail = $isDetail;
        $this->_isAdd = $isAdd;
        $this->_isDelete = $isDelete;
        $this->_isModify = $isModify;
    }

    public function getShowDataInfo($param)
    {
        // $strWhere = "";
        // if (array_key_exists("is_search", $param) && $param['is_search'] != "") {
        //     $strWhere .= " or is_search = '" . $db->escape($param['is_search']) . "' ";
        // }
        // if (array_key_exists("is_view", $param) && $param['is_view'] != "") {
        //     $strWhere .= " or is_view = '" . $db->escape($param['is_view']) . "' ";
        // }
        // if (array_key_exists("is_edit", $param) && $param['is_edit'] != "") {
        //     $strWhere .= " or is_edit = '" . $db->escape($param['is_edit']) . "' ";
        // }
        // if (array_key_exists("is_table_view", $param) && $param['is_table_view'] != "") {
        //     $strWhere .= " or is_table_view = '" . $db->escape($param['is_table_view']) . "' ";
        // }
        $strWhere = "";
        if (array_key_exists("page_type", $param) && $param['page_type'] != "") {
            $strWhere .= " and page_type = '" . $param['page_type'] . "' ";
        }
        $sql = "select * from inhe_dynamic_page_field "
            . " where module_type = '" . $param['module_type'] . "'";
        $sqlPage  = "select * from inhe_dynamic_page where module_type = '" . $param['module_type'] . "'" . $strWhere;
        $fieldItems = exequery(TD::conn(), $sql);
        $pageDataInfo = array();

        $fieldArr = array();
        while ($item = mysql_fetch_assoc($fieldItems)) {
            $fieldArr[] = $item;
        }
        mysql_free_result($items);

        $pageInfoItems = exequery(TD::conn(), $sqlPage);
        $pageInfoArr = array();
        while ($item = mysql_fetch_assoc($pageInfoItems)) {
            $pageInfoArr[] = $item;
        }
        mysql_free_result($items);

        $pageDataInfo['field'] = $fieldArr;
        $pageDataInfo['page'] = $pageInfoArr;
        return $pageDataInfo;
    }

    public function getInfoById($id, $tableName)
    {
        $sql = "select * from " . $tableName . " where id = '" . $id . "'";
        $items = exequery(TD::conn(), $sql);
        $result = array();
        while ($item = mysql_fetch_assoc($items)) {
            $result = $item;
        }
        return $result;
    }

    public function getAttachments($relatedId, $type)
    {
        $sql = "select * from inhe_attachment where relatedId = '" . $relatedId . "' and type = '" . $type . "' ";
        $items = exequery(TD::conn(), $sql);
        $result = array();
        while ($item = mysql_fetch_assoc($items)) {
            $ext = strtolower(substr($item['filename'], strrpos($item['filename'], '.') + 1));
            $item['isImage'] = false;
            if (in_array($ext, array('jpg', 'jpeg', 'png', 'gif', 'bmp', 'tif'))) {
                $item['isImage'] = true;
            }
            $item['size'] = number_format($item['size'] / 1024, 1) . 'KB';
            $item['name'] = $item['filename'];
            $result[] = $item;
        }
        return $result;
    }

    /**
     * 一般一个页面包含head，body head固定
     * body包含
     * 1、html
     * 2、script
     */
    public function createHtml($htmlDataArr, $pageType, $editDataArr = array())
    {
        $elemArr = $htmlDataArr['field'];
        $htmlInfo = array();
        switch ($pageType) {
            case 'list':
                $currUrl = '';
                $htmlSearch = "";
                for ($i = 0; $i < count($elemArr); $i++) {
                    if ($elemArr[$i]['is_search'] == '1') {
                        switch ($elemArr[$i]['data_type']) {
                            case 'input':
                                $htmlSearch .= '<div class="layui-inline">';
                                $htmlSearch .= '<input type="text" name="' . $elemArr[$i]['param_key'] . '" placeholder="请输入' . $elemArr[$i]['param_name'] . '" autocomplete="off" class="layui-input">';
                                break;
                            case 'date':
                                $htmlSearch .= '<div class="layui-inline">';
                                $htmlSearch .= '<input class="layui-input date" name="' . $elemArr[$i]['param_key'] . '" placeholder="请输入' . $elemArr[$i]['param_name'] . '">';
                                break;
                            case 'select':
                                $htmlSearch .= '<div class="layui-input-inline">';
                                $selectField = $elemArr[$i]['module_type'] . "_" . $elemArr[$i]['param_key'];
                                $htmlSearch .= '<select id="' . $elemArr[$i]['param_key'] . '" name="' . $elemArr[$i]['param_key'] . '" width="30%" lay-verify="required"><option value="">请选择</option>';
                                if (is_array(SelectEnum::$$selectField)) {
                                    foreach (SelectEnum::$$selectField as $k => $v) {
                                        $htmlSearch .= '<option value="' . $k . '">' . $v . '</option>';
                                    }
                                }
                                $htmlSearch .= '</select>';
                                // $htmlSearch .= '<input class="layui-input date" name="'.$elemArr[$i]['param_key'].'" placeholder="请输入'.$elemArr[$i]['param_name'].'">';
                                break;
                            case 'dates':
                                $htmlSearch .= '<input class="layui-input date" name="' . $elemArr[$i]['param_key'] . '" placeholder="请输入' . $elemArr[$i]['param_name'] . '">';
                                break;
                            case 'datee':
                                $htmlSearch .= '<input class="layui-input date" name="' . $elemArr[$i]['param_key'] . '" placeholder="请输入' . $elemArr[$i]['param_name'] . '">';
                                break;
                            default:
                                $htmlSearch .= '<div class="layui-inline">';
                                $htmlSearch .= '<input type="text" name="' . $elemArr[$i]['param_key'] . '" placeholder="请输入' . $elemArr[$i]['param_name'] . '" autocomplete="off" class="layui-input">';
                                break;
                        }
                        $htmlSearch .= '</div>';
                    }
                }
                $htmlInfo['search'] = $htmlSearch;

                $htmlList = '<tr class="layui-bg-gray">';
                $htmltmpl = '<tr>';

                $colNum = 0;
                for ($i = 0; $i < count($elemArr); $i++) {
                    if ($elemArr[$i]['is_list'] == '1') {
                        $htmlList .= '<th>' . $elemArr[$i]['param_name'] . '</th>';
                        $css = (is_null($elemArr[$i]['css']) || $elemArr[$i]['css'] == "") ? "center" : $elemArr[$i]['css'];
                        $filed = (is_null($elemArr[$i]['alias']) || $elemArr[$i]['alias'] == "") ? $elemArr[$i]['param_key'] : $elemArr[$i]['alias'];
                        $htmltmpl .= '<td align="' . $css . '">${' . $filed . '}</td>';
                        $colNum++;
                    }
                }

                if ($this->_isDetail || $this->_isDelete || $this->_isModify) {
                    $htmlList .= '<th>操作</th>'; //这里可以根据用户角色调整
                    $colNum++;
                    $htmltmpl .= '<td align="center">';
                    for ($i = 0; $i < count($htmlDataArr['page']); $i++) {
                        if ($htmlDataArr['page'][$i]['page_type'] == "list") {
                            $currUrl = $htmlDataArr['page'][$i]['page_url'];
                        }
                        if ($this->_isAdd && $htmlDataArr['page'][$i]['page_type'] == "add") {
                            $htmltmpl .= '<a href="javascript:void(0);" onclick="pageAdd(\'' . $htmlDataArr['page'][$i]['page_url'] . '\',\'' . $htmlDataArr['page'][$i]['page_name'] . '\');return false;">新增</a>&nbsp;';
                        }
                        if ($this->_isModify && $htmlDataArr['page'][$i]['page_type'] == "modify") {
                            $htmltmpl .= '<a href="javascript:void(0);" onclick="pageAdd(\'' . $htmlDataArr['page'][$i]['page_url'] . '&id=${id}\',\'' . $htmlDataArr['page'][$i]['page_name'] . '\');return false;">修改</a>&nbsp;';
                        }
                        // if($this->_isModify && $htmlDataArr['page'][$i]['page_type'] == "delete"){
                        //     $htmltmpl .= '<a href="javascript:void(0);" onclick="deleteData(\'' . $htmlDataArr['page'][$i]['page_url'] . '&id=${id}\',\'' . $htmlDataArr['page'][$i]['page_name'] . '\');return false;">删除</a>&nbsp;';
                        // }
                    }

                    $htmltmpl .= '</td>';
                }
                $htmlList .= '</tr>';
                $htmltmpl .= '</tr>';

                $htmlInfo['list'] = $htmlList;
                $htmlInfo['tmpl'] = $htmltmpl;
                $htmlInfo['colNum'] = $colNum;
                $htmlInfo['url'] = $currUrl;
                break;
            case 'add':
                $htmlAdd = "";
                $scriptAdd = "";
                $mustInputElem = '<span class="layui-red">*</span>';
                for ($i = 0; $i < count($elemArr); $i++) {
                    if ($elemArr[$i]['is_add'] == '1') {
                        $htmlAdd .= '<tr><td class="tdright"><b>' . $elemArr[$i]['param_name'] . '</b>' . ($elemArr[$i]['is_must'] == 1 ? $mustInputElem : "") . '</td><td class="left">';
                        switch ($elemArr[$i]['data_type']) {
                            case 'input':
                                $htmlAdd .= '<input type="text" class="layui-input" lay-verify="' . $elemArr[$i]['verify'] . '" name="' . $elemArr[$i]['param_key'] . '" id="' . $elemArr[$i]['param_key'] . '"/>';
                                break;
                            case 'text':
                                $htmlAdd .= '<textarea class="layui-textarea" lay-verify="' . $elemArr[$i]['verify'] . '" name="' . $elemArr[$i]['param_key'] . '" id="' . $elemArr[$i]['param_key'] . '" style="height: 160px;"></textarea>';
                                break;
                            case 'date':
                                $htmlAdd .= '<input class="layui-input date" lay-verify="' . $elemArr[$i]['verify'] . '" name="' . $elemArr[$i]['param_key'] . '" id="' . $elemArr[$i]['param_key'] . '">';
                                break;
                            case 'attach':
                                $htmlAdd .= '<input id="' . $elemArr[$i]['param_key'] . '" type="button" class="layui-btn layui-bg-gray" style="display: inline!important;" value="' . $elemArr[$i]['param_name'] . '" />';
                                $attUrl = ATT_URL . (is_null($elemArr[$i]['other_info']) || $elemArr[$i]['other_info'] == "" ? "default" : $elemArr[$i]['other_info']);
                                $scriptAdd .= 'attach.init({elem: "#' . $elemArr[$i]['param_key'] . '",uploadUrl: "' . $attUrl . '"});';
                                break;
                            case 'select':
                                $selectField = $elemArr[$i]['module_type'] . "_" . $elemArr[$i]['param_key'];
                                $htmlAdd .= '<select id="' . $elemArr[$i]['param_key'] . '" name="' . $elemArr[$i]['param_key'] . '" width="30%" lay-verify="required"><option value="">请选择</option>';
                                if (is_array(SelectEnum::$$selectField)) {
                                    foreach (SelectEnum::$$selectField as $k => $v) {
                                        $htmlAdd .= '<option value="' . $k . '">' . $v . '</option>';
                                    }
                                }
                                $htmlAdd .= '</select>';
                                break;
                            case 'selectPerson':
                                $selectLevel = (is_null($elemArr[$i]['other_info']) || $elemArr[$i]['other_info'] == "" ? "3" : $elemArr[$i]['other_info']);
                                $isMultipleSelection = (!is_null($elemArr[$i]['css']) && $elemArr[$i]['css'] == "multi") ? true : false;
                                $multStr = "";
                                if ($isMultipleSelection) {
                                    $multStr = '"isMultipleSelection": ' . $isMultipleSelection . ',"MultipleSelectDivId": "' . $elemArr[$i]['param_key'] . '_div",';
                                }
                                $htmlAdd .= '<span id="' . $elemArr[$i]['param_key'] . '"></span><div id="' . $elemArr[$i]['param_key'] . '_div" style="clear:both"></div>';
                                $scriptAdd .= '$("#' . $elemArr[$i]['param_key'] . '").newDeptSelect({"selectLevel": ' . $selectLevel
                                    . ',"selectName": "' . $elemArr[$i]['param_key'] . '",' . $multStr . 'nextFunc: nextFunc});';
                                break;
                            default:
                                $htmlAdd .= '<input type="text" name="' . $elemArr[$i]['param_key'] . '" placeholder="请输入' . $elemArr[$i]['param_name'] . '" autocomplete="off" class="layui-input">';
                                break;
                        }
                        $htmlAdd .= '</td></tr>';
                    }
                }
                $htmlInfo['html'] = $htmlAdd;
                $htmlInfo['script'] = $scriptAdd;
                break;
            case 'edit':
                $htmlAdd = "";
                $scriptAdd = "";
                $mustInputElem = '<span class="layui-red">*</span>';
                for ($i = 0; $i < count($elemArr); $i++) {
                    if ($elemArr[$i]['is_add'] == '1') {
                        $htmlAdd .= '<tr><td class="tdright"><b>' . $elemArr[$i]['param_name'] . '</b>' . ($elemArr[$i]['is_must'] == 1 ? $mustInputElem : "") . '</td><td class="left">';
                        switch ($elemArr[$i]['data_type']) {
                            case 'input':
                                $htmlAdd .= '<input type="text" class="layui-input" lay-verify="' . $elemArr[$i]['verify'] . '" name="' . $elemArr[$i]['param_key'] . '" id="' . $elemArr[$i]['param_key'] . '"/>';
                                $scriptAdd .= '$("#' . $elemArr[$i]['param_key'] . '").val(\'' . $editDataArr[$elemArr[$i]['param_key']] . '\');';
                                break;
                            case 'text':
                                $htmlAdd .= '<textarea class="layui-textarea" lay-verify="' . $elemArr[$i]['verify'] . '" name="' . $elemArr[$i]['param_key'] . '" id="' . $elemArr[$i]['param_key'] . '" style="height: 160px;"></textarea>';
                                $scriptAdd .= '$("#' . $elemArr[$i]['param_key'] . '").val(\'' . $editDataArr[$elemArr[$i]['param_key']] . '\');';
                                break;
                            case 'date':
                                $htmlAdd .= '<input class="layui-input date" lay-verify="' . $elemArr[$i]['verify'] . '" name="' . $elemArr[$i]['param_key'] . '" id="' . $elemArr[$i]['param_key'] . '">';
                                $scriptAdd .= '$("#' . $elemArr[$i]['param_key'] . '").val(\'' . $editDataArr[$elemArr[$i]['param_key']] . '\');';
                                break;
                            case 'attach':
                                $htmlAdd .= '<input id="' . $elemArr[$i]['param_key'] . '" type="button" class="layui-btn layui-bg-gray" style="display: inline!important;" value="' . $elemArr[$i]['param_name'] . '" />';
                                $attUrl = ATT_URL . (is_null($elemArr[$i]['other_info']) || $elemArr[$i]['other_info'] == "" ? "default" : $elemArr[$i]['other_info']);
                                // $scriptAdd .= 'attach.init({elem: "#' . $elemArr[$i]['param_key'] . '",uploadUrl: "' . $attUrl . '"});';
                                break;
                            case 'select':
                                $selectField = $elemArr[$i]['module_type'] . "_" . $elemArr[$i]['param_key'];
                                $htmlAdd .= '<select id="' . $elemArr[$i]['param_key'] . '" name="' . $elemArr[$i]['param_key'] . '" width="30%" lay-verify="required"><option value="">请选择</option>';
                                if (is_array(SelectEnum::$$selectField)) {
                                    foreach (SelectEnum::$$selectField as $k => $v) {
                                        $htmlAdd .= '<option value="' . $k . '">' . $v . '</option>';
                                    }
                                }
                                $htmlAdd .= '</select>';
                                $scriptAdd .= '$("#' . $elemArr[$i]['param_key'] . '").val(\'' . $editDataArr[$elemArr[$i]['param_key']] . '\');';
                                break;
                            case 'selectPerson':
                                $selectLevel = (is_null($elemArr[$i]['other_info']) || $elemArr[$i]['other_info'] == "" ? "3" : $elemArr[$i]['other_info']);
                                $isMultipleSelection = (!is_null($elemArr[$i]['css']) && $elemArr[$i]['css'] == "multi") ? true : false;
                                $multStr = "";
                                if ($isMultipleSelection) {
                                    $multStr = '"isMultipleSelection": ' . $isMultipleSelection . ',"MultipleSelectDivId": "' . $elemArr[$i]['param_key'] . '_div",';
                                }
                                $htmlAdd .= '<span id="' . $elemArr[$i]['param_key'] . '"></span><div id="' . $elemArr[$i]['param_key'] . '_div" style="clear:both"></div>';
                                $scriptAdd .= '$("#' . $elemArr[$i]['param_key'] . '").newDeptSelect({"selectLevel": ' . $selectLevel
                                    . ',"selectName": "' . $elemArr[$i]['param_key'] . '",' . $multStr . 'nextFunc: nextFunc});';
                                break;
                            default:
                                $htmlAdd .= '<input type="text" name="' . $elemArr[$i]['param_key'] . '" placeholder="请输入' . $elemArr[$i]['param_name'] . '" autocomplete="off" class="layui-input">';
                                break;
                        }
                        $htmlAdd .= '</td></tr>';
                    }
                }
                $htmlInfo['html'] = $htmlAdd;
                $htmlInfo['script'] = $scriptAdd;
                break;
            default:
                # code...
                break;
        }

        return $htmlInfo;
    }
}
