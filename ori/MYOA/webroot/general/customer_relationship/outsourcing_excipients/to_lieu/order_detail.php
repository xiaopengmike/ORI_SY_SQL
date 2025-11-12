<?if($outexcipients_lieu):?>
<table id="tab3"  class="TableBlock" style="width: 80%;margin:auto;">
    <tr><td colspan="8" style="text-align: center;" class="TableHeader">转代收物料待处理</td></tr>
    <tr class="TableContent" id="tabHead" style="text-align: center;">
        <td nowrap style="text-align: center;width: 5%;"> <input  type="checkbox" id="check-all" name="check-all"  ></td>
        <td nowrap style="text-align: center;"> 状态</td>
        <td nowrap style="text-align: center;"> 编号</td>
        <?php foreach ($detailItem['non_excipients'] as $itemOne) : ?>
            <td nowrap style="text-align: center;" <?= $itemOne['class'] ?>><?= $itemOne['item_name'] ?></td>
        <?php endforeach; ?>
    </tr>
    <tbody id="tabBody" name="tabBody" class="TableContent">
    <!-- $orderDetail['non_excipients'] -->
    <?php $colNum=0; foreach ($outexcipients_lieu as $dn => $dr) : if ($dn == 0) $dn = ''; ?>
        <?php  ++$colNum;  ?>
        <tr align="center" class="TableLine<?= $line % 2 == 0 ? 1 : 2 ?>">
        
            <td align="center" ><input  type="checkbox" id="<?= $dr["id"] ?>" name="cb_list"  <?php if (in_array($dr['id'], $checkedArr)) { ?>checked<?php } ?>>
        
        </td>
        <td class="TableData"><?= $dr["status"]?></td>
        <td class="TableData"><?= $dr["id"]?></td>
            <?php foreach ($detailItem['non_excipients'] as $itemOne) : ?>
                <td class="TableData">
                    <?php if ($itemOne['have_attach']) { ?>
                        <input id="<?= $itemOne['item_id'] .'_'."NonExcipients" . $dr["non_excipients_serial_number"] ?>" type="button" class="layui-btn layui-bg-gray btn-upload" style="display:none" value="上传" readonly />
                    <?php } else if (!empty($itemOne['if_select'])) { ?>
                        <?= ${$itemOne['if_select']}[$dr[$itemOne['item_id']]] ?>
                    <?php } else if ($itemOne['item_id']=='non_excipients_serial_number') { ?>
                        <?= $colNum ?>
                    <?php } else { ?>
                        <?= $dr[$itemOne['item_id']] ?>
                    <?php } ?>
                </td>
            <?php endforeach; ?>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>
<br/>
<?endif;?>
<?if($outexcipients_lieuing):?>
<table id="tab4"  class="TableBlock" style="width: 80%;margin:auto;">
    <tr><td colspan="8" style="text-align: center;" class="TableHeader">转代收审核中物料</td></tr>
    <tr class="TableContent" id="tabHead" style="text-align: center;">
        <!-- <td nowrap style="text-align: center;width: 5%;"></td> -->
        <td nowrap style="text-align: center;"> 状态</td>
        <td nowrap style="text-align: center;"> 编号</td>
        <?php foreach ($detailItem['non_excipients'] as $itemOne) : ?>
            <td nowrap style="text-align: center;" <?= $itemOne['class'] ?>><?= $itemOne['item_name'] ?></td>
        <?php endforeach; ?>
    </tr>
    <tbody id="tabBody" name="tabBody" class="TableContent">
    <!-- $orderDetail['non_excipients'] -->
    <?php $colNum=0; foreach ($outexcipients_lieuing as $dn => $dr) : if ($dn == 0) $dn = ''; ?>
        <?php  ++$colNum;  ?>
        <tr align="center" class="TableLine<?= $line % 2 == 0 ? 1 : 2 ?>">
        
            <!-- <td align="center" ></td> -->
        <td class="TableData"><?= $dr["status"]?></td>
        <td class="TableData"><?= $dr["id"]?></td>
            <?php foreach ($detailItem['non_excipients'] as $itemOne) : ?>
                <td class="TableData">
                    <?php if ($itemOne['have_attach']) { ?>
                        <input id="<?= $itemOne['item_id'] .'_'."NonExcipients" . $dr["non_excipients_serial_number"] ?>" type="button" class="layui-btn layui-bg-gray btn-upload" style="display:none" value="上传" readonly />
                    <?php } else if (!empty($itemOne['if_select'])) { ?>
                        <?= ${$itemOne['if_select']}[$dr[$itemOne['item_id']]] ?>
                    <?php } else if ($itemOne['item_id']=='non_excipients_serial_number') { ?>
                        <?= $colNum ?>
                    <?php } else { ?>
                        <?= $dr[$itemOne['item_id']] ?>
                    <?php } ?>
                </td>
            <?php endforeach; ?>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>
<br/>
<?endif;?>
<?if($outexcipients_lieued):?>
<table id="tab5"  class="TableBlock" style="width: 80%;margin:auto;">
    <tr><td colspan="8" style="text-align: center;" class="TableHeader">转代收已审核物料</td></tr>
    <tr class="TableContent" id="tabHead" style="text-align: center;">
        <!-- <td nowrap style="text-align: center;width: 5%;"> </td> -->
        <td nowrap style="text-align: center;"> 状态</td>
        <td nowrap style="text-align: center;"> 编号</td>
        <?php foreach ($detailItem['non_excipients'] as $itemOne) : ?>
            <td nowrap style="text-align: center;" <?= $itemOne['class'] ?>><?= $itemOne['item_name'] ?></td>
        <?php endforeach; ?>
    </tr>
    <tbody id="tabBody" name="tabBody" class="TableContent">
    <!-- $orderDetail['non_excipients'] -->
    <?php $colNum=0; foreach ($outexcipients_lieued as $dn => $dr) : if ($dn == 0) $dn = ''; ?>
        <?php  ++$colNum;  ?>
        <tr align="center" class="TableLine<?= $line % 2 == 0 ? 1 : 2 ?>">
        
            <!-- <td align="center" ></td> -->
        <td class="TableData"><?= $dr["status"]?></td>
        <td class="TableData"><?= $dr["id"]?></td>
            <?php foreach ($detailItem['non_excipients'] as $itemOne) : ?>
                <td class="TableData">
                    <?php if ($itemOne['have_attach']) { ?>
                        <input id="<?= $itemOne['item_id'] .'_'."NonExcipients" . $dr["non_excipients_serial_number"] ?>" type="button" class="layui-btn layui-bg-gray btn-upload" style="display:none" value="上传" readonly />
                    <?php } else if (!empty($itemOne['if_select'])) { ?>
                        <?= ${$itemOne['if_select']}[$dr[$itemOne['item_id']]] ?>
                    <?php } else if ($itemOne['item_id']=='non_excipients_serial_number') { ?>
                        <?= $colNum ?>
                    <?php } else { ?>
                        <?= $dr[$itemOne['item_id']] ?>
                    <?php } ?>
                </td>
            <?php endforeach; ?>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>
<br/>
<?endif;?>
<br/>
<br/>
<?if($lieu_data):?>
<table id="tab5"  class="TableBlock" style="width: 80%;margin:auto;">
    <tr><td colspan="8" style="text-align: center;" class="TableHeader">代收货订单</td></tr>
    <tr class="TableContent" id="tabHead" style="text-align: center;">
        <td nowrap style="text-align: center;">流程单号</td>
        <td nowrap style="text-align: center;">仓库</td>
        <td nowrap style="text-align: center;">制单人</td>
        <td nowrap style="text-align: center;">制单时间</td>
        <td nowrap style="text-align: center;">办理状态</td>
        <td nowrap style="text-align: center;">操作</td>
    </tr>
    <tbody id="tabBody" name="tabBody" class="TableContent">
    <?php $colNum=0; foreach ($lieu_data as $dn => $val) : if ($dn == 0) $dn = ''; ?>
        <?php  ++$colNum;  ?>
        <tr align="center" class="TableLine<?= $line % 2 == 0 ? 1 : 2 ?>">
        <td class="TableData"><?= $val["form_id"]?></td>
        <td align="center"><?= $val["company_name"] ?></td>
        <td class="TableData"><?= $val["deptName"].$dr["createUserName"]?></td>
        <td class="TableData"><?= $val["write_time"]?></td>
        <td class="TableData"><font class="<?= SystemEnum::$STATUS_CSS[$val["status"]] ?>"><?= SystemEnum::$STATUS[$val["status"]] ?></font></td>
        <td class="TableData">
        <a href="javascript:void(0);" class="newColor" onclick="pageOperate('../lieu_receive/detail.php?id=<?= $val[form_id] ?>&operate=detail','detail');return false;">查阅</a>&nbsp;
                        <?php if ($val["status"] != 'T') : ?>
                            <a href="javascript:void(0);" class="newColor" onclick="pageOperate('../common/list/process_schedule.php?id=<?= $val[form_id] ?>','smallModel');return false;">查看进度</a>&nbsp;
                        <?php endif; ?>
                        <?php if (($userId == $val['user_id'] || $isAdmin) && $val["status"] == 'T' || $val["status"] == 'N') : ?>
                            <a href="javascript:void(0);" class="newColor" onclick="pageOperate('../lieu_receive/modify.php?id=<?= $val[form_id] ?>','modify');return false;">修改</a>&nbsp;
                        <?php endif; ?>
                        <?php if (($userId == $val['approve_user']) && ($val["status"] == 'P')) : ?>
                            <a href="javascript:void(0);" class="newColor" onclick="pageOperate('../lieu_receive/detail.php?id=<?= $val[form_id] ?>&operate=approve&step=<?=$val[step]?>','detail');return false;">办理</a>&nbsp;
                        <?php endif; ?>
                        <?php if (($userId == $val['user_id'] || $isAdmin) && ($val["status"] == 'T' || $val["status"] == 'N')) : ?>
                            <a href="javascript:void(0);" class="newColor" onclick="pageOperate('../lieu_receive/action.php?action=Delete&id=<?= $val[form_id] ?>','delete');return false;">删除</a>&nbsp;
                        <?php endif; ?>
        </td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>
<br/>
<?endif;?>
