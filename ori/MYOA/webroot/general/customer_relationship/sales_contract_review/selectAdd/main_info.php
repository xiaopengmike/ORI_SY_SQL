<table id="tab_main" width="100%" style="text-align: center;">
    <tr>
        <td class="TableContent" >客户名称</td>
        <td class="TableData" >
            <input type="text" name="customer_name"  id="customer_name" class="layui-input" value="<?= str_replace("\"","&quot;", $main['customer_name']) ?>" <?=$main[user_bu]=='INHENERGY'||$main[user_bu]=='HKNERGY'?'readonly':''?>/>
        </td>
        <td class="TableContent" >合同相对方</td>
        <td class="TableData" >
            <input type="text" name="contract_party" id="contract_party" class="layui-input" value="<?= str_replace("\"","&quot;", $main['contract_party']) ?>" lay-verify="required" <?=$main[user_bu]=='INHENERGY'||$main[user_bu]=='HKNERGY'?'readonly':''?>/>
        </td>
        <td class="TableContent" >合同归属公司</td>
        <td class="TableData" align="left" >
            <input readonly type="text" name="contract_belong" id="contract_belong" class="layui-input" value="<?= $main['contract_belong'] ?>" />
        </td>
        <td class="TableContent" ><?=$main['user_bu']=='INHENERGY'||$main['user_bu']=='HKNERGY'?'销售合同编号':'编号'?></td>
        <td class="TableData" align="left">
            <input type="hidden" name="related_id" id="related_id" class="layui-input" value="<?= $main['related_id']?$main['related_id']:$main['form_id'] ?>" />
            <?= $main['related_id']?$main['related_id']:$main['form_id'] ?>
        </td>
    </tr>
    <tr>
        <td class="TableContent">
            <b>
                <font style="color: red;">项目名称</font>
            </b>
        </td>
        <td class="TableData">
            <input readonly type="text" name="project_name" id="project_name" class="layui-input" value="<?= $main['project_name'] ?>" />
        </td>
        <td class="TableContent">项目编号</td>
        <td class="TableData">
            <input <?if(strpos($main['type'], '_change') != false):?>readonly<?endif;?> type="text" name="project_number" id="project_number" class="layui-input" value="<?= $main['project_number'] ?>" <?if($main['user_bu']!='INHEGRID'):?>lay-verify="required"<?endif;?> />
        </td>
        <td class="TableContent">币种</td>
        <td class="TableData" align="left">
            <div class="layui-input-inline">
                <select id="currency" name="currency" lay-search lay-verify="required">
                    <option value="">请选择</option>
                    <?php foreach ($selectArr['currency'] as $val) : ?>
                        <option value="<?= $val['paras_value'] ?>" <?php if ($val['paras_value'] == $main['currency']) : ?>selected<?php endif; ?>><?= $val['paras_desc'] ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
        </td>
        <td class="TableContent">交货时间</td>
        <td class="TableData">
            <input type="text" name="delivery_time" id="delivery_time" class="layui-input" value="<?= $main['delivery_time'] ?>" />
        </td>
    </tr>
    <tr>
        <td class="TableContent">项目星级</td>
        <td class="TableData" align="left">
            <div class="layui-input-inline">
                <select id="project_star" name="project_star" lay-filter="project_level" lay-search>
                    <option value="">请选择</option>
                    <?php foreach ($selectArr['project_star'] as $val) : ?>
                        <option value="<?= $val['paras_value'] ?>" <?php if ($val['paras_value'] == $main['project_star']) : ?>selected<?php endif; ?>><?= $val['paras_desc'] ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
        </td>
        <?if($main['user_bu']=='INHENERGY'||$main[user_bu]=='HKNERGY'):?>
            <td class="TableContent">星级图标</td>
            <td class="TableData"  align="left">
                <div data-type="star_icon">
                    <font style="color: red;font-size:20px"><?= $project_star[$main['project_star']]['extra'] ?></font>
                </div>
            </td>
            <td class="TableContent">合同类型</td>
            <td class="TableData" align="left">
                <select id="contract_type" name="contract_type" lay-verify="required" >
                    <option value="">请选择</option>
                    <option value="1" <?php if ($main['contract_type'] == 1) : ?>selected<?php endif; ?>>批量合同</option>
                    <option value="2" <?php if ($main['contract_type'] == 2) : ?>selected<?php endif; ?>>样机合同</option>
                </select>
            </td>
            <td class="TableContent">客户采购订单编号</td>
            <td class="TableData" align="left">
                <input type="text" name="customer_order_no" id="customer_order_no" value="<?=$main['customer_order_no']?>" class="layui-input" />
            </td>
        <?else:?>
            <td class="TableContent">星级图标</td>
            <td class="TableData" colspan="3" align="left">
                <div data-type="star_icon">
                    <font style="color: red;font-size:20px"><?= $project_star[$main['project_star']]['extra'] ?></font>
                </div>
            </td>
            <td class="TableContent">销售合同编号
            <br/>
                <span style="font-size:12px;color:red"><b>（须与实际双签的合同编号保持一致）</b><span>
            </td>
            <td class="TableData" align="left">
                <input type="text" name="customer_order_no" id="customer_order_no" value="<?=$main['customer_order_no']?>" class="layui-input"  lay-verify="required"/>
            <!-- <?if($main['type']=='sales_contract_review'):?>
                <input type="text" name="customer_order_no" id="customer_order_no" value="<?=$main['customer_order_no']?>" class="layui-input"  lay-verify="required"/>
                <?else:?>
                <?=$main['customer_order_no']?><input type="hidden" name="customer_order_no" id="customer_order_no" value="<?=$main['customer_order_no']?>" class="layui-input"  />
            <?endif;?> -->
            </td>
        <?endif;?>
        
    </tr>
</table>