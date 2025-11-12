<?php
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
include_once("inc/utility_org.php");

$HTML_PAGE_TITLE = _("数据列表");
include_once("inc/header.inc.php");
include_once("common/Common.php");
include_once("/MYOA/webroot/general/customer_relationship/common/CommonControl.php");

$userId = $_SESSION['LOGIN_USER_ID'];
$deptId = $_SESSION['LOGIN_DEPT_ID'];

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
            <input type="hidden" id="type" name="type" value="<?= $type ?>" />
            <!-- <div class="demoTable" style="margin:5px;">
                项目编号：
                <div class="layui-inline">
                    <input class="layui-input" name="project_number" id="project_number" autocomplete="off" style="width: 110px;">
                </div>
                订单号：
                <div class="layui-inline">
                    <input class="layui-input" value="<?=$form_id?>" name="form_id" id="form_id" autocomplete="off" style="width: 110px;">
                </div>
                <input lay-submit lay-filter="reload" id="reload" data-type="reload" value="查询" type=button class="layui-btn" />
            </div> -->
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
                url: 'get_product_data.php?type=<?= $type ?>&form_id=<?= $form_id ?>',
                title: '项目列表',
                cols: [
                    [{
                        type: 'checkbox',
                        fixed: 'left'
                    }, {
                        field: 'name',
                        title: '名称',
                        width: 170,
                        align: 'center'
                    }, {
                        field: 'specification',
                        title: '规格',
                        width: 120,
                        align: 'center'
                    }, {
                        field: 'number',
                        title: '数量',
                        width: 90,
                        align: 'center'
                    }, {
                        field: 'price',
                        title: '单价',
                        width: 90,
                        align: 'center'
                    }, {
                        field: 'project_name',
                        title: '项目名称',
                        width: 90,
                        align: 'center'
                    }, {
                        field: 'project_number',
                        title: '项目编号',
                        width: 90,
                        align: 'center'
                    }, {
                        field: 'form_id',
                        width: 170,
                        title: '订单号',
                        align: 'center'
                    }, {
                        field: 'license_product',
                        title: '授权产品',
                        hide:true
                    }, {
                        field: 'license_years',
                        title: '授权年限',
                        hide:true
                    }, {
                        field: 'license_type',
                        title: '授权方式',
                        hide:true
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
                                type: $('#type').val(),
                                project_number: $('#project_number').val(),
                                form_id: $('#form_id').val()
                            }
                        }, 'data');
                    }
                };

            table.on('checkbox(tableList)', function(obj) {
                // 返回参数到原表单上
                var selectData = layui.table.checkStatus('tableReload').data;
                parent.returnProductInfo(selectData, '<?= $domId ?>');
                // parent.returnProductInfo(obj.data, '<?= $domId ?>');
            });

            form.on('submit(reload)', function(data) {
                var type = $(this).data('type');
                active[type] ? active[type].call(this) : '';
            });
        });
    </script>

</body>

</html>