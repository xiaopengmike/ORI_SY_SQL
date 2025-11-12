<table id="pro_detail" width="100%" style="text-align: center;">
        <tr>
            <td class="TableHeader" colspan="11">报价明细</td>
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
        <tbody id="lineBody">
            <input type="hidden" id="add<?='Quotation'?>DetailCount" name="add<?='Quotation'?>DetailCount" value="<?= count($product['quotation']) ?>"  />
            <?php
            $eleNum = $eleAmount = 0;
            foreach ($product['quotation'] as $eval) :
                $eleNum += $eval['number'];
                $eleAmount += $eval['count_price'];
            ?>
            <tr>
            <td class="TableData">
                        <input type="hidden" class="layui-input" name="serial_number_<?='quotation'?><?= $eval['serial_number'] ?>" data-type="<?='quotation'?>" value="<?= $eval['serial_number'] ?>" />
                        <?= $eval['serial_number'] ?>
                    </td>
                    <td class="TableData"><input type="text" class="layui-input" data-type="<?='quotation'?>" name="product_type_<?='quotation'?><?= $eval['serial_number'] ?>" value="<?= $eval['product_type'] ?>" /></td>
                    <td class="TableData"><input type="text" class="layui-input" data-type="<?='quotation'?>" name="product_name_<?='quotation'?><?= $eval['serial_number'] ?>" value="<?= $eval['product_name'] ?>" /></td>
                    <td class="TableData"><input type="text" class="layui-input" data-type="<?='quotation'?>" name="remarks_<?='quotation'?><?= $eval['serial_number'] ?>" value="<?= $eval['remarks'] ?>" /></td>
                    <td class="TableData"><input type="text" class="layui-input" data-type="<?='quotation'?>" name="cost_<?='quotation'?><?= $eval['serial_number'] ?>" value="<?= $eval['cost'] ?>" /></td>
                    <td class="TableData"><input type="text" class="layui-input" data-type="<?='quotation'?>" name="quotation_scheme_<?='quotation'?><?= $eval['serial_number'] ?>" value="<?= $eval['quotation_scheme'] ?>" /></td>
                    <td class="TableData"><input type="text" class="layui-input" data-type="<?='quotation'?>" id="quotation_price_<?='quotation'?>" name="quotation_price_<?='quotation'?><?= $eval['serial_number'] ?>" value="<?= $eval['quotation_price'] ?>" /></td>
                    <td class="TableData"><input type="text" class="layui-input" data-type="<?='quotation'?>" id="number_<?='quotation'?>" name="number_<?='quotation'?><?= $eval['serial_number'] ?>" value="<?= $eval['number'] ?>" /></td>
                    <td class="TableData"><input type="text" class="layui-input" data-type="<?='quotation'?>" id="count_price_<?='quotation'?>" name="count_price_<?='quotation'?><?= $eval['serial_number'] ?>" readonly value="<?= $eval['count_price'] ?>" /></td>
                    <td class="TableData"><input type="text" class="layui-input" data-type="<?='quotation'?>" name="proposal_price_<?='quotation'?><?= $eval['serial_number'] ?>"  value="<?= $eval['proposal_price'] ?>" />
                    <a href="javascript:;" onclick="removeLine(this)">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;删除</a>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
        <tr align="left" id="add<?='Quotation'?>Detail">
            <td class="TableData" colspan="11">
                <input value="添加" type=button class="layui-btn layui-btn-oa layui-btn-sm" onclick="addLine('add<?='Quotation'?>Detail','<?='Quotation'?>');return false;" />
            </td>
        </tr>
        <tr class="TableData">
            <td colspan="2">总计</td>
            <td colspan="6"><input type="hidden" class="layui-input" name="sum_num_quotation" value="<?= $eleNum ?>" readonly /></td>
            <td><input type="text" class="layui-input" name="sum_amount_quotation" value="<?= $eleAmount ?>" readonly /></td>
            <td></td>
        </tr>
</table>