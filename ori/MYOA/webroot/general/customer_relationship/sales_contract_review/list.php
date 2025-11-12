<?php
// include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
include_once("inc/utility_org.php");
$HTML_PAGE_TITLE = _("销售合同评审列表");
include_once("inc/header.inc.php");
include_once("common/Common.php");

?>
<script type="text/javascript" src="/inc/js_lang.php"></script>
<script src="<?= MYOA_JS_SERVER ?>/static/js/module.js"></script>
<script type="text/javascript" src="<?= MYOA_JS_SERVER ?>/static/js/attach.js"></script>
<script language="Javascript" type="text/javascript" src="<?= COMMON_FILE_URL ?>/jquery.js"></script>
<script language="Javascript" type="text/javascript" src="<?= COMMON_FILE_URL ?>/common.js"></script>
<script language="Javascript" type="text/javascript" src="<?= COMMON_FILE_URL ?>/layui/layui.js"></script>
<script language="Javascript" type="text/javascript" src="main.js"></script>
<script language="Javascript" type="text/javascript" src="../list.js"></script>
<link rel="stylesheet" type="text/css" href="<?= COMMON_FILE_URL ?>/layui/css/layui.css">
<link rel="stylesheet" type="text/css" href="<?= COMMON_FILE_URL ?>/common.css">
<script language="Javascript" type="text/javascript" src="../crm.js"></script>
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
    .newColor{
        white-space:nowrap;
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
    function sureData(url,$type) {
       
        layer.confirm("确定催办该流程吗？", {
            icon: 3,
            title: '提示'
        }, function (index) {
            layer.close(index);
            $.post(url, {}, function (data) {
                var jsonOb = eval("(" + data + ")");
                if (jsonOb.status == "success") {
                    layer.alert("催办成功", {
                        icon: 1
                    }, function () {
                        $.loadList('1');
                        layer.closeAll();
                    });
                } else {
                    layer.alert(jsonOb.msg, {
                        icon: 5
                    });
                }
            });
        });

    }
</script>

<body class="bodyColorNew">
    <form id="tab" class="layui-form">
        <input type="hidden" id="type" name="type" value="sales_contract_review" />
        <input type="hidden" id="sort_way" name="sort_way" value="" />
        <input type="hidden" id="sort_field" name="sort_field" value="" />
        <input type="hidden" id="page_size" name="page_size" value="10" />
        <input type="hidden" id="checkedIds" name="checkedIds" value="" />
        <input type="hidden" name="business_user_id" id="business_user_id" class="layui-input" value="<?=$business_user_id?>" placeholder="请输入工号">
        <input type="hidden" name="starting_time" id="starting_time" class="layui-input" value="<?=$starting_time?>" placeholder="请输入跟进开始时间">
        <input type="hidden" name="ending_time" id="ending_time" class="layui-input" value="<?=$ending_time?>" placeholder="请输入结束时间">
        <input type="hidden" name="business_status" id="business_status" class="layui-input" value="<?=$business_status?>" placeholder="请输入工号">
        <table style="margin: 1%">
            <tr>
                <td align="right">编号</td>
                <td>
                    <input type="text" name="form_id" id="form_id" class="layui-input" placeholder="请输入编号">
                </td>
                <td align="right">合同编号</td>
                <td>
                    <input type="text" name="customer_order_no" id="customer_order_no" class="layui-input" placeholder="请输入合同编号">
                </td>
                <td align="right">项目名称</td>
                <td>
                    <input type="text" name="project_name" id="project_name" class="layui-input" placeholder="请输入项目名称" />
                </td>
                <td align="right">客户名称</td>
                <td>
                    <input type="text" name="customer_name" id="customer_name" class="layui-input" placeholder="请输入客户名称">
                </td>
                <td align="right">流程状态：</td>
                <td>
                    <div class="layui-input-inline">
                        <select id="status" name="status">
                            <option value=''>请选择</option>
                            <option value='T'>待提交</option>
                            <option value='P'>审核中</option>
                            <option value='R'>备案中</option>
                            <option value='Y'>已审核</option>
                            <option value='N'>退回</option>
                        </select>
                    </div>
                </td>
                <td nowrap align="right">公司：</td>
				<td>
					<div class="layui-input-inline" style="width: 150px;">
						<select id="user_bu" name="user_bu" lay-verify="" lay-search>
							<option value=''>请选择</option>
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
                <td>
                    <button class="layui-btn layui-btn-oa layui-btn-sm" onclick="$.loadList('1');return false;" style="margin-left: 10px;">查询</button>
                </td>
            </tr>
        </table>
    </form>
    <div id="tableList" style="width:100%;text-align:center;"></div>
</body>

</html>