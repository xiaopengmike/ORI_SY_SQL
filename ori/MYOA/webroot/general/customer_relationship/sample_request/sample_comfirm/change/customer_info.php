<table id="tab11" width="100%" style="text-align: left;">
<tr>
        <td class=" TableContent">申请部门</td>
        <td class="TableData">
            <?= $request_main['deptName'] ?>
        </td>
        <td class=" TableContent">申请人</td>
        <td class="TableData">
            <?= $request_main['createUserName'] ?>
        </td>
    </tr>
    <tr>
        <td class=" TableContent">申请时间</td>
        <td class="TableData" >
            <?= $request_main['write_time'] ?>
        </td>
        <td class=" TableContent">流程类别</td>
        <td class="TableData">
            <?= $sample_request_material_flow[$request_main['material_flow']] ?>
        </td>
    </tr>
<tr>
        <td class="TableHeader" colspan="8" style="color: red;text-align:center">索样需求</td>
    </tr>
    <tr>
        <td class=" TableContent">所用项目</td>
        <td class="TableData">
            <?= $request_main['project_code'] ?>
        </td>
        <td class=" TableContent">物料类别</td>
        <td class="TableData">
        <?= $sample_request_material_type[$request_main['material_type']] ?>
        </td>
    </tr>
    <tr>
        <td class=" TableContent">是否开模</td>
        <td class="TableData" colspan="3">
           <?= $yes_or_no[$request_main['mold_opening']] ?>
        </td>
    </tr>
    <tr>
        <td class=" TableContent">物料名称</td>
        <td class="TableData">
            <?= $request_main['material_name'] ?>
        </td>
        <td class=" TableContent">样品数量</td>
        <td class="TableData">
            <?= $request_main['material_number'] ?>
        </td>
    </tr>
    <!-- <tr>
        <td class=" TableContent">规格型号</td>
        <td class="TableData" colspan="3">
            <pre><?= $request_main['m_specification_model'] ?></pre>
        </td>
    </tr> -->
    <tr>
        <td class=" TableContent">参数说明</td>
        <td class="TableData" colspan="3"></pre>
            <pre><?= $request_main['m_parameter_description'] ?>
        </td>
    </tr>
    <tr>
        <td class=" TableContent">打样说明</td>
        <pre><td class="TableData" colspan="3">
            <?= $request_main['m_drawing_number'] ?></pre>
        </td>
    </tr>
    <tr>
        <td width="17%" class=" TableContent">采购人员</td>
        <td class="TableData" colspan="3">
            <?= $main['createUserName'] ?>
        </td>
    </tr>
    <tr>
        <td class=" TableContent">推荐供应商</td>
        <td class="TableData">
            <?= $request_main['recommended_supplier'] ?>
        </td>
        <td class=" TableContent">需求到货时间</td>
        <td class="TableData">
            <?= $request_main['expected_arrival_time'] ?>
        </td>
</tr>
    <tr>
        <td width="17%" class="TableContent">打样图纸</td>
        <td class="TableData" colspan="3"> 
            <input id="excipients_attch" type="button" class="layui-btn layui-bg-gray btn-upload" style="display: none;" value="上传" readonly />  
        </td>
    </tr>
    <tr>
        <td class="TableContent" width="20%">其它需要说明的事项</td>
        <td class="TableData" colspan="3" >
            
            <pre><?= $request_main['other_explain'] ?></pre>
        </td>
    </tr>
    <tr>
        <td class=" TableContent">供应商数量</td>
    <td class="TableData" colspan="3">
            <?=$request_main['supplier_number']?>
        </td>
        </tr>
</table>
<table id="tab1" width="100%" style="text-align: center;">
<tr>
        <td class="TableHeader" colspan="8" style="color: red;text-align:center">索样确认</td>
    </tr>
    <!-- <tr>
        <td width="17%" class=" TableContent">采购人员</td>
        <td class="TableData" colspan="3">
            <?= $main['createUserName'] ?>
        </td>
    </tr> -->
    <tr>
        <td width="17%" class=" TableContent">供应商</td>
        <td class="TableData" colspan="">
            <input type="text" name="supplier" id="supplier" class="layui-input" value="<?= $main['supplier'] ?>" lay-verify="required"/>
        </td>
        <td class=" TableContent">预计价格</td>
        <td class="TableData">
            <input type="text" name="supplier_price" id="supplier_price" class="layui-input " value="<?= $main['supplier_price'] ?>" lay-verify="required"/>
        </td>
        <td class=" TableContent">预计回样时间</td>
        <td class="TableData">
            <input type="text" name="arrival_time" id="arrival_time" class="layui-input date" value="<?= $main['arrival_time'] ?>" lay-verify="required"/>
        </td>
        <td class=" TableContent">预计批量采购周期</td>
        <td class="TableData">
            <input type="text" name="procurement_cycle" id="procurement_cycle" class="layui-input " value="<?= $main['procurement_cycle'] ?>" lay-verify="required"/>
        </td>
    </tr>
</table>