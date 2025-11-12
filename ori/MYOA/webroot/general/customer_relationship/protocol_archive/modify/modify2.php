<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
include_once("inc/utility_org.php");

$HTML_PAGE_TITLE = _("编辑数据");
include_once("inc/header.inc.php");
include_once("common/Common.php");
include_once("../ActionModel.php");
include_once("../../common/CommonControl.php");
$model = new CommonControlModel();

if(empty($id)){
    // echo "对应数据不存在！";
    Message("提示","对应数据不存在！");
    exit;
}

$deptId = $_SESSION['LOGIN_DEPT_ID'];
$userId = $_SESSION['LOGIN_USER_ID'];
$companyNew = $model->getUserBuNew($deptId);
$selectParam = array(
    'is_used' => '1', // 可用参数
    'type' => 'yes_or_no,contract_category,manage_dept,currency'
);
$selectArr = $model->getCommonParam($selectParam);
$yes_or_no = array();
foreach ($selectArr['yes_or_no'] as $co) {
    $yes_or_no[$co['paras_value']] = $co['paras_desc'];
}
$manage_dept = array();
foreach ($selectArr['manage_dept'] as $md) {
    $manage_dept[$md['paras_value']] = $md['paras_desc'];
}
$contract_category = array();
foreach ($selectArr['contract_category'] as $md) {
    $contract_category[$md['paras_value']] = $md['paras_desc'];
}
$continent = array();
foreach ($selectArr['currency'] as $md) {
    $currency[$md['paras_value']] = $md['paras_desc'];
}

$action = new ActionModel();
$param['id'] = $id;
$data = $action->querySalesContract($param);
$attachObj = $action->getAttachById($id);

?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta name="renderer" content="webkit">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=0">
    <script language="Javascript" type="text/javascript" src="<?= COMMON_FILE_URL ?>/jquery-1.11.3.min.js"></script>
    <script language="Javascript" type="text/javascript" src="<?= COMMON_FILE_URL ?>/common.js"></script>
    <script language="Javascript" type="text/javascript" src="<?= COMMON_FILE_URL ?>/layui/layui.js" charset="utf-8"></script>
    <script language="Javascript" type="text/javascript" src="../main.js"></script>
    <link rel="stylesheet" href="<?= COMMON_FILE_URL ?>/css/font.css">
    <link rel="stylesheet" href="<?= COMMON_FILE_URL ?>/css/weadmin.css">
    <script language="Javascript" type="text/javascript" src="<?= COMMON_FILE_URL ?>/dtreeck.js"></SCRIPT>
    <link rel="stylesheet" type="text/css" href="<?= COMMON_FILE_URL ?>/dtreeck.css">

    <script type="text/javascript">
        $(function() {
            layui.extend({
                attach: '<?= COMMON_FILE_URL ?>/js/extends/attach'
            }).use(['form', 'layer', 'laydate', 'attach', 'table'], function() {
                var layer = layui.layer,
                    form = layui.form,
                    attach = layui.attach,
                    laydate = layui.laydate,
                    table = layui.table;

                init();

                $(".date").each(function() {
                    laydate.render({
                        elem: this,
                        theme: '#027AFF',
                        range: false
                    });
                });

                var attachObj = <?= $attachObj ?>;
                $(".btn-upload").each(function() {
                    var id = this.id;
                    if (ifEmpty(attachObj[id])) attachObj[id] = {};
                    attach.attachList({
                        elem: "#" + id,
                        uploadUrl: "/general/attachment/attach.php?type=" + id + "&url=common",
                        attachObj: attachObj[id]
                    });
                });

                form.on('submit(submit)', function(data) {
                    var btnVal = data.elem.value;
                    var actionType = $(data.elem).attr("actionType");
                    var url = "../action.php?action=" + actionType;
                    $.post(url, $("#addForm").serializeArray(), function(data) {
                        var reJson = null;
                        try {
                            reJson = eval('(' + data + ')');
                        } catch (e) {
                            layer.alert('服务器异常: ' + e, {
                                icon: 5
                            });
                            return;
                        }
                        if (reJson.code == "200") {
                            layer.alert(btnVal + "成功!", {
                                icon: 1
                            }, function(index, layero) {
                                var index = parent.layer.getFrameIndex(window.name); //先得到当前iframe层的索引
                                parent.layui.table.reload('testReload');
                                parent.layer.close(index); //再执行关闭
                            });
                        } else {
                            layer.alert(btnVal + '失败！', {
                                icon: 5
                            });
                        }
                    });
                });

                form.on('select(create_user)', function(data) {
                    $("#create_user_name").val(data.elem[data.elem.selectedIndex].text);

                    form.render();
                });

                form.render();
            });
        });

        // 页面初始化加载
        function init() {
            // textarea初始化样式
            $.each($("textarea"), function(i, n) {
                $(n).css("width", "100%");
                $(n).css("min-height", "60px");
                $(n).css("margin", "auto");
                $(n).css("resize", "none");
                $(n).css("overflow", "hidden");
            });

            // textarea输入后自适应高度设置
            var remark = document.getElementById('remark');
            makeExpandingArea(remark);

            selectUserByDeptId('<?= $data['dept_id'] ?>', '', '<?= $data['create_user'] ?>');
        }

        function selectUserByDeptId(deptId, number, defaultVal) {
            layui.use(['form', 'layer'], function() {
                var layer = layui.layer,
                    form = layui.form;
                if (deptId != '') {
                    $.post('../user_info.php?deptId=' + deptId, {}, function(data) {
                        var obj = eval('(' + data + ')');

                        $("#create_user" + number).empty();
                        $("#create_user" + number).append("<option value=''>请选择</option>");
                        var selected = "";
                        for (i = 0; i < obj.length; i++) {
                            selected = "";
                            if (defaultVal == obj[i].user_id) {
                                selected = " selected ";
                            }
                            $("#create_user" + number).append("<option value='" + obj[i].user_id + "' " + selected + ">" + obj[i].user_name + "</option>");
                        }
                        form.render();
                    });
                }
                form.render();
            });
        }
    </script>

<body>
    <div class="weadmin-body" style="width:90%;margin:1% 4%;">
        <form id="addForm" class="layui-form">
            <input type="hidden" name="id" id="id" value="<?= $data['id'] ?>" />
            <input type="hidden" name="form_type" id="form_type" value="sales_contract" />
            <input type="hidden" name="user_bu" id="user_bu" value="<?= $companyNew ?>" />
            <input type="hidden" name="dept_number" id="dept_number" value="" />
            <table class="layui-table" border="0" cellpadding="0" cellspacing="10" lay-size="sm">
                <tr>
                    <td class="layui-bg-gray">合同签署时间</td>
                    <td>
                        <input type="text" name="sign_date" id="sign_date" class="layui-input date" value="<?= $data['sign_date'] ?>" />
                    </td>
                    <td class="layui-bg-gray">合同类别</td>
                    <td>
                        <div class="layui-input-inline mandatory">
                            <select id="contract_category" name="contract_category" lay-search lay-verify="required">
                                <option value=''>请选择</option>
                                <?php foreach ($selectArr['contract_category'] as $sc) : ?>
                                    <option value="<?= $sc['paras_value'] ?>" <?php if ($sc['paras_value'] == $data['contract_category']) : ?>selected<?php endif; ?>><?= $sc['paras_desc'] ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </td>
                    <td class="layui-bg-gray">项目名称</td>
                    <td>
                        <input type="text" name="project_name" id="project_name" lay-verify="required" class="layui-input" value="<?= $data['project_name'] ?>" />
                    </td>
                    <td class="layui-bg-gray">项目编号</td>
                    <td>
                        <input type="text" name="project_no" id="project_no" lay-verify="required" class="layui-input" value="<?= $data['project_no'] ?>" />
                    </td>
                </tr>
                <tr>
                    <td class="layui-bg-gray">合同编号</td>
                    <td>
                        <input type="text" name="contract_no" id="contract_no" lay-verify="required" class="layui-input" value="<?= $data['contract_no'] ?>" />
                    </td>
                    <td class="layui-bg-gray">币种</td>
                    <td>
                        <div class="layui-input-inline mandatory">
                            <select id="currency" name="currency" lay-search lay-verify="required">
                                <option value=''>请选择</option>
                                <?php foreach ($selectArr['currency'] as $yVal) : ?>
                                    <option value="<?= $yVal['paras_value'] ?>" <?php if ($yVal['paras_value'] == $data['currency']) : ?>selected<?php endif; ?>><?= $yVal['paras_desc'] ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </td>
                    <td class="layui-bg-gray">合同金额</td>
                    <td colspan="3">
                        <input type="text" name="amount" id="amount" lay-verify="required" class="layui-input" value="<?= $data['amount'] ?>" />
                    </td>
                </tr>
                <tr>
                    <td class="layui-bg-gray">客户名称</td>
                    <td>
                        <input type="text" name="customer_name" id="customer_name" lay-verify="required" class="layui-input" value="<?= $data['customer_name'] ?>" />
                    </td>
                    <td class="layui-bg-gray">合同相对方</td>
                    <td>
                        <input type="text" name="contract_party" id="contract_party" class="layui-input" value="<?= $data['contract_party'] ?>" />
                    </td>
                    <td class="layui-bg-gray">业务归属</td>
                    <td colspan="3">
                        <input type="text" name="business_belong" id="business_belong" class="layui-input" value="<?= $data['business_belong'] ?>" />
                    </td>
                </tr>
                <tr>
                    <td class="layui-bg-gray">部门</td>
                    <td>
                        <div class="layui-input-inline" style="width: 220px;">
                            <input type="text" id="menu_parent_name" class="layui-input" data-type="dept_select" name="dept_name" style="width: 220px;" value="<?= $data['dept_name'] ?>" />
                            <input type="hidden" id="menu_parent" name="dept_id" value="<?= $data['dept_id'] ?>">
                        </div>
                    </td>
                    <td class="layui-bg-gray">制单人</td>
                    <td>
                        <div class="layui-input-inline" style="width: 220px;">
                            <input type="hidden" id="create_user_name" name="create_user_name" value="<?= $data['create_user_name'] ?>" />
                            <select name="create_user" id="create_user" lay-filter="create_user">
                                <option value=''>请选择</option>
                            </select>
                        </div>
                    </td>
                    <td class="layui-bg-gray">管理部门</td>
                    <td colspan="3">
                        <div class="layui-input-inline mandatory">
                            <select id="manage_dept" name="manage_dept" lay-search lay-verify="required">
                                <option value=''>请选择</option>
                                <?php foreach ($selectArr['manage_dept'] as $md) : ?>
                                    <option value="<?= $md['paras_value'] ?>" <?php if ($md['paras_value'] == $data['manage_dept']) : ?>selected<?php endif; ?>><?= $md['paras_desc'] ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td class="layui-bg-gray">外文附件</td>
                    <td colspan="7">
                        <input type="button" id="foreign_attach" class="layui-btn layui-bg-gray btn-upload" style="display: inline!important;" value="附件上传" />
                    </td>
                </tr>
                <tr>
                    <td class="layui-bg-gray">中文翻译件</td>
                    <td colspan="7">
                        <input type="button" id="chinese_attach" class="layui-btn layui-bg-gray btn-upload" style="display: inline!important;" value="附件上传" />
                    </td>
                </tr>
                <tr>
                    <td class="layui-bg-gray">备注</td>
                    <td colspan="7">
                        <textarea class="layui-textarea" lay-verify="required" id="remark" name="remark" placeholder="请输入备注"><?= $data['remark'] ?></textarea>
                    </td>
                </tr>
                <tr>
                    <td colspan="8" align="center" style="padding: 5px;">
                        <input lay-submit lay-filter="submit" name="btn_submit" actionType="Submit" value="提交" type=button class="layui-btn layui-btn-oa layui-btn-sm" />
                    </td>
                </tr>
            </table>
        </form>
    </div>
    <br />
    <?php include_once("common/mydtree_new.php"); ?>
</body>

</html>