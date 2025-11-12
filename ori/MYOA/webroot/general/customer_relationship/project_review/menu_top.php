<?
include_once("inc/auth.inc.php");


$MENU_TOP = array(
   array("text" => "立项申请表", "href" => "list.php", "target" => "", "title" => "", "img" => MYOA_STATIC_SERVER . "/static/images/menu/news.gif"),
   array("text" => "立项申请变更", "href" => "list_change.php", "target" => "", "title" => "", "img" => MYOA_STATIC_SERVER . "/static/images/menu/news.gif"),
   array("text" => "星级申请变更", "href" => "list_star.php", "target" => "", "title" => "", "img" => MYOA_STATIC_SERVER . "/static/images/menu/news.gif")
);

include_once("inc/menu_top.php");