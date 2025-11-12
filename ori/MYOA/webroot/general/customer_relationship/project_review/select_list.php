<?php
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
include_once("inc/utility_org.php");
$HTML_PAGE_TITLE = _("客户列表");
include_once("inc/header.inc.php");
include_once("common/Common.php");
include_once("/MYOA/webroot/general/customer_relationship/common/UserModel.php");  // 公共类
$model = new UserModel();
$userId = $_SESSION['LOGIN_USER_ID'];
// 数据权限
$deptId = $_SESSION['LOGIN_DEPT_ID'];
$systemId = 'project_code';
$data = $model->getAdminDataQuery($userId, $deptId, $systemId);
$isAdmin = $data['isAdmin'];
$systemAdmin = $data['systemAdmin'];

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
</head>

<body style="background-color: white;">
    <div style="margin:1%;width: 60%">
        <div class="demoTable" style="margin:5px;">
            项目代号：
            <div class="layui-inline">
                <input class="layui-input" name="project_code" id="project_code" autocomplete="off">
            </div>
            <button class="layui-btn" data-type="reload" id="reload">搜索</button>
        </div>
        <!--数据表格-->
        <table class="layui-hide" id="LAY_table_user" lay-filter="tableList"></table>
    </div>
    <script>
        layui.use('table', function() {
            var table = layui.table;
            //方法级渲染
            var tableIns = table.render({
                elem: '#LAY_table_user',
                url: 'get_data.php',
                cellMinWidth: 90,
                title: '项目代号列表',
                cols: [
                    [{
                        type: 'numbers',
                        title: '序号',
                        width: 60,
                        align: 'center',
                        fixed: true
                    }, {
                        field: 'project_code',
                        title: '项目代号',
                        align: 'center'
                    }, {
                        field: 'company',
                        title: '项目归属',
                        align: 'center'
                    }, {
                        field: 'project_star_desc',
                        title: '项目星级',
                        align: 'center'
                    }, {
                        field: 'star_icon',
                        title: '项目星级图标',
                        align: 'center'
                    }]
                ],
                id: 'testReload',
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
                        table.reload('testReload', {
                            page: {
                                curr: 1 //重新从第 1 页开始
                            },
                            where: {
                                project_code: $('#project_code').val()
                            }
                        }, 'data');
                    }
                };

            $('.demoTable .layui-btn').on('click', function() {
                var type = $(this).data('type');
                active[type] ? active[type].call(this) : '';
            });
        });
    </script>

</body>

</html>