<?php
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
include_once("inc/utility_org.php");

$HTML_PAGE_TITLE = _("客户信息列表");
include_once("inc/header.inc.php");
include_once("common/Common.php");
include_once("/MYOA/webroot/general/customer_relationship/common/CommonControl.php");  // 公共类

$model = new CommonControlModel();

$userId = $_SESSION['LOGIN_USER_ID'];

?>
<html>

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
    <div style="margin:1%">
        <div class="demoTable" style="margin:5px;">
            员工姓名：
            <div class="layui-inline">
                <input class="layui-input" name="user_name" id="user_name" autocomplete="off">
            </div>
            <button class="layui-btn" data-type="reload" id="reload">搜索</button>
        </div>
        <!--数据表格-->
        <table class="layui-hide" id="LAY_table_user" lay-filter="tableList" ></table>
    </div>
    <script type="text/html" id="toolbarDemo">
        <div class="layui-btn-container">
            <button class="layui-btn layui-btn-sm" lay-event="addData">添加</button>
        </div>
    </script>
    <script type="text/html" id="barDemo">
        <a class="layui-btn layui-btn-danger layui-btn-xs" lay-event="delete">删除</a>
    </script>
    <script>
        layui.use('table', function() {
            var table = layui.table;
            //方法级渲染
            var tableIns = table.render({
                elem: '#LAY_table_user'
                ,url: '../data/get_auth_data.php?type=<?= $type ?>'
                ,cellMinWidth: 90 //全局定义常规单元格的最小宽度
                ,toolbar: '#toolbarDemo' //开启工具栏，此处显示默认图标，可以自定义模板，详见文档
                ,defaultToolbar: ['filter', 'exports']
                ,title: '权限管理表'
                ,cols: [[
                        // {type: 'checkbox', fixed: 'left'},dept_name
                        {field: 'useName',title: '姓名',width: 170,align: 'center',fixed: true}
                        ,{field: 'deptName',title: '部门',width: 340,align: 'center'}
                        ,{field: 'manage_dept_desc',title: '管理部门',align: 'center'}
                        // ,{field: 'manage_dept_desc',title: '管理部门',align: 'center',width: 120}
                        ,{field: '',title: '操作',fixed: 'right', width: 160, align:'center', toolbar: '#barDemo'} //这里的toolbar值是模板元素的选择器
                    ]]
                ,id: 'testReload'
                ,page: true
                ,height: 'auto'
                ,done: function (res, curr, count) {
                    exportData=res.data;
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
                                user_name: $('#user_name').val()
                            }
                        }, 'data');
                    }
                };

            $('.demoTable .layui-btn').on('click', function() {
                var type = $(this).data('type');
                active[type] ? active[type].call(this) : '';
            });

            table.on('toolbar(tableList)', function (obj) {
                switch(obj.event){
                    case 'addData':
                        // 新增一条数据
                        var index = layui.layer.open({
                            title: "新增管理部门权限",
                            type: 2,
                            content: '../add/add_auth.php?type=<?= $type ?>',
                            success: function (layero, index) {
                                setTimeout(function () {
                                    layui.layer.tips('点击此处返回列表', '.layui-layer-setwin .layui-layer-close', {
                                        tips: 3
                                    });
                                }, 500)
                            }
                        });
                        layui.layer.full(index);
                    break;
                };
            });

            table.on('tool(tableList)', function (obj) {
                var data = obj.data;
                switch(obj.event){
                    case 'delete':
                        // 注销
                        deleteData = data;
                        layer.confirm('确定要删除当前记录吗？', {
                            btn: ['确定', '取消']
                        }, function () {
                            var url = '../action.php?action=Delete&id='+deleteData.id+'&tableName=inhe_system_admin';
                            $.post(url, {}, function (data) {
                                var jsonOb = eval("(" + data + ")");
                                if (jsonOb.status == "success") {
                                    layer.alert("删除成功", {
                                        icon: 1
                                    }, function () {
                                        $("#reload").click();
                                        layer.closeAll();
                                    });
                                } else {
                                    layer.alert(jsonOb.msg, {
                                        icon: 5
                                    });
                                }
                            });
                        });
                    break;
                } 
            });
        });
    </script>

</body>

</html>