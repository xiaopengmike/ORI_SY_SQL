<?php
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
include_once("inc/utility_org.php");

$HTML_PAGE_TITLE = _("客户信息列表");
include_once("inc/header.inc.php");
include_once("common/Common.php");

?>
<html>

<head>
    <meta charset="utf-8">
    <meta name="renderer" content="webkit">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <script type="text/javascript" src="<?= COMMON_FILE_URL ?>/jquery.js"></script>
    <script type="text/javascript" src="<?= COMMON_FILE_URL ?>/layui/layui.js"></script>
    <link rel="stylesheet" type="text/css" href="<?= COMMON_FILE_URL ?>/layui/css/layui.css">
</head>

<body class="bodyColorNew">
    <div class="demoTable">
        搜索ID：
        <div class="layui-inline">
            <input class="layui-input" name="id" id="demoReload" autocomplete="off">
        </div>
        <button class="layui-btn" data-type="reload">搜索</button>
    </div>

    <table class="layui-hide" id="LAY_table_user" lay-filter="user"></table>

    <script>
        layui.use('table', function() {
            var table = layui.table;

// No.	大洲	国家	项目代号	相对方	协议编号	协议明细类别	协议类型	是否为独家合作协议	独家合作生效条件	生效时间	终止时间	签订时间	审批流程流水号	经办人	申请部门	是否存档	存档形式	附件	备注	管理部门

            //方法级渲染
            table.render({
                elem: '#LAY_table_user',
                url: 'demo/table/user/',
                cols: [
                    [{
                        checkbox: true,
                        fixed: true
                    }, {
                        field: '',
                        title: '序号',
                        width: 80,
                        type: 'numbers', // normal,checkbox,radio,numbers,space
                        LAY_CHECKED: true, // 是否全选状态
                        fixed: true, // 固定列,left（固定在左）、right（固定在右）
                        hide: false, // 是否初始隐藏列，默认：false
                    },{
                        field: 'id',
                        title: 'ID',
                        width: 80,
                        sort: true,
                        fixed: true
                    }, {
                        field: 'username',
                        title: '用户名',
                        width: 80
                    }, {
                        field: 'sex',
                        title: '性别',
                        width: 80,
                        sort: true
                    }, {
                        field: 'city',
                        title: '城市',
                        width: 80
                    }, {
                        field: 'sign',
                        title: '签名'
                    }, {
                        field: 'experience',
                        title: '积分',
                        sort: true,
                        width: 80
                    }, {
                        field: 'score',
                        title: '评分',
                        sort: true,
                        width: 80
                    }, {
                        field: 'classify',
                        title: '职业',
                        width: 80
                    }, {
                        field: 'wealth',
                        title: '财富',
                        sort: true,
                        width: 135
                    }]
                ],
                id: 'testReload',
                page: true,
                height: 'auto'
            });

            var $ = layui.$,
                active = {
                    reload: function() {
                        var demoReload = $('#demoReload');

                        //执行重载
                        table.reload('testReload', {
                            page: {
                                curr: 1 //重新从第 1 页开始
                            },
                            where: {
                                key: {
                                    id: demoReload.val()
                                }
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