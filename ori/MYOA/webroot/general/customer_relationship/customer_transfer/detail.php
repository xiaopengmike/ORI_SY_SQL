<?php
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
include_once("inc/utility_org.php");
include_once("lang.php");
// 详情页面 - 关闭
// 审批页面 - 确认、退回、关闭
// 变更登记 - 只显示信息
$HTML_PAGE_TITLE = _($LG_CUSTOMER_DATA['客户信息共享'][$_COOKIE['LANG']]);
include_once("inc/header.inc.php");
include_once("common/Common.php");
include_once("ActionModel.php");
include_once("../common/ProcessModel.php");
include_once("../common/DataModel.php");
require_once("../common/UserModel.php");
$userModel = new UserModel();
$dataModel = new DataModel();
$action = new ActionModel();
$process = new ProcessModel();
$userId = $_SESSION['LOGIN_USER_ID'];
$deptId = $_SESSION['LOGIN_DEPT_ID'];
$systemId="customer_transfer";
if (empty($id)) {
    Message("提示", "对应数据不存在！");
    exit;
}
$param['id'] = $id;
$data = $action->queryById($param);
$main = $data['main'];
$mainid = $main[id];
$customer =array();
$commonSql = "select k.*,u.user_name createUserName, u.user_name createUserName, d.dept_name deptName, " .
" tc.zh_name continentName, yc.zh_name countryName from inhe_customer_data k " .
" left join user u on u.user_id = k.user_id " .
" left join department d on d.dept_id = k.organ_id " .
" left join continent_code tc on tc.iso_two = k.continent " .
" left join country_code yc on yc.iso_three = k.country and yc.continent=k.continent " .
" where find_in_set(k.id,'$main[customer_ids]')";
$res = exequery(TD::conn(), $commonSql);
while ($item = mysql_fetch_assoc($res)) {
    $customer_names[]= $item['customer_name'];
    if($item['version']>0){
        $customer_id= $item[id];
        $changeSql = " select k.*,u.user_name createUserName, u.user_name createUserName, d.dept_name deptName,tc.zh_name continentName, yc.zh_name countryName from inhe_customer_data k "
        . "left join user u on u.user_id = k.user_id left join department d on d.dept_id = k.organ_id left join continent_code tc on tc.iso_two = k.continent left join country_code yc on yc.iso_three = k.country and yc.continent=k.continent "
        ."where 1=1 and k.type ='customer_data_change' and k.status in('R','Y') and k.version=".$item['version']." and k.related_id='".$item['form_id']."'";
        $changeData = exequery(TD::conn(), $changeSql);
        $item = mysql_fetch_assoc($changeData);
        $item[id] =$customer_id;
    }   
    $customer[]=$item;
}

// 获取审核数据
$approveData = $process->getApproveData($param);
$flowArr = $process->getFlowStep($systemId, $main['user_bu']);
$curOperate = $flowArr[$step]['operate'];
$backRecord = $process->findBackRecord($param);
$passRecord = $process->findPassRecord($param);

$conditionParam = array(
    'step' => $step,
    'form_id' => $id,
    'approve_user' => $userId,
);

$approveInfo = $process->findApproveInfo($conditionParam);
if (count($approveInfo) <= 0) {
    $operate = 'detail';
    $step = '';
}
$selectParam = array(
    'is_used' => '1', // 可用参数
    'type' => 'customer_type,customer_source'
);
$selectArr = $dataModel->getCommonParam($selectParam);
?>
<head>
    <meta charset="UTF-8">
    <meta name="renderer" content="webkit">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=0">
    <script language="Javascript" type="text/javascript" src="<?= COMMON_FILE_URL ?>/jquery-1.11.3.min.js"></script>
    <script language="Javascript" type="text/javascript" src="<?= COMMON_FILE_URL ?>/common.js"></script>
    <script language="Javascript" type="text/javascript" src="<?= COMMON_FILE_URL ?>/layui/layui.js" charset="utf-8"></script>
    <script language="Javascript" type="text/javascript" src="../detail.js"></script>
    <link rel="stylesheet" href="<?= COMMON_FILE_URL ?>/css/font.css">
    <link rel="stylesheet" href="<?= COMMON_FILE_URL ?>/css/weadmin.css">
    <link rel="stylesheet" href="../crm.css">
    <link rel="stylesheet" type="text/css" href="<?= COMMON_FILE_URL ?>/common.css">
<style>
    .border-solid {
        border: 1px solid;
    }
</style>
<script type="text/javascript">
    $(function() {
        layui.extend({
                attach: '<?= COMMON_FILE_URL ?>/js/extends/attach'
            }).use(['form', 'layer', 'attach'], function() {
                var layer = layui.layer,
                    form = layui.form,
                    attach = layui.attach;
                $.initPage('<?= $operate ?>');
                $.verifyChecked();
                $.formSubmit();
                form.render();
            });
    });

    // 页面初始化加载
    function init() {
        // textarea初始化样式
        $.each($("textarea"), function(i, n) {
            $(n).css("width", "100%");
            $(n).css("min-height", "50px");
            $(n).css("height", n.scrollHeight + "px");
            $(n).css("margin", "auto");
            $(n).css("resize", "none");
            $(n).css("overflow", "hidden");
            makeExpandingArea(document.getElementById(n.id));
        });
    }

    function getNextApprover(formId, actionType) {
        var step = $('#step').val();
        var user_bu = $('#user_bu').val();
        var type = $('#type').val();
        layer.open({
            title: "",
            type: 2,
            area: ['600px', '80%'],
            content: "next_approver.php?step=" + step + "&user_bu=" + user_bu + "&form_id=" + formId + "&actionType=" + actionType + "&type=" + type,
            success: function(layero, index) {
                setTimeout(function() {
                    layui.layer.tips('点击此处返回列表', '.layui-layer-setwin .layui-layer-close', {
                        tips: 3
                    });
                }, 500)
            },
            cancel: function() {
                var index = parent.layer.getFrameIndex(window.name);
                parent.location.reload();
                parent.layer.close(index);
            }

        });
    }
</script>

<body>
    <form id="mainForm" class="layui-form">
        <div style="width: 100%;height:auto;margin:auto;">
            <div class="r" style="height: auto;" <?php if ($isLink) : ?>style="display: none;" <?php endif; ?>>
                <div style="text-align: left;" class="layui-btn-group">
                    <table id="tab2" width="100%">
                        <tr class="layui-bg-body layui-bg-gray" id="tHeader">
                            <td style="padding: 5px;">
                                <?php if ($operate == 'approve' && count($approveInfo) > 0) : ?>
                                    <input lay-submit lay-filter="submit" name="btn_submit" actionType="Approve" value="提交" type=button class="layui-btn layui-btn-oa layui-btn-sm" />
                                    <?php if ($curOperate == 'approve') : ?>
                                        <input lay-submit lay-filter="submit" name="btn_back" actionType="Back" value="退回" type=button class="layui-btn layui-btn-oa layui-btn-sm" />
                                    <?php endif; ?>
                                <?php endif; ?>
                                <?php if ($main['status'] == 'Y' || $main['status'] == 'R' || $main['status'] == 'E'||strpos($approveInfo['work_flow'], '备案') !== false) : ?>
                                    <button type="button" class="layui-btn layui-btn-sm layui-btn-oa" onclick="$.printDetail();return false;">打印</button>
                                <?php endif; ?>
                                <input name="btn_close" value="关闭" type=button actionType="Close" class="layui-btn layui-btn-oa layui-btn-sm" onclick="closeIframe();return false;" />
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
            <br />
            <div class="weadmin-body" style="margin-top: 30px">
                <input type="hidden" name="type" id="type" value="<?= $main['type'] ?>" />
                <input type="hidden" name="user_bu" id="user_bu" value="<?= $main['user_bu'] ?>" />
                <input type="hidden" name="form_id" id="form_id" value="<?= $main['form_id'] ?>" />
                <input type="hidden" name="create_user" id="create_user" value="<?= $main['create_user'] ?>" />
                <input type="hidden" name="step" id="step" value="<?= $step ?>" />
                <table width="100%" align="center">
                    <td align="center">
                        <img alt="" src="../../../../../images/<?= $main['user_bu'] ?>/<?= $main['type'] ?>_<?= $main['user_bu'] ?>.jpg" style="width: 34%; height: auto;" title="" />
                    </td>
                </table>
                <br />
                <table class="TableBlock" width="100%" align="center"  style="margin-bottom: 5px;">
                    <tr>
                        <td>
                            <table id="tab1" width="100%" style="text-align: center;">
                                <tr>
                                   
                                    <td class=" TableContent" width="10%">原业务人员</td>
                                    <td class="TableData" width="15%">
                                        <?= $main['from_username'] ?>
                                    </td>
                                    <td class=" TableContent" width="10%">新业务人员</td>
                                    <td class="TableData" width="15%">
                                        <?= $main['to_username'] ?>
                                    </td>
                                    <td class="TableContent" width="10%">创建人</td>
                                    <td class="TableData" width="15%">
                                        <?= $main["dept_name"] . ' ' . $main['user_name'] ?>
                                    </td>
                                    <td class=" TableContent" width="10%">创建时间</td>
                                    <td class="TableData">
                                        <?= $main['write_time']?>
                                    </td>
                                </tr>
                                
                                <tr>
                                    <td class="TableHeader" colspan="8">转移客户信息</td>
                                </tr>
                                <tr>
                                <td class="TableContent" colspan="2">客户名称</td>
                                    <td class="TableContent" colspan="2">客户简称</td>
                                    <td class="TableContent" colspan="2">大洲</td>
                                    <td class="TableContent" colspan="2">国家</td>
                                </tr>
                                <?php foreach ($customer as $tk => $tv) : ?>
                                    <tr>
                                        <td class="TableData" colspan="2">
                                            <?= $tv['customer_name'] ?>
                                        </td>
                                        <td class="TableData" colspan="2">
                                            <?= $tv['short_name'] ?>
                                        </td>
                                        <td class="TableData" colspan="2">
                                            <?= $tv['continentName'] ?>
                                        </td>
                                        <td class="TableData" colspan="2">
                                            <?= $tv['countryName'] ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </table>
                            <table id="tab3" width="100%">
                                <tr>
                                    <td class="TableContent" width="20%">转移原因</td>
                                    <td class="TableData" >
                                    
                                        <textarea class="layui-textarea" id="remark" name="remark" placeholder="转移原因" ><?=$main[remark]?></textarea>
                                    </td>
                                </tr>
                            </table>
                            <?php
                                if ($main['status']=="Y"&&$operate=='detail') {
                                    include_once("../approve_opinion.php");
                                }else{
                                    include_once("./approve_opinion.php");
                                }
                            ?>
                             <?php
                            if (count($backRecord) > 0) {
                                include_once("../back_record.php");
                            }
                            ?>
                            <?php
                            if (count($passRecord) > 0) {
                                include_once("../pass_record.php");
                            }
                            ?>
                        </td>
                    </tr>
                </table>
            </div>
        </div>
    </form>
</body>

</html>