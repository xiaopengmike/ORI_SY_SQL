<?php
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
include_once("inc/utility_org.php");
$HTML_PAGE_TITLE = _("数据列表");
include_once("inc/header.inc.php");
include_once("common/Common.php");

switch($company){
    case 'INHE':
        $contract_url='INHE_SALES_CONFIRMATION';
        break;
    case 'INHEGRID':
        $contract_url='INHE_SALES_CONFIRMATION_grid';
        break;
    case 'INHENERGY':
        $contract_url='INHE_SALES_CONFIRMATION_nergy';
        break;
    case 'HKNERGY':
        $contract_url='INHE_SALES_CONFIRMATION_nergy';
        break;
    case 'WITLINK':
        $contract_url='INHE_SALES_CONFIRMATION_witlink';
        break;
    case 'INHEJX':
        $contract_url='inhejx/INHE_SALES_CONFIRMATION';
        break;
    case 'JXINHE':
        $contract_url='jxinhe/INHE_SALES_CONFIRMATION';
        break;
    default :
        $contract_url='INHE_SALES_CONFIRMATION';
        break;
}
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
                项目编号：
                <div class="layui-inline">
                    <input class="layui-input" name="project_code" id="project_code" autocomplete="off" style="width: 110px;">
                </div>
                <input lay-submit lay-filter="reload" id="reload" data-type="reload" value="查询" type=button class="layui-btn" />
                <input  id="getcode"  value="取号" type=button class="layui-btn" onclick="pageOperate('/general/crm/INHE_GETID/<?=$contract_url?>/add.php','smallModel');return false;"/>
                
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
                url: 'get_contract_number.php'+"?company=<?=$company?>",
                cellMinWidth: 90,
                title: '合同编号列表',
                cols: [
                    [{
                        type: 'checkbox',
                        fixed: 'left'
                    }, {
                        field: 'sc_no',
                        title: '合同编号',
                        width: 170,
                        align: 'center'
                    }, {
                        field: 'pm_id',
                        title: '项目编号',
                        align: 'center'
                    }, {
                        field: 'createUserName',
                        title: '取号人',
                        align: 'center'
                    }]
                ],
                id: 'tableReload',
                page: true,
                height: 'auto',
                text: {
                    none: '未取号，请先点击上面的取号再选择'
                },
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
                                project_code: $('#project_code').val()
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
                parent.returnContractNumber(obj.data);
            });

            form.on('submit(reload)', function(data) {
                var type = $(this).data('type');
                active[type] ? active[type].call(this) : '';
            });
        });
    </script>
</body>

</html>