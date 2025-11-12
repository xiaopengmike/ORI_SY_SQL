<table id="tab4" name="tab4" width="100%" style="text-align: left;">
    <tr>
        <td class="TableHeader" colspan="5" style="color: red;text-align:center">（四）审核意见</td>
    </tr>
    <?php foreach ($flowArr as $k => $fv) : ?>
        <?php if (($fv['step'] == $step)) : ?>
            <input type="hidden" name="role" id="role" value="<?= $fv['role'] ?>" />
            <input type="hidden" name="step" id="step" value="<?= $fv['step'] ?>" />
        <?php endif; ?>
        <?php if ($fv['if_show'] == '1') : ?>
            <?php $rowspan=count($opinion_extra_arr[$fv['role']])+4; ?>
            <tr>
                <td class="TableContent" width="15%" align="center" rowspan="<?=$rowspan?>"><?= $fv['role'] ?>
                        <?php if ($fv['remind_content'] != '') : ?>
                            <br/>
                            <span  style="color: red;text-align:center">
                                (<?= $fv['remind_content'] ?>)
                            </span>
                        <?php endif; ?>
                    </td>
                <?php if ($fv['star_select']) : ?>
                    <td class="TableContent" align="center" width="10%">项目星级</td>
                    <?php if ($fv['step'] == $step && $operate == 'approve') : ?>
                        <td class="TableData" width="33%">
                            <div class="layui-input-inline mandatory">
                                <select id="project_star" name="project_star" lay-filter="project_level" <?if($fv['role']=='董事长'||$fv['role']=='总经理'): ?> lay-verify="required"<?endif;?> lay-search>
                                    <option value=''>请选择</option>
                                    <option value="00">一星级</option>
                                    <option value="01">二星级</option>
                                    <option value="02">三星级</option>
                                    <option value="03">四星级</option>
                                    <option value="04">五星级</option>
                                    <option value="05">不符合</option>
                                </select>
                            </div>
                           <!-- <b><i onclick="layer.msg('星级必选，代表，并且在后续流程和邮件中需要使用')" class="iconfont">&#59044;</i></b> -->
                        </td>
                        <td class="TableContent" align="center" width="10%">星级图标</td>
                        <td class="TableData">
                            <div data-type="star_icon" id="star_icon"></div>
                        </td>
                    <?php else : ?>
                        <td class="TableData" width="33%">
                            <div class="layui-input-inline">
                                <h2>
                                    <b>
                                        <font style="color: red;"><?= $psArr[$approveData[$fv['step']]['project_star']]['paras_desc'] ?></font>
                                    </b>
                                </h2>
                            </div>
                        </td>
                        <td class="TableContent" align="center" width="10%">星级图标</td>
                        <td class="TableData">
                            <div data-type="star_icon">
                                <h2>
                                    <font style="color: red;"><?= $psArr[$approveData[$fv['step']]['project_star']]['extra'] ?></font>
                                </h2>
                            </div>
                        </td>
                    <?php endif; ?>
                <?php endif; ?>
            </tr>
            <!-- 其他填写内容,通过inhe_oa_paras设置变量参数，通过json数据形式存入inhe_flow_opinion表的extra -->
            <?php $opinion_extra=td_json_decode($approveData[$fv['step']]['extra']);?>
            <?php foreach ($opinion_extra_arr[$fv['role']] as $ok => $ov) : ?>
                <?php if ($fv['step'] == $step && $operate == 'approve') : ?>
                    <tr>
                        <td class="TableContent"><?=$ov[paras_desc]?></td>
                        <td class="TableData" colspan="3">
                            <?if($ov[related_table]=="user"):?>
                                <input type="hidden" class="" name="<?=$ok."_user"?>" id="opinion_extra" value="<?=$opinion_extra[$ok."_user"]?>" >
                                <textarea class="layui-textarea mandatory" id="opinion_extra" <?if($ov[extra]=="required"):?>lay-verify="opinionRequired"<?endif;?> placeholder="请点击选择<?=$ov[paras_desc]?>" name="<?=$ok?>" readonly onClick="SelectUserSingle('','<?=$ok."_user"?>', '<?=$ok?>')"><?=$opinion_extra[$ok]?></textarea>
                            <?else:?>
                                <textarea class="layui-textarea mandatory" id="opinion_extra" <?if($ov[extra]=="required"):?>lay-verify="opinionRequired"<?endif;?>  name="<?=$ok?>"><?=$opinion_extra[$ok]?></textarea>
                            <?endif;?>
                        </td>
                    </tr>
                <?php else : ?>
                    <tr>
                        <td class="TableContent"><?=$ov[paras_desc]?></td>
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
                    <textarea id="approve_opinion" class="layui-textarea <?php if (($fv['step'] == $step)) : ?>mandatory<?php endif; ?>" id="opinion<?= $fv['step'] ?>" name="opinion<?= $fv['step'] ?>" data-type="opinion" <?php if (($fv['step'] != $step)) : ?>disabled<?php endif; ?>><?= $approveData[$fv['step']]['opinion'] ?></textarea>
                </td>
            </tr>
            <tr>
                <td class="TableData MinHeight" colspan="4">
                    <?= $approveData[$fv['step']]['signature'] ?>
                </td>
            </tr>
            <!-- <?php if ($fv['remind_content'] != '') : ?>
                <tr>
                    <td class="TableContent" colspan="5" style="color: red;text-align:center">
                        <?= $fv['remind_content'] ?>
                    </td>
                </tr>
            <?php endif; ?> -->
        <?php endif; ?>
    <?php endforeach; ?>
</table>