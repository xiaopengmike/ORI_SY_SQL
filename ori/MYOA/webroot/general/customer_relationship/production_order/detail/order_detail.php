<?if(count($orderDetail['meter_one'])>0):?>
<table id="tab2" width="100%" style="text-align: left;">
    <tr>
        <td class="TableHeader" colspan="<?= count($detailItem['meter_one']) ?>" style="text-align:center"><?=$selectArr['product_detail_type'][0][paras_desc]?>&nbsp;&nbsp;&nbsp;<input type='hidden'  value="1" name="is_<?=$product_type[paras_value]?>" title="是否需要总部技术支持"/></td>
    </tr>
    <?php foreach (ActionModel::$METER_TYPE as $meterType) : ?>
        <tr class="TableContent" style="text-align: center;">
            <?php foreach ($detailItem[$meterType] as $val) : 
                if($product_company!='INHE'&&$val['item_id']=='other_company'){ continue;}
                ?>
                <td  <?= $val['class'] ?> colspan="<?= $val['colspan'] ?>"><?= $val['item_name'] ?></td>
            <?php endforeach; ?>
        </tr>
        <?php $colNum=0;foreach ($orderDetail[$meterType] as $dk => $dv) : if ($dk == 0) $dk = '';++$colNum; ?>
            <tr align="center">
                <?php foreach ($detailItem[$meterType] as $md) : 
                    if($product_company!='INHE'&&$md['item_id']=='other_company'){ continue;}
                    ?>
                    <td class="TableData" colspan="<?= $md['colspan'] ?>">
                        <?php if ($md['have_attach']) { ?>
                            <input id="<?= $md['item_id'] . $colNum ?>" type="button" class="layui-btn layui-bg-gray btn-upload" style="display:none" value="上传" readonly />
                        <?php } else if (!empty($md['if_select'])) { ?>
                            <?= $md['item_id']=='other_company'?$other_company_product[$dv[$md['item_id']]]:${$md['if_select']}[$dv[$md['item_id']]] ?>
                        <?php } else if ($md['item_id']=='serial_number') { ?>
                            <?= $colNum ?>
                        <?php } else { ?>
                            <?= $dv[$md['item_id']] ?>
                        <?php } ?>
                    </td>
                <?php endforeach; ?>
            </tr>
        <?php endforeach; ?>
    <?php endforeach; ?>
</table>
<?endif;?>
<!-- 多类型产品-->
<input type="hidden" id="product_detail_types" value='<?=json_encode($product_detail_types)?>'/>
<? $line_num=1; foreach($selectArr['product_detail_type'] as $product_type):?>
    <?php if(!in_array($product_type['paras_value'],$non_types)){continue;} ++$line_num; ?>
    <?php $detailItemType=in_array($product_type['paras_value'],array('Soft'))?"non_".$product_type['paras_value']:$detailItemType="non_meter"?>
<table id="tab3" width="100%" style="text-align: left;">
    <tr>
        <td class="TableHeader" colspan="<?= count($detailItem[$detailItemType]) ?>" style="text-align:center">
        
        <?if($product_type[paras_value]=='Other'):?><?=$company_names[$company]?><?endif;?><?=$product_type[paras_desc]?> 
        <!-- <?if($product_type[user_bu]&&strtoupper($product_type[user_bu])!=$company):?>&nbsp;&nbsp;&nbsp;<input type='checkbox' <?if($sub_process[$product_type[paras_value]]):?>checked<?endif;?> value="1" name="is_<?=$product_type[paras_value]?>" title="是否需要走分公司流程"/><?endif;?> -->
        </td>
    </tr>
    <?if($product_type['paras_value']=='Soft'):?>
        <!--TODO 慧软CRM合同评审 <?if($contract[contract_begin_time]):?> -->
        <tr >
        <td class="TableContent">合同有效期</td>
        <td class="TableContent">开始时间</td>
        <td class="TableData" colspan="2"> <input type="text" name="contract_begin_time" id="contract_begin_time" class="layui-input" value="<?=$contract[contract_begin_time]?>" readonly></td>
        <td class="TableContent">结束时间</td>
        <td class="TableData" colspan="5"> <input type="text" name="contract_end_time" id="contract_end_time" class="layui-input " value="<?=$contract[contract_end_time]?>" readonly></td>
        </tr>
        <!-- TODO 慧软CRM合同评审 -->
        <!--TODO 慧软CRM合同评审 <?endif;?>
        <?if($license_service[$contract[om_services]]):?>
        <tr >
            <td class="TableContent"  colspan="2">运维服务</td>
            <td class="TableData" colspan="">
            <?=$license_service[$contract[om_services]]?></td>
            <td class="TableContent">年限</td>
            <td class="TableData" colspan="2"> <input type="number" placeholder="填数字（单位：年）" name="om_services_years" id="om_services_years" class="layui-input" value="<?=$contract[om_services_years]?>" ></td>
            <td class="TableContent" colspan="5"></td>
        </tr>
        <?endif;?>
        <?if($licenseProduct):?>
        <tr class="TableContent">
            <td  colspan="">序号</td>
            <td  colspan="2">授权产品</td>
            <td  colspan="3">授权年限</br>（单位：月，永久请填999）</td>
            <td  colspan="5">授权方式</td>
        </tr>
        <?endif;?>
    <?foreach($licenseProduct as $eval) :?>
        <tr class="TableData">
            <td  colspan=""><?= $eval['serial_number'] ?></td>
            <td  colspan="2"><?= $license_product[$eval['license_product']] ?></td>
            <td  colspan="3"><?= $eval['license_years'] ?></td>
            <td  colspan="5"><?= $license_type[$eval['license_type']] ?></td>
        </tr>
    <?endforeach;?> -->
    <?endif;?>
    <tr class="TableContent" style="text-align: center;">
        <?php foreach ($detailItem[$detailItemType] as $itemOne) : 
             if($product_company!=$product_type['user_bu']&&$product_type[paras_value]!='Other'&&$itemOne['item_id']=='other_company'){ continue;}
            ?>
            <td  <?= $itemOne['class'] ?>><?= $itemOne['item_name'] ?></td>
        <?php endforeach; ?>
    </tr>
    <?php $colNum=0; foreach ($orderDetail['non_meter'] as $dn => $dr) : if ($dn == 0) $dn = ''; ?>
    <?php if($product_type['paras_value']!=$dr["non_type"]){continue;} ++$colNum;  ?>
        <tr align="center">
            <?php foreach ($detailItem[$detailItemType] as $itemOne) : 
                 if($product_company!=$product_type['user_bu']&&$product_type[paras_value]!='Other'&&$itemOne['item_id']=='other_company'){ continue;}
                ?>
                <td class="TableData">
                    <?php if ($itemOne['have_attach']) { ?>
                        <input id="<?= $itemOne['item_id'] .'_'.$product_type['paras_value'] . $dr["non_serial_number"] ?>" type="button" class="layui-btn layui-bg-gray btn-upload" style="display:none" value="上传" readonly />
                    <?php } else if (!empty($itemOne['if_select'])) { ?>
                        <?= $itemOne['item_id']=='other_company'?$other_company_product[$dr[$itemOne['item_id']]]:${$itemOne['if_select']}[$dr[$itemOne['item_id']]] ?>
                    <?php } else if ($itemOne['item_id']=='non_serial_number') { ?>
                        <?= $colNum ?>
                    <?php } else { ?>
                        <?= $dr[$itemOne['item_id']] ?>
                    <?php } ?>
                </td>
            <?php endforeach; ?>
        </tr>
    <?php endforeach; ?>
    <?if($product_type['paras_value']=='Soft'&&$flowArr[$step][role]=='填写品号'&&$main[user_bu]=="WITLINK"):?>
        <tr  class="TableContent" style="text-align: center;">
           <td>品名</td> <td>品号</td><td colspan="8">备注</td></td>
        </tr>
        <?$pnNum=0;foreach($orderDetail['pn'] as $dn => $dr): ++$pnNum;?>
            <tr align="center">
                <td class="TableData" colspan=""><input value="<?=$dr[license_product_name]?>" form-verify="required" type="text" class="layui-input required_field not_disabled" id="license_product_name" name="license_product_name_<?=$pnNum?>" /></td>
                <td class="TableData" colspan=""><input value="<?=$dr[license_product_code]?>" form-verify="required" type="text" class="layui-input required_field not_disabled" id="license_product_code" name="license_product_code_<?=$pnNum?>" /></td>
                <td class="TableData" colspan="8"><textarea value="<?=$dr[remark]?>" class="layui-input not_disabled"  id="license_product_remark" name="license_product_remark_<?=$pnNum?>" ><?=$dr[remark]?></textarea>
                <a href="javascript:;" onclick="removeLine(this)">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;删除</a>
                </td>
            </tr>
        <?endforeach;?>
        <tr align="left" id="addLicenseProduct">
            <td class="TableData" colspan="10">
                <input value="添加" type=button class="layui-btn layui-btn-oa layui-btn-sm not_disabled" onclick="createLine('addLicenseProduct','LicenseProduct');return false;" />
            </td>
        </tr>
        <input type="hidden" id="addLicenseProductCount" class="layui-input not_disabled" name="addLicenseProductCount" value="<?=$pnNum?>" />
        <!-- <?php $colNum=0;$colSoftNum=0; foreach ($orderDetail['non_meter'] as $dn => $dr) : if ($dn == 0) $dn = ''; ?>
            <?php if($product_type['paras_value']!=$dr["non_type"]||empty($dr["license_product"])){continue;} ++$colNum;++$colSoftNum;  ?>
            
            <tr align="center">
                <td class="TableData"><?=$license_product[$dr["license_product"]]?>
                <input type="hidden" name="license_product_id_<?=$colNum?>" class="not_disabled" value="<?=$dr[id]?>" />
                
            </td>
                <td class="TableData"><input form-verify="required" class="layui-input not_disabled required_field"  name="license_product_code_<?=$colNum?>" id="license_product_code_<?=$colNum?>"/></td>
                <td class="TableData"><input form-verify="required" class="layui-input not_disabled required_field" name="license_product_name_<?=$colNum?>" id="license_product_name_<?=$colNum?>"/></td>
                <td class="TableData" colspan="7"></td>
            </tr>
            <?php endforeach; ?> 
            <input type="hidden" name="license_product_num" class="not_disabled" value="<?=$colNum?>" />-->
    <?endif;?>
    <?if($product_type['paras_value']=='Soft'&&$flowArr[$step][role]!='填写品号'&&$main[user_bu]=="WITLINK"):?>
        <tr class="TableContent" style="text-align: center;">
            <td>品名</td><td>品号</td><td colspan="8">备注</td></td>
            </tr>
            <?$pnNum=0;foreach($orderDetail['pn'] as $dn => $dr): ++$pnNum;?>
                <tr align="center">
                    <td class="TableData"><?=$dr["license_product_name"]?></td>
                    <td class="TableData"><?=$dr["license_product_code"]?></td>
                    <td class="TableData" colspan="8"><pre><?=$dr["remark"]?></pre></td>
                </tr>
            <?endforeach;?>
    <?endif;?>
    <?if($product_type['paras_value']=='Soft'&&$flowArr[$step][role]=='验收填写'&&$main[user_bu]=="WITLINK"):?>
        <tr class="TableContent" style="text-align: center;">
            <td>系统上线验收日期</td><td>验收报告</td><td>运维服务截止日期</td><td  colspan="7">备注</td>
            </tr>
            <?$acceptNum=0;foreach($orderDetail['accept'] as $dn => $dr): if($dr["serial_number"]>$acceptNum){$acceptNum=$dr["serial_number"];}?>
            <tr align="center">
                <td class="TableData" colspan=""><input value="<?=$dr[acceptance_date]?>" form-verify="required" type="text" class="layui-input required_field not_disabled" id="acceptance_date" name="acceptance_date_<?=$dr["serial_number"]?>" /></td>
                <td class="TableData"> <input  id="acceptance_report_<?=$dr["serial_number"]?>" type="button" class="layui-btn layui-bg-gray btn-upload not_disabled"  style="display: inline!important;" value="上传"  /></td>
                <td class="TableData" colspan=""><input value="<?=$dr[server_end_date]?>" form-verify="required" type="text" class="layui-input required_field not_disabled" id="server_end_date" name="server_end_date_<?=$dr["serial_number"]?>" /></td>
                <td class="TableData" colspan="7"><textarea value="<?=$dr[remark]?>" class="layui-input not_disabled"  id="remark" name="remark_<?=$dr["serial_number"]?>" ><?=$dr[remark]?></textarea>
                <a href="javascript:;" onclick="removeLine(this)">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;删除</a>
                </td>
            </tr>
        <?endforeach;?>
            <tr align="left" id="addLicenseAccept">
                <td class="TableData" colspan="10">
                    <input value="添加" type=button class="layui-btn layui-btn-oa layui-btn-sm not_disabled" onclick="createLine('addLicenseAccept','LicenseAccept');return false;" />
                </td>
            </tr>
            <input type="hidden" id="addLicenseAcceptCount" class="layui-input not_disabled" name="addLicenseAcceptCount" value="<?=$acceptNum?>" />
            <!-- <tr align="center">
                <td class="TableData"><input not_disabled="true" form-verify="required" id="acceptance_date" name="acceptance_date" class="required_field layui-input not_disabled date" value="<?=$dr["acceptance_date"]?>" /></td>
                <td class="TableData"> <input form-verify="required" id="acceptance_report" type="button" class="required_field layui-btn layui-bg-gray btn-upload not_disabled" style="display: inline!important;" value="上传" /></td>
                <td class="TableData"><input not_disabled="true" form-verify="required" id="server_end_date" name="server_end_date" class="required_field layui-input not_disabled date" value="<?=$dr["server_end_date"]?>" /></td>
                <td class="TableData" colspan="7"></td>
            </tr> -->
    <?endif;?>
    <?if($product_type['paras_value']=='Soft'&&$flowArr[$step][role]!='验收填写'&&$main[user_bu]=="WITLINK"):?>
        <tr class="TableContent" style="text-align: center;">
            <td>系统上线验收日期</td><td>验收报告</td><td>运维服务截止日期</td><td  colspan="7">备注</td>
            </tr>
            <?$acceptNum=0;foreach($orderDetail['accept'] as $dn => $dr): ++$acceptNum;?>
                <tr align="center">
                    <td class="TableData"><?=$dr["acceptance_date"]?></td>
                    <td class="TableData"> <input  id="acceptance_report_<?=$dr["serial_number"]?>" type="button" class="layui-btn layui-bg-gray btn-upload "  style="display: none;" value="上传" readonly /></td>
                    <td class="TableData"><?=$dr["server_end_date"]?></td>
                    <td class="TableData" colspan="7"><pre><?=$dr["remark"]?></pre></td>
                </tr>
            <?endforeach;?>
    <?endif;?>
</table>
<?php endforeach;?>
<table id="tab4" width="100%" style="text-align: left;">
<?if(!in_array($company,array('INHENERGY','HKNERGY'))):?>
    <tr>
        <td class="TableContent">是否为集抄项目</td>
        <td class="TableData" colspan="1">
        <input type="text" class="layui-input" value="<?= $main['is_collective'] ?>" />
        <input type="hidden" name="is_collective" id="is_collective" class="layui-input" value="<?= $main['is_collective'] ?>" />
        </td>
        <td class="TableContent">SGC码</td>
        <td class="TableData" colspan="3">
        <div class="layui-input-inline ">
        <input type="text" class="layui-input" value="<?= $is_sgc[$main['is_sgc']] ?>" />
        <input type="hidden" name="is_sgc" id="is_sgc" class="layui-input" value="<?= $main['is_sgc'] ?>" />
            </div>
            <div class="layui-input-inline ">
            <input type="text" class="layui-input" name='sgc' lay-verify="SGC" value="<?=$main['sgc']?>" />
            </div>
        </td>
    </tr>
    <?endif;?>
    <tr>
    <td class=" TableContent">是否紧急</td>
            <td class="TableData" colspan="3">
            <input type="text" class="layui-input"  value="<?= $main['urgent'] ?>" />
        </td>
    </tr>
    <tr>
        <td class="TableContent">本市场过往项目代号</td>
        <td class="TableData" colspan="1">
        <textarea class="layui-textarea " id="past_pcode"  name="past_pcode" ><?= $main['past_pcode'] ?></textarea>
        </td>
        <td class="TableContent">本市场不同客户曾经出现过的项目代号</td>
        <td class="TableData" colspan="1">
        <textarea class="layui-textarea " id="c_past_pcode" name="c_past_pcode" ><?= $main['c_past_pcode'] ?></textarea>
        </td>
    </tr>
    <tr>
        <td class="TableContent" width="20%">其它需要说明的事项</td>
        <td class="TableData" colspan="3" align="left">
            <textarea  class="layui-textarea TextDetails" id="other_explain" name="other_explain"><?= $main['other_explain'] ?></textarea>
            <!-- <?= $main['other_explain'] ?> -->
        </td>
    </tr>
    <tr>
        <td class="TableContent" width="20%">备品要求说明</td>
        <td class="TableData" colspan="3" align="left">
            <textarea class="layui-textarea TextDetails" id="spare_requirement" name="spare_requirement"><?= $main['spare_requirement'] ?></textarea>
            <!-- <?= $main['spare_requirement'] ?> -->
        </td>
    </tr>
    <?if(!in_array($company,array('INHENERGY','HKNERGY'))):?>
    <tr>
        <td class="TableContent">电表数量</td>
        <td class="TableData" width="30%">
            <?= $main['meter_number'] ?>
        </td>
        <td class="TableContent" width="20%">填表时间</td>
        <td class="TableData">
            <?= $main['write_time'] ?>
        </td>
    </tr>
    <tr>
        <td class="TableContent">是否需按照表号顺序包装：</td>
        <td class="TableData">
            <?= $packed_in_sequence[$main['package_in_sequence']] ?>
        </td>
        <td class="TableContent" colspan="2" style="text-align: left;">
            <font style="color:red;">
                （请根据客户需求谨慎选择，如无特别要求推荐不按照表号顺序包装，生产效率高且生产成本低。）
            </font>
        </td>
    </tr>
    <?else:?>
        <tr>
        <td class="TableContent" >填表时间</td>
        <td class="TableData" colspan="3">
            <?= $main['write_time'] ?>
        </td>
    </tr>
    <?endif;?>
</table>
<script>
    $(function(){

        $('#other_explain,#spare_requirement').each(function() {
            //$(this).css("display","none");
            if($(this).val()!=''){
                $(this).parent().html($(this).val().replace( /\n|\r|(\r\n)|(\u0085)|(\u2028)|(\u2029)/g,"<br>"));
            }
            

        });
    })
</script>