<table id="pro_detail" width="100%" style="text-align: center;">
    <input type="hidden" id="product_detail_types" value='<?=json_encode($product_detail_types)?>'/>
    <? foreach($selectArr['product_detail_type'] as $product_type):?>
        <?if(($company=='INHENERGY'||$company=='HKNERGY')&&(!in_array($product_type['paras_value'],array('Inhenergy','Other')))){continue;}?>
        <tr>
            <td class="TableHeader" colspan="12"><?if($product_type[paras_value]=='Other'):?><?=$company_names[$company]?><?endif;?><?=$product_type[paras_desc]?></td>
        </tr>
        <?if($product_type['paras_value']=='Soft'):?>
            <tr >
            <td class="TableContent">合同有效期</td>
            <td class="TableContent">开始时间</td>
            <td class="TableData" colspan="2"> <input type="text" name="contract_begin_time" id="contract_begin_time" class="layui-input date" value="" lay-key="1"></td>
            <td class="TableContent">结束时间</td>
            <td class="TableData" colspan="2"> <input type="text" name="contract_end_time" id="contract_end_time" class="layui-input date" value="" lay-key="1"></td>
            <td class="TableContent">附件</td>
            <td class="TableData" colspan="3" align="left"><input id="soft_license_contract" type="button" class="layui-btn layui-bg-gray btn-upload" style="display: inline!important;" value="上传"></td>
            </tr>
            <!--TODO 慧软CRM合同评审 <tr>
                <td class="TableHeader" colspan="12">慧联软通产品授权明细</td>
            </tr>
            <tr class="TableContent">
                <td  colspan="">序号</td>
                <td  colspan="">授权产品</td>
                <td  colspan="3">授权年限</br>（单位：月，永久请填999）</td>
                <td  colspan="5">授权方式</td>
            </tr>
        <input type="hidden" id="addSoftLicenseProductDetailCount" name="addSoftLicenseProductDetailCount" value="0" />
        
        <tr align="left" id="addSoftLicenseProductDetail">
            <td class="TableData" colspan="12">
                <input value="添加" type=button class="layui-btn layui-btn-oa layui-btn-sm" onclick="addLicenseLine('addSoftLicenseProductDetail','SoftLicenseProduct');return false;" />
            </td>
        </tr>
        <tr >
            <td class="TableContent"  colspan="2">运维服务</td>
            <td class="TableData" colspan="2">
                <select name="om_services">
                <option  value="">请选择</option>
                <?foreach($license_service as $k=>$v) :?>
                    <option value="<?=$k?>"><?=$v?></option>
                <?endforeach;?>

            </select></td>
            <td class="TableContent">年限</td>
            <td class="TableData" colspan="2"> <input type="number" placeholder="填数字（单位：年）" name="om_services_years" id="om_services_years" class="layui-input" value="" ></td>
            <td class="TableContent" colspan="">是否包含授权产品</td>
            <td class="TableContent" colspan="3"> <select name="has_soft">
                <option value="">请选择</option>
                <?foreach($selectArr[yes_or_no] as $k=>$v) :?>
                    <option value="<?=$v[paras_value]?>"><?=$v[paras_desc]?></option>
                <?endforeach;?>

            </select></td>
        </tr>
        <tr>
            <td class="TableHeader" colspan="12">慧联软通产品明细</td>
        </tr> -->
        <?endif;?>
        <tr class="TableContent">
            <td>序号</td>
            <?php if($product_type['paras_value']=='Electric'):?>
                <td width="20%" style="color: red">
                    <b>名称应包含下列信息：<br />1、电表通讯模块 2、CIU及下行通讯方式</b>
                </td>
            <?php else:?>
                <td>名称</td>
            <?php endif;?>
            <td>规格</td>
            <td>单位</td>
            <td>数量</td>
            <td>单价</td>
            <?php if($product_type['user_bu']==$product_company||$product_type['paras_value']=='Other'):?>
                <td colspan="">总金额</td>
                <td colspan="">涉及其它公司产品作为配件</td>
            <?php else:?>
                <td colspan="2">总金额</td>
            <?php endif;?>
            <?php if($product_type['paras_value']=='Electric'):?>
                <td colspan="2">技术要求</td>
                <td colspan="2">产品品牌</td>
            <?php elseif($product_type['paras_value']=='Soft'):?>
                <!-- TODO 慧软CRM合同评审 -->
            <td>授权产品</td>
            <td>授权年限</br>（单位：月，永久请填999）</td>
            <td>授权方式</td>
            <td>技术要求</td>
            <?php else:?>
                <td colspan="4">技术要求</td>
            <?php endif;?>
            
        </tr>
        <input type="hidden" id="add<?=$product_type[paras_value]?>DetailCount" name="add<?=$product_type[paras_value]?>DetailCount" value="0" />
        
        <tr align="left" id="add<?=$product_type[paras_value]?>Detail">
            <td class="TableData" colspan="12">
                <input value="添加" type=button class="layui-btn layui-btn-oa layui-btn-sm" onclick="addLine('add<?=$product_type[paras_value]?>Detail','<?=$product_type[paras_value]?>','<?=$product_type[user_bu]?>');return false;" />
                <input value="导入" type="button"  class="layui-btn layui-btn-oa layui-btn-sm" onclick="$('#upload_<?=$product_type['paras_value']?>').click();return false;" />
                <input type="file" style="display: none!important;" productType="<?=$product_type[paras_value]?>"  id="upload_<?=$product_type['paras_value']?>" accept=".xls"  class="layui-btn layui-btn-oa layui-btn-sm" />
                <a href="#" onclick="window.location='./excel_templ/产品明细_<?=$product_type[paras_value]?>.xls'">导入模板下载</a>
                <input value="清空" type="button"  class="layui-btn layui-btn-oa layui-btn-sm" onclick="clear_product('<?=$product_type['paras_value']?>');return false;" />
                
            </td>
        </tr>
        <tr class="TableData">
            <td colspan="2">小计</td>
            <td></td>
            <td></td>
            <td><input type="text" class="layui-input" name="sum_num_<?=strtolower($product_type[paras_value])?>" value="0" readonly /></td>
            <td></td>
            <td><input type="text" class="layui-input" name="sum_amount_<?=strtolower($product_type[paras_value])?>" value="0" readonly /></td>
            <td colspan="4"></td>
        </tr>
    <?php endforeach;?>
</table>