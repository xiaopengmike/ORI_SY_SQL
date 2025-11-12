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
<SCRIPT language="Javascript" type="text/javascript" src="<?= COMMON_FILE_URL ?>/jquery.js"></SCRIPT>
<SCRIPT language="Javascript" type="text/javascript" src="<?= COMMON_FILE_URL ?>/common.js"></SCRIPT>
<SCRIPT language="Javascript" type="text/javascript" src="<?= COMMON_FILE_URL ?>/layui/layui.js"></SCRIPT>
<SCRIPT language="Javascript" type="text/javascript" src="../list.js"></SCRIPT>
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

    .dropdown:hover .dropdown-content {
        display: block;
    }
    .dropdown-content a:hover {
        background-color: #027AFF;
    }

    .dropdown-child {
        position: relative;
        /* display: inline-block; */
    }

    .dropdown-content-child {
        display: none;
        position: absolute;
        z-index: 9999;
        background-color: lightgray;
        min-width: 154px;
        margin-left: 154px;
        margin-top: -40px;
        box-shadow: 0px 8px 16px 0px rgba(0, 0, 0, 0.2);
    }

    .dropdown-content-child a {
        color: black;
        padding: 12px 16px;
        text-decoration: none;
        display: block;
    }

    .dropdown-child:hover .dropdown-content-child {
        display: block;
    }
    .dropdown-content-child a:hover {
        background-color: #027AFF;
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
</script>

<body class="bodyColorNew">
    <form id="tab" class="layui-form">
        <input type="hidden" id="sort_way" name="sort_way" value="" />
        <input type="hidden" id="sort_field" name="sort_field" value="" />
        <input type="hidden" id="page_size" name="page_size" value="10" />
        <input type="hidden" id="checkedIds" name="checkedIds" value="" />
        <table style="margin: 1%">
            <tr>
                <td align="right">流程单号</td>
                <td>
                    <input type="text" name="form_id" id="form_id" class="layui-input">
                </td>
                <td align="right">项目代号</td>
                <td>
                    <input type="text" name="project_code" id="project_code" class="layui-input">
                </td>
                <td align="right">项目编号批次</td>
                <td>
                    <input type="text" name="batch_number" id="batch_number" class="layui-input">
                </td>
                <td align="right">状态：</td>
                <td>
                    <div class="layui-input-inline">
                        <select id="status" name="status">
                            <option value=''>请选择</option>
                            <option value='T'>待提交</option>
                            <option value='P'>审核中</option>
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
                                if($item[paras_value]=='INHENERGY'||$item[paras_value]=='INHEGRID'){
                                  
                                }else{
                                    echo "<option value='$item[paras_value]'>$item[paras_desc]</option>";
                                }
								
							}
                            echo "<optgroup label='银河电网'>";
                            echo "<option value='INHEGRID'>集团外发货通知单</option>";
                            echo "<option value='INHEGRIDIN'>集团内发货通知单</option>";
                            echo "</optgroup>";
                            echo "<optgroup label='银河耐吉'>";
                            echo "<option value='INHENERGY'>生产订单发货</option>";
                            echo "<option value='RETAILNERGY'>国内零售库存产品发货</option>";
                            echo "</optgroup>";
							?>
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
    <div id="tableList" style="width:100%;text-align:center;">

    </div>
</body>

</html>