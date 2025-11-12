<table id="tab4" width="100%" style="text-align: left;">
<tr>
        <td class="TableHeader" colspan="8" style="color: red;text-align:center">索样需求</td>
    </tr>
    <tr>
        <td class=" TableContent">所用项目</td>
        <td class="TableData">
        
        <input type="text" name="project_code" id="project_code" class="layui-input" value="<?= $main['project_code'] ?>" />
        </td>
        <td class=" TableContent">物料类别</td>
        <td class="TableData">
            <input type="text" name="material_type" id="material_type" class="layui-input" value="<?= $sample_request_material_type[$main[material_type]] ?>" />
        </td>
    </tr>
    <tr>
    <td class="TableContent">打样原因</td>
        <td class="TableData" colspan="3">
           <?if('01'==$main[m_specification_model]):?>新设计打样<?endif;?>
        <?if('02'==$main[m_specification_model]):?>原有设计变更<?endif;?>
        <?if('03'==$main[m_specification_model]):?><?=$main[other_explain]?><?endif;?>
        </td>
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
    <!-- <tr>
        <td class=" TableContent">规格型号</td>
        <td class="TableData" colspan="3">
        <textarea class="layui-textarea " id="m_specification_model"  name="m_specification_model" ><?= $main['m_specification_model'] ?></textarea>
        </td>
    </tr> -->
    <tr>
        <td class=" TableContent">参数说明</td>
        <td class="TableData" colspan="3">
        <textarea class="layui-textarea " id="m_parameter_description"  name="m_parameter_description" ><?= $main['m_parameter_description'] ?></textarea>
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
            <input type="text" name="expected_arrival_time" id="expected_arrival_time" class="layui-input" value="<?= $main['expected_arrival_time'] ?>" />
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
            
            <textarea class="layui-textarea " id="other_explain"  name="other_explain" ><?= $main['other_explain'] ?></textarea>
        </td>
    </tr> -->
    <?if(in_array('supplier_number',$editable_fields_arr[$approveInfo[work_flow]][editable_fields])&&$operate=='approve'):?>
    <tr  class="TableContent" style="text-align: center;">
        <td colspan="">供应商</td>
        <td colspan="">预计价格</td>
        <td colspan="">预计回样时间</td>
        <td colspan="">预计批量采购周期</td>
    </tr>
    
    <tr align="center">
        <td class="TableData" colspan=""><input value="" form-verify="required" type="text" class="layui-input required_field not_disabled" id="supplier_name" name="supplier_name_<?=1?>" /></td>
        <td class="TableData" colspan=""><input value="" form-verify="required" type="text" class="layui-input required_field not_disabled" id="supplier_price" name="supplier_price_<?=1?>" /></td>
        <td class="TableData" colspan=""><input value="" form-verify="required" type="text" class="layui-input required_field not_disabled " id="supplier_date" name="supplier_date_<?=1?>" /></td>
        <td class="TableData" colspan=""><input value="" form-verify="required" type="text" class="layui-input required_field not_disabled" id="procurement_cycle" name="procurement_cycle_<?=1?>" /></td>
        <!-- <td class="TableData" colspan="8"><textarea value="" class="layui-input not_disabled"  id="license_product_remark" name="license_product_remark_<?=1?>" ></textarea> -->
        <a href="javascript:;" onclick="removeLine(this)">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;删除</a>
        </td>
    </tr>

    <tr align="left" id="addLicenseProduct">
        <td class="TableData" colspan="10">
            <input value="添加" type=button class="layui-btn layui-btn-oa layui-btn-sm not_disabled" onclick="createLine('addLicenseProduct','LicenseProduct');return false;" />
            <input type="hidden" id="addLicenseProductCount" class="layui-input not_disabled" name="addLicenseProductCount" value="<?=1?>" />
        </td>
    </tr>
   <?endif;?>
 
    <tr>
        <!-- <td class=" TableContent">料号是否启用</td>
        <td class="TableData" colspan="">
            <div class="layui-input-inline">
            <input type="radio" class="layui-input <?if(in_array('part_number',$editable_fields_arr[$approveInfo[work_flow]][editable_fields])&&$operate=='approve'):?>not_disabled required_field<?endif;?>" <?if(in_array('part_number',$editable_fields_arr[$approveInfo[work_flow]][editable_fields])&&$operate=='approve'):?>lay-verify="radio_required"<?endif;?> name="pm_status" id="pm_status" value="01" title="是" <?if($main['pm_status']=="01"):?>checked="checked"<?endif;?>>
                <input type="radio" class="layui-input <?if(in_array('part_number',$editable_fields_arr[$approveInfo[work_flow]][editable_fields])&&$operate=='approve'):?>not_disabled required_field<?endif;?>" <?if(in_array('part_number',$editable_fields_arr[$approveInfo[work_flow]][editable_fields])&&$operate=='approve'):?>lay-verify="radio_required"<?endif;?> name="pm_status" id="pm_status" value="02" title="否" <?if($main['pm_status']=="02"):?>checked="checked"<?endif;?>>
        </div>
        </td> -->
        <td class=" TableContent">料号</td>
        <td class="TableData" colspan="3">
            
            <input placeholder="填写料号" class="layui-input <?if(in_array('part_number',$editable_fields_arr[$approveInfo[work_flow]][editable_fields])&&$operate=='approve'):?>not_disabled required_field<?endif;?>" <?if(in_array('part_number',$editable_fields_arr[$approveInfo[work_flow]][editable_fields])&&$operate=='approve'):?>lay-verify="required"<?endif;?> name="part_number" id="part_number" value="<?=$main['part_number']?>" >
        
        </td>
    </tr> 

    <!-- <tr>
        <td class=" TableContent">供应商数量</td>
        <td class="TableData" colspan="3">
            <div class="layui-input-inline">
            <input type="number" class="layui-input <?if(in_array('supplier_number',$editable_fields_arr[$approveInfo[work_flow]][editable_fields])&&$operate=='approve'):?>not_disabled required_field<?endif;?>" <?if(in_array('supplier_number',$editable_fields_arr[$approveInfo[work_flow]][editable_fields])&&$operate=='approve'):?>lay-verify="required"<?endif;?> name="supplier_number" id="supplier_number" value="<?=$main['supplier_number']?>" >
        </div>
        </td>
    </tr> -->
    
</table>

<script>
    // $(function(){

    //     $('#other_explain,#spare_requirement').each(function() {
    //         //$(this).css("display","none");
    //         if($(this).val()!=''){
    //             $(this).parent().html($(this).val().replace( /\n|\r|(\r\n)|(\u0085)|(\u2028)|(\u2029)/g,"<br>"));
    //         }
            

    //     });
    // })
</script>