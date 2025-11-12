<?php
include_once("../../inc/auth.php");
include_once("../../inc/utility_all.php");
include_once("../../inc/utility_org.php");
include_once("../../inc/utility_file.php");
include_once("lang.php");
$HTML_PAGE_TITLE = _($LG_CUSTOMER_DATA['����������¼'][$_COOKIE['LANG']]);
include_once("../../inc/header.inc.php");
include_once("../../common/Common.php");
include_once("../../common/DataModel.php");
include_once("../../common/UserModel.php");
include_once("ActionModel.php");
$userId = $_SESSION['LOGIN_USER_ID'];
$deptId = $_SESSION['LOGIN_DEPT_ID'];
$action = new ActionModel();
$dataModel = new DataModel();
$userModel = new UserModel();
if (empty($id)) {
    Message("��ʾ", "��Ӧ���ݲ����ڣ�");
    exit;
}
$query = "select d.customer_name,k.* from inhe_customer_data_flow k " .
    " left join inhe_customer_data d on k.form_id = d.form_id where k.id='$_GET[id]'";
$res = exequery(TD::conn(), $query);
$flow = mysql_fetch_assoc($res);

$data = $action->queryById(array("id"=>$flow[form_id]));
$main = $data['main'];
$mainid = $main[id];
//���ڱ������ȡ�ϴα����Ϣ
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

$userName = $_SESSION['LOGIN_USER_NAME'];
$date = date('Y-m-d');
$companyNew = $userModel->getUserBu($deptId);
$selectParam = array(
    'is_used' => '1', // ���ò���
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
    <script type="text/javascript" src="../../inc/js_lang.php"></script>
    
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
                            return "ֻ����д������";
                        }
                    }
                })
            form.on('select(contract_id)', function (data) {
                    var elem = data.elem;
                    var contract_name = $(data.elem).find("option:selected").attr("data-name");
                    console.log(contract_name);
                    $("input[name='contract_name']").val(contract_name);
                    form.render('select','project_level');
                });
            form.render();
        });
    });
</script>
</head>

<body>
    <div style="width: 100%;height:auto;margin:auto;">
        <form id="mainForm" class="layui-form" lay-filter="mainForm" method="post" action="action.php?action=Retrieve" accept-charset='gbk' enctype="multipart/form-data">
            <div class="r" style="height: auto;">
                <div style="text-align: left;" class="layui-btn-group">
                    <table id="tab" width="100%">
                        <tr class="layui-bg-body">
                            <td style="padding: 5px;">
                                <input lay-submit  name="btn_submit" actionType="Save" value="<?=$LG_CUSTOMER_DATA['�ύ'][$_COOKIE['LANG']]?>" type=submit type=button class="layui-btn layui-btn-oa layui-btn-sm" />
                                <input name="btn_close" value="<?=$LG_CUSTOMER_DATA['�ر�'][$_COOKIE['LANG']]?>" type=button class="layui-btn layui-btn-oa layui-btn-sm" onclick="window.close();return false;" />
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
            <br />
            <div class="weadmin-body" style="width:80%;margin:1% 9%;">
                <input type="hidden" name="dataType" id="dataType" value="addCustomerFlow" />
                <input type="hidden" name="customer_id" id="customer_id" value="<?= $flow[id] ?>" />
                <input type="hidden" name="form_id" id="form_id" value="<?= $flow[form_id] ?>" />
                <input type="hidden" name="id" id="id" value="<?= $flow[id] ?>" />
                <input type="hidden" name="user_bu" id="user_bu" value="<?=$flow[user_bu]?>" />
                <table class="TableBlock" width="100%" align="center">
                    <tr>
                        <td class="TableContent"><?=$LG_CUSTOMER_DATA['�ͻ�����'][$_COOKIE['LANG']]?></td>
                        <td class="TableData" colspan="3">
                            <input type="text"  readonly class="layui-input" value="<?= $flow[customer_name] ?>" />
                        </td>
                    </tr>
                    <tr>
                        <td class="TableContent"><?=$LG_CUSTOMER_DATA['��ϵ��'][$_COOKIE['LANG']]?></td>
                        <td class="TableData" colspan="3">
                        <select name="contract_id" id="contract_id" class="normalSelect" lay-filter="contract_id">
                            <option value=""><?=$LG_CUSTOMER_DATA['��ѡ��'][$_COOKIE['LANG']]?></option>
                            <?php foreach($contacts as $contact):?>
                            <option value='<?=$contact['id']?>' <?php if ($contact['id'] == $flow[contract_id]||$contact['name'] == $flow[contract_name]) : ?>selected<?php endif; ?> data-name="<?=$contact['name']?>"><?=$contact['name']?></option>
                            <?php endforeach;?>
                        </select>
                        <input type="hidden"  readonly name="contract_name" class="layui-input" value="<?= $flow[contract_name] ?>" />
                            </td>
                    </tr>
                    <tr>
                        <td class="TableContent"><?=$LG_CUSTOMER_DATA['��������'][$_COOKIE['LANG']]?></td>
                        <td class="TableData" colspan="3">
                            <input type="text" name="flow_date" id="flow_date" class="layui-input date" value="<?= $flow[flow_date] ?>"  />
                        </td>
                    </tr>
                    <tr>
                    <td class="TableContent"><?=$LG_CUSTOMER_DATA['��Ǣ��ʽ'][$_COOKIE['LANG']]?></td>
                        <td class="TableData" colspan="3">
                            <select name="flow_type" id="flow_type" class="normalSelect">
                                <option value=""><?=$LG_CUSTOMER_DATA['��ѡ��'][$_COOKIE['LANG']]?></option>
                                <?php foreach($selectArr['customer_flow_type'] as $customer_type):?>
                                <option value='<?=$customer_type['paras_value']?>' <?php if ($flow['flow_type'] == $customer_type['paras_value']) : ?>selected<?php endif; ?>><?=$LG_CUSTOMER_DATA[$customer_type['paras_desc']][$_COOKIE['LANG']]?></option>
                                <?php endforeach;?>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td class="TableContent"><?=$LG_CUSTOMER_DATA['����'][$_COOKIE['LANG']]?></td>
                        <td class="TableData" colspan="3">
                            <textarea class="layui-textarea" id="need" name="need" placeholder="<?=$LG_CUSTOMER_DATA['����'][$_COOKIE['LANG']]?>" autocomplete="off"><?= $flow[need] ?></textarea>
                        </td>
                    </tr>
                    <tr>
                        <td class="TableContent"><?=$LG_CUSTOMER_DATA['Ŀǰ��Ӧ��'][$_COOKIE['LANG']]?></td>
                        <td class="TableData" colspan="3">
                            <input type="text" name="purma"  class="layui-input" value="<?= $flow[purma] ?>" />
                        </td>
                    </tr>
                    <tr>
                        <td class="TableContent"><?=$LG_CUSTOMER_DATA['�Ƿ�������ɹ���Ʒ'][$_COOKIE['LANG']]?></td>
                        <td class="TableData">
                        <select name="order_will" id="order_will" class="normalSelect">
                                    <option value=""><?=$LG_CUSTOMER_DATA['��ѡ��'][$_COOKIE['LANG']]?></option>
                                    <option value='Y' <?php if ($flow['order_will'] == 'Y') : ?>selected<?php endif; ?>><?=$LG_CUSTOMER_DATA['��'][$_COOKIE['LANG']]?></option>
                                     <option value='N' <?php if ($flow['order_will'] == 'N') : ?>selected<?php endif; ?>><?=$LG_CUSTOMER_DATA['��'][$_COOKIE['LANG']]?></option>
                                    </select>
                        </td>
                        <td class="TableContent"><?=$LG_CUSTOMER_DATA['������'][$_COOKIE['LANG']]?></td>
                        <td class="TableData">
                            <input type="text" name="order_num"  class="layui-input" value="<?= $flow[order_num] ?>" layui-verify="checknum" />
                        </td>
                    </tr>
                    <tr>
                    <tr>
                        <td class="TableContent" ><?=$LG_CUSTOMER_DATA['����'][$_COOKIE['LANG']]?></td>
                        <td class="TableData"  style="text-align: left;" id="file" colspan="3">
                            <?php
                                if($flow['ATTACH_ID']){
                                    $array=explode('</div>',attach_link($flow['ATTACH_ID'],$flow['ATTACH_NAME'],1,1,1,0,1,0));
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
                        <td class="TableContent"><?=$LG_CUSTOMER_DATA['��̸����'][$_COOKIE['LANG']]?></td>
                        <td class="TableData" colspan="3">
                            <textarea class="layui-textarea" id="remark" name="remark" placeholder="<?=$LG_CUSTOMER_DATA['��̸����'][$_COOKIE['LANG']]?>" autocomplete="off"><?= $flow[remark] ?></textarea>
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
                        layer.alert('�������쳣: ' + e, {
                            icon: 5
                        });
                        return;
                    }
                    if (reJson.code == "200") {
                        layer.alert(btnVal + "�ɹ�!", {
                            icon: 1
                        }, function(index, layero) {
                            window.opener.location.reload();
                            parent.layer.close(index);
                            parent.closeIframe();
                        });
                    } else {
                        layer.alert(btnVal + 'ʧ�ܣ�', {
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
                        layer.alert('�������쳣: ' + e, {
                            icon: 5
                        });
                        return;
                    }
                    if (reJson.code == "200") {
                        layer.alert(btnVal + "�ɹ�!", {
                            icon: 1
                        }, function(index, layero) {
                            // var index = parent.layer.getFrameIndex(window.name); //�ȵõ���ǰiframe�������
                            parent.layer.close(index); //��ִ�йر�
                        });
                    } else {
                        layer.alert(btnVal + 'ʧ�ܣ�', {
                            icon: 5
                        });
                    }
                });
            });

            $("#temp_code").blur(function() {
                var url = "action.php?action=Retrieve";
                // У��ϵͳ��Ŀ����
                if (this.value != '') {
                    $.post(url, {
                        dataType: 'verifyProjectCode',
                        projectCode: this.value
                    }, function(data) {
                        var reJson = null;
                        reJson = eval('(' + data + ')');
                        if (reJson.code == '200') {
                            if (reJson.data) {
                                layer.msg('��Ŀ�����Ѵ��ڣ�');
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
                    $("#user_id" + number).append("<option value=''>��ѡ��</option>");
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