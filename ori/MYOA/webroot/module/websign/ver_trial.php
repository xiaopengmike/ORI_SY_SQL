<?php
/**************************/
/** http://www.52abc.net **/
/***¿ìÀÖ¹þ¹þ  QQ:9886165***/
/**************************/
include_once( "inc/td_config.php" );
//$websign_object_html = "<object id=DWebSignSeal classid=\"CLSID:77709A87-71F9-41AE-904F-886976F99E3E\" style=\"position:absolute;width:0px;height:0px;left:0px;top:0px;\" codebase=\"/module/websign/WebSign_trial.dll#version=4,5,0,0\"></object>";
$websign_object_html = "<object id=DWebSignSeal classid=\"CLSID:77709A87-71F9-41AE-904F-886976F99E3E\" style=\"position:absolute;width:0px;height:0px;left:0px;top:0px;\" codebase=\"/module/websign/WebSign_trial.dll#version=4,0,4,6\"></object>";

$websign_dir = MYOA_ROOT_PATH."module/websign/";
if ( file_exists( $websign_dir."ver.php" ) && file_exists( $websign_dir."WebSign.dll" ) )
{
				include( $websign_dir."/ver.php" );
}
?>