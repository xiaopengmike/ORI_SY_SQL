<table id="tab11" width="100%" style="text-align: left;">
<tr>
        <td  class=" TableContent">申请部门</td>
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
        <td class="TableData" colspan="3">
            <pre><?= $request_main['m_parameter_description'] ?></pre>
        </td>
    </tr>
    <tr>
        <td class=" TableContent">打样说明</td>
        <td class="TableData" colspan="3">
            <pre><?= $request_main['m_drawing_number'] ?></pre>
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
    <!-- <tr>
        <td class="TableContent" width="20%">其它需要说明的事项</td>
        <td class="TableData" colspan="3" >
            <pre><?= $request_main['other_explain'] ?></pre>
        </td>
    </tr> -->
    <tr>
        <td class=" TableContent">供应商数量</td>
    <td class="TableData" colspan="3">
            <?=$request_main['supplier_number']?>
        </td>
        </tr>
</table>
<?php 
    include_once("../templet/detail_".$request_main[material_type]."_".$request_main[param_templ_v].".php");
?>
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
            <input type="text" name="supplier" id="supplier" class="layui-input" value="<?= $main['supplier'] ?>" />
        </td>
        <td class=" TableContent">预计价格</td>
        <td class="TableData">
            <input type="text" name="supplier_price" id="supplier_price" class="layui-input" value="<?= in_array($_SESSION[LOGIN_USER_ID],$whitelist_perm)?$main['supplier_price']:"" ?>" />
        </td>
        <td class=" TableContent">预计回样时间</td>
        <td class="TableData">
            <input type="text" name="arrival_time" id="arrival_time" class="layui-input" value="<?= $main['arrival_time'] ?>" />
        </td>
        <td class=" TableContent">预计批量采购周期</td>
        <td class="TableData">
            <input type="text" name="procurement_cycle" id="procurement_cycle" class="layui-input" value="<?= $main['procurement_cycle'] ?>" />
        </td>
    </tr>
    <tr>
        <td width="17%" class=" TableContent">预计回样时间（回样填写）</td>
        <td class="TableData" colspan="3">
            <input type="text" name="sample_projected_time" id="sample_projected_time" class="layui-input  <?if(in_array('sample_projected_time',$editable_fields_arr[$approveInfo[work_flow]][editable_fields])&&$operate=='approve'):?>date not_disabled required_field<?endif;?>" value="<?= $main['sample_projected_time'] ?>" <?if(in_array('sample_projected_time',$editable_fields_arr[$approveInfo[work_flow]][editable_fields])&&$operate=='approve'):?>lay-verify="required"<?endif;?>/>
        </td>
        <td class=" TableContent">实际回样时间（回样确认填写）</td>
        <td class="TableData" colspan="3">
            <input type="text" name="sample_actual_time" id="sample_actual_time" class="layui-input <?if(in_array('sample_actual_time',$editable_fields_arr[$approveInfo[work_flow]][editable_fields])&&$operate=='approve'):?>date not_disabled required_field<?endif;?>" value="<?= $main['sample_actual_time'] ?>" <?if(in_array('sample_actual_time',$editable_fields_arr[$approveInfo[work_flow]][editable_fields])&&$operate=='approve'):?>lay-verify="required"<?endif;?>/>
        </td>
    </tr>
    <tr>
        <td width="17%" class=" TableContent">预计开模完成时间(开模填写)</td>
        <td class="TableData" colspan="3">
            <input type="text" name="mold_opening_time" id="mold_opening_time" class="layui-input  <?if(in_array('mold_opening_time',$editable_fields_arr[$approveInfo[work_flow]][editable_fields])&&$operate=='approve'):?>date not_disabled required_field<?endif;?>" value="<?= $main['mold_opening_time'] ?>" <?if(in_array('mold_opening_time',$editable_fields_arr[$approveInfo[work_flow]][editable_fields])&&$operate=='approve'):?>lay-verify="required"<?endif;?>/>
        </td>
        <td class=" TableContent">预计开模回样时间(开模填写)</td>
        <td class="TableData" colspan="3">
            <input type="text" name="sample_return_time" id="sample_return_time" class="layui-input <?if(in_array('mold_opening_time',$editable_fields_arr[$approveInfo[work_flow]][editable_fields])&&$operate=='approve'):?>date not_disabled required_field<?endif;?>" value="<?= $main['sample_return_time'] ?>" <?if(in_array('mold_opening_time',$editable_fields_arr[$approveInfo[work_flow]][editable_fields])&&$operate=='approve'):?>lay-verify="required"<?endif;?>/>
        </td>
    </tr>
    <tr>
        <td width="17%" class=" TableContent">实际开模完成时间(开模回样填写)</td>
        <td class="TableData" colspan="3">
            <input type="text" name="actual_opening_time" id="actual_opening_time" class="layui-input  <?if(in_array('actual_opening_time',$editable_fields_arr[$approveInfo[work_flow]][editable_fields])&&$operate=='approve'):?>date not_disabled required_field<?endif;?>" value="<?= $main['actual_opening_time'] ?>" <?if(in_array('actual_opening_time',$editable_fields_arr[$approveInfo[work_flow]][editable_fields])&&$operate=='approve'):?>lay-verify="required"<?endif;?>/>
        </td>
        <td width="17%" class=" TableContent">实际开模回样时间(开模回样填写)</td>
        <td class="TableData" colspan="3">
            <input type="text" name="actual_return_time" id="actual_return_time" class="layui-input  <?if(in_array('actual_return_time',$editable_fields_arr[$approveInfo[work_flow]][editable_fields])&&$operate=='approve'):?>date not_disabled required_field<?endif;?>" value="<?= $main['actual_return_time'] ?>" <?if(in_array('actual_return_time',$editable_fields_arr[$approveInfo[work_flow]][editable_fields])&&$operate=='approve'):?>lay-verify="required"<?endif;?>/>
        </td>
            
        </td>
    </tr>
    <tr>
        <td width="17%" class=" TableContent">流程结束填写</td>
        <td class="TableData" colspan="3" >
        <?if(in_array('end_type',$editable_fields_arr[$approveInfo[work_flow]][editable_fields])&&$operate=='approve'):?>
            <div class="layui-input-inline" style="float:left">
                <select id="end_type" name="end_type" lay-filter="end_type" class="not_disabled required_field" lay-search lay-verify="param_required">
                    <option value="">请选择</option>
                    <?php foreach ($selectArr['sample_comfirm_end_type'] as $val) : ?>
                        <option value="<?= $val['paras_value'] ?>" <?if($val['paras_value']==$main[end_type]):?>selected="selected"<?endif;?>><?= $val['paras_desc'] ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <?else:?>
                <input type="text"  class="layui-input" value="<?=$sample_comfirm_end_type[$main[end_type]]?>" />
            <?endif;?>
           
        </td>
        <td class="TableData" colspan="4">
            <input placeholder="启用填写料号，不启用填写原因" type="text" name="end_remark" id="end_remark" class="layui-input  <?if(in_array('end_remark',$editable_fields_arr[$approveInfo[work_flow]][editable_fields])&&$operate=='approve'):?> not_disabled required_field<?endif;?>" value="<?= $main['end_remark'] ?>" <?if(in_array('end_remark',$editable_fields_arr[$approveInfo[work_flow]][editable_fields])&&$operate=='approve'):?>lay-verify="required"<?endif;?>/>
        </td>
            
        </td>
    </tr>
</table>