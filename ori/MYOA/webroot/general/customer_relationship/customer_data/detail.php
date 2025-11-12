<?php
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
include_once("inc/utility_org.php");
include_once("lang.php");
// 详情页面 - 关闭
// 审批页面 - 确认、退回、关闭
// 变更登记 - 只显示信息
$HTML_PAGE_TITLE = _($LG_CUSTOMER_DATA['客户信息登记表'][$_COOKIE['LANG']]);
include_once("inc/header.inc.php");
include_once("common/Common.php");
include_once("ActionModel.php");
include_once("../common/ProcessModel.php");
include_once("../common/DataModel.php");
require_once("../common/UserModel.php");
$userModel = new UserModel();
$dataModel = new DataModel();
$userId = $_SESSION['LOGIN_USER_ID'];
$deptId = $_SESSION['LOGIN_DEPT_ID'];
$action = new ActionModel();
if (empty($id)) {
    Message("提示", "对应数据不存在！");
    exit;
}

$param['id'] = $id;
$data = $action->queryById($param);
$main = $data['main'];
$mainid = $main[id];

//存在变更，则取上次变更信息
// if($main['version']>0 && $main['type']=="customer_data"){
//     $item=$dataModel->getLastChange($main['form_id'],$main['version'],'customer_data_change');
//     if($item['form_id']){
//         $data = $action->queryById(array("id"=>$item['form_id'],"customer_id"=>$mainid));
//         $main = $data['main'];
//         $main['type']="customer_data";
//     }
// }
$contacts = $data['contacts'];
$company = $data['company'];
$share = $data['share'];
$flow = $data['flow'];

$process = new ProcessModel();
// 获取审核数据
$approveData = $process->getApproveData($param);
$flowArr = $process->getFlowStep($main[type], $main['user_bu']);
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
    'type' => 'customer_type,customer_source,yes_or_no'
);
$selectArr = $dataModel->getCommonParam($selectParam);
//获取变更记录
$changeData=$dataModel->getNewChange($id,"customer_data");
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
                            <td align="center" style="padding: 5px;">
                                <?php if ($operate == 'approve' && count($approveInfo) > 0) : ?>
                                    <input lay-submit lay-filter="submit" name="btn_approve" actionType="Approve" value="<?=$LG_CUSTOMER_DATA['提交'][$_COOKIE['LANG']]?>" type=button class="layui-btn layui-btn-oa layui-btn-sm" />
                                    <?php if ($operate == 'approve'&&strpos($approveInfo['work_flow'], '备案') === false) : ?>
                                    <input lay-submit lay-filter="submit" name="btn_back" actionType="Back" value="<?=$LG_CUSTOMER_DATA['退回'][$_COOKIE['LANG']]?>" type=button class="layui-btn layui-btn-oa layui-btn-sm" />
                                    <?php endif; ?>
                                <?php endif; ?>
                                <?php if ($main['status'] == 'Y' || $main['status'] == 'R' || $main['status'] == 'E'||strpos($approveInfo['work_flow'], '备案') !== false) : ?>
                                    <button type="button" class="layui-btn layui-btn-sm layui-btn-oa" onclick="$.printDetail();return false;">打印</button>
                                <?php endif; ?>
                                <input name="btn_close" value="<?=$LG_CUSTOMER_DATA['关闭'][$_COOKIE['LANG']]?>" type=button class="layui-btn layui-btn-oa layui-btn-sm" onclick="closeIframe();return false;" />
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
                                    <td class="TableContent" width="10%"><?=$LG_CUSTOMER_DATA['客户名称'][$_COOKIE['LANG']]?></td>
                                    <td class="TableData" width="15%">
                                        <?= $main['customer_name'] ?>
                                    </td>
                                    <td class=" TableContent" width="10%"><?=$LG_CUSTOMER_DATA['简称'][$_COOKIE['LANG']]?></td>
                                    <td class="TableData" width="15%">
                                        <?= $main['short_name'] ?>
                                    </td>
                                    <td class=" TableContent" width="10%"><?=$LG_CUSTOMER_DATA['大洲'][$_COOKIE['LANG']]?></td>
                                    <td class="TableData" width="15%">
                                        <?= $main['continentName'] == '' ? $main["continent"] : ($_COOKIE['LANG']=="EN"?$main["continentEnName"]:$main["continentName"]) ?>
                                    </td>
                                    <td class=" TableContent" width="10%"><?=$LG_CUSTOMER_DATA['国家'][$_COOKIE['LANG']]?></td>
                                    <td class="TableData">
                                        <?= $main['countryName'] == '' ? $main["country"] : ($_COOKIE['LANG']=="EN"?$main["countryEnName"]:$main["countryName"]) ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td class=" TableContent"><?=$LG_CUSTOMER_DATA['网址'][$_COOKIE['LANG']]?></td>
                                    <td class="TableData" colspan="3">
                                        <?= $main['website'] ?>
                                    </td>
                                    <td class=" TableContent"><?=$LG_CUSTOMER_DATA['地址'][$_COOKIE['LANG']]?></td>
                                    <td class="TableData" colspan="3">
                                        <?= $main['address'] ?>
                                    </td>
                                </tr>
                                <?if($main[user_bu]=="INHENERGY"||$main[user_bu]=="HKNERGY"):?>
                                    <tr>
                                    <td class="TableContent"><?=$LG_CUSTOMER_DATA['类型'][$_COOKIE['LANG']]?></td>
                                    <td class="TableData">
                                        <?php foreach($selectArr['customer_type'] as $customer_type):?>
                                            <?php if ($main['customer_type'] == $customer_type['paras_value']) : ?><?=$LG_CUSTOMER_DATA[$customer_type['paras_desc']][$_COOKIE['LANG']]?><?php endif; ?>
                                        <?php endforeach;?>
                                    </td>
                                    <td class="TableContent"><?=$LG_CUSTOMER_DATA['客户来源'][$_COOKIE['LANG']]?></td>
                                    <td class="TableData" >
                                            <?php foreach ($selectArr['customer_source'] as $val) : ?>
                                                <?php if ($main['customer_source'] == $val['paras_value']) : ?><?=$LG_CUSTOMER_DATA[$val['paras_desc']][$_COOKIE['LANG']]?><?php endif; ?>
                                            <?php endforeach; ?>
                                    </td>
                                    <td class="TableContent"><?=$LG_CUSTOMER_DATA['是否有效'][$_COOKIE['LANG']]?></td>
                                    <td class="TableData" >
                                            <?php foreach ($selectArr['yes_or_no'] as $val) : ?>
                                                <?php if ($main['is_vaild'] == $val['paras_value']) : ?><?=$LG_CUSTOMER_DATA[$val['paras_desc']][$_COOKIE['LANG']]?><?php endif; ?>
                                            <?php endforeach; ?>
                                    </td>
                                    <td class="TableData" colspan="2"></td>
                                    <!-- <td class="TableContent"><?=$LG_CUSTOMER_DATA['星级'][$_COOKIE['LANG']]?></td>
                                    <td class="TableData" >
                                            <?php foreach ($selectArr['project_level'] as $val) : ?>
                                                <?php if ($main['project_star'] == $val['paras_value']) : ?><?=$LG_CUSTOMER_DATA[$val['paras_desc']][$_COOKIE['LANG']]?><?php endif; ?>
                                            <?php endforeach; ?>
                                    </td>
                                    <td class="TableContent"><?=$LG_CUSTOMER_DATA['星级图标'][$_COOKIE['LANG']]?></td>
                                    <td class="TableData" colspan="3">
                                        <div id="star_icon">
                                        <font style="color: red;font-size:20px"><?= $project_star[$main['project_star']]['extra'] ?></font>
                                        </div>
                                    </td> -->
                                    </tr>
                                <?endif;?>
                                <tr>
                                    <td class=" TableContent"><?=$LG_CUSTOMER_DATA['客户概述'][$_COOKIE['LANG']]?></td>
                                    <td class="TableData" colspan="7">
                                        <textarea readonly class="layui-textarea TextDetails" id="summarize" name="summarize"><?= $main['summarize'] ?></textarea>
                                    </td>
                                </tr>
                                <?if($main[user_bu]=="INHENERGY"||$main[user_bu]=="HKNERGY"):?>
                                <tr>
                                    <td class=" TableContent"><?=$LG_CUSTOMER_DATA['客户关注'][$_COOKIE['LANG']]?></td>
                                    <td class="TableData" colspan="7">
                                        <textarea readonly class="layui-textarea TextDetails" id="focus" name="focus"><?= $main['focus'] ?></textarea>
                                    </td>
                                </tr>
                                <?endif;?>
                                <tr>
                                    <td class="TableHeader" colspan="8"><?=$LG_CUSTOMER_DATA['联系人'][$_COOKIE['LANG']]?></td>
                                </tr>
                                <tr>
                                <td class="TableContent"><?=$LG_CUSTOMER_DATA['姓名'][$_COOKIE['LANG']]?></td>
                                    <td class="TableContent"><?=$LG_CUSTOMER_DATA['电话1'][$_COOKIE['LANG']]?></td>
                                    <td class="TableContent"><?=$LG_CUSTOMER_DATA['电话2'][$_COOKIE['LANG']]?></td>
                                    <td class="TableContent"><?=$LG_CUSTOMER_DATA['邮箱'][$_COOKIE['LANG']]?></td>
                                    <td class="TableContent"><?=$LG_CUSTOMER_DATA['职位'][$_COOKIE['LANG']]?></td>
                                    <td class="TableContent" colspan="2"><?=$LG_CUSTOMER_DATA['地址'][$_COOKIE['LANG']]?></td>
                                    <td class="TableContent"><?=$LG_CUSTOMER_DATA['备注'][$_COOKIE['LANG']]?></td>
                                </tr>
                                <?php foreach ($contacts as $tk => $tv) : ?>
                                    <tr>
                                        <td class="TableData">
                                            <?= $tv['name'] ?>
                                        </td>
                                        <td class="TableData">
                                            <?= $tv['phone_one'] ?>
                                        </td>
                                        <td class="TableData">
                                            <?= $tv['phone_two'] ?>
                                        </td>
                                        <td class="TableData">
                                            <?= $tv['email'] ?>
                                        </td>
                                        <td class="TableData">
                                            <?= $tv['position'] ?>
                                        </td>
                                        <td class="TableData" colspan="2">
                                            <?= $tv['address'] ?>
                                        </td>
                                        <td class="TableData">
                                            <?= $tv['remark'] ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                                <tr>
                                    <td class="TableContent"><?=$LG_CUSTOMER_DATA['客户关联公司'][$_COOKIE['LANG']]?><br />（<?=$LG_CUSTOMER_DATA['合同相对方'][$_COOKIE['LANG']]?>）</td>
                                    <td class="TableContent"><?=$LG_CUSTOMER_DATA['简称'][$_COOKIE['LANG']]?></td>
                                    <td class="TableContent"><?=$LG_CUSTOMER_DATA['国家'][$_COOKIE['LANG']]?></td>
                                    <td class="TableContent" colspan="3"><?=$LG_CUSTOMER_DATA['地址'][$_COOKIE['LANG']]?></td>
                                    <td class="TableContent"><?=$LG_CUSTOMER_DATA['联系人姓名'][$_COOKIE['LANG']]?></td>
                                    <td class="TableContent"><?=$LG_CUSTOMER_DATA['电话'][$_COOKIE['LANG']]?></td>
                                </tr>
                                <?php foreach ($company as $ck => $cv) : ?>
                                    <tr>
                                        <td class="TableData">
                                            <?= $cv['contract_party'] ?>
                                        </td>
                                        <td class="TableData">
                                            <?= $cv['short_name'] ?>
                                        </td>
                                        <td class="TableData">
                                            <?= $cv['country'] ?>
                                        </td>
                                        <td class="TableData" colspan="3">
                                            <?= $cv['address'] ?>
                                        </td>
                                        <td class="TableData">
                                            <?= $cv['contact_name'] ?>
                                        </td>
                                        <td class="TableData">
                                            <?= $cv['phone'] ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </table>
                            <?if(strpos($main['type'], '_change') != false):?>
                            <table id="tab3" width="100%">
                                <tr>
                                    <td class="TableContent"  style="text-align: left;width: 25%"><?=$LG_CUSTOMER_DATA['变更说明'][$_COOKIE['LANG']]?></td>
                                    <td class="TableData" >
                                    
                                        <textarea readonly class="layui-textarea" id="change_explain" name="change_explain" placeholder="变更说明" ><?=$main[change_explain]?></textarea>
                                    </td>
                                </tr>
                            </table>
                            <?endif;?>
                            <table id="tab3" width="100%">
                                <tr>
                                    <td class="TableContent" style="text-align: left;width: 25%"><?=$LG_CUSTOMER_DATA['登记单号'][$_COOKIE['LANG']]?>： <?= $main['form_id'] ?></td>
                                    <td class="TableContent" style="text-align: left;width: 25%"><?=$LG_CUSTOMER_DATA['所属部门'][$_COOKIE['LANG']]?>： <?= $main['deptName'] ?></td>
                                    <td class="TableContent" style="text-align: left;width: 25%"><?=$LG_CUSTOMER_DATA['登记人'][$_COOKIE['LANG']]?>： <?= $main['createUserName'] ?></td>
                                    <td class="TableContent" style="text-align: left;"><?=$LG_CUSTOMER_DATA['登记时间'][$_COOKIE['LANG']]?>： <?= $main['write_time'] ?></td>
                                </tr>
                                <?php if (!empty($share)) : ?>
                                    <tr align="left">
                                        <td colspan="4" class="TableContent" style="text-align: left;"><?=$LG_CUSTOMER_DATA['共享业务人员'][$_COOKIE['LANG']]?>： <?= $share ?></td>
                                    </tr>
                                <?php endif; ?>
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
                <?php include_once("../common/list/change_html.php"); ?>
            </div>
        </div>
    </form>
</body>

</html>