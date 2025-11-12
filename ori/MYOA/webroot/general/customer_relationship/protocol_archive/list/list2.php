<?php
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
include_once("inc/utility_org.php");

$HTML_PAGE_TITLE = _("数据列表");
include_once("inc/header.inc.php");
include_once("common/Common.php");
include_once("/MYOA/webroot/general/customer_relationship/common/CommonControl.php");  // 公共类

$model = new CommonControlModel();

$userId = $_SESSION['LOGIN_USER_ID'];

// 数据权限
$deptId = $_SESSION['LOGIN_DEPT_ID'];
$systemId = 'sales_contract';
$data = $model->getAdminDataQuery($userId, $deptId, $systemId);
$isAdmin = $data['isAdmin'];
$systemAdmin = $data['systemAdmin'];

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
            项目名称：
            <div class="layui-inline">
                <input class="layui-input" name="project_name" id="project_name" autocomplete="off">
            </div>
            项目编号：
            <div class="layui-inline">
                <input class="layui-input" name="project_no" id="project_no" autocomplete="off">
            </div>
            合同编号：
            <div class="layui-inline">
                <input class="layui-input" name="contract_no" id="contract_no" autocomplete="off">
            </div>
            <button class="layui-btn" data-type="reload" id="reload">搜索</button>
        </div>
        <!--数据表格-->
        <table class="layui-hide" id="LAY_table_user" lay-filter="tableList"></table>
    </div>
    <script type="text/html" id="toolbarDemo">
    {{# if(!ifEmpty(<?= $systemAdmin ?>)){ }}
        <div class="layui-btn-container">
            <button class="layui-btn layui-btn-sm" lay-event="addData">添加</button>
            <button class="layui-btn layui-btn-sm" lay-event="authManage">权限管理</button>
        </div>
        {{# } }}
    </script>
    <script type="text/html" id="barDemo">
        <a class="layui-btn layui-btn-xs" lay-event="detail">查看</a>
        {{# if(d.user_id == '<?= $userId ?>' || '<?= empty($systemAdmin)?FALSE:$systemAdmin ?>'){ }}
            <a class="layui-btn layui-btn-xs" lay-event="modify">编辑</a>
            {{# if(d.user_id == '<?= $userId ?>'){ }}
                <a class="layui-btn layui-btn-danger layui-btn-xs" lay-event="delete">删除</a>
                {{# } }}
                    {{# } }}
    </script>
    <script>
        layui.use('table', function() {
            var table = layui.table;
            //方法级渲染
            var tableIns = table.render({
                elem: '#LAY_table_user',
                url: '../data/get_data2.php',
                cellMinWidth: 90 //全局定义常规单元格的最小宽度
                    ,
                toolbar: '#toolbarDemo' //开启工具栏，此处显示默认图标，可以自定义模板，详见文档
                    ,
                defaultToolbar: ['filter', 'exports'],
                title: '销售合同登记表',
                cols: [
                    [
                        // {type: 'checkbox', fixed: 'left'},
                        {
                            field: 'serial_number',
                            title: '序号',
                            width: 60,
                            align: 'center',
                            fixed: true
                        }, {
                            field: 'sign_date',
                            title: '合同签署时间',
                            align: 'center'
                        }, {
                            field: 'contract_category_desc',
                            title: '合同类别',
                            align: 'center'
                        }, {
                            field: 'project_name',
                            title: '项目名称',
                            align: 'center'
                        }, {
                            field: 'project_no',
                            title: '项目编号'
                        }, {
                            field: 'contract_no',
                            title: '合同编号'
                        }, {
                            field: 'currency',
                            title: '币种'
                        }, {
                            field: 'amount',
                            title: '合同金额'
                        }, {
                            field: 'remark',
                            title: '备注',
                            align: 'center'
                        }, {
                            field: 'foreign_attach',
                            title: '外文附件',
                            templet: foreignAttachLink,
                            align: 'center'
                        }, {
                            field: 'chinese_attach',
                            title: '中文翻译件',
                            templet: chineseAttachLink,
                            align: 'center'
                        }, {
                            field: 'customer_name',
                            title: '客户名称',
                            width: 130
                        }, {
                            field: 'contract_party',
                            title: '合同相对方',
                            width: 130
                        }, {
                            field: 'business_belong',
                            title: '业务归属',
                            width: 150
                        }, {
                            field: 'create_user_name',
                            title: '制单人',
                            align: 'center',
                            width: 100
                        }, {
                            field: 'dept_name',
                            title: '部门',
                            align: 'center'
                        }, {
                            field: 'manage_dept_desc',
                            title: '管理部门',
                            align: 'center',
                            width: 120
                        }, {
                            field: 'user_id',
                            title: '操作',
                            fixed: 'right',
                            width: 160,
                            align: 'center',
                            toolbar: '#barDemo'
                        } //这里的toolbar值是模板元素的选择器
                    ]
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
                                project_name: $('#project_name').val(),
                                project_no: $('#project_no').val(),
                                contract_no: $('#contract_no').val()
                            }
                        }, 'data');
                    }
                };

            $('.demoTable .layui-btn').on('click', function() {
                var type = $(this).data('type');
                active[type] ? active[type].call(this) : '';
            });

            table.on('toolbar(tableList)', function(obj) {
                switch (obj.event) {
                    case 'addData':
                        // 新增一条数据
                        var index = layui.layer.open({
                            title: "新增",
                            type: 2,
                            content: "../add/add2.php",
                            success: function(layero, index) {
                                setTimeout(function() {
                                    layui.layer.tips('点击此处返回列表', '.layui-layer-setwin .layui-layer-close', {
                                        tips: 3
                                    });
                                }, 500)
                            }
                        });
                        layui.layer.full(index);
                        break;
                    case 'authManage':
                        // 权限管理
                        var index = layui.layer.open({
                            title: "权限管理",
                            type: 2,
                            content: "auth_list.php?type=<?= $systemId ?>",
                            success: function(layero, index) {
                                setTimeout(function() {
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

            table.on('tool(tableList)', function(obj) {
                var data = obj.data;
                switch (obj.event) {
                    case 'delete':
                        // 注销
                        deleteData = data;
                        layer.confirm('确定要删除当前记录吗？', {
                            btn: ['确定', '取消']
                        }, function() {
                            var url = '../action.php?action=Delete&id=' + deleteData.id + '&tableName=inhe_sales_contract';
                            $.post(url, {}, function(data) {
                                var jsonOb = eval("(" + data + ")");
                                if (jsonOb.status == "success") {
                                    layer.alert("删除成功", {
                                        icon: 1
                                    }, function() {
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
                    case 'detail':
                        // 详情
                        infoData = data;
                        var index = layui.layer.open({
                            title: "详情",
                            type: 2,
                            content: "../detail/detail2.php?id=" + infoData.id,
                            success: function(layero, index) {
                                setTimeout(function() {
                                    layui.layer.tips('点击此处返回列表', '.layui-layer-setwin .layui-layer-close', {
                                        tips: 3
                                    });
                                }, 500)
                            }
                        });
                        layui.layer.full(index);
                        break;
                    case 'modify':
                        // 详情
                        infoData = data;
                        var index = layui.layer.open({
                            title: " 编辑数据",
                            type: 2,
                            content: "../modify/modify2.php?id=" + infoData.id,
                            success: function(layero, index) {
                                setTimeout(function() {
                                    layui.layer.tips('点击此处返回列表', '.layui-layer-setwin .layui-layer-close', {
                                        tips: 3
                                    });
                                }, 500)
                            }
                        });
                        layui.layer.full(index);
                        break;
                }
            });
        });

        function foreignAttachLink(d) {
            var attachName = d.foreign_attach;
            if ('' == attachName || null == attachName || undefined == attachName) {
                return '';
            }
            if (attachName.length > 0) {
                var attachUrl = '<a class="layui-blue" href="javascript:void(0);" lay-event="file" onclick="openFullModel(\'/attachment/reportshop/attachment/' +
                    d.foreign_attach + '\');return false;">附件下载</a>';
                return attachUrl;
            }
        }

        function chineseAttachLink(d) {
            var attachName = d.chinese_attach;
            if ('' == attachName || null == attachName || undefined == attachName) {
                return '';
            }
            if (attachName.length > 0) {
                var attachUrl = '<a class="layui-blue" href="javascript:void(0);" lay-event="file" onclick="openFullModel(\'/attachment/reportshop/attachment/' +
                    d.chinese_attach + '\');return false;">附件下载</a>';
                return attachUrl;
            }
        }
    </script>

</body>

</html>