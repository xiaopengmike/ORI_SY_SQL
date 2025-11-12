<table id="pro_detail" width="100%" style="text-align: center;">
<input type="hidden" id="product_detail_types" value='<?=json_encode($product_detail_types)?>'/>
<? foreach($selectArr['product_detail_type'] as $product_type):?>
    <?php if(empty($product[strtolower($product_type['paras_value'])])){
        continue;
    }?>
    <tr>
        <td class="TableHeader" colspan="12"><?if($product_type[paras_value]=='Other'):?><?=$company_names[$company]?><?endif;?><?=$product_type[paras_desc]?></td>
    </tr>
    <?if($product_type['paras_value']=='Soft'):?>
        
        <tr >
        <td class="TableContent">合同有效期</td>
        <td class="TableContent">开始时间</td>
        <td class="TableData" > <input type="text" name="contract_begin_time" id="contract_begin_time" class="layui-input date" value="<?=$contract[contract_begin_time]?>" lay-key="1"></td>
        <td class="TableContent">结束时间</td>
        <td class="TableData" colspan="2"> <input type="text" name="contract_end_time" id="contract_end_time" class="layui-input date" value="<?=$contract[contract_end_time]?>" lay-key="1"></td>
        <td class="TableContent" >附件</td>
        <td class="TableData" colspan="4" align="left"><input id="soft_license_contract" type="button" class="layui-btn layui-bg-gray btn-upload" style="display: none;" value="上传" readonly ></td>
        </tr>
    <?endif;?>
    <tr class="TableContent">
        <td width="5%">序号</td>
        <?php if($product_type['paras_value']=='Electric'):?>
                <td width="20%" style="color: red">
                    <b>名称应包含下列信息：<br />1、电表通讯模块 2、CIU及下行通讯方式</b>
                </td>
            <?php else:?>
                <td>名称</td>
            <?php endif;?>
        <td width="25%">规格</td>
        <td width="6%">单位</td>
        <td width="6%">数量</td>
        <td width="9%">单价</td>
        <?php if($product_type['user_bu']==$product_company||$product_type['paras_value']=='Other'):?>
                <td colspan="" width="5%">总金额</td>
                <td colspan="" width="4%">涉及其它公司产品作为配件</td>
            <?php else:?>
                <td colspan="2" width="9%">总金额</td>
            <?php endif;?>
        <?php if($product_type['paras_value']=='Electric'):?>
                <td colspan="2">技术要求</td>
                <td colspan="2">产品品牌</td>
            <?php elseif($product_type['paras_value']=='Soft'):?>
                <!-- TODO 慧软CRM合同评审 -->
            <td >授权产品</td>
            <td >授权年限</br>（单位：月，永久请填999）</td>
            <td >授权方式</td>
            <td >技术要求</td>
        <?php else:?>
            <td colspan="4">技术要求</td>
        <?php endif;?>
    </tr>
    <?php
    $serial_number=$eleNum = $eleAmount = 0;
    foreach ($product[strtolower($product_type['paras_value'])] as $eval) :
        $eleNum += $eval['number'];
        $eleAmount += $eval['total_amount'];
        $serial_number=$serial_number+1;
        $eval['serial_number']=$serial_number;
    ?>
        <tr class="TableData">
            <td class="TableData"><?= $eval['serial_number'] ?></td>
            <td class="TableData"><?= $eval['name'] ?></td>
            <td class="TableData"><?= $eval['specification'] ?></td>
            <td class="TableData"><?= $eval['unit'] ?></td>
            <td class="TableData"><?= $eval['number'] ?></td>
            <td class="TableData"><?= $isHide?'':$eval['price'] ?></td>
            <?php if($product_type['user_bu']==$product_company||$product_type['paras_value']=='Other'):?>
                <td class="TableData"><?= $isHide?'':$eval['total_amount'] ?></td>
                <td class="TableData"><?= $other_company_product[$eval['other_company_product']] ?></td>
            <?php else:?>
                <td class="TableData"  colspan="2"><?= $isHide?'':$eval['total_amount'] ?></td>
            <?php endif;?>
            
            <?php if($product_type['paras_value']=='Electric'):?>
                <td class="TableData" colspan="2"><?= $eval['tech_requirement'] ?></td>
                <td class="TableData" colspan="2"><?= $product_brand[$eval['brand']]['paras_desc'] ?></td>
                <?php elseif($product_type['paras_value']=='Soft'):?>
                    <!-- TODO 慧软CRM合同评审 -->
                <td class="TableData" ><?= $license_product[$eval['license_product']] ?></td>
                <td class="TableData" ><?= $eval['license_years'] ?></td>
                <td class="TableData" ><?= $license_type[$eval['license_type']] ?></td>
                <td class="TableData" ><?= $eval['tech_requirement'] ?></td>
            <?php else:?>
                <td class="TableData" colspan="4"><?= $eval['tech_requirement'] ?></td>
            <?php endif;?>
            
        </tr>
    <?php endforeach; ?>
    <tr class="TableData">
        <td colspan="2">小计</td>
        <td></td>
        <td></td>
        <td class="TableData">
            <?= $eleNum ?>
            <!-- <input type="text" class="layui-input" name="sum_num" value="0" /> -->
        </td>
        <td></td>
        <td class="TableData">
            <?= $isHide?'':number_format($eleAmount, 4, '.', ''); ?>
            <!-- <input type="text" class="layui-input" name="sum_amount" value="0" /> -->
        </td>
        <td colspan="5"></td>
    </tr>
<?php endforeach;?>
</table>