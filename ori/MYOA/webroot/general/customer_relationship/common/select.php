<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");

include_once("inc/header.inc.php");
include_once("common/Common.php");
// var_dump(111);exit;

?>
<script type="text/javascript" src="/inc/js_lang.php"></script>
<script src="<?= MYOA_JS_SERVER ?>/static/js/module.js"></script>
<SCRIPT language="Javascript" type="text/javascript" src="<?= COMMON_FILE_URL ?>/jquery.js"></SCRIPT>

<?
$paras = $_POST;
$query = stripslashes($paras['contents']); // 去除转义字符
$cursor = exequery(TD::conn(), $query);
$COUNT = mysql_num_rows($cursor);
$resultArr = $keyArr = array();
while ($ROW = mysql_fetch_assoc($cursor)) {
    $resultArr[] = $ROW;
}
if (count($resultArr) > 0) {
    $keyArr = array_keys($resultArr[0]);
}
if ($paras['sub'] == "export") {
    //直接用头部信息输出excel格式文件，内容以表格形式展示。
    $curTime = date('YmdHms', time());
    $filename = 'table' . mt_rand(100,1000);
    // $filename=$curTime.'_KPI';
    header("Content-type: application/vnd.ms-excel; charset=gbk");
    header("Content-Disposition: attachment; filename=$filename.xls");
    //$list为数据库查询结果，既二维数组。利用循环出表格，直接输出，既在线生成execl文件
    $data = "";
    $data .= '<table border=1><thead><tr>';
    foreach ($keyArr as $ekk => $ekv) {
        $data .= '<td nowrap align="center">'.$ekv.'</td>';
    }
    $data .= '</tr></thead>';
    foreach ($resultArr as $erk => $erv) {
        $data .= '<tr>';
        foreach ($erv as $etdk => $etdv) {
            $data .= '<td align="center">'.$etdv.'</td>';
        }
        $data .= '</tr>';
    }
    $data .= '</table>';

    echo $data . "\t";
    exit;
}

?>

<body class="bodycolor">
    <form enctype="multipart/form-data" action="select.php" method="post" name="form1">
        <table>
            <tr>
                <td nowrap align="center">
                    <h3><b><?= _("Output：") ?></b></h3>
                </td>
                <td>
                    <textarea id="contents" name="contents" style="width:600px;resize:none;overflow:hidden;"><?= $query ?></textarea>
                </td>
                <td>
                    <INPUT type="submit" value="query" name="sub" class="BigButton">
                    <INPUT type="submit" value="export" name="sub" class="BigButton">
                </td>
            </tr>
        </table>
    </form>
    <?
    if ($COUNT <= 0) {
        Message("", _("无对应记录"));
        exit;
    }
    ?>
    <table class="TableList" width="100%" id="tab">
        <thead class="TableHeader">
            <?php
            foreach ($keyArr as $kk => $kv) { ?>
                <td nowrap align="center"><?= $kv ?></td>
            <?php
            } ?>
        </thead>

        <?php foreach ($resultArr as $rk => $rv) { ?>
            <tr class="TableData">
                <?php foreach ($rv as $tdk => $tdv) { ?>
                    <td align="center"><?= $tdv ?></td>
                <?php
                    }
                    ?>
            </tr>
        <?
        } ?>
    </table>
</body>

</html>