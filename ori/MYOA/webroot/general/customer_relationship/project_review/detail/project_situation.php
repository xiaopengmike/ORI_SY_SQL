<table id="tab2" width="100%" style="text-align: left;">
    <tr>
        <td class="TableHeader" colspan="8" style="color: red;text-align:center">（二）项目情况</td>
    </tr>
    <tr>
        <td class="TableContent" width="15%">1.银河项目代号</td>
        <td class="TableData" width="35%">
            <input type="text" name="project_code" id="project_code" class="layui-input" value="<?= $project['project_code'] ?>" />
        </td>
        <td class="TableContent" width="15%">2.项目所在国</td>
        <td class="TableData">
            <input type="text" name="country" id="country" class="layui-input" placeholder="请输入项目所在国" value="<?= $project['country'] ?>" />
        </td>
    </tr>
    <tr>
        <td class="TableContent">3.项目范围（供应和服务内容：电表/AMI交付/EPC）</td>
        <td class="TableData" colspan="3">
            <input type="text" name="scope" id="scope" class="layui-input" value="<?= $project['scope'] ?>" />
        </td>
    </tr>
    <tr>
        <td class="TableContent">4.立项项目类型
            （投标方式只针对经销，投标方式是根据最终客户订单的获取方式来判断，不是以经销商订单的获取来判断。）
        </td>
        <td class="TableData" colspan="3">
            <textarea class="layui-textarea" id="project_type" name="project_type"><?= $project_type[$project['project_type']] ?></textarea>
            <!-- <input type="hidden" name="project_type" id="project_type" class="layui-input" value="<?= $project['project_type'] ?>" /> -->
            <!-- <?= $project_type[$project['project_type']] ?> -->
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
            <!-- <?= $project['potential'] ?> -->
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
            <input id="background_attach" type="button" class="layui-btn layui-bg-gray btn-upload" style="display: none;" value="上传" readonly />
        </td>
    </tr>
    <tr>
        <td class="TableContent">8.EPC项目说明（分包商和监理介绍）</td>
        <td class="TableData">
            <textarea class="layui-textarea" id="explain_user" name="explain_user"><?= $project['explain_user'] ?></textarea>
        </td>
        <td class="TableContent" width="15%">添加附件：</td>
        <td class="TableData">
            <input id="explain_user_attach" type="button" class="layui-btn layui-bg-gray btn-upload" style="display: none;" value="上传" readonly />
        </td>
    </tr>
    <tr>
        <td class="TableContent">9.EPC项目说明（项目工期，特殊资质和要求）</td>
        <td class="TableData">
            <textarea class="layui-textarea" id="explain_project" name="explain_project"><?= $project['explain_project'] ?></textarea>
        </td>
        <td class="TableContent" width="15%">添加附件：</td>
        <td class="TableData">
            <input id="explain_project_attach" type="button" class="layui-btn layui-bg-gray btn-upload" style="display: none;" value="上传" readonly />
        </td>
    </tr>
    <tr>
        <td class="TableContent" colspan="4">10.项目需求产品、市场参考价格及客户目标价格</td>
    </tr>
    <? $line_num=0;foreach($selectArr['product_detail_type'] as $product_type): if(count($projectDetail[$product_type['paras_value']])<=0){continue;};?>
    <tr>
        <td class="TableContent" colspan="4"><?=$product_type[paras_desc]?></td>
    </tr>
    <tr>
        <td colspan="4">
            <table id="tab21" width="100%">
                <tr class="TableContent" style="text-align: center;">
                    <td width="4%">序号</td>
                    <td width="11%">项目需求产品</td>
                    <td width="9%">业务建议价格</td>
                    <td width="9%">历史投/开标价格</td>
                    <td width="32%">技术规格/标准</td>
                    <td width="6%">预期数量</td>
                    <td>附件</td>
                </tr>
                <?php $an = 0;
                foreach ($projectDetail[$product_type['paras_value']] as $pd) : $an++;?>
                    <tr align="center">
                        <td class="TableData"><?= $an ?></td>
                        <td class="TableData"><?= $pd['product'] ?></td>
                        <td class="TableData"><?= $pd['price'] ?></td>
                        <td class="TableData"><?= $pd['first_price'] ?></td>
                        <td class="TableData"><?= $pd['standard'] ?></td>
                        <td class="TableData"><?= $pd['number'] ?></td>
                        <td class="TableData">
                            <input id="project_detail_attach<?= $pd['serial_number'] ?>" type="button" class="layui-btn layui-bg-gray btn-upload" style="display: none;" value="上传" readonly />
                        </td>
                    </tr>
                <?php
                endforeach; ?>
            </table>
        </td>
    </tr>
    <?php endforeach;?>
    <tr>
        <td class="TableContent">11.服务要求（验厂，验货，培训等）</td>
        <td class="TableData" colspan="3">
            <textarea class="layui-textarea" id="service" name="service"><?= $project['service'] ?></textarea>
        </td>
    </tr>
    <tr>
        <td class="TableContent">是否为集抄项目</td>
        <td class="TableData" colspan="3">
        <input type="text" class="layui-input" value="<?= $main['is_collective'] ?>" />
        <input type="hidden" name="is_collective" id="is_collective" class="layui-input" value="<?= $main['is_collective'] ?>" />
        </td>
    </tr>
    <tr>
        <td class="TableContent">本市场过往项目代号</td>
        <td class="TableData">
            <textarea class="layui-textarea" id="past_pcode"  name="past_pcode"  ><?= $main['past_pcode'] ?></textarea>
        </td>
        <td class="TableContent">本市场不同客户曾经出现过的项目代号</td>
        <td class="TableData">
            <textarea class="layui-textarea" id="c_past_pcode" name="c_past_pcode" ><?= $main['c_past_pcode'] ?></textarea>
        </td>
    </tr>
</table>