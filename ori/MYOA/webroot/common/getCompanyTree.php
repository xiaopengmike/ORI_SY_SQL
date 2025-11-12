<?php
include_once("inc/utility_all.php"); // 如需要使用公用函数则包含
include_once("inc/auth.inc.php");

$query1 = "SELECT dept_id, dept_parent, dept_name FROM department order by dept_no  ";
$cursor = exequery(TD::conn(), $query1);
$max = mysql_num_rows($cursor);
$data = array();
while ($ROW = mysql_fetch_assoc($cursor)) {
	$nId = $ROW['dept_id'];
	$Id = $ROW['dept_parent'];
	$nName = $ROW['dept_name'];
	$data[] = array(
		'id' => $nId,
		'pId' => $Id,
		'name' => $nName
		// 'url' => 'http://inhe0863/general/',
		// 'click' => 'alert("test");',
	);
}


function gbk2utf8($data)
{
	if (is_array($data)) {
		return array_map('gbk2utf8', $data);
	}
	return iconv('gbk', 'utf-8', $data);
}

echo json_encode(gbk2utf8($data));
