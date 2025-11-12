<table id="back_tab" name="back_tab" width="100%" style="text-align: left;">
    <tr>
        <td class="TableHeader" colspan="8" style="color: red;text-align:center;">审批退回历史记录</td>
    </tr>
    <?php $backNum = 0;
    foreach ($backRecord as $fv) : $backNum++; ?>
    <?php $rowspan=count($opinion_extra_arr[$fv['work_flow']])+4; ?>
        <tr>
            <td class="TableContent" width="20%" align="center" rowspan="<?=$rowspan?>"><?= $fv['work_flow'] ?></td>
        </tr>
        <!-- 其他填写内容,通过inhe_oa_paras设置变量参数，通过json数据形式存入inhe_flow_opinion表的extra -->
        <?php $opinion_extra=td_json_decode($fv['extra']);?>
            <?php foreach ($opinion_extra_arr[$fv['work_flow']] as $ok => $ov) : ?>
                <?php if ($fv['step'] == $step && $operate == 'approve') : ?>
                    <tr>
                        <td class="TableContent" width="15%"><?=$ov?></td>
                        <td class="TableData" colspan="3"><textarea class="layui-textarea mandatory" id="opinion_extra" name="<?=$ok?>"><?=$opinion_extra[$ok]?></textarea></td>
                    </tr>
                <?php else : ?>
                    <tr>
                        <td class="TableContent" width="15%"><?=$ov?></td>
                        <td class="TableData" colspan="3"><textarea class="layui-textarea " id="opinion_extra2"  ><?=$opinion_extra[$ok]?></textarea></td>
                    </tr>
                <?php endif; ?>
            <?php endforeach;?>

        <tr>
            <td class="TableData" colspan="4">
                &nbsp;&nbsp;&nbsp;&nbsp;
                <input lay-ignore disabled type="radio" value="01" <?php if ($fv['if_agree'] == "01") : ?>checked<?php endif; ?>><label>
                    <font size=2>同意</font>
                </label>
                &nbsp;&nbsp;&nbsp;&nbsp;
                <input lay-ignore disabled type="radio" value="02" <?php if ($fv['if_agree'] == "02") : ?>checked<?php endif; ?>><label>
                    <font size=2>不同意</font>
                </label>
            </td>
        </tr>
        <tr>
            <td class="TableData" colspan="4">
                <textarea class="layui-textarea" id="back_opinion<?= $backNum ?>" name="back_opinion<?= $backNum ?>" disabled><?= $fv['opinion'] ?></textarea>
            </td>
        </tr>
        <tr>
            <td class="TableData MinHeight" colspan="4">
                <?= $fv['signature'] ?>
            </td>
        </tr>
    <?php endforeach; ?>
</table>