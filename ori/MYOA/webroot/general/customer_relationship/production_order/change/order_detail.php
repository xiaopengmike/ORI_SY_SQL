<?if(!in_array($company,array('INHENERGY','HKNERGY'))&&(($type=="rd_production_order"&&($company=='INHE'||$company=='JXINHE'||$company=='INHEJX'||$company=='INHEIAC'||$company=='INHEIMC'||$company=='GRIDINHE'||$company=='IMCINHE'))||$type!="rd_production_order")):?>
<table id="tab2" width="100%" style="text-align: left;">
    <input type="hidden" id="addMeterCount" name="addMeterCount" value="<?= count($orderDetail['meter_one']); ?>" />
    <tr>
        <td class="TableHeader" colspan="<?= count($detailItem['meter_three']) + 1 ?>" style="text-align:center"><?=$selectArr['product_detail_type'][0][paras_desc]?>
            <input type='hidden' name='sub_user_bu' value="INHE"/>
        </td>
    </tr>
    <?php foreach (ActionModel::$METER_TYPE as $meterType) : ?>
        <tr class="TableContent" style="text-align: center;">
            <?php foreach ($detailItem[$meterType] as $val) : 
                if($product_company!='INHE'&&$val['item_id']=='other_company'){ continue;}
            ?>
                <td <?= $val['nowrap'] ?> <?= $val['class'] ?> colspan="<?= $val['colspan'] ?>"><?= $val['item_name'] ?></td>
            <?php endforeach; ?>
        </tr>
        <tbody id="<?= $meterType ?>">
            <?php $colNum=0;foreach ($orderDetail[$meterType] as $dk => $dv) : $colNum = $colNum + 1; ?>
                <tr align="center">
                    <?php $colCount = 0;
                    foreach ($detailItem[$meterType] as $md) : $colCount++; 
                    if($product_company!='INHE'&&$md['item_id']=='other_company'){ continue;}
                    ?>
                    
                        <td class="TableData" colspan="<?= $md['colspan'] ?>">
                            <?php if ($md['have_attach']) { ?>
                                <input id="<?= $md['item_id'] . $colNum ?>" type="button" class="layui-btn layui-bg-gray btn-upload" style="display:inline!important;" value="上传" />
                            <?php } else if (!empty($md['if_select'])) { ?>
                                <div class="layui-input-inline">
                                <?if($md['item_id']=='other_company'):?>
                                    <select id="<?= $md['item_id'] . $colNum ?>"  name="<?= $md['item_id'] . $colNum ?>" lay-search lay-filter="<?= $md['item_id']?>" <?= $md['is_required']==1?'lay-verify="required"':''?>>
                                        <option value=''>请选择</option>
                                        <?php foreach ($selectArr[$md['if_select']] as $v) : 
                                            if($product_company==$v['user_bu']){ continue;}
                                            ?>
                                            <option value="<?= $v['user_bu'] ?>" <?php if ($v['user_bu'] == $dv[$md['item_id']]) : ?>selected<?php endif; ?>><?= $v['extra'] ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                <?else:?>
                                    <select id="<?= $md['item_id'] . $colNum ?>"  name="<?= $md['item_id'] . $colNum ?>" lay-search lay-filter="<?= $md['item_id']?>" <?= $md['is_required']==1?'lay-verify="required"':''?>>
                                        <option value=''>请选择</option>
                                        <?php foreach ($selectArr[$md['if_select']] as $v) : ?>
                                            <option value="<?= $v['paras_value'] ?>" <?php if ($v['paras_value'] == $dv[$md['item_id']]) : ?>selected<?php endif; ?>><?= $v['paras_desc'] ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                <?endif;?>
                                    
                                </div>
                            <?php } else if ($md['item_id']=='serial_number') { ?>
                                <input type="text" name="<?= $md['item_id'] . $colNum ?>" class="layui-input" value="<?= $colNum ?>" <?= $md['class'] ?> />
                            <?php } else { ?>
                                <input type="text" id="<?= $md['item_id']?>" name="<?= $md['item_id'] . $colNum ?>" class="layui-input" value="<?= $dv[$md['item_id']] ?>" <?= $md['class'] ?> />
                            <?php } ?>
                            <?php if ($colCount == count($detailItem[$meterType])) { ?>
                                <a href="javascript:;" onclick="removeLine(this)">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;删除</a>
                            <?php } ?>
                        </td>
                    <?php endforeach; ?>
                </tr>
            <?php endforeach; ?>
        </tbody>
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
    <?if(!in_array($company,array('GRIDINHE','IMCINHE','INHENERGY','HKNERGY'))&&$type=="rd_production_order"&&(!in_array(strtoupper($product_type['paras_value']),array($company)))&&(!in_array($product_type['paras_value'],array('Other')))){continue;}?>
    <?if(($company=='GRIDINHE'||$company=='IMCINHE')&&(!in_array($product_type['paras_value'],array('Other')))){continue;}?>
    <?if(($company=='INHENERGY'||$company=='HKNERGY')&&(!in_array($product_type['paras_value'],array('Inhenergy','Other')))){continue;}?>
    <?php $detailItemType=in_array($product_type['paras_value'],array('Soft'))?"non_".$product_type['paras_value']:$detailItemType="non_meter"?>
    <?php if(in_array($product_type['paras_value'],array('Electric'))){continue;} ++$line_num; ?>
<table id="tab3" width="100%" style="text-align: left;">
    
    <tr>
        <td class="TableHeader" colspan="<?= count($detailItem[$detailItemType]) ?>" style="text-align:center">
        <?if($product_type[paras_value]=='Other'):?><?=$company_names[$company]?><?endif;?><?=$product_type[paras_desc]?> 
        <?if($product_type[user_bu]&&strtoupper($product_type[user_bu])!=$company):?>&nbsp;&nbsp;&nbsp;<input type='hidden' <?if($sub_process[$product_type[paras_value]]):?>checked<?endif;?> value="1" name="is_<?=$product_type[paras_value]?>" title="是否需要走分公司流程"/><?endif;?>
        </td>
    </tr>
    <?if($product_type['paras_value']=='Soft'):?>
        <tr >
        <td class="TableContent">合同有效期</td>
        <td class="TableContent">开始时间</td>
        <td class="TableData" colspan="2"> <input type="text" name="contract_begin_time" id="contract_begin_time" class="layui-input" value="<?=$contract[contract_begin_time]?>" readonly></td>
        <td class="TableContent">结束时间</td>
        <td class="TableData" colspan="5"> <input type="text" name="contract_end_time" id="contract_end_time" class="layui-input " value="<?=$contract[contract_end_time]?>" readonly></td>
        </tr>
    <?endif;?>
    <tr class="TableContent" style="text-align: center;">
        <?php foreach ($detailItem[$detailItemType] as $itemOne) : 
            if($product_company!=$product_type['user_bu']&&$product_type[paras_value]!='Other'&&$itemOne['item_id']=='other_company'){ continue;}
            ?>
            <td <?= $itemOne['nowrap'] ?> <?= $itemOne['class'] ?>><?= $itemOne['item_name'] ?></td>
        <?php endforeach; ?>
    </tr>
    <tbody id="non_meter_<?=$product_type[paras_value]?>">
        <?php $colNum=0;foreach ($orderDetail['non_meter'] as $dn => $dr) :?>
        <?php if($product_type['paras_value']!=$dr["non_type"]){continue;} $colNum= $colNum + 1;  ?>
            <tr align="center">
                <?php $colCount = 0;
                foreach ($detailItem[$detailItemType] as $itemOne) : $colCount++; 
                if($product_company!=$product_type['user_bu']&&$product_type[paras_value]!='Other'&&$itemOne['item_id']=='other_company'){ continue;}
                ?>
                    <td class="TableData">
                        <?php if ($itemOne['have_attach']) { ?>
                            <input id="<?= $itemOne['item_id'].'_'.$product_type['paras_value'] . $colNum ?>" type="button" class="layui-btn layui-bg-gray btn-upload" style="display:inline!important;" value="上传" />
                        <?php } else if (!empty($itemOne['if_select'])) { ?>
                            <div class="layui-input-inline">
                                <?if($itemOne['item_id']=='other_company'):?>
                                    <select id="<?= $itemOne['item_id'].'_'.$product_type['paras_value'] . $colNum ?>" name="<?= $itemOne['item_id'].'_'.$product_type['paras_value'] . $colNum ?>" lay-search>
                                        <option value=''>请选择</option>
                                        <?php foreach ($selectArr[$itemOne['if_select']] as $v) : 
                                            if($product_company==$v['user_bu']){ continue;}
                                            ?>
        
                                            <option value="<?= $v['user_bu'] ?>" <?php if ($v['user_bu'] == $dr[$itemOne['item_id']]) : ?>selected<?php endif; ?>><?= $v['extra'] ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                <?else:?>
                                    <select id="<?= $itemOne['item_id'].'_'.$product_type['paras_value'] . $colNum ?>" name="<?= $itemOne['item_id'].'_'.$product_type['paras_value'] . $colNum ?>" lay-search>
                                        <option value=''>请选择</option>
                                        <?php foreach ($selectArr[$itemOne['if_select']] as $v) : ?>
                                            <option value="<?= $v['paras_value'] ?>" <?php if ($v['paras_value'] == $dr[$itemOne['item_id']]) : ?>selected<?php endif; ?>><?= $v['paras_desc'] ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                <?endif;?>
                                
                            </div>
                        <?php } else if ($itemOne['item_id']=='non_serial_number') { ?>
                            <input type="text" name="<?= $itemOne['item_id'].'_'.$product_type['paras_value'] . $colNum ?>" class="layui-input" value="<?= $colNum ?>" <?= $itemOne['class'] ?> />
                        <?php } else { ?>
                            <input type="text" name="<?= $itemOne['item_id'].'_'.$product_type['paras_value'] . $colNum ?>" class="layui-input" value="<?= $dr[$itemOne['item_id']] ?>" <?= $itemOne['class'] ?> />
                        <?php } ?>
                        <?php if ($colCount == count($detailItem[$detailItemType])) { ?>
                            <a href="javascript:;" onclick="removeLine(this)">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;删除</a>
                        <?php } ?>
                    </td>
                <?php endforeach; ?>
            </tr>
        <?php endforeach; ?>
    </tbody>
    <input type="hidden" id="add<?=$product_type[paras_value]?>Count" name="add<?=$product_type[paras_value]?>Count" value="<?= $colNum ?>" />
    <tr align="left" id="add<?=$product_type[paras_value]?>">
        <td class="TableData" colspan="<?= count($detailItem[$detailItemType]) ?>">
            <input value="添加" type=button class="layui-btn layui-btn-oa layui-btn-sm" onclick="addLine('add<?=$product_type[paras_value]?>','<?=$product_company==$product_type['user_bu']||$product_type[paras_value]=='Other'?$product_company:''?>');return false;" />
        </td>
    </tr>
</table>
<?php endforeach;?>
<table id="tab4" width="100%" style="text-align: left;">
<?if(!in_array($company,array('INHENERGY','HKNERGY','GRIDINHE','IMCINHE'))):?>
<tr>
        <td class="TableContent">是否为集抄项目</td>
        <td class="TableData" colspan="1">
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
                        <option value="<?= $v['paras_value'] ?>" <?php if ($main['is_sgc'] == $v['paras_value']) : ?>selected<?php endif; ?>><?= $v['paras_desc'] ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="layui-input-inline mandatory">
            <input type="text" class="layui-input" name='sgc' lay-verify="SGC" value="<?=$main['sgc']?>" />
            </div>
        </td>
    </tr>
    <?endif;?>
    <tr>
    <td class=" TableContent" >是否紧急</td>
        <td class="TableData" colspan="3" align="left">
            <div class="layui-input-inline">
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
        <td class="TableData" colspan="3" align="left">
            <textarea class="layui-textarea TextDetails" id="other_explain" name="other_explain"><?= $main['other_explain'] ?></textarea>
        </td>
    </tr>
    <tr>
        <td class="TableContent">备品要求说明</td>
        <td class="TableData" colspan="3">
            <textarea class="layui-textarea TextDetails" id="spare_requirement" name="spare_requirement"><?= $main['spare_requirement'] ?></textarea>
        </td>
    </tr>
    <?if(!in_array($company,array('INHENERGY','HKNERGY'))):?>
    <tr>
        <td class="TableContent">电表数量</td>
        <td class="TableData">
            <input type="text" name="meter_number" id="meter_number" class="layui-input" value="<?= $main['meter_number'] ?>" />
        </td>
        <td class="TableContent">填表时间</td>
        <td class="TableData">
            <input type="text" name="create_time" id="create_time" class="layui-input date" value="<?= date('Y-m-d'); ?>" />
        </td>
    </tr>
    <tr>
        <td class="TableContent">是否需按照表号顺序包装：</td>
        <td class="TableData" align="left">
            <select id="if_package" name="if_package" lay-search>
                <option value=''>请选择</option>
                <?php foreach ($selectArr['packed_in_sequence'] as $v) : ?>
                    <option value="<?= $v['paras_value'] ?>" <?php if ($v['paras_value'] == $main['package_in_sequence']) : ?>selected<?php endif; ?>><?= $v['paras_desc'] ?></option>
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