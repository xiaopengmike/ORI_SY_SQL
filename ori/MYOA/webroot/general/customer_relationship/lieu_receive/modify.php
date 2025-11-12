<?php
require_once("inc/auth.inc.php");
require_once("inc/utility_all.php");
require_once("inc/utility_org.php");
$HTML_PAGE_TITLE = _("代收货登记");
require_once("inc/header.inc.php");
require_once("common/Common.php");
require_once("ActionModel.php");
require_once("../common/AttachModel.php");
require_once("../common/UserModel.php");
require_once("../common/DataModel.php");
if (empty($id)) {
    Message("提示", "表单数据不存在！");
    exit;
}
$attachModel = new AttachModel();
$userModel = new UserModel();
$dataModel = new DataModel();
$deptId = $_SESSION['LOGIN_DEPT_ID'];
$userId = $_SESSION['LOGIN_USER_ID'];
$userName = $_SESSION['LOGIN_USER_NAME'];
$currentTime = date('Y-m-d H:i:s', time());
$userInfo = $userModel->getUserInfo($userId);
$selectParam = array(
    'is_used' => '1', // 可用参数
    'type' => 'project_star,check_opinion,yes_or_no,inspection_type,display_mode,production_mode,test_report,packed_in_sequence,product_brand,form_type,product_detail_type,company,SGC_TYPE'
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
$project_star = array();
foreach ($selectArr['project_star'] as $v) {
    $project_star[$v['paras_value']] = $v;
}
$action = new ActionModel();
$param['id'] = $id;
$data = $action->queryById($param);
$main = $data['main'];
$company=$main['user_bu'];
$orderDetail = $data['orderDetail'];

$detailItem = $data['detailItem'];
$attachObj = $attachModel->getAttachById($id);
if ($main['delivery_time'] == '0000-00-00') $main['delivery_time'] = '';
$projectObj = json_encode($dataModel->array_iconv($project_star));

$isChange = '';
$submitAction = 'Submit';
$saveAction = 'Save';
if (strpos($main['type'], '_change') != false) {
    $isChange = '_change';
    $submitAction = 'Change';
    $saveAction = 'ChangeSave';
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
    <script language="Javascript" type="text/javascript" src="main.js"></script>
    <script language="Javascript" type="text/javascript" src="../add.js"></script>
    <link rel="stylesheet" href="<?= COMMON_FILE_URL ?>/css/font.css">
    <link rel="stylesheet" href="<?= COMMON_FILE_URL ?>/css/weadmin.css">
    <link rel="stylesheet" type="text/css" href="<?= COMMON_FILE_URL ?>/common.css">
    <script type="text/javascript">
        $(function() {
            layui.extend({
                attach: '<?= COMMON_FILE_URL ?>/js/extends/attach'
            }).use(['form', 'layer', 'laydate', 'attach'], function() {
                var layer = layui.layer,
                    form = layui.form;
                $.initDate();
                $.initTextarea();
                lineInit();
                var attachObj = <?= $attachObj ?>;
                $.initAttachList(attachObj);
                $.formSubmit();

                form.on('select(form_type)', function(data) {
                    var formType = $("select[name=form_type").val();
                    var url = "../../../../../images/<?= $main['user_bu'] ?>/trial_lieu_receive_<?= $main['user_bu'] ?>.jpg";
                    if (formType == '02') {
                        url = "../../../../../images/<?= $main['user_bu'] ?>/stock_lieu_receive_<?= $main['user_bu'] ?>.jpg";
                    }
                    if (data.value&&data.value!='01'&&data.value!='02') {
                        url = "../../../../../images/<?= $main['user_bu'] ?>/Replenishment_production_notice.jpg";
                    }
                    $("#page_title").attr('src', url);
                    form.render();
                });

                form.verify({
                    SGC: function (value) {
                        var is_sgc = $('#is_sgc').val();
                        if(is_sgc=="03"&&ifEmpty(value)){
                        return "选择有SGC码，则需填写具体SGC码";
                        }
                    },
                });

                form.render();
            });
        });
        function addNonLieu(){
            var form_id=$("#excipients_form_id").val();
            var index = layui.layer.open({
                        title: "外包服务辅料列表",
                        type: 2,
                        content: "product_list.php?form_id="+form_id,
                        area: ['800px', '650px'],
                        success: function(layero, index) {
                            setTimeout(function() {
                                layui.layer.tips('点击此处返回列表', '.layui-layer-setwin .layui-layer-close', {
                                    tips: 3
                                });
                            }, 500)
                        }
                    });
        }

        function returnProductInfo(reData) {
            layui.use(['form', 'layer'], function() {
                var layer = layui.layer,
                    form = layui.form;
                    $=layui.$;
                var addNonLieuCount=$("#addNonLieuCount").val();
                var lineHtml='';
                var length = reData.length;
                // TODO: 实现下拉多选回显列表
                for (i=0; i < length; i++) {
                    var checkid=reData[i].id;
                    // console.log(checkid);
                    // console.log(reData[i]);
                    if(checkExistId(checkid)){
                        continue;
                    }
                    //console.log(reData[i]);
                    
                    addNonLieuCount=parseInt(addNonLieuCount)+1;
                    var obj={};
                    lineHtml+='<tr align="center">';
                    obj.non_lieu_serial_number=reData[i].non_excipients_serial_number;
                    obj.non_lieu_excipients_id=reData[i].id;
                    obj.non_lieu_name=reData[i].non_excipients_name;
                    obj.non_lieu_specification=reData[i].non_excipients_specification;
                    obj.non_lieu_model=reData[i].non_excipients_model;
                    obj.non_lieu_number=reData[i].non_excipients_number;
                    $.each(obj, function(key, val) {
                        if(key=="non_lieu_serial_number"){
                            val=addNonLieuCount;
                        }
                        console.log(addNonLieuCount);
                        lineHtml+='<td class="TableData">';
                        lineHtml+=val;
                        lineHtml+='<input type="hidden" name="'+key+'_NonLieu'+addNonLieuCount+'" value="'+val+'">';
                        if(key=="non_lieu_number"){
                            lineHtml+='<a href="javascript:;" style="float:right" onclick="removeLine(this)">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;删除</a>';
                        }
                        
                        lineHtml+="</td>";
                    });
                    lineHtml+='</tr>';
                    
                }
                $("#non_lieu_NonLieu").append(lineHtml);
                $("#addNonLieuCount").val(addNonLieuCount);
                layer.closeAll();
                form.render();
            });
        }
        // function initSerialNumber(){
        //     var length = $("#non_lieu_NonLieu").find("tr").length;
        //     var trs =$("#non_lieu_NonLieu").find("tr");
        //     var i = 0;
        //     var number = 0;
        //     for (i; i < length; i++) {
        //         var SerialNumber=trs.eq(i).find("td").eq(0).text();
        //         console.log(SerialNumber);
        //     }
        //     $("#addNonLieuCount").val(length);
        // }

        function checkExistId(id){
            var length2 = $("#non_lieu_NonLieu").find("tr").length;
            var trs =$("#non_lieu_NonLieu").find("tr");
            var flag=false;
            for (var i=0; i < length2; i++) {
                var SerialNumber=trs.eq(i).find("td").eq(1).text();
                if(id==parseInt(SerialNumber)){
                    flag= true;
                    break;
                }
            }
            
            return flag;
        }

        // function removeLine(obj) {
        //     var table = obj.parentNode.parentNode.parentNode;
        //     table.removeChild(obj.parentNode.parentNode);
        //     initSerialNumber();
        // }
        
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
                                <input lay-submit lay-filter="submit" name="btn_submit" actionType="<?= $submitAction ?>" value="提交" type=button class="layui-btn layui-btn-oa layui-btn-sm" />
                                <input lay-submit lay-filter="submit" name="btn_save" actionType="<?= $saveAction ?>" value="保存" type=button class="layui-btn layui-btn-oa layui-btn-sm" />
                                <input name="btn_close" value="关闭" type=button class="layui-btn layui-btn-oa layui-btn-sm" onclick="$.closeIframe();return false;" />
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
            <br />
            <div class="weadmin-body" style="width:90%;margin:1% 4%;">
                <input type="hidden" name="form_id" id="form_id" value="<?= $id ?>" />
                <input type="hidden" name="fieldName" value="form_id" />
                <input type="hidden" name="tableName" value="inhe_lieu_receive" />
                <input type="hidden" name="flowType" value="flbg" />
                <input type="hidden" name="reset" value="day" />
                <input type="hidden" name="digit" value="2" />
                <input type="hidden" id="type" name="type" value="<?= $main['type'] ?>" />
                <input type="hidden" id="related_id" name="related_id" value="<?= $main['related_id'] ?>" />
                <input type="hidden" name="step" id="step" value="1" />
                <input type="hidden" name="user_bu" id="user_bu" value="<?= $main['user_bu'] ?>" />
                <input type="hidden" name="title" id="title" value="<?= $main['title'] ?>" />
                <input type="hidden" name="excipients_form_id" id="excipients_form_id" value="<?= $main[excipients_form_id] ?>" />
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
                    <?if(strpos($main['type'],'trial_')===false):?>
                    <tr>
                        <td>
                        <?php include_once("instructions.php"); ?>
                        </td>
                    </tr>
                    <?endif;?>
                </table>
                <br />
                <table class="TableBlock" width="100%" align="center" border="2">
                    <tr>
                        <td>
                            <?php if ($main['type'] == 'trial_lieu_receive') { ?>
                                <?php require_once("trial_change/customer_info.php"); ?>
                                <?php require_once("trial_change/order_detail.php"); ?>
                            <?php } else { ?>
                                <?php require_once("change/customer_info.php"); ?>
                                <?php require_once("change/order_detail.php"); ?>
                            <?php } ?>
                        </td>
                    </tr>
                </table>
            </div>
        </form>
    </div>
</body>

</html>