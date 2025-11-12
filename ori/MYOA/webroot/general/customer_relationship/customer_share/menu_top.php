<?
include_once("inc/auth.inc.php");
if(empty($_COOKIE[LANG])){
   $_COOKIE[LANG]="CH";  //默认设置为中文
}

$MENU_TOP = array(
   array("text" => _($LG_CUSTOMER_DATA['客户信息共享'][$_COOKIE['LANG']]), "href" => "list.php", "target" => "", "title" => "", "img" => MYOA_STATIC_SERVER . "/static/images/menu/news.gif"),
);

include_once("inc/menu_top.php");