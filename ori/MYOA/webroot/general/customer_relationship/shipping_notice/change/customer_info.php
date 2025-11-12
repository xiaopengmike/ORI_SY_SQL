<table id="tab1" width="100%" style="text-align: center;">
    <tr>
        <td class="TableContent" width="14%">客户名称</td>
        <td class="TableData" width="19%">
            <input type="text" name="customer_name" id="customer_name" class="layui-input" value="<?= $main['customer_name'] ?>" />
        </td>
        
        <td class="TableContent" width="14%">订单编号</td>
        <td class="TableData">
            <input type="text" name="order_no" id="order_no" class="layui-input" value="<?= $main['order_no'] ?>" />
            <input type="hidden" name="order_form_id" id="order_form_id" class="layui-input" value="<?= $main['order_form_id'] ?>" />
        </td>
        <td class=" TableContent" width="14%">合同相对方</td>
        <td class="TableData" width="19%">
            <input type="text" name="contract_party" id="contract_party" class="layui-input" value="<?= $main['contract_party'] ?>" />
        </td>
    </tr>
    <tr>
        <td class="TableContent">业务归属部门</td>
        <td class="TableData">
            <input type="text" name="business_dept" id="business_dept" class="layui-input" value="<?= $main['business_dept'] ?>" />
        </td>
        <td class=" TableContent">业务归属</td>
        <td class="TableData">
            <input type="text" name="business_belong" id="business_belong" class="layui-input" value="<?= $main['business_belong'] ?>" />
        </td>
        <td class=" TableContent">制单人</td>
        <td class="TableData">
            <input type="text" name="create_user" id="create_user" class="layui-input" value="<?= $main['createUserName'] == '' ? $main['create_user'] : $main['createUserName'] ?>" readonly />
        </td>
    </tr>
    <tr>
        <td class="TableContent">运输方式</td>
        <td class="TableData">
            <input type="text" name="transport_mode" id="transport_mode" class="layui-input" value="<?= $main['transport_mode'] ?>" />
        </td>
        <td class=" TableContent">运费承担</td>
        <td class="TableData" <?if(in_array($company,array('INHENERGY','HKNERGY','RETAILNERGY'))):?>colspan="3"<?endif;?>>
            <input type="text" name="freight_bearing" id="freight_bearing" class="layui-input" value="<?= $main['freight_bearing'] ?>" />
        </td>
        <?if(!in_array($company,array('INHENERGY','HKNERGY','RETAILNERGY'))):?>
        <td class=" TableContent" style="width: 14%;">币种</td>
        <td class="TableData" align="left">
            <div class="layui-input-inline mandatory">
                <select id="currency" name="currency" lay-search>
                    <option value="">请选择</option>
                    <?php foreach ($selectArr['currency'] as $v) : ?>
                        <option value="<?= $v['paras_value'] ?>" <?php if ($v['paras_value'] == $main['currency']) { ?>selected<?php } ?>><?= $v['paras_desc'] ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
        </td>
        <?endif;?>
    </tr>
    <tr>
       
        <td class=" TableContent">发往地</td>
        <td class="TableData" >
            <input type="text" name="shipment_place" id="shipment_place" class="layui-input" value="<?= $main['shipment_place'] ?>" />
        </td>
        <td class=" TableContent">客户地址</td>
        <td class="TableData" <?if(in_array($company,array('INHENERGY','HKNERGY','RETAILNERGY'))):?>colspan="3"<?endif;?>>
            <input type="text" name="address" id="address" class="layui-input" value="<?= $main['address'] ?>" />
        </td>
        <?if(!in_array($company,array('INHENERGY','HKNERGY','RETAILNERGY'))):?>
        <td class=" TableContent" style="width: 14%;">ERP号</td>
        <td class="TableData">
            <input type="text" name="erp_no" id="erp_no" class="layui-input" value="<?= $main['erp_no'] ?>" />
        </td>
        <?endif;?>
    </tr>
    <tr>
        <td class="TableContent">收货人</td>
        <td class="TableData">
            <input type="text" name="consignee" id="consignee" class="layui-input" value="<?= $main['consignee'] ?>" />
        </td>
        <td class=" TableContent">电话</td>
        <td class="TableData">
            <input type="text" name="phone" id="phone" class="layui-input" value="<?= $main['phone'] ?>" />
        </td>
        <td class=" TableContent">发货时间</td>
        <td class="TableData">
            <input type="text" name="shipment_time" id="shipment_time" class="layui-input date" value="<?= $main['shipment_time']=="0100-01-01"?"":$main['shipment_time'] ?>" />
        </td>
    </tr>
   
    
    <?if(!in_array($company,array('INHENERGY','HKNERGY','RETAILNERGY'))):?>
    <tr>
        <td class=" TableContent">是否含【转口贸易】的货物？</td>
        <td class="TableData" align="left">
            <div class="layui-input-inline mandatory">
                <select id="have_entrepot_trade" name="have_entrepot_trade" lay-search>
                    <option value="">请选择</option>
                    <?php foreach ($selectArr['yes_or_no'] as $v) : ?>
                        <option value="<?= $v['paras_value'] ?>" <?php if ($v['paras_value'] == $main['have_entrepot_trade']) { ?>selected<?php } ?>><?= $v['paras_desc'] ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
        </td>
        <td class=" TableContent">如含转口贸易，请在右侧注明涉及哪些产品？ </td>
        <td class="TableData">
            <input type="text" name="involved_product" id="involved_product" class="layui-input" value="<?= $main['involved_product'] ?>" />
        </td>
        <td class=" TableContent">时间</td>
        <td class="TableData">
            <input type="text" name="create_time" id="create_time" class="layui-input" value="<?= date('Y-m-d H:i:s'); ?>" readonly />
        </td>
    </tr>
    <tr>
    <td class=" TableContent">产品经理(客服)</td>
            <td class="TableData" align="left" >
            <input type="hidden" name="customer_service" id="customer_service" value="<?=$main['customer_service']?>">
            <input style="width:288px;float:left" placeholder="请点击选择产品经理，流程结束备案到该人员" onClick="SelectUserSingle('','customer_service', 'customer_service_name')" class="layui-input mandatory" id="customer_service_name" name="customer_service_name" value="<?= $main['customer_service_name']?>" readonly ></input>
        </td>     
        <td class=" TableContent">客户是否已申请好进口国进口许可？</td>
        <td class="TableData" align="left" colspan="">
            <div class="layui-input-inline mandatory">
                <select id="import_permit" name="import_permit" lay-search>
                    <option value="">请选择</option>
                    <?php foreach ($selectArr['yes_or_no'] as $v) : ?>
                        <option value="<?= $v['paras_value'] ?>" <?php if ($v['paras_value'] == $main['import_permit']) { ?>selected<?php } ?>><?= $v['paras_desc'] ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
        </td> 
        <td class=" TableContent">项目编号批次</td>
        <td class="TableData">
            <input type="text" name="batch_number" id="batch_number" class="layui-input" value="<?=$main['batch_number']?>" />
        </td>
    </tr>
    <?endif;?>
    <?php if ($main['user_bu'] == 'INHEGRID') { ?>
        <tr>
            <td class=" TableContent" width="14%">通知单类型</td>
            <td class="TableData" align="left" colspan="5">
                <div class="layui-input-inline mandatory">
                    <select id="notice_type" name="notice_type" lay-search>
                        <option value="">请选择</option>
                        <?php foreach ($selectArr['inhegrid_notice_type'] as $v) : ?>
                            <option value="<?= $v['paras_value'] ?>" <?php if ($v['paras_value'] == $main['notice_type']) { ?>selected<?php } ?>><?= $v['paras_desc'] ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </td>
        </tr>
    <?php } ?>
</table>