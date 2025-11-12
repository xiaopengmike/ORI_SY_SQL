<?php
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
include_once("inc/utility_org.php");
$HTML_PAGE_TITLE = _("项目组名单");
include_once("inc/header.inc.php");
include_once("common/Common.php");
require_once("../common/CommonMethodModel.php");
include_once("../common/UserModel.php");  // 公共类
$model = new UserModel();
$common = new CommonMethodModel();
$deptId = $_SESSION['LOGIN_DEPT_ID'];
$userId = $_SESSION['LOGIN_USER_ID'];
$companyNew = $model->getUserBu($deptId);  // 取用户公司归属
$systemId = "project_team_add";
if (!empty($type)) $systemId = $type;
$userdata = $model->getAdminDataQuery($userId, $deptId, $systemId);
$isAdmin = $userdata['isAdmin'];
$systemAdmin = $userdata['systemAdmin'];
$user_ids = $data['userIds'];
$dept_ids = $data['deptIds'];
$companyNew = $model->getUserBu($deptId);
?>

<head>
    <meta charset="utf-8">
    <meta name="renderer" content="webkit">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <script type="text/javascript" src="<?= COMMON_FILE_URL ?>/jquery-1.11.3.min.js"></script>
    <script type="text/javascript" src="<?= COMMON_FILE_URL ?>/common.js"></script>
    <script type="text/javascript" src="<?= COMMON_FILE_URL ?>/layui/layui.js"></script>
    <link rel="stylesheet" type="text/css" href="<?= COMMON_FILE_URL ?>/layui/css/layui.css">
    <link rel="stylesheet" type="text/css" href="<?= COMMON_FILE_URL ?>/common.css">
    <script language="Javascript" type="text/javascript" src="../list.js"></script>
</head>
<style>
    .drop-btn {
        cursor: pointer;
    }

    .dropdown {
        position: relative;
        display: inline-block;
    }

    .dropdown-content {
        display: none;
        position: absolute;
        z-index: 9999;
        background-color: lightgray;
        min-width: 154px;
        box-shadow: 0px 8px 16px 0px rgba(0, 0, 0, 0.2);
    }

    .dropdown-content a {
        color: black;
        padding: 12px 16px;
        text-decoration: none;
        display: block;
    }

    .dropdown-content a:hover {
        background-color: #027AFF;
    }

    .dropdown:hover .dropdown-content {
        display: block;
    }
</style>
<script>
    $(function() {
        layui.use(['form', 'layer'], function() {
            var layer = layui.layer,
                form = layui.form;
            $.loadList('1');
            form.render();
        });
    });
</script>

<body class="bodyColorNew">
    <form id="tab" class="layui-form">
        <input type="hidden" id="sort_way" name="sort_way" value="" />
        <input type="hidden" id="sort_field" name="sort_field" value="" />
        <input type="hidden" id="page_size" name="page_size" value="50" />
        <table style="margin: 1%">
            <tr>
            <td align="right">项目组</td>
                <td>
                    <input type="text" name="team_name" id="team_name" class="layui-input">
                </td>
                <td align="right">单号</td>
                <td>
                    <input type="text" name="form_id" id="form_id" class="layui-input">
                </td>
               
                <td>
                    <button class="layui-btn layui-btn-oa layui-btn-sm" onclick="$.loadList('1');return false;" style="margin-left: 10px;">查询</button>
                </td>
            </tr>
        </table>
    </form>
    <div style="text-align: right;width: 98%;margin:auto;">
        <?if($isAdmin=='1'):?>
        <div style="float: left;">
            <button class="layui-btn layui-btn-oa layui-btn-sm" onclick="pageOperate('add.php','add');return false;">新建</button>
        </div>
        <?endif;?>
        <?= $common->page_bar($param['page'], $count, $param['page_size'], '$.loadList(\'%s\')', '1') ?>
    </div>
    <div id="tableList" style="width:100%;text-align:center;"></div>
</body>

</html>