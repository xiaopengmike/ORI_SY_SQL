
<table id="pro_detail" width="100%" style="text-align: center;">
<?if(!in_array($company,array('INHENERGY','HKNERGY','RETAILNERGY'))):?>
    <?if(!in_array($company,array('INHEGRIDIN'))):?>
    <tr>
        <td class="TableHeader" colspan="15">所发电表类产品明细(请选择项目编号，可带出对应的其他栏位信息）</td>
    </tr>
    <tr class="TableContent">
        <td width="6%">序号</td>
        <td>项目编号</td>
        <td width="14%">品名</td>
        <td>内部型号</td>
        <td>数量</td>
        <td>单价</td>
        <td>金额</td>
        
        <td>项目代号</td>
        <td colspan="7">订单编号</td>
    </tr>
    <input type="hidden" id="addEleDetailCount" name="addEleDetailCount" value="1" />
    <tr class="TableData">
        
        <td><input type="hidden" class="layui-input" data-type="electric" id="serial_number_electric" name="serial_number_electric1" value="1" />1</td>
        <td><input type="text" class="layui-input mandatory" data-type="electric" id="no_electric" name="no_electric1" onclick="findProductList(this);return false;" /></td>
        <td><input type="text" class="layui-input" data-type="electric" id="name_electric" name="name_electric1" /></td>
        <td><input type="text" class="layui-input" data-type="electric" id="specification_electric" name="specification_electric1" /></td>
        <td><input type="text" class="layui-input" data-type="electric" id="number_electric" name="number_electric1" /></td>
        <td><input type="text" class="layui-input" data-type="electric" id="price_electric" name="price_electric1" /></td>
        <td><input type="text" class="layui-input" data-type="electric" id="total_amount_electric" name="total_amount_electric1" /></td>
        
        <td><input type="text" class="layui-input" data-type="electric" id="code_electric" name="code_electric1" /></td>
        <td colspan="7"><input type="text" class="layui-input" data-type="electric" id="order_no_electric" name="order_no_electric1" /> <a href="javascript:;" onclick="removeLine(this)">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;删除</a></td>
    </tr>
    <tbody id="addEleDetailBody"></tbody>
    <tr align="left" id="addEleDetail">
        <td class="TableData" colspan="15">
            <input value="添加" type=button class="layui-btn layui-btn-oa layui-btn-sm" onclick="addLine('addEleDetail');return false;" />
        </td>
    </tr>
    <tr class="TableData">
        <td colspan="2">小计</td>
        <td></td>
        <td class="TableData">
            <input type="text" class="layui-input" id="all_number_electric" name="all_number_electric" readonly />
        </td>
        <td></td>
        <td class="TableData">
            <input type="text" class="layui-input" id="all_amount_electric" name="all_amount_electric" readonly />
        </td>
        <td></td>
        <td></td>
        <td colspan="7"></td>
    </tr>
    <tr>
        <td class="TableHeader" colspan="15">软件类产品明细(含软件、软件相关服务、软件相关产品掌上机、短信猫等)</td>
    </tr>
    <tr >
        <td class="TableContent">合同有效期</td>
        <td class="TableContent">开始时间</td>
        <td class="TableData" colspan="5"> <input type="text" name="contract_begin_time" id="contract_begin_time" class="layui-input" value="" readonly></td>
        <td class="TableContent">结束时间</td>
        <td class="TableData" colspan="7"> <input type="text" name="contract_end_time" id="contract_end_time" class="layui-input " value="" readonly></td>
    </tr>
    <tr class="TableContent">
        
        <td width="6%">序号</td>
        <td>项目编号</td>
        <td width="14%">品名</td>
        <td>内部型号</td>
        <td>数量</td>
        <td>单价</td>
        <td>金额</td>
        <td>项目代号</td>
        <td>授权产品</td>
        <td>授权年限(单位:月 永久填999)</td>
        <td>授权方式</td>
        <td>申请授权KEY</td>
        <td>申请授权期限(单位:月 永久填999)</td>
        <td>期望授权码生效时间</td>
        <td>订单编号</td>
    </tr>
    <input type="hidden" id="addSoftDetailCount" name="addSoftDetailCount" value="1" />
    <tr class="TableData">
        <td><input type="hidden" class="layui-input" data-type="soft" id="serial_number_soft" name="serial_number_soft1" value="1" />1</td>
        <td><input type="text" class="layui-input mandatory" data-type="soft" id="no_soft" name="no_soft1" onclick="findProductList(this);return false;" /></td>
        <td><input type="text" class="layui-input" data-type="soft" id="name_soft" name="name_soft1" /></td>
        <td><input type="text" class="layui-input" data-type="soft" id="specification_soft" name="specification_soft1" /></td>
        <td><input type="text" class="layui-input" data-type="soft" id="number_soft" name="number_soft1" /></td>
        <td><input type="text" class="layui-input" data-type="soft" id="price_soft" name="price_soft1" /></td>
        <td><input type="text" class="layui-input" data-type="soft" id="total_amount_soft" name="total_amount_soft1" /></td>
        <td><input type="text" class="layui-input" data-type="soft" id="code_soft" name="code_soft1" /></td>
        <td>
        <select class="layui-input"  data-type="soft" id="license_product_soft" name="license_product_soft1">
        <option value="">请选择</option>
        <?php
        foreach ($selectArr['license_product'] as $v) {
            echo '<option value="' . $v['paras_value'] . '">' . $v['paras_desc'] . '</option>';
        }
        ?>
        </select>
    </td>
        <td><input type="text" class="layui-input" data-type="soft" id="license_years_soft" name="license_years_soft1" /></td>
        <td>
        <select class="layui-input"  data-type="soft" id="license_type_soft" name="license_type_soft1">
        <option value="">请选择</option>
        <?php
        foreach ($selectArr['license_type'] as $v) {
            echo '<option value="' . $v['paras_value'] . '">' . $v['paras_desc'] . '</option>';
        }
        ?>
        </select>
        </td>
        <td><input type="text" class="layui-input" data-type="soft" id="license_key" name="license_key1" /></td>
        <td><input type="text" class="layui-input" data-type="soft" id="license_term" name="license_term1" /></td>
        <td><input type="text" class="layui-input date" data-type="soft" id="license_key_date" name="license_key_date1" /></td>
        <td><input type="text" class="layui-input" data-type="soft" id="order_no_soft" name="order_no_soft1" /><a href="javascript:;" onclick="removeLine(this)">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;删除</a></td>
    </tr>
    <tbody id="addSoftDetailBody"></tbody>
    <tr align="left" id="addSoftDetail">
        <td class="TableData" colspan="16">
            <input value="添加" type=button class="layui-btn layui-btn-oa layui-btn-sm" onclick="addLine('addSoftDetail');return false;" />
        </td>
    </tr>
    <tr class="TableData">
        <td colspan="2">小计</td>
        <td></td>
        <td class="TableData">
            <input type="text" class="layui-input" id="all_number_soft" name="all_number_soft" readonly />
        </td>
        <td></td>
        <td class="TableData">
            <input type="text" class="layui-input" id="all_amount_soft" name="all_amount_soft" readonly />
        </td>
        <td></td>
        <td></td>
        <td colspan="7"></td>
    </tr>
    <?endif;?>

    <tr>
        <td class="TableHeader" colspan="15">所发银河电网产品明细</td>
    </tr>
    <tr class="TableContent">
        <td width="6%">序号</td>
        <td>项目编号</td>
        <td width="14%">品名</td>
        <td>内部型号</td>
        <td>数量</td>
        <td>单价</td>
        <td>金额</td>
        
        <td>项目代号</td>
        <td colspan="7">订单编号</td>
    </tr>
    <input type="hidden" id="addInhegridDetailCount" name="addInhegridDetailCount" value="1" />
    <tr class="TableData">
        
        <td><input type="hidden" class="layui-input" data-type="inhegrid" id="serial_number_inhegrid" name="serial_number_inhegrid1" value="1" />1</td>
        <td><input type="text" class="layui-input mandatory" data-type="inhegrid" id="no_inhegrid" name="no_inhegrid1" onclick="findProductList(this);return false;" /></td>
        <td><input type="text" class="layui-input" data-type="inhegrid" id="name_inhegrid" name="name_inhegrid1" /></td>
        <td><input type="text" class="layui-input" data-type="inhegrid" id="specification_inhegrid" name="specification_inhegrid1" /></td>
        <td><input type="text" class="layui-input" data-type="inhegrid" id="number_inhegrid" name="number_inhegrid1" /></td>
        <td><input type="text" class="layui-input" data-type="inhegrid" id="price_inhegrid" name="price_inhegrid1" /></td>
        <td><input type="text" class="layui-input" data-type="inhegrid" id="total_amount_inhegrid" name="total_amount_inhegrid1" /></td>
        
        <td><input type="text" class="layui-input" data-type="inhegrid" id="code_inhegrid" name="code_inhegrid1" /></td>
        <td colspan="7"><input type="text" class="layui-input" data-type="inhegrid" id="order_no_inhegrid" name="order_no_inhegrid1" /><a href="javascript:;" onclick="removeLine(this)">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;删除</a></td>
    </tr>
    <tbody id="addInhegridDetailBody"></tbody>
    <tr align="left" id="addInhegridDetail">
        <td class="TableData" colspan="15">
            <input value="添加" type=button class="layui-btn layui-btn-oa layui-btn-sm" onclick="addLine('addInhegridDetail');return false;" />
        </td>
    </tr>
    <tr class="TableData">
        <td colspan="2">小计</td>
        <td></td>
        <td class="TableData">
            <input type="text" class="layui-input" id="all_number_inhegrid" name="all_number_inhegrid" readonly />
        </td>
        <td></td>
        <td class="TableData">
            <input type="text" class="layui-input" id="all_amount_inhegrid" name="all_amount_inhegrid" readonly />
        </td>
        <td></td>
        <td></td>
        <td colspan="7"></td>
    </tr>
<?endif;?>

<?if(!in_array($company,array('INHEGRIDIN'))):?>
    <tr>
        <td class="TableHeader" colspan="15">所发银河耐吉产品明细</td>
    </tr>
    <tr class="TableContent">
        <td width="6%">序号</td>
        <td>项目编号</td>
        <td width="14%">品名</td>
        <td>内部型号</td>
        <td>数量</td>
        <td>单价</td>
        <td>金额</td>
        
        <td>项目代号</td>
        <td colspan="7">订单编号</td>
    </tr>
    <input type="hidden" id="addInhenergyDetailCount" name="addInhenergyDetailCount" value="1" />
    <tr class="TableData">
        
        <td><input type="hidden" class="layui-input" data-type="inhenergy" id="serial_number_inhenergy" name="serial_number_inhenergy1" value="1" />1</td>
        <td><input type="text" class="layui-input mandatory" data-type="inhenergy" id="no_inhenergy" name="no_inhenergy1" onclick="findProductList(this);return false;" /></td>
        <td><input type="text" class="layui-input" data-type="inhenergy" id="name_inhenergy" name="name_inhenergy1" /></td>
        <td><input type="text" class="layui-input" data-type="inhenergy" id="specification_inhenergy" name="specification_inhenergy1" /></td>
        <td><input type="text" class="layui-input" data-type="inhenergy" id="number_inhenergy" name="number_inhenergy1" /></td>
        <td><input type="text" class="layui-input" data-type="inhenergy" id="price_inhenergy" name="price_inhenergy1" /></td>
        <td><input type="text" class="layui-input" data-type="inhenergy" id="total_amount_inhenergy" name="total_amount_inhenergy1" /></td>
        
        <td><input type="text" class="layui-input" data-type="inhenergy" id="code_inhenergy" name="code_inhenergy1" /></td>
        <td colspan="7"><input type="text" class="layui-input" data-type="inhenergy" id="order_no_inhenergy" name="order_no_inhenergy1" /><a href="javascript:;" onclick="removeLine(this)">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;删除</a></td>
    </tr>
    <tbody id="addInhenergyDetailBody"></tbody>
    <tr align="left" id="addInhenergyDetail">
        <td class="TableData" colspan="15">
            <input value="添加" type=button class="layui-btn layui-btn-oa layui-btn-sm" onclick="addLine('addInhenergyDetail');return false;" />
        </td>
    </tr>
    <tr class="TableData">
        <td colspan="2">小计</td>
        <td></td>
        <td class="TableData">
            <input type="text" class="layui-input" id="all_number_inhenergy" name="all_number_inhenergy" readonly />
        </td>
        <td></td>
        <td class="TableData">
            <input type="text" class="layui-input" id="all_amount_inhenergy" name="all_amount_inhenergy" readonly />
        </td>
        <td></td>
        <td></td>
        <td colspan="7"></td>
    </tr>
<?endif;?>

    <?if(!in_array($company,array('INHENERGY','HKNERGY','RETAILNERGY','INHEGRID','INHEGRIDIN'))):?>
    <tr >
        <td class="TableHeader" colspan="15">所发银河成都产品明细</td>
    </tr>
    <tr class="TableContent" >
        <td width="6%">序号</td>
        <td>项目编号</td>
        <td width="14%">品名</td>
        <td>内部型号</td>
        <td>数量</td>
        <td>单价</td>
        <td>金额</td>
        
        <td>项目代号</td>
        <td colspan="7">订单编号</td>
    </tr>
    <input type="hidden" id="addCdinheDetailCount" name="addCdinheDetailCount" value="1" />
    <tr class="TableData" >
        
        <td><input type="hidden" class="layui-input" data-type="cdinhe" id="serial_number_cdinhe" name="serial_number_cdinhe1" value="1" />1</td>
        <td><input type="text" class="layui-input mandatory" data-type="cdinhe" id="no_cdinhe" name="no_cdinhe1" onclick="findProductList(this);return false;" /></td>
        <td><input type="text" class="layui-input" data-type="cdinhe" id="name_cdinhe" name="name_cdinhe1" /></td>
        <td><input type="text" class="layui-input" data-type="cdinhe" id="specification_cdinhe" name="specification_cdinhe1" /></td>
        <td><input type="text" class="layui-input" data-type="cdinhe" id="number_cdinhe" name="number_cdinhe1" /></td>
        <td><input type="text" class="layui-input" data-type="cdinhe" id="price_cdinhe" name="price_cdinhe1" /></td>
        <td><input type="text" class="layui-input" data-type="cdinhe" id="total_amount_cdinhe" name="total_amount_cdinhe1" /></td>
        
        <td><input type="text" class="layui-input" data-type="cdinhe" id="code_cdinhe" name="code_cdinhe1" /></td>
        <td colspan="7"><input type="text" class="layui-input" data-type="cdinhe" id="order_no_cdinhe" name="order_no_cdinhe1" /><a href="javascript:;" onclick="removeLine(this)">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;删除</a></td>
    </tr>
    <tbody id="addCdinheDetailBody"></tbody>
    <tr align="left" id="addCdinheDetail" >
        <td class="TableData" colspan="15">
            <input value="添加" type=button class="layui-btn layui-btn-oa layui-btn-sm" onclick="addLine('addCdinheDetail');return false;" />
        </td>
    </tr>
    <tr class="TableData" >
        <td colspan="2">小计</td>
        <td></td>
        <td class="TableData">
            <input type="text" class="layui-input" id="all_number_cdinhe" name="all_number_cdinhe" readonly />
        </td>
        <td></td>
        <td class="TableData">
            <input type="text" class="layui-input" id="all_amount_cdinhe" name="all_amount_cdinhe" readonly />
        </td>
        <td></td>
        <td></td>
        <td colspan="7"></td>
    </tr>
   <?endif;?>


    <tr>
        <td class="TableHeader" colspan="15">所发其他类产品明细</td>
    </tr>
    <tr class="TableContent">
        <td width="6%">序号</td>
        <td>项目编号</td>
        <td width="14%">品名</td>
        <td>内部型号</td>
        <td>数量</td>
        <td>单价</td>
        <td>金额</td>
        
        <td>项目代号</td>
        <td colspan="7">订单编号</td>
    </tr>
    <input type="hidden" id="addOtherDetailCount" name="addOtherDetailCount" value="1" />
    <tr class="TableData">
        
        <td><input type="hidden" class="layui-input" data-type="other" id="serial_number_other" name="serial_number_other1" value="1" />1</td>
        <td><input type="text" class="layui-input mandatory" data-type="other" id="no_other" name="no_other1" onclick="findProductList(this);return false;" /></td>
        <td><input type="text" class="layui-input" data-type="other" id="name_other" name="name_other1" /></td>
        <td><input type="text" class="layui-input" data-type="other" id="specification_other" name="specification_other1" /></td>
        <td><input type="text" class="layui-input" data-type="other" id="number_other" name="number_other1" /></td>
        <td><input type="text" class="layui-input" data-type="other" id="price_other" name="price_other1" /></td>
        <td><input type="text" class="layui-input" data-type="other" id="total_amount_other" name="total_amount_other1" /></td>
        
        <td><input type="text" class="layui-input" data-type="other" id="code_other" name="code_other1" /></td>
        <td colspan="7"><input type="text" class="layui-input" data-type="other" id="order_no_other" name="order_no_other1" /><a href="javascript:;" onclick="removeLine(this)">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;删除</a></td>
    </tr>
    <tbody id="addOtherDetailBody"></tbody>
    <tr align="left" id="addOtherDetail">
        <td class="TableData" colspan="15">
            <input value="添加" type=button class="layui-btn layui-btn-oa layui-btn-sm" onclick="addLine('addOtherDetail');return false;" />
        </td>
    </tr>
    <tr class="TableData">
        <td colspan="2">小计</td>
        <td></td>
        <td class="TableData">
            <input type="text" class="layui-input" id="all_number_other" name="all_number_other" readonly />
        </td>
        <td></td>
        <td class="TableData">
            <input type="text" class="layui-input" id="all_amount_other" name="all_amount_other" readonly />
        </td>
        <td></td>
        <td></td>
        <td colspan="7"></td>
    </tr>

    <tr>
        <td class=" TableContent" colspan="2">合计</td>
        <td class="TableData" colspan="13">
            <input type="text" class="layui-input" id="all_amount" name="all_amount" />
        </td>
    </tr>
    <tr>
        <td class=" TableContent" colspan="2">备注</td>
        <td class="TableData" colspan="13">
            <textarea class="layui-textarea" id="remark" name="remark"><?= $main['remark'] ?></textarea>
        </td>
    </tr>
    <tr>
        <td class=" TableContent" colspan="2">附件</td>
        <td class="TableData" colspan="13" align="left">
        <input id="shipping_attch" type="button" class="layui-btn layui-bg-gray btn-upload" style="display: inline!important;" value="上传" />
        </td>
    </tr>
</table>