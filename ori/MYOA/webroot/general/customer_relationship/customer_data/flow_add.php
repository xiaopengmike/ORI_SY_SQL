<?php
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
include_once("inc/utility_org.php");
include_once( "inc/utility_file.php" );
include_once("lang.php");
$HTML_PAGE_TITLE = _($LG_CUSTOMER_DATA['客户跟踪'][$_COOKIE['LANG']]);
include_once("inc/header.inc.php");
include_once("common/Common.php");
include_once("../common/DataModel.php");
include_once("../common/UserModel.php");
include_once("ActionModel.php");
$userId = $_SESSION['LOGIN_USER_ID'];
$deptId = $_SESSION['LOGIN_DEPT_ID'];
$action = new ActionModel();
$dataModel = new DataModel();
$userModel = new UserModel();
if (empty($id)) {
    Message("提示", "对应数据不存在！");
    exit;
}
$param['id'] = $id;
$data = $action->queryById($param);
$main = $data['main'];
$mainid = $main[id];
//存在变更，则取上次变更信息
if($main['version']>0 && $main['type']=="customer_data"){
    $item=$dataModel->getLastChange($main['form_id'],$main['version'],'customer_data_change');
    if($item['form_id']){
        $data = $action->queryById(array("id"=>$item['form_id'],"customer_id"=>$mainid));
        $main = $data['main'];
        $main['type']="customer_data";
        $main['form_id']=$main['related_id'];
    }
}
$contacts = $data['contacts'];
//var_dump($data);

$userName = $_SESSION['LOGIN_USER_NAME'];
$date = date('Y-m-d');
$companyNew = $userModel->getUserBu($deptId);
$selectParam = array(
    'is_used' => '1', // 可用参数
    'type' => 'customer_flow_type'
);
$selectArr = $dataModel->getCommonParam($selectParam);
$customer_flow_type = array();
foreach ($selectArr['customer_flow_type'] as $v) {
    $customer_flow_type[$v['paras_value']] = $v;
}

?>

<head>
    <meta charset="UTF-8">
    <meta name="renderer" content="webkit">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=0">
    <script type="text/javascript">
	var upload_limit = <?=$UPLOAD_LIMIT?>,
		limit_type = "<?=strtolower($UPLOAD_LIMIT_TYPE)?>";
    </script>
    <script src="/inc/js/utility.js"></script>
    <script src="../attachMenu.js"></script>
    <script src="/inc/js/fujian.js"></script>
    <script type="text/javascript" src="/inc/js_lang.php"></script>
    
    <script language="Javascript" type="text/javascript" src="<?= COMMON_FILE_URL ?>/jquery-1.11.3.min.js"></script>
    <script language="Javascript" type="text/javascript" src="<?= COMMON_FILE_URL ?>/common.js"></script>
    <script language="Javascript" type="text/javascript" src="<?= COMMON_FILE_URL ?>/layui/layui.js" charset="utf-8"></script>
    <script src="/inc/js/module.js"></script>
    <link rel="stylesheet" href="<?= COMMON_FILE_URL ?>/css/font.css">
    <link rel="stylesheet" href="<?= COMMON_FILE_URL ?>/css/weadmin.css">
    <link rel="stylesheet" type="text/css" href="<?= COMMON_FILE_URL ?>/common.css">
    <link rel="stylesheet" type="text/css" href="../crm.css">
    <script type="text/javascript">
    $(function() {
        layui.use(['form', 'layer'], function() {
            var layer = layui.layer,
                form = layui.form;
                form.verify({
                    checknum:function(value,item){
                        var numreg=/(^$)|^[1-9]\d*$/;
                        if(!numreg.test(value)){
                            return "只能填写正整数";
                        }
                    }
                })
            form.on('select(contract_id)', function (data) {
                    var elem = data.elem;
                    var contract_name = $(data.elem).find("option:selected").attr("data-name");
                    $("input[name='contract_name']").val(contract_name);
                    form.render('select','project_level');
                });
            form.render();
        });
    });
    function CheckForm(){
        if($("#order_will").val()=="Y"){
            var numreg=/(^$)|^[1-9]\d*$/;
            //console.log($("#order_num").val());
            if(ifEmpty($("#order_num").val())||!numreg.test($("#order_num").val())){
                var msg='<?=$LG_CUSTOMER_DATA['有采购意向，总数量必须大于0'][$_COOKIE['LANG']]?>';
                alert(msg);
                return false;
            }
        }
        return true;
    }
</script>
<style>
.xing {
height: 20px;
display: inline-block;
vertical-align: middle;
line-height: 26px;
font-size: 20px;
color:red
}
</style>
</head>

<body>
    <div style="width: 100%;height:auto;margin:auto;">
        <form id="mainForm" class="layui-form" lay-filter="mainForm" method="post" action="action.php?action=Retrieve" accept-charset='gbk' enctype="multipart/form-data" onsubmit="return CheckForm();">
            <div class="r" style="height: auto;">
                <div style="text-align: left;" class="layui-btn-group">
                    <table id="tab" width="100%">
                        <tr class="layui-bg-body">
                            <td style="padding: 5px;">
                                <input lay-submit  name="btn_submit" actionType="Save" value="<?=$LG_CUSTOMER_DATA['提交'][$_COOKIE['LANG']]?>" type=submit  class="layui-btn layui-btn-oa layui-btn-sm" />
                                <input name="btn_close" value="<?=$LG_CUSTOMER_DATA['关闭'][$_COOKIE['LANG']]?>" type=button class="layui-btn layui-btn-oa layui-btn-sm" onclick="window.close();return false;" />
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
            <br />
            <div class="weadmin-body" style="width:80%;margin:1% 9%;">
                <input type="hidden" name="dataType" id="dataType" value="addCustomerFlow" />
                <input type="hidden" name="customer_id" id="customer_id" value="<?= $main[id] ?>" />
                <input type="hidden" name="form_id" id="form_id" value="<?= $main[form_id] ?>" />
                <input type="hidden" name="user_bu" id="user_bu" value="<?=$companyNew?>" />
                <table class="TableBlock" width="100%" align="center">
                    <tr>
                        <td class="TableContent"><?=$LG_CUSTOMER_DATA['客户名称'][$_COOKIE['LANG']]?></td>
                        <td class="TableData" colspan="3">
                            <input type="text"  readonly class="layui-input" value="<?= $main[customer_name] ?>" />
                        </td>
                    </tr>
                    <tr>
                        <td class="TableContent"><span class="xing">*</span><?=$LG_CUSTOMER_DATA['联系人'][$_COOKIE['LANG']]?></td>
                        <td class="TableData" colspan="3">
                        <select name="contract_id" id="contract_id" class="normalSelect" lay-filter="contract_id" lay-verify="required">
                            <!-- <option value=""><?=$LG_CUSTOMER_DATA['请选择'][$_COOKIE['LANG']]?></option> -->
                            <?php foreach($contacts as $contact):?>
                            <option value='<?=$contact['id']?>' data-name="<?=$contact['name']?>"><?=$contact['name']?></option>
                            <?php endforeach;?>
                        </select>
                        <input type="hidden"  readonly name="contract_name" class="layui-input" value="<?= $main[contract_name] ?>" />
                            </td>
                    </tr>
                    <tr>
                        <td class="TableContent"><span class="xing">*</span><?=$LG_CUSTOMER_DATA['跟进日期'][$_COOKIE['LANG']]?></td>
                        <td class="TableData" colspan="3">
                            <input type="text" name="flow_date" id="form_date" class="layui-input date" value="<?= date('Y-m-d') ?>" lay-verify="required" />
                        </td>
                    </tr>
                    <tr>
                    <td class="TableContent"><span class="xing">*</span><?=$LG_CUSTOMER_DATA['接洽方式'][$_COOKIE['LANG']]?></td>
                        <td class="TableData" colspan="3">
                            <select name="flow_type" id="flow_type" class="normalSelect" lay-verify="required">
                                <?php foreach($selectArr['customer_flow_type'] as $customer_type):?>
                                <option value='<?=$customer_type['paras_value']?>' <?php if ($main['flow_type'] == $customer_type['paras_value']) : ?>selected<?php endif; ?>><?=$LG_CUSTOMER_DATA[$customer_type['paras_desc']][$_COOKIE['LANG']]?></option>
                                <?php endforeach;?>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td class="TableContent"><span class="xing">*</span><?=$LG_CUSTOMER_DATA['需求'][$_COOKIE['LANG']]?></td>
                        <td class="TableData" colspan="3">
                            <textarea class="layui-textarea" id="need" name="need" placeholder="<?=$LG_CUSTOMER_DATA['需求'][$_COOKIE['LANG']]?>" autocomplete="off" lay-verify="required"></textarea>
                        </td>
                    </tr>
                    <tr>
                        <td class="TableContent"><?=$LG_CUSTOMER_DATA['目前供应商'][$_COOKIE['LANG']]?></td>
                        <td class="TableData" colspan="3">
                            <input type="text" name="purma"  class="layui-input" value="" />
                        </td>
                    </tr>
                    <tr>
                        <td class="TableContent"><span class="xing">*</span><?=$LG_CUSTOMER_DATA['是否有意向采购产品'][$_COOKIE['LANG']]?></td>
                        <td class="TableData">
                        <select name="order_will" id="order_will" class="normalSelect" lay-verify="required">
                                    <option value=""><?=$LG_CUSTOMER_DATA['请选择'][$_COOKIE['LANG']]?></option>
                                    <option value='Y' <?php if ($main['order_will'] == 'Y') : ?>selected<?php endif; ?>><?=$LG_CUSTOMER_DATA['是'][$_COOKIE['LANG']]?></option>
                                     <option value='N' <?php if ($main['order_will'] == 'N') : ?>selected<?php endif; ?>><?=$LG_CUSTOMER_DATA['否'][$_COOKIE['LANG']]?></option>
                                    </select>
                        </td>
                        <td class="TableContent"><?=$LG_CUSTOMER_DATA['总数量'][$_COOKIE['LANG']]?></td>
                        <td class="TableData">
                            <input type="text" name="order_num" id="order_num"  class="layui-input" value="" layui-verify="checknum" />
                        </td>
                    </tr>
                    <tr>
                    <tr>
                        <td class="TableContent" ><?=$LG_CUSTOMER_DATA['附件'][$_COOKIE['LANG']]?></td>
                        <td class="TableData"  style="text-align: left;" id="file" colspan="3">
                            <?php
                                if($main['ATTACH_ID']&&$operate=='modify'){
                                    $array=explode('</div>',attach_link($main['ATTACH_ID'],$main['ATTACH_NAME'],1,1,1,0,1,0));
                                    for($i=0;$i<count($array);$i++){
                                        echo $array[$i]."</div><br>";
                                    }
                                }     
                            ?>
                            <script>
                                ShowAddFile123( "", "0" );
                            </script>
                        </td>
                    </tr>
                    </tr>
                    <tr>
                        <td class="TableContent"><span class="xing">*</span><?=$LG_CUSTOMER_DATA['商谈事项'][$_COOKIE['LANG']]?></td>
                        <td class="TableData" colspan="3">
                            <textarea class="layui-textarea" id="remark" name="remark" placeholder="<?=$LG_CUSTOMER_DATA['商谈事项'][$_COOKIE['LANG']]?>" autocomplete="off" lay-verify="required"></textarea>
                        </td>
                    </tr>
                </table>
            </div>
        </form>
    </div>
</body>

<script type="text/javascript">
    $(function() {
        layui.use(['form', 'layer', 'table','laydate'], function() {
            var layer = layui.layer,
                form = layui.form,
                table = layui.table,
                laydate = layui.laydate;
                laydate.render({
                    elem: ".date",
                    theme: '#027AFF',
                    range: false
                });
            form.on('submit(submit)', function(data) {
                var btnVal = data.elem.value;
                var actionType = $(data.elem).attr("actionType");
                var url = "action.php?action=Retrieve";
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
                            window.opener.location.reload();
                            parent.layer.close(index);
                            parent.closeIframe();
                        });
                    } else {
                        layer.alert(btnVal + '失败！', {
                            icon: 5
                        });
                    }
                });
            });

            form.on('select(scope_code)', function(data) {
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
                            // var index = parent.layer.getFrameIndex(window.name); //先得到当前iframe层的索引
                            parent.layer.close(index); //再执行关闭
                        });
                    } else {
                        layer.alert(btnVal + '失败！', {
                            icon: 5
                        });
                    }
                });
            });

            $("#temp_code").blur(function() {
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
                                $("#temp_code").val('');
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

</html>