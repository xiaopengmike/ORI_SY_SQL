<?php
include_once("../../inc/auth.php");
include_once("../../inc/utility_all.php");
include_once("../../inc/utility_org.php");
include_once("lang.php");
$HTML_PAGE_TITLE = _($LG_CUSTOMER_DATA['������¼'][$_COOKIE['LANG']]);
include_once("../../inc/header.inc.php");
include_once("../../common/Common.php");

?>
<script type="text/javascript" src="../../inc/js_lang.php"></script>
<script src="<?= MYOA_JS_SERVER ?>/static/js/module.js"></script>
<script type="text/javascript" src="<?= MYOA_JS_SERVER ?>/static/js/attach.js"></script>
<SCRIPT language="Javascript" type="text/javascript" src="<?= COMMON_FILE_URL ?>/jquery.js"></SCRIPT>
<SCRIPT language="Javascript" type="text/javascript" src="<?= COMMON_FILE_URL ?>/common.js"></SCRIPT>
<SCRIPT language="Javascript" type="text/javascript" src="<?= COMMON_FILE_URL ?>/layui/layui.js"></SCRIPT>
<SCRIPT language="Javascript" type="text/javascript" src="main.js"></SCRIPT>
<script language="Javascript" type="text/javascript" src="../../customer_relationship/list.js"></script>
<link rel="stylesheet" type="text/css" href="<?= COMMON_FILE_URL ?>/layui/css/layui.css">
<link rel="stylesheet" type="text/css" href="<?= COMMON_FILE_URL ?>/common.css">
<script language="Javascript" type="text/javascript" src="../../customer_relationship/crm.js"></script>
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
            '<div><img src="/static/images/loading.gif" align="absmiddle" />����װ��...</div>'
        );
        $("#tableList").load("query_flow.php?" + $("#tab").serialize() + "&page=" + page + "&type=" + type, function() {});
    }
    // ʵ��������
    function sortList(obj) {
            //�����߼�
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
    table-layout:fixed;/*table���ڲ����̶ֹ���С���ſ���ͨ��td��width���ƿ���*/
    word-wrap:break-word; /*���������ʻ�����һ��*/
}
</style>
<body class="bodyColorNew">
    <form id="tab" class="layui-form">
        <input type="hidden" id="type" name="type" value="customer_data" />
        <input type="hidden" id="sort_way" name="sort_way" value="" />
        <input type="hidden" id="sort_field" name="sort_field" value="" /> <!-- �����ֶ� -->
        <input type="hidden" id="page_size" name="page_size" value="10" />
        <input type="hidden" id="checkedIds" name="checkedIds" value="" /> <!-- ����ʵ�ֿ�ҳѡ������ -->
        <input type="hidden" name="user_byid" id="user_byid" class="layui-input" value="<?=$user_byid?>" placeholder="�����빤��">
                    <input type="hidden" name="starting_time" id="starting_time" class="layui-input" value="<?=$starting_time?>" placeholder="�����������ʼʱ��?>
                    <input type="hidden" name="ending_time" id="ending_time" class="layui-input" value="<?=$ending_time?>" placeholder="���������ʱ��?>
        <table style="margin: 1%">
            <tr>
                <td align="right"><?=$LG_CUSTOMER_DATA['�ͻ�����'][$_COOKIE['LANG']]?></td>
                <td>
                    <input type="text" name="customer_name" id="customer_name" value="<?=$customer_name?>" class="layui-input" placeholder="<?=$LG_CUSTOMER_DATA['������ͻ�����?][$_COOKIE['LANG']]?>">
                </td>
                <td align="right"><?=$LG_CUSTOMER_DATA['������Ա'][$_COOKIE['LANG']]?></td>
                <td>
                    <input type="text" name="user_name" id="user_name" class="layui-input" value="<?=$user_name?>" placeholder="<?=$LG_CUSTOMER_DATA['������Ա'][$_COOKIE['LANG']]?>">
                </td>
                <td  align="right"><?=$LG_CUSTOMER_DATA['�Ƿ�������ɹ����?][$_COOKIE['LANG']]?></td>
                <td>
                    <div class="layui-input-inline">
                        <select id="order_will" name="order_will">
                            <option value=''><?=$LG_CUSTOMER_DATA['��ѡ��'][$_COOKIE['LANG']]?></option>
                            <option value='Y' <?php if ($order_will == 'Y') : ?>selected<?php endif; ?>><?=$LG_CUSTOMER_DATA['��'][$_COOKIE['LANG']]?></option>
                            <option value='N' <?php if ($order_will == 'N') : ?>selected<?php endif; ?>><?=$LG_CUSTOMER_DATA['��'][$_COOKIE['LANG']]?></option>
                        </select>
                    </div>
                </td>
                <td>
                    <button class="layui-btn layui-btn-oa layui-btn-sm" onclick="loadList('1');return false;" style="margin-left: 10px;"><?=$LG_CUSTOMER_DATA['��ѯ'][$_COOKIE['LANG']]?></button>
                </td>
            </tr>
        </table>
    </form>
    <div id="tableList" style="width:100%;text-align:center;"></div>
</body>

</html>