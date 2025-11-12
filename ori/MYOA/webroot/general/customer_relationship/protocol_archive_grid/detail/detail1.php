<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
include_once("inc/utility_org.php");

$HTML_PAGE_TITLE = _("协议存档管理");
include_once("inc/header.inc.php");
include_once("common/Common.php");
include_once("../ActionModel.php");
include_once("../../common/CommonControl.php");
$model = new CommonControlModel();

if(empty($id)){
    Message("提示","对应数据不存在！");
    exit;
}

$deptId = $_SESSION['LOGIN_DEPT_ID'];
$userId = $_SESSION['LOGIN_USER_ID'];
$systemId = "contract_protocol_grid";
$companyNew = $model->getUserBuNew($deptId);
$selectParam = array(
    'is_used' => '1', // 可用参数
    'type' => 'yes_or_no,contract_protocol_type,continent,manage_dept_grid'
);
$selectArr = $model->getCommonParam($selectParam);
$yes_or_no = array();
foreach ($selectArr['yes_or_no'] as $co) {
    $yes_or_no[$co['paras_value']] = $co['paras_desc'];
}
$manage_dept = array();
foreach ($selectArr['manage_dept_grid'] as $md) {
    $manage_dept[$md['paras_value']] = $md['paras_desc'];
}
$contract_protocol_type = array();
foreach ($selectArr['contract_protocol_type'] as $md) {
    $contract_protocol_type[$md['paras_value']] = $md['paras_desc'];
}
$continent = array();
foreach ($selectArr['continent'] as $md) {
    $continent[$md['paras_value']] = $md['paras_desc'];
}

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

    <script type="text/javascript">
        $(function() {
            layui.extend({
                attach: '<?= COMMON_FILE_URL ?>/js/extends/attach'
            }).use(['form', 'layer', 'attach'], function() {
                var layer = layui.layer,
                    form = layui.form,
                    attach = layui.attach;

                init();

                // attach.attachReadonly({
                //     elem: ".btn-upload",
                //     uploadUrl: "/general/attachment/attach.php?type=contract_protocol_grid&url=common",
                //     attachObj: <?= $attachObj ?>
                // });
                $(".btn-upload").each(function() {
                    attach.attachReadonly({
                        elem: "#" + this.id,
                        uploadUrl: "/general/attachment/attach.php?type=" + this.id + "&url=common",
                        attachObj: <?= $attachObj ?>
                    });
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
        }
    </script>

<body>
    <div class="weadmin-body" style="width:90%;margin:1% 4%;">
        <form id="addForm" class="layui-form">
            <input type="hidden" name="form_type" id="form_type" value="contract_protocol_grid" />
            <input type="hidden" name="user_bu" id="user_bu" value="<?= $companyNew ?>" />
            <input type="hidden" name="dept_number" id="dept_number" value="" />
            <table class="layui-table" border="0" cellpadding="0" cellspacing="10" lay-size="sm">
                <tr>
                    <td class="layui-bg-gray" width="10%">大洲</td>
                    <td width="15%">
                        <?= $continent[$data['continent']] ?>
                    </td>
                    <td class="layui-bg-gray" width="10%">国家</td>
                    <td width="15%">
                        <?= $data['country'] ?>
                    </td>
                    <td class="layui-bg-gray" width="10%">项目代号</td>
                    <td width="15%">
                        <?= $data['project_code'] ?>
                    </td>
                    <td class="layui-bg-gray" width="10%">相对方</td>
                    <td>
                        <?= $data['contract_party'] ?>
                    </td>
                </tr>
                <tr>
                    <td class="layui-bg-gray">协议编号</td>
                    <td>
                        <?= $data['protocol_no'] ?>
                    </td>
                    <td class="layui-bg-gray">协议明细类别</td>
                    <td>
                        <?= $data['detail_type'] ?>
                    </td>
                    <td class="layui-bg-gray">协议类型</td>
                    <td colspan="3">
                        <?= $contract_protocol_type[$data['type']] ?>
                    </td>
                </tr>
                <tr>
                    <td class="layui-bg-gray">是否为独家合作协议</td>
                    <td>
                        <?= $yes_or_no[$data['if_exclusive']] ?>
                    </td>
                    <td class="layui-bg-gray">独家合作生效条件</td>
                    <td>
                        <?= $data['effective_condition'] ?>
                    </td>
                    <td class="layui-bg-gray">生效时间</td>
                    <td>
                        <?= $data['effective_date'] ?>
                    </td>
                    <td class="layui-bg-gray">终止时间</td>
                    <td>
                        <?= $data['end_date'] ?>
                    </td>
                </tr>
                <tr>
                    <td class="layui-bg-gray">签订时间</td>
                    <td>
                        <?= $data['sign_date'] ?>
                    </td>
                    <td class="layui-bg-gray">审批流程流水号</td>
                    <td>
                        <?= $data['flow_no'] ?>
                    </td>
                    <td class="layui-bg-gray">申请部门</td>
                    <td>
                        <?= $data['apply_dept_name'] ?>
                    </td>
                    <td class="layui-bg-gray">经办人</td>
                    <td>
                        <?= $data['applyUserName'] == '' ? $data['user_name'] : $data['applyUserName'] ?>
                    </td>
                </tr>
                <tr>
                    <td class="layui-bg-gray">是否存档</td>
                    <td>
                        <?= $yes_or_no[$data['if_archive']] ?>
                    </td>
                    <td class="layui-bg-gray">存档形式</td>
                    <td>
                        <?= $data['archive_mode'] ?>
                    </td>
                    <td class="layui-bg-gray">管理部门</td>
                    <td colspan="3">
                        <?= $manage_dept[$data['manage_dept']] == '' ? $data['manage_dept'] : $manage_dept[$data['manage_dept']] ?>
                    </td>
                </tr>
                <tr>
                    <td class="layui-bg-gray">附件</td>
                    <td colspan="7">
                        <input id="contract_protocol_grid" type="button" class="layui-btn layui-bg-gray btn-upload" style="display: none;" value="附件上传" readonly />
                    </td>
                </tr>
                <tr>
                    <td class="layui-bg-gray">备注</td>
                    <td colspan="7">
                        <textarea class="layui-textarea" readonly id="remark" name="remark"><?= $data['remark'] ?></textarea>
                    </td>
                </tr>
            </table>
        </form>
    </div>
</body>

</html>