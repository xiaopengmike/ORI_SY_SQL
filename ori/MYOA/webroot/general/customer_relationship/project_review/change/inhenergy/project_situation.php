<table id="tab2" width="100%" style="text-align: left;">
    <tr>
        <td class="TableHeader" colspan="8" style="color: red;text-align:center">（二）项目情况</td>
    </tr>
    <tr>
        <td class="TableContent" width="15%">1.银河项目代号</td>
        <td class="TableData" width="35%">
            <input readonly type="text" name="project_code" id="project_code" class="layui-input" value="<?= $project['project_code'] ?>" autocomplete="off" lay-verify="required" />
            <input type="hidden" readonly name="temp_project_code" id="temp_project_code" class="layui-input" value="<?= $project['temp_project_code'] ?>" autocomplete="off"  />
        </td>
        <td class="TableContent" width="15%">2.市场所在国</td>
        <td class="TableData">
            <input type="text" name="country" id="country" class="layui-input" placeholder="请输入市场所在国" value="<?= $project['country'] ?>" />
        </td>
    </tr>
    <tr>
        <td class="TableContent">3.项目范围（供货和服务内容）</td>
        <td class="TableData" colspan="3">
            <input type="text" name="scope" id="scope" class="layui-input" value="<?= $project['scope'] ?>" />
        </td>
    </tr>
    <tr>
        <td class="TableContent">4.立项项目类型
            （投标方式只针对经销，投标方式是根据最终客户订单的获取方式来判断，不是以经销商订单的获取来判断。）
        </td>
        <td class="TableData" colspan="3">
            <div class="layui-input-inline mandatory">
                <select id="project_type" name="project_type" lay-search>
                    <option value=''>请选择</option>
                    <?php foreach ($selectArr['project_type'] as $val) : ?>
                        <?php if($val['user_bu']!=='INHENERGY'){continue;} ?>
                        <option value="<?= $val['paras_value'] ?>" <?php if ($project['project_type'] == $val['paras_value']) : ?>selected<?php endif; ?>><?= $val['paras_desc'] ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
        </td>
    </tr>
    <tr>
        <td class="TableContent">5.样机要求（样机数量，交样时间）</td>
        <td class="TableData" colspan="3">
            <input type="text" name="prototype" id="prototype" class="layui-input" value="<?= $project['prototype'] ?>" />
        </td>
    </tr>
    <tr>
        <td class="TableContent">6.项目潜力（可持续性和重要性）</td>
        <td class="TableData" colspan="3">
            <textarea class="layui-textarea" id="potential" name="potential"><?= $project['potential'] ?></textarea>
        </td>
    </tr>
    <tr>
        <td class="TableContent">7.项目背景</td>
        <td class="TableData">
            <textarea class="layui-textarea" id="background" name="background"><?= $project['background'] ?></textarea>
        </td>
        <td class="TableContent" width="15%">添加附件：</td>
        <td class="TableData">
            <input id="background_attach" type="button" class="layui-btn layui-bg-gray btn-upload" style="display: inline!important;" value="上传" />
        </td>
    </tr>
    <tr>
        <td class="TableContent">8.主要商务条款（价格条款、付款方式、交期、质保）</td>
        <td class="TableData">
            <textarea class="layui-textarea" id="explain_project" name="explain_project"><?= $project['explain_project'] ?></textarea>
        </td>
        <td class="TableContent" width="15%">添加附件：</td>
        <td class="TableData">
            <input id="explain_project_attach" type="button" class="layui-btn layui-bg-gray btn-upload" style="display: inline!important;" value="上传" />
        </td>
    </tr>
    <tr>
        <td class="TableContent" colspan="4">9.项目需求产品、市场参考价格及客户目标价格</td>
    </tr>
    <? $i=$addProjectDetailCount = 0;foreach($selectArr['product_detail_type'] as $product_type): ?>
        <?if(!in_array($product_type['paras_value'],array('Inhenergy','Other'))){continue;}?>
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
                    <td>现有供应商</td>
                    <td>附件</td>
                </tr>
                
                <?php 
                foreach ($projectDetail[$product_type['paras_value']] as $tk => $v) :
                    $i++; ?>
                    <tr align="center">
                        <!-- <td class="TableData"><input type="hidden" name="serial_number" id="serial_number" class="layui-input" />1</td> -->
                        <td class="TableData">
                        <input type="hidden" name="type<?=$i?>" value="<?=$product_type['paras_value'] ?>" />
                            <input type="text" name="product<?= $i ?>" id="product" class="layui-input" value="<?= $v['product'] ?>" />
                            
                        </td>
                        <td class="TableData"><input type="text" name="price<?= $i ?>" id="price" class="layui-input" value="<?= $v['price'] ?>" /></td>
                        <td class="TableData"><input type="text" name="first_price<?= $i ?>" id="first_price" class="layui-input" value="<?= $v['first_price'] ?>" /></td>
                        <td class="TableData"><input type="text" name="standard<?= $i ?>" id="standard" class="layui-input" value="<?= $v['standard'] ?>" /></td>
                        <td class="TableData"><input type="text" name="number<?= $i ?>" id="number" class="layui-input" lay-verify="checknum" value="<?= $v['number'] ?>" /></td>
                        <td class="TableData"><input type="text" name="supplier<?= $i ?>" id="supplier" class="layui-input" value="<?= $v['supplier'] ?>" /></td>
                        <td class="TableData">
                            <input id="project_detail_attach<?= $i ?>" type="button" class="layui-btn layui-bg-gray btn-upload" style="display: inline!important;" value="上传" />
                            <a href="javascript:;" onclick="removeLine(this)">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;删除</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
                <tr align="left" id="addProjectDetail">
                    <td class="TableData" colspan="7">
                        <input value="添加" type=button class="layui-btn layui-btn-oa layui-btn-sm" onclick="addLine('addProjectDetail',this,'<?=$product_type['paras_value']?>');return false;" />
                    </td>
                </tr>
            </table>
        </td>
    </tr>
    <?php endforeach; ?>
    <input type="hidden" id="addProjectDetailCount" name="addProjectDetailCount" value="<?= $i ?>" />
    <tr>
        <td class="TableContent">10.服务要求（验厂，验货，培训等）</td>
        <td class="TableData" colspan="3">
            <textarea class="layui-textarea" id="service" name="service"><?= $project['service'] ?></textarea>
        </td>
    </tr>
    <!-- <tr>
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
    </tr> -->
    <tr>
        <td class="TableContent">本市场过往项目代号</td>
        <td class="TableData">
            <textarea class="layui-textarea required_field" id="past_pcode"  name="past_pcode" placeholder="此框必填，如无也要填无" <?if($changeStar!=1):?>lay-verify="required"<?endif?>><?= $main['past_pcode'] ?></textarea>
        </td>
        <td class="TableContent">本市场不同客户曾经出现过的项目代号</td>
        <td class="TableData">
            <textarea class="layui-textarea required_field" id="c_past_pcode" name="c_past_pcode" placeholder="此框必填，如无也要填无" <?if($changeStar!=1):?>lay-verify="required"<?endif?>><?= $main['c_past_pcode'] ?></textarea>
        </td>
    </tr>
</table>