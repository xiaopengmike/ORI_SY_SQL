<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
include_once("inc/utility_file.php");
include_once("inc/utility_field.php");
include_once("/MYOA/webroot/general/hr/UserCommonModel.php");
$model = new UserCommonModel();

$userId = $_SESSION['LOGIN_USER_ID'];
$deptId = $_SESSION['LOGIN_DEPT_ID'];

$buSql = " select user_bu from department where dept_id = '" . $deptId . "' ";
$buRes = exequery(TD::conn(), $buSql);
$userBuArr = mysql_fetch_assoc($buRes);
$userBu = $userBuArr['user_bu'];

$strWhere = " where 1=1 ";
$deptSelectArr = array();

$type = "dept_select";
$adminArr = $model->getAdminDataQuery($userId, $type);
$otherWhere = "";

if ($adminArr['isAdmin']) {
  if (strpos($adminArr['userBu'], 'INHEGRID') !== false || strpos($adminArr['userBu'], 'INHENERGY') !== false || strpos($adminArr['userBu'], 'WITLINK') !== false) {
    $otherWhere = " or d.dept_id = '126' ";
  }
  $strWhere .= "  and (d.user_bu in(" . $adminArr['userBu'] . ") or d.dept_id = '2' " . $otherWhere . " ) ";
} else {
  if (strpos($userBu, 'INHEGRID') !== false || strpos($userBu, 'INHENERGY') !== false || strpos($userBu, 'WITLINK') !== false) {
    $otherWhere = " or d.dept_id = '126' ";
  }
  $strWhere .= " and (d.user_bu = '" . $userBu . "' or d.dept_id = '2' " . $otherWhere . " ) ";
}

$sql = "select d.DEPT_ID ID, d.DEPT_NAME NAME, d.DEPT_PARENT PARENT from department d " . $strWhere;
$orderBy = " order by dept_no ";

$res = exequery(TD::conn(), $sql . $orderBy);
while ($row = mysql_fetch_assoc($res)) {
  $deptSelectArr[] = $row;
}

echo json_encode($model->array_iconv($deptSelectArr));
exit;
