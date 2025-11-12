<?php
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
include_once("inc/utility_org.php");
$HTML_PAGE_TITLE = _("生产通知单");
include_once("inc/header.inc.php");
include_once("common/Common.php");
include_once("ActionModel.php");
require_once("../common/AttachModel.php");
require_once("../common/ProcessModel.php");
require_once("../common/DataModel.php");
require_once '../common/FormUserManageModel.php';
$process = new ProcessModel();
$attachModel = new AttachModel();
$dataModel = new DataModel();
$formUserManageModel = new FormUserManageModel();

$isHide=$formUserManageModel->isHide('lieu_receive');

$deptId = $_SESSION['LOGIN_DEPT_ID'];
$userId = $_SESSION['LOGIN_USER_ID'];
$userName = $_SESSION['LOGIN_USER_NAME'];
$currentTime = date('Y-m-d H:i:s', time());
$selectParam = array(
    'is_used' => '1', // 可用参数
    'type' => 'project_star,check_opinion,yes_or_no,inspection_type,display_mode,production_mode,
              test_report,packed_in_sequence,product_brand,form_type,product_detail_type,company,other_condition,SGC_TYPE'
);
$selectArr = $dataModel->getCommonParam($selectParam);
$company_names=array();
foreach ($selectArr['company'] as $v) {
    $company_names[$v['paras_value']]=$v['paras_desc'];
}
$product_detail_types=array();
foreach ($selectArr['product_detail_type'] as $v) {
    $product_detail_types[]=$v['paras_value'];
}
$psArr = array();
foreach ($selectArr['project_star'] as $ps) {
    $psArr[$ps['paras_value']] = $ps;
}
$checkArr = array();
foreach ($selectArr['check_opinion'] as $co) {
    $checkArr[$co['paras_value']] = $co;
}
$yes_or_no = array();
foreach ($selectArr['yes_or_no'] as $v) {
    $yes_or_no[$v['paras_value']] = $v['paras_desc'];
}
$display_mode = array();
foreach ($selectArr['display_mode'] as $v) {
    $display_mode[$v['paras_value']] = $v['paras_desc'];
}
$production_mode = array();
foreach ($selectArr['production_mode'] as $v) {
    $production_mode[$v['paras_value']] = $v['paras_desc'];
}
$test_report = array();
foreach ($selectArr['test_report'] as $v) {
    $test_report[$v['paras_value']] = $v['paras_desc'];
}
$inspection_type = array();
foreach ($selectArr['inspection_type'] as $v) {
    $inspection_type[$v['paras_value']] = $v['paras_desc'];
}
$packed_in_sequence = array();
foreach ($selectArr['packed_in_sequence'] as $v) {
    $packed_in_sequence[$v['paras_value']] = $v['paras_desc'];
}
$product_brand = array();
foreach ($selectArr['product_brand'] as $v) {
    $product_brand[$v['paras_value']] = $v['paras_desc'];
}
$other_condition = array();
foreach ($selectArr['other_condition'] as $v) {
    $other_condition[$v['paras_value']] = $v;
}

$is_sgc = array();
foreach ($selectArr['SGC_TYPE'] as $v) {
    $is_sgc[$v['paras_value']] = $v['paras_desc'];
}

$action = new ActionModel();
if (empty($id)) {
    Message("提示", "对应数据不存在！");
    exit;
}
$param['id'] = $id;
//获取变更记录
$changeData=$dataModel->getNewChange($id,'lieu_receive');
$data = $action->queryById($param);
$main = $data['main'];
$company=$main['user_bu'];
//$process->autoCreateProductOrder(array('form_id'=>$main['form_id'],'user_bu'=>$main['user_bu']));
$orderDetail = $data['orderDetail'];

$detailItem = $data['detailItem'];

//审批需要填写其他文本参数
$selectParam = array(
    'is_used' => '1', // 可用参数
    'type' => 'opinion_extra',
    'user_bu' => $main['user_bu'],
    'paras_group' => 'lieu_receive',
);
$opinion_extra = $dataModel->getCommonParam($selectParam);
$opinion_extra_arr=array();
foreach ($opinion_extra['opinion_extra'] as $ps) {
    $opinion_extra_arr[$ps['type_desc']][$ps['paras_value']] = $ps;
}

//审批需要填写其他附件参数
$selectParam = array(
    'is_used' => '1', // 可用参数
    'type' => 'opinion_attachment',
    'user_bu' => $main['user_bu'],
    'paras_group' => 'lieu_receive',
);
$opinion_attachment = $dataModel->getCommonParam($selectParam);
$opinion_attachment_arr=array();
foreach ($opinion_attachment['opinion_attachment'] as $ps) {
    $opinion_attachment_arr[$ps['type_desc']][$ps['paras_value']] = $ps['paras_desc'];
}

//审批需要填写其他选择参数
$selectParam = array(
    'is_used' => '1', // 可用参数
    'type' => 'opinion_select',
    'user_bu' => $main['user_bu'],
    'paras_group' => 'lieu_receive',
);
$opinion_select = $dataModel->getCommonParam($selectParam);
$opinion_select_arr=array();
foreach ($opinion_select['opinion_select'] as $ps) {
    $selectParam = array(
        'is_used' => '1', // 可用参数
        'type' => $ps['paras_value']
    );
    $opinion_select_data = $dataModel->getCommonParam($selectParam);
    $opinion_select_arr[$ps['type_desc']][$ps['paras_value']] = array("name"=>$ps['paras_desc'],"data"=>$opinion_select_data);
}


// 获取审核数据
$approveData = $process->getApproveData($param);
// $systemId = "lieu_receive";
$flowArr = $process->getFlowStep($main['type'], $main['user_bu']);
$new_step=$step;
//流程设置中董事长前面一个步骤为必须经过步骤
if(strpos($main['form_id'],strtolower($main['user_bu']).'_')===0&&$flowArr[$step+1]['approver']=='meterw'){
    $new_step=$step+1;
}
$curOperate = $flowArr[$step]['operate'];
$attachObj = $attachModel->getAttachById($id);
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
//自动生成订单处理
if(strpos($main['form_id'],strtolower($main['user_bu']).'_')>=0&&$operate == 'detail'){
    $sql = " select * from inhe_flow_manage where type='$main[type]' and common_opinion='2' group by user_bu";
    $res = exequery(TD::conn(), $sql);
    $approverInfo=array();
    while ($item = mysql_fetch_assoc($res)) {
        $approverInfo[$item['user_bu']] = $item;
    }
    $step = empty($approverInfo)?'':$approverInfo[$main['user_bu']]['step'];
}

?>

<head>
    <meta charset="UTF-8">
    <meta name="renderer" content="webkit">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=0">
    <script language="Javascript" type="text/javascript" src="<?= COMMON_FILE_URL ?>/jquery-1.11.3.min.js"></script>
    <script language="Javascript" type="text/javascript" src="<?= COMMON_FILE_URL ?>/common.js"></script>
    <script language="Javascript" type="text/javascript" src="<?= COMMON_FILE_URL ?>/layui/layui.js" charset="utf-8"></script>
    <script language="Javascript" type="text/javascript" src="../detail.js?v.20220312"></script>
    <link rel="stylesheet" href="<?= COMMON_FILE_URL ?>/css/font.css">
    <link rel="stylesheet" href="<?= COMMON_FILE_URL ?>/css/weadmin.css">
    <link rel="stylesheet" href="../crm.css">
    <link rel="stylesheet" type="text/css" href="<?= COMMON_FILE_URL ?>/common.css">
    <script type="text/javascript">
        $(function() {
            layui.extend({
                attach: '<?= COMMON_FILE_URL ?>/js/extends/attach'
            }).use(['form', 'layer', 'attach'], function() {
                var layer = layui.layer,
                    form = layui.form;
                $.initPage('<?= $operate ?>');
                $.verifyChecked();
                var attachObj = <?= $attachObj ?>;
                $.initAttachList(attachObj);
                $.formSubmit();
                form.render();
            });
        });

        function getNextApprover(formId, actionType) {
            var step = $('#step').val();
            var user_bu = $('#user_bu').val();
            var type = $('#type').val();
            var E = $('#E').val();
            var S = $('#S').val();
            var N = $('#N').val();
            var G = $('#G').val();
            var is_collective = $('#is_collective').val()=="是"?1:0;
            var EP = $('#if_external_procure').val();
            layer.open({
                title: "",
                type: 2,
                area: ['600px', '80%'],
                resize:true,
                content: "next_approver.php?step=" + step + "&user_bu=" + user_bu + "&form_id=" + formId + "&actionType=" + actionType + "&type=" + type + "&E=" + E + "&S=" + S + "&N=" + N + "&G=" + G+"&is_collective="+is_collective+"&EP="+EP,
                success: function(layero, index) {
                    setTimeout(function() {
                        layui.layer.tips('点击此处返回列表', '.layui-layer-setwin .layui-layer-close', {
                            tips: 3
                        });
                    }, 500)
                }
            });
        }
    </script>
</head>

<body>
    <div style="width: 100%;height:auto;margin:auto;">
        <form id="mainForm" class="layui-form">
            <div class="r" style="height: auto;">
                <div style="text-align: left;" class="layui-btn-group">
                    <table id="tab" width="100%">
                        <tr class="layui-bg-body">
                            <td style="padding: 5px;">
                                <?php if ($operate == 'approve' && count($approveInfo) > 0) : ?>
                                    <input lay-submit lay-filter="submit" name="btn_submit" actionType="Approve" value="提交" type=button class="layui-btn layui-btn-oa layui-btn-sm" />
                                    <?php if ($curOperate == 'approve') : ?>
                                        <input lay-submit lay-filter="submit" name="btn_back" actionType="Back" value="退回" type=button class="layui-btn layui-btn-oa layui-btn-sm" />
                                    <?php endif; ?>
                                <?php endif; ?>
                                <?php if ($main['status'] == 'Y' || $main['status'] == 'R' || $main['status'] == 'E') : ?>
                                    <button type="button" class="layui-btn layui-btn-sm layui-btn-oa" onclick="$.printDetail();return false;">打印</button>
                                <?php endif; ?>
                                <input name="btn_close" value="关闭" type=button actionType="Close" class="layui-btn layui-btn-oa layui-btn-sm" onclick="$.closeIframe();return false;" />
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
            <br />
            <!--startprint-->
            <div class="weadmin-body" style="width:90%;margin:1% 4%;">
                <input type="hidden" name="soft_count" id="E" value="<?= count($orderDetail['meter_one'])+count($orderDetail['meter_two'])+count($orderDetail['meter_three']) ?>" />
                <input type="hidden" name="soft_count" id="S" value="<?= in_array('Soft',$non_types)?1:0 ?>" />
                <input type="hidden" name="soft_count" id="N" value="<?= in_array('Inhenergy',$non_types)?1:0 ?>" />
                <input type="hidden" name="soft_count" id="G" value="<?= in_array('Inhegrid',$non_types)?1:0 ?>" />
                <input type="hidden" name="type" id="type" value="<?= $main['type'] ?>" />
                <input type="hidden" name="user_bu" id="user_bu" value="<?= $main['user_bu'] ?>" />
                <input type="hidden" name="form_id" id="form_id" value="<?= $id ?>" />
                <input type="hidden" name="step" id="step" value="<?= $new_step ?>" />
                <table width="100%" align="center">
                    <tr>
                        <td align="center">
                        <?php if ($main['form_type'] == '02') { ?>
                                <img id="page_title" alt="" src="../../../../../images/<?= $main['user_bu'] ?>/<?= str_replace('trial', 'stock', $main['type']) ?>_<?= $main['user_bu'] ?>.jpg" style="width: 34%; height: auto;" title="" />
                            <?php } elseif($main['form_type'] == '03') { ?>
                                <img  id="page_title" alt="" src="../../../../../images/<?= $main['user_bu'] ?>/Replenishment_production_notice.jpg" style="width: 34%; height: auto;" title="" />
                            <?php } else { ?>
                                <img  id="page_title" alt="" src="../../../../../images/<?= $main['user_bu'] ?>/<?= $main['type'] ?>_<?= $main['user_bu'] ?>.jpg" style="width: 34%; height: auto;" title="" />
                                
                            <?php } ?>
                        </td>
                    </tr>
                    <tr>
                        <td>
                        <?php include_once("instructions.php"); ?>
                        </td>
                    </tr>
                </table>
                <br />
                <table class="TableBlock" width="100%" align="center" border="2">
                    <tr>
                        <td>
                            <?php include_once("detail/customer_info.php"); ?>
                            <?php include_once("detail/order_detail.php"); ?>
                            <?php
                                if ($main['status']=="Y"&&$operate=='detail') {
                                    include_once("../approve_opinion.php");
                                }else{
                                    if(strpos($main['form_id'],strtolower($main['user_bu']).'_')===0){
                                        include_once("detail/approve_opinion_auto.php"); 
                                    }else{
                                        include_once("detail/approve_opinion.php"); 
                                    }
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
                <?php include_once("../common/list/change_html.php"); ?>
            </div>
            <!--endprint-->
        </form>
    </div>
</body>

</html>