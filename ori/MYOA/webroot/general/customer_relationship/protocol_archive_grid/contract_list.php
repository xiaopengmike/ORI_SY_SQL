<?php
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
include_once("inc/utility_org.php");

$HTML_PAGE_TITLE = _("数据列表");
include_once("inc/header.inc.php");
include_once("common/Common.php");
require_once("../common/DataModel.php");
$dataModel = new DataModel();

$userId = $_SESSION['LOGIN_USER_ID'];
$deptId = $_SESSION['LOGIN_DEPT_ID'];
$selectParam = array(
    'is_used' => '1', // 可用参数
    'type' => 'project_star'
);
$selectArr = $dataModel->getCommonParam($selectParam);
?>

<head>
    <meta charset="utf-8">
    <meta name="renderer" content="webkit">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <script type="text/javascript" src="<?= COMMON_FILE_URL ?>/jquery-1.11.3.min.js"></script>
    <script type="text/javascript" src="<?= COMMON_FILE_URL ?>/common.js"></script>
    <script type="text/javascript" src="<?= COMMON_FILE_URL ?>/layui/layui.js"></script>
    <link rel="stylesheet" href="<?= COMMON_FILE_URL ?>/css/font.css">
    <link rel="stylesheet" href="<?= COMMON_FILE_URL ?>/css/weadmin.css">
</head>

<body style="background-color: white;">
    <div style="margin:1%">
        <form class="layui-form">
            <div class="demoTable" style="margin:5px;">
                <!-- 订单号：
                <div class="layui-inline">
                    <input class="layui-input" name="form_id" id="form_id" autocomplete="off" style="width: 110px;">
                </div>
                项目名称：
                <div class="layui-inline">
                    <input class="layui-input" name="project_name" id="project_name" autocomplete="off" style="width: 110px;">
                </div> -->
                项目编号：
                <div class="layui-inline">
                    <input class="layui-input" name="project_number" id="project_number" value="<?=$project_number?>" autocomplete="off" style="width: 110px;">
                </div>
                <input lay-submit lay-filter="reload" id="reload" data-type="reload" value="查询" type=button class="layui-btn" />
            </div>
        </form>
        <!--数据表格-->
        <table class="layui-hide" id="LAY_table_user" lay-filter="tableList"></table>
    </div>
    <script>
        layui.use(['form', 'table'], function() {
            var table = layui.table;
            var form = layui.form;
            var $ = layui.$;
            //方法级渲染
            var tableIns = table.render({
                elem: '#LAY_table_user',
                url: 'get_contract_data.php',
                where: {project_number: $("#project_number").val()},
                cellMinWidth: 90,
                title: '客户名称列表',
                cols: [
                    [{
                        type: 'checkbox',
                        fixed: 'left'
                    }, {
                        field: 'customer_name',
                        title: '客户名称',
                        width: 120,
                        align: 'center'
                    }, {
                        field: 'project_name',
                        title: '项目名称',
                        width: 120,
                        align: 'center'
                    }, {
                        field: 'project_number',
                        title: '项目编号',
                        width: 120,
                        align: 'center'
                    }, {
                        field: 'form_id',
                        title: '订单号',
                        align: 'center'
                    }, {
                        field: 'contract_party',
                        title: '合同相对方',
                        align: 'center'
                    }]
                ],
                id: 'tableReload',
                page: false,
                height: 'auto',
                done: function(res, curr, count) {
                    exportData = res.data;
                }
            });

            var $ = layui.$,
                active = {
                    reload: function() {
                        //执行重载
                        table.reload('tableReload', {
                            page:false,
                            where: {
                                project_number: $("#project_number").val()
                            }
                        }, 'data');
                    }
                };

            table.on('checkbox(tableList)', function(obj) {
                // 返回参数到原表单上
                parent.returnProductData(obj.data);
            });

            form.on('submit(reload)', function(data) {
                var type = $(this).data('type');
                active[type] ? active[type].call(this) : '';
            });
        });
    </script>

</body>

</html>