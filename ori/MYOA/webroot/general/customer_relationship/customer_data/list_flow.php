<?php
// include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
include_once("inc/utility_org.php");
include_once("lang.php");
$HTML_PAGE_TITLE = _($LG_CUSTOMER_DATA['跟进记录'][$_COOKIE['LANG']]);
include_once("inc/header.inc.php");
include_once("common/Common.php");

?>
<script type="text/javascript" src="/inc/js_lang.php"></script>
<script src="<?= MYOA_JS_SERVER ?>/static/js/module.js"></script>
<script type="text/javascript" src="<?= MYOA_JS_SERVER ?>/static/js/attach.js"></script>
<SCRIPT language="Javascript" type="text/javascript" src="<?= COMMON_FILE_URL ?>/jquery.js"></SCRIPT>
<SCRIPT language="Javascript" type="text/javascript" src="<?= COMMON_FILE_URL ?>/common.js"></SCRIPT>
<SCRIPT language="Javascript" type="text/javascript" src="<?= COMMON_FILE_URL ?>/layui/layui.js"></SCRIPT>
<SCRIPT language="Javascript" type="text/javascript" src="main.js"></SCRIPT>
<script language="Javascript" type="text/javascript" src="../list.js"></script>
<link rel="stylesheet" type="text/css" href="<?= COMMON_FILE_URL ?>/layui/css/layui.css">
<link rel="stylesheet" type="text/css" href="<?= COMMON_FILE_URL ?>/common.css">
<script language="Javascript" type="text/javascript" src="../crm.js"></script>
<script>
    $(function() {
        layui.use(['form', 'layer', 'laydate'], function() {
            var layer = layui.layer,
                form = layui.form;
            loadList('1');
            form.render();
        });
        $(document).on("click","#remarks",function(e) {
                if (this.offsetWidth < this.scrollWidth ||this.innerHTML.length>=1) {
                    var that = this;
                    var text = $(this).attr('title');
                    layer.alert(text);
                }
            });
    });

    function loadList(page) {
        var type = $("#type").val();
        $("#tableList").empty();
        $("#tableList").html(
            '<div><img src="/static/images/loading.gif" align="absmiddle" />正在装载...</div>'
        );
        $("#tableList").load("query_flow.php?" + $("#tab").serialize() + "&page=" + page + "&type=" + type, function() {});
    }
    // 实现列排序
    function sortList(obj) {
            //排序逻辑
            myObj = $(obj).parent();
            var sortWay = $(obj).attr("sort-way");
            var sortField = $(obj).parent().attr("sort-field");
            $(obj).parent().attr("lay-sort", sortField);
            $("#sort_way").val(sortWay);
            $("#sort_field").val(sortField);
            loadList('1');
        }
</script>
<style>
.table #remarks {
    white-space: pre-line;
    overflow : hidden;
    text-overflow: ellipsis;
    display: -webkit-box;
    -webkit-line-clamp: 3;
    -webkit-box-orient: vertical;
}
 
table {
    table-layout:fixed;/*table的内部布局固定大小，才可以通过td的width控制宽度*/
    word-wrap:break-word; /*允许长单词换到下一行*/
}
</style>
<body class="bodyColorNew">
    <form id="tab" class="layui-form">
        <input type="hidden" id="type" name="type" value="customer_data" />
        <input type="hidden" id="sort_way" name="sort_way" value="" />
        <input type="hidden" id="sort_field" name="sort_field" value="" /> <!-- 排序字段 -->
        <input type="hidden" id="page_size" name="page_size" value="10" />
        <input type="hidden" id="checkedIds" name="checkedIds" value="" /> <!-- 用于实现跨页选择数据 -->
        <input type="hidden" name="user_byid" id="user_byid" class="layui-input" value="<?=$user_byid?>" placeholder="请输入工号">
                    <input type="hidden" name="starting_time" id="starting_time" class="layui-input" value="<?=$starting_time?>" placeholder="请输入跟进开始时间">
                    <input type="hidden" name="ending_time" id="ending_time" class="layui-input" value="<?=$ending_time?>" placeholder="请输入结束时间">
        <table style="margin: 1%">
            <tr>
                <td align="right"><?=$LG_CUSTOMER_DATA['客户名称'][$_COOKIE['LANG']]?></td>
                <td>
                    <input type="text" name="customer_name" id="customer_name" value="<?=$customer_name?>" class="layui-input" placeholder="<?=$LG_CUSTOMER_DATA['请输入客户名称'][$_COOKIE['LANG']]?>">
                </td>
                <td align="right"><?=$LG_CUSTOMER_DATA['跟进人员'][$_COOKIE['LANG']]?></td>
                <td>
                    <input type="text" name="user_name" id="user_name" class="layui-input" value="<?=$user_name?>" placeholder="<?=$LG_CUSTOMER_DATA['跟进人员'][$_COOKIE['LANG']]?>">
                </td>
                <td  align="right"><?=$LG_CUSTOMER_DATA['是否有意向采购产品'][$_COOKIE['LANG']]?></td>
                <td>
                    <div class="layui-input-inline">
                        <select id="order_will" name="order_will">
                            <option value=''><?=$LG_CUSTOMER_DATA['请选择'][$_COOKIE['LANG']]?></option>
                            <option value='Y' <?php if ($order_will == 'Y') : ?>selected<?php endif; ?>><?=$LG_CUSTOMER_DATA['是'][$_COOKIE['LANG']]?></option>
                            <option value='N' <?php if ($order_will == 'N') : ?>selected<?php endif; ?>><?=$LG_CUSTOMER_DATA['否'][$_COOKIE['LANG']]?></option>
                        </select>
                    </div>
                </td>
                <td>
                    <button class="layui-btn layui-btn-oa layui-btn-sm" onclick="loadList('1');return false;" style="margin-left: 10px;"><?=$LG_CUSTOMER_DATA['查询'][$_COOKIE['LANG']]?></button>
                </td>
            </tr>
        </table>
    </form>
    <div id="tableList" style="width:100%;text-align:center;"></div>
</body>

</html>