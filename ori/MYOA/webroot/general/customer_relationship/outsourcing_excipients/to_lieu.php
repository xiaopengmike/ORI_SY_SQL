<?php
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
include_once("inc/utility_org.php");
$HTML_PAGE_TITLE = _("外包服务辅料申请");
include_once("inc/header.inc.php");
include_once("common/Common.php");
include_once("ActionModel.php");
require_once("../common/AttachModel.php");
require_once("../common/ProcessModel.php");
require_once("../common/DataModel.php");
require_once '../common/FormUserManageModel.php';
require_once '../common/UserModel.php';
require_once("../common/SystemEnum.php");
$process = new ProcessModel();
$userModel = new UserModel();
$attachModel = new AttachModel();
$dataModel = new DataModel();
$formUserManageModel = new FormUserManageModel();

$isHide=$formUserManageModel->isHide('outsourcing_excipients');

$deptId = $_SESSION['LOGIN_DEPT_ID'];
$userId = $_SESSION['LOGIN_USER_ID'];
$userName = $_SESSION['LOGIN_USER_NAME'];
$currentTime = date('Y-m-d H:i:s', time());
$selectParam = array(
    'is_used' => '1', // 可用参数
    'type' => 'check_opinion,yes_or_no,company,warehouse'
);
$selectArr = $dataModel->getCommonParam($selectParam);
$company_names=array();
foreach ($selectArr['company'] as $v) {
    $company_names[$v['paras_value']]=$v['paras_desc'];
}
$companyArr=array();
foreach ($selectArr['warehouse'] as $v) {
    $companyArr[$v['paras_value']]=$v['paras_desc'];
}


$checkArr = array();
foreach ($selectArr['check_opinion'] as $co) {
    $checkArr[$co['paras_value']] = $co;
}
$yes_or_no = array();
foreach ($selectArr['yes_or_no'] as $v) {
    $yes_or_no[$v['paras_value']] = $v['paras_desc'];
}

$action = new ActionModel();
if (empty($id)) {
    Message("提示", "对应数据不存在！");
    exit;
}
$param['id'] = $id;
//获取变更记录
$data = $action->queryById($param);
$main = $data['main'];
$company=$main['user_bu'];
$orderDetail = $data['orderDetail'];
$detailItem = $data['detailItem'];

//审批需要填写其他文本参数
$selectParam = array(
    'is_used' => '1', // 可用参数
    'type' => 'opinion_extra',
    'user_bu' => $main['user_bu'],
    'paras_group' => 'outsourcing_excipients',
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
    'paras_group' => 'outsourcing_excipients',
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
    'paras_group' => 'outsourcing_excipients',
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
// $systemId = "outsourcing_excipients";
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
$companyNew = "";
$companyNew = $userModel->getUserBu($deptId);
if (isset($company)) $companyNew = $company;

// $sql = " select id,company,user_bu from company ";
// $res = exequery(TD::conn(), $sql);
// while ($item = mysql_fetch_assoc($res)) {
// 	$companyArr[] = $item;
// }

foreach($orderDetail[non_excipients] as $k=>$v){
    
    $status="T";
    $sql = " select b.status from inhe_lieureceive_detail a,inhe_lieu_receive b where a.form_id=b.form_id and a.non_lieu_excipients_id='$v[id]'";
   $res = exequery(TD::conn(), $sql);
   while ($item = mysql_fetch_assoc($res)) {
    $status=$item[status];
    if($status=="R"||$status=="Y"||$status=="P"){
        break;
    }
   }
   $orderDetail[non_excipients][$k][status]=$status=="R"||$status=="Y"?"已代收":($status=="P"?"审核中":"未代收");
}
$outexcipients_lieu=array();
$sql = " select k.* from inhe_outexcipients_detail k where k.form_id='$id' and not exists(
select b.status from inhe_lieureceive_detail a,inhe_lieu_receive b where a.form_id=b.form_id and a.non_lieu_excipients_id=k.id and b.status in('P','R','Y'))";
$res = exequery(TD::conn(), $sql);
while ($item = mysql_fetch_assoc($res)) {
    $item[status]="未代收";
    $outexcipients_lieu[]=$item;
}

$outexcipients_lieuing=array();
$sql = " select k.* from inhe_outexcipients_detail k where k.form_id='$id' and exists(
select b.status from inhe_lieureceive_detail a,inhe_lieu_receive b where a.form_id=b.form_id and a.non_lieu_excipients_id=k.id and b.status in('P'))";
$res = exequery(TD::conn(), $sql);
while ($item = mysql_fetch_assoc($res)) {
    $item[status]="审核中";
    $outexcipients_lieuing[]=$item;
    
}

$outexcipients_lieued=array();
$sql = " select k.* from inhe_outexcipients_detail k where k.form_id='$id' and exists(
select b.status from inhe_lieureceive_detail a,inhe_lieu_receive b where a.form_id=b.form_id and a.non_lieu_excipients_id=k.id and b.status in('R','Y'))";
$res = exequery(TD::conn(), $sql);
while ($item = mysql_fetch_assoc($res)) {
    $item[status]="已代收";
    $outexcipients_lieued[]=$item;
}

$lieu_data=array();
$totalQuery = "select k.*,u.user_name createUserName, d.dept_name deptName,o.approve_user,o.step " .
    "  from inhe_lieu_receive k " .
    " left join user u on u.user_id = k.user_id " .
    " left join department d on d.dept_id = k.organ_id " .
    " left join inhe_flow_opinion o on o.form_id=k.form_id and o.status='U' and o.approve_user='$userId' ".
    " where excipients_form_id='$id' ".
    " order by k.id desc ";

$res = exequery(TD::conn(), $totalQuery);
while ($item = mysql_fetch_assoc($res)) {
    $item['company_name']=$companyArr[$item['user_bu']];
    $lieu_data[]=$item;
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
    <script language="Javascript" type="text/javascript" src="../crm.js?v.20220312"></script>
    <link rel="stylesheet" type="text/css" href="<?= COMMON_FILE_URL ?>/common.css">
<link rel="stylesheet" type="text/css" href="<?= COMMON_FILE_URL ?>/layui/css/layui.css">
    <style>
	.drop-btn {
		cursor: pointer;
	}

	.dropdown {
		position: relative;
		display: inline-block;
	}

	.dropdown-content {
		display: none;
		position: absolute;
		z-index: 9999;
		background-color: lightgray;
		min-width: 154px;
		box-shadow: 0px 8px 16px 0px rgba(0, 0, 0, 0.2);
		font-size:14px;
	}

	.dropdown-content a {
		color: black;
		padding: 12px 16px;
		text-decoration: none;
		display: block;
	}

	.dropdown-content a:hover {
		background-color: #027AFF;
	}

	.dropdown:hover .dropdown-content {
		display: block;
	}

	.newColor {
		white-space: nowrap;
	}
</style>
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
            cbxCheckAll("check-all", "cb_list");
        });
        function cbxCheckAll(cbxAllNm, cbxItemNm) {
            var ids = $("#checkedIds").val();
            $("input[name='" + cbxAllNm + "']").change(function () {
                var cbxAllIsChecked = $(this).is(":checked")?true:false; // true or false
                console.log(cbxAllIsChecked);
                $("input[name='" + cbxItemNm + "']").each(function (i, item) {
                    if (cbxAllIsChecked) {
                       // $(item).attr("checked", "checked");
                        $(item).prop('checked','true');
                        ids += item.id + ",";
                    } else {
                        $(item).removeAttr("checked");
                        ids = ids.replace(item.id + ",", "");
                    }
                    console.log(ids);
                });
                $("#checkedIds").val(ids);
            });
        }
        function to_lieu(sort, sortName, processType, userBu, url) {
            if ($("input[name='cb_list']:checked").length === 0) {
                alert("请选择您要代收物料！");
                return false;
            }
            
            var ids = "";
            $("input[name='cb_list']:checked").each(function (i, item) {
                ids += ($("input[name='cb_list']:checked").length - 1) === i ? $(item).attr("id") : $(item).attr("id") + ",";
            });
            var myObject = {};
            myObject.excipients_ids=ids;
            myObject.excipients_formid=$("#form_id").val();
            if (ifEmpty(url)) url = '../lieu_receive/add.php';
            layui.use(['form', 'layer'], function () {
                var layer = layui.layer,
                    form = layui.form;
                if (sort == userBu) {
                    // 若新建归属公司流程不弹出提示直接打开页面
                    //pageOperate(url + '?company=' + sort+ "&id="+ids, 'add');
                    openPostWindow(url + '?company=' + sort, myObject,'转'+sortName+'代收货');
                    return false;
                } else {
                    var content = '<div style="padding: 50px;line-height: 22px;font-weight: 360px;text-align:center"><b>';
                    content += '您确认新建<font style="color:#FF5722">' + sortName + '</font>' + processType + '吗？';
                    content += '</b></div>';
                    layer.open({
                        type: 1, // 0（信息框，默认）1（页面层）2（iframe层）3（加载层）4（tips层）
                        title: '提醒',
                        closeBtn: false,
                        area: '352px;',
                        id: 'process_add_id', //设定一个id，防止重复弹出
                        btn: ['确认', '取消'],
                        btnAlign: 'c',
                        content: content,
                        success: function (layero) {
                            
                            // var btn = layero.find('.layui-layer-btn');
                            // btn.find('.layui-layer-btn0').attr({
                            //     //onclick: "pageOperate('" + url + "?company=" + sort + "&id="+ids+"', 'add');return false;",
                            //     onclick: "openPostWindow('" + url + "?company=" + sort +"', "+myObject+",'转"+sortName+"代收货');return false;",
                            //     target: '_blank'
                            // });
                        },
                        btn1:function(index, layero){
                            openPostWindow(url + '?company=' + sort, myObject,'转'+sortName+'代收货');
                            layer.close(index);
                        }
                    });
                    form.render();
                }
                form.render();
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
                resize:true,
                content: "next_approver.php?step=" + step + "&user_bu=" + user_bu + "&form_id=" + formId + "&actionType=" + actionType + "&type=" + type,
                success: function(layero, index) {
                    setTimeout(function() {
                        layui.layer.tips('点击此处返回列表', '.layui-layer-setwin .layui-layer-close', {
                            tips: 3
                        });
                    }, 500)
                }
            });
        }

        function openPostWindow(url, data, name)
        {
            var tempForm = document.createElement("form");
            tempForm.id = "tempForm1";
            tempForm.method = "post";
            tempForm.action = url;
            tempForm.target = name;    // _blank - URL加载到一个新的窗口
            $.each(data, function(key, val) {
                var hideInput = document.createElement("input");
                hideInput.type = "hidden";
                hideInput.name = key;
                hideInput.value = data[key];
                tempForm.appendChild(hideInput);
            });
            // var hideInput = document.createElement("input");
            // hideInput.type = "hidden";
            // hideInput.name = "excipients_ids";
            // hideInput.value = data;
            // tempForm.appendChild(hideInput);
            // 可以传多个参数
            /* var nextHideInput = document.createElement("input");
            nextHideInput.type = "hidden";
            nextHideInput.name = "content";
            nextHideInput.value = data;
            tempForm.appendChild(nextHideInput); */
            if(document.all){    // 兼容不同浏览器
                tempForm.attachEvent("onsubmit",function(){openWindow(name)});        //IE
            }else{
                tempForm.addEventListener("submit",function(){openWindow(name)},false);    //firefox
            }
            document.body.appendChild(tempForm);
            if(document.all){    // 兼容不同浏览器
                tempForm.fireEvent("onsubmit");
            }else{
                tempForm.dispatchEvent(new Event("submit"));
            }
            tempForm.submit();
            document.body.removeChild(tempForm);
        }   
        // function openPostWindow(url,data,name){  //url要跳转到的页面，data要传递的数据，name显示方式（可能任意命名）
        //         var tempForm = document.createElement("form");  
        //         tempForm.id="tempForm1";  
        //         tempForm.method="post";  
        //         tempForm.action=url;  
        //         tempForm.target=name;  
        //         var hideInput = document.createElement("input");  
        //         hideInput.type="hidden";  
        //         hideInput.name= "dataname"
        //         hideInput.value= data;
        //         tempForm.appendChild(hideInput);   
        //         tempForm.attachEvent("onsubmit",function(){ openWindow(name); });//必须用name不能只用url，否则无法传值到新页面
        //         document.body.appendChild(tempForm);           
        //         tempForm.fireEvent("onsubmit");
        //         tempForm.submit();
        //         document.body.removeChild(tempForm);
        // } 
        function openWindow(name)  {  
            //"width=" + (window.screen.availWidth - 10) + ",height=" + (window.screen.availHeight - 30) + ",top=0,left=0,resizable=yes,status=yes,menubar=no,scrollbars=yes"
            var iWidth=1100; //弹出窗口的宽度;
            var iHeight=550; //弹出窗口的高度;
            var iTop = (window.screen.availHeight-30-iHeight)/2; //获得窗口的垂直位置;
            var iLeft = (window.screen.availWidth-10-iWidth)/2; //获得窗口的水平位置;        
            window.open('about:blank',name,"height="+iHeight+", width="+iWidth+", top="+iTop+", left="+iLeft+",toolbar=no, menubar=no,  scrollbars=yes,resizable=yes,location=no, status=no");  
        }  
    </script>
</head>

<body>
    <div style="width: 100%;height:auto;margin:auto;">
        <form id="tab1" class="layui-form">
            <input id="checkedIds" type="hidden">
            <input id="form_id" type="hidden" value="<?=$id?>">
            <div class="r" style="height: auto;">
                <div style="text-align: left;" class="layui-btn-group">
                    <table width="100%">
                        <tr class="layui-bg-body layui-bg-gray" id="tHeader">
                            <td align="center" style="padding: 5px;">
                                <div class="dropdown">
									<input lay-submit onclick="to_lieu()','modify');return false;" name="btn_save" actionType="Save" value="转代收货" type=button class="layui-btn layui-btn-oa layui-btn-sm drop-btn" />
									<div class="dropdown-content" align="center">
										<?php foreach ($companyArr as $key=>$val) { ?>
                                            
											<a href="javascript:void(0);" class="newColor" onclick="to_lieu('<?= $key ?>','<?= $val ?>','代收货流程','<?= $companyNew ?>')"><?= $val ?></a>
										<?php } ?>
									</div>
								</div>
                                <input name="btn_close" value="关闭" type=button actionType="Close" class="layui-btn layui-btn-oa layui-btn-sm" onclick="$.closeIframe();return false;" />
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
            <br />
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
        </form>
        <?php include_once("to_lieu/order_detail.php"); ?>
    </div>
</body>

</html>