<?php
include_once "inc/utility_all.php";
include_once "inc/utility_org.php";
$HTML_PAGE_TITLE = _("加班调休列表");
include_once "inc/header.inc.php";
include_once "common/Common.php";
include_once "../../common/DataModel.php";
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
            <td align="right">流程单号</td>
                <td>
                    <input type="text" name="form_id" id="form_id" class="layui-input" value="<?=$form_id?>">
                </td>
                <td align="right">所用项目</td>
                <td>
                    <input type="text" name="project_code" id="project_code" class="layui-input">
                </td>
                <td align="right">物料名称</td>
                <td>
                    <input type="text" name="material_name" id="material_name" class="layui-input">
                </td>
                <td><button type="button" lay-submit class="layui-btn layui-btn-oa layui-btn-sm" lay-filter="reload"
                        id="reload" data-type="talentReload" style="margin-left: 10px;">查询</button></td>
            </tr>
        </table>
    </form>
    <!-- <fieldset class="layui-elem-field layui-field-title site-title">
      <legend><a name="use">打卡记录</a></legend>
    </fieldset> -->
    <p style="width: 98%;margin:auto;font-size: 12px;color: red;font-weight: bold;">* 点击时长可查看时长明细</p>
    <div id="tableList" style="width: 98%;margin:auto;">
    <table class='layui-hide' id="door_userprocess" lay-filter="door_userprocess"></table>
    </div>
    <script type="text/html" id="toolbarExcel">
        <div class="layui-btn-container" id="export">
            <button class="layui-btn layui-btn-oa layui-btn-sm" lay-event="getExcel" title="时长账户列表">导出全部</button>
        </div>
    </script>
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
        url: "report_data/get_no_reason.php" //数据接口
            ,
        method: 'post',
        page: {theme: '#0066cc',limits:[10,50,100,200,400,800,1000]}, //开启分页
        where: {
            project_code: $("#project_code").val(),
                            material_name: $("#material_name").val(),
                            form_id: $("#form_id").val(),
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
                },
                {
                    field: 'form_id',
                    title: '单号',
                    width: 200,
                    sort:true,
                    align: 'right',
                    
                }, 
                {
                    field: 'project_code',
                    title: '项目',
                    width: 200,
                    sort:true,
                    align: 'right',
                    
                }, 
                {
                    field: 'material_name',
                    title: '物料',
                    width: 200,
                    sort:true,
                    align: 'right',
                    
                }, 
                {
                    field: 'reason',
                    title: '退回原因',
                    width: 200,
                    sort:true,
                    align: 'right',
                    
                },
                {
                    field: 'work_flow',
                    title: '退回步骤',
                    width: 200,
                    sort:true,
                    align: 'right',
                    
                },
                {
                    field: 'signature',
                    title: '操作人员',
                    width: 200,
                    sort:true,
                    align: 'right',
                    
                },
                {
                    field: 'create_time',
                    title: '操作时间',
                    sort:true,
                    align: 'right',
                    
                },
                //  {
                //     title: '操作',
                //     width: 80,
                //     align: 'center',
                //     templet:function(d){
                //         return "<a href='#' onclick=\"pageOperate('./add_over_log.php?user_name=" + d.user_name+"&user_byid=" + d.user_id+"&u_date=" + d.u_date+"','smallModel');return false;\">增加</a>";
                //     }
                   
                // },
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
                    url: 'report_data/get_no_reason.php?page=1&limit=1000',
                    type: 'post',
                    async: false,
                    dataType: 'json',
                    data: {
                            page:1,
                            limit:1000,
                            project_code: $("#project_code").val(),
                            material_name: $("#material_name").val(),
                            form_id: $("#form_id").val(),
                    },
                    success:function (res) {
                        table.exportFile(obj.config.id, res.data, 'xls','退回原因统计');
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
                        project_code: $("#project_code").val(),
                            material_name: $("#material_name").val(),
                            form_id: $("#form_id").val(),
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