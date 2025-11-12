<?php
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
include_once("inc/utility_org.php");
$HTML_PAGE_TITLE = _("项目进度登记表");
include_once("inc/header.inc.php");
include_once("common/Common.php");
include_once("../common/UserModel.php");  // 公共类
require_once("../common/DataModel.php");
$dataModel = new DataModel();
$model = new UserModel();
$deptId = $_SESSION['LOGIN_DEPT_ID'];
$companyNew = $model->getUserBu($deptId);  // 取用户公司归属


$selectParam = array(
    'is_used' => '1', // 可用参数
    'type' => 'company,project_schedule_nodes',
);
$selectArr = $dataModel->getCommonParam($selectParam);
$company = array();
foreach ($selectArr['company'] as $cc) {
    $company[$cc['paras_value']] = $cc['paras_desc'];
}

$project_schedule_nodes = array();
foreach ($selectArr['project_schedule_nodes'] as $v) {
    $project_schedule_nodes[$v['paras_value']] = $v['paras_desc'];
}

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
    function ForbiddenData(url) {
    // if (confirm("确定要删除该记录吗？删除后将不可恢复！")){
    layer.confirm("确定禁用/启用该数据吗？", {
        icon: 3,
        title: '提示'
    }, function (index) {
        layer.close(index);
        $.post(url, {}, function (data) {
            var jsonOb = eval("(" + data + ")");
            if (jsonOb.status == "success") {
                layer.alert("操作成功", {
                    icon: 1
                }, function () {
                    parent.location.reload();
                    layer.closeAll();
                });
            } else {
                layer.alert(jsonOb.msg, {
                    icon: 5
                });
            }
        });
    });
    // }
}
</script>

<body class="bodyColorNew">
    <form id="tab" class="layui-form">
        <input type="hidden" id="sort_way" name="sort_way" value="" />
        <input type="hidden" id="sort_field" name="sort_field" value="" />
        <input type="hidden" id="page_size" name="page_size" value="10" />
        <table style="margin: 1%">
            <tr>
               
                <td align="right">区域</td>
                <td>
                    <input type="text" name="name" id="name" class="layui-input">
                </td>
                <td align="right">业务负责人</td>
                <td>
                    <input type="text" name="business_user_name" id="business_user_name" class="layui-input">
                </td>
                <td align="right">交付经理</td>
                <td>
                    <input type="text" name="delivery_user_name" id="delivery_user_name" class="layui-input">
                </td>
                
                <td>
                    <button class="layui-btn layui-btn-oa layui-btn-sm" onclick="$.loadList('1');return false;" style="margin-left: 10px;">查询</button>
                </td>
            </tr>
        </table>
    </form>
    <div id="tableList" style="width:100%;text-align:center;"></div>
</body>

</html>