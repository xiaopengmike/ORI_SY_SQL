<?php
include_once("../../inc/auth.php");
include_once("../../inc/utility_all.php");
include_once("../../inc/utility_org.php");
include_once("lang.php");
$HTML_PAGE_TITLE = _($LG_CUSTOMER_DATA['�ͻ���Ϣ�б�'][$_COOKIE['LANG']]);
include_once("../../inc/header.inc.php");
include_once("../../common/Common.php");
include_once("../../common/DataModel.php");
include_once("../../common/UserModel.php");
$userModel = new UserModel();
$dataModel = new DataModel();
$userId = $_SESSION['LOGIN_USER_ID'];
$deptId = $_SESSION['LOGIN_DEPT_ID'];
// CRM��������
$selectParam = array(
    'is_used' => '1',
    'type' => 'crm_process_sort,crm_process_type'
);
$selectArr = $dataModel->getCommonParam($selectParam);
$continentArr = $dataModel->getContinentData();
$countryArr = $dataModel->getCountryData();
$crm_process_sort = array();
foreach ($selectArr['crm_process_sort'] as $v) {
    $crm_process_sort[$v['paras_value']] = $v;
}
$crm_process_type = array();
foreach ($selectArr['crm_process_type'] as $v) {
    $crm_process_type[$v['paras_value']] = $v['paras_desc'];
}
$userBu = $userModel->getUserBu($deptId);
?>
<script type="text/javascript" src="../../inc/js_lang.php"></script>
<!-- 静态资源已移至 common 目录 -->
<SCRIPT language="Javascript" type="text/javascript" src="<?= COMMON_FILE_URL ?>/jquery.js"></SCRIPT>
<SCRIPT language="Javascript" type="text/javascript" src="<?= COMMON_FILE_URL ?>/common.js"></SCRIPT>
<SCRIPT language="Javascript" type="text/javascript" src="<?= COMMON_FILE_URL ?>/layui/layui.js"></SCRIPT>
<SCRIPT language="Javascript" type="text/javascript" src="main.js?v=1.0.2"></SCRIPT>
<script language="Javascript" type="text/javascript" src="../../customer_relationship/list.js"></script>
<link rel="stylesheet" type="text/css" href="<?= COMMON_FILE_URL ?>/layui/css/layui.css">
<link rel="stylesheet" type="text/css" href="<?= COMMON_FILE_URL ?>/common.css">
<script language="Javascript" type="text/javascript" src="../../customer_relationship/crm.js"></script>
<style>
    .drop-btn {
        cursor: pointer;
    }

    .dropdown {
        position: relative;
        display: inline-block;
    }

    .dropdown-content {
        display: none;
        position: absolute;
        z-index: 9999;
        background-color: lightgray;
        min-width: 154px;
        box-shadow: 0px 8px 16px 0px rgba(0, 0, 0, 0.2);
    }

    .dropdown-content a {
        color: black;
        padding: 12px 16px;
        text-decoration: none;
        display: block;
    }

    .dropdown-content a:hover {
        background-color: #027AFF;
    }

    .dropdown:hover .dropdown-content {
        display: block;
    }
</style>
<script>
    $(function() {
        layui.use(['form', 'layer', 'laydate'], function() {
            var layer = layui.layer,
                form = layui.form;

            $.loadList('1');
            form.on('select(change_lang)', function(data){
                window.location = "change_lang.php?LANG="+data.value;
			});

            form.render();
        });
    });
    function test(){
        var pattern=/^\d+$/;
        if(!ifEmpty($("#flow_date").val())&&!pattern.test($("#flow_date").val())){
            alert($LG_CUSTOMER_DATA['�����������ֻ����д����?][$_COOKIE['LANG']]);
            return false;
        }
        $.loadList('1');
        
    }
</script>

<body class="bodyColorNew">
    <form id="tab" class="layui-form">
        <input type="hidden" id="type" name="type" value="customer_data" />
        <input type="hidden" id="sort_way" name="sort_way" value="" />
        <input type="hidden" id="sort_field" name="sort_field" value="" /> <!-- �����ֶ� -->
        <input type="hidden" id="page_size" name="page_size" value="10" />
        <input type="hidden" id="checkedIds" name="checkedIds" value="" /> <!-- ����ʵ�ֿ�ҳѡ������ -->
        <input type="hidden" name="user_byid" id="user_byid" class="layui-input" value="<?=$user_byid?>" placeholder="�����빤��">
        <input type="hidden" name="starting_time" id="starting_time" class="layui-input" value="<?=$starting_time?>" placeholder="�����������ʼʱ��?>
        <input type="hidden" name="ending_time" id="ending_time" class="layui-input" value="<?=$ending_time?>" placeholder="���������ʱ��?>
        <input type="hidden" name="data_flow" id="data_flow" class="layui-input" value="<?=$data_flow?>" placeholder="�����빤��">
        <input type="hidden" name="order_will" id="order_will" class="layui-input" value="<?=$order_will?>" placeholder="�����빤��">
        <table style="margin: 1%">
            <tr>
                <td align="right"><?=$LG_CUSTOMER_DATA['�Ǽǵ���'][$_COOKIE['LANG']]?></td>
                <td>
                    <input type="text" name="form_id" id="form_id" class="layui-input" placeholder="<?=$LG_CUSTOMER_DATA['������Ǽǵ���?][$_COOKIE['LANG']]?>">
                </td>
                <td align="right"><?=$LG_CUSTOMER_DATA['�ͻ�����'][$_COOKIE['LANG']]?></td>
                <td>
                    <input type="text" name="customer_name" id="customer_name" class="layui-input" placeholder="<?=$LG_CUSTOMER_DATA['������ͻ�����?][$_COOKIE['LANG']]?>">
                </td>
                <td align="right"><?=$LG_CUSTOMER_DATA['���?][$_COOKIE['LANG']]?></td>
                <td>
                    <input type="text" name="short_name" id="short_name" class="layui-input" placeholder="<?=$LG_CUSTOMER_DATA['��������'][$_COOKIE['LANG']]?>">
                </td>
                <td nowrap align="right"><?=$LG_CUSTOMER_DATA['��˾'][$_COOKIE['LANG']]?></td>
				<td>
					<div class="layui-input-inline" >
						<select id="user_bu" name="user_bu" lay-verify="" lay-search>
							<option value=''><?=$LG_CUSTOMER_DATA['��ѡ��'][$_COOKIE['LANG']]?></option>
                            <?php foreach ($crm_process_sort as $company => $item) { ?>
                                <option value='<?=$item[paras_value]?>'><?= $_COOKIE['LANG']=="EN"?$company:$item['paras_desc'] ?></option>
                            <?php } ?>
						</select>
					</div>
				</td>
                </tr>
                <?if($userBu=='INHENERGY'||$userBu=='HKNERGY'||$userId=="INHE-1008"||$userId=="admin"):?>
                <tr>
                    <td align="right"><?=$LG_CUSTOMER_DATA['����'][$_COOKIE['LANG']]?></td>
                    <td>
                    <select id="continent" name="continent" lay-filter="continent" lay-search >
                        <option value=''><?=$LG_CUSTOMER_DATA['��ѡ��'][$_COOKIE['LANG']]?></option>
                        <?php foreach ($continentArr as $ck => $cv) : ?>
                            <option value="<?= $cv['iso_two'] ?>"><?= $_COOKIE['LANG']=="EN"?$cv['en_name']:$cv['zh_name'] ?></option>
                        <?php endforeach; ?>
                    </select>
                    </td>
                    <td align="right"><?=$LG_CUSTOMER_DATA['����'][$_COOKIE['LANG']]?></td>
                    <td>
                    <select id="country" name="country" lay-search >
                        <option value=''><?=$LG_CUSTOMER_DATA['��ѡ��'][$_COOKIE['LANG']]?></option>
                        <?php foreach ($countryArr as $yk => $yv) : ?>
                            <option value="<?= $yv['iso_three'] ?>"><?= $_COOKIE['LANG']=="EN"?$yv['country']:$yv['zh_name'] ?></option>
                        <?php endforeach; ?>
                    </select>
                    </td>
                    <td align="right"><?=$LG_CUSTOMER_DATA['��ϵ��'][$_COOKIE['LANG']]?></td>
                    <td>
                        <input type="text" name="contact" id="contact" class="layui-input" placeholder="<?=$LG_CUSTOMER_DATA['����'][$_COOKIE['LANG']]?>/<?=$LG_CUSTOMER_DATA['�绰'][$_COOKIE['LANG']]?>/<?=$LG_CUSTOMER_DATA['����'][$_COOKIE['LANG']]?>">
                    </td>
                    <td align="right"><?=$LG_CUSTOMER_DATA['�Ƶ���'][$_COOKIE['LANG']]?></td>
                    <td>
                        <input type="text" name="create_user_name" id="create_user_name" class="layui-input" placeholder="<?=$LG_CUSTOMER_DATA['�Ƶ���'][$_COOKIE['LANG']]?>">
                    </td>
                </tr>
                <?endif;?>
                <tr> 
                <td  align="right"><?=$LG_CUSTOMER_DATA['״̬'][$_COOKIE['LANG']]?></td>
                <td>
                    <div class="layui-input-inline" >
                        <select id="status" name="status">
                            <option value=''><?=$LG_CUSTOMER_DATA['��ѡ��'][$_COOKIE['LANG']]?></option>
                            <option value='T'><?=$LG_CUSTOMER_DATA['���ύ'][$_COOKIE['LANG']]?></option>
                            <option value='P'><?=$LG_CUSTOMER_DATA['�����?][$_COOKIE['LANG']]?></option>
                            <option value='R'><?=$LG_CUSTOMER_DATA['������'][$_COOKIE['LANG']]?></option>
                            <option value='Y'><?=$LG_CUSTOMER_DATA['�����?][$_COOKIE['LANG']]?></option>
                            <option value='N'><?=$LG_CUSTOMER_DATA['�˻�'][$_COOKIE['LANG']]?></option>
                        </select>
                    </div>
                </td>
                    
                <td  align="right"><?=$LG_CUSTOMER_DATA['�����������?][$_COOKIE['LANG']]?></td>
                <td>
                    <input  type="text" name="flow_date" id="flow_date" class="layui-input"  placeholder="<?=$LG_CUSTOMER_DATA['���ڶ�����'][$_COOKIE['LANG']]?>">
                </td>
                <td  align="right"><?=$LG_CUSTOMER_DATA['�Ƿ���Ч'][$_COOKIE['LANG']]?></td>
                <td>
                    <div class="layui-input-inline" >
                        <select id="is_vaild" name="is_vaild" >
                            <option value=''><?=$LG_CUSTOMER_DATA['��ѡ��'][$_COOKIE['LANG']]?></option>
                            <option value='01'><?=$LG_CUSTOMER_DATA['��'][$_COOKIE['LANG']]?></option>
                            <option value='02'><?=$LG_CUSTOMER_DATA['��'][$_COOKIE['LANG']]?></option>
                        </select>
                    </div>
                </td>
                <td  align="right"><?=$LG_CUSTOMER_DATA['�Ƿ�ɽ�����?][$_COOKIE['LANG']]?></td>
                <td>
                    <div class="layui-input-inline" >
                        <select id="own_orders" name="own_orders" >
                            <option value=''><?=$LG_CUSTOMER_DATA['��ѡ��'][$_COOKIE['LANG']]?></option>
                            <option value='Y'><?=$LG_CUSTOMER_DATA['��'][$_COOKIE['LANG']]?></option>
                            <option value='N'><?=$LG_CUSTOMER_DATA['��'][$_COOKIE['LANG']]?></option>
                        </select>
                    </div>
                </td>

                <td>
                    <button class="layui-btn layui-btn-oa layui-btn-sm" onclick="test();return false;" style="margin-left: 10px;"><?=$LG_CUSTOMER_DATA['��ѯ'][$_COOKIE['LANG']]?></button>
                    <!-- <button class="layui-btn layui-btn-oa layui-btn-sm" onclick="exportData();return false;">����</button> -->
                </td> 
            </tr>
        </table>
    </form>
    <div id="tableList" style="width:100%;text-align:center;"></div>
</body>

</html>