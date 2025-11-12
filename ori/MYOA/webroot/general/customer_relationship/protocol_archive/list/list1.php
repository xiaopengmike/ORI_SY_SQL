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

$selectParam = array(
    'is_used' => '1', // 可用参数
    'type' => 'yes_or_no'
);
$selectArr = $model->getCommonParam($selectParam);

// 数据权限
$deptId = $_SESSION['LOGIN_DEPT_ID'];
$systemId = 'contract_protocol';
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
    <!-- <link rel="stylesheet" type="text/css" href="<?= COMMON_FILE_URL ?>/layui/css/layui.css"> -->
    <link rel="stylesheet" href="<?= COMMON_FILE_URL ?>/css/font.css">
    <link rel="stylesheet" href="<?= COMMON_FILE_URL ?>/css/weadmin.css">
</head>

<body style="background-color: white;">
    <div style="margin:1%">
        <form class="layui-form">
            <div class="demoTable" style="margin:5px;">
                国家：
                <div class="layui-inline">
                    <input class="layui-input" name="country" id="country" autocomplete="off" style="width: 110px;">
                </div>
                项目代号：
                <div class="layui-inline">
                    <input class="layui-input" name="project_code" id="project_code" autocomplete="off" style="width: 110px;">
                </div>
                相对方：
                <div class="layui-inline">
                    <input class="layui-input" name="contract_party" id="contract_party" autocomplete="off" style="width: 110px;">
                </div>
                是否为独家合作协议：
                <div class="layui-inline" style="width: 110px;">
                    <select id="if_exclusive" name="if_exclusive" lay-search>
                        <option value=''>请选择</option>
                        <?php foreach ($selectArr['yes_or_no'] as $v) : ?>
                            <option value="<?= $v['paras_value'] ?>"><?= $v['paras_desc'] ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                经办人：
                <div class="layui-inline">
                    <input class="layui-input" name="user_name" id="user_name" autocomplete="off" style="width: 110px;">
                </div>
                <!-- <button class="layui-btn" data-type="reload" id="reload" lay-submit lay-filter="reload">搜索</button> -->
                <input lay-submit lay-filter="reload" id="reload" data-type="reload" value="查询" type=button class="layui-btn" />
            </div>
        </form>
        <!--数据表格-->
        <table class="layui-hide" id="LAY_table_user" lay-filter="tableList"></table>
    </div>
    <script type="text/html" id="toolbarDemo">
    {{# if(!ifEmpty(<?= $systemAdmin ?>)){ }}
        <div class="layui-btn-container">
            <button class="layui-btn layui-btn-sm" lay-event="addData">添加</button>
            <button class="layui-btn layui-btn-sm" lay-event="authManage">权限管理</button>
            <button class="layui-btn layui-btn-sm" lay-event="import">批量导入</button>
        </div>
        {{# } }}
    </script>
    <script type="text/html" id="barDemo">
        <a class="layui-btn layui-btn-xs" lay-event="detail">查看</a>
        {{# if(d.create_user == '<?= $userId ?>' || '<?= empty($systemAdmin)?FALSE:$systemAdmin ?>'){ }}
            <a class="layui-btn layui-btn-xs" lay-event="modify">编辑</a>
            {{# if(d.create_user == '<?= $userId ?>'){ }}
                <a class="layui-btn layui-btn-danger layui-btn-xs" lay-event="delete">删除</a>
                {{# } }}
                    {{# } }}
    </script>
    <script>
        layui.use(['form', 'table'], function() {
            var table = layui.table;
            var form = layui.form;

            //方法级渲染
            var tableIns = table.render({
                elem: '#LAY_table_user',
                url: '../data/get_data1.php',
                cellMinWidth: 90 //全局定义常规单元格的最小宽度
                    ,
                toolbar: '#toolbarDemo' //开启工具栏，此处显示默认图标，可以自定义模板，详见文档
                    ,
                defaultToolbar: ['filter', 'exports'],
                title: '合作协议登记表',
                cols: [
                    [
                        // {type: 'checkbox', fixed: 'left'},
                        {
                            field: 'serial_number',
                            title: 'No.',
                            width: 60,
                            align: 'center',
                            fixed: true
                        }, {
                            field: 'continent_desc',
                            title: '大洲',
                            align: 'center'
                        }, {
                            field: 'country',
                            title: '国家',
                            align: 'center'
                        }, {
                            field: 'project_code',
                            title: '项目代号',
                            align: 'center'
                        }, {
                            field: 'contract_party',
                            title: '相对方'
                        }, {
                            field: 'protocol_no',
                            title: '协议编号'
                        }, {
                            field: 'detail_type',
                            title: '协议明细类别'
                        }, {
                            field: 'type_desc',
                            title: '协议类型'
                        }, {
                            field: 'if_exclusive_desc',
                            title: '是否为独家合作协议',
                            align: 'center'
                        }, {
                            field: 'effective_condition',
                            title: '独家合作生效条件',
                            align: 'center'
                        }, {
                            field: 'effective_date',
                            title: '生效时间',
                            width: 130
                        }, {
                            field: 'end_date',
                            title: '终止时间',
                            width: 130
                        }, {
                            field: 'sign_date',
                            title: '签订时间',
                            width: 130
                        }, {
                            field: 'flow_no',
                            title: '审批流程流水号',
                            width: 150
                        }, {
                            field: 'apply_dept_name',
                            title: '申请部门',
                            align: 'center',
                            width: 100
                        }, {
                            field: 'user_name',
                            title: '经办人',
                            align: 'center'
                        }, {
                            field: 'if_archive_desc',
                            title: '是否存档',
                            align: 'center'
                        }, {
                            field: 'archive_mode',
                            title: '存档形式',
                            align: 'center'
                        }, {
                            field: 'attachName',
                            title: '附件',
                            templet: attachLink,
                            align: 'center'
                        }, {
                            field: 'remark',
                            title: '备注'
                        }, {
                            field: 'manage_dept_desc',
                            title: '管理部门',
                            align: 'center',
                            width: 120
                        }, {
                            field: 'create_user',
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
                                contract_party: $('#contract_party').val(),
                                user_name: $('#user_name').val(),
                                country: $('#country').val(),
                                if_exclusive: $('#if_exclusive').val()
                            }
                        }, 'data');
                    }
                };

            form.on('submit(reload)', function(data) {
                var type = $(this).data('type');
                active[type] ? active[type].call(this) : '';
            });

            // $('.demoTable .layui-btn').on('click', function() {
            //     var type = $(this).data('type');
            //     active[type] ? active[type].call(this) : '';
            // });

            table.on('toolbar(tableList)', function(obj) {
                // var checkStatus = table.checkStatus(obj.config.id); // 获取选中行数据
                // checkStatus参数： data数组，isAll是否全选
                // layer.alert(JSON.stringify(data));  //选中行数据json格式化
                switch (obj.event) {
                    case 'addData':
                        // 新增一条数据
                        var index = layui.layer.open({
                            title: "新增",
                            type: 2,
                            content: "../add/add1.php",
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
                    case 'import':
                        // 数据导入
                        var templateName = '<?= $systemId ?>';
                        var formatType = '3';
                        var tableName = 'inhe_<?= $systemId ?>';
                        var isExtra = 1;
                        var importUrl = 'import/pre_import.php';
                        importDataNew(templateName, formatType, tableName, isExtra, importUrl);
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
                            var url = '../action.php?action=Delete&id=' + deleteData.id + '&tableName=inhe_contract_protocol';
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
                            content: "../detail/detail1.php?id=" + infoData.id,
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
                            content: "../modify/modify1.php?id=" + infoData.id,
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

        function attachLink(d) {
            var attachName = d.attachName;
            if ('' == attachName || null == attachName || undefined == attachName) {
                return '';
            }
            if (attachName.length > 0) {
                var attachUrl = '<a class="layui-blue" href="javascript:void(0);" lay-event="file" onclick="openFullModel(\'/attachment/reportshop/attachment/' +
                    d.attachName + '\');return false;">附件下载</a>';
                return attachUrl;
            }
        }
    </script>

</body>

</html>