<table id="contract_tab" width="100%" style="text-align: center;">
    <tr>
        <td class=" TableContent" width="20%">合同总金额</td>
        <td class="TableData" colspan="3">
            <!-- <input type="text" name="total_amount" id="total_amount" class="layui-input" value="<?= $isHide?'':number_format($contract['total_amount'], 4, '.', ''); ?>" /> -->
            <input type="text" name="total_amount" id="total_amount" class="layui-input" value="<?= $isHide?'':number_format($contract['total_amount'], 4, '.', ''); ?>" />
        </td>
    </tr>
    <?if(!$isHide):?>
    <tr>
        <td class="TableContent" width="20%">表计类商务附件
        </br>
<b><font style="color: red;">(只能上传商务内容附件)
<br>
        若有框架合同，必须附上框架合同 </font></b>
        
        </td>
        <td class="TableData" align="left" width="30%" <?if(in_array($company,array('INHENERGY','HKNERGY'))):?>colspan="3"<?endif?>>
            <input id="meter_contract" type="button" class="layui-btn layui-bg-gray btn-upload" style="display: none;" value="上传" readonly />
        </td>
        <?if(!in_array($company,array('INHENERGY','HKNERGY'))):?>
        <td class="TableContent" width="20%">软件类商务合同</td>
        <td class="TableData" align="left">
            <input id="soft_contract" type="button" class="layui-btn layui-bg-gray btn-upload" style="display: none;" value="上传" readonly />
        </td>
        <?endif;?>
    </tr>
    <?endif;?>
    <tr>
        <td class="TableContent" width="20%">表计类技术附件
        </br>
        <b><font style="color: red;">(只能上传技术内容附件)
        <br>
        必须上传技术规格
        </font></b>
        </td>
        <td class="TableData" align="left" width="30%" <?if(in_array($company,array('INHENERGY','HKNERGY'))):?>colspan="3"<?endif?>>
            <input id="tech_meter_contract" type="button" class="layui-btn layui-bg-gray btn-upload" style="display: none;" value="上传" readonly />
        </td>
        <?if(!in_array($company,array('INHENERGY','HKNERGY'))):?>
            <td class="TableContent" width="20%">软件类技术附件</td>
            <td class="TableData" align="left">
                <input id="tech_soft_contract" type="button" class="layui-btn layui-bg-gray btn-upload" style="display: none;" value="上传" readonly />
            </td>
        <?endif;?>
    </tr>
    <tr>
        <td class=" TableContent" width="20%">制单人</td>
        <td class="TableData" width="30%">
            <input type="text" class="layui-input" value="<?= $contract['businessName'] ?>" />
            <input type="hidden" name="create_user" id="create_user" class="layui-input" value="<?= $contract['create_user'] ?>" />
        </td>
        <td class=" TableContent" width="20%">部门</td>
        <td class="TableData" width="30%">
            <input type="text" class="layui-input" value="<?= $contract['businessDept'] ?>" />
            <input type="hidden" name="dept_id" id="dept_id" class="layui-input" value="<?= $contract['dept_id'] ?>" />
        </td>
    </tr>
    <tr>
        <td class="TableContent">业务归属</td>
        <td class="TableData">
            <input type="text" name="business_attribution" id="business_attribution" class="layui-input" value="<?= $contract['business_attribution'] ?>" />
        </td>
        <td class=" TableContent">日期</td>
        <td class="TableData">
            <input type="text" name="form_date" id="form_date" class="layui-input date" value="<?= $contract['form_date'] ?>" />
        </td>
    </tr>
    <tr>
        <td class="TableContent">运输方式</td>
        <td class="TableData">
            <input type="text" name="transport_mode" id="transport_mode" class="layui-input" value="<?= $contract['transport_mode'] ?>" />
        </td>
        <td class=" TableContent">运费承担</td>
        <td class="TableData">
            <input type="text" name="freight_bearing" id="freight_bearing" class="layui-input" value="<?= $contract['freight_bearing'] ?>" />
        </td>
    </tr>
    <tr>
        <td class="TableContent">交货地点</td>
        <td class="TableData">
            <input type="text" name="delivery_place" id="delivery_place" class="layui-input" value="<?= $contract['delivery_place'] ?>" />
        </td>
        <td class=" TableContent">出货地址</td>
        <td class="TableData">
            <input type="text" name="shipping_address" id="shipping_address" class="layui-input" value="<?= $contract['shipping_address'] ?>" />
        </td>
    </tr>
    <tr>
        <td class="TableContent">包装要求</td>
        <td class="TableData">
            <input type="text" name="package_requirement" id="package_requirement" class="layui-input" value="<?= $contract['package_requirement'] ?>" />
        </td>
        <td class=" TableContent">质量验收标准</td>
        <td class="TableData">
            <input type="text" name="acceptance_criteria" id="acceptance_criteria" class="layui-input" value="<?= $contract['acceptance_criteria'] ?>" />
        </td>
    </tr>
    <?if(!in_array($company,array('INHENERGY','HKNERGY'))):?>
    <tr>
        <td class="TableContent"><b>
                <font style="color: red;">是否含【转口贸易】的货物？</font>
            </b></td>
        <td class="TableData" align="left">
            <input type="text" class="layui-input" value="<?= $yes_or_no[$contract['have_entrepot_trade']]['paras_desc'] ?>" />
            <input type="hidden" name="have_entrepot_trade" id="have_entrepot_trade" class="layui-input" value="<?= $contract['have_entrepot_trade'] ?>" />
        </td>
        <td class=" TableContent">如含转口贸易，请在右侧注明涉及哪些产品？</td>
        <td class="TableData">
            <input type="text" name="involved_product" id="involved_product" class="layui-input" value="<?= $contract['involved_product'] ?>" />
        </td>
    </tr>
    <?endif;?>
    <tr>
        <td class="TableContent">合同报价来源</td>
        <td class="TableData">
            <input type="text" class="layui-input" value="<?= $quoteSource[$contract['quote_source']]['paras_desc'] ?>" />
            <input type="hidden" name="quote_source" id="quote_source" class="layui-input" value="<?= $contract['quote_source'] ?>" />
        </td>
        <td class=" TableContent">如参照历史订单报价，请填写对应项目代号</td>
        <td class="TableData">
            <input type="text" name="history_project_code" id="history_project_code" class="layui-input" value="<?= $contract['history_project_code'] ?>" />
        </td>
    </tr>
    <tr>
    <td class="TableContent">合同背景<br>
        <b><font style="color: red;">请注明要维护我方哪些利益，要规避哪些风险</font></b></td>
        <td class="TableData" colspan="5" align="left">
        <textarea class="layui-textarea " id="contract_background"  name="contract_background" ><?= $contract['contract_background'] ?></textarea>
        </td>
    </tr>
    <tr>
        <td class="TableContent">项目资金来源
    </td>
        <td class="TableData">
            <input type="text" class="layui-input" value="<?= $funds_come[$contract['funds_come']]['paras_desc'] ?>" />
            <input type="hidden" name="funds_come" id="funds_come" class="layui-input" value="<?= $contract['funds_come'] ?>" />
        </td>
        <td class=" TableContent">若是融资行资金，需填写融资行名称</td>
        <td class="TableData">
            <input type="text" name="financing_bank" id="financing_bank" class="layui-input" value="<?= $contract['financing_bank'] ?>" />
        </td>
    </tr>
    <tr>
        <td class="TableContent"><b><font style="color: red;">是否符合：公司格式合同 + 全款提货 + 有库存 + 高于最底价 + 运保费足够？</font></b></td>
        <td class="TableData"  >
            <input type="text" class="layui-input" value="<?= $contract['if_eligible'] ?>" />
            <input type="hidden" name="if_eligible"  class="layui-input" value="<?= $contract['if_eligible'] ?>" />
        </td>
        <td class="TableContent">项目类型</td>
        <td class="TableData">
            <input type="text" class="layui-input" value="<?= $product_project_type[$contract['product_project_type']]['paras_desc'] ?>" />
            <input type="hidden" name="product_project_type" id="product_project_type" class="layui-input" value="<?= $contract['product_project_type'] ?>" />
        </td>
    </tr>
</table>
<table id="settle_table" width="100%">
    <tr>
        <td class="TableContent" rowspan="<?= count($settleModel); ?>" width="20%" align="center">结算方式及期限</td>
        <?php foreach ($settleModel as $mId => $mVal) : ?>
            <td class="TableData" align="left">
                <b>
                    <font style="color: red;"><?= $mVal['item'] ?></font>
                </b>
            </td>
            <?php if ($mVal['have_attach']) : ?>
                <td class="TableData">
                    <?php if ($mVal['if_select'] != '') : ?>
                        <?= ${$mVal['if_select']}[$settle[$mId]['content']]['paras_desc'] ?>
                    <?php else : ?>
                        <?= $settle[$mId]['content'] ?>
                    <?php endif; ?>
                </td>
                <td class="TableContent">
                    <b>
                        <font style="color: red;" width="20%">附件：</font>
                    </b>
                </td>
                <td class="TableData">
                    <input id="is_third_attach" type="button" class="layui-btn layui-bg-gray btn-upload" style="display: none;" value="上传" readonly />
                </td>
            <?php else : ?>
                <td class="TableData" colspan="3">
                    <?php if ($mVal['if_select'] != '') : ?>
                        <?= ${$mVal['if_select']}[$settle[$mId]['content']]['paras_desc'] ?>
                    <?php else : ?>
                        <?= $settle[$mId]['content'] ?>
                    <?php endif; ?>
                </td>
            <?php endif; ?>
    </tr>
    <tr>
    <?php endforeach; ?>
    <td class="TableContent" rowspan="2" align="center">技术规格</td>
    <td class="TableContent" width="20%"><b>
            <font style="color: red;">合同是否有技术规格：</font>
        </b></td>
    <td class="TableData" colspan="3">
        <input type="text" class="layui-input" value="<?= $yes_or_no[$contract['have_tech_specification']]['paras_desc'] ?>" />
        <input type="hidden" name="have_tech_specification" id="have_tech_specification" class="layui-input" value="<?= $contract['have_tech_specification'] ?>" />
    </td>
    </tr>
    <tr>
        <td class="TableContent"><b>
                <font style="color: red;">是否已通过客服部OA邮件审核：</font>
            </b></td>
        <td class="TableData">
            <input type="text" class="layui-input" value="<?= $yes_or_no[$contract['have_email_approval']]['paras_desc'] ?>" />
            <input type="hidden" name="have_email_approval" id="have_email_approval" class="layui-input" value="<?= $contract['have_email_approval'] ?>" />
        </td>
        <td class="TableContent"><b>
                <font style="color: red;">OA邮件名称：</font>
            </b></td>
        <td class="TableData">
            <input type="text" name="email_name" id="email_name" class="layui-input" value="<?= $contract['email_name'] ?>" />
        </td>
    </tr>
    <tr>
        <?if(!in_array($company,array('INHENERGY','HKNERGY'))):?>
            <td class="TableContent" align="center">产品型号</td>
            <td class="TableData" colspan="2">
                <input type="text" name="product_model" id="product_model" class="layui-input" value="<?= $contract['product_model'] ?>" />
            </td>
        <?endif;?>
        <td class=" TableContent">是否有库存</td>
        <td class="TableData" <?if(in_array($company,array('INHENERGY','HKNERGY'))):?>colspan="4"<?endif;?>>
            <input type="text" class="layui-input" value="<?= $yes_or_no[$contract['have_inventory']]['paras_desc'] ?>" />
            <input type="hidden" name="have_inventory" id="have_inventory" class="layui-input" value="<?= $contract['have_inventory'] ?>" />
        </td>
    </tr>
    <?if(in_array($company,array('INHE','INHEIAC'))):?>
    <tr>
    <td class=" TableContent">交付经理/交付项目负责人</td>
            <td class="TableData" align="left" colspan="5">
            <input type="hidden" class="<?if(in_array('customer_service',$editable_fields_arr[$approveInfo[work_flow]][editable_fields])&&$operate=='approve'):?>not_disabled<?endif;?>" name="customer_service" id="customer_service" value="<?=$contract['customer_service']?>" >
            <input style="width:288px;float:left"  placeholder="请点击选择交付经理/交付项目负责人" onClick="SelectUserSingle('','customer_service', 'customer_service_name')" class="layui-input  <?if(in_array('customer_service',$editable_fields_arr[$approveInfo[work_flow]][editable_fields])&&$operate=='approve'):?>not_disabled required_field<?endif;?>" id="customer_service_name" name="customer_service_name" value="<?=trim(GetUserNameById($contract['customer_service']),",")?>" readonly></input>
        </td>      
    </tr>
    <?endif;?>
    <?if(!in_array($company,array('INHENERGY','HKNERGY'))):?>
    <tr>
        <td class="TableContent">是否为集抄项目</td>
        <td class="TableData" colspan="5" align="left">
        <input type="text" class="layui-input" value="<?= $contract['is_collective'] ?>" />
        <input type="hidden" name="is_collective" id="is_collective" class="layui-input" value="<?= $contract['is_collective'] ?>" />
        </td>
    </tr>
    <?endif;?>
    <tr>
        <td class="TableContent">是否紧急</td>
        <td class="TableData" colspan="5" align="left">
        <input type="text" class="layui-input" value="<?= $main['urgent'] ?>" />
        <input type="hidden" name="urgent" id="urgent" class="layui-input" value="<?= $main['urgent'] ?>" />
        </td>
    </tr>
    <tr>
        <td class="TableContent">本市场过往项目代号</td>
        <td class="TableData" colspan="2" align="left">
        <textarea class="layui-textarea " id="past_pcode"  name="past_pcode" ><?= $contract['past_pcode'] ?></textarea>
        </td>
        <td class="TableContent">本市场不同客户曾经出现过的项目代号</td>
        <td class="TableData" colspan="1" align="left">
        <textarea class="layui-textarea " id="c_past_pcode" name="c_past_pcode" ><?= $contract['c_past_pcode'] ?></textarea>
        </td>
    </tr>
    <tr>
        <td class="TableContent" rowspan="2" align="center">产品功能要求（已有产品无法满足要求填写）</td>
        <td class="TableData" colspan="5" align="left">
            <font style="color: red;">当订单类型为“新研发产品/变更产品”，此栏目“必填”。“翻单”填无。</font>
        </td>
    </tr>
    <tr>
        <td class="TableData" colspan="5">
            <textarea class="layui-textarea" id="functional_requirement" name="functional_requirement"><?= $contract['functional_requirement'] ?></textarea>
        </td>
    </tr>
    <tr>
        <td class="TableContent" rowspan="2" align="center">特殊要求说明</td>
        <td class="TableData" colspan="5" align="left">
            <font style="color: red;">客户的特殊要求，请在此栏目填写清楚。</font>
        </td>
    </tr>
    <tr>
        <td class="TableData" colspan="5">
            <textarea class="layui-textarea" id="special_requirement" name="special_requirement"><?= $contract['special_requirement'] ?></textarea>
        </td>
    </tr>
    <tr>
        <td class="TableContent" rowspan="2">备品要求说明</td>
        <td class="TableData" colspan="5" align="left">
            <font style="color:  red;">备品要求（若有），请在此栏目填写清楚。</font>
        </td>
    </tr>
    <tr>
        <td class="TableData" colspan="5">
            <textarea class="layui-textarea" id="spare_requirement" name="spare_requirement"><?= $contract['spare_requirement'] ?></textarea>
        </td>
    </tr>
    <tr>
        <td class="TableContent" rowspan="2" align="center">合同变更说明<br />
            （只有在合同有变更的时候才需要填写）</td>
        <td class="TableData" colspan="5">
            <textarea class="layui-textarea" id="change_explain" name="change_explain"><?= $contract['change_explain'] ?></textarea>
        </td>
    </tr>
    <tr>
        <td class="TableData" colspan="5"><?= $contract['businessName'] . ' ' . $main['write_time'] ?></td>
    </tr>

</table>