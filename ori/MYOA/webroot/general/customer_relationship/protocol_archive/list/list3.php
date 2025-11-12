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
$systemId = 'authorization_letter';
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
            项目代号：
            <div class="layui-inline">
                <input class="layui-input" name="project_code" id="project_code" autocomplete="off">
            </div>
            项目所在国：
            <div class="layui-inline">
                <input class="layui-input" name="project_country" id="project_country" autocomplete="off">
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
                url: '../data/get_data3.php',
                cellMinWidth: 90 //全局定义常规单元格的最小宽度
                    ,
                toolbar: '#toolbarDemo' //开启工具栏，此处显示默认图标，可以自定义模板，详见文档
                    ,
                defaultToolbar: ['filter', 'exports'],
                title: '保函登记表',
                cols: [
                    [
                        // {type: 'checkbox', fixed: 'left'},
                        // {field: 'serial_number',title: '序号',width: 60,align: 'center',fixed: true},
                        {
                            field: 'project_code',
                            title: '项目代号',
                            align: 'center',
                            fixed: true
                        }, {
                            field: 'bid_type',
                            title: '保函类型',
                            align: 'center'
                        }, {
                            field: 'opening_time',
                            title: '保函开立/延期次数'
                        }, {
                            field: 'status',
                            title: '目前状态'
                        }, {
                            field: 'effective_date',
                            title: '开立/修改生效日期'
                        }, {
                            field: 'end_date',
                            title: '到期日期'
                        }, {
                            field: 'bank_charge',
                            title: '国内银行费用',
                            align: 'center'
                        }, {
                            field: 'transfer_fee',
                            title: '转开行费用',
                            align: 'center'
                        }, {
                            field: 'currency',
                            title: '保函金额和币种',
                            align: 'center'
                        }, {
                            field: 'guarantee_amount',
                            title: '保函金额（人民币）',
                            align: 'center'
                        }, {
                            field: 'margin_amount',
                            title: '保证金金额',
                            align: 'center'
                        }, {
                            field: 'occupation_quota',
                            title: '占用额度',
                            align: 'center'
                        }, {
                            field: 'opening_bank',
                            title: '国内开立银行',
                            align: 'center'
                        }, {
                            field: 'transfer_bank',
                            title: '国外转开行（若有）',
                            align: 'center'
                        }, {
                            field: 'apply_dept_name',
                            title: '申请部门',
                            align: 'center'
                        }, {
                            field: 'project_country',
                            title: '项目所在国',
                            width: 130
                        }, {
                            field: 'flow_no',
                            title: '保函审批流程流水号',
                            width: 130
                        }, {
                            field: 'commerce_manager',
                            title: '商务经办人',
                            width: 150
                        }, {
                            field: 'business_leader',
                            title: '业务负责人',
                            align: 'center',
                            width: 100
                        }, {
                            field: 'apply_attach',
                            title: '最终保函申请资料',
                            templet: applyAttachLink,
                            align: 'center'
                        }, {
                            field: 'bank_attach',
                            title: '国内银行电文',
                            templet: bankAttachLink,
                            align: 'center'
                        }, {
                            field: 'transfer_attach',
                            title: '转开行最终保函',
                            templet: transferAttachLink,
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
                                project_code: $('#project_code').val(),
                                project_country: $('#project_country').val()
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
                            content: "../add/add3.php",
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
                            var url = '../action.php?action=Delete&id=' + deleteData.id + '&tableName=inhe_authorization_letter';
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
                            content: "../detail/detail3.php?id=" + infoData.id,
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
                            content: "../modify/modify3.php?id=" + infoData.id,
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

        function applyAttachLink(d) {
            var attachName = d.apply_attach;
            if ('' == attachName || null == attachName || undefined == attachName) {
                return '';
            }
            if (attachName.length > 0) {
                var attachUrl = '<a class="layui-blue" href="javascript:void(0);" lay-event="file" onclick="openFullModel(\'/attachment/reportshop/attachment/' +
                    d.apply_attach + '\');return false;">附件下载</a>';
                return attachUrl;
            }
        }

        function bankAttachLink(d) {
            var attachName = d.bank_attach;
            if ('' == attachName || null == attachName || undefined == attachName) {
                return '';
            }
            if (attachName.length > 0) {
                var attachUrl = '<a class="layui-blue" href="javascript:void(0);" lay-event="file" onclick="openFullModel(\'/attachment/reportshop/attachment/' +
                    d.bank_attach + '\');return false;">附件下载</a>';
                return attachUrl;
            }
        }

        function transferAttachLink(d) {
            var attachName = d.transfer_attach;
            if ('' == attachName || null == attachName || undefined == attachName) {
                return '';
            }
            if (attachName.length > 0) {
                var attachUrl = '<a class="layui-blue" href="javascript:void(0);" lay-event="file" onclick="openFullModel(\'/attachment/reportshop/attachment/' +
                    d.transfer_attach + '\');return false;">附件下载</a>';
                return attachUrl;
            }
        }
    </script>

</body>

</html>