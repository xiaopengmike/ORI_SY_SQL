<table id="tab4" name="tab4" width="100%" style="text-align: left;">
    <tr>
        <td class="TableHeader" colspan="5" style="color: red;text-align:center">审核意见</td>
    </tr>
    <?php foreach ($flowArr as $k => $fv) : ?>
        <?php if (($fv['step'] == $step)) : ?>
            <input type="hidden" name="role" id="role" value="<?= $fv['role'] ?>" />
            <input type="hidden" name="step" id="step" value="<?= $fv['step'] ?>" />
        <?php endif; ?>
        <?php if ($fv['if_show'] == '1') :?>
            <?php $rowspan=count($opinion_extra_arr[$fv['role']])+4; ?>
            <tr>
                <td class="TableContent" width="15%" align="center" rowspan="<?= $rowspan ?>"><?= $fv['role'] ?></td>
                <?php if (!empty($fv['other_condition'])) : ?>
                    <td class="TableData" width="20%" nowrap>
                        <label  class="label_condition">
                            <font style="color: black;"><?= $other_condition[$fv['other_condition']]['paras_desc'] ?></font>
                        </label>
                    </td>
                    <td class="TableData" align="left">
                        <?php if (($fv['step'] == $step)) : ?>
                            <div class="layui-input-inline mandatory" style="width: 90px;">
                                <select id="<?= $fv['other_condition'] ?>" name="<?= $fv['other_condition'] ?>" lay-search lay-verify="required">
                                    <option value="">请选择</option>
                                    <?php foreach ($selectArr['yes_or_no'] as $v) : ?>
                                        <option value="<?= $v['paras_value'] ?>"><?= $v['paras_desc'] ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        <?php else : ?>
                            <input type="hidden" disabled="disabled" name="<?= $fv['other_condition'] ?>" id="<?= $fv['other_condition'] ?>" value="<?= $approveData[$fv['step']]['extra'] ?>" />
                            <?= $yes_or_no[$approveData[$fv['step']]['extra']]['paras_desc'] ?>
                        <?php endif; ?>
                    </td>
                <?php endif; ?>
            </tr>
             <!-- 其他填写内容,通过inhe_oa_paras设置变量参数，通过json数据形式存入inhe_flow_opinion表的extra -->
             <?php $opinion_extra=td_json_decode($approveData[$fv['step']]['extra']);?>
            <?php foreach ($opinion_extra_arr[$fv['role']] as $ok => $ov) : ?>
                <?php if ($fv['step'] == $step && $operate == 'approve') : ?>
                    <tr>
                        <td class="TableContent" width="15%"><?=$ov['paras_desc']?></td>
                        <td class="TableData" colspan="3"><textarea class="layui-textarea mandatory" id="opinion_extra" name="<?=$ok?>"><?=$opinion_extra[$ok]?></textarea></td>
                    </tr>
                <?php else : ?>
                    <tr>
                        <td class="TableContent" width="15%"><?=$ov['paras_desc']?></td>
                        <td class="TableData" colspan="3"><textarea class="layui-textarea " id="opinion_extra2"  ><?=$opinion_extra[$ok]?></textarea></td>
                    </tr>
                <?php endif; ?>
            <?php endforeach;?>
            <tr>
            <td class="TableData" colspan="4">
                &nbsp;&nbsp;&nbsp;&nbsp;
                <?php if ($fv['step'] == $step && $operate == 'approve') : ?>
                    &nbsp;&nbsp;&nbsp;&nbsp;
                    <input type="radio" name="agree<?= $fv['step'] ?>" value="01" lay-verify="otherReq" lay-ignore><label>
                        <font size=2>同意</font>
                    </label>
                    &nbsp;&nbsp;&nbsp;&nbsp;
                    <input type="radio" name="agree<?= $fv['step'] ?>" value="02" lay-verify="otherReq" lay-ignore><label>
                        <font size=2>不同意</font>
                    </label>
                <?php else : ?>
                    &nbsp;&nbsp;&nbsp;&nbsp;
                    <input lay-ignore disabled type="radio" name="agree<?= $fv['step'] ?>" value="01" <?php if ($approveData[$fv['step']]['if_agree'] == "01") : ?>checked<?php endif; ?>><label>
                        <font size=2>同意</font>
                    </label>
                    &nbsp;&nbsp;&nbsp;&nbsp;
                    <input lay-ignore disabled type="radio" name="agree<?= $fv['step'] ?>" value="02" <?php if ($approveData[$fv['step']]['if_agree'] == "02") : ?>checked<?php endif; ?>><label>
                        <font size=2>不同意</font>
                    </label>
                <?php endif; ?>
            </td>
            </tr>
            <tr>
                <td class="TableData" colspan="4">
                    <textarea id="approve_opinion" class="layui-textarea TextDetails <?php if (($fv['step'] == $step)) : ?>mandatory<?php endif; ?>" id="opinion<?= $fv['step'] ?>" name="opinion<?= $fv['step'] ?>" data-type="opinion" <?php if (($fv['step'] != $step)) : ?>disabled<?php endif; ?>><?= $approveData[$fv['step']]['opinion'] ?></textarea>
                </td>
            </tr>
            <tr>
                <td class="TableData MinHeight" colspan="4">
                    <?= $approveData[$fv['step']]['signature'] ?>
                </td>
            </tr>
            <?php if ($fv['remind_content'] != '') : ?>
                <tr>
                    <td class="TableContent" colspan="4" style="color: red;text-align:center">
                        <?= $fv['remind_content'] ?>
                    </td>
                </tr>
            <?php endif; ?>
        <?php endif; ?>
    <?php endforeach; ?>
</table>