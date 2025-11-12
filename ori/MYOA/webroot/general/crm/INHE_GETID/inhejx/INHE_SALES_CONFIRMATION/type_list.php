<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
include_once("inc/utility_org.php");

$HTML_PAGE_TITLE = _("人员选择");
include_once("inc/header.inc.php");
include_once("common/Common.php");

$deptId = $_SESSION["LOGIN_DEPT_ID"];
// 暂时取全公司所有人范围，可通过dept_id和user_bu进行数据范围限制
$deptId = 0;
$sql = " select dept_parent from department where dept_id='" . $deptId . "' ";
$deptRes = exequery(TD::conn(), $sql);
$item = mysql_fetch_assoc($deptRes);
$deptId = $item['dept_parent'];

if (!isset($xtree) || $xtree == "") {
    // &PARA_TARGET=$PARA_TARGET&PARA_URL=$PARA_URL
    $xtree = "./type_tree.php?DEPT_PARENT=$deptId";
    //$xtree="/inc/dept_list/tree.php";
}
?>
<script type="text/javascript" src="/inc/js_lang.php"></script>
<script src="<?= MYOA_JS_SERVER ?>/static/js/module.js"></script>
<link rel="stylesheet" type="text/css" href="<?= MYOA_STATIC_SERVER ?>/static/images/org/ui.dynatree.css<?= $GZIP_POSTFIX ?>">
<script type="text/javascript" src="/inc/js_lang.php"></script>
<script type="text/javascript" src="<?= MYOA_JS_SERVER ?>/static/js/tree.js<?= $GZIP_POSTFIX ?>"></script>
<SCRIPT language="Javascript" type="text/javascript" src="<?= COMMON_FILE_URL ?>/common.js"></SCRIPT>
<SCRIPT language="Javascript" type="text/javascript" src="<?= COMMON_FILE_URL ?>/layui/layui.js"></SCRIPT>
<!-- <link rel="stylesheet" type="text/css" href="<?= COMMON_FILE_URL ?>/layui/css/layui.css"> -->
<link rel="stylesheet" type="text/css" href="<?= COMMON_FILE_URL ?>/common.css">

<script type="text/javascript">
    $(function() {
        //var tree = new Tree("tree", "./type_tree.php?DEPT_PARENT=0&PARA_TARGET=product_main&PARA_URL=user_list.php");
        // &PARA_TARGET=list&PARA_URL=user_list.php
        // id, jsonURL, iconsPath, checkbox, selectMode, options 参数  ,"","","3",{"minExpandLevel":1,"persist":true}
        var tree = new Tree("tree", "./type_tree.php?DEPT_PARENT=<?= $deptId ?>", "", true, "1", {
            "minExpandLevel": 1,
            "persist": false
        });
        tree.BuildTree();
    });

    function chooseUser() {
        layui.use(['form', 'layer'], function() {
            var layer = layui.layer,
                form = layui.form;

            var select = $("#tree").dynatree("getTree").getSelectedNodes();
            // alert(select);
            // console.log(select);

            var userSelect = "";
            var userIdVal = "";
            for (var se in select) {
                // console.log(select[se].data);
                // dept_id: "2"
                // key: "meterw"
                // title: "王功勇"
                // user_byid: "INHE-0016"
                // user_id: "meterw"
                select[se].select(true) //false不选中，true选中, 
                userSelect = select[se].data.title;  // 单选
                userIdVal = select[se].data.user_id;
            }
            parent.returnUser(userSelect, userIdVal, "<?= $domId ?>");

            var index = parent.layer.getFrameIndex(window.name);
            parent.layer.close(index); //关闭当前页  

            form.render();
        });
    }
</script>

<body>
    <div class="weadmin-body">
        <ul>
            <div>
                <div id="tree" style="float: left"></div>
                <div>
                    <input type="button" class="BigButton" id="choose_btn" onclick="chooseUser();return false;" value="选择" />
                </div>
            </div>
        </ul>
    </div>
</body>

</html>