<?php
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
include_once("inc/utility_org.php");
$HTML_PAGE_TITLE = _("待办工作");
include_once("inc/header.inc.php");
include_once("common/Common.php");
require_once '../DataModel.php';
$model = new DataModel();
$selectParam = array(
    'is_used' => '1', // 可用参数
    'type' => 'crm_process_type',
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
                流程类型
                <div class="layui-inline">
                    <div class="layui-input-inline" style="width: auto;">
                        <select id="type" name="type" lay-search>
                            <option value="">请选择</option>
                            <?php foreach ($selectArr['crm_process_type'] as $val) : ?>
                                <option value="<?= $val['paras_value'] ?>"><?= $val['paras_desc'] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                流程单号
                <div class="layui-inline">
                    <input class="layui-input" name="form_id" id="form_id" autocomplete="off" style="width: auto;">
                </div>
                <input lay-submit lay-filter="reload" id="reload" data-type="reload" value="查询" type=button class="layui-btn" />
            </div>
        </form>
        <!--数据表格-->
        <table class="layui-hide" id="LAY_table_user" lay-filter="tableList"></table>
    </div>
    <script type="text/html" id="barDemo">
        <a class="layui-btn layui-btn-xs layui-btn-normal" lay-event="detail">查阅</a>
        <a class="layui-btn layui-btn-xs" lay-event="approve">办理</a>
        <a class="layui-btn layui-btn-xs layui-btn-normal" lay-event="process_schedule">进度</a>
    </script>
    <script>
        layui.use(['form', 'table'], function() {
            var table = layui.table;
            var form = layui.form;

            //方法级渲染
            var tableIns = table.render({
                elem: '#LAY_table_user',
                url: 'get_todo_data.php',
                cellMinWidth: 90,
                title: '项目列表',
                cols: [
                    [{
                        type: 'numbers',
                        title: '序号',
                        fixed: 'left',
                        align: 'center'
                    }, {
                        field: 'title',
                        title: '标题',
                        align: 'center'
                    }, {
                        field: 'crm_process_type',
                        title: '流程类型',
                        align: 'center'
                    }, {
                        field: 'form_id',
                        title: '流程单号',
                        align: 'center'
                    }, {
                        field: 'create_user',
                        title: '操作',
                        fixed: 'right',
                        width: 160,
                        align: 'center',
                        toolbar: '#barDemo'
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
                                form_id: $('#form_id').val()
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

            table.on('tool(tableList)', function(obj) {
                var data = obj.data;
                data.type = data.type.replace('_change', ''); // 变更单路径与原流程一样，仅form_id不同
                data.type = data.type.replace('trial_', '');
                data.type = data.type.replace('rd_', '');
                switch (obj.event) {
                    case 'detail':
                        // 详情
                        infoData = data;
                        var index = layui.layer.open({
                            title: "查阅数据",
                            type: 2,
                            content: "../../" + infoData.type + "/detail.php?id=" + infoData.form_id + "&operate=detail",
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
                    case 'approve':
                        // 详情
                        infoData = data;
                        var index = layui.layer.open({
                            title: " 办理流程",
                            type: 2,
                            content: "../../" + infoData.type + "/detail.php?id=" + infoData.form_id + "&operate=approve&step=" + infoData.step,
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
                    case 'process_schedule':
                        // 详情
                        infoData = data;
                        var index = layui.layer.open({
                            title: "流程进度",
                            type: 2,
                            area: ['900px', '600px'],
                            content: "process_schedule.php?id=" + infoData.form_id,
                            success: function(layero, index) {
                                setTimeout(function() {
                                    layui.layer.tips('点击此处返回列表', '.layui-layer-setwin .layui-layer-close', {
                                        tips: 3
                                    });
                                }, 500)
                            }
                        });
                        break;
                }
            });
        });
    </script>

</body>

</html>