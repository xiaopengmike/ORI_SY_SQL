<table id="tab2" width="100%" style="text-align: left;">
    <tr>
        <td class="TableHeader" colspan="8" style="color: red;text-align:center">（二）项目情况</td>
    </tr>
    <tr>
        <td class="TableContent" width="15%">1.银河项目代号</td>
        <td class="TableData" width="35%">
            <input type="text" readonly name="project_code" id="project_code" class="layui-input required_field" value="<?= $project['project_code'] ?>" autocomplete="off" lay-verify="required" />
            <input type="hidden" readonly name="temp_project_code" id="temp_project_code" class="layui-input" value="<?= $project['temp_project_code'] ?>" autocomplete="off"  />
           
        </td>
        <td class="TableContent" width="15%">2.项目所在国</td>
        <td class="TableData">
            <input type="text" name="country" id="country" class="layui-input" placeholder="请输入项目所在国" value="<?= $project['country'] ?>" />
        </td>
    </tr>
    <tr>
        <td class="TableContent">3.项目范围（供应和服务内容：电表/AMI交付/EPC）</td>
        <td class="TableData" colspan="3">
            <!-- <input type="text" name="scope" id="scope" class="layui-input" value="<?= $project['scope'] ?>" /> -->
            <textarea class="layui-textarea" id="scope" name="scope"><?= $project['scope'] ?></textarea>
        </td>
    </tr>
    <tr>
        <td class="TableContent">4.立项项目类型
            （投标方式只针对经销，投标方式是根据最终客户订单的获取方式来判断，不是以经销商订单的获取来判断。）
        </td>
        <td class="TableData" colspan="3">
            <div class="layui-input-inline mandatory">
                <select id="project_type" name="project_type" lay-search >
                    <option value=''>请选择</option>
                    <?php foreach ($selectArr['project_type'] as $val) : ?>
                        <?php if($val['user_bu']=='INHENERGY'){continue;} ?>
                        <option value="<?= $val['paras_value'] ?>" <?php if ($project['project_type'] == $val['paras_value']) : ?>selected<?php endif; ?>><?= $val['paras_desc'] ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
        </td>
    </tr>
    <tr>
        <td class="TableContent">5.样机要求（样机数量，交样时间）</td>
        <td class="TableData" colspan="3">
            <!-- <input type="text" name="prototype" id="prototype" class="layui-input" value="<?= $project['prototype'] ?>" /> -->
            <textarea class="layui-textarea" id="prototype" name="prototype"><?= $project['prototype'] ?></textarea>
        </td>
    </tr>
    <tr>
        <td class="TableContent">6.项目潜力（可持续性和重要性）</td>
        <td class="TableData" colspan="3">
            <textarea class="layui-textarea" id="potential" name="potential"><?= $project['potential'] ?></textarea>
        </td>
    </tr>
    <tr>
        <td class="TableContent">7.项目来源国政治背景/社会经济状况</td>
        <td class="TableData">
            <textarea class="layui-textarea" id="background" name="background"><?= $project['background'] ?></textarea>
        </td>
        <td class="TableContent" width="15%">添加附件：</td>
        <td class="TableData">
            <input id="background_attach" type="button" class="layui-btn layui-bg-gray btn-upload" style="display: inline!important;" value="上传" />
        </td>
    </tr>
    <tr>
        <td class="TableContent">8.EPC项目说明（分包商和监理介绍）</td>
        <td class="TableData">
            <textarea class="layui-textarea" id="explain_user" name="explain_user"><?= $project['explain_user'] ?></textarea>
        </td>
        <td class="TableContent" width="15%">添加附件：</td>
        <td class="TableData">
            <input id="explain_user_attach" type="button" class="layui-btn layui-bg-gray btn-upload" style="display: inline!important;" value="上传" />
        </td>
    </tr>
    <tr>
        <td class="TableContent">9.EPC项目说明（项目工期，特殊资质和要求）</td>
        <td class="TableData">
            <textarea class="layui-textarea" id="explain_project" name="explain_project"><?= $project['explain_project'] ?></textarea>
        </td>
        <td class="TableContent" width="15%">添加附件：</td>
        <td class="TableData">
            <input id="explain_project_attach" type="button" class="layui-btn layui-bg-gray btn-upload" style="display: inline!important;" value="上传" />
        </td>
    </tr>
    <tr>
        <td class="TableContent" colspan="4">10.项目需求产品、市场参考价格及客户目标价格</td>
    </tr>
    <? $line_num=0;foreach($selectArr['product_detail_type'] as $product_type): ++$line_num;?>
    <tr>
        <td class="TableContent" colspan="4"><?=$product_type[paras_desc]?></td>
    </tr>
    <tr>
        <td colspan="4">
            <table id="tab21" width="100%">
                <tr class="TableContent" style="text-align: center;">
                    <!-- <td>序号</td> -->
                    <td>项目需求产品</td>
                    <td>业务建议价格</td>
                    <td>历史投/开标价格</td>
                    <td>技术规格/标准</td>
                    <td>预期数量</td>
                    <td>附件</td>
                </tr>
                <tr align="center">                    
                    <input type="hidden" name="type<?=$line_num?>" value="<?=$product_type['paras_value'] ?>" />
                    <!-- <td class="TableData"><input type="hidden" name="serial_number" id="serial_number" class="layui-input" />1</td> -->
                    <td class="TableData" width="10%"><input type="text" name="product<?=$line_num?>" id="product" class="layui-input" /></td>
                    <td class="TableData" width="10%"><input type="text" name="price<?=$line_num?>" id="price" class="layui-input" /></td>
                    <td class="TableData" width="10%"><input type="text" name="first_price<?=$line_num?>" id="first_price" class="layui-input" /></td>
                    <td class="TableData" width="40%"><input type="text" name="standard<?=$line_num?>" id="standard" class="layui-input" /></td>
                    <td class="TableData" width="6%"><input type="text" name="number<?=$line_num?>" id="number" lay-verify="checknum" class="layui-input" /></td>
                    <td class="TableData">
                        <input id="project_detail_attach<?=$line_num?>" type="button" class="layui-btn layui-bg-gray btn-upload" style="display: inline!important;" value="上传" />
                        <a href="javascript:;" onclick="removeLine(this)">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;删除</a>
                    </td>
                </tr>
                <tr align="left" id="addProjectDetail">
                    <td class="TableData" colspan="6">
                        <input value="添加" type=button class="layui-btn layui-btn-oa layui-btn-sm" onclick="addLine('addProjectDetail',this,'<?=$product_type['paras_value']?>');return false;" />
                    </td>
                </tr>
            </table>
        </td>
    </tr>
    <?php endforeach;?>
    <input type="hidden" id="addProjectDetailCount" name="addProjectDetailCount" value="<?= count($selectArr['product_detail_type']) ?>" />
    <tr>
        <td class="TableContent">11.服务要求（验厂，验货，培训等）</td>
        <td class="TableData" colspan="3">
            <textarea class="layui-textarea" id="service" name="service"><?= $project['service'] ?></textarea>
        </td>
    </tr>
    <tr>
        <td class="TableContent">是否为集抄项目</td>
        <td class="TableData" colspan="3">
        <div class="layui-input-inline mandatory">
                <select id="is_collective" name="is_collective" lay-search lay-verify="required">
                    <option value=''>请选择</option>
                    <option value="是" <?php if ($main['is_collective'] == '是') : ?>selected<?php endif; ?>>是</option>
                    <option value="否" <?php if ($main['is_collective'] == '否') : ?>selected<?php endif; ?>>否</option>
                </select>
            </div>
        </td>
    </tr>
    <tr>
        <td class="TableContent">本市场过往项目代号</td>
        <td class="TableData">
            <textarea class="layui-textarea required_field" id="past_pcode"  name="past_pcode" placeholder="此框必填，如无也要填无" lay-verify="required"><?= $main['past_pcode'] ?></textarea>
        </td>
        <td class="TableContent">本市场不同客户曾经出现过的项目代号</td>
        <td class="TableData">
            <textarea class="layui-textarea required_field" id="c_past_pcode" name="c_past_pcode" placeholder="此框必填，如无也要填无" lay-verify="required"><?= $main['c_past_pcode'] ?></textarea>
        </td>
    </tr>
</table>