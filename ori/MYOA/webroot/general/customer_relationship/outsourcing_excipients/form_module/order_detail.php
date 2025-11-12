
<table id="tab3" width="100%" style="text-align: left;">
    <input type="hidden" id="addNonExcipientsCount" name="addNonExcipientsCount" value="0" />
    <tr>
        <td class="TableHeader" colspan="<?= count($detailItem['non_excipients']) ?>" style="text-align:center">物料明细表</td>
    </tr>
    <tr class="TableContent" style="text-align: center;">
        <?php foreach ($detailItem['non_excipients'] as $val) : ?>
            <td <?= $val['class'] ?>><?= $val['item_name'] ?></td>
        <?php endforeach; ?>
    </tr>
    <tbody id="non_excipients_NonExcipients"></tbody>
    <tr align="left" id="addNonExcipients">
        <td class="TableData" colspan="7">
            <input value="添加" type=button class="layui-btn layui-btn-oa layui-btn-sm" onclick="addLine('addNonExcipients');return false;" />
        </td>
    </tr>
</table>
<table id="tab4" width="100%" style="text-align: left;">
            <tr>
                <td width="17%" class="TableContent">附件</td>
                <td class="TableData" colspan="3"> <input id="excipients_attch" type="button" class="layui-btn layui-bg-gray btn-upload" style="display: inline!important;" value="上传" /></td>
        </tr>
    <tr>
        <td class="TableContent">其它需要说明的事项</td>
        <td class="TableData" colspan="3">
            <textarea class="layui-textarea" id="other_explain" name="other_explain"></textarea>
        </td>
    </tr>
</table>