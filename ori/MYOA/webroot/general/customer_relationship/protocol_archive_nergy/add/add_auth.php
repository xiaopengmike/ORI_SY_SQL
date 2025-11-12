<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
include_once("inc/utility_org.php");

$HTML_PAGE_TITLE = _("管理部门权限设置");
include_once("inc/header.inc.php");
include_once("common/Common.php");

include_once("../../common/CommonControl.php");
$model = new CommonControlModel();

$deptId = $_SESSION['LOGIN_DEPT_ID'];
$userId = $_SESSION['LOGIN_USER_ID'];

$companyNew = $model->getUserBuNew($deptId);
$selectParam = array(
    'is_used' => '1', // 可用参数
    'type' => 'manage_dept_nergy'
);
$selectArr = $model->getCommonParam($selectParam);

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
            }).use(['form', 'layer', 'table'], function() {
                var layer = layui.layer,
                    form = layui.form,
                    table = layui.table;

                form.on('submit(submit)', function(data) {
                    var btnVal = data.elem.value;
                    var actionType = $(data.elem).attr("actionType");
                    var url = "../action.php?action=" + actionType;

                    //获取checkbox[name='like']的值
                    var arr = new Array();
                    $("input:checkbox[name='manage_dept']:checked").each(function(i){
                        arr[i] = $(this).val();
                    });
                    data.field.manage_dept = arr.join(",");//将数组合并成字符串

                    $.post(url, {data: data.field}, function(data) {
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

                form.render();
            });
        });

        function selectUserByDeptId(deptId, number, defaultVal) {
            layui.use(['form', 'layer'], function() {
                var layer = layui.layer,
                    form = layui.form;
                if (deptId != '') {
                    $.post('../user_info.php?deptId=' + deptId, {}, function(data) {
                        var obj = eval('(' + data + ')');

                        $("#user_name" + number).empty();
                        $("#user_name" + number).append("<option value=''>请选择</option>");
                        var selected = "";
                        for (i = 0; i < obj.length; i++) {
                            if (defaultVal == obj[i].user_id) {
                                selected = " selected ";
                            }
                            $("#user_name" + number).append("<option value='" + obj[i].user_id + "' " + selected + ">" + obj[i].user_name + "</option>");
                        }
                    });
                }
                form.render();
            });
        }
    </script>

<body>
    <div class="weadmin-body" style="width:90%;margin:1% 4%;">
        <form id="addForm" class="layui-form">
            <input type="hidden" name="form_type" id="form_type" value="system_admin" />
            <input type="hidden" name="is_admin" id="is_admin" value="1" />
            <input type="hidden" name="system_admin" id="system_admin" value="0" />
            <input type="hidden" name="user_bu" id="user_bu" value="<?= $companyNew ?>" />
            <input type="hidden" name="type" id="type" value="<?= $type ?>" />
            <input type="hidden" name="dept_number" id="dept_number" value="" />
            <table class="layui-table" border="0" cellpadding="0" cellspacing="10" lay-size="sm">
                <tr>
                    <td class="layui-bg-gray">部门</td>
                    <td>
                        <div class="layui-input-inline" style="width: 220px;">
                            <input type="text" id="menu_parent_name" class="layui-input" data-type="dept_select" name="dept_name" style="width: 220px;" placeholder="请选择申请部门" />
                            <input type="hidden" id="menu_parent" name="dept_id">
                        </div>
                    </td>
                </tr>
                <tr>
                    <td class="layui-bg-gray">姓名</td>
                    <td>
                        <div class="layui-input-inline" style="width: 220px;">
                            <select name="user_name" id="user_name" lay-filter="user_name" placeholder="请选择经办人">
                                <option value=''>请选择</option>
                            </select>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td class="layui-bg-gray">管理部门</td>
                    <td colspan="3">
                        <div class="layui-input-inline mandatory">
                            <?php foreach ($selectArr['manage_dept_nergy'] as $md) : ?>
                                <input type="checkbox" name="manage_dept" title="<?= $md['paras_desc'] ?>" lay-skin="primary" value="<?= $md['paras_value'] ?>">
                            <?php endforeach; ?>
                        </div>
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