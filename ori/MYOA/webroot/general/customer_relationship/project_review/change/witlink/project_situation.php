<table id="tab2" width="100%" style="text-align: left;">
    <tr>
        <td class="TableHeader" colspan="8" style="color: red;text-align:center">（二）项目情况</td>
    </tr>
    <tr>
        <td class="TableContent" width="15%">1.（银河/慧联）项目代号</td>
        <td class="TableData" width="35%">
            <input type="text" readonly name="project_code" id="project_code" class="layui-input" value="<?= $project['project_code'] ?>" autocomplete="off" <?if($changeStar!=1):?>lay-verify="required"<?endif;?> />
            <input type="hidden" readonly name="temp_project_code" id="temp_project_code" class="layui-input" value="<?= $project['temp_project_code'] ?>" autocomplete="off"  />
        </td>
        <td class="TableContent" width="15%">2.所在国</td>
        <td class="TableData" colspan="3">
            <input type="text" name="country" id="country" class="layui-input" placeholder="请输入项目所在国" value="<?= $project['country'] ?>" />
        </td>
    </tr>
    <tr>
        <td class="TableContent">3. 项目范围（AMI/VENDING/AGENT/MESSENGER/PAY/服务器/数据库/本地维护等）</td>
        <td class="TableData" colspan="5">
            <!-- <input type="text" name="scope" id="scope" class="layui-input" value="<?= $project['scope'] ?>" /> -->
            <textarea class="layui-textarea" id="scope" name="scope"><?= $project['scope'] ?></textarea>
        </td>
    </tr>
    <tr>
        <td class="TableContent">4.立项类型（ODM/直销（投标）/直销（直接采购）/经销（联合投标）/经销（直接采购））
        </td>
        <td class="TableData" colspan="5">
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
        <td class="TableContent">5. 软件演示要求（功能，演示时间）</td>
        <td class="TableData" colspan="5">
            <!-- <input type="text" name="prototype" id="prototype" class="layui-input" value="<?= $project['prototype'] ?>" /> -->
            <textarea class="layui-textarea" id="prototype" name="prototype"><?= $project['prototype'] ?></textarea>
        </td>
    </tr>
    <tr>
        <td class="TableContent" colspan="8">6.项目潜力（可持续性和重要性）</td>
    </tr>
    <tr>
        <td class="TableContent">6.1 客户类型（电力公司、私人售电公司（物业公司，酒店，学校）、农网）</td>
        <td class="TableData" >
            <textarea class="layui-textarea" id="customer_type" name="customer_type"><?= $projectWitlink['customer_type'] ?></textarea>
        </td>
        <td class="TableContent" width="15%">添加附件：</td>
        <td class="TableData" colspan="3">
            <input id="customer_type_attach" type="button" class="layui-btn layui-bg-gray btn-upload" style="display: inline!important;" value="上传" />
        </td>
    </tr>
    <tr>
        <td class="TableContent">6.2 用户数量</td>
        <td class="TableData" colspan="5">
            <textarea class="layui-textarea" id="num_users" name="num_users"><?= $projectWitlink['num_users'] ?></textarea>
        </td>
    </tr>
    <tr>
        <td class="TableContent">6.3 根据规划或目前用户的增长率评估未来5年后用户总数是多少？</td>
        <td class="TableData" colspan="5">
            <textarea class="layui-textarea" id="users_five_years_later" name="users_five_years_later"><?= $projectWitlink['users_five_years_later'] ?></textarea>
        </td>
    </tr>
    <tr>
        <td class="TableContent">6.4 客户首次购买系统，还是替换旧系统，基于什么原因更换掉旧系统？（系统问题，售后服务质量，收费问题）</td>
        <td class="TableData" colspan="5">
            <textarea class="layui-textarea" id="replacement_reason" name="replacement_reason"><?= $projectWitlink['replacement_reason'] ?></textarea>
        </td>
    </tr>
    <tr>
        <td class="TableContent">6.5 旧系统的供应商，历史收费方式，价格水平，SLA报价情况</td>
        <td class="TableData" colspan="5">
            <textarea class="layui-textarea" id="old_system_situation" name="old_system_situation"><?= $projectWitlink['old_system_situation'] ?></textarea>
        </td>
    </tr>
    <tr>
        <td class="TableContent">6.6 购买方式（永久许可，短期许可，计数许可）：</td>
        <td class="TableData" colspan="5">
            <textarea class="layui-textarea" id="purchase_method" name="purchase_method"><?= $projectWitlink['purchase_method'] ?></textarea>
        </td>
    </tr>
    <tr>
        <td class="TableContent" colspan="8">7 项目具体情况（技术需求确认，成本核算）</td>
    </tr>
    <tr>
        <td class="TableContent" width="15%">客户侧IT负责人</td>
        <td class="TableData" width="10%" >
            <input type="text" name="customer_it_leader" id="customer_it_leader" class="layui-input" value="<?= $projectWitlink['customer_it_leader'] ?>"  />
        </td>
        <td class="TableContent" >电话</td>
        <td class="TableData" >
            <input type="text" name="leader_phone" id="leader_phone" class="layui-input" placeholder="请输入电话" value="<?= $projectWitlink['leader_phone'] ?>" />
        </td>
        <td class="TableContent" width="10%">邮箱</td>
        <td class="TableData" >
            <input type="text" name="leader_email" id="leader_email" class="layui-input" placeholder="请输入邮箱" value="<?= $projectWitlink['leader_email'] ?>" />
        </td>
    </tr>
    <tr>
        <td class="TableContent">7.1 是否包括服务器、数据库供货？</td>
        <td class="TableData" colspan="5">
            <textarea class="layui-textarea" id="supply_server_database" name="supply_server_database" placeholder="是/否"><?= $projectWitlink['supply_server_database'] ?></textarea>
        </td>
    </tr>
    <tr>
        <td class="TableContent" colspan="8">数据库</td>
    </tr>
    <tr>
        <td class="TableContent" width="15%">类型</td>
        <td class="TableData" width="10%" >
            <textarea class="layui-textarea" id="database_type" name="database_type" ><?= $projectWitlink['database_type'] ?></textarea>
        </td>
        <td class="TableContent" >配置</td>
        <td class="TableData" >
            <textarea class="layui-textarea" id="database_config" name="database_config" ><?= $projectWitlink['database_config'] ?></textarea>
        </td>
        <td class="TableContent" width="10%">数量</td>
        <td class="TableData" >
            <textarea class="layui-textarea" id="database_num" name="database_num" ><?= $projectWitlink['database_num'] ?></textarea>
        </td>
    </tr>
    <tr>
        <td class="TableContent" colspan="8">服务器</td>
    </tr>
    <tr>
        <td class="TableContent" width="15%">型号</td>
        <td class="TableData" width="10%" >
            <textarea class="layui-textarea" id="server_model" name="server_model" ><?= $projectWitlink['server_model'] ?></textarea>
        </td>
        <td class="TableContent" >配置</td>
        <td class="TableData" >
            <textarea class="layui-textarea" id="server_config" name="server_config" ><?= $projectWitlink['server_config'] ?></textarea>
        </td>
        <td class="TableContent" width="10%">数量</td>
        <td class="TableData" >
            <textarea class="layui-textarea" id="server_num" name="server_num" ><?= $projectWitlink['server_num'] ?></textarea>
        </td>
    </tr>
    <tr>
        <td class="TableContent" colspan="8">部署方式</td>
    </tr>
    <tr>
        <td class="TableContent" width="15%">中央式</td>
        <td class="TableData" width="10%" >
            <textarea class="layui-textarea" id="central_type" name="central_type" ><?= $projectWitlink['central_type'] ?></textarea>
        </td>
        <td class="TableContent" >分布式</td>
        <td class="TableData" >
            <textarea class="layui-textarea" id="distributed" name="distributed" ><?= $projectWitlink['distributed'] ?></textarea>
        </td>
        <td class="TableContent" width="10%">云部署</td>
        <td class="TableData" >
            <textarea class="layui-textarea" id="cloud_deployment" name="cloud_deployment" ><?= $projectWitlink['cloud_deployment'] ?></textarea>
        </td>
    </tr>
    <tr>
        <td class="TableContent" colspan="8">7.2 定制化功能描述（系统语言，数据迁移，增加功能，第三方系统集成/硬件兼容，SLA维护要求）</td>
    </tr>
    <tr>
        <td class="TableContent" width="15%">定制化项目</td>
        <td class="TableContent" colspan="2">要求</td>
        <td class="TableContent" colspan="4">备注</td>
    </tr>
    <tr>
        <td class="TableContent" width="15%">OS语言</td>
        <td class="TableContent" colspan="2">（中/英/法/西/阿/俄）</td>
        <td class="TableData" colspan="4">
            <textarea class="layui-textarea" id="os_language" name="os_language"><?= $projectWitlink['os_language'] ?></textarea>
        </td>
    </tr>
    <tr>
        <td class="TableContent" width="15%">数据迁移</td>
        <td class="TableContent" colspan="2">(数据量，数据样本)</td>
        <td class="TableData" colspan="4">
            <textarea class="layui-textarea" id="data_migration" name="data_migration"><?= $projectWitlink['data_migration'] ?></textarea>
        </td>
    </tr>
    <tr>
        <td class="TableContent" width="15%">兼容第三方计量设备</td>
        <td class="TableContent" colspan="2">（厂家，设备，通讯方式，通讯协议,指令清单）</td>
        <td class="TableData" colspan="4">
            <textarea class="layui-textarea" id="compatible_equipment" name="compatible_equipment"><?= $projectWitlink['compatible_equipment'] ?></textarea>
        </td>
    </tr>
    <tr>
        <td class="TableContent" width="15%">兼容第三方安全模块</td>
        <td class="TableContent" colspan="2">STS6/TALES/虚拟/国产加密机</td>
        <td class="TableData" colspan="4">
            <textarea class="layui-textarea" id="compatible_module" name="compatible_module"><?= $projectWitlink['compatible_module'] ?></textarea>
        </td>
    </tr>
    <tr>
        <td class="TableContent" width="15%">集成第三方软件</td>
        <td class="TableContent" colspan="2">（Billing/GIS/CIS/CRM/ERP/HES等等）</td>
        <td class="TableData"  colspan="4">
            <textarea class="layui-textarea" id="integrated_software" name="integrated_software"><?= $projectWitlink['integrated_software'] ?></textarea>
        </td>
    </tr>
    <tr>
        <td class="TableContent" width="15%">定制API</td>
        <td class="TableContent" colspan="2">（定制化第三方接口文档）</td>
        <td class="TableData" colspan="4">
            <textarea class="layui-textarea" id="custom_api" name="custom_api"><?= $projectWitlink['custom_api'] ?></textarea>
        </td>
    </tr>
    <tr>
        <td class="TableContent" width="15%">其他</td>
        <td class="TableContent" colspan="2">其他特殊技术要求</td>
        <td class="TableData" colspan="4">
            <textarea class="layui-textarea" id="other_requirements" name="other_requirements"><?= $projectWitlink['other_requirements'] ?></textarea>
        </td>
    </tr>
    <tr>
        <td class="TableContent" colspan="8">7.3 实施方式（安装，培训，调试）</td>
    </tr>
    <tr>
        <td class="TableContent" width="15%">现场</td>
        <td class="TableData" width="10%" >
            <textarea class="layui-textarea" id="implement_scene" name="implement_scene" ><?= $projectWitlink['implement_scene'] ?></textarea>
        </td>
        <td class="TableContent" >时长</td>
        <td class="TableData" >
            <textarea class="layui-textarea" id="implement_scene_duration" name="implement_scene_duration" ><?= $projectWitlink['implement_scene_duration'] ?></textarea>
        </td>
        <td class="TableContent" width="10%">人员安排</td>
        <td class="TableData" >
            <textarea class="layui-textarea" id="implement_scene_person" name="implement_scene_person" ><?= $projectWitlink['implement_scene_person'] ?></textarea>
        </td>
    </tr>
    <tr>
        <td class="TableContent" width="15%">远程</td>
        <td class="TableData" width="10%" >
            <textarea class="layui-textarea" id="implement_long" name="implement_long" ><?= $projectWitlink['implement_long'] ?></textarea>
        </td>
        <td class="TableContent" >时长</td>
        <td class="TableData" >
            <textarea class="layui-textarea" id="implement_long_duration" name="implement_long_duration" ><?= $projectWitlink['implement_long_duration'] ?></textarea>
        </td>
        <td class="TableContent" width="10%">人员安排</td>
        <td class="TableData" >
            <textarea class="layui-textarea" id="implement_long_person" name="implement_long_person" ><?= $projectWitlink['implement_long_person'] ?></textarea>
        </td>
    </tr>
    <tr>
        <td class="TableContent" width="15%">代理实施</td>
        <td class="TableData" width="10%" >
            <textarea class="layui-textarea" id="implement_agent" name="implement_agent" ><?= $projectWitlink['implement_agent'] ?></textarea>
        </td>
        <td class="TableContent" >时长</td>
        <td class="TableData" >
            <textarea class="layui-textarea" id="implement_agent_duration" name="implement_agent_duration" ><?= $projectWitlink['implement_agent_duration'] ?></textarea>
        </td>
        <td class="TableContent" width="10%">人员安排</td>
        <td class="TableData" >
            <textarea class="layui-textarea" id="implement_agent_person" name="implement_agent_person" ><?= $projectWitlink['implement_agent_person'] ?></textarea>
        </td>
    </tr>
    <tr>
        <td class="TableContent" colspan="8">7.4 后期需求变更约束（功能修改，定制报表，系统更新，新增集成软件，新增兼容硬件）</td>
    </tr>
    <tr>
        <td class="TableData" colspan="6">
            <textarea class="layui-textarea" id="demand_change" name="demand_change"><?= $projectWitlink['demand_change'] ?></textarea>
        </td>
    </tr>
    <tr>
        <td class="TableContent">7.5 维护义务（SLA）是否收费？</td>
        <td class="TableData" colspan="5">
            <textarea class="layui-textarea" id="maintain_obligation" name="maintain_obligation" placeholder="是/否"><?= $projectWitlink['maintain_obligation'] ?></textarea>
        </td>
    </tr>
    <tr>
        <td class="TableContent" width="15%">方式（现场/远程）：</td>
        <td class="TableData" width="10%" >
            <input type="text"  name="maintain_mode" id="maintain_mode" class="layui-input" value="<?= $projectWitlink['maintain_mode'] ?>"  />
        </td>
        <td class="TableContent" >年限：</td>
        <td class="TableData" >
            <input type="text" name="maintain_years" id="maintain_years" class="layui-input" placeholder="" value="<?= $projectWitlink['maintain_years'] ?>" />
        </td>
        <td class="TableContent" width="10%">SLA服务等级</td>
        <td class="TableData" >
            <input type="text" name="service_level" id="service_level" class="layui-input" placeholder="" value="<?= $projectWitlink['service_level'] ?>" />
        </td>
    </tr>
    <tr>
        <td class="TableContent">8.EPC项目说明（分包商和监理介绍）</td>
        <td class="TableData" >
            <textarea class="layui-textarea" id="explain_user" name="explain_user"><?= $project['explain_user'] ?></textarea>
        </td>
        <td class="TableContent" width="15%">添加附件：</td>
        <td class="TableData" colspan="3">
            <input id="explain_user_attach" type="button" class="layui-btn layui-bg-gray btn-upload" style="display: inline!important;" value="上传" />
        </td>
    </tr>
    <tr>
        <td class="TableContent">9.EPC项目说明（项目工期，特殊资质和要求）</td>
        <td class="TableData" >
            <textarea class="layui-textarea" id="explain_project" name="explain_project"><?= $project['explain_project'] ?></textarea>
        </td>
        <td class="TableContent" width="15%">添加附件：</td>
        <td class="TableData" colspan="3">
            <input id="explain_project_attach" type="button" class="layui-btn layui-bg-gray btn-upload" style="display: inline!important;" value="上传" />
        </td>
    </tr>
    <tr>
        <td class="TableContent" colspan="8">10.项目需求产品、市场参考价格及客户目标价格</td>
    </tr>
    <?php  $i=$addProjectDetailCount = 0;foreach($selectArr['product_detail_type'] as $product_type): ?>
    <tr>
        <td class="TableContent" colspan="8"><?=$product_type[paras_desc]?></td>
    </tr>
    <tr>
        <td colspan="8">
            <table id="tab21" width="100%">
                <tr class="TableContent" style="text-align: center;">
                    <!-- <td>序号</td> -->
                    <td>软件产品</td>
                    <td>业务建议价格</td>
                    <td>历史价格</td>
                    <td>技术规格/标准</td>
                    <td>预期数量</td>
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
                            <a href="javascript:;" onclick="removeLine(this)">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;删除</a>
                        </td>
                        <td class="TableData"><input type="text" name="price<?= $i ?>" id="price" class="layui-input" value="<?= $v['price'] ?>" /></td>
                        <td class="TableData"><input type="text" name="first_price<?= $i ?>" id="first_price" class="layui-input" value="<?= $v['first_price'] ?>" /></td>
                        <td class="TableData"><input type="text" name="standard<?= $i ?>" id="standard" class="layui-input" value="<?= $v['standard'] ?>" /></td>
                        <td class="TableData"><input type="text" name="number<?= $i ?>" id="number" <?if($changeStar!=1):?>lay-verify="checknum"<?endif;?> class="layui-input" value="<?= $v['number'] ?>" /></td>
                        <td class="TableData">
                            <input id="project_detail_attach<?= $i ?>" type="button" class="layui-btn layui-bg-gray btn-upload" style="display: inline!important;" value="上传" />
                        </td>
                    </tr>
                <?php endforeach; ?>
                <tr align="left" id="addProjectDetail">
                    <td class="TableData" colspan="6">
                        <input value="添加" type=button class="layui-btn layui-btn-oa layui-btn-sm" onclick="addLine('addProjectDetail',this,'<?=$product_type['paras_value']?>');return false;" />
                    </td>
                </tr>
            </table>
        </td>
    </tr>
    <?php endforeach; ?>
    <input type="hidden" id="addProjectDetailCount" name="addProjectDetailCount" value="<?= $i ?>" />
    <tr>
        <td class="TableContent">11.服务要求（验厂，验货，培训等）</td>
        <td class="TableData" colspan="5">
            <textarea class="layui-textarea" id="service" name="service"><?= $project['service'] ?></textarea>
        </td>
    </tr>
    <tr>
        <td class="TableContent">是否为集抄项目</td>
        <td class="TableData"  colspan="5">
        <div class="layui-input-inline mandatory">
                <select id="is_collective" name="is_collective" lay-search <?if($changeStar!=1):?>lay-verify="required"<?endif?>>
                    <option value=''>请选择</option>
                    <option value="是" <?php if ($main['is_collective'] == '是') : ?>selected<?php endif; ?>>是</option>
                    <option value="否" <?php if ($main['is_collective'] == '否') : ?>selected<?php endif; ?>>否</option>
                </select>
            </div>
        </td>
    </tr>
    <tr>
        <td class="TableContent">本市场过往项目代号</td>
        <td class="TableData"  colspan="1">
            <textarea class="layui-textarea required_field" id="past_pcode"  name="past_pcode" placeholder="此框必填，如无也要填无" <?if($changeStar!=1):?>lay-verify="required"<?endif?>><?= $main['past_pcode'] ?></textarea>
        </td>
        <td class="TableContent">本市场不同客户曾经出现过的项目代号</td>
        <td class="TableData"  colspan="3">
            <textarea class="layui-textarea required_field" id="c_past_pcode" name="c_past_pcode" placeholder="此框必填，如无也要填无" <?if($changeStar!=1):?>lay-verify="required"<?endif?>><?= $main['c_past_pcode'] ?></textarea>
        </td>
    </tr>
</table>