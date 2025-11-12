<table id="tab4" name="tab4" width="100%" style="text-align: left;">
    <tr>
        <td class="TableHeader" style="color: red;text-align:center" colspan="8">审核意见</td>
    </tr>
    <?php foreach ($approveData as $k => $approveInfo) : ?>
        <?php if ($approveInfo['step']==1||$operate == 'approve'|| strpos($approveInfo['work_flow'],'备案')!==false|| 
        in_array($approveInfo['work_flow'],array('填写品号','品号通知','验收填写','申请料号'))){continue;} ?>
        <tr>
            <?php $rowspan=count($opinion_extra_arr[$approveInfo['work_flow']])+count($opinion_select_arr[$approveInfo['work_flow']])+count($opinion_attachment_arr[$approveInfo['work_flow']])+4; ?>
            <td class="TableContent" rowspan="<?=$rowspan?>" width="20%" align="center">
                <?= $approveInfo['work_flow'] ?>
                </br>
                <span class="en_name"><?= $LG_ROLE[$approveInfo['work_flow']]['EN'] ?></span>
            </td>
            <?php if ($approveInfo['project_star']) : ?>
                <td class="TableContent" align="center" width="10%">项目星级</td>
                <td class="TableData" width="33%">
                        <div class="layui-input-inline">
                            <h2>
                                <b>
                                    <font style="color: red;"><?= $psArr[$approveInfo['project_star']]['paras_desc'] ?></font>
                                </b>
                            </h2>
                        </div>
                    </td>
                    <td class="TableContent" align="center" width="10%">星级图标</td>
                    <td class="TableData">
                        <div data-type="star_icon">
                            <h2>
                                <font style="color: red;"><?= $psArr[$approveInfo['project_star']]['extra'] ?></font>
                            </h2>
                        </div>
                    </td>
            <?php endif; ?>
            <?php if (!empty($flowArr[$approveInfo['step']]['other_condition'])&&$flowArr[$approveInfo['step']]['other_condition']!='opinion_attachment'&&$flowArr[$approveInfo['step']]['other_condition']!='opinion_extra') : ?>
                <td class="TableData" width="20%" nowrap>
                    <label>
                        <font style="color: black;"><?= $other_condition[$flowArr[$approveInfo['step']]['other_condition']]['paras_desc'] ?></font>
                    </label>
                </td>
                <td class="TableData" align="left">
                        <?= $yes_or_no[$approveInfo['extra']]['paras_desc'] ?>
                </td>
            <?php endif; ?>
            </tr>
            <!-- 其他填写内容,通过inhe_oa_paras设置变量参数，通过json数据形式存入inhe_flow_opinion表的extra -->
            <?php $opinion_extra=td_json_decode($approveInfo['extra']); ?>
            <?php foreach ($opinion_extra_arr[$approveInfo['work_flow']] as $ok => $ov) : ?>
                <?php if ($approveInfo['step'] == $step && $operate == 'approve') : ?>
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
            <?php foreach ($opinion_select_arr[$approveInfo['work_flow']] as $ok => $ov) : ?>
                <?php if ($approveInfo['step'] == $step && $operate == 'approve') : ?>
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
             <?php foreach ($opinion_attachment_arr[$approveInfo['work_flow']] as $ok => $ov) : ?>
                <?php if ($approveInfo['step'] == $step && $operate == 'approve') : ?>
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
            <?php if ($k == $step && $operate == 'approve') : ?>
                &nbsp;&nbsp;&nbsp;&nbsp;
                <input type="radio" name="agree<?= $k ?>" value="01" lay-verify="otherReq" lay-ignore><label>
                    <font size=2>Approval(同意)</font>
                </label>
                &nbsp;&nbsp;&nbsp;&nbsp;
                <input type="radio" name="agree<?= $k ?>" value="02" lay-verify="otherReq" lay-ignore><label>
                    <font size=2>Disapproval(不同意)</font>
                </label>
            <?php else : ?>
                &nbsp;&nbsp;&nbsp;&nbsp;
                <input lay-ignore disabled type="radio" name="agree<?= $k ?>" value="01" <?php if ($approveInfo['if_agree'] == "01") : ?>checked<?php endif; ?>><label>
                    <font size=2>Approval(同意)</font>
                </label>
                &nbsp;&nbsp;&nbsp;&nbsp;
                <input lay-ignore disabled type="radio" name="agree<?= $k ?>" value="02" <?php if ($approveInfo['if_agree'] == "02") : ?>checked<?php endif; ?>><label>
                    <font size=2>Disapproval(不同意)</font>
                </label>
            <?php endif; ?>
            </td>
        </tr>
        <tr>
            <td class="TableData"  colspan="4">
                <textarea id="approve_opinion" class="layui-textarea TextDetails " id="opinion<?= $k ?>" name="opinion<?= $k ?>" data-type="opinion" disabled><?= $approveInfo['opinion'] ?></textarea>
            </td>
        </tr>
        <tr>
            <td class="TableData MinHeight" colspan="4">
                <?= $approveInfo['signature'] ?>
            </td>
        </tr>
    <?php endforeach; ?>
</table>