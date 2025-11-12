<?
include_once("inc/auth.inc.php");

$HTML_PAGE_TITLE = _("项目进度登记表");

$MENU_TOP = array(
   array("text" => "项目维度报表", "href" => "list.php", "target" => "", "title" => "", "img" => MYOA_STATIC_SERVER . "/static/images/menu/news.gif"),
   array("text" => "区域维度报表", "href" => "list2.php", "target" => "", "title" => "", "img" => MYOA_STATIC_SERVER . "/static/images/menu/news.gif"),
   array("text" => "进度维度报表", "href" => "list3.php", "target" => "", "title" => "", "img" => MYOA_STATIC_SERVER . "/static/images/menu/news.gif"),
   
);

include_once("inc/menu_top.php");

?>