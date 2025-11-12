<?php
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
include_once("inc/utility_org.php");
include_once("lang.php");
$HTML_PAGE_TITLE = "流程结束";
include_once("inc/header.inc.php");
include_once("common/Common.php");
?>

<head>
    <meta charset="UTF-8">
    <meta name="renderer" content="webkit">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=0">
    <script language="Javascript" type="text/javascript" src="<?= COMMON_FILE_URL ?>/jquery-1.11.3.min.js"></script>
    <script language="Javascript" type="text/javascript" src="<?= COMMON_FILE_URL ?>/layui/layui.js" charset="utf-8"></script>
    <link rel="stylesheet" type="text/css" href="<?= COMMON_FILE_URL ?>/layui/css/layui.css">
    <link rel="stylesheet" href="<?= COMMON_FILE_URL ?>/css/font.css">
    <link rel="stylesheet" href="<?= COMMON_FILE_URL ?>/css/weadmin.css">
</head>

<body>
    <form id="mainForm" class="layui-form" style="text-align: center;">
        <fieldset class="layui-elem-field layui-field-title" style="margin-top: 50px;">
            <legend>流程结束</legend>
        </fieldset>
        <div class="layui-form-item">
            <div style="text-align: center;">
                <input name="btn_close" value="<?=$LG_CUSTOMER_DATA['关闭'][$_COOKIE['LANG']]?>" type=button class="layui-btn layui-btn-oa layui-btn-sm" onclick="closeDialog();return false" />
            </div>
        </div>
    </form>
</body>
<script type="text/javascript">
    $(function() {
        layui.use(['form', 'layer'], function() {
            var layer = layui.layer,
                form = layui.form;
            // closeDialog();
        });
    });

    function closeDialog() {
        var index = parent.layer.getFrameIndex(window.name);
        parent.layer.close(index);
        parent.closeIframe();
        parent.opener.location.reload();
    }
</script>

</html>