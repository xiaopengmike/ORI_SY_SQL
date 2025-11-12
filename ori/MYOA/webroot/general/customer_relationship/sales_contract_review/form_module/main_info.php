<table id="tab_main" width="100%" style="text-align: center;">
    <tr>
    <td class="TableContent">
            <b>
                <font style="color: red;">项目名称</font>
            </b>
        </td>
        <td class="TableData">
            <input type="text" readonly name="project_name" id="project_name" class="layui-input mandatory" autocomplete="off"  <?if($company!='INHEGRID'&&$company!='INHENERGY'&&$company!='HKNERGY'):?>lay-verify="required" <?endif;?>/>
        </td>
        <td class="TableContent tips" tips_class="form_id" tips_msg="先通过销售合同取号，才能选择"><?=$company=='INHENERGY'||$company=='HKNERGY'?'销售合同编号':'编号'?><i style="color:blue" class="layui-icon layui-icon-about"></i></td>
        <td class="TableData">
            <input type="text" name="form_id" id="form_id" class="layui-input" lay-verify='formIdReg'/>
        </td>
        <td class="TableContent">客户名称</td>
        <td class="TableData" align="left">
            <div class="layui-input-inline mandatory">
                <select id="customer_name" name="customer_name" lay-filter="customer_name" lay-search lay-verify="required" >
                    <option value="">请选择</option>
                    <?php foreach ($customerSelect as $cusId => $cusName) : ?>
                        <?if($company!='INHEGRID'&&$company!='INHENERGY'&&$company!='HKNERGY'){continue;}?>
                        <option value="<?= str_replace("\"","&quot;", $cusName) ?>" ><?= $cusName ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <!-- <div id="customer_name2" name="customer_name2" lay-filter="customer_name2" lay-search style="display: none !important;">
                    <option value="">请选择</option>
                    <?php foreach ($customerSelect as $cusId => $cusName) : ?>
                        <?if($company!='INHEGRID'&&$company!='INHENERGY'&&$company!='HKNERGY'){continue;}?>
                        <option value="<?= $cusName ?>" ><?= $cusName ?></option>
                    <?php endforeach; ?>
                    </div> -->
        </td>
        <td class="TableContent">合同相对方</td>
        <td class="TableData" align="left">
            <div class="layui-input-inline">
                <select id="contract_party" name="contract_party" lay-search lay-verify="required">
                    <option value="">请选择</option>
                    <?php foreach ($selectArr['contract_party'] as $val) : ?>
                        <option value="<?= $val['paras_value'] ?>"><?= $val['paras_desc'] ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
        </td>
    </tr>
    <tr>
    <td class="TableContent">合同归属公司</td>
        <td class="TableData">
            <input type="text" name="contract_belong" id="contract_belong" class="layui-input" value="<?= $userInfo['companyName'] ?>" />
        </td>
        <td class="TableContent">项目编号</td>
        <td class="TableData">
            <input type="text" name="project_number" id="project_number" class="layui-input" <?if($company!='INHEGRID'):?>lay-verify="required"<?endif;?> />
        </td>
        <td class="TableContent">币种</td>
        <td class="TableData" align="left">
            <div class="layui-input-inline mandatory">
                <select id="currency" name="currency" lay-filter="currency" lay-search lay-verify="required">
                    <option value="">请选择</option>
                    <?php foreach ($selectArr['currency'] as $cVal) : ?>
                        <option value="<?= $cVal['paras_value'] ?>"><?= $cVal['paras_desc'] ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
        </td>
        <td class="TableContent">交货时间</td>
        <td class="TableData">
            <input type="text" name="delivery_time" id="delivery_time" class="layui-input" />
        </td>
    </tr>
    <tr>
        <td class="TableContent">项目星级</td>
        <td class="TableData" align="left">
            <input type="hidden" name="project_star" id="project_star" class="layui-input" />
            <div data-type="project_star"></div>
        </td>
        
        <?if($company=='INHENERGY'||$company=='HKNERGY'):?>
            <td class="TableContent">星级图标</td>
            <td class="TableData"  align="left">
                <div id="star_icon"></div>
            </td>
            <td class="TableContent">合同类型</td>
            <td class="TableData" align="left">
                <select id="contract_type" name="contract_type" lay-verify="required" >
                    <option value="">请选择</option>
                    <option value="1">批量合同</option>
                    <option value="2">样机合同</option>
                </select>
            </td>
            <td class="TableContent">客户采购订单编号</td>
            <td class="TableData" align="left">
                <input type="text" name="customer_order_no" id="customer_order_no" class="layui-input" />
            </td>
        <?else:?>
            <td class="TableContent">星级图标</td>
            <td class="TableData" colspan="3" align="left">
                <div id="star_icon"></div>
            </td>
            <td class="TableContent">销售合同编号
                <br/>
                <span style="font-size:12px;color:red"><b>（须与实际双签的合同编号保持一致）</b><span>
            </td>
            <td class="TableData" align="left">
                <input type="text" name="customer_order_no" id="customer_order_no" class="layui-input" lay-verify="required"/>
            </td>
        <?endif;?>
    </tr>
</table>