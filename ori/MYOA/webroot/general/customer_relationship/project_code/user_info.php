<?php
include_once("/MYOA/webroot/general/hr/UserCommonModel.php");
$model = new UserCommonModel();

$sql = " select u.uid, u.user_id, u.user_name, u.user_byid, p.rzsj from user u " .
       " left join interviewmx2 p on p.rzgh = u.user_byid " .
       " where u.dept_id = '".$deptId."' ";

$res = exequery(TD::conn(), $sql);
$result = array();
while ($item = mysql_fetch_assoc($res)) {
    $result[] = $item;
}

// 释放结果集，减少内存占用
mysql_free_result($res);



echo json_encode($model->array_iconv($result));
