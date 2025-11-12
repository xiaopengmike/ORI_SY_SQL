<?php
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
include_once("inc/utility_org.php");
$HTML_PAGE_TITLE = _("下一步审核");
include_once("inc/header.inc.php");
include_once("common/Common.php");
require_once("../common/ProcessModel.php");
require_once("../common/FormModel.php");
$processModel = new ProcessModel();
$formModel = new FormModel();
$deptId = $_SESSION['LOGIN_DEPT_ID'];
$userId = $_SESSION['LOGIN_USER_ID'];
$time = date('Y-m-d H:i:s');
$param = $processModel->getParamToArray();
// FIX:若选择不同意但点击提交操作时，视为退回操作，获取退回步骤
$ifAgree = "";

$param2=$param;

$flowArr = $processModel->getFlowStep($param2['type'], $param2['user_bu']);
//流程设置中董事长前面一个步骤为必须经过步骤,回退一步判断,为了抛单跳过董事长重复审批模拟董事长步骤,但是审批人员实际是上一步人员
// if(strpos($param2['form_id'],strtolower($param2['user_bu']).'_')===0&&$flowArr[$param2[step]]['approver']=='meterw'){
//     $param2[step]=$param2[step]-1;
//     $currApproverData2 = $processModel->getApprover($param2);
//     $approveRole2 = $currApproverData2[0]['role'];
// }
$param['tableName'] = 'inhe_production_order';
$formData = $formModel->query($param);
$data = $formData[0];

$approveInfo = $processModel->findApproveInfo($param2);
if(count($approveInfo)==0){
    Message("", "流程已提交，请勿重复操作！");
    echo '<div align=center>
    <input type="button" value="关闭" class="BigButton" title="关闭窗口" onclick="window.parent.close();">
    </div>';
    exit;
}
//成都走电力流程特殊处理
$flow_manage_param=$param;
if($data['user_bu']=="INHE"&&$data['create_user_bu']=="CDINHE"){
    $flow_manage_param[user_bu]=$data['user_bu']."_".$data['create_user_bu'];
}
$ifAgree = $approveInfo['if_agree'];
// TODO: 若退回某步骤后，修改提交是否可直接跳过已审核步骤人员，待确定
$approverArr = array();
if ($param['actionType'] == 'Back' || $ifAgree == FormModel::DISAGREE) {
    $actionType = '退回';
    $approverArr = $processModel->getBackStep($param);
} else {
    $actionType = '提交';
    $approverArr = $processModel->nextApprover($flow_manage_param);
}
// 获取当前审核角色
$currApproverData = $processModel->getApprover($flow_manage_param);
$approveRole = $currApproverData[0]['role'];
if($param['EP'] == '01'){
    $external_procure = $processModel->getApprover(array('type'=>$param[type],'user_bu'=>$param[user_bu],'role'=>'外购备案'));
    $otherApprover=$external_procure[0][approver];
}
$currApproverData2=empty($currApproverData2)?$currApproverData:$currApproverData2;
?>

<head>
    <meta charset="UTF-8">
    <meta name="renderer" content="webkit">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=0">
    <script language="Javascript" type="text/javascript" src="<?= COMMON_FILE_URL ?>/jquery-1.11.3.min.js"></script>
    <script language="Javascript" type="text/javascript" src="<?= COMMON_FILE_URL ?>/common.js"></script>
    <script language="Javascript" type="text/javascript" src="main.js"></script>
    <script language="Javascript" type="text/javascript" src="<?= COMMON_FILE_URL ?>/layui/layui.js" charset="utf-8"></script>
    <link rel="stylesheet" type="text/css" href="<?= COMMON_FILE_URL ?>/layui/css/layui.css">
    <link rel="stylesheet" href="<?= COMMON_FILE_URL ?>/css/font.css">
    <link rel="stylesheet" href="<?= COMMON_FILE_URL ?>/css/weadmin.css">
</head>

<?php
if (empty($approverArr) || count($approverArr) == 0) {
    // 无下一步即最后一步时
    $flowParam = array(
        'form_id' => $param['form_id'],
        'type' => $param['type'],
        'prev_writer' => $userId,
        'prev_time' => $time
    );
    $processModel->updateFlowData($flowParam);

    $sql = "update inhe_production_order set status = 'Y' where form_id = '" . $param['form_id'] . "' ";
    exequery(TD::conn(), $sql);

    header("Location: ../common/list/finish_page.php");
    exit;
    // echo '<br />';
    // Message("提示", "流程结束");
}

//其它公司产品备案人员
$other_company_record="";
$sql = " select k.* from inhe_oa_paras k where k.type='auto_flow_user' and is_used='1' 
and exists(select * from inhe_production_detail_non where form_id='$data[form_id]' and find_in_set(k.user_bu,other_company)) ";

$res = exequery(TD::conn(), $sql);
while ($item = mysql_fetch_assoc($res)) {
    $other_company_record .= $item[paras_value].",";
}

$sql = " select k.* from inhe_oa_paras k where k.type='auto_flow_user' and is_used='1' 
and exists(select * from inhe_production_detail_three where form_id='$data[form_id]' and find_in_set(k.user_bu,other_company)) ";

$res = exequery(TD::conn(), $sql);
while ($item = mysql_fetch_assoc($res)) {
    $other_company_record .= $item[paras_value].",";
}
//TODO 慧软CRM合同评审
/*
$sql = " select * from inhe_contract_review_pn k where k.form_id='$data[main][order_form_id]'";
$res = exequery(TD::conn(), $sql);
$licenseProduct=array();
while($item = mysql_fetch_assoc($res)) {
    $licenseProduct[] = $item;
}
*/
?>

<body>
    <form id="mainForm" class="layui-form">
        <input type="hidden" name="actionType" id="actionType" value="<?= $param['actionType'] ?>" />
        <input type="hidden" name="systemId" id="systemId" value="<?= $param['type'] ?>" />
        <input type="hidden" name="form_type" id="form_type" value="update_approver" />
        <input type="hidden" name="form_id" id="form_id" value="<?= $param['form_id'] ?>" />
        <input type="hidden" name="prev_step" id="prev_step" value="<?= $param['step'] ?>" />
        <input type="hidden" name="user_bu" id="user_bu" value="<?= $data['user_bu'] ?>" />
        <input type="hidden" name="title" id="title" value="<?= $data['title'] ?>" />
        <input type="hidden" name="urgent" id="urgent" value="<?= $data['urgent']=="是"?'Y':'' ?>" />
        <input type="hidden" name="licenseProduct" id="licenseProduct" value="<?= count($licenseProduct) ?>" />
        <input type="hidden" name="create_user_bu" id="create_user_bu" value="<?= $data['create_user_bu'] ?>" />
        <fieldset class="layui-elem-field layui-field-title" style="margin-top: 20px;">
            <legend><?= $actionType ?>任务</legend>
        </fieldset>
        <div class="layui-form-item">
            <label class="layui-form-label">下一步骤</label>
            <?php foreach ($approverArr as $app) {

                  //多步骤选择才判断
                  if(isset($approverArr[1])&&$actionType == '提交'){
                    //按顺序，只要出现某类产品大于0，则后面步骤不在显示，前面步骤如果在设置的四类技术评审内则不显示，前面存在其他步骤会显示
                    //TODO 如果需要仅仅只显示某一个步骤，则需循环判断取出符合条件的步骤，然后通过不等于则跳过其他所有步骤，类似上面判断的商务总监步骤
                    $role_names = array_column($approverArr, 'role');
                    $jx_manage=0;
                    if(in_array($data['user_bu'],array('INHE','INHEIAC','INHEIMC','INHEIEU'))&&($param['E']>0||$param['O']>0)){
                        $jx_manage=1;
                    }
                    $param['江西总经理']=in_array('江西总经理',$role_names)?$jx_manage:0;
                    $param['电表技术评审']=in_array('电表技术评审',$role_names)?$param['E']:0;
                    $param['配电产品技术评审']=in_array('配电产品技术评审',$role_names)?$param['G']:0;
                    $param['系统软件技术评审']=in_array('系统软件技术评审',$role_names)?$param['S']:0;
                    $param['光伏产品技术评审']=in_array('光伏产品技术评审',$role_names)?$param['N']:0;
                    $param['成都产品技术评审']=in_array('成都产品技术评审',$role_names)?$param['D']:0;
                    $param['集抄项目评审']=in_array('集抄项目评审',$role_names)?$param[is_collective]:0;

                    if (isset($param[$app['role']])&&$param[$app['role']]>0) {
                        echo '<div class="layui-input-block">
                                <input type="radio" name="step" value="'.$app['step'].'" title="'.$app['role'].'" data-type="'.$app['operate'].'" data-approver="'.$app['approver'].'" lay-filter="step" />
                            </div>';
                        break;
                    }

                    if (isset($param[$app['role']])&&$param[$app['role']]<= 0) {
                        continue;
                    }

                    // if($data['user_bu']=="WITLINK"&&$approveRole == '董事长'){
                    //     if ($param['EP'] == '01' && $app['role'] == '备案') {
                    //         continue;
                    //     }
                    //     if($param['EP'] != '01' && $app['role'] != '备案') {
                    //         continue;
                    //     }
                    // }
                   
                }
                // if(strpos($data['type'],'rd_')===false&&$data['user_bu']=="INHEGRID"&&$approveRole == '总经理'&&$actionType == '提交'){
                //         if ($data['sub_user_bu']=="INHE" && $app['role'] == '备案') {
                //             continue;
                //         }
                //         if($data['sub_user_bu']!="INHE" && $app['role'] != '备案') {
                //             continue;
                //         }
                // }
                

                // if (strpos($data['type'],'rd_')===false&&in_array($data['user_bu'],array("WITLINK"))&&$approveRole == '总经理'&&$actionType == '提交') {
                //         if ($app['role'] == '备案'){
                //             continue;
                //         }
                // }

                // if(strpos($data['type'],'rd_')===false&&strpos($param2['form_id'],strtolower($param2['user_bu']).'_')===0&&$currApproverData2[0]['auto_approver']=="1"&&$currApproverData2[0]['operate']!='record'){
                //     $role_names = array_column($approverArr, 'role');
                //     if($app['role'] != '备案'&&in_array('备案',$role_names)) {
                //         continue;
                //      }
                //     if($app['operate']=='record'){
                //             //备案流程
                //         $approve_user='';
                //         $opinionSql=" select * from  user u where 
                //         exists(select * from inhe_flow_manage where type='$data[type]' and user_bu='$data[user_bu]'  
                //             and notice_user is not null
                //             and ((find_in_set(u.user_id,substring_index(approver,',',1)) and if_show='1' and notice_user='1') or  (find_in_set(u.user_id,approver) and operate='record' and next_step=''))) 
                //         and u.user_id!='meterw' and not exists(select * from inhe_flow_opinion where form_id='$data[form_id]' and approve_user=u.user_id) ";
                //         $res4=exequery(TD::conn(), $opinionSql); 
                //         while ($item = mysql_fetch_assoc($res4)) {
                //             $approve_user.=$item[USER_ID].",";
                //         }
                //         $app['approver']=$approve_user;
                //      }
                // }

                //备案追加其它公司产品评审
                if(strpos($app['role'],"备案")!==false){
                    $app['approver']=$other_company_record.",".$app['approver'];
                }

                if($app['role'] == '备案'&&$data['user_bu']=="WITLINK"&&$param['EP'] == '01'){
                    $app['approver']=$otherApprover?$otherApprover.",".$app['approver']:$app['approver'];
                }

            ?>
                <div class="layui-input-block">
                    <input type="radio" name="step" value="<?= $app['step'] ?>" title="<?= $app['role'] ?>" data-type="<?= $app['operate'] ?>" data-approver="<?= $app['approver'] ?>" lay-filter="step" />
                </div>
            <?php } ?>
        </div>
        <div class="layui-form-item layui-form-text">
            <label class="layui-form-label">待办人员</label>
            <div class="layui-input-block" id="user_select" style="width: 150px;display: none">
                <select id="user_name" name="user_name" lay-filter="user_name">
                    <option value=""></option>
                </select>
            </div>
            <br />
            <div class="layui-input-block">
                <input type="hidden" data-type="user_id" name="user_id" id="user_id" />
                <textarea placeholder="显示指定待办人员" class="layui-textarea" id="approver_list" name="approver_list" readonly></textarea>
            </div>
        </div>
        <div class="layui-form-item">
            <div class="layui-input-block">
                <input lay-submit lay-filter="submit" name="btn_submit" actionType="Submit" value="提交" type=button class="layui-btn layui-btn-oa layui-btn-sm" />
            </div>
        </div>
    </form>
</body>

<script type="text/javascript">
    $(function() {
        layui.use(['form', 'layer'], function() {
            var layer = layui.layer,
                form = layui.form;

            $.each($("textarea"), function(i, n) {
                $(n).css("height", n.scrollHeight + "px");
                $(n).css("width", "80%");
                $(n).css("float", "left");
                $(n).css("resize", "none");
                $(n).css("overflow", "hidden");
            });

            form.on('submit(submit)', function(data) {
                var btnVal = data.elem.value;
                var actionType = $(data.elem).attr("actionType");
                var url = "action.php?action=" + actionType;
                var userList = $("#user_id").val();
                if (ifEmpty(userList)) {
                    layer.msg('未指定下一步待办人员！');
                    return false;
                }
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
                            parent.layer.close(index);
                            parent.closeIframe();
                            parent.location.reload();
                            // parent.opener.location.reload();
                        });
                    } else {
                        layer.alert(btnVal + '失败！', {
                            icon: 5
                        });
                    }
                });
            });

            form.on('select(user_name)', function(data) {
                $("#user_id").val(data.value);
                $("#approver_list").val(data.elem[data.elem.selectedIndex].text);

                form.render();
            });

            form.on('radio(step)', function(data) {
                var approver = $(this).data('approver');
                var type = $(this).data('type');
                var step = data.value;
                $("#user_select").hide();
                $("#user_id").val('');
                $("#approver_list").val('');
                // 分3种情况：1、指定1人； 2、指定多人 3、选择一人
                if (approver == 'dept_select') {
                    getUserSelect('approver_list');
                } else {
                    var url = "action.php?action=Retrieve";
                    $.post(url, {
                        dataType: 'getApproverList',
                        approver: approver,
                        type: type
                    }, function(data) {
                        var obj = eval('(' + data + ')');
                        if (type == 'record') {
                            var userNameList = userIdList = "";
                            for (var se in obj.data) {
                                userNameList += obj.data[se].user_name + ','; // 单选
                                userIdList += obj.data[se].user_id + ',';
                            }
                            returnUser(userNameList, userIdList, 'approver_list');
                        } else {
                            $("#user_select").show();
                            $("#user_name").empty();
                            var selected = "";
                            if (obj.data.length == 1) {
                                // 仅有一个选项时默认带出值
                                $("#user_name").append("<option value='" + obj.data[0].user_id + "' selected>" + obj.data[0].user_name + "</option>");
                                $("#user_id").val(obj.data[0].user_id);
                                $("#approver_list").val(obj.data[0].user_name);
                                form.render();
                            } else {
                                $("#user_name").append("<option value=''>请选择</option>");
                                for (i = 0; i < obj.data.length; i++) {
                                    $("#user_name").append("<option value='" + obj.data[i].user_id + "' " + selected + ">" + obj.data[i].user_name + "</option>");
                                }
                            }
                        }

                        form.render();
                    });
                }
            });

            form.render();
        });
    });

    function getUserSelect(domId) {
        layui.use(['form', 'layer'], function() {
            var layer = layui.layer,
                form = layui.form;

            layer.open({
                title: '选择人员',
                type: 2,
                skin: 'layui-layer-rim',
                area: ['75%', '80%'],
                fixed: false,
                maxmin: true,
                offset: '50px',
                content: '../common/list/single_type_list.php?domId=' + domId,
                moveOut: true
            });
            form.render();
        });
    }

    function returnUser(userName, userId, domId) {
        $("#" + domId).val(userName);
        $("#" + domId).parent().find("[data-type='user_id']").val(userId); //取userId隐藏栏位赋值
    }
</script>

</html>