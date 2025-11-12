<?php
include_once("../../inc/auth.php");
include_once("../../inc/utility_all.php");
include_once("../../inc/utility_org.php");
include_once("../../inc/utility_file.php");
include_once("lang.php");
// 详情页面 - 关闭
// 审批页面 - 确认、退回、关闭
// 变更登记 - 只显示信息
$HTML_PAGE_TITLE = _($LG_CUSTOMER_DATA['客户信息登记表'][$_COOKIE['LANG']]);
include_once("../../inc/header.inc.php");
include_once("../../common/Common.php");
include_once("ActionModel.php");
include_once("../../common/ProcessModel.php");
include_once("../../common/DataModel.php");
require_once("../../common/UserModel.php");
$userModel = new UserModel();
$dataModel = new DataModel();
$userId = $_SESSION['LOGIN_USER_ID'];
$deptId = $_SESSION['LOGIN_DEPT_ID'];
$action = new ActionModel();
if (empty($id)) {
    Message("提示", "对应数据不存在！");
    exit;
}
$query = "select d.customer_name,k.* from inhe_customer_data_flow k " .
    " left join inhe_customer_data d on k.form_id = d.form_id where k.id='$_GET[id]'";
$res = exequery(TD::conn(), $query);
$flow = mysql_fetch_assoc($res);

$param['id'] = $flow['form_id'];
$data = $action->queryById($param);
$main = $data['main'];
$main_user_name = $main['user_name'];
$main_dept_name = $main['dept_name'];
$main_write_time = $main['write_time'];
$mainid = $main[id];
//存在变更，则取上次变更信息
if($main['version']>0 && $main['type']=="customer_data"){
    $item=$dataModel->getLastChange($main['form_id'],$main['version'],'customer_data_change');
    if($item['form_id']){
        $data = $action->queryById(array("id"=>$item['form_id'],"customer_id"=>$mainid));
        $main = $data['main'];
        $main['type']="customer_data";
        $main['form_id']=$main['related_id'];
        $main['createUserName']=$main_user_name;
        $main['deptName']=$main_dept_name;
        $main['write_time']=$main_write_time;
    }
}
$contacts = $data['contacts'];
$company = $data['company'];
$share = $data['share'];


$selectParam = array(
    'is_used' => '1', // 可用参数
    'type' => 'customer_type,project_level,project_star,customer_flow_type'
);
$selectArr = $dataModel->getCommonParam($selectParam);
$project_star = array();
foreach ($selectArr['project_star'] as $v) {
    $project_star[$v['paras_value']] = $v;
}

?>
<script type="text/javascript" src="../../inc/js_lang.php"></script>
<script src="<?= MYOA_JS_SERVER ?>/static/js/module.js"></script>
<script language="Javascript" type="text/javascript" src="<?= COMMON_FILE_URL ?>/jquery.js"></script>
<script language="Javascript" type="text/javascript" src="<?= COMMON_FILE_URL ?>/common.js"></script>
<script language="Javascript" type="text/javascript" src="<?= COMMON_FILE_URL ?>/layui/layui.js"></script>
<script language="Javascript" type="text/javascript" src="main.js"></script>
<link rel="stylesheet" type="text/css" href="<?= COMMON_FILE_URL ?>/layui/css/layui.css">
<link rel="stylesheet" type="text/css" href="<?= COMMON_FILE_URL ?>/common.css">

<style>
    .border-solid {
        border: 1px solid;
    }
</style>
<script type="text/javascript">
    $(function() {
        layui.use(['form', 'layer'], function() {
            var layer = layui.layer,
                form = layui.form;

            init();

            form.on('submit(submit)', function(data) {
                var btnVal = data.elem.value;
                var actionType = $(data.elem).attr("actionType");
                var url = "action.php?action=" + actionType;
                var formId = $("#form_id").val();
                getNextApprover(formId, actionType);
                return false;
            });

            form.render();
        });
    });

    // 页面初始化加载
    function init() {
        // textarea初始化样式
        $.each($("textarea"), function(i, n) {
            $(n).css("width", "100%");
            $(n).css("min-height", "50px");
            $(n).css("height", n.scrollHeight + "px");
            $(n).css("margin", "auto");
            $(n).css("resize", "none");
            $(n).css("overflow", "hidden");
            makeExpandingArea(document.getElementById(n.id));
        });
    }

</script>

<body>
    <form id="mainForm" class="layui-form">
        <div style="width: 100%;height:auto;margin:auto;">
            <div class="r" style="height: auto;" <?php if ($isLink) : ?>style="display: none;" <?php endif; ?>>
                <div style="text-align: left;" class="layui-btn-group">
                    <table id="tab2" width="100%">
                        <tr class="layui-bg-body layui-bg-gray" id="tHeader">
                            <td align="center" style="padding: 5px;">
                                
                                <input name="btn_close" value="<?=$LG_CUSTOMER_DATA['关闭'][$_COOKIE['LANG']]?>" type=button class="layui-btn layui-btn-oa layui-btn-sm" onclick="closeIframe();return false;" />
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
            <br />
            <div class="weadmin-body" style="margin-top: 30px">
                <input type="hidden" name="type" id="type" value="<?= $main['type'] ?>" />
                <input type="hidden" name="user_bu" id="user_bu" value="<?= $main['user_bu'] ?>" />
                <input type="hidden" name="form_id" id="form_id" value="<?= $main['form_id'] ?>" />
                <input type="hidden" name="create_user" id="create_user" value="<?= $main['create_user'] ?>" />
                <table width="70%" align="center">
                    <td align="center">
                        <img alt="" src="../../../../../images/<?= $main['user_bu'] ?>/<?= $main['type'] ?>_<?= $main['user_bu'] ?>.jpg" style="width: 34%; height: auto;" title="" />
                    </td>
                </table>
                <br />
                <table class="TableBlock" width="70%" align="center" style="margin-bottom: 5px;">
                <tr>
                        <td>
                            <table id="tab1" width="100%" style="text-align: center;">
                                <tr>
                                    <td class="TableContent" width="10%"><?=$LG_CUSTOMER_DATA['客户名称'][$_COOKIE['LANG']]?></td>
                                    <td class="TableData" width="15%">
                                        <?= $main['customer_name'] ?>
                                    </td>
                                    <td class=" TableContent" width="10%"><?=$LG_CUSTOMER_DATA['简称'][$_COOKIE['LANG']]?></td>
                                    <td class="TableData" width="15%">
                                        <?= $main['short_name'] ?>
                                    </td>
                                    <td class=" TableContent" width="10%"><?=$LG_CUSTOMER_DATA['大洲'][$_COOKIE['LANG']]?></td>
                                    <td class="TableData" width="15%">
                                        <?= $main['continentName'] == '' ? $main["continent"] : $main["continentName"] ?>
                                    </td>
                                    <td class=" TableContent" width="10%"><?=$LG_CUSTOMER_DATA['国家'][$_COOKIE['LANG']]?></td>
                                    <td class="TableData">
                                        <?= $main['countryName'] == '' ? $main["country"] : $main["countryName"] ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td class=" TableContent"><?=$LG_CUSTOMER_DATA['网址'][$_COOKIE['LANG']]?></td>
                                    <td class="TableData" colspan="3">
                                        <?= $main['website'] ?>
                                    </td>
                                    <td class=" TableContent"><?=$LG_CUSTOMER_DATA['地址'][$_COOKIE['LANG']]?></td>
                                    <td class="TableData" colspan="3">
                                        <?= $main['address'] ?>
                                    </td>
                                </tr>
                                <?if($main[user_bu]=="INHENERGY"||$main[user_bu]=="HKNERGY"):?>
                                    <tr>
                                    <td class="TableContent"><?=$LG_CUSTOMER_DATA['类型'][$_COOKIE['LANG']]?></td>
                                    <td class="TableData">
                                        <?php foreach($selectArr['customer_type'] as $customer_type):?>
                                            <?php if ($main['customer_type'] == $customer_type['paras_value']) : ?><?=$LG_CUSTOMER_DATA[$customer_type['paras_desc']][$_COOKIE['LANG']]?><?php endif; ?>
                                        <?php endforeach;?>
                                    </td>
                                    <td class="TableContent"><?=$LG_CUSTOMER_DATA['星级'][$_COOKIE['LANG']]?></td>
                                    <td class="TableData" >
                                            <?php foreach ($selectArr['project_level'] as $val) : ?>
                                                <?php if ($main['project_star'] == $val['paras_value']) : ?><?=$LG_CUSTOMER_DATA[$val['paras_desc']][$_COOKIE['LANG']]?><?php endif; ?>
                                            <?php endforeach; ?>
                                    </td>
                                    <td class="TableContent"><?=$LG_CUSTOMER_DATA['星级图标'][$_COOKIE['LANG']]?></td>
                                    <td class="TableData" colspan="3">
                                        <div id="star_icon">
                                        <font style="color: red;font-size:20px"><?= $project_star[$main['project_star']]['extra'] ?></font>
                                        </div>
                                    </td>
                                    </tr>
                                <?endif;?>
                                <tr>
                                    <td class=" TableContent"><?=$LG_CUSTOMER_DATA['客户概述'][$_COOKIE['LANG']]?></td>
                                    <td class="TableData" colspan="7">
                                        <textarea readonly class="layui-textarea TextDetails" id="summarize" name="summarize"><?= $main['summarize'] ?></textarea>
                                    </td>
                                </tr>
                                <?if($main[user_bu]=="INHENERGY"||$main[user_bu]=="HKNERGY"):?>
                                <tr>
                                    <td class=" TableContent"><?=$LG_CUSTOMER_DATA['客户关注'][$_COOKIE['LANG']]?></td>
                                    <td class="TableData" colspan="7">
                                        <textarea readonly class="layui-textarea TextDetails" id="focus" name="focus"><?= $main['focus'] ?></textarea>
                                    </td>
                                </tr>
                                <?endif;?>
                                <tr>
                                    <td class="TableHeader" colspan="8"><?=$LG_CUSTOMER_DATA['联系人'][$_COOKIE['LANG']]?></td>
                                </tr>
                                <tr>
                                <td class="TableContent"><?=$LG_CUSTOMER_DATA['姓名'][$_COOKIE['LANG']]?></td>
                                    <td class="TableContent"><?=$LG_CUSTOMER_DATA['电话1'][$_COOKIE['LANG']]?></td>
                                    <td class="TableContent"><?=$LG_CUSTOMER_DATA['电话2'][$_COOKIE['LANG']]?></td>
                                    <td class="TableContent"><?=$LG_CUSTOMER_DATA['邮箱'][$_COOKIE['LANG']]?></td>
                                    <td class="TableContent" colspan="3"><?=$LG_CUSTOMER_DATA['地址'][$_COOKIE['LANG']]?></td>
                                    <td class="TableContent"><?=$LG_CUSTOMER_DATA['备注'][$_COOKIE['LANG']]?></td>
                                </tr>
                                <?php foreach ($contacts as $tk => $tv) : ?>
                                    <tr>
                                        <td class="TableData">
                                            <?= $tv['name'] ?>
                                        </td>
                                        <td class="TableData">
                                            <?= $tv['phone_one'] ?>
                                        </td>
                                        <td class="TableData">
                                            <?= $tv['phone_two'] ?>
                                        </td>
                                        <td class="TableData">
                                            <?= $tv['email'] ?>
                                        </td>
                                        <td class="TableData" colspan="3">
                                            <?= $tv['address'] ?>
                                        </td>
                                        <td class="TableData">
                                            <?= $tv['remark'] ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                                <tr>
                                    <td class="TableContent"><?=$LG_CUSTOMER_DATA['客户关联公司'][$_COOKIE['LANG']]?><br />（<?=$LG_CUSTOMER_DATA['合同相对方'][$_COOKIE['LANG']]?>）</td>
                                    <td class="TableContent"><?=$LG_CUSTOMER_DATA['简称'][$_COOKIE['LANG']]?></td>
                                    <td class="TableContent"><?=$LG_CUSTOMER_DATA['国家'][$_COOKIE['LANG']]?></td>
                                    <td class="TableContent" colspan="3"><?=$LG_CUSTOMER_DATA['地址'][$_COOKIE['LANG']]?></td>
                                    <td class="TableContent"><?=$LG_CUSTOMER_DATA['联系人姓名'][$_COOKIE['LANG']]?></td>
                                    <td class="TableContent"><?=$LG_CUSTOMER_DATA['电话'][$_COOKIE['LANG']]?></td>
                                </tr>
                                <?php foreach ($company as $ck => $cv) : ?>
                                    <tr>
                                        <td class="TableData">
                                            <?= $cv['contract_party'] ?>
                                        </td>
                                        <td class="TableData">
                                            <?= $cv['short_name'] ?>
                                        </td>
                                        <td class="TableData">
                                            <?= $cv['country'] ?>
                                        </td>
                                        <td class="TableData" colspan="3">
                                            <?= $cv['address'] ?>
                                        </td>
                                        <td class="TableData">
                                            <?= $cv['contact_name'] ?>
                                        </td>
                                        <td class="TableData">
                                            <?= $cv['phone'] ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </table>
                            <table id="tab3" width="100%">
                                <tr>
                                    <td class="TableContent" style="text-align: left;width: 25%"><?=$LG_CUSTOMER_DATA['登记单号'][$_COOKIE['LANG']]?>： <?= $main['form_id'] ?></td>
                                    <td class="TableContent" style="text-align: left;width: 25%"><?=$LG_CUSTOMER_DATA['所属部门'][$_COOKIE['LANG']]?>： <?= $main['deptName'] ?></td>
                                    <td class="TableContent" style="text-align: left;width: 25%"><?=$LG_CUSTOMER_DATA['登记人'][$_COOKIE['LANG']]?>： <?= $main['createUserName'] ?></td>
                                    <td class="TableContent" style="text-align: left;"><?=$LG_CUSTOMER_DATA['登记时间'][$_COOKIE['LANG']]?>： <?= $main['write_time'] ?></td>
                                </tr>
                                <?php if (!empty($share)) : ?>
                                    <tr align="left">
                                        <td colspan="4" class="TableContent" style="text-align: left;"><?=$LG_CUSTOMER_DATA['共享业务人员'][$_COOKIE['LANG']]?>： <?= $share ?></td>
                                    </tr>
                                <?php endif; ?>
                            </table>
                        </td>
                    </tr>
                </table>
                
                <table class="TableBlock" width="70%" align="center" style="margin-bottom: 5px;">
                    <tr>
                        <td>
                            <table id="tab4" width="100%" style="text-align: center;">  
                                <tr>
                                    <td class="TableHeader" colspan="8"><?=$LG_CUSTOMER_DATA['跟进记录'][$_COOKIE['LANG']]?></td>
                                </tr>
                                <?php if (empty($flow)) : ?>
						
                                    <tr class="TableData">
                                        <td colspan="8">
                                            <div style="text-align: center;"><?=$LG_CUSTOMER_DATA['暂无数据'][$_COOKIE['LANG']]?></div>
                                        </td>
                                    </tr>
					            <?php else : ?>

                                <tr style="border:1px;">
                                    <td class="TableContent" ><?=$LG_CUSTOMER_DATA['跟进人员'][$_COOKIE['LANG']]?></td>
                                    <td  class="TableData" ><?=$flow[user_name]?></td>
                                    <td  class="TableContent" ><?=$LG_CUSTOMER_DATA['跟进日期'][$_COOKIE['LANG']]?></td>
                                    <td class="TableData" ><?=$flow[flow_date]?></td>
                                    <td  class="TableContent" ><?=$LG_CUSTOMER_DATA['创建时间'][$_COOKIE['LANG']]?></td>
                                    <td class="TableData" ><?=$flow[create_time]?></td>
                                </tr>
                                <tr style="border:1px;">
                                    <td class="TableContent" ><?=$LG_CUSTOMER_DATA['联系人'][$_COOKIE['LANG']]?></td>
                                    <td  class="TableData" ><?=$flow[contract_name]?></td>
                                    <td  class="TableContent" ><?=$LG_CUSTOMER_DATA['接洽方式'][$_COOKIE['LANG']]?></td>
                                    <td class="TableData" >
                                    <?php foreach($selectArr['customer_flow_type'] as $customer_type):?>
                                        <?php if ($flow['flow_type'] == $customer_type['paras_value']) : ?><?=$LG_CUSTOMER_DATA[$customer_type['paras_desc']][$_COOKIE['LANG']]?><?php endif; ?>
                                    <?php endforeach;?>        
                                    </td>
                                    <td  class="TableContent" ><?=$LG_CUSTOMER_DATA['目前供应商'][$_COOKIE['LANG']]?></td>
                                    <td class="TableData" ><?=$flow[purma]?></td>
                                </tr>
                                <tr style="border:1px;">
                                    <td  class="TableContent"  ><?=$LG_CUSTOMER_DATA['是否有意向采购产品'][$_COOKIE['LANG']]?></td>
                                    <td  class="TableData" >
                                        <?php if ($flow['order_will'] == "Y") : ?><?=$LG_CUSTOMER_DATA['是'][$_COOKIE['LANG']]?><?php endif; ?>
                                        <?php if ($flow['order_will'] == "N") : ?><?=$LG_CUSTOMER_DATA['否'][$_COOKIE['LANG']]?><?php endif; ?>
                                    </td>
                                    <td  class="TableContent"  ><?=$LG_CUSTOMER_DATA['总数量'][$_COOKIE['LANG']]?></td>
                                    <td  class="TableData" colspan="3">
                                        <?=$flow['order_num']?>
                                    </td>
                                </tr>
                                <tr style="border:1px;">
                                    <td  class="TableContent"  ><?=$LG_CUSTOMER_DATA['需求'][$_COOKIE['LANG']]?></td>
                                    <td  class="TableData" colspan="5">
                                    <textarea class="TextDetail" id="need" name="need"  readonly><?=$flow[need]?></textarea>	
                                    </td>
                                </tr>
                                <tr>
                                <td class="TableContent" ><?=$LG_CUSTOMER_DATA['附件'][$_COOKIE['LANG']]?></td>
                                <td class="TableData" colspan="5" align="left"><?php
                                    $array=explode('</div>',attach_link($flow[ATTACH_ID],$flow[ATTACH_NAME],1,1,1,0,0,0));
                                    for($i=0;$i<count($array);$i++){
                                        echo $array[$i]."</div><br>";
                                    }
                                ?></td>
                                </tr>
                                <tr style="border:1px;">
                                    <td  class="TableContent" ><?=$LG_CUSTOMER_DATA['商谈事项'][$_COOKIE['LANG']]?></td>
                                    <td  class="TableData" colspan="5">
                                    <textarea class="TextDetail" id="remark" name="remark"  readonly><?=$flow[remark]?></textarea>	
                                    </td>
                                </tr>
           			            <?php endif; ?>
                            </table>
                        </td>
                    </tr>
                </table>
            </div>
        </div>
    </form>
</body>

</html>