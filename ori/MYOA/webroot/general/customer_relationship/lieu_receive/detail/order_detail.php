<table id="tab3" width="100%" style="text-align: left;">
    <tr>
        <td class="TableHeader" colspan="<?= count($detailItem['non_lieu']) ?>" style="text-align:center">
        物料明细表
        </td>
    </tr>
    <tr class="TableContent" style="text-align: center;">
        <?php foreach ($detailItem['non_lieu'] as $itemOne) : ?>
            <td  <?= $itemOne['class'] ?>><?= $itemOne['item_name'] ?></td>
        <?php endforeach; ?>
    </tr>
    <?php $colNum=0; foreach ($orderDetail['non_lieu'] as $dn => $dr) : if ($dn == 0) $dn = ''; ?>
        <?php  ++$colNum;  ?>
        <tr align="center">
            <?php foreach ($detailItem['non_lieu'] as $itemOne) : ?>
                <td class="TableData">
                    <?php if ($itemOne['have_attach']) { ?>
                        <input id="<?= $itemOne['item_id'] .'_'."NonLieu" . $dr["non_lieu_serial_number"] ?>" type="button" class="layui-btn layui-bg-gray btn-upload" style="display:none" value="上传" readonly />
                    <?php } else if (!empty($itemOne['if_select'])) { ?>
                        <?= ${$itemOne['if_select']}[$dr[$itemOne['item_id']]] ?>
                    <?php } else if ($itemOne['item_id']=='non_lieu_serial_number') { ?>
                        <?= $colNum ?>
                    <?php } else { ?>
                        <?= $dr[$itemOne['item_id']] ?>
                    <?php } ?>
                </td>
            <?php endforeach; ?>
        </tr>
    <?php endforeach; ?>
</table>
<table id="tab4" width="100%" style="text-align: left;">
<tr>
        <td width="17%" class="TableContent">附件</td>
        <td class="TableData" colspan="3"> 
            <input id="lieu_attch" type="button" class="layui-btn layui-bg-gray btn-upload" style="display: none;" value="上传" readonly />  
        </td>
    </tr>
    <tr>
        <td class="TableContent" width="20%">其它需要说明的事项</td>
        <td class="TableData" colspan="3" align="left">
            <textarea  class="layui-textarea TextDetails" id="other_explain" name="other_explain"><?= $main['other_explain'] ?></textarea>
            <!-- <?= $main['other_explain'] ?> -->
        </td>
    </tr>
    
    
</table>
<script>
    $(function(){

        $('#other_explain,#spare_requirement').each(function() {
            //$(this).css("display","none");
            if($(this).val()!=''){
                $(this).parent().html($(this).val().replace( /\n|\r|(\r\n)|(\u0085)|(\u2028)|(\u2029)/g,"<br>"));
            }
            

        });
    })
</script>