<?
include_once("inc/auth.inc.php");


$MENU_TOP = array(
   array("text" => "索样申请单", "href" => "list.php", "target" => "", "title" => "", "img" => MYOA_STATIC_SERVER . "/static/images/menu/news.gif"),
   array("text" => "索样确认单", "href" => "sample_comfirm/list.php", "target" => "", "title" => "", "img" => MYOA_STATIC_SERVER . "/static/images/menu/news.gif"),
   array("text" => "索样退回统计", "href" => "sample_report/list.php", "target" => "", "title" => "", "img" => MYOA_STATIC_SERVER . "/static/images/menu/news.gif"),
   // array("text" => "外包服务辅料更单", "href" => "list_change.php", "target" => "", "title" => "", "img" => MYOA_STATIC_SERVER . "/static/images/menu/news.gif"),
);

include_once("inc/menu_top.php");