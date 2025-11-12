<?php
include_once("inc/auth.inc.php");
$HTML_PAGE_TITLE = _("变更历史记录");
require_once("inc/header.inc.php");
require_once("common/Common.php");
require_once("/MYOA/webroot/general/customer_relationship/common/FormModel.php");

// 获取历史记录数据 表：system_id + _history
$history = array();
$form = new FormModel();
$param = array(
    'main_table' => 'inhe_project_main_history',
    'form_id' => $formId,
);
$history = $form->findChangeHistory($param);

?>

<head>
    <meta charset="UTF-8">
    <title>变更历史记录</title>
    <meta name="renderer" content="webkit">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=0">
    <script language="Javascript" type="text/javascript" src="<?= COMMON_FILE_URL ?>/jquery-1.11.3.min.js"></script> <!-- 引入新版本jquery -->
    <script language="Javascript" type="text/javascript" src="<?= COMMON_FILE_URL ?>/common.js"></script>
    <script language="Javascript" type="text/javascript" src="<?= COMMON_FILE_URL ?>/layui/layui.js"></script>
    <link rel="stylesheet" type="text/css" href="<?= COMMON_FILE_URL ?>/layui/css/layui.css">
    <link rel="stylesheet" type="text/css" href="<?= COMMON_FILE_URL ?>/common.css">
    <!-- 让IE8/9支持媒体查询，从而兼容栅格 -->
    <!--[if lt IE 9]>
        <script src="<?= COMMON_FILE_URL ?>/layui/html5shiv.min.js"></script>
        <script src="<?= COMMON_FILE_URL ?>/layui/respond.min.js"></script>
    <![endif]-->
</head>

<body>
    <div class="weadmin-body">
        <form id="form" class="layui-form">
            <table class="layui-table" border="0" cellpadding="0" cellspacing="10" lay-size="sm" lay-even>
                <thead>
                    <tr class="layui-bg-head layui-bg-gray">
                        <td colspan="7">
                            <span style="float:left;">变更历史记录</span>
                        </td>
                    </tr>
                    <tr class="layui-bg-gray">
                        <td align="center">序号</td>
                        <td align="center">流程单号</td>
                        <td align="center">项目代号</td>
                        <td align="center">创建者</td>
                        <td align="center">创建时间</td>
                        <td align="center">操作</td>
                    </tr>
                </thead>
                <tbody>
                    <?php $line = 0;
                    foreach ($history as $k => $val) : $line++; ?>
                        <tr>
                            <td nowrap align="center"><?= $line ?></td>
                            <td align="center"><?= $val["form_id"] ?></td>
                            <td align="center"><?= $val["project_code"] ?></td>
                            <td align="center"><?= $val["user_id"] ?></td>
                            <td align="center"><?= $val["write_time"] ?></td>
                            <td align="center">
                                <a href="javascript:void(0);" class="newColor" onclick="pageOperate('detail.php?id=<?= $val[form_id] ?>&operate=detail','detail');return false;">查看</a>&nbsp;
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
                <tr class="layui-bg-body">
                    <td colspan="7" align="center">
                        <input value="关闭" type=button class="layui-btn layui-btn-oa layui-btn-sm" onclick="closeIframe();" />
                    </td>
                </tr>
            </table>
        </form>
    </div>
</body>

</html>