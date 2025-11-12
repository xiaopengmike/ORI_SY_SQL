<?php

include_once "inc/auth.inc.php";
include_once("inc/utility_all.php");
$HTML_PAGE_TITLE = "数据导入模块";
include_once "inc/header.inc.php";
include_once "common/Common.php";

/**
 * excel文件数据导入功能模块说明
 * 传递参数： 
 * @param dataType 导入数据业务类型
 * @param templetName 标准数据模板文件名称
 * @param formatType 表格格式类型参数
 */
if($formatType == '' || $formatType == null){
    $formatType = '1';   // 默认采用格式1：表头为字段名，内容为数值；
}
if($templetName == '' || $templetName == null){
    $templetName = 'templet';     // 默认模板名称定为templet.xls
}
?>
<script>
    function CheckForm2() {
        if (document.form2.EXCEL_FILE.value == "") {
            alert("请选择要导入的文件！");
            return false;
        } else {
            var file_temp = document.form2.EXCEL_FILE.value,
                file_name;
            var Pos;
            Pos = file_temp.lastIndexOf("\\\\");
            file_name = file_temp.substr(Pos + 1, file_temp.length);
            document.form2.FILE_NAME.value = file_name;
        }
        return true;
    }
</script>

<body class="bodycolor">
    <table border="0" width="100%" cellspacing="0" cellpadding="3" class="small">
        <tr>
            <td class="Big"><img src="<?= MYOA_STATIC_SERVER ?>/static/images/sys_config.gif" WIDTH="18" HEIGHT="18" align="absmiddle">
                <span class="big3">&nbsp;<?= $dataType ?>数据导入</span>
            </td>
        </tr>
    </table>
    <br />
    <table class="TableBlock" align="center" width="70%">
        <form name="form2" method="post" action="import.php" enctype="multipart/form-data" onSubmit="return CheckForm2();">
            <input type="hidden" name="format_type" id="format_type" value="<?= $formatType ?>" />
            <input type="hidden" name="table_name" id="table_name" value="<?= $tableName ?>" />
            <input type="hidden" name="is_extra" id="is_extra" value="<?= $isExtra ?>" />
            
            <tr class="TableData" align="center" height="30">
                <td width="150" align="right">
                    <b>下载导入模板：</b>
                </td>
                <td width="400" align="left">
                    <a href="#" onClick="window.location='<?= COMMON_FILE_URL ?>/excel/templet/<?= $templetName ?>.xls'">导入模板下载</a>
                </td>
            </tr>
            <tr class="TableData" align="center" height="30">
                <td width="150" align="right">
                    <b>&nbsp;&nbsp;选择导入文件：</b>
                </td>
                <td align="left" width="400">
                    <input type="file" name="EXCEL_FILE" class="BigInput" size="30">
                    <input type="hidden" name="FILE_NAME">
                    <input type="hidden" name="GROUP_ID" value="<?= $GROUP_ID ?>">
                </td>
            </tr>
            <tr>
                <td nowrap class="TableControl" colspan="3" align="center">
                    <input type="submit" value="导入" class="BigButton">
                </td>
            </tr>
    </table>
    </form>
</body>
</html>