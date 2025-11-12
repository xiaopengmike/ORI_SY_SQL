<table id="tab4" name="tab4" width="100%" style="text-align: left;">
    <tr>
        <td class="TableHeader" style="color: red;text-align:center" colspan="2">（四）审核意见</td>
    </tr>
    <?php foreach ($approveData as $k => $approveInfo) : ?>
        <?php if ($approveInfo['step']==1||$approveInfo['work_flow']=='备案'){continue;} ?>
        <?php if (($operate == 'approve'&&$approveInfo['status']!='H')){continue;} ?>
        <tr>
            <td class="TableContent" rowspan="3" width="20%" align="center">
                <h3><b><?= $approveInfo['work_flow'] ?></b></h3>
            </td>
            <td class="TableData">
                &nbsp;&nbsp;&nbsp;&nbsp;
                <input lay-ignore disabled type="radio"  value="01" <?php if ($approveInfo['if_agree'] == "01") : ?>checked<?php endif; ?>><label>
                    <font size=2>同意</font>
                </label>
                &nbsp;&nbsp;&nbsp;&nbsp;
                <input lay-ignore disabled type="radio"  value="02" <?php if ($approveInfo['if_agree'] == "02") : ?>checked<?php endif; ?>><label>
                    <font size=2>不同意</font>
                </label>
            </td>
        </tr>
        <tr>
            <td class="TableData">
                <textarea id="approve_opinion" class="layui-textarea TextDetails " ><?= $approveInfo['opinion'] ?></textarea>
            </td>
        </tr>
        <tr>
            <td class="TableData MinHeight">
                <?= $approveInfo['signature'] ?>
            </td>
        </tr>
    <?php endforeach; ?>
    <?php foreach ($flowArr as $k => $fv) : ?>
        <?php if (($fv['step'] < $step||empty($step) ||$fv['approver']=='meterw')){continue;} ?>
        <?php if (($fv['step'] == $step)) : ?>
            <input type="hidden" name="role" id="role" value="<?= $fv['role'] ?>" />
            <input type="hidden" name="step" id="step" value="<?= $fv['step'] ?>" />
        <?php endif; ?>
        <?php if ($fv['if_show'] == '1'&&$fv['auto_approver'] == '1') : ?>
            <tr>
                <td class="TableContent" rowspan="3" width="20%" align="center">
                    <h3><b><?= $fv['role'] ?></b></h3>
                </td>
                <td class="TableData">
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
                <td class="TableData">
                    <textarea id="approve_opinion" class="layui-textarea TextDetails <?php if (($fv['step'] == $step)) : ?>mandatory<?php endif; ?>" id="opinion<?= $fv['step'] ?>" name="opinion<?= $fv['step'] ?>" data-type="opinion" <?php if (($fv['step'] != $step)) : ?>disabled<?php endif; ?>><?= $approveData[$fv['step']]['opinion'] ?></textarea>
                </td>
            </tr>
            <tr>
                <td class="TableData MinHeight">
                    <?= $approveData[$fv['step']]['signature'] ?>
                </td>
            </tr>
        <?php endif; ?>
        <?php if ($fv['remind_content'] != '') : ?>
            <tr>
                <td class="TableContent" colspan="4" style="color: red;text-align:center">
                    <?= $fv['remind_content'] ?>
                </td>
            </tr>
        <?php endif; ?>
    <?php endforeach; ?>
    <?php foreach ($approveData as $k => $approveInfo) : ?>
        <?php if ($main['status']=='P'||$approveInfo['approve_user']!='meterw'){continue;} ?>
        <?php if (($operate == 'detail'&&$approveInfo['step']!=0)||($operate == 'approve'&&$approveInfo['status']!='H')){continue;} ?>
        <tr>
            <td class="TableContent" rowspan="3" width="20%" align="center">
                <h3><b><?= $approveInfo['work_flow'] ?></b></h3>
            </td>
            <td class="TableData">
                &nbsp;&nbsp;&nbsp;&nbsp;
                <input lay-ignore disabled type="radio"  value="01" <?php if ($approveInfo['if_agree'] == "01") : ?>checked<?php endif; ?>><label>
                    <font size=2>同意</font>
                </label>
                &nbsp;&nbsp;&nbsp;&nbsp;
                <input lay-ignore disabled type="radio"  value="02" <?php if ($approveInfo['if_agree'] == "02") : ?>checked<?php endif; ?>><label>
                    <font size=2>不同意</font>
                </label>
            </td>
        </tr>
        <tr>
            <td class="TableData">
                <textarea id="approve_opinion" class="layui-textarea TextDetails " ><?= $approveInfo['opinion'] ?></textarea>
            </td>
        </tr>
        <tr>
            <td class="TableData MinHeight">
                <?= $approveInfo['signature'] ?>
            </td>
        </tr>
    <?php endforeach; ?>
</table>