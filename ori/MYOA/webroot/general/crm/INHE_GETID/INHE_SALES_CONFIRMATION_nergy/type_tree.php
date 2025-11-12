<?
include_once("general/crm/inc/header.php");
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
include_once("inc/utility_org.php");
include_once("/MYOA/webroot/general/hr/UserCommonModel.php");

$model = new UserCommonModel();
$deptId = $_SESSION['LOGIN_DEPT_ID'];
$userId = $_SESSION['LOGIN_DEPT_ID'];
$userBu = $model->getUserBuNew($deptId);

ob_end_clean();
echo getProdTypeList($DEPT_PARENT);

function getProdTypeList($DEPT_PARENT)
{
  global  $PARA_TARGET, $PARA_URL;

  if ($DEPT_PARENT != 0) {
    $userSql = " SELECT UID, USER_ID, USER_byID, USER_NAME, SEX, LAST_VISIT_TIME, LAST_VISIT_IP, PRIV_NO, DEPT_ID, USER .USER_PRIV, TEL_NO_DEPT, EMAIL, OICQ_NO, MY_STATUS, PRIV_NAME
    FROM USER, USER_PRIV
    WHERE DEPT_ID != 0
    AND ( DEPT_ID = '" . $DEPT_PARENT . "' OR find_in_set('" . $DEPT_PARENT . "', DEPT_ID_OTHER) )
    AND USER .USER_PRIV = USER_PRIV.USER_PRIV
    ORDER BY PRIV_NO, USER_NO, USER_NAME ";
    $userRes = exequery(TD::conn(), $userSql);
    while ($item = mysql_fetch_assoc($userRes)) {
      // $userUrl = "/general/hr/manage/personnel/modify.php?GH=" . $item['USER_byID'];
      // $JSON = "test();";
      $ORG_ARRAY[] = array(
        "title" => td_iconv($item['USER_NAME'], MYOA_CHARSET, 'utf-8'),
        "isFolder" => FALSE,
        "isLazy" => FALSE,
        // "expand" => true,  // 展开
        "key" => $item['USER_ID'],
        "user_id" => $item['USER_ID'],
        "user_byid" => $item['USER_byID'],
        "dept_id" => $item['DEPT_ID'],
        "icon" => $item['SEX'] == 0 ? 'U01.png' : 'U11.png',
        // "url" => td_iconv($userUrl, MYOA_CHARSET, 'utf-8'),
        "tooltip" => td_iconv($item['USER_NAME'], MYOA_CHARSET, 'utf-8'),
        // "json" => td_iconv($JSON, MYOA_CHARSET, 'utf-8'),
        "target" => $PARA_TARGET,
        // "onCheck" => td_iconv($ONCHECK, MYOA_CHARSET, 'utf-8'),
        // "onClick" => null
      );
    }
  }


  $query  = "SELECT * from department ";
  // $query .= "WHERE DEPT_PARENT=\"$DEPT_PARENT\" AND USER_BU='INHEGRID' order by DEPT_NO";  // 公司别控制
  $query .= "WHERE DEPT_PARENT=\"$DEPT_PARENT\" order by DEPT_NO";

  $cursor = exequery(TD::conn(), $query);
  while ($ROW = mysql_fetch_assoc($cursor)) {
    $DEPT_ID = $ROW["DEPT_ID"];
    $DEPT_NAME = $ROW["DEPT_NAME"];
    $USER_BU = $ROW["USER_BU"];

    $DEPT_ID  = htmlspecialchars($DEPT_ID);
    $DEPT_ID  = stripslashes($DEPT_ID);
    $DEPT_NAME  = htmlspecialchars($DEPT_NAME);
    $DEPT_NAME  = stripslashes($DEPT_NAME);

    $CHILD_COUNT = 0;
    $query1  = "SELECT 1 from department where DEPT_PARENT='$DEPT_ID'";
    $cursor1 = exequery(TD::conn(), $query1);
    if ($ROW1 = mysql_fetch_assoc($cursor1)) {
      $CHILD_COUNT++;
    }
    $query1 = "SELECT 1 from USER where DEPT_ID='" . $DEPT_ID . "'";
    $cursor1 = exequery(TD::conn(), $query1);
    if ($ROW1 = mysql_fetch_assoc($cursor1)) {
      $CHILD_COUNT++;
    }
    $JSON = "";
    if ($CHILD_COUNT > 0) {
      // &PARA_TARGET=list&PARA_URL=user_list.php
      $JSON = "./type_tree.php?DEPT_PARENT=$DEPT_ID";
      $IS_LAZY = true;
    }

    $ONCHECK = ($showButton && $DEPT_PRIV1 == "1") ? "click_node" : "";
    // . "&PROD_NAME=" . $DEPT_NAME
    $URL = "$PARA_URL?DEPT_ID=" . $DEPT_ID;
    $ORG_ARRAY[] = array(
      "title" => td_iconv($DEPT_NAME, MYOA_CHARSET, 'utf-8'),
      "isFolder" => FALSE,
      "isLazy" => $IS_LAZY,
      // "expand" => true,
      "key" => $DEPT_ID,
      "dept_id" => $DEPT_ID,
      "icon" => false,
      "url" => td_iconv($URL, MYOA_CHARSET, 'utf-8'),
      "tooltip" => td_iconv($DEPT_NAME, MYOA_CHARSET, 'utf-8'),
      "json" => td_iconv($JSON, MYOA_CHARSET, 'utf-8'),
      "target" => $PARA_TARGET,
      "onCheck" => td_iconv($ONCHECK, MYOA_CHARSET, 'utf-8'),
      "hideCheckbox" => true
    );
  }

  return json_encode($ORG_ARRAY);
}
