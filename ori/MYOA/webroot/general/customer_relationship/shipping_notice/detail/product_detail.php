<table id="pro_detail" width="100%" style="text-align: center;">
<?if($product['electric']):?>
    <tr>
        <td class="TableHeader" colspan="15">所发电表类产品明细(请选择项目编号，可带出对应的其他栏位信息）</td>
    </tr>
    <tr class="TableContent">
        <td width="4%">序号</td>
        <td width="14%">品名</td>
        <td>内部型号</td>
        <td>数量</td>
        <td>单价</td>
        <td>金额</td>
        <td>项目编号</td>
        <td>项目代号</td>
        <td colspan="7">订单编号</td>
    </tr>
    <?php
    $serial_number=$eleNum = $eleAmount = 0;
    foreach ($product['electric'] as $eval) :
        $eleNum += $eval['number'];
        $eleAmount += $eval['total_amount'];
        ++$serial_number;
        $eval['serial_number']=$serial_number;
    ?>
        <tr class="TableData">
            <td class="TableData"><?= $eval['serial_number'] ?></td>
            <td class="TableData"><?= $eval['product_name'] ?></td>
            <td class="TableData"><?= $eval['internal_model'] ?></td>
            <td class="TableData"><?= $eval['number'] ?></td>
            <td class="TableData"><?= $isHide?'':$eval['price'] ?></td>
            <td class="TableData"><?= $isHide?'':$eval['total_amount'] ?></td>
            <td class="TableData"><?= $eval['project_number'] ?></td>
            <td class="TableData"><?= $eval['project_code'] ?></td>
            <td class="TableData" colspan="7"><?= $eval['order_number'] ?></td>
        </tr>
    <?php endforeach; ?>
    <tr class="TableData">
        <td colspan="2">小计</td>
        <td></td>
        <td class="TableData">
            <?= $eleNum ?>
        </td>
        <td></td>
        <td class="TableData">
            <?= $isHide?'':number_format($eleAmount, 4, '.', ''); ?>
        </td>
        <td></td>
        <td></td>
        <td colspan="7"></td>
    </tr>
<?endif;?>
<?if($product['soft']):?>
    
    <tr>
        <td class="TableHeader" colspan="15">软件类产品明细(含软件、软件相关服务、软件相关产品掌上机、短信猫等)</td>
    </tr>
    <tr >
        <td class="TableContent">合同有效期</td>
        <td class="TableContent">开始时间</td>
        <td class="TableData" colspan="5"> <input type="text" name="contract_begin_time" id="contract_begin_time" class="layui-input" value="<?=$contract[contract_begin_time]?>" readonly></td>
        <td class="TableContent">结束时间</td>
        <td class="TableData" colspan="7"> <input type="text" name="contract_end_time" id="contract_end_time" class="layui-input " value="<?=$contract[contract_end_time]?>" readonly></td>
    </tr>
    <tr class="TableContent">
        <td width="6%">序号</td>
        <td width="14%">品名</td>
        <td>内部型号</td>
        <td>数量</td>
        <td>单价</td>
        <td>金额</td>
        <td>项目编号</td>
        <td>项目代号</td>
        <td>授权产品</td>
        <td>授权年限(单位:月 永久填999)</td>
        <td>授权方式</td>
        <td>申请授权KEY</td>
        <td>申请授权期限(单位:月 永久填999)</td>
        <td>期望授权码生效时间</td>
        <td>订单编号</td>
    </tr>
    <?php
    $serial_number=$softNum = $softAmount = 0;
    foreach ($product['soft'] as $sVal) :
        $softNum += $sVal['number'];
        $softAmount += $sVal['total_amount'];
        ++$serial_number;
        $sVal['serial_number']=$serial_number;
    ?>
        <tr class="TableData">
            <td class="TableData" width="4%"><?= $sVal['serial_number'] ?></td>
            <td class="TableData"><?= $sVal['product_name'] ?></td>
            <td class="TableData" width="10%"><?= $sVal['internal_model'] ?></td>
            <td class="TableData" width="6%"><?= $sVal['number'] ?></td>
            <td class="TableData" width="8%"><?= $isHide?'':$sVal['price'] ?></td>
            <td class="TableData" width="8%"><?= $isHide?'':$sVal['total_amount'] ?></td>
            <td class="TableData" width="6%"><?= $sVal['project_number'] ?></td>
            <td class="TableData" width="6%"><?= $sVal['project_code'] ?></td>
            <td class="TableData" ><?= $license_product[$sVal['license_product']] ?></td>
            <td class="TableData" ><?= $sVal['license_years'] ?></td>
            <td class="TableData" ><?= $license_type[$sVal['license_type']] ?></td>
            <td class="TableData" ><?= $sVal['license_key'] ?></td>
            <td class="TableData" ><?= $sVal['license_term'] ?></td>
            <td class="TableData"><?= $sVal['license_key_date'] ?></td>
            <td class="TableData" width="8%"><?= $sVal['order_number'] ?></td>
        </tr>
    <?php endforeach; ?>
    <tr class="TableData">
        <td colspan="2">小计</td>
        <td></td>
        <td class="TableData">
            <?= $softNum ?>
        </td>
        <td></td>
        <td class="TableData">
            <?= $isHide?'':number_format($softAmount, 4, '.', ''); ?>
        </td>
        <td></td>
        <td></td>
        <td colspan="7"></td>
    </tr>
    <?if($main[user_bu]=="WITLINK"):?>
        <tr class="TableContent" style="text-align: center;">
        <td>授权产品</td><td>系统版本</td><td>授权生效时间</td><td>授权次数</td><td>授权天数（单位:天）</td><td>授权到期时间</td><td>授权码</td><td  colspan="8"></td>
        </tr>
        <?php $colNum=0;$colSoftNum=0; foreach ($product['soft'] as $dn => $dr) : if ($dn == 0) $dn = ''; ?>
            <?php if(empty($dr["license_product"])){continue;} ++$colNum;++$colSoftNum;  ?>
                <?if($flowArr[$step][role]=='运维部门'):?>
                    <tr align="center">
                        <td class="TableData"><?=$license_product[$dr["license_product"]]?>
                        <input type="hidden" name="license_product_id_<?=$colNum?>" class="not_disabled" value="<?=$dr[id]?>" />
                        </td>
                        <td class="TableData"><input form-verify="required" class="layui-input not_disabled" value="<?=$dr[license_version]?>"  name="license_version_<?=$colNum?>" id="license_version_<?=$colNum?>"/></td>
                        <td class="TableData" ></td>
                        <td class="TableData" ></td>
                        <td class="TableData" ></td>
                        <td class="TableData" ></td>
                        <td class="TableData" ></td>
                        <td class="TableData" colspan="8"></td>
                    </tr>
                <?elseif($flowArr[$step][role]=='项目管理部'):?>
                    <tr align="center">
                        <td class="TableData"><?=$license_product[$dr["license_product"]]?>
                        <input type="hidden" name="license_product_id_<?=$colNum?>" class="not_disabled" value="<?=$dr[id]?>" />
                        
                    </td>
                        <td class="TableData" ><?=$dr[license_version]?></td>
                        <td class="TableData"><input form-verify="required" value="<?=$dr[license_effect_time]?$dr[license_effect_time]:date("Y-m-d H:i:s")?>" class="layui-input not_disabled license_effect_time"  name="license_effect_time_<?=$colNum?>" id="license_effect_time_<?=$colNum?>"/></td>
                        <td class="TableData"><input form-verify="required" value="<?=$dr[license_times]?>"class="layui-input not_disabled"  name="license_times_<?=$colNum?>" id="license_times_<?=$colNum?>"/></td>
                        <td class="TableData"><input form-verify="required" value="<?=$dr[license_days]?>"class="layui-input not_disabled license_days"  name="license_days_<?=$colNum?>" id="license_days_<?=$colNum?>"/></td>
                        <td class="TableData"><input form-verify="required" value="<?=$dr[license_expire_time]?>"class="layui-input datetime not_disabled license_expire_time"  name="license_expire_time_<?=$colNum?>" id="license_expire_time_<?=$colNum?>"/></td>
                        <td class="TableData"><input form-verify="required" value="<?=$dr[license_code]?>"class="layui-input not_disabled"  name="license_code_<?=$colNum?>" id="license_code_<?=$colNum?>"/></td>
                        <td class="TableData" colspan="8"></td>
                    </tr>
                <?else:?>
                    <tr align="center">
                        <td class="TableData"><?=$license_product[$dr["license_product"]]?>     
                        <td class="TableData" ><?=$dr[license_version]?></td>
                        <td class="TableData" ><?=$dr[license_effect_time]?></td>
                        <td class="TableData" ><?=$dr[license_times]?></td>
                        <td class="TableData" ><?=$dr[license_days]?></td>
                        <td class="TableData" ><?=$dr[license_expire_time]?></td>
                        <td class="TableData" ><?=$dr[license_code]?></td>
                        <td class="TableData" colspan="8"></td>
                    </tr>
                <?endif;?>
        <?php endforeach; ?>
        <input type="hidden" name="license_product_num" class="not_disabled" value="<?=$colNum?>" />
    <?endif;?>
<?endif;?>
<?if($product['inhegrid']):?>
    <tr>
        <td class="TableHeader" colspan="15">所发银河电网产品明细</td>
    </tr>
    <tr class="TableContent">
        <td width="6%">序号</td>
        <td width="14%">品名</td>
        <td>内部型号</td>
        <td>数量</td>
        <td>单价</td>
        <td>金额</td>
        <td>项目编号</td>
        <td>项目代号</td>
        <td colspan="7">订单编号</td>
    </tr>
    <?php
    $serial_number=$inhegridNum = $inhegridAmount = 0;
    foreach ($product['inhegrid'] as $oval) :
        $inhegridNum += $oval['number'];
        $inhegridAmount += $oval['total_amount'];
        ++$serial_number;
        $oval['serial_number']=$serial_number;
    ?>
        <tr class="TableData">
            <td class="TableData"><?= $oval['serial_number'] ?></td>
            <td class="TableData"><?= $oval['product_name'] ?></td>
            <td class="TableData"><?= $oval['internal_model'] ?></td>
            <td class="TableData"><?= $oval['number'] ?></td>
            <td class="TableData"><?= $isHide?'':$oval['price'] ?></td>
            <td class="TableData"><?= $isHide?'':$oval['total_amount'] ?></td>
            <td class="TableData"><?= $oval['project_number'] ?></td>
            <td class="TableData"><?= $oval['project_code'] ?></td>
            <td class="TableData" colspan="7"><?= $oval['order_number'] ?></td>
        </tr>
    <?php endforeach; ?>
    <tr class="TableData">
        <td colspan="2">小计</td>
        <td></td>
        <td class="TableData">
            <?= $inhegridNum ?>
        </td>
        <td></td>
        <td class="TableData">
            <?= $isHide?'':number_format($inhegridAmount, 4, '.', ''); ?>
        </td>
        <td></td>
        <td></td>
        <td colspan="7"></td>
    </tr>
    <?endif;?>
<?if($product['inhenergy']):?>
    <tr>
        <td class="TableHeader" colspan="15">所发银河耐吉产品明细</td>
    </tr>
    <tr class="TableContent">
        <td width="6%">序号</td>
        <td width="14%">品名</td>
        <td>内部型号</td>
        <td>数量</td>
        <td>单价</td>
        <td>金额</td>
        <td>项目编号</td>
        <td>项目代号</td>
        <td colspan="7">订单编号</td>
    </tr>
    <?php
    $serial_number=$inhenergyNum = $inhenergyAmount = 0;
    foreach ($product['inhenergy'] as $oval) :
        $inhenergyNum += $oval['number'];
        $inhenergyAmount += $oval['total_amount'];
        ++$serial_number;
        $oval['serial_number']=$serial_number;
    ?>
        <tr class="TableData">
            <td class="TableData"><?= $oval['serial_number'] ?></td>
            <td class="TableData"><?= $oval['product_name'] ?></td>
            <td class="TableData"><?= $oval['internal_model'] ?></td>
            <td class="TableData"><?= $oval['number'] ?></td>
            <td class="TableData"><?= $isHide?'':$oval['price'] ?></td>
            <td class="TableData"><?= $isHide?'':$oval['total_amount'] ?></td>
            <td class="TableData"><?= $oval['project_number'] ?></td>
            <td class="TableData"><?= $oval['project_code'] ?></td>
            <td class="TableData" colspan="7"><?= $oval['order_number'] ?></td>
        </tr>
    <?php endforeach; ?>
    <tr class="TableData">
        <td colspan="2">小计</td>
        <td></td>
        <td class="TableData">
            <?= $inhenergyNum ?>
        </td>
        <td></td>
        <td class="TableData">
            <?= $isHide?'':number_format($inhenergyAmount, 4, '.', ''); ?>
        </td>
        <td></td>
        <td></td>
        <td colspan="7"></td>
    </tr>
    <?endif;?>

    <?if($product['cdinhe']):?>
    <tr>
        <td class="TableHeader" colspan="15">所发银河成都产品明细</td>
    </tr>
    <tr class="TableContent">
        <td width="6%">序号</td>
        <td width="14%">品名</td>
        <td>内部型号</td>
        <td>数量</td>
        <td>单价</td>
        <td>金额</td>
        <td>项目编号</td>
        <td>项目代号</td>
        <td colspan="7">订单编号</td>
    </tr>
    <?php
    $serial_number=$cdinheNum = $cdinheAmount = 0;
    foreach ($product['cdinhe'] as $oval) :
        $cdinheNum += $oval['number'];
        $cdinheAmount += $oval['total_amount'];
        ++$serial_number;
        $oval['serial_number']=$serial_number;
    ?>
        <tr class="TableData">
            <td class="TableData"><?= $oval['serial_number'] ?></td>
            <td class="TableData"><?= $oval['product_name'] ?></td>
            <td class="TableData"><?= $oval['internal_model'] ?></td>
            <td class="TableData"><?= $oval['number'] ?></td>
            <td class="TableData"><?= $isHide?'':$oval['price'] ?></td>
            <td class="TableData"><?= $isHide?'':$oval['total_amount'] ?></td>
            <td class="TableData"><?= $oval['project_number'] ?></td>
            <td class="TableData"><?= $oval['project_code'] ?></td>
            <td class="TableData" colspan="7"><?= $oval['order_number'] ?></td>
        </tr>
    <?php endforeach; ?>
    <tr class="TableData">
        <td colspan="2">小计</td>
        <td></td>
        <td class="TableData">
            <?= $cdinheNum ?>
        </td>
        <td></td>
        <td class="TableData">
            <?= $isHide?'':number_format($cdinheAmount, 4, '.', ''); ?>
        </td>
        <td></td>
        <td></td>
        <td colspan="7"></td>
    </tr>
    <?endif;?>
   
<?if($product['other']):?>
    <tr>
        <td class="TableHeader" colspan="15">所发其他类产品明细</td>
    </tr>
    <tr class="TableContent">
        <td width="6%">序号</td>
        <td width="14%">品名</td>
        <td>内部型号</td>
        <td>数量</td>
        <td>单价</td>
        <td>金额</td>
        <td>项目编号</td>
        <td>项目代号</td>
        <td colspan="7">订单编号</td>
    </tr>
    <?php
    $serial_number=$otherNum = $otherAmount = 0;
    foreach ($product['other'] as $oval) :
        $otherNum += $oval['number'];
        $otherAmount += $oval['total_amount'];
        ++$serial_number;
        $oval['serial_number']=$serial_number;
    ?>
        <tr class="TableData">
            <td class="TableData"><?= $oval['serial_number'] ?></td>
            <td class="TableData"><?= $oval['product_name'] ?></td>
            <td class="TableData"><?= $oval['internal_model'] ?></td>
            <td class="TableData"><?= $oval['number'] ?></td>
            <td class="TableData"><?= $isHide?'':$oval['price'] ?></td>
            <td class="TableData"><?= $isHide?'':$oval['total_amount'] ?></td>
            <td class="TableData"><?= $oval['project_number'] ?></td>
            <td class="TableData"><?= $oval['project_code'] ?></td>
            <td class="TableData" colspan="7"><?= $oval['order_number'] ?></td>
        </tr>
    <?php endforeach; ?>
    <tr class="TableData">
        <td colspan="2">小计</td>
        <td></td>
        <td class="TableData">
            <?= $otherNum ?>
        </td>
        <td></td>
        <td class="TableData">
            <?= $isHide?'':number_format($otherAmount, 4, '.', ''); ?>
        </td>
        <td></td>
        <td></td>
        <td colspan="7"></td>
    </tr>
    <?endif;?>
   
</table>
<table id="pro_detail2" width="100%" style="text-align: center;">
    <tr>
        <td class="TableContent" width="15%">合计</td>
        <td class="TableData">
            <input type="text" name="total_amount" id="total_amount" class="layui-input" value="<?= $isHide?'':number_format($eleAmount +$inhegridAmount+$inhenergyAmount+ $otherAmount + $softAmount+ $cdinheAmount, 4, '.', ''); ?>" />
        </td>
    </tr>
    <tr>
        <td class=" TableContent">备注</td>
        <td class="TableData">
            <textarea class="layui-textarea" id="remark" name="remark"><?= $main['remark'] ?></textarea>
        </td>
    </tr>
    <tr>
        <td class=" TableContent">附件</td>
        <td class="TableData" align="left">
        <input id="shipping_attch" type="button" class="layui-btn layui-bg-gray btn-upload" style="display: none;" value="上传" readonly />
        </td>
    </tr>
    
</table>