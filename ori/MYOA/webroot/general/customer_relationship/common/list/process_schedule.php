<?php
// 展示流程审核进度
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
include_once("inc/utility_org.php");

$HTML_PAGE_TITLE = "流程进度查阅";
include_once("inc/header.inc.php");
include_once("common/Common.php");
include_once("../SystemEnum.php");

if (empty($id)) {
    Message("提示", "对应数据不存在！");
    exit;
}

$flowSql = " select k.*,u.user_name approveName,uu.user_name prevName from inhe_flow_opinion k " .
    " left join user u on u.user_id = k.approve_user " .
    " left join user uu on uu.user_id = k.prev_writer " .
    " where form_id = '" . $id . "' order by ISNULL(approve_time),approve_time ='',approve_time ";
$res = exequery(TD::conn(), $flowSql);
while ($item = mysql_fetch_assoc($res)) {
    $flowArr[] = $item;
}
mysql_free_result($res);

?>

<head>
    <meta charset="UTF-8">
    <meta name="renderer" content="webkit">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=0">
    <script type="text/javascript" src="/inc/js_lang.php"></script>
    <script src="<?= MYOA_JS_SERVER ?>/static/js/module.js"></script>
    <script language="Javascript" type="text/javascript" src="<?= COMMON_FILE_URL ?>/jquery-1.11.3.min.js"></script>
    <script language="Javascript" type="text/javascript" src="<?= COMMON_FILE_URL ?>/common.js"></script>
    <script language="Javascript" type="text/javascript" src="<?= COMMON_FILE_URL ?>/layui/layui.js" charset="utf-8"></script>
    <script language="Javascript" type="text/javascript" src="main.js"></script>
    <link rel="stylesheet" href="<?= COMMON_FILE_URL ?>/common.css">
    <link rel="stylesheet" href="<?= COMMON_FILE_URL ?>/css/font.css">
    <link rel="stylesheet" href="<?= COMMON_FILE_URL ?>/css/weadmin.css">
</head>

<body>
    <div class="weadmin-body" style="width:90%;margin:1% 4%;">
        <form id="mainForm" class="layui-form">
            <input type="hidden" name="form_id" id="form_id" value="<?= $formId ?>" />
            <table class="TableBlock" width="100%" style="text-align: center;">
                <thead>
                    <tr>
                        <td class="TableHeader" colspan="6" style="text-align: left;">流程进度</td>
                    </tr>
                    <tr>
                        <td class="TableContent">流程步骤</td>
                        <td class="TableContent" width="12%">状态</td>
                        <td class="TableContent" width="15%">办理人</td>
                        <td class="TableContent" width="18%">办理时间</td>
                        <td class="TableContent" width="15%">交办人</td>
                        <td class="TableContent" width="18%">交办时间</td>
                    </tr>
                </thead>
                <tbody id="tab_list">
                    <?php foreach ($flowArr as $k => $fv) : ?>
                        <tr>
                            <td nowrap class="TableData"><?= $fv['work_flow'] ?></td>
                            <td nowrap class="TableData">
                                <font class="<?= SystemEnum::$STATUS_CSS[$fv["status"]] ?>"><?= SystemEnum::$STATUS[$fv["status"]] ?></font>
                            </td>
                            <td nowrap class="TableData"><?= $fv['approveName'] ?></td>
                            <td nowrap class="TableData"><?= $fv['approve_time'] ?></td>
                            <td nowrap class="TableData"><?= $fv['prevName'] ?></td>
                            <td nowrap class="TableData"><?= $fv['prev_time'] ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <div style="text-align: center;margin:1%">
                <input name="btn_close" value="关闭" type=button actionType="Close" class="layui-btn layui-btn-oa layui-btn-sm" onclick="closeIframe();return false;" />
            </div>
        </form>
    </div>
</body>

</html>