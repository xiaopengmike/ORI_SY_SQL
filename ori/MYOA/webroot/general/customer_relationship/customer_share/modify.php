<?php
require_once("inc/auth.inc.php");
require_once("inc/utility_all.php");
require_once("inc/utility_org.php");
include_once("lang.php");
$HTML_PAGE_TITLE = _($LG_CUSTOMER_DATA['客户信息共享'][$_COOKIE['LANG']]);
require_once("inc/header.inc.php");
require_once("common/Common.php");
require_once("ActionModel.php");
require_once("../common/DataModel.php");
require_once("../common/UserModel.php");
if (empty($id)) {
    Message("提示", "对应数据不存在！");
    exit;
}
$param['id'] = $id;
$action = new ActionModel();
$dataModel = new DataModel();
$data = $action->queryById($param);
$main = $data['main'];
$sql = " select * from inhe_customer_data where id='$main[customer_id]' ";
$res = exequery(TD::conn(), $sql);
$customer_data = mysql_fetch_assoc($res);
//存在变更，则取上次变更信息
if($customer_data['version']>0 ){
    $customer_data=$dataModel->getLastChange($customer_data['form_id'],$customer_data['version'],'customer_data_change');
}
$sql = " select * from inhe_customer_company where form_id='$customer_data[form_id]' ";
$res = exequery(TD::conn(), $sql);
while ($item = mysql_fetch_assoc($res)) {
    $contractPartyArr[] = $item['contract_party'];
}



$isChange = '';
$submitAction = 'Submit';
$saveAction = 'Save';
if (strpos($main['type'], '_change') != false) {
    $isChange = '_change';
    $submitAction = 'Change';
    $saveAction = 'ChangeSave';
}
$selectParam = array(
    'is_used' => '1', // 可用参数
    'type' => 'customer_type,project_level,customer_source'
);
$selectArr = $dataModel->getCommonParam($selectParam);

$sql = " select * from user where find_in_set(user_id,'$main[user_ids]') ";
$res = exequery(TD::conn(), $sql);
while ($item = mysql_fetch_assoc($res)) {
    $userArr[] = $item['USER_NAME'];
}
mysql_free_result($res);
$result = implode(',', $userArr);
$shareUserArr = rtrim($result, ',');

?>
<script type="text/javascript" src="/inc/js_lang.php"></script>
<script src="<?= MYOA_JS_SERVER ?>/static/js/module.js"></script>
<script language="Javascript" type="text/javascript" src="<?= COMMON_FILE_URL ?>/jquery.js"></script>
<script language="Javascript" type="text/javascript" src="<?= COMMON_FILE_URL ?>/common.js"></script>
<script language="Javascript" type="text/javascript" src="<?= COMMON_FILE_URL ?>/layui/layui.js"></script>
<script language="Javascript" type="text/javascript" src="main.js"></script>
<script language="Javascript" type="text/javascript" src="../add.js"></script>
<link rel="stylesheet" type="text/css" href="<?= COMMON_FILE_URL ?>/layui/css/layui.css">
<link rel="stylesheet" type="text/css" href="<?= COMMON_FILE_URL ?>/common.css">
<script type="text/javascript">
    $(function() {
        layui.use(['form', 'layer'], function() {
            var layer = layui.layer,
                form = layui.form;
            $.initTextarea();
            $.formSubmit();
            //makeCountrySelect();
            //findCustomerExists();
            form.on('select(project_level)', function (data) {
                    var starCount = data.value;
                    var html = "";
                    var starShow = "";
                    if (parseInt(starCount) < 5) {
                        for (var i = 0; i < parseInt(starCount) + 1; i++) {
                            starShow += "★";
                        }
                    }
                    html = '<font style="color: red;font-size:20px">' + starShow + '</font>';
                    $("#star_icon").html(html);
                    form.render('select','project_level');
                });
            form.render();
        });
        $("#customer_name").click(function() {
            var user_bu=$("#user_bu").val();
            var index = layui.layer.open({
                title: "客户列表",
                type: 2,
                content: "./customer_list.php?user_bu="+user_bu,
                area: ['600px', '650px'],
                success: function(layero, index) {
                    setTimeout(function() {
                        layui.layer.tips('点击此处返回列表', '.layui-layer-setwin .layui-layer-close', {
                            tips: 3
                        });
                    }, 500)
                }
            });
        });
    });
    function returnParentInfo(reData){
        layui.use(['form', 'layer'], function() {
                var layer = layui.layer,
                    form = layui.form;
                $("#customer_name").val(reData.customer_name);
                $("#customer_id").val(reData.id);
                var user_bu=$("#user_bu").val();
                if(user_bu!=reData.user_bu){
                    $("#user_bu").val(reData.user_bu);
                    top_img_src="../../../../../images/"+reData.user_bu+"/customer_share_"+reData.user_bu+".jpg";
                    $("#top_img").attr("src", top_img_src);;
                }
        });
    }
    function getUserSelect(obj) {
        layui.use(['form', 'layer'], function() {
            var layer = layui.layer,
                form = layui.form;

            layer.open({
                title: '业务人员选择',
                type: 2,
                skin: 'layui-layer-rim',
                area: ['75%', '80%'],
                fixed: false,
                maxmin: true,
                offset: '50px',
                content: '../common/list/type_list.php?domId=' + obj.id,
                moveOut: true
            });
            form.render();
        });
    }

    function returnUser(userName, userId, domId) {
        $("#" + domId).val(userName);
        $("#" + domId).parent().find("[data-type='user_id']").val(userId); //取userId隐藏栏位赋值
    }

    // 页面初始化加载
    function init() {
        // textarea初始化样式
        $.each($("textarea"), function(i, n) {
            $(n).css("width", "100%");
            $(n).css("min-height", "20px");
            $(n).css("margin", "auto");
            $(n).css("resize", "none");
            $(n).css("overflow", "hidden");
            makeExpandingArea(document.getElementById(n.id));
        });
    }
</script>

<body>
    <form id="mainForm" class="layui-form">
        <div style="width: 100%;height:auto;margin:auto;">
            <div class="r" style="height: auto;">
                <div style="text-align: left;" class="layui-btn-group">
                    <table id="tab2" width="100%">
                        <tr class="layui-bg-body layui-bg-gray" id="tHeader">
                            <td align="center" style="padding: 5px;">
                                <input lay-submit lay-filter="submit" name="btn_submit" actionType="<?= $submitAction ?>" value="<?=$LG_CUSTOMER_DATA['提交'][$_COOKIE['LANG']]?>" type=button class="layui-btn layui-btn-oa layui-btn-sm" />
                                <input lay-submit lay-filter="submit" name="btn_save" actionType="<?= $saveAction ?>" value="<?=$LG_CUSTOMER_DATA['保存'][$_COOKIE['LANG']]?>" type=button class="layui-btn layui-btn-oa layui-btn-sm" />
                                <input name="btn_close" value="<?=$LG_CUSTOMER_DATA['关闭'][$_COOKIE['LANG']]?>" type=button class="layui-btn layui-btn-oa layui-btn-sm" onclick="$.closeIframe();return false;" />
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
            <br />
            <div class="weadmin-body" style="margin-top: 30px">
                <input type="hidden" name="fieldName" value="form_id" />
                <input type="hidden" name="tableName" value="inhe_customer_share_main" />
                <input type="hidden" name="flowType" value="khgx" />
                <input type="hidden" name="digit" value="4" />
                <input type="hidden" name="type" id="type" value="customer_share<?= $isChange ?>" />
                <input type="hidden" name="user_bu" id="user_bu" value="<?= $main['user_bu'] ?>" />
                <input type="hidden" name="form_id" id="form_id" value="<?= $main['form_id'] ?>" />
                <input type="hidden" name="title" id="title" value="<?= $main['title'] ?>" />
                <input type="hidden" name="step" id="step" value="1" />
                <input type="hidden" id="LANG" value="<?=$_COOKIE[LANG]?>" />
               
                <table width="70%" align="center">
                    <td align="center">
                        <img alt="" src="../../../../../images/<?= $main['user_bu'] ?>/customer_share<?= $isChange ?>_<?= $main['user_bu'] ?>.jpg" style="width: 34%; height: auto;" title="" />
                    </td>
                </table>
                <br />
                <table class="TableBlock" width="70%" align="center">
                <tr>
                                <td class="TableContent" width="25%"><?=$LG_CUSTOMER_DATA['客户名称'][$_COOKIE['LANG']]?></td>
                                <td class="TableData">
                                    <input type="hidden" name="user_id" id="user_id" value="<?=$main[user_ids]?>">
                                    <input type="hidden" name="customer_id" id="customer_id" value="<?=$main['customer_id']?>">
                                    <input class="layui-input mandatory" placeholder="点击选择客户" name="customer_name" id="customer_name" value="<?=$main['customer_name']?>" readonly lay-verify="required">
                                </td>
                            </tr>
                            <tr>
                                <td class="TableContent" width="25%"><?=$LG_CUSTOMER_DATA['备注'][$_COOKIE['LANG']]?></td>
                                <td class="TableData">
                                   
                                    <textarea class="layui-textarea" id="remark" name="remark" placeholder="备注说明" lay-verify="required"><?=$main[remark]?></textarea>
                                </td>
                            </tr>
                </table>
            </div>
        </div>
    </form>
</body>

</html>