<?php
include_once "inc/utility_all.php";
include_once "inc/utility_org.php";
$HTML_PAGE_TITLE = _("时长账户列表");
include_once "inc/header.inc.php";
include_once "common/Common.php";
include_once "../common/DataModel.php";
$dataModel = new DataModel();
$selectParam = array(
    'is_used' => '1', // 可用参数
    'type' => 'company,work_process_type',
);
$selectArr = $dataModel->getCommonParam($selectParam);
$month = $month?$month:date("Y-m",strtotime('-1 month'));
$month_t = date('t',strtotime($month));
?>
<script type="text/javascript" src="/inc/js_lang.php"></script>
<script src="<?=MYOA_JS_SERVER?>/static/js/module.js"></script>
<script type="text/javascript" src="<?=COMMON_FILE_URL?>/jquery-1.11.3.min.js"></script>
<SCRIPT language="Javascript" type="text/javascript" src="<?=COMMON_FILE_URL?>/common.js"></SCRIPT>
<SCRIPT language="Javascript" type="text/javascript" src="<?=COMMON_FILE_URL?>/layui/layui.js"></SCRIPT>
<script language="Javascript" type="text/javascript" src="../list.js"></script>
<link rel="stylesheet" type="text/css" href="<?=COMMON_FILE_URL?>/layui/css/layui.css">
<link rel="stylesheet" type="text/css" href="<?=COMMON_FILE_URL?>/common.css">
<script>
$(function() {
    layui.use(['form', 'layer','laydate'], function() {
        var layer = layui.layer,
            laydate = layui.laydate,
            form = layui.form;
            laydate.render({
                    elem: '#u_date',
                    theme: '#027AFF',
                    type: 'date',
                    done:function(value,date){
                        //value, date, endDate点击日期、清空、现在、确定均会触发。回调返回三个参数，分别代表：生成的值、日期时间对象、结束的日期时间对象

                    }
                });
                laydate.render({
                    elem: '#end_u_date',
                    theme: '#027AFF',
                    type: 'date',
                    done:function(value,date){
                        //value, date, endDate点击日期、清空、现在、确定均会触发。回调返回三个参数，分别代表：生成的值、日期时间对象、结束的日期时间对象

                    }
                });
        form.render();
    });
});
</script>

<body class="bodyColorNew">
    <form id="tab" class="layui-form">
        <input type="hidden" id="type" name="type" value="talent_record" />
        <input type="hidden" id="sort_way" name="sort_way" value="" />
        <input type="hidden" id="sort_field" name="sort_field" value="" />
        <input type="hidden" id="page_size" name="page_size" value="10" />
        <input type="hidden" id="checkedIds" name="checkedIds" value="" />
        <table style="margin: 1%">
            <tr>
                <td align="right">姓名</td>
                <td>
                    <input type="text" name="user_name" id="user_name" class="layui-input"
                        placeholder="请输入姓名">
                </td>
                <td align="right">工号</td>
                <td>
                    <input type="text" name="user_id" id="user_id" class="layui-input" value="<?=$user_id?>"
                        placeholder="请输入工号">
                </td> 
                <td align="right">开始日期</td>
                <td>
                    <input type="text" name="u_date" id="u_date" class="layui-input date" value="" autocomplete="off" placeholder="开始日期">
                </td>
                <td align="right">结束日期</td>
                <td>
                    <input type="text" name="end_u_date" id="end_u_date" class="layui-input date" value="" autocomplete="off" placeholder="结束日期">
                </td>
                <td><button type="button" lay-submit class="layui-btn layui-btn-oa layui-btn-sm" lay-filter="reload"
                        id="reload" data-type="talentReload" style="margin-left: 10px;">查询</button></td>
            </tr>
        </table>
    </form>

    <div id="tableList" style="width: 98%;margin:auto;">
    <table class='layui-hide' id="door_userprocess" lay-filter="door_userprocess"></table>
    </div>
    <!-- <script type="text/html" id="toolbarExcel">
        <div class="layui-btn-container" id="export">
            <button class="layui-btn layui-btn-oa layui-btn-sm" lay-event="getExcel" title="时长账户列表">导出全部</button>
        </div>
    </script> -->
</body>

</html>
<script>
layui.use('table', function() {
    var table = layui.table,
        layer = layui.layer,
        $ = layui.$,
        form = layui.form;
    //第一个实例
    table.render({
        elem: '#door_userprocess',
        height: 312,
        url: "report_data/get_project.php" //数据接口
            ,
        method: 'post',
        page: {theme: '#0066cc',limits:[10,50,100,200,400,800,1000]}, //开启分页
        where: {
            user_name: $("#user_name").val(),
            user_id: $("#user_id").val(),
        },
        id: 'transactionReload',
        height: 'auto',
        defaultToolbar:['filter','exports'],
        toolbar:'#toolbarExcel',
        cols: [
            [ //表头
                {
                    type: 'numbers',
                    title: '序号',
                    width: 80,
                    align: 'center'
                }, {
                    field: 'project_code',
                    title: '项目',
                    width: 100,
                    align: 'left'
                }, {
                    field: 'region',
                    title: '区域',
                    width: 300,
                    align: 'left',
                }, {
                    field: 'progress_nodes',
                    title: '进度',
                    width: 300,
                    align: 'left',
                },  {
                    field: 'nodes_desc',
                    title: '进度描述',
                    width: 300,
                    align: 'left',
                },  {
                    field: 'business_user_name',
                    title: '业务负责人',
                    align: 'left',
                },
            ]
        ]
    });

    table.on('toolbar(door_userprocess)', function(obj){
        // if (!!window.ActiveXObject || "ActiveXObject" in window) { //是否ie
        //         layer.msg('导出功能不支持 IE，请用 Chrome 等高级浏览器导出');
        //        return false;
        //     }
        switch(obj.event){
            case 'getExcel':
                //请求所有数据
                $.ajax({
                    url: 'report_data/get_project.php?page=1&limit=1000',
                    type: 'post',
                    async: false,
                    dataType: 'json',
                    data: {
                            page:1,
                            limit:1000,
                            u_date: $("#u_date").val(),
                            end_u_date: $("#end_u_date").val(),
                    },
                    success:function (res) {
                        table.exportFile(obj.config.id, res.data, 'xls','时长账户列表');
                    }
                });
                break;
        };
    });
    var active = {
            talentReload: function() {
                //执行重载
                table.reload('transactionReload', {
                    method: 'post',
                    page: {curr: 1,theme: '#0066cc',limits:[10,50,100,200,400,800,1000]}, //开启分页
                    where: {
                        user_name: $("#user_name").val(),
                        user_id: $("#user_id").val(),
                        u_date: $("#u_date").val(),
                        end_u_date: $("#end_u_date").val(),
                    }
                }, 'data');
            }
        };
    form.on('submit(reload)', function(data) {
        var type = $(this).data('type');
        active[type] ? active[type].call(this) : '';
    });
    $(".layui-input").keydown(function (e) {//当按下按键时
        if (e.which == 13) {//.which属性判断按下的是哪个键，回车键的键位序号为13
            $("#reload").click();
        }
    });

});
</script>