<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
include_once("inc/utility_org.php");

$HTML_PAGE_TITLE = _("项目代号信息编辑");
include_once("inc/header.inc.php");
include_once("common/Common.php");
include_once("ActionModel.php");
include_once("../common/DataModel.php");
$model = new DataModel();

$deptId = $_SESSION['LOGIN_DEPT_ID'];

$selectParam = array(
    'is_used' => '1', // 可用参数
    'type' => 'yes_or_no,company'
);
$selectArr = $model->getCommonParam($selectParam);

$action = new ActionModel();
$param['id'] = $id;
$data = $action->queryById($param);

?>

<head>
    <meta charset="UTF-8">
    <meta name="renderer" content="webkit">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=0">
    <script language="Javascript" type="text/javascript" src="<?= COMMON_FILE_URL ?>/jquery-1.11.3.min.js"></script>
    <script language="Javascript" type="text/javascript" src="<?= COMMON_FILE_URL ?>/common.js"></script>
    <script language="Javascript" type="text/javascript" src="<?= COMMON_FILE_URL ?>/layui/layui.js" charset="utf-8"></script>
    <link rel="stylesheet" href="<?= COMMON_FILE_URL ?>/css/font.css">
    <link rel="stylesheet" href="<?= COMMON_FILE_URL ?>/css/weadmin.css">
    <link rel="stylesheet" type="text/css" href="<?= COMMON_FILE_URL ?>/dtreeck.css">
    <script language="Javascript" type="text/javascript" src="<?= COMMON_FILE_URL ?>/dtreeck.js"></script>

    <script type="text/javascript">
        $(function() {
            layui.use(['form', 'layer', 'table'], function() {
                var layer = layui.layer,
                    form = layui.form,
                    table = layui.table;
                
                selectUserByDeptId('<?= $data['dept_id'] ?>', '', '<?= $data['user_id'] ?>');

                form.on('submit(submit)', function(data) {
                    var btnVal = data.elem.value;
                    var actionType = $(data.elem).attr("actionType");
                    var url = "action.php?action=" + actionType;
                    $.post(url, $("#mainForm").serializeArray(), function(data) {
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

                $("#project_code").blur(function() {
                    var url = "action.php?action=Retrieve";
                    // 校验系统项目代号
                    if (this.value != '') {
                        $.post(url, {
                            dataType: 'verifyProjectCode',
                            projectCode: this.value
                        }, function(data) {
                            var reJson = null;
                            reJson = eval('(' + data + ')');
                            if (reJson.code == '200') {
                                if (reJson.data) {
                                    layer.msg('项目代号已存在！');
                                    $("#project_code").val('');
                                }
                            }
                        });
                    }
                });
                
                form.on('select(user_id)', function(data) {
                    $("#user_name").val(data.elem[data.elem.selectedIndex].text);

                    form.render();
                });

                form.render();
            });
        });

        function selectUserByDeptId(deptId, number, defaultVal) {
            layui.use(['form', 'layer'], function() {
                var layer = layui.layer,
                    form = layui.form;
                if (deptId != '') {
                    $.post('user_info.php?deptId=' + deptId, {}, function(data) {
                        var obj = eval('(' + data + ')');

                        $("#user_id" + number).empty();
                        $("#user_id" + number).append("<option value=''>请选择</option>");
                        var selected = "";
                        for (i = 0; i < obj.length; i++) {
                            if (defaultVal == obj[i].user_id) {
                                selected = " selected ";
                            }
                            $("#user_id" + number).append("<option value='" + obj[i].user_id + "' " + selected + ">" + obj[i].user_name + "</option>");
                        }
                    });
                }
                form.render();
            });
        }
    </script>

<body>
    <div class="weadmin-body" style="width:90%;margin:1% 4%;">
        <form id="mainForm" class="layui-form">
            <input type="hidden" name="id" id="id" value="<?= $id ?>" />
            <input type="hidden" name="form_type" id="form_type" value="project_code" />
            <input type="hidden" name="user_bu" id="user_bu" value="<?= $main['user_bu'] ?>" />
            <input type="hidden" name="dept_number" id="dept_number" value="" />
            <table class="layui-table" border="0" cellpadding="0" cellspacing="10" lay-size="sm">
                <tr>
                    <td class="layui-bg-gray">项目代号</td>
                    <td>
                        <input type="text" name="project_code" id="project_code" lay-verify="required" class="layui-input" placeholder="请输入项目代号" autocomplete="off" value="<?= $data['project_code'] ?>" />
                    </td>
                </tr>
                <tr>
                    <td class="layui-bg-gray">项目所在国</td>
                    <td>
                        <input type="text" name="country" id="country" class="layui-input" lay-verify="required" placeholder="请输入国家名称" value="<?= $data['country'] ?>" />
                    </td>
                </tr>
                <tr>
                    <td class="layui-bg-gray">项目归属公司</td>
                    <td>
                        <div class="layui-input-inline mandatory">
                            <select id="company" name="company" lay-search lay-verify="required">
                                <option value=''>请选择</option>
                                <?php foreach ($selectArr['company'] as $yVal) : ?>
                                    <option value="<?= $yVal['paras_value'] ?>" <?php if ($yVal['paras_value'] == $data['company']) : ?>selected<?php endif; ?>><?= $yVal['paras_desc'] ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </td>
                </tr>
                <!-- <tr>
                    <td class="layui-bg-gray">是否正式立项</td>
                    <td colspan="3">
                        <div class="layui-input-inline mandatory">
                            <select id="if_project" name="if_project" lay-search lay-verify="required">
                                <option value=''>请选择</option>
                                <?php foreach ($selectArr['yes_or_no'] as $yVal) : ?>
                                    <option value="<?= $yVal['paras_value'] ?>" <?php if ($yVal['paras_value'] == $data['if_project']) : ?>selected<?php endif; ?>><?= $yVal['paras_desc'] ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </td>
                </tr> -->
                <tr>
                    <td class="layui-bg-gray">业务部门</td>
                    <td>
                        <div class="layui-input-inline" style="width: 220px;">
                            <input type="text" id="menu_parent_name" class="layui-input" data-type="dept_select" name="dept_name" style="width: 220px;" placeholder="请选择申请部门" value="<?= $data['dept_name'] ?>" />
                            <input type="hidden" id="menu_parent" name="dept_id" value="<?= $data['dept_id'] ?>">
                        </div>
                    </td>
                </tr>
                <tr>
                    <td class="layui-bg-gray">业务负责人</td>
                    <td>
                        <div class="layui-input-inline" style="width: 220px;">
                            <input type="hidden" id="user_name" name="user_name" value="<?= $data['user_name'] ?>" />
                            <select name="user_id" id="user_id" lay-filter="user_id" placeholder="请选择经办人">
                                <option value=''>请选择</option>
                            </select>
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
    <?php
    include_once("common/mydtree_new.php");
    ?>
</body>

</html>