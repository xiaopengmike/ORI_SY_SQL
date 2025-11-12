<?php
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
include_once("inc/utility_org.php");
$HTML_PAGE_TITLE = _("数据列表");
include_once("inc/header.inc.php");
include_once("common/Common.php");
include_once("../common/DataModel.php");
include_once("lang.php");
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
                客户名称：
                <div class="layui-inline">
                    <input class="layui-input" name="customer_name" id="customer_name" autocomplete="off" style="width: 110px;">
                </div>
                <!-- 客户简称：
                <div class="layui-inline">
                    <input class="layui-input" name="short_name" id="short_name" autocomplete="off" style="width: 110px;">
                </div> -->
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
                url: 'get_customer_list.php?user_bu='+'<?= $user_bu ?>',
                cellMinWidth: 90,
                title: '<?=$LG_CUSTOMER_DATA['客户信息列表'][$_COOKIE['LANG']]?>',
                text: {
                    none: '<?=$LG_CUSTOMER_DATA['无客户'][$_COOKIE['LANG']]?>'
                },
                cols: [
                    [{
                        type: 'checkbox',
                        fixed: 'left'
                    }, {
                        field: 'customer_name',
                        title: '<?=$LG_CUSTOMER_DATA['客户名称'][$_COOKIE['LANG']]?>',
                        width: 150,
                        align: 'center'
                    }, {
                        field: 'short_name',
                        title: '<?=$LG_CUSTOMER_DATA['简称'][$_COOKIE['LANG']]?>',
                        align: 'center'
                    }, {
                        field: 'continentName',
                        title: '<?=$LG_CUSTOMER_DATA['大洲'][$_COOKIE['LANG']]?>',
                        align: 'center'
                    },  {
                        field: 'countryName',
                        title: '<?=$LG_CUSTOMER_DATA['国家'][$_COOKIE['LANG']]?>',
                        align: 'center'
                    }, {
                        field: 'user_bu',
                        align: 'center',
                        style:'display:none;',
                        hide: true
                    }, {
                        field: 'user_bu_name',
                        align: 'center',
                        style:'display:none;',
                        hide: true
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
                                customer_name: $('#customer_name').val(),
                                user_bu: '<?= $user_bu ?>'
                            }
                        }, 'data');
                    }
                };

            table.on('checkbox(tableList)', function(obj) {
                //是否选中，选中为true
                // console.log(obj.checked);
                //选中行的数据
                // console.log(obj.data);
                // 返回参数到原表单上
                parent.returnParentInfo(obj.data);
            });

            form.on('submit(reload)', function(data) {
                var type = $(this).data('type');
                active[type] ? active[type].call(this) : '';
            });
        });
    </script>

</body>

</html>