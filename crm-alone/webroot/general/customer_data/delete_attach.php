<?php

include_once "../../inc/auth.php";
include_once "../../inc/header.inc.php";
include_once "../../inc/utility_file.php";
include_once "../../inc/utility_all.php";
$TABLE_NAME = $ATTACH_TABLE_NAME?$ATTACH_PRIMARY_KEY:'inhe_customer_data_flow';
$PRIMARY_KEY = $ATTACH_PRIMARY_KEY?$ATTACH_PRIMARY_KEY:'id';
$PRIMARY_KEY_VALUE = $ATTACH_PRIMARY_KEY_VALUE;
$ATTACH_NAME = $ATTACH_NAME?$ATTACH_NAME:'ATTACH_NAME';
$ATTACH_ID = $ATTACH_ID?$ATTACH_ID:'ATTACH_ID';

$SEND_TIME = time();

$query = "select * from $TABLE_NAME where $PRIMARY_KEY='" . $PRIMARY_KEY_VALUE . "'";
$cursor = exequery(TD::conn(), $query);
$ATTACHMENT_NAME_OLD2 = "";
if ($ROW = mysql_fetch_array($cursor)) {
    $ATTACHMENT_ID_OLD2 = $ROW[$ATTACH_ID];
    $ATTACHMENT_NAME_OLD2 = $ROW[$ATTACH_NAME];
}
$SIZE = 0;
if ($ATTACHMENT_NAME_OLD2 != "") {
    delete_attach($DEL_ATTACHMENT_ID, $DEL_ATTACHMENT_NAME);
    $ATTACHMENT_ID_ARRAY = explode(",", $ATTACHMENT_ID_OLD2);
    $ATTACHMENT_NAME_ARRAY = explode("*", $ATTACHMENT_NAME_OLD2);
    $ARRAY_COUNT = sizeof($ATTACHMENT_ID_ARRAY);
    $I = 0;
    for (; $I < $ARRAY_COUNT; ++$I) {
        if (!($ATTACHMENT_ID_ARRAY[$I] == $DEL_ATTACHMENT_ID)) {
            if ($ATTACHMENT_ID_ARRAY[$I] == "") {
                break;
            }
        } else {
            continue;
        }
        $ATTACHMENT_ID1 .= $ATTACHMENT_ID_ARRAY[$I] . ",";
        $ATTACHMENT_NAME1 .= $ATTACHMENT_NAME_ARRAY[$I] . "*";
        $SIZE += attach_size($ATTACHMENT_ID_ARRAY[$I], $ATTACHMENT_NAME_ARRAY[$I]);
    }
    $ATTACHMENT_ID = $ATTACHMENT_ID1;
    $ATTACHMENT_NAME = $ATTACHMENT_NAME1;
    $query = "update $TABLE_NAME SET $ATTACH_ID='$ATTACHMENT_ID1',$ATTACH_NAME='$ATTACHMENT_NAME' where $PRIMARY_KEY='" . $PRIMARY_KEY_VALUE . "'";
    exequery(TD::conn(), $query);
}
Message("", "ɾ���ɹ���");
Button_Back();
exit;
//echo "\r\n<script>\r\n  parent.reload_page();\r\n</script>";