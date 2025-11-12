
<table id="tab4" width="100%" style="text-align: left;">
<!-- <tr>
        <td class="TableHeader" colspan="8" style="color: red;text-align:center">索样需求</td>
    </tr>
    <tr>
        <td class=" TableContent">物料名称</td>
        <td class="TableData">
            <input type="text" name="material_name" id="material_name" class="layui-input" value="<?= $main['material_name'] ?>" />
        </td>
        <td class=" TableContent">样品数量</td>
        <td class="TableData">
            <input type="text" name="material_number" id="material_number" class="layui-input" value="<?= $main['material_number'] ?>" />
        </td>
    </tr>
    <tr>
        <td class=" TableContent">规格型号</td>
        <td class="TableData" colspan="3">
            <input type="text" name="m_specification_model" id="m_specification_model" class="layui-input" value="<?= $main['m_specification_model'] ?>" />
        </td>
    </tr>
    <tr>
        <td class=" TableContent">参数说明</td>
        <td class="TableData" colspan="3">
            <input type="text" name="m_parameter_description" id="m_parameter_description" class="layui-input" value="<?= $main['m_parameter_description'] ?>" />
        </td>
    </tr>
    <tr>
        <td class=" TableContent">图号</td>
        <td class="TableData" colspan="3">
            <input type="text" name="m_drawing_number" id="m_drawing_number" class="layui-input" value="<?= $main['m_drawing_number'] ?>" />
        </td>
    </tr>
    <tr>
        <td class=" TableContent">推荐供应商</td>
        <td class="TableData">
            <input type="text" name="recommended_supplier" id="recommended_supplier" class="layui-input" value="<?= $main['recommended_supplier'] ?>" />
        </td>
        <td class=" TableContent">预期到货时间</td>
        <td class="TableData">
            <input type="text" name="expected_arrival_time" id="expected_arrival_time" class="layui-input" value="<?= $main['expected_arrival_time'] ?>" />
        </td>
    </tr> -->
    <tr>
            <td width="17%" class="TableContent">附件</td>
            <td class="TableData" colspan="3"> <input id="spec_attch" type="button" class="layui-btn layui-bg-gray btn-upload" style="display: inline!important;" value="上传" lay-verify="required"/></td>
    </tr>
    <tr>
        <td class="TableContent">其它需要说明的事项</td>
        <td class="TableData" colspan="3">
            <textarea class="layui-textarea" id="other_explain" name="other_explain"></textarea>
        </td>
    </tr>
</table>