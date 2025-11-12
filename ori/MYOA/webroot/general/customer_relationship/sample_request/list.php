<?php
// include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
include_once("inc/utility_org.php");
$HTML_PAGE_TITLE = _("客户信息列表");
include_once("inc/header.inc.php");
include_once("common/Common.php");

?>
<script type="text/javascript" src="/inc/js_lang.php"></script>
<script src="<?= MYOA_JS_SERVER ?>/static/js/module.js"></script>
<script type="text/javascript" src="<?= MYOA_JS_SERVER ?>/static/js/attach.js"></script>
<script language="Javascript" type="text/javascript" src="<?= COMMON_FILE_URL ?>/jquery.js"></script>
<script language="Javascript" type="text/javascript" src="<?= COMMON_FILE_URL ?>/common.js"></script>
<script language="Javascript" type="text/javascript" src="<?= COMMON_FILE_URL ?>/layui/layui.js"></script>
<script language="Javascript" type="text/javascript" src="../list.js"></script>
<link rel="stylesheet" type="text/css" href="<?= COMMON_FILE_URL ?>/layui/css/layui.css">
<link rel="stylesheet" type="text/css" href="<?= COMMON_FILE_URL ?>/common.css">
<style>
    .drop-btn {
        cursor: pointer;
    }

    .dropdown {
        position: relative;
        display: inline-block;
    }

    .dropdown-content {
        display: none;
        position: absolute;
        z-index: 9999;
        background-color: lightgray;
        min-width: 154px;
        box-shadow: 0px 8px 16px 0px rgba(0, 0, 0, 0.2);
    }

    .dropdown-content a {
        color: black;
        padding: 12px 16px;
        text-decoration: none;
        display: block;
    }

    .dropdown-content a:hover {
        background-color: #027AFF;
    }

    .dropdown:hover .dropdown-content {
        display: block;
    }
</style>
<script>
    $(function() {
        layui.use(['form', 'layer'], function() {
            var layer = layui.layer,
                form = layui.form;
            $.loadList('1');
            form.render();
        });
    });
    function returnData(url,part_number,pm_status) {
        layer.prompt({
            formType: 2,
            value: part_number,
            //title: '填写料号(启用填写料号，不启用填写原因)',
            btn: ['确定','取消'], //按钮，
            btnAlign: 'c',
            success: function(layero, index) {
                //var input = layero.find('input'); // 获取输入框元素
                $(".layui-layer-input").attr('placeholder', '填写料号'); // 设置 placeholder
                html="<label><input type=\"radio\" name=\"pm_status\" id=\"pm_status\" value=\"01\" /> 启用</label>   <label><input type=\"radio\" name=\"pm_status\" id=\"pm_status\" value=\"02\" /> 不启用</label><br/><br/>";
                if(pm_status=="02"){
                    html="<label><input type=\"radio\" name=\"pm_status\" id=\"pm_status\" value=\"01\" /> 启用</label>   <label><input type=\"radio\" checked=\"checked\" name=\"pm_status\" id=\"pm_status\" value=\"02\" /> 不启用</label><br/><br/>";
                }else if(pm_status=="01"){
                    html="<label><input type=\"radio\" checked=\"checked\"  name=\"pm_status\" id=\"pm_status\" value=\"01\" /> 启用</label>   <label><input type=\"radio\" checked=\"checked\" name=\"pm_status\" id=\"pm_status\" value=\"02\" /> 不启用</label><br/><br/>";
                }
                //$(".layui-layer-input").before(html);
            }
        }, function(value,index){
            var pm_status=$("#pm_status").val();
            $.post(url, {"part_number":value,"pm_status":pm_status}, function (data) {
                    var jsonOb = eval("(" + data + ")");
                    if (jsonOb.status == "success") {
                        layer.alert("提交成功", {
                            icon: 1
                        }, function () {
                            //parent.location.reload();
                            //location.href =window.location.href;
                            window.close();
                            layer.closeAll();
                        });
                    } else {
                        layer.alert(jsonOb.msg, {
                            icon: 5
                        });
                    }
                });
            layer.close(index);
            
        });
        
    }
</script>

<body class="bodyColorNew">
    <form id="tab" class="layui-form">
        <input type="hidden" id="type" name="type" value="sample_request" />
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
                <td align="right">制单人</td>
                <td>
                    <input type="text" name="create_user_name" id="create_user_name" class="layui-input">
                </td>
                <td align="right">打样原因：</td>
                <td>
                    <div class="layui-input-inline">
                        <select id="m_specification_model" name="m_specification_model">
                            <option value=''>请选择</option>
                            <option value='01'>新设计打样</option>
                            <option value='02'>原有设计变更</option>
                            <option value='03'>其它</option>
                        </select>
                    </div>
                </td>
                <td align="right">状态：</td>
                <td>
                    <div class="layui-input-inline">
                        <select id="status" name="status">
                            <option value=''>请选择</option>
                            <option value='T'>待提交</option>
                            <option value='P'>审核中</option>
                            <option value='N'>已退回</option>
                            <option value='YP'>索样确认</option>
                            <option value='YY'>料号申请</option>
                            <option value='YE'>已结束</option>
                            <option value='YN'>已作废</option>
                            
                        </select>
                    </div>
                </td>
               
                <td>
                    <button class="layui-btn layui-btn-oa layui-btn-sm" onclick="$.loadList('1');return false;" style="margin-left: 10px;">查询</button>
                    <!-- <button class="layui-btn layui-btn-oa layui-btn-sm" onclick="exportData();return false;">导出</button> -->
                </td>
            </tr>
        </table>
    </form>
    <div id="tableList" style="width:100%;text-align:center;"></div>
</body>

</html>