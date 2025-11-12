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

<table class="layui-hide" id="test"></table>

    <script>
        layui.use('table', function() {
            var table = layui.table;
            											
            //方法级渲染
            table.render({
                elem: '#test',
                url: 'demo/table/user/',
                cellMinWidth: 80, //全局定义常规单元格的最小宽度
                cols: [[
                        {field: 'serial_number',title: 'No.',sort: true,fixed: true}
                        ,{field: 'continent',title: '大洲'}
                        ,{field: 'country',title: '国家'}
                        ,{field: 'project_code',title: '项目代号'}
                        ,{field: 'customer_name',title: '相对方'}
                        ,{field: 'protocol_no',title: '协议编号'}
                        ,{field: 'detail_type',title: '协议明细类别'}
                        ,{field: 'type',title: '协议类型'}
                        ,{field: 'if_exclusive',title: '是否为独家合作协议'}
                        ,{field: 'effective_condition',title: '独家合作生效条件'}
                        ,{field: 'effective_date',title: '生效时间'}
                        ,{field: 'end_date',title: '终止时间'}
                        ,{field: 'sign_date',title: '签订时间'}
                        ,{field: 'flow_no',title: '审批流程流水号'}
                        ,{field: 'apply_dept',title: '申请部门'}
                        // ,{field: 'user_id',title: ''}
                        ,{field: 'user_name',title: '经办人'}
                        ,{field: 'if_archive',title: '是否存档'}
                        ,{field: 'archive_mode',title: '存档形式'}
                        ,{field: 'attach_name',title: '附件'}
                        ,{field: 'remark',title: '备注'}
                        ,{field: 'manage_dept',title: '管理部门'}
                    ]]
                    ,page: false
                    ,limit:10
            });
        });
    </script>

</body>

</html>