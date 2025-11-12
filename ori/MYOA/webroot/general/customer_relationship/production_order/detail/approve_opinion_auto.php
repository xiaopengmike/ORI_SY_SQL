<table id="tab4" name="tab4" width="100%" style="text-align: left;">
    <tr>
        <td class="TableHeader" style="color: red;text-align:center" colspan="5">（四）审核意见</td>
    </tr>
    <?php foreach ($approveData as $k => $approveInfo) : ?>
        <?php if ($approveInfo['step']==1||$approveInfo['work_flow']=='备案'||in_array($approveInfo['work_flow'],array('填写品号','品号通知','验收填写'))){continue;} ?>
        <?php if (($operate == 'approve'&&$approveInfo['status']!='H')){continue;} ?>
        <?php $rowspan=4; $rowspan=!empty($flowArr[$approveInfo[step]]['other_condition'])&&$flowArr[$approveInfo['step']]['other_condition']!='opinion_attachment'&&$flowArr[$approveInfo['step']]['other_condition']!='opinion_extra'?$rowspan+1:$rowspan; ?>
        <tr>
            <td class="TableContent" rowspan="<?=$rowspan?>" width="20%" align="center">
                <h3><b><?= $approveInfo['work_flow'] ?></b></h3>
            </td>
        </tr>
        <?php if (!empty($flowArr[$approveInfo[step]]['other_condition'])&&$flowArr[$approveInfo['step']]['other_condition']!='opinion_attachment'&&$flowArr[$approveInfo['step']]['other_condition']!='opinion_extra') : ?>
        <tr>
            <td class="TableData" width="20%" nowrap>
                <label>
                    <font style="color: black;"><?= $other_condition[$flowArr[$approveInfo[step]]['other_condition']]['paras_desc'] ?></font>
                </label>
            </td>
            <td class="TableData" align="left">
                    <input type="hidden"  id="<?= $flowArr[$approveInfo[step]]['other_condition'] ?>" value="<?= $approveInfo['extra'] ?>" />
                    <?= $yes_or_no[$approveInfo['extra']] ?>
            </td>
        </tr>
        <?php endif; ?>
        <tr>
            <td class="TableData" colspan="4">
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
            <td class="TableData" colspan="4">
                <textarea id="approve_opinion" class="layui-textarea TextDetails " ><?= $approveInfo['opinion'] ?></textarea>
            </td>
        </tr>
        <tr>
            <td class="TableData MinHeight" colspan="4">
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
            <?php $rowspan=count($opinion_extra_arr[$fv['role']])+count($opinion_select_arr[$fv['role']])+count($opinion_attachment_arr[$fv['role']])+4; ?>
            <?php $rowspan=!empty($fv['other_condition'])&&$fv['other_condition']!='opinion_attachment'&&$fv['other_condition']!='opinion_extra'?$rowspan+1:$rowspan; ?>
            <tr>
                <td class="TableContent" rowspan="<?=$rowspan?>" width="20%" align="center">
                    <h3><b><?= $fv['role'] ?></b></h3>
                </td>
            </tr>
                <?php if (!empty($fv['other_condition'])&&$fv['other_condition']!='opinion_attachment'&&$fv['other_condition']!='opinion_extra') : ?>
                    <tr>
                        <td class="TableData" width="20%" nowrap>
                            <label>
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
                                <input type="hidden"  id="<?= $fv['other_condition'] ?>" value="<?= $approveData[$fv['step']]['extra'] ?>" />
                                <?= $yes_or_no[$approveData[$fv['step']]['extra']] ?>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endif; ?>
                <!-- 其他填写内容,通过inhe_oa_paras设置变量参数，通过json数据形式存入inhe_flow_opinion表的extra -->
                <?php $opinion_extra=td_json_decode($approveData[$fv['step']]['extra']);?>
                <?php foreach ($opinion_extra_arr[$fv['role']] as $ok => $ov) : ?>
                    <?php if ($fv['step'] == $step && $operate == 'approve') : ?>
                        <tr>
                            <td class="TableContent" width="15%"><?=$ov[paras_desc]?></td>
                            <td class="TableData" colspan="3"><textarea class="layui-textarea mandatory" id="opinion_extra" name="<?=$ok?>"><?=$opinion_extra[$ok]?></textarea></td>
                        </tr>
                    <?php else : ?>
                        <tr>
                            <td class="TableContent" width="15%"><?=$ov[paras_desc]?></td>
                            <td class="TableData" colspan="3"><textarea class="layui-textarea " id="opinion_extra2"  ><?=$opinion_extra[$ok]?></textarea></td>
                        </tr>
                    <?php endif; ?>
                <?php endforeach;?>
                <!-- 其他选择下拉内容,通过inhe_oa_paras设置变量参数，通过json数据形式存入inhe_flow_opinion表的extra -->
                <?php foreach ($opinion_select_arr[$fv['role']] as $ok => $ov) : ?>
                    <?php if ($fv['step'] == $step && $operate == 'approve') : ?>
                        <tr>
                            <td class="TableContent" width="15%"><?=$ov[name]?></td>
                            <td class="TableData" colspan="3">
                            <div class="layui-input-inline mandatory">
                            <select id="<?=$ok?>" name="<?=$ok?>" lay-search lay-verify="required">
                                        <option value=''>请选择</option>
                                        <?php foreach ($ov[data][$ok] as $v) : ?>
                                            <option value="<?= $v['paras_value'] ?>" <?php if ($opinion_extra[$ok] == $v['paras_value']) : ?>selected<?php endif; ?>><?= $v['paras_desc'] ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </td>
                        </tr>
                    <?php else : ?>
                        <tr>
                            <td class="TableContent" width="15%"><?=$ov[name]?></td>
                            <td class="TableData" colspan="3">
                            <?php foreach ($ov[data][$ok] as $v) : ?>
                                <?php if ($opinion_extra[$ok] == $v['paras_value']) : ?>
                                    <?= $v['paras_desc'] ?> 
                                <?php endif; ?>
                                            
                            <?php endforeach; ?>
                            </td>
                        </tr>
                    <?php endif; ?>
                <?php endforeach;?>
                <!-- 其他填写内容,通过inhe_oa_paras设置变量参数，通过json数据形式存入inhe_flow_opinion表的extra -->
                <?php foreach ($opinion_attachment_arr[$fv['role']] as $ok => $ov) : ?>
                    <?php if ($fv['step'] == $step && $operate == 'approve') : ?>
                        <tr>
                            <td class="TableContent" width="15%"><?=$ov?></td>
                            <td class="TableData" colspan="3"><input id="<?=$ok?>" type="button" class="layui-btn layui-bg-gray btn-upload not_disabled" style="display: inline!important;" value="上传"  /></td>
                        </tr>
                    <?php else : ?>
                        <tr>
                            <td class="TableContent" width="15%"><?=$ov?></td>
                            <td class="TableData" colspan="3"><input id="<?=$ok?>" type="button" class="layui-btn layui-bg-gray btn-upload " style="display: none;" value="上传" readonly /></td>
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
        <?php endif; ?>
        <?php if ($fv['remind_content'] != '') : ?>
            <tr>
                <td class="TableContent" colspan="4" style="color: red;text-align:center">
                    <?= $fv['remind_content'] ?>
                </td>
            </tr>
        <?php endif; ?>
    <?php endforeach; ?>
</table>