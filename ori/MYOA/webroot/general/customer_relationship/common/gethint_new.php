<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
include_once("inc/utility_file.php");
include_once("inc/utility_field.php");
include_once("/MYOA/webroot/general/hr/UserCommonModel.php");
$model = new UserCommonModel();
$userId = $_SESSION['LOGIN_USER_ID'];
$deptId = $_SESSION['LOGIN_DEPT_ID'];
$userBu = "";
$userBu = $model->getUserBu($userId);



$strWhere = " where 1=1 ";
if((bool)$flag){
  $strWhere .= " and exists(
      select * from (
      select u.dept_id from user u where u.user_bu=(select uu.user_bu from user uu where uu.user_id = '$_SESSION[LOGIN_USER_ID]') 
      or u.user_id = 'meterw' group by u.dept_id ) dd
            where dd.dept_id = d.dept_id )  ";
}

$sql="select d.* from department d " . $strWhere;
$orderBy = " order by dept_no asc ";



$res=exequery(TD::conn(),$sql);
$i=0;
$hint=array();







while($row = mysql_fetch_assoc($res))
{		
		
}

echo json_encode(gbk2utf8($hint));


?>