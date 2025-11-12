<table id="pro_detail" width="100%" style="text-align: center;">
<?if(!in_array($company,array('INHENERGY','HKNERGY','RETAILNERGY'))):?>
    <?if(!in_array($company,array('INHEGRIDIN'))):?>
    <tr>
        <td class="TableHeader" colspan="15">所发电表类产品明细(请选择项目编号，可带出对应的其他栏位信息）</td>
    </tr>
    <tr class="TableContent">
        <td width="6%">序号</td>
        <td width="8%">项目编号</td>
        <td>品名</td>
        <td>内部型号</td>
        <td width="8%">数量</td>
        <td width="8%">单价</td>
        <td width="8%">金额</td>
        
        <td width="8%">项目代号</td>
        <td width="28%" colspan="7">订单编号</td>
    </tr>
    <input type="hidden" id="addEleDetailCount" name="addEleDetailCount" value="<?= count($product['electric']); ?>" />
    <?php
    $eleNum = $eleAmount = $colCount = 0;
    foreach ($product['electric'] as $eval) :
        $eleNum += $eval['number'];
        $eleAmount += $eval['total_amount'];
        $colCount++;
        $eval['serial_number']=$colCount;
    ?>
        <tr class="TableData">
            <td class="TableData">
                <input type="hidden" class="layui-input" data-type="electric" id="serial_number_electric" name="serial_number_electric<?= $colCount ?>" value="<?= $eval['serial_number'] ?>" />
                <?= $eval['serial_number'] ?>
            </td>
            <td class="TableData">
                <input type="text" class="layui-input" data-type="electric" id="no_electric" name="no_electric<?= $colCount ?>" value="<?= $eval['project_number'] ?>" onclick="findProductList(this);return false;" />
            </td>
            <td class="TableData">
                <input type="text" class="layui-input" data-type="electric" id="name_electric" name="name_electric<?= $colCount ?>" value="<?= $eval['product_name'] ?>" />
            </td>
            <td class="TableData">
                <input type="text" class="layui-input" data-type="electric" id="specification_electric" name="specification_electric<?= $colCount ?>" value="<?= $eval['internal_model'] ?>" />
            </td>
            <td class="TableData">
                <input type="text" class="layui-input" data-type="electric" id="number_electric" name="number_electric<?= $colCount ?>" value="<?= $eval['number'] ?>" />
            </td>
            <td class="TableData">
                <input type="text" class="layui-input" data-type="electric" id="price_electric" name="price_electric<?= $colCount ?>" value="<?= $eval['price'] ?>" />
            </td>
            <td class="TableData">
                <input type="text" class="layui-input" data-type="electric" id="total_amount_electric" name="total_amount_electric<?= $colCount ?>" value="<?= $eval['total_amount'] ?>" />
            </td>
            
            <td class="TableData">
                <input type="text" class="layui-input" data-type="electric" id="code_electric" name="code_electric<?= $colCount ?>" value="<?= $eval['project_code'] ?>" />
            </td>
            <td class="TableData" colspan="7">
                <input type="text" class="layui-input" data-type="electric" id="order_no_electric" name="order_no_electric<?= $colCount ?>" value="<?= $eval['order_number'] ?>" />
                <a href="javascript:;" onclick="removeLine(this)">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;删除</a>
            </td>
        </tr>
    <?php endforeach; ?>
    <tr align="left" id="addEleDetail">
        <td class="TableData" colspan="15">
            <input value="添加" type=button class="layui-btn layui-btn-oa layui-btn-sm" onclick="addLine('addEleDetail');return false;" />
        </td>
    </tr>
    <tr class="TableData">
        <td colspan="2">小计</td>
        <td></td>
        <td></td>
        <td class="TableData">
            <input type="text" class="layui-input" id="all_number_electric" name="all_number_electric" value="<?= $eleNum ?>" readonly />
        </td>
        <td></td>
        <td class="TableData">
            <input type="text" class="layui-input" id="all_amount_electric" name="all_amount_electric" value="<?= number_format($eleAmount, 4, '.', ''); ?>" readonly />
        </td>
        <td></td>
       
        <td colspan="7"></td>
    </tr>

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
        <td>序号</td>
        <td>项目编号</td>
        <td>品名</td>
        <td>内部型号</td>
        <td>数量</td>
        <td>单价</td>
        <td>金额</td>
       
        <td>项目代号</td>
        <td>授权产品</td>
        <td>授权年限(单位:月 永久填999)</td>
        <td>授权方式</td>
        <td>申请授权key</td>
        <td>申请授权期限(单位:月 永久填999)</td>
        <td>期望授权码生效时间</td>
        <td>订单编号</td>
    </tr>
    <input type="hidden" id="addSoftDetailCount" name="addSoftDetailCount" value="<?= count($product['soft']); ?>" />
    <?php
    $softNum = $softAmount = $colCount = 0;
    foreach ($product['soft'] as $sVal) :
        $softNum += $sVal['number'];
        $softAmount += $sVal['total_amount'];
        $colCount++;
        $sVal['serial_number']=$colCount;
    ?>
        <tr class="TableData">
            <td class="TableData">
                <input type="hidden" class="layui-input" data-type="soft" id="serial_number_soft" name="serial_number_soft<?= $colCount ?>" value="<?= $sVal['serial_number'] ?>" />
                <?= $sVal['serial_number'] ?>
            </td>
            <td class="TableData">
                <input type="text" class="layui-input" data-type="soft" id="no_soft" name="no_soft<?= $colCount ?>" value="<?= $sVal['project_number'] ?>" onclick="findProductList(this);return false;" />
            </td>
            <td class="TableData">
                <input type="text" class="layui-input" data-type="soft" id="name_soft" name="name_soft<?= $colCount ?>" value="<?= $sVal['product_name'] ?>" />
            </td>
            <td class="TableData">
                <input type="text" class="layui-input" data-type="soft" id="specification_soft" name="specification_soft<?= $colCount ?>" value="<?= $sVal['internal_model'] ?>" />
            </td>
            <td class="TableData">
                <input type="text" class="layui-input" data-type="soft" id="number_soft" name="number_soft<?= $colCount ?>" value="<?= $sVal['number'] ?>" />
            </td>
            <td class="TableData">
                <input type="text" class="layui-input" data-type="soft" id="price_soft" name="price_soft<?= $colCount ?>" value="<?= $sVal['price'] ?>" />
            </td>
            <td class="TableData">
                <input type="text" class="layui-input" data-type="soft" id="total_amount_soft" name="total_amount_soft<?= $colCount ?>" value="<?= $sVal['total_amount'] ?>" />
            </td>
           
            <td class="TableData">
                <input type="text" class="layui-input" data-type="soft" id="code_soft" name="code_soft<?= $colCount ?>" value="<?= $sVal['project_code'] ?>" />
            </td>
            <td class="TableData">
            <select class="layui-input"  data-type="soft" id="license_product_soft" name="license_product_soft<?= $colCount ?>">
                <option value="">请选择</option>
                <?php
                foreach ($selectArr['license_product'] as $v) {
                    $selectstr="";
                    if($sVal['license_product']==$v['paras_value']){$selectstr="selected='selected'";}
                    echo '<option '.$selectstr.' value="' . $v['paras_value'] . '">' . $v['paras_desc'] . '</option>';
                }
                ?>
                </select>

            </td>
            <td class="TableData">
                <input type="text" class="layui-input" data-type="soft" id="license_years_soft" name="license_years_soft<?= $colCount ?>" value="<?= $sVal['license_years'] ?>" />
            </td>
            <td class="TableData">
            <select class="layui-input"  data-type="soft" id="license_type_soft" name="license_type_soft<?= $colCount ?>">
                <option value="">请选择</option>
                <?php
                foreach ($selectArr['license_type'] as $v) {
                    $selectstr="";
                    if($sVal['license_type']==$v['paras_value']){$selectstr='selected="selected"';}
                    echo '<option '.$selectstr.' value="' . $v['paras_value'] . '">' . $v['paras_desc'] . '</option>';
                }
                ?>
                </select>
            </td>
            <td class="TableData">
                <input type="text" class="layui-input" data-type="soft" id="license_key_soft" name="license_key_soft<?= $colCount ?>" value="<?= $sVal['license_key'] ?>" />
            </td>
            <td class="TableData">
                <input type="text" class="layui-input" data-type="soft" id="license_term_soft" name="license_term_soft<?= $colCount ?>" value="<?= $sVal['license_term'] ?>" />
            </td>
            <td class="TableData">
                <input type="text" class="layui-input date" data-type="soft" id="license_key_date_soft" name="license_key_date_soft<?= $colCount ?>" value="<?= $sVal['license_key_date']=="0000-00-00"?"":$sVal['license_key_date'] ?>" />
            </td>
            <td class="TableData" >
                <input type="text" class="layui-input" data-type="soft" id="order_no_soft" name="order_no_soft<?= $colCount ?>" value="<?= $sVal['order_number'] ?>" />
                <a href="javascript:;" onclick="removeLine(this)">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;删除</a>
            </td>
        </tr>
    <?php endforeach; ?>
    <tr align="left" id="addSoftDetail">
        <td class="TableData" colspan="15">
            <input value="添加" type=button class="layui-btn layui-btn-oa layui-btn-sm" onclick="addLine('addSoftDetail');return false;" />
        </td>
    </tr>
    <tr class="TableData">
        <td colspan="2">小计</td>
        <td></td>
        <td></td>
        <td class="TableData">
            <input type="text" class="layui-input" id="all_number_soft" name="all_number_soft" value="<?= $softNum ?>" readonly />
        </td>
        <td></td>
        <td class="TableData">
            <input type="text" class="layui-input" id="all_amount_soft" name="all_amount_soft" value="<?= number_format($softAmount, 4, '.', ''); ?>" readonly/>
        </td>
        <td></td>
        
        <td colspan="7"></td>
    </tr>
    <?endif;?>


    <tr>
        <td class="TableHeader" colspan="15">所发银河电网产品明细</td>
    </tr>
    <tr class="TableContent">
        <td>序号</td>
        <td>项目代号</td>
        <td>品名</td>
        <td>内部型号</td>
        <td>数量</td>
        <td>单价</td>
        <td>金额</td>
        <td>项目编号</td>
       
        <td colspan="7">订单编号</td>
    </tr>
    <input type="hidden" id="addInhegridDetailCount" name="addInhegridDetailCount" value="<?= count($product['inhegrid']); ?>" />
    <?php
    $inhegridNum = $inhegridAmount = $colCount = 0;
    foreach ($product['inhegrid'] as $oval) :
        $inhegridNum += $oval['number'];
        $inhegridAmount += $oval['total_amount'];
        $colCount++;
        $oval['serial_number']=$colCount;
    ?>
        <tr class="TableData">
            <td class="TableData">
                <input type="hidden" class="layui-input" data-type="inhegrid" id="serial_number_inhegrid" name="serial_number_inhegrid<?= $colCount ?>" value="<?= $oval['serial_number'] ?>" />
                <?= $oval['serial_number'] ?>
            </td>
            <td class="TableData">
                <input type="text" class="layui-input" data-type="inhegrid" id="no_inhegrid" name="no_inhegrid<?= $colCount ?>" value="<?= $oval['project_number'] ?>" onclick="findProductList(this);return false;" />
            </td>
            <td class="TableData">
                <input type="text" class="layui-input" data-type="inhegrid" id="name_inhegrid" name="name_inhegrid<?= $colCount ?>" value="<?= $oval['product_name'] ?>" />
            </td>
            <td class="TableData">
                <input type="text" class="layui-input" data-type="inhegrid" id="specification_inhegrid" name="specification_inhegrid<?= $colCount ?>" value="<?= $oval['internal_model'] ?>" />
            </td>
            <td class="TableData">
                <input type="text" class="layui-input" data-type="inhegrid" id="number_inhegrid" name="number_inhegrid<?= $colCount ?>" value="<?= $oval['number'] ?>" />
            </td>
            <td class="TableData">
                <input type="text" class="layui-input" data-type="inhegrid" id="price_inhegrid" name="price_inhegrid<?= $colCount ?>" value="<?= $oval['price'] ?>" />
            </td>
            <td class="TableData">
                <input type="text" class="layui-input" data-type="inhegrid" id="total_amount_inhegrid" name="total_amount_inhegrid<?= $colCount ?>" value="<?= $oval['total_amount'] ?>" />
            </td>
           
            <td class="TableData">
                <input type="text" class="layui-input" data-type="inhegrid" id="code_inhegrid" name="code_inhegrid<?= $colCount ?>" value="<?= $oval['project_code'] ?>" />
            </td>
            <td class="TableData" colspan="7">
                <input type="text" class="layui-input" data-type="inhegrid" id="order_no_inhegrid" name="order_no_inhegrid<?= $colCount ?>" value="<?= $oval['order_number'] ?>" />
                <a href="javascript:;" onclick="removeLine(this)">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;删除</a>
            </td>
        </tr>
    <?php endforeach; ?>
    <tr align="left" id="addInhegridDetail">
        <td class="TableData" colspan="15">
            <input value="添加" type=button class="layui-btn layui-btn-oa layui-btn-sm" onclick="addLine('addInhegridDetail');return false;" />
        </td>
    </tr>
    <tr class="TableData">
        <td colspan="2">小计</td>
        <td></td>
        <td></td>
        <td class="TableData">
            <input type="text" class="layui-input" id="all_number_inhegrid" name="all_number_inhegrid" value="<?= $inhegridNum ?>" readonly />
        </td>
        <td></td>
        <td class="TableData">
            <input type="text" class="layui-input" id="all_amount_inhegrid" name="all_amount_inhegrid" value="<?= number_format($inhegridAmount, 4, '.', ''); ?>" readonly />
        </td>
        <td></td>
        
        <td colspan="7"></td>
    </tr>
<?endif;?>

<?if(!in_array($company,array('INHENERGY','HKNERGY','RETAILNERGY','INHEGRIDIN'))):?>
    <tr>
        <td class="TableHeader" colspan="15">所发银河耐吉产品明细</td>
    </tr>
    <tr class="TableContent">
        <td>序号</td>
        <td>项目编号</td>
        <td>品名</td>
        <td>内部型号</td>
        <td>数量</td>
        <td>单价</td>
        <td>金额</td>
       
        <td>项目代号</td>
        <td colspan="7">订单编号</td>
    </tr>
    <input type="hidden" id="addInhenergyDetailCount" name="addInhenergyDetailCount" value="<?= count($product['inhenergy']); ?>" />
    <?php
    $inhenergyNum = $inhenergyAmount = $colCount = 0;
    foreach ($product['inhenergy'] as $oval) :
        $inhenergyNum += $oval['number'];
        $inhenergyAmount += $oval['total_amount'];
        $colCount++;
        $oval['serial_number']=$colCount;
    ?>
        <tr class="TableData">
            <td class="TableData">
                <input type="hidden" class="layui-input" data-type="inhenergy" id="serial_number_inhenergy" name="serial_number_inhenergy<?= $colCount ?>" value="<?= $oval['serial_number'] ?>" />
                <?= $oval['serial_number'] ?>
            </td>
            <td class="TableData">
                <input type="text" class="layui-input" data-type="inhenergy" id="no_inhenergy" name="no_inhenergy<?= $colCount ?>" value="<?= $oval['project_number'] ?>" onclick="findProductList(this);return false;" />
            </td>
            <td class="TableData">
                <input type="text" class="layui-input" data-type="inhenergy" id="name_inhenergy" name="name_inhenergy<?= $colCount ?>" value="<?= $oval['product_name'] ?>" />
            </td>
            <td class="TableData">
                <input type="text" class="layui-input" data-type="inhenergy" id="specification_inhenergy" name="specification_inhenergy<?= $colCount ?>" value="<?= $oval['internal_model'] ?>" />
            </td>
            <td class="TableData">
                <input type="text" class="layui-input" data-type="inhenergy" id="number_inhenergy" name="number_inhenergy<?= $colCount ?>" value="<?= $oval['number'] ?>" />
            </td>
            <td class="TableData">
                <input type="text" class="layui-input" data-type="inhenergy" id="price_inhenergy" name="price_inhenergy<?= $colCount ?>" value="<?= $oval['price'] ?>" />
            </td>
            <td class="TableData">
                <input type="text" class="layui-input" data-type="inhenergy" id="total_amount_inhenergy" name="total_amount_inhenergy<?= $colCount ?>" value="<?= $oval['total_amount'] ?>" />
            </td>
           
            <td class="TableData">
                <input type="text" class="layui-input" data-type="inhenergy" id="code_inhenergy" name="code_inhenergy<?= $colCount ?>" value="<?= $oval['project_code'] ?>" />
            </td>
            <td class="TableData" colspan="7">
                <input type="text" class="layui-input" data-type="inhenergy" id="order_no_inhenergy" name="order_no_inhenergy<?= $colCount ?>" value="<?= $oval['order_number'] ?>" />
                <a href="javascript:;" onclick="removeLine(this)">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;删除</a>
            </td>
        </tr>
    <?php endforeach; ?>
    <tr align="left" id="addInhenergyDetail">
        <td class="TableData" colspan="15">
            <input value="添加" type=button class="layui-btn layui-btn-oa layui-btn-sm" onclick="addLine('addInhenergyDetail');return false;" />
        </td>
    </tr>
    <tr class="TableData">
        <td colspan="2">小计</td>
        <td></td>
        <td></td>
        <td class="TableData">
            <input type="text" class="layui-input" id="all_number_inhenergy" name="all_number_inhenergy" value="<?= $inhenergyNum ?>" readonly />
        </td>
        <td></td>
        <td class="TableData">
            <input type="text" class="layui-input" id="all_amount_inhenergy" name="all_amount_inhenergy" value="<?= number_format($inhenergyAmount, 4, '.', ''); ?>" readonly />
        </td>
        <td></td>
        
        <td colspan="7"></td>
    </tr>
    <?endif;?>

    <?if(!in_array($company,array('INHENERGY','HKNERGY','RETAILNERGY','INHEGRID','INHEGRIDIN'))):?>
    <tr >
        <td class="TableHeader" colspan="15">所发银河成都产品明细</td>
    </tr>
    <tr class="TableContent" >
        <td>序号</td>
        <td>项目编号</td>
        <td>品名</td>
        <td>内部型号</td>
        <td>数量</td>
        <td>单价</td>
        <td>金额</td>
        <td>项目代号</td>
        <td colspan="7">订单编号</td>
    </tr>
    <input type="hidden" id="addCdinheDetailCount" name="addCdinheDetailCount" value="<?= count($product['cdinhe']); ?>" />
    <?php
    $cdinheNum = $cdinheAmount = $colCount = 0;
    foreach ($product['cdinhe'] as $oval) :
        $cdinheNum += $oval['number'];
        $cdinheAmount += $oval['total_amount'];
        $colCount++;
        $oval['serial_number']=$colCount;
    ?>
        <tr class="TableData">
            <td class="TableData">
                <input type="hidden" class="layui-input" data-type="cdinhe" id="serial_number_cdinhe" name="serial_number_cdinhe<?= $colCount ?>" value="<?= $oval['serial_number'] ?>" />
                <?= $oval['serial_number'] ?>
            </td>
            <td class="TableData">
                <input type="text" class="layui-input" data-type="cdinhe" id="no_cdinhe" name="no_cdinhe<?= $colCount ?>" value="<?= $oval['project_number'] ?>" onclick="findProductList(this);return false;" />
            </td>
            <td class="TableData">
                <input type="text" class="layui-input" data-type="cdinhe" id="name_cdinhe" name="name_cdinhe<?= $colCount ?>" value="<?= $oval['product_name'] ?>" />
            </td>
            <td class="TableData">
                <input type="text" class="layui-input" data-type="cdinhe" id="specification_cdinhe" name="specification_cdinhe<?= $colCount ?>" value="<?= $oval['internal_model'] ?>" />
            </td>
            <td class="TableData">
                <input type="text" class="layui-input" data-type="cdinhe" id="number_cdinhe" name="number_cdinhe<?= $colCount ?>" value="<?= $oval['number'] ?>" />
            </td>
            <td class="TableData">
                <input type="text" class="layui-input" data-type="cdinhe" id="price_cdinhe" name="price_cdinhe<?= $colCount ?>" value="<?= $oval['price'] ?>" />
            </td>
            <td class="TableData">
                <input type="text" class="layui-input" data-type="cdinhe" id="total_amount_cdinhe" name="total_amount_cdinhe<?= $colCount ?>" value="<?= $oval['total_amount'] ?>" />
            </td>
           
            <td class="TableData">
                <input type="text" class="layui-input" data-type="cdinhe" id="code_cdinhe" name="code_cdinhe<?= $colCount ?>" value="<?= $oval['project_code'] ?>" />
            </td>
            <td class="TableData" colspan="7">
                <input type="text" class="layui-input" data-type="cdinhe" id="order_no_cdinhe" name="order_no_cdinhe<?= $colCount ?>" value="<?= $oval['order_number'] ?>" />
                <a href="javascript:;" onclick="removeLine(this)">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;删除</a>
            </td>
        </tr>
    <?php endforeach; ?>
    <tr align="left" id="addCdinheDetail">
        <td class="TableData" colspan="15">
            <input value="添加" type=button class="layui-btn layui-btn-oa layui-btn-sm" onclick="addLine('addCdinheDetail');return false;" />
        </td>
    </tr>
    <tr class="TableData">
        <td colspan="2">小计</td>
        <td></td>
        <td></td>
        <td class="TableData">
            <input type="text" class="layui-input" id="all_number_cdinhe" name="all_number_cdinhe" value="<?= $cdinheNum ?>" readonly />
        </td>
        <td></td>
        <td class="TableData">
            <input type="text" class="layui-input" id="all_amount_cdinhe" name="all_amount_cdinhe" value="<?= number_format($cdinheAmount, 4, '.', ''); ?>" readonly />
        </td>
        <td></td>
        
        <td colspan="7"></td>
    </tr>
    <?endif;?>


    <tr>
        <td class="TableHeader" colspan="15">所发其他类产品明细</td>
    </tr>
    <tr class="TableContent">
        <td>序号</td>
        <td>项目编号</td>
        <td>品名</td>
        <td>内部型号</td>
        <td>数量</td>
        <td>单价</td>
        <td>金额</td>
        
        <td>项目代号</td>
        <td colspan="7">订单编号</td>
    </tr>
    <input type="hidden" id="addOtherDetailCount" name="addOtherDetailCount" value="<?= count($product['other']); ?>" />
    <?php
    $otherNum = $otherAmount = $colCount = 0;
    foreach ($product['other'] as $oval) :
        $otherNum += $oval['number'];
        $otherAmount += $oval['total_amount'];
        $colCount++;
        $oval['serial_number']=$colCount;
    ?>
        <tr class="TableData">
            <td class="TableData">
                <input type="hidden" class="layui-input" data-type="other" id="serial_number_other" name="serial_number_other<?= $colCount ?>" value="<?= $oval['serial_number'] ?>" />
                <?= $oval['serial_number'] ?>
            </td>
            <td class="TableData">
                <input type="text" class="layui-input" data-type="other" id="no_other" name="no_other<?= $colCount ?>" value="<?= $oval['project_number'] ?>" onclick="findProductList(this);return false;" />
            </td>
            <td class="TableData">
                <input type="text" class="layui-input" data-type="other" id="name_other" name="name_other<?= $colCount ?>" value="<?= $oval['product_name'] ?>" />
            </td>
            <td class="TableData">
                <input type="text" class="layui-input" data-type="other" id="specification_other" name="specification_other<?= $colCount ?>" value="<?= $oval['internal_model'] ?>" />
            </td>
            <td class="TableData">
                <input type="text" class="layui-input" data-type="other" id="number_other" name="number_other<?= $colCount ?>" value="<?= $oval['number'] ?>" />
            </td>
            <td class="TableData">
                <input type="text" class="layui-input" data-type="other" id="price_other" name="price_other<?= $colCount ?>" value="<?= $oval['price'] ?>" />
            </td>
            <td class="TableData">
                <input type="text" class="layui-input" data-type="other" id="total_amount_other" name="total_amount_other<?= $colCount ?>" value="<?= $oval['total_amount'] ?>" />
            </td>
           
            <td class="TableData">
                <input type="text" class="layui-input" data-type="other" id="code_other" name="code_other<?= $colCount ?>" value="<?= $oval['project_code'] ?>" />
            </td>
            <td class="TableData" colspan="7">
                <input type="text" class="layui-input" data-type="other" id="order_no_other" name="order_no_other<?= $colCount ?>" value="<?= $oval['order_number'] ?>" />
                <a href="javascript:;" onclick="removeLine(this)">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;删除</a>
            </td>
        </tr>
    <?php endforeach; ?>
    <tr align="left" id="addOtherDetail">
        <td class="TableData" colspan="15">
            <input value="添加" type=button class="layui-btn layui-btn-oa layui-btn-sm" onclick="addLine('addOtherDetail');return false;" />
        </td>
    </tr>
    <tr class="TableData">
        <td colspan="2">小计</td>
        <td></td>
        <td></td>
        <td class="TableData">
            <input type="text" class="layui-input" id="all_number_other" name="all_number_other" value="<?= $otherNum ?>" readonly />
        </td>
        <td></td>
        <td class="TableData">
            <input type="text" class="layui-input" id="all_amount_other" name="all_amount_other" value="<?= number_format($otherAmount, 4, '.', ''); ?>" readonly />
        </td>
        <td></td>
        
        <td colspan="7"></td>
    </tr>

    
</table>
<table id="pro_detail2" width="100%" style="text-align: center;">
    <tr>
        <td class="TableContent" width="15%">合计</td>
        <td class="TableData">
            <input type="text" class="layui-input" id="all_amount" name="all_amount" value="<?= number_format($eleAmount +$inhegridAmount+$inhenergyAmount+ $otherAmount + $softAmount+$cdinheAmount, 4, '.', ''); ?>" readonly />
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
            <input id="shipping_attch" type="button" class="layui-btn layui-bg-gray btn-upload" style="display: inline!important;" value="上传" />
        </td>
    </tr>
    
</table>