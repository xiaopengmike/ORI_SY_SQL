<?if(!in_array($company,array('INHENERGY','HKNERGY'))):?>
<table id="tab2" width="100%" style="text-align: left;">
    <input type="hidden" id="addMeterCount" name="addMeterCount" value="0" />
    <tr>
        <td class="TableHeader" colspan="<?= count($detailItem['meter_one'])+2 ?>" style="text-align:center"><?=$selectArr['product_detail_type'][0][paras_desc]?>&nbsp;&nbsp;&nbsp; <input type='hidden' name='sub_user_bu' value="INHE"/></td>
    </tr>
    <?php foreach (ActionModel::$METER_TYPE as $meterType) : ?>
        <tr class="TableContent" style="text-align: center;">
            <?php foreach ($detailItem[$meterType] as $val) : ?>
                <?php if($product_company=='INHE'):?>
                    <td colspan="<?= $val['colspan'] ?>" <?= $val['class'] ?>><?= $val['item_name'] ?></td>
                <?php else:?>
                    <?php if(in_array($val['table_id'],array('meter_three'))&&$val['item_id']=='other_company'){continue;}?>
                    <td colspan="<?= in_array($val['table_id'],array('meter_three'))&&$val['item_id']=='product_brand'?$val['colspan']+1:$val['colspan'] ?>" <?= $val['class'] ?>><?= $val['item_name'] ?></td>
                <?php endif;?>
                
            <?php endforeach; ?>
        </tr>
        <tbody id="<?= $meterType ?>"></tbody>
    <?php endforeach; ?>
    <tr align="left" id="addMeter">
        <td class="TableData" colspan="17">
            <input value="添加" type=button class="layui-btn layui-btn-oa layui-btn-sm" onclick="addLine('addMeter','<?=$product_company=='INHE'?$product_company:''?>');return false;" />
        </td>
    </tr>
</table>
<?endif;?>
<!-- 多类型产品-->
<input type="hidden" id="product_detail_types" value='<?=json_encode($product_detail_types)?>'/>
<? $line_num=1; foreach($selectArr['product_detail_type'] as $product_type):?>
    <?if(($company=='GRIDINHE'||$company=='IMCINHE')&&(!in_array($product_type['paras_value'],array('Other')))){continue;}?>
    <?if(($company=='INHENERGY'||$company=='HKNERGY')&&(!in_array($product_type['paras_value'],array('Inhenergy','Other')))){continue;}?>
    <?php $detailItemType=in_array($product_type['paras_value'],array('Soft'))?"non_".$product_type['paras_value']:$detailItemType="non_meter"?>
    <?php if(in_array($product_type['paras_value'],array('Electric'))){continue;} ++$line_num; ?>
<table id="tab3" width="100%" style="text-align: left;">
    <input type="hidden" id="add<?=$product_type[paras_value]?>Count" name="add<?=$product_type[paras_value]?>Count" value="0" />
    <tr>
        <td class="TableHeader" colspan="<?= count($detailItem[$detailItemType]) ?>" style="text-align:center"><?if($product_type[paras_value]=='Other'):?><?=$company_names[$company]?><?endif;?><?=$product_type[paras_desc]?> <?if($product_type[user_bu]&&strtoupper($product_type[user_bu])!=$company):?>&nbsp;&nbsp;&nbsp;<input  type='hidden'  value="1" name="is_<?=$product_type[paras_value]?>" title="是否需要走分公司流程"/><?endif;?></td>
    </tr>
    <?if($product_type['paras_value']=='Soft'):?>
        <tr >
        <td class="TableContent">合同有效期</td>
        <td class="TableContent">开始时间</td>
        <td class="TableData" colspan="2"> <input type="text" name="contract_begin_time" id="contract_begin_time" class="layui-input" value="" readonly></td>
        <td class="TableContent">结束时间</td>
        <td class="TableData" colspan="5"> <input type="text" name="contract_end_time" id="contract_end_time" class="layui-input " value="" readonly></td>
        </tr>
    <?endif;?>
    <tr class="TableContent" style="text-align: center;">
        <?php foreach ($detailItem[$detailItemType] as $val) : ?>
            <?php if($product_type[paras_value]!='Other'&&$product_company!=$product_type['user_bu']&&$val['item_id']=='other_company'){continue;}?>
            <td <?= $val['class'] ?>><?= $val['item_name'] ?></td>
        <?php endforeach; ?>
    </tr>
    <tbody id="non_meter_<?=$product_type[paras_value]?>"></tbody>
    <tr align="left" id="add<?=$product_type[paras_value]?>">
        <td class="TableData" colspan="<?= count($detailItem[$detailItemType]) ?>">
            <input value="添加" type=button class="layui-btn layui-btn-oa layui-btn-sm" onclick="addLine('add<?=$product_type[paras_value]?>','<?=$product_company==$product_type['user_bu']||$product_type[paras_value]=='Other'?$product_company:''?>');return false;" />
        </td>
    </tr>
</table>
<?php endforeach;?>
<table id="tab4" width="100%" style="text-align: left;">
    <?if(!in_array($company,array('INHENERGY','HKNERGY'))):?>
        <tr>
            <td class="TableContent">是否为集抄项目</td>
            <td class="TableData" colspan="">
            <div class="layui-input-inline mandatory">
            <select id="is_collective" name="is_collective" lay-search lay-verify="required">
                        <option value=''>请选择</option>
                        <option value="是" <?php if ($main['is_collective'] == '是') : ?>selected<?php endif; ?>>是</option>
                        <option value="否" <?php if ($main['is_collective'] == '否') : ?>selected<?php endif; ?>>否</option>
                    </select>
                </div>
            </td>
            <td class="TableContent">SGC码</td>
            <td class="TableData" colspan="3">
            <div class="layui-input-inline mandatory">
            <select id="is_sgc" name="is_sgc" lay-search lay-verify="required">
                        <option value=''>请选择</option>
                        <?php foreach ($selectArr['SGC_TYPE'] as $v) : ?>
                            <option value="<?= $v['paras_value'] ?>"><?= $v['paras_desc'] ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="layui-input-inline mandatory">
                <input type="text" class="layui-input" name='sgc' lay-verify="SGC" />
                </div>
            </td>
        </tr>
    <?endif;?>
    <tr>
    <td class=" TableContent" >是否紧急</td>
        <td class="TableData" colspan="3" align="left" >
            <div class="layui-input-inline">
            <select id="urgent" name="urgent" lay-search >
                <option value=''>请选择</option>
                <option value="是" <?php if ($main['urgent'] == '是') : ?>selected<?php endif; ?>>是</option>
                <option value="否" <?php if ($mian['urgent'] == '否') : ?>selected<?php endif; ?>>否</option>
            </select>
            </div>
        </td>
    </tr>
    <tr>
        <td class="TableContent">本市场过往项目代号</td>
        <td class="TableData" colspan="1">
        <textarea class="layui-textarea required_field" id="past_pcode"  name="past_pcode" placeholder="此框必填，如无也要填无" lay-verify="required"><?= $main['past_pcode'] ?></textarea>
        </td>
        <td class="TableContent">本市场不同客户曾经出现过的项目代号</td>
        <td class="TableData" colspan="1">
        <textarea class="layui-textarea required_field" id="c_past_pcode" name="c_past_pcode" placeholder="此框必填，如无也要填无" lay-verify="required"><?= $main['c_past_pcode'] ?></textarea>
        </td>
    </tr>
   
    <tr>
        <td class="TableContent">其它需要说明的事项</td>
        <td class="TableData" colspan="3">
            <textarea class="layui-textarea" id="other_explain" name="other_explain"  <?if($form_type=="03"||$form_type=="04"):?>placeholder="请填写创建此类生产通知单理由" lay-verify="required" <?endif;?>></textarea>
        </td>
    </tr>
    <tr>
        <td class="TableContent">备品要求说明</td>
        <td class="TableData" colspan="3">
            <textarea class="layui-textarea" id="spare_requirement" name="spare_requirement"></textarea>
        </td>
    </tr>
    <?if(!in_array($company,array('INHENERGY','HKNERGY'))):?>
    <tr>
        <td class="TableContent">电表数量</td>
        <td class="TableData">
            <input type="text" name="meter_number" id="meter_number" class="layui-input" readonly />
        </td>
        <td class="TableContent">填表时间</td>
        <td class="TableData">
            <input type="text" name="create_time" id="create_time" class="layui-input date" value="<?= date('Y-m-d'); ?>" />
        </td>
    </tr>
    <tr>
        <td class="TableContent">是否需按照表号顺序包装：</td>
        <td class="TableData" align="left">
            <div class="layui-input-inline">
                <select id="if_package" name="if_package" lay-search>
                    <option value=''>请选择</option>
                    <?php foreach ($selectArr['packed_in_sequence'] as $v) : ?>
                        <option value="<?= $v['paras_value'] ?>"><?= $v['paras_desc'] ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
        </td>
        <td class="TableContent" colspan="2" style="text-align: left;">
            <font style="color:red;">
                （请根据客户需求谨慎选择，如无特别要求推荐不按照表号顺序包装，生产效率高且生产成本低。）
            </font>
        </td>
    </tr>
    <?endif;?>
</table>