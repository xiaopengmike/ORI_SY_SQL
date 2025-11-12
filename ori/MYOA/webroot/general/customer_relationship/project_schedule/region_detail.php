<?php
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
include_once("inc/utility_org.php");

$HTML_PAGE_TITLE = _("项目进度登记");
include_once("inc/header.inc.php");
include_once("common/Common.php");
include_once("../common/DataModel.php");
include_once("../common/UserModel.php");
require_once("ActionModel.php");
require_once("../common/AttachModel.php");
$dataModel = new DataModel();
$userModel = new UserModel();

$deptId = $_SESSION['LOGIN_DEPT_ID'];
$userId = $_SESSION['LOGIN_USER_ID'];
$userName = $_SESSION['LOGIN_USER_NAME'];
$date = date('Y-m-d');
$userInfo = $userModel->getUserInfo($userId);
$companyNew=$userInfo[company_user_bu];
$selectParam = array(
    'is_used' => '1', // 可用参数
    'type' => 'project_schedule_nodes'
);
$selectArr = $dataModel->getCommonParam($selectParam);
$project_schedule_nodes = array();
foreach ($selectArr['project_schedule_nodes'] as $v) {
    $project_schedule_nodes[$v['paras_value']] = $v;
}

// 管理员权限查询范围
$adminArr = $userModel->getAdminDataQuery($userId, "project_schedule");
$showData = false;
$userBuArr = array();
if ($adminArr['isAdmin']) {
	$showData = true;
	$userBuScope = $adminArr['userBu'];
	$userBuScope = trim($userBuScope, "'");
	$userBuArr = explode("','", $userBuScope);
}
if(!empty($_GET[region_id])){
    $param = $dataModel->getParamToArray();
    $where = " where 1=1 and k.region_id='$_GET[region_id]'";
    $queryStr = "";
    $groupBy = $limit = "";
    $where .= " and k.id in(select max(id) from inhe_project_schedule group by project_code) ";
    $groupBy = " group by k.project_code ";
    $orderBy = " order by k.project_code ";
    $totalQuery = "select k.*,u.user_name createUserName, d.dept_name deptName,
    pr.business_user_name,pr.delivery_user_name,pr.business_user,pr.delivery_user,
    (select star_icon from inhe_project_main where project_code=k.project_code order by id limit 1)star_icon " .
        "  from inhe_project_schedule k " .
        " left join inhe_project_region pr on pr.id = k.region_id " .
        " left join user u on u.user_id = k.create_user_id " .
        " left join department d on d.dept_id = k.dept_id " .
        $where
        .$groupBy. $orderBy;
    $query = $totalQuery . $limit;
    $res = exequery(TD::conn(), $query);
    $result = array();
    while ($item = mysql_fetch_assoc($res)) {
        $attachModel = new AttachModel();
        $attachObj = $attachModel->getAttachById($item[form_id]);
        $item[attach]=json_decode($attachObj,true);
        $result[] = $item;
    }
}
if(in_array($result[0][user_bu],$userBuArr)||$userId==$result[0][business_user]||$userId==$result[0][delivery_user]){

}else{
    Message("错误", _("无权限访问"));
		exit;
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
        
    <script language="Javascript" type="text/javascript" src="../detail.js"></script>
    <link rel="stylesheet" href="<?= COMMON_FILE_URL ?>/css/font.css">
    <link rel="stylesheet" href="<?= COMMON_FILE_URL ?>/css/weadmin.css">
    <link rel="stylesheet" type="text/css" href="<?= COMMON_FILE_URL ?>/common.css">
    <link rel="stylesheet" type="text/css" href="../crm.css">
</head>

<body>
    <div style="width: 100%;height:auto;margin:auto;">
        <form id="mainForm" class="layui-form" lay-filter="mainForm">
            <div class="r" style="height: auto;">
                <div style="text-align: left;" class="layui-btn-group">
                    <table id="tab" width="100%">
                        <tr class="layui-bg-body">
                            <td style="padding: 5px;">
                            <button type="button" class="layui-btn layui-btn-sm layui-btn-oa" onclick="$.printDetail();return false;">打印</button>
                                <input name="btn_close" value="关闭" type=button class="layui-btn layui-btn-oa layui-btn-sm" onclick="window.close();return false;" />
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
            <br />
            <!--startprint-->
            <div class="weadmin-body" style="width:80%;margin:1% 9%;">
            <input type="hidden" name="fieldName" value="form_id" />
                <input type="hidden" name="tableName" value="inhe_project_schedule" />
                <input type="hidden" name="flowType" value="INHEPS" />
                <input type="hidden" name="reset" value="day" />
                <input type="hidden" name="digit" value="2" />
                <input type="hidden" id="type" name="type" value="inhe_project_schedule" />
                <input type="hidden" name="company" id="company" value="<?= $companyNew ?>" />
                <input type="hidden" name="user_bu" id="user_bu" value="<?= $userInfo[company_user_bu] ?>" />
                <input type="hidden" name="dept_id" id="dept_id" value="<?= $userInfo[dept_id] ?>" />
                <input type="hidden" name="dept_name" id="dept_name" value="<?= $userInfo[deptName] ?>" />
                <table class="TableBlock" width="100%" align="center">
                    <tr>
                        
                        <td class="TableContent">项目所在地/区域</td>
                        <td class="TableData">
                            <?=$result[0][region]?$result[0][region]:$userInfo[deptName]?>
                        </td>
                        <td class="TableContent">业务负责人</td>
                        <td class="TableData">
                        <?=$result[0][business_user_name] ?>
                        </td>
                        <td class="TableContent">交付经理</td>
                        <td class="TableData">
                        <?=$result[0][delivery_user_name] ?>
                        </td>
                    </tr>
                </table>
               
                    <br/>
                    <table class="TableBlock" width="100%" align="center">
                    <tr>
                    <td class="TableContent"  width="5%">项目代号</td>
                        <td class="TableContent" width="8%">项目星级</td>
                        <td class="TableContent" width="10%">最新进度</td>
                        <td class="TableContent">进度描述</td>
                        <td class="TableContent" width="10%">涉及项目编号</td>
                        <td class="TableContent"  width="10%">附件</td>
                    </tr>
                    <? foreach ($result as $k=>$main) : ?>
                        <tr>
                        <td class="TableData" rowspan="2" align="center">
                            <b><a href="javascript:void(0);" class="newColor" onclick="pageOperate('project_detail.php?project_code=<?= $main[project_code] ?>&operate=detail','detail');return false;"><?=$main[project_code]?></a></b>
                            </td>
                            <td class="TableData" rowspan="2" align="center">
                                <font style="color: red;font-size:20px"><?=$main[star_icon]?></font>
                            </td>
                            <td class="TableContent" rowspan="2">
                                <?=$project_schedule_nodes[$main[progress_nodes]][paras_desc]?>
                                
                            </td>
                            <td class="TableData">
                                <pre><?=$main[nodes_desc] ?></pre>
                                <br>
                            </td>
                            <td class="TableData" rowspan="2">
                                <?=$main[project_number]?>
                            </td>
                            
                            <td class="TableData" rowspan="2">
                                <? foreach ($main[attach][project_schedule_nodes_attach] as $k2=>$v2) : ?>
                                    <a href="/general/attachment/attach.php?action=download&id=<?=$v2[id]?>"><?=iconv("UTF-8","GBK",$v2[filename])?></a>
                                <?endforeach;?>
                            </td>
                        </tr>
                        <tr>
                            <td class="TableData" style="color:black">
                                <?=$main[user_name]?>&nbsp;&nbsp;&nbsp;&nbsp;<?=$main[create_time]?>
                            </td>
                        </tr>
                    <?endforeach;?>
                </table>
               
                <!-- <? foreach ($result as $k=>$main) : ?>
                    <br/>
                    <table class="TableBlock" width="100%" align="center">
                    <tr>
                        <td class="TableContent" width="10%">项目进度</td>
                        <td class="TableData">
                            <?=$project_schedule_nodes[$main[progress_nodes]][paras_desc]?>
                        </td>
                    </tr>
                   
                    <tr>
                        <td class="TableContent">进度描述</td>
                        <td class="TableData">
                            <pre><?=$main[nodes_desc] ?></pre>
                        </td>
                    </tr>
                    <tr>
                        <td class="TableContent">附件</td>
                        <td class="TableData">
                            <? foreach ($main[attach][project_schedule_nodes_attach] as $k2=>$v2) : ?>
                                <a href="/general/attachment/attach.php?action=download&id=<?=$v2[id]?>"><?=iconv("UTF-8","GBK",$v2[filename])?></a>
                            <?endforeach;?>
                        </td>
                    </tr>
                </table>
                <?endforeach;?> -->
            </div>
            <!--endprint-->
        </form>
    </div>
</body>

<script type="text/javascript">
    $(function() {
        layui.extend({
                attach: '/common/js/extends/attach'
            }).use(['form', 'layer', 'table'], function() {
            var layer = layui.layer,
                form = layui.form,
                table = layui.table;
                //$.initTextarea();
               // $.initAttach();
               // var attachObj = <?= $attachObj ?>;
                //$.initAttachList(attachObj);
            form.on('submit(submit)', function(data) {
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