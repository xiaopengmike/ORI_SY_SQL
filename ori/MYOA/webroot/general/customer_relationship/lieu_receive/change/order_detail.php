
<table id="tab3" width="100%" style="text-align: left;">
    
    <tr>
        <td class="TableHeader" colspan="<?= count($detailItem['non_lieu']) ?>" style="text-align:center">
        物料明细表
        </td>
    </tr>
    <tr class="TableContent" style="text-align: center;">
        <?php foreach ($detailItem['non_lieu'] as $itemOne) : ?>
            <td <?= $itemOne['nowrap'] ?> <?= $itemOne['class'] ?>><?= $itemOne['item_name'] ?></td>
        <?php endforeach; ?>
    </tr>
    <tbody id="non_lieu_NonLieu">
        <?php $colNum=0;foreach ($orderDetail['non_lieu'] as $dn => $dr) :?>
        <?php $colNum= $colNum + 1;  ?>
            <tr align="center">
                <?php $colCount = 0;
                foreach ($detailItem['non_lieu'] as $itemOne) : $colCount++; ?>
                    <td class="TableData">
                    <?=  $dr[$itemOne['item_id']] ?>
                        <?php if ($itemOne['have_attach']) { ?>
                            <input id="<?= $itemOne['item_id'].'_'."NonLieu" . $colNum ?>" type="button" class="layui-btn layui-bg-gray btn-upload" style="display:inline!important;" value="上传" />
                        <?php }  ?>
                        <?php if ($colCount == count($detailItem['non_lieu'])) { ?>
                            <a href="javascript:;" style="float:right" onclick="removeLine(this)">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;删除</a>
                        <?php } ?>
                        <span hidden>
                            <?php if ($itemOne['have_attach']) { ?>
                                <input id="<?= $itemOne['item_id'].'_'."NonLieu" . $colNum ?>" type="button" class="layui-btn layui-bg-gray btn-upload" style="display:inline!important;" value="上传" />
                            <?php } else if (!empty($itemOne['if_select'])) { ?>
                                <div class="layui-input-inline">
                                    <select id="<?= $itemOne['item_id'].'_'."NonLieu" . $colNum ?>" name="<?= $itemOne['item_id'].'_'."NonLieu" . $colNum ?>" lay-search>
                                        <option value=''>请选择</option>
                                        <?php foreach ($selectArr[$itemOne['if_select']] as $v) : ?>
                                            <option value="<?= $v['paras_value'] ?>" <?php if ($v['paras_value'] == $dr[$itemOne['item_id']]) : ?>selected<?php endif; ?>><?= $v['paras_desc'] ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            <?php } else if ($itemOne['item_id']=='non_serial_number') { ?>
                                <input type="text" name="<?= $itemOne['item_id'].'_'."NonLieu" . $colNum ?>" class="layui-input" value="<?= $colNum ?>" <?= $itemOne['class'] ?> />
                            <?php } else { ?>
                                <input type="text" name="<?= $itemOne['item_id'].'_'."NonLieu" . $colNum ?>" class="layui-input" value="<?= $dr[$itemOne['item_id']] ?>" <?= $itemOne['class'] ?> />
                            <?php } ?>
                        </sapn>
                        
                    </td>
                <?php endforeach; ?>
            </tr>
        <?php endforeach; ?>
    </tbody>
    <input type="hidden" id="addNonLieuCount" name="addNonLieuCount" value="<?= $colNum ?>" />
    <tr align="left" id="addNonLieu">
        <td class="TableData" colspan="7">
            <input value="添加" type=button class="layui-btn layui-btn-oa layui-btn-sm" onclick="addNonLieu();return false;" />
        </td>
    </tr>
</table>
<table id="tab4" width="100%" style="text-align: left;">
<tr>
        <td width="17%" class="TableContent">附件</td>
        <td class="TableData" colspan="3"> 
            <input id="lieu_attch" type="button" class="layui-btn layui-bg-gray btn-upload" style="display: inline!important;" value="上传" />
           
        </td>
    </tr>
    <tr>
        <td class="TableContent">其它需要说明的事项</td>
        <td class="TableData" colspan="3" align="left">
            <textarea class="layui-textarea TextDetails" id="other_explain" name="other_explain"><?= $main['other_explain'] ?></textarea>
        </td>
    </tr>
</table>