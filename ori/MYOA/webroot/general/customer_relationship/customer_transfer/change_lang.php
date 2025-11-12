<?
include_once("inc/auth.inc.php");
include_once("inc/header.inc.php");
setcookie('LANG',$LANG,time()+3600*24*7,'/general/customer_relationship/');  //设置为1周有效
echo "<script>
window.parent.location.reload();
</script>";
//header("location: list.php");
?>