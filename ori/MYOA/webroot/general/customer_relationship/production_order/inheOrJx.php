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


$deptId = $_SESSION['LOGIN_DEPT_ID'];
$userId = $_SESSION['LOGIN_USER_ID'];
$userName = $_SESSION['LOGIN_USER_NAME'];
$currentTime = date('Y-m-d H:i:s', time());

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

if (in_array($main[status],array("R","Y")) ) {
    $INHEORJX=$main[sub_user_bu]=='INHE'?'inhe_'.$main['form_id']:'jxinhe_'.$main['form_id'];
    $sql = " select k.* from inhe_production_order k where form_id='$INHEORJX'" ;
    $res = exequery(TD::conn(), $sql);
    if ($item = mysql_fetch_assoc($res)) {
        Message("提示", "抛单数据已存在！");
        exit;
    }
    //生产通知单模块，董事长审核后,备案中自动生成其他公司生产通知单
    $process->autoCreateProductOrderNew(array('form_id'=>$main['form_id'],'user_bu'=>$main['user_bu']));
    Message("提示", "抛单成功！");
    exit;
}else{
    Message("提示", "生产通知单未审核！");
    exit;
}

?>
