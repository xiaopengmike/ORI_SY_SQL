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
if(!empty($_GET[id])){
    $param['id'] = $_GET[id];
    $action = new ActionModel();
    $data = $action->queryById($param);
    $main =$data[main];
    $attachModel = new AttachModel();
    $attachObj = $attachModel->getAttachById($main[form_id]);
    if($main[project_code]){
        $query = "select * from inhe_project_main where project_code='$main[project_code]' order by id";
        $res = exequery(TD::conn(), $query);
        $project_main = array();
        while($item = mysql_fetch_assoc($res)) {
            $project_main=$item;
        }
    }
    
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
                        <td class="TableContent"  width="20%">项目所在地/区域</td>
                        <td class="TableData">
                            <?=$main[region]?$main[region]:$userInfo[deptName]?>
                        </td>
                    </tr>
                    <tr>
                        <td class="TableContent">项目代号</td>
                        <td class="TableData">
                            <?=$main[project_code] ?>
                        </td>
                    </tr>
                    <tr>
                        <td class="TableContent">项目星级</td>
                        <td class="TableData">
                        <font style="color: red;font-size:20px"><?=$project_main[star_icon]?></font>
                        </td>
                    </tr>
                    <tr>
                        <td class="TableContent">项目进度</td>
                        <td class="TableData">
                        <span style="float:left"><?=$project_schedule_nodes[$main[progress_nodes]]['paras_desc']?></span>
                        <span <?if(empty($main[project_number])):?>hidden="hidden"<?endif;?> class="project_number_tr" style="color:black;float:left;" >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;涉及项目编号：</span>
                        <span <?if(empty($main[project_number])):?>hidden="hidden"<?endif;?> class="project_number_tr" style="float:left">
                            <?=$main[project_number] ?>
                            </span>
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
                        <input id="project_schedule_nodes_attach" type="button" class="layui-btn layui-bg-gray btn-upload" style="display: none;" readonly />
                        </td>
                    </tr>
                    <tr>
                        <td class="TableContent">登记人员</td>
                        <td class="TableData">
                            <pre><?=$main[user_name] ?></pre>
                        </td>
                    </tr>
                    <tr>
                        <td class="TableContent">登记时间</td>
                        <td class="TableData">
                            <pre><?=$main[create_time] ?></pre>
                        </td>
                    </tr>
                </table>
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
               var attachObj = <?= $attachObj?>;
                $.initAttachList(attachObj);
            })
        })
</script>

</html>