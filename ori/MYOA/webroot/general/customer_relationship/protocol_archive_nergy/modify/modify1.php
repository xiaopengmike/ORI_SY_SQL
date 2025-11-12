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
$systemId = "contract_protocol_nergy";
$companyNew = $model->getUserBuNew($deptId);
$selectParam = array(
    'is_used' => '1', // 可用参数
    'type' => 'yes_or_no,contract_protocol_type,continent,manage_dept_nergy'
);
$selectArr = $model->getCommonParam($selectParam);

$action = new ActionModel();
$param['id'] = $id;
$data = $action->queryById($param);
$attachObj = $action->getAttachList($id,$systemId);

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

                $(".date").each(function() {
                    laydate.render({
                        elem: this,
                        theme: '#027AFF',
                        range: false
                    });
                });

                init();

                attach.attachList({
                    elem: ".btn-upload",
                    uploadUrl: "/general/attachment/attach.php?type=contract_protocol_nergy&url=common",
                    attachObj: <?= $attachObj ?>
                });

                form.render();

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

                $('[data-type="customer_name"]').each(function() {
                    this.onfocus = function() {

                        // 获取客户数据
                        // getCustomerSelect(this);
                    }
                });

                form.on('select(user_name)', function(data) {
                    $("#real_name").val(data.elem[data.elem.selectedIndex].text);

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

            selectUserByDeptId('<?= $data['apply_dept'] ?>', '', '<?= $data['user_id'] ?>');
        }

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
                            selected = "";
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
            <input type="hidden" name="id" id="id" value="<?= $data['id'] ?>" />
            <input type="hidden" name="form_type" id="form_type" value="contract_protocol_nergy" />
            <input type="hidden" name="user_bu" id="user_bu" value="<?= $companyNew ?>" />
            <input type="hidden" name="dept_number" id="dept_number" value="" />
            <table class="layui-table" border="0" cellpadding="0" cellspacing="10" lay-size="sm">
                <tr>
                    <td class="layui-bg-gray">大洲</td>
                    <td>
                        <div class="layui-input-inline mandatory">
                            <select id="continent" name="continent" lay-search lay-verify="required">
                                <option value=''>请选择</option>
                                <?php foreach ($selectArr['continent'] as $sc) : ?>
                                    <option value="<?= $sc['paras_value'] ?>" <?php if ($sc['paras_value'] == $data['continent']) : ?>selected<?php endif; ?>><?= $sc['paras_desc'] ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </td>
                    <td class="layui-bg-gray">国家</td>
                    <td>
                        <input type="text" name="country" id="country" value="<?= $data['country'] ?>" class="layui-input" placeholder="请输入国家名称" />
                    </td>
                    <td class="layui-bg-gray">项目代号</td>
                    <td>
                        <input type="text" name="project_code" id="project_code" value="<?= $data['project_code'] ?>" class="layui-input" placeholder="请输入项目代号" />
                    </td>
                    <td class="layui-bg-gray">相对方</td>
                    <td>
                        <input type="text" name="contract_party" id="contract_party" value="<?= $data['contract_party'] ?>" class="layui-input" placeholder="请输入相对方" />
                    </td>
                </tr>
                <tr>
                    <td class="layui-bg-gray">协议编号</td>
                    <td>
                        <input type="text" name="protocol_no" id="protocol_no" value="<?= $data['protocol_no'] ?>" class="layui-input" placeholder="请输入协议编号" />
                    </td>
                    <td class="layui-bg-gray">协议明细类别</td>
                    <td>
                        <input type="text" name="detail_type" id="detail_type" value="<?= $data['detail_type'] ?>" class="layui-input" placeholder="请输入协议明细类别" />
                    </td>
                    <td class="layui-bg-gray">协议类型</td>
                    <td colspan="3">
                        <div class="layui-input-inline mandatory">
                            <select id="type" name="type" lay-search lay-verify="required">
                                <option value=''>请选择</option>
                                <?php foreach ($selectArr['contract_protocol_type'] as $yVal) : ?>
                                    <option value="<?= $yVal['paras_value'] ?>" <?php if ($yVal['paras_value'] == $data['type']) : ?>selected<?php endif; ?>><?= $yVal['paras_desc'] ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td class="layui-bg-gray">是否为独家合作协议</td>
                    <td>
                        <div class="layui-input-inline mandatory">
                            <select id="if_exclusive" name="if_exclusive" lay-search lay-verify="required">
                                <option value=''>请选择</option>
                                <?php foreach ($selectArr['yes_or_no'] as $yVal) : ?>
                                    <option value="<?= $yVal['paras_value'] ?>" <?php if ($yVal['paras_value'] == $data['if_exclusive']) : ?>selected<?php endif; ?>><?= $yVal['paras_desc'] ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </td>
                    <td class="layui-bg-gray">独家合作生效条件</td>
                    <td>
                        <input type="text" name="effective_condition" id="effective_condition" value="<?= $data['effective_condition'] ?>" class="layui-input" placeholder="请输入独家合作生效条件" />
                    </td>
                    <td class="layui-bg-gray">生效时间</td>
                    <td>
                        <input type="text" name="effective_date" id="effective_date" value="<?= $data['effective_date'] ?>" class="layui-input date" placeholder="请选择生效时间" />
                    </td>
                    <td class="layui-bg-gray">终止时间</td>
                    <td>
                        <input type="text" name="end_date" id="end_date" value="<?= $data['end_date'] ?>" class="layui-input" placeholder="请输入终止时间" />
                    </td>
                </tr>
                <tr>
                    <td class="layui-bg-gray">签订时间</td>
                    <td>
                        <input type="text" name="sign_date" id="sign_date" value="<?= $data['sign_date'] ?>" class="layui-input date" placeholder="请选择签订时间" />
                    </td>
                    <td class="layui-bg-gray">审批流程流水号</td>
                    <td>
                        <input type="text" name="flow_no" id="flow_no" value="<?= $data['flow_no'] ?>" class="layui-input" placeholder="请输入审批流程流水号" />
                    </td>
                    <td class="layui-bg-gray">申请部门</td>
                    <td>
                        <div class="layui-input-inline" style="width: 220px;">
                            <input type="text" id="menu_parent_name" value="<?= $data['apply_dept_name'] ?>" class="layui-input" data-type="dept_select" name="apply_dept_name" style="width: 220px;" placeholder="请选择申请部门" />
                            <input type="hidden" id="menu_parent" name="apply_dept" value="<?= $data['apply_dept'] ?>">
                        </div>
                    </td>
                    <td class="layui-bg-gray">经办人</td>
                    <td>
                        <div class="layui-input-inline" style="width: 220px;">
                            <input type="hidden" id="real_name" name="real_name" value="<?= $data['user_name'] ?>" />
                            <select name="user_name" id="user_name" lay-filter="user_name" placeholder="请选择经办人">
                                <option value=''>请选择</option>
                            </select>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td class="layui-bg-gray">是否存档</td>
                    <td>
                        <div class="layui-input-inline mandatory">
                            <select id="if_archive" name="if_archive" lay-search lay-verify="required">
                                <option value=''>请选择</option>
                                <?php foreach ($selectArr['yes_or_no'] as $yVal) : ?>
                                    <option value="<?= $yVal['paras_value'] ?>" <?php if ($yVal['paras_value'] == $data['if_archive']) : ?>selected<?php endif; ?>><?= $yVal['paras_desc'] ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </td>
                    <td class="layui-bg-gray">存档形式</td>
                    <td>
                        <input type="text" name="archive_mode" id="archive_mode" value="<?= $data['archive_mode'] ?>" class="layui-input" placeholder="请选择存档形式" />
                    </td>
                    <td class="layui-bg-gray">管理部门</td>
                    <td colspan="3">
                        <div class="layui-input-inline mandatory">
                            <select id="manage_dept" name="manage_dept" lay-search lay-verify="required">
                                <option value=''>请选择</option>
                                <?php foreach ($selectArr['manage_dept_nergy'] as $md) : ?>
                                    <option value="<?= $md['paras_value'] ?>" <?php if ($md['paras_value'] == $data['manage_dept']) : ?>selected<?php endif; ?>><?= $md['paras_desc'] ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td class="layui-bg-gray">附件</td>
                    <td colspan="7">
                        <input type="button" id="contract_protocol_nergy" class="layui-btn layui-bg-gray btn-upload" style="display: inline!important;" value="附件上传" />
                    </td>
                </tr>
                <tr>
                    <td class="layui-bg-gray">备注</td>
                    <td colspan="7">
                        <textarea class="layui-textarea" id="remark" name="remark" placeholder="请输入备注"><?= $data['remark'] ?></textarea>
                    </td>
                </tr>

                <tr>
                    <td colspan="8" align="center" style="padding: 5px;">
                        <input lay-submit lay-filter="submit" name="btn_submit" actionType="Submit" value="提交" type=button class="layui-btn layui-btn-oa layui-btn-sm" />
                        <!-- <input lay-submit lay-filter="submit" name="btn_save" actionType="Save" value="保存" type=button class="layui-btn layui-btn-oa layui-btn-sm" /> -->
                    </td>
                </tr>
            </table>
        </form>
    </div>
    <br />
    <?php include_once("common/mydtree_new.php"); ?>
</body>

</html>