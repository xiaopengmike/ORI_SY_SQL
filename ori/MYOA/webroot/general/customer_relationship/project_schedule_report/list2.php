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
        url: "report_data/get_region.php" //数据接口
            ,
        method: 'post',
        page: false, // 不分页
        limit: 99999, // 数据表格默认全部显示
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
                    field: 'region',
                    title: '区域',
                    width: 300,
                    align: 'left',
                },  {
                    field: 'business_user_name',
                    title: '业务负责人',
                    width: 100,
                    align: 'left',
                }, {
                    field: 'project_code',
                    title: '项目',
                    width: 100,
                    align: 'left'
                },{
                    field: 'progress_nodes',
                    title: '进度',
                    width: 300,
                    align: 'left',
                }, 
                {
                    field: 'nodes_desc',
                    title: '描述',
                    align: 'left',
                }, 
            ]
        ],
        done: function (res, curr, count) {
            /**
             * 参数1  需要合并列的数组，0代表的是合并单选按钮或者是复选框 radio/checkbox 后端需要将这些字段进行排序
             * 参数2  当前表格的index (用于在一个页面中有使用了多个表格的情况，如果只有一个表格填0就行
             * 		  index这个值是表格加载的顺序，从0开始，如果页面上表格很多建议debug用 $(".layui-table-body")[index]看一下就拿到了)
             * 参数3  是按照html元素合并，还是按照标签中的text内容合并，建议填true
             * 参数4  radio/checkbox根据哪一行去合并
             */
            layuiRowspan(['0','region','business_user_name'],"0",true,"region");
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
                    url: 'report_data/get_region.php?page=1&limit=1000',
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
                    },
                    done: function (res, curr, count) {
                        /**
                         * 参数1  需要合并列的数组，0代表的是合并单选按钮或者是复选框 radio/checkbox 后端需要将这些字段进行排序
                         * 参数2  当前表格的index (用于在一个页面中有使用了多个表格的情况，如果只有一个表格填0就行
                         * 		  index这个值是表格加载的顺序，从0开始，如果页面上表格很多建议debug用 $(".layui-table-body")[index]看一下就拿到了)
                         * 参数3  是按照html元素合并，还是按照标签中的text内容合并，建议填true
                         * 参数4  radio/checkbox根据哪一行去合并
                         */
                        layuiRowspan(['0','region','business_user_name'],"0",true,"region");
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


 //行合并 start
 function execRadioRows(childFilterArr,ckChildFilterArr,flag){
    //获取td的个数和种类
    var chChildFilterTextObj = {};
    var chText = [];
    var chIndex = [];

    for(var i = 0; i < ckChildFilterArr.length; i++){
        var chChildText = flag?ckChildFilterArr[i].innerHTML:ckChildFilterArr[i].textContent;
        if(chChildFilterTextObj[chChildText] == undefined){
            chChildFilterTextObj[chChildText] = 1;
            chText.push(chChildText);
        }else{
            var num = chChildFilterTextObj[chChildText];
            chChildFilterTextObj[chChildText] = num*1 + 1;
        }
    }
    for (var i = 0; i < chText.length; i++) {
        var chNum = 0;
        for (var j = 0; j < ckChildFilterArr.length ; j++) {
            var chChildText = flag?ckChildFilterArr[j].innerHTML:ckChildFilterArr[j].textContent;
            if(chText[i] == chChildText){
                chNum = chNum +1
            }
        }
        chIndex.push(chNum);
    }
    var newIndex = [];
    for (var i = 0; i < chIndex.length; i++) {
        if(i == 0){
            newIndex.push(0);
        }else {
            var newNum = 0;
            for (var j = 0; j < chIndex.length; j++) {
                if(j<i){
                    newNum = newNum +chIndex[j];
                }
            }
            newIndex.push(newNum);
        }
    }
    chIndex = newIndex;

    for (var j = 0; j < childFilterArr.length; j++) {
        var findFlag = false;
        for (var k = 0; k < chIndex.length; k++) {
            if(j == chIndex[k]){
                findFlag = true;
                if(chIndex[k+1] != null){
                    childFilterArr[j].setAttribute("rowspan",chIndex[k+1] - j);
                    $(childFilterArr[j]).find("div.rowspan").parent("div.layui-table-cell").addClass("rowspanParent");
                    $(childFilterArr[j]).find("div.layui-table-cell")[0].style.height= (chIndex[k+1] - j)*38-10 +"px";

                }else {
                    childFilterArr[j].setAttribute("rowspan",childFilterArr.length - j);
                    $(childFilterArr[j]).find("div.rowspan").parent("div.layui-table-cell").addClass("rowspanParent");
                    $(childFilterArr[j]).find("div.layui-table-cell")[0].style.height= (childFilterArr.length - j)*38-10 +"px";
                }
            }
        }
        if(findFlag == false){
            childFilterArr[j].style.display = "none";
        }
    }
}
function execRowspan (fieldName,index,flag,ckRows){
    var fixedNode = $(".layui-table-body")[index];
    var child = $(fixedNode).find("td");
    var childFilterArr = [];
    for(var j = 0; j < child.length; j++){
        child[j].getAttribute('data-field')
        if(child[j].getAttribute("data-field") == fieldName){
            childFilterArr.push(child[j]);
        }
    }

    var ckChildFilterArr = [];
    if(fieldName == "0"){
        for(var j = 0; j < child.length; j++){
            child[j].getAttribute('data-field')
            if(child[j].getAttribute("data-field") == ckRows){
                ckChildFilterArr.push(child[j]);
            }
        }
        execRadioRows(childFilterArr,ckChildFilterArr,flag);
        return;
    }

    //获取td的个数和种类
    var childFilterTextObj = {};
    for(var i = 0; i < childFilterArr.length; i++){
        var childText = flag?childFilterArr[i].innerHTML:childFilterArr[i].textContent;
        if(childFilterTextObj[childText] == undefined){
            childFilterTextObj[childText] = 1;
        }else{
            var num = childFilterTextObj[childText];
            childFilterTextObj[childText] = num*1 + 1;
        }
    }
    var canRowspan = true;
    var maxNum;//以前列单元格为基础获取的最大合并数
    var finalNextIndex;//获取其下第一个不合并单元格的index
    var finalNextKey;//获取其下第一个不合并单元格的值
    for(var i = 0; i < childFilterArr.length; i++){
        (maxNum >9000 || !maxNum)&&(maxNum = $(childFilterArr[i]).prev().attr("rowspan")&&fieldName!="8"?$(childFilterArr[i]).prev().attr("rowspan"):9999);
        var key = flag?childFilterArr[i].innerHTML:childFilterArr[i].textContent;//获取下一个单元格的值
        var nextIndex = i+1;
        var tdNum = childFilterTextObj[key];
        var curNum = maxNum<tdNum?maxNum:tdNum;
        if(canRowspan){
            for(var j =1;j<=curNum&&(i+j<childFilterArr.length);){//循环获取最终合并数及finalNext的index和key
                finalNextKey = flag?childFilterArr[i+j].innerHTML:childFilterArr[i+j].textContent;
                finalNextIndex = i+j;
                if((key!=finalNextKey&&curNum>1)||maxNum == j){
                    canRowspan = true;
                    curNum = j;
                    break;
                }
                j++;
                if((i+j)==childFilterArr.length){
                    finalNextKey=undefined;
                    finalNextIndex=i+j;
                    break;
                }
            }
            childFilterArr[i].setAttribute("rowspan",curNum);
            if($(childFilterArr[i]).find("div.rowspan").length>0){//设置td内的div.rowspan高度适应合并后的高度
                $(childFilterArr[i]).find("div.rowspan").parent("div.layui-table-cell").addClass("rowspanParent");
                $(childFilterArr[i]).find("div.layui-table-cell")[0].style.height= curNum*38-10 +"px";
            }
            canRowspan = false;
        }else{
            childFilterArr[i].style.display = "none";
        }
        if( --childFilterTextObj[key] == 0 | --maxNum==0 | --curNum==0 | (finalNextKey!=undefined && nextIndex==finalNextIndex)){
            canRowspan = true;
        }
    }
}
function layuiRowspan(fieldNameTmp,index,flag,ckRows){
    var fieldName = [];
    if(typeof fieldNameTmp == "string"){
        fieldName.push(fieldNameTmp);
    }else{
        fieldName = fieldName.concat(fieldNameTmp);
    }
    for(var i = 0;i<fieldName.length;i++){
        execRowspan(fieldName[i],index,flag,ckRows);
    }
}
//行合并 end

</script>