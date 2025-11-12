<?php
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
include_once("inc/utility_org.php");
$HTML_PAGE_TITLE = _("数据列表");
include_once("inc/header.inc.php");
include_once("common/Common.php");
include_once("../DataModel.php");
$model = new DataModel();

$userId = $_SESSION['LOGIN_USER_ID'];
$deptId = $_SESSION['LOGIN_DEPT_ID'];
$selectParam = array(
    'is_used' => '1', // 可用参数
    'type' => 'project_star'
);
$selectArr = $model->getCommonParam($selectParam);

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
                项目编号：
                <div class="layui-inline">
                    <input class="layui-input" name="project_number" id="project_number" autocomplete="off" style="width: 110px;">
                </div>
                项目代号：
                <div class="layui-inline">
                    <input class="layui-input" name="project_code" id="project_code" autocomplete="off" style="width: 110px;">
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

            //方法级渲染
            var tableIns = table.render({
                elem: '#LAY_table_user',
                url: 'get_project_number.php',
                cellMinWidth: 90,
                title: '项目列表',
                cols: [
                    [{
                        type: 'checkbox',
                        fixed: 'left'
                    }, {
                        field: 'project_number',
                        title: '项目编号',
                        width: 120,
                        align: 'center'
                    }, {
                        field: 'customer_name',
                        title: '客户名称',
                        width: 120,
                        align: 'center'
                    }, {
                        field: 'project_name',
                        title: '项目代号',
                        width: 120,
                        align: 'center'
                    }, {
                        field: 'project_dept_name',
                        title: '部门',
                        align: 'center'
                    }]
                ],
                id: 'tableReload',
                page: true,
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
                            page: {
                                curr: 1 //重新从第 1 页开始
                            },
                            where: {
                                project_number: $('#project_number').val(),
                                project_code: $('#project_code').val(),
                                project_star: $('#project_star').val()
                            }
                        }, 'data');
                    }
                };

            table.on('checkbox(tableList)', function(obj) {
                // 返回参数到原表单上
                parent.returnProjectInfo(obj.data);
            });

            form.on('submit(reload)', function(data) {
                var type = $(this).data('type');
                active[type] ? active[type].call(this) : '';
            });
        });
    </script>

</body>

</html>