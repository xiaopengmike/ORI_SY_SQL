<?php
include_once("../../inc/auth.php");
include_once("../../inc/utility_all.php");
include_once("../../inc/utility_org.php");
include_once("lang.php");
$HTML_PAGE_TITLE = _($LG_CUSTOMER_DATA['�ͻ�������ѯ�б�'][$_COOKIE['LANG']]);
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
    });

    public

    function loadList(page) {
        var type = $("#type").val();
        $("#tableList").empty();
        $("#tableList").html(
            '<div><img src="/static/images/loading.gif" align="absmiddle" />����װ��...</div>'
        );
        $("#tableList").load("query_share.php?" + $("#tab").serialize() + "&page=" + page + "&type=" + type, function() {});
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

<body class="bodyColorNew">
    <form id="tab" class="layui-form">
        <input type="hidden" id="type" name="type" value="customer_data" />
        <input type="hidden" id="sort_way" name="sort_way" value="" />
        <input type="hidden" id="sort_field" name="sort_field" value="" /> <!-- �����ֶ� -->
        <input type="hidden" id="page_size" name="page_size" value="10" />
        <input type="hidden" id="checkedIds" name="checkedIds" value="" /> <!-- ����ʵ�ֿ�ҳѡ������ -->
        <table style="margin: 1%">
            <tr>
                <td align="right"><?=$LG_CUSTOMER_DATA['�ͻ�����'][$_COOKIE['LANG']]?></td>
                <td>
                    <input type="text" name="customer_name" id="customer_name" class="layui-input" placeholder="<?=$LG_CUSTOMER_DATA['������ͻ�����'][$_COOKIE['LANG']]?>">
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