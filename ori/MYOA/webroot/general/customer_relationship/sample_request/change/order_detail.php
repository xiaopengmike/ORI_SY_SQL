
<table id="tab4" width="100%" style="text-align: left;">
<tr>
        <td class="TableHeader" colspan="8" style="color: red;text-align:center">索样需求</td>
    </tr>
    <tr>
        <td class=" TableContent">所有项目</td>
        <td class="TableData">
            <input type="text" name="project_code" id="project_code" class="layui-input required_field" value="<?= $main['project_code'] ?>" lay-verify="required"/>
        </td>
        <td class=" TableContent">物料类别</td>
        <td class="TableData">
        <div class="layui-input-inline" id="material_type_div">
                <select id="material_type" name="material_type" lay-filter="material_type" lay-search lay-verify="param_required" >
                    <option value="">请选择</option>
                    <?php foreach ($selectArr['sample_request_material_type'] as $val) : ?>
                        <option value="<?= $val['paras_value'] ?>" <?if($val['paras_value']==$main[material_type]):?>selected="selected"<?endif;?>><?= $val['paras_desc'] ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
        </td>
    </tr>
    <tr>
    <td class="TableContent">打样原因</td>
        <td class="TableData" colspan="3">
            <input type="radio" name="m_specification_model" lay-skin="primary" class="class_one" title="新设计打样" value="01"  <?if('01'==$main[m_specification_model]):?>checked="checked"<?endif;?>>
            <input type="radio" name="m_specification_model" lay-skin="primary" class="class_one" title="原有设计变更" value="02"  <?if('02'==$main[m_specification_model]):?>checked="checked"<?endif;?>>
            <input type="radio" name="m_specification_model" lay-skin="primary" class="class_one" title="其它（说明）" value="03" <?if('03'==$main[m_specification_model]):?>checked="checked"<?endif;?>>
            <div class="layui-input-inline">
            <input type="text" class="class_one " name="other_explain" id="other_explain" class="layui-input" style="height:20px" value="<?=$main[other_explain]?>"  />
            </div>
        </td>
        </tr>  
    <tr>
        <td class=" TableContent">是否需要开模</td>
        <td class="TableData" colspan="3">
        <div class="layui-input-inline" >
                <select id="mold_opening" name="mold_opening" lay-search lay-verify="required" >
                    <option value="">请选择</option>
                    <?php foreach ($selectArr['yes_or_no'] as $val) : ?>
                        <option value="<?= $val['paras_value'] ?>" <?if($val['paras_value']==$main[mold_opening]):?>selected="selected"<?endif;?>><?= $val['paras_desc'] ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
        </td>
    </tr>
    <tr>
        <td class=" TableContent">物料名称</td>
        <td class="TableData">
            <input type="text" name="material_name" id="material_name" class="layui-input required_field" value="<?= $main['material_name'] ?>" lay-verify="required"/>
        </td>
        <td class=" TableContent">样品数量</td>
        <td class="TableData">
            <input type="text" name="material_number" id="material_number" class="layui-input required_field" value="<?= $main['material_number'] ?>"lay-verify="required" />
        </td>
    </tr>
    <!-- <tr>
        <td class=" TableContent">规格型号</td>
        <td class="TableData" colspan="3">
        <textarea class="layui-textarea " id="m_specification_model"  name="m_specification_model" lay-verify="required"><?= $main['m_specification_model'] ?></textarea>
        </td>
    </tr> -->
    <tr>
        <td class=" TableContent">参数说明</td>
        <td class="TableData" colspan="3">
        <textarea class="layui-textarea" id="m_parameter_description"  name="m_parameter_description" ><?= $main['m_parameter_description'] ?></textarea>
        </td>
    </tr>
    <tr>
        <td class=" TableContent">打样说明</td>
        <td class="TableData" colspan="3">
        <textarea class="layui-textarea " id="m_drawing_number"  name="m_drawing_number" ><?= $main['m_drawing_number'] ?></textarea>
        </td>
    </tr>
    <tr>
        <td class=" TableContent">推荐供应商</td>
        <td class="TableData">
            <input type="text" name="recommended_supplier" id="recommended_supplier" class="layui-input" value="<?= $main['recommended_supplier'] ?>" />
        </td>
        <td class=" TableContent">需求到货时间</td>
        <td class="TableData">
            <input type="text" name="expected_arrival_time" id="expected_arrival_time" class="layui-input date required_field" value="<?= $main['expected_arrival_time'] ?>" lay-verify="required"/>
        </td>
    </tr>
<tr>
        <td width="17%" class="TableContent">打样图纸</td>
        <td class="TableData" colspan="3"> 
            <input id="excipients_attch" type="button" class="layui-btn  btn-upload" style="display: inline!important;background-color:#0066cc;color:#fff" value="上传" />
           
        </td>
    </tr>

    <!-- <tr>
        <td class="TableContent">其它需要说明的事项</td>
        <td class="TableData" colspan="3" align="left">
            <textarea class="layui-textarea TextDetails" id="other_explain" name="other_explain"><?= $main['other_explain'] ?></textarea>
        </td>
    </tr> -->
</table>