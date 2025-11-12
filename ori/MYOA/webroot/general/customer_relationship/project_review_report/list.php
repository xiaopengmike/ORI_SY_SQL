<?php
include_once "inc/utility_all.php";
include_once "inc/utility_org.php";
$HTML_PAGE_TITLE = _("立项统计表");
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
<style>
    .layui-table-tips-main{ white-space:pre-line; }
</style>
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
                <td align="right">项目</td>
                <td>
                    <input type="text" name="project_code" id="project_code" class="layui-input"
                        placeholder="请输入完整项目">
                </td>
                <td align="right">区域</td>
                <td>
                    <input type="text" name="region" id="region" class="layui-input" value=""
                        placeholder="请输入区域">
                </td> 
                <td align="right">状态</td>
                <td>
                <div class="layui-input-inline">
                        <select id="status" name="status" lay-search>
                            <option value='N'>未结束</option>
                            <option value='Y'>已结束</option>
                        </select>
                    </div>
                </td> 
                <td nowrap align="right">公司：</td>
				<td>
					<div class="layui-input-inline" style="width: 150px;">
						<select id="user_bu" name="user_bu" lay-verify="" lay-search>
							<option value='INHE'>银河电力</option>
							<?php
							$queryCompany = " select * from  `inhe_oa_paras`  where type='crm_process_sort' and  is_used ='1' order by sort_code";
							$res = exequery(TD::conn(), $queryCompany);
							while ($item = mysql_fetch_assoc($res)) {
								echo "<option value='$item[paras_value]'>$item[paras_desc]</option>";
							}
							?>
						</select>
					</div>
				</td>
                <td><button type="button" lay-submit class="layui-btn layui-btn-oa layui-btn-sm" lay-filter="reload"
                        id="reload" data-type="talentReload" style="margin-left: 10px;">查询</button>
                        <button type="button" lay-submit class="layui-btn layui-btn-oa layui-btn-sm" onClick="exportData();return false;" 
                        data-type="talentReload" style="margin-left: 10px;">导出</button>
                        
                    </td>
            </tr>
        </table>
    </form>
    
    <div id="tableList" style="width: 98%;margin:auto;">
    <table class='layui-hide' id="door_userprocess" lay-filter="door_userprocess"></table>
    </div>
    <script type="text/html" id="barDemo">
        <a class="layui-btn layui-btn-xs" lay-event="modify" style="background-color: #0066cc;">结束</a>
    </script>
    <!-- <script type="text/html" id="toolbarExcel">
        <div class="layui-btn-container" id="export">
            <button class="layui-btn layui-btn-oa layui-btn-sm" lay-event="getExcel" title="立项统计表">导出全部</button>
        </div>
    </script> -->
</body>

</html>
<script>
    function exportData() {
		window.open("report_data/get_project.php?" + $("#tab").serialize() + "&export=1");
	}
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
        page: {theme: '#0066cc',limits:[500,800,1000]}, //开启分页
        limit:500,
        where: {
            project_code: $("#project_code").val(),
            region: $("#region").val(),
            status: $("#status").val(),
            user_bu: $("#user_bu").val(),
        },
        id: 'transactionReload',
        height: '700',
        defaultToolbar:['filter'],
        toolbar:'#toolbarExcel',
        cols: [
            [ //表头
                {
                    type: 'numbers',
                    title: '序号',
                    width: 50,
                    align: 'center'
                }, {
                    field: 'region',
                    title: '区域',
                    width: 120,
                    align: 'left',
                },  {
                    field: 'project_code',
                    title: '项目',
                    width: 100,
                    align: 'left'
                }, {
                    field: 'project_star',
                    title: '星级',
                    width: 100,
                    align: 'left'
                },{
                    field: 'project_model',
                    title: '合作模式',
                    width: 100,
                    align: 'left'
                },{
                    field: 'project_need',
                    title: '项目需求',
                    width: 500,
                    align: 'left'
                },{
                    field: 'project_time',
                    title: '立项时间',
                    width: 160,
                    align: 'left'
                },{
                    field: 'country',
                    title: '项目所在国',
                    width: 100,
                    align: 'left'
                },{
                    field: 'business_user_name',
                    title: '业务负责人',
                    width: 100,
                    align: 'left'
                },{
                    field: 'region_user_name',
                    title: '区域负责人',
                    width: 100,
                    align: 'left'
                },{
                    field: 'end_date',
                    title: '截标时间',
                    width: 110,
                    align: 'left'
                },{
                    field: 'project_desc',
                    title: '立项批示/备注',
                    width: 120,
                    align: 'left'
                },{
                    field: 'nodes_desc',
                    title: '进度说明',
                    width: 500,
                    templet:function(d){
                        
                        return '<span class="nodes_desc">'+d.nodes_desc+'</span>';
                    }

                },{
                    field: 'nodes_time',
                    title: '进度时间',
                    width: 180,
                    align: 'left'
                },{
                    field: 'create_user',
                    title: '操作',
                    fixed: 'right',
                    width: 80,
                    align: 'center',
                    
                    templet:function(d){
                        var buttonName=d.status=='N'?'结束':'重启';
                        return '<a class="layui-btn layui-btn-xs" lay-event="modify" style="background-color: #0066cc;">'+buttonName+'</a>';
                    }, hide: true
                    //toolbar: '#barDemo'
                } //这里的toolbar值是模板元素的选择器
            ]
        ],
        done:function(){
            $('.layui-layer-tips .layui-layer-content>span').css({'white-space':'pre-line'});
        }
    });
    table.on('tool(door_userprocess)', function(obj) {
        var data = obj.data;
        switch (obj.event) {
            case 'modify':
                console.log(data);
                // 详情
                infoData = data;
                layer.confirm('确定要结束当前项目（'+infoData.project_code+'）吗？', {
                            btn: ['确定', '取消']
                        }, function() {
                            var url = './action.php?action=Forbidden&id=' + infoData.id;
                            $.post(url, {}, function(data) {
                                var jsonOb = eval("(" + data + ")");
                                if (jsonOb.status == "success") {
                                    layer.alert("结束成功", {
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
        }
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
                            project_code: $("#project_code").val(),
                            region: $("#region").val(),
                            status: $("#status").val(),
                            u_date: $("#u_date").val(),
                            end_u_date: $("#end_u_date").val(),
                    },
                    success:function (res) {
                        table.exportFile(obj.config.id, res.data, 'xls','立项统计表');
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
                    page: {curr: 1,theme: '#0066cc',limits:[500,800,1000]}, //开启分页
                    limit:500,
                    where: {
                        project_code: $("#project_code").val(),
                        region: $("#region").val(),
                        status: $("#status").val(),
                        user_bu: $("#user_bu").val(),
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