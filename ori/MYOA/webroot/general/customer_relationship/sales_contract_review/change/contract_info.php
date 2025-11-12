<table id="contract_tab" width="100%" style="text-align: center;">
    <tr>
        <td class=" TableContent" width="20%">合同总金额</td>
        <td class="TableData" colspan="3">
            <input type="text" name="total_amount" id="total_amount" class="layui-input" value="<?= number_format($contract['total_amount'], 4, '.', ''); ?>" />
        </td>
    </tr>
    <tr>
        <td class="TableContent" width="20%">表计类合同<b><font style="color: red;">商务</font></b>附件
</br>
<b><font style="color: red;">(只能上传商务内容附件)
<br>
        若有框架合同，必须附上框架合同
</font></td>
        <td class="TableData" width="30%" align="left" <?if(in_array($company,array('INHENERGY','HKNERGY'))):?>colspan="3"<?endif;?>>
            <input id="meter_contract" type="button" class="layui-btn layui-bg-gray btn-upload" style="display: inline!important;" value="上传" />
        </td>
        <?if(!in_array($company,array('INHENERGY','HKNERGY'))):?>
            <td class="TableContent" width="20%">软件类<b><font style="color: red;">商务</font></b>合同
            </br>
            <b><font style="color: red;">(只能上传商务内容附件)</font></b>
            </td>
            <td class="TableData" align="left">
                <input id="soft_contract" type="button" class="layui-btn layui-bg-gray btn-upload" style="display: inline!important;" value="上传" />
            </td>
        <?endif;?>
    </tr>
    <tr>
        <td class="TableContent" width="20%">表计类合同<b><font style="color: red;">技术</font></b>附件
        </br>
        <b><font style="color: red;">(只能上传技术内容附件)
        <br>
        必须上传技术规格
        </font></b></td>
        <td class="TableData" width="30%" align="left" <?if(in_array($company,array('INHENERGY','HKNERGY'))):?>colspan="3"<?endif;?>>
            <input id="tech_meter_contract" type="button" class="layui-btn layui-bg-gray btn-upload" style="display: inline!important;" value="上传" />
        </td>
        <?if(!in_array($company,array('INHENERGY','HKNERGY'))):?>
            <td class="TableContent" width="20%">软件类<b><font style="color: red;">技术</font></b>附件
            </br>
            <b><font style="color: red;">(只能上传技术内容附件)</font></td>
            <td class="TableData" align="left">
                <input id="tech_soft_contract" type="button" class="layui-btn layui-bg-gray btn-upload" style="display: inline!important;" value="上传" />
            </td>
        <?endif;?>
    </tr>
    <tr>
        <td class=" TableContent"  width="20%">制单人</td>
        <td class="TableData" width="30%">
            <input readonly type="text" class="layui-input" value="<?= $userInfo['USER_NAME'] ?>" />
            <input type="hidden" name="create_user" id="create_user" class="layui-input" value="<?= $userId ?>" />
        </td>
        <td class=" TableContent"  width="20%">部门</td>
        <td class="TableData" width="30%">
            <input readonly type="text" class="layui-input" value="<?= $userInfo['deptName'] ?>" />
            <input type="hidden" name="dept_id" id="dept_id" class="layui-input" value="<?= $userInfo['DEPT_ID'] ?>" />
        </td>
    </tr>
    <tr>
        <td class="TableContent">业务归属</td>
        <td class="TableData">
            <input type="hidden" data-type="business_dept_id" name="business_dept_id" id="business_dept_id" value="<?= $contract['business_dept_id'] ?>"/>
            <input type="hidden" data-type="business_user_id" name="business_user_id" id="business_user_id" value="<?= $contract['business_user_id'] ?>"/>
            <input type="text" name="business_attribution" id="business_attribution" class="layui-input" value="<?= $contract['business_attribution'] ?>" />
        </td>
        <td class=" TableContent">日期</td>
        <td class="TableData">
            <input type="text" name="form_date" id="form_date" class="layui-input date" value="<?= date('Y-m-d') ?>" />
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
            <input type="text" name="package_requirement" id="package_requirement" class="layui-input" value="<?= $contract['package_requirement'] ?>" placeholder="最多255字符" lay-verify="str_length" lay-max="255" lay-name="包装要求"/>
        </td>
        <td class=" TableContent">质量验收标准</td>
        <td class="TableData">
            <input type="text" name="acceptance_criteria" id="acceptance_criteria" class="layui-input" value="<?= $contract['acceptance_criteria'] ?>" placeholder="最多255字符" lay-verify="str_length" lay-max="255" lay-name="质量验收标准"/>
        </td>
    </tr>
    <?if(!in_array($company,array('INHENERGY','HKNERGY'))):?>
    <tr>
        <td class="TableContent">
            <b>
                <font style="color: red;">是否含【转口贸易】的货物？</font>
            </b>
        </td>
        <td class="TableData" align="left">
            <div class="layui-input-inline mandatory">
                <select id="have_entrepot_trade" name="have_entrepot_trade" lay-search>
                    <option value="">请选择</option>
                    <?php foreach ($selectArr['yes_or_no'] as $v) : ?>
                        <option value="<?= $v['paras_value'] ?>" <?php if ($v['paras_value'] == $contract['have_entrepot_trade']) : ?>selected<?php endif; ?>><?= $v['paras_desc'] ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
        </td>
        <td class=" TableContent">如含转口贸易，请在右侧注明涉及哪些产品？</td>
        <td class="TableData">
            <input type="text" name="involved_product" id="involved_product" class="layui-input" value="<?= $contract['involved_product'] ?>" />
        </td>
    </tr>
    <?endif;?>
    <tr>
        <td class="TableContent">合同报价来源</td>
        <td class="TableData" align="left">
            <div class="layui-input-inline">
                <select id="quote_source" name="quote_source" lay-search>
                    <option value="">请选择</option>
                    <?php foreach ($selectArr['quote_source'] as $v) : ?>
                        <option value="<?= $v['paras_value'] ?>" <?php if ($v['paras_value'] == $contract['quote_source']) : ?>selected<?php endif; ?>><?= $v['paras_desc'] ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
        </td>
        <td class=" TableContent">如参照历史订单报价，请填写对应项目代号</td>
        <td class="TableData">
            <input type="text" name="history_project_code" id="history_project_code" class="layui-input" value="<?= $contract['history_project_code'] ?>" />
        </td>
    </tr>
    <td class="TableContent">合同背景<br>
        <b><font style="color: red;">请注明要维护我方哪些利益，要规避哪些风险</font></b></td>
        <td class="TableData" colspan="5" align="left">
        <textarea class="layui-textarea " id="contract_background"  name="contract_background" lay-verify="required"><?= $contract['contract_background'] ?></textarea>
        </td>
    </tr>
    <tr>
        <td class="TableContent">项目资金来源</td>
        <td class="TableData" align="left">
            <div class="layui-input-inline">
                <select id="funds_come" name="funds_come" lay-search lay-verify="required">
                    <option value="">请选择</option>
                    <?php foreach ($selectArr['funds_come'] as $v) : ?>
                        <option value="<?= $v['paras_value'] ?>" <?php if ($v['paras_value'] == $contract['funds_come']) : ?>selected<?php endif; ?>><?= $v['paras_desc'] ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
        </td>
        <td class=" TableContent">若是融资行资金，需填写融资行名称</td>
        <td class="TableData">
            <input type="text" name="financing_bank" id="financing_bank" class="layui-input" value="<?= $contract['financing_bank'] ?>" />
        </td>
    </tr>
    <tr>
        <td class="TableContent"><b><font style="color: red;">是否符合：公司格式合同 + 全款提货 + 有库存 + 高于最底价 + 运保费足够？</font></b></td>
        <td class="TableData" align="left"  >
            <div class="layui-input-inline mandatory">
                <select id="if_eligible" name="if_eligible" lay-filter="if_eligible" lay-search>
                    <option value="">请选择</option>
                    <option value="是" <?php if ("是" == $contract['if_eligible']) : ?>selected<?php endif; ?>>是</option>
                    <option value="否" <?php if ("否"  == $contract['if_eligible']) : ?>selected<?php endif; ?>>否</option>
                </select>
            </div>
        </td>
        <td class="TableContent">项目类型</td>
        <td class="TableData" align="left">
            <div class="layui-input-inline">
                <select id="product_project_type" name="product_project_type" lay-search>
                    <option value="">请选择</option>
                    <?php foreach ($selectArr['product_project_type'] as $v) : ?>
                        <option value="<?= $v['paras_value'] ?>" <?php if ($v['paras_value'] == $contract['product_project_type']) : ?>selected<?php endif; ?>><?= $v['paras_desc'] ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
        </td>
    </tr>
</table>
<table id="settle_table" width="100%">
    <tr>
        <td class="TableContent" rowspan="<?= count($settleModel); ?>" width="20%">结算方式及期限</td>
        <input type="hidden" name="model_count" value="<?= count($settleModel); ?>" />
        <?php foreach ($settleModel as $mId => $mVal) : ?>
            <td class="TableData" align="left" width="20%">
                <b>
                    <font style="color: red;"><?= $mVal['item'] ?></font>
                </b>
            </td>
            <?php if ($mVal['have_attach']) : ?>
                <td class="TableData" align="left">
                    <?php if ($mVal['if_select'] != '') : ?>
                        <div class="layui-input-inline ">
                            <select name="model_<?= $mId ?>" lay-search>
                                <option value="">请选择</option>
                                <?php foreach ($selectArr[$mVal['if_select']] as $v) : ?>
                                    <option value="<?= $v['paras_value'] ?>" <?php if ($v['paras_value'] == $settle[$mId]['content']) : ?>selected<?php endif; ?>><?= $v['paras_desc'] ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    <?php else : ?>
                        <input type="text" name="model_<?= $mId ?>" class="layui-input" value="<?= $settle[$mId]['content'] ?>" />
                    <?php endif; ?>
                </td>
                <td class="TableData">
                    <b>
                        <font style="color: red;">附件：</font>
                    </b>
                </td>
                <td class="TableData" align="left">
                    <input id="is_third_attach" type="button" class="layui-btn layui-bg-gray btn-upload" style="display: inline!important;" value="上传" />
                </td>
            <?php else : ?>
                <td class="TableData" align="left" colspan="3">
                    <?php if ($mVal['if_select'] != '') : ?>
                        <div class="layui-input-inline ">
                            <select name="model_<?= $mId ?>" lay-search>
                                <option value="">请选择</option>
                                <?php foreach ($selectArr[$mVal['if_select']] as $v) : ?>
                                    <option value="<?= $v['paras_value'] ?>" <?php if ($v['paras_value'] == $settle[$mId]['content']) : ?>selected<?php endif; ?>><?= $v['paras_desc'] ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <!-- <?= ${$mVal['if_select']}[$settle[$mId]['content']]['paras_desc'] ?> -->
                    <?php else : ?>
                        <input type="text" name="model_<?= $mId ?>" class="layui-input" value="<?= $settle[$mId]['content'] ?>" />
                    <?php endif; ?>
                </td>
            <?php endif; ?>
    </tr>
    <tr>
    <?php endforeach; ?>
    <td class="TableContent" rowspan="2">技术规格</td>
    <td class="TableContent"><b>
            <font style="color: red;">合同是否有技术规格：</font>
        </b></td>
    <td class="TableData" align="left" colspan="3">
        <div class="layui-input-inline">
            <select id="have_tech_specification" name="have_tech_specification" lay-search>
                <option value="">请选择</option>
                <?php foreach ($selectArr['yes_or_no'] as $v) : ?>
                    <option value="<?= $v['paras_value'] ?>" <?php if ($v['paras_value'] == $contract['have_tech_specification']) : ?>selected<?php endif; ?>><?= $v['paras_desc'] ?></option>
                <?php endforeach; ?>
            </select>
        </div>
    </td>
    </tr>
    <tr>
        <td class="TableContent"><b>
                <font style="color: red;">是否已通过客服部OA邮件审核：</font>
            </b></td>
        <td class="TableData" align="left">
            <div class="layui-input-inline">
                <select id="have_email_approval" name="have_email_approval" lay-search>
                    <option value="">请选择</option>
                    <?php foreach ($selectArr['yes_or_no'] as $v) : ?>
                        <option value="<?= $v['paras_value'] ?>" <?php if ($v['paras_value'] == $contract['have_email_approval']) : ?>selected<?php endif; ?>><?= $v['paras_desc'] ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
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
            <td class="TableContent">产品型号</td>
            <td class="TableData" colspan="2">
                <input type="text" name="product_model" id="product_model" class="layui-input" value="<?= $contract['product_model'] ?>" />
            </td>
        <?endif;?>
        <td class=" TableContent">是否有库存</td>
        <td class="TableData" align="left" <?if(in_array($company,array('INHENERGY','HKNERGY'))):?>colspan="4"<?endif;?>>
            <div class="layui-input-inline">
                <select id="have_inventory" name="have_inventory" lay-search>
                    <option value="">请选择</option>
                    <?php foreach ($selectArr['yes_or_no'] as $v) : ?>
                        <option value="<?= $v['paras_value'] ?>" <?php if ($v['paras_value'] == $contract['have_inventory']) : ?>selected<?php endif; ?>><?= $v['paras_desc'] ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
        </td>
    </tr>
    <?if(in_array($company,array('INHE','INHEIAC'))):?>
    <tr>
    <td class=" TableContent">交付经理/交付项目负责人</td>
            <td class="TableData" align="left" colspan="5">
            <input type="hidden" name="customer_service" id="customer_service" value="<?=$contract['customer_service']?>" >
            <input style="width:288px;float:left" placeholder="请点击选择交付经理/交付项目负责人" onClick="SelectUserSingle('','customer_service', 'customer_service_name')" class="layui-input mandatory required_field" id="customer_service_name" name="customer_service_name" value="<?=trim(GetUserNameById($contract['customer_service']),",")?>"  lay-verify="required"></input>
        </td>      
    </tr>
    <?endif;?>
    <?if(!in_array($company,array('INHENERGY','HKNERGY'))):?>
    <tr>
        <td class="TableContent">是否为集抄项目</td>
        <td class="TableData" colspan="5" align="left">
        <div class="layui-input-inline mandatory ">
                <select id="is_collective" name="is_collective" lay-search lay-verify="required">
                    <option value=''>请选择</option>
                    <option value="是" <?php if ($contract['is_collective'] == '是') : ?>selected<?php endif; ?>>是</option>
                    <option value="否" <?php if ($contract['is_collective'] == '否') : ?>selected<?php endif; ?>>否</option>
                </select>
            </div>
        </td>
    </tr>
    <?endif;?>
    <tr>
        <td class="TableContent">是否紧急</td>
        <td class="TableData" colspan="5" align="left">
        <div class="layui-input-inline  ">
                <select id="urgent" name="urgent" lay-search >
                    <option value=''>请选择</option>
                    <option value="是" <?php if ($main['urgent'] == '是') : ?>selected<?php endif; ?>>是</option>
                    <option value="否" <?php if ($main['urgent'] == '否') : ?>selected<?php endif; ?>>否</option>
                </select>
            </div>
        </td>
    </tr>
    <tr>
        <td class="TableContent">本市场过往项目代号</td>
        <td class="TableData" colspan="2" align="left">
        <textarea class="layui-textarea required_field" id="past_pcode"  name="past_pcode" placeholder="此框必填，如无也要填无" lay-verify="required"><?= $contract['past_pcode'] ?></textarea>
        </td>
        <td class="TableContent">本市场不同客户曾经出现过的项目代号</td>
        <td class="TableData" colspan="1" align="left">
        <textarea class="layui-textarea required_field" id="c_past_pcode" name="c_past_pcode" placeholder="此框必填，如无也要填无" lay-verify="required"><?= $contract['c_past_pcode'] ?></textarea>
        </td>
    </tr>
    <tr>
        <td class="TableContent" rowspan="2">产品功能要求（已有产品无法满足要求填写）</td>
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
        <td class="TableContent" rowspan="2">特殊要求说明</td>
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
        <td class="TableContent" rowspan="2">合同变更说明<br />
            （只有在合同有变更的时候才需要填写）</td>
        <td class="TableData" colspan="5">
            <textarea class="layui-textarea" id="change_explain" name="change_explain"><?= $contract['change_explain'] ?></textarea>
        </td>
    </tr>
    <tr>
        <td class="TableData" colspan="5">&nbsp;</td>
    </tr>
</table>