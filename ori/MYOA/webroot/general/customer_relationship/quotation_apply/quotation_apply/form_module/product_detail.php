<table id="pro_detail" width="100%" style="text-align: center;">
    <input type="hidden" id="product_detail_types" value='<?=json_encode($product_detail_types)?>'/>
        <tr>
            <td class="TableHeader" colspan="10">报价明细</td>
        </tr>
        <tr class="TableContent">
            <td>序号</td>
            <td>软件、设备或服务</td>
            <td>产品名称</td>
            <td>备注（软件许可类型，硬件参数，服务要求）</td>
            <td>BOM含税成本(RMB)</td>
            <td>报价方案</td>
            <td>EXW报价</td>
            <td>数量</td>
            <td>合计</td>
            <td>业务建议价格</td>
        </tr>
        <input type="hidden" id="add<?='Quotation'?>DetailCount" name="add<?='Quotation'?>DetailCount" value="0" />
        
        <tr align="left" id="add<?='Quotation'?>Detail">
            <td class="TableData" colspan="10">
                <input value="添加" type=button class="layui-btn layui-btn-oa layui-btn-sm" onclick="addLine('add<?='Quotation'?>Detail','<?='Quotation'?>');return false;" />
            </td>
        </tr>
        <tr class="TableData">
            <td colspan="2">总计</td>
            <td colspan="6"><input type="hidden" class="layui-input" name="sum_num_quotation" value="0" readonly /></td>
            <td><input type="text" class="layui-input" name="sum_amount_quotation" value="0" readonly /></td>
            <td></td>
        </tr>
</table>