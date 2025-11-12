<table id="tab_main" width="100%" style="text-align: center;">
    <tr>
        <td class="TableContent">
            <b>
                <font style="color: red;">项目代号</font>
            </b>
        </td>
        <td class="TableData">
            <input type="text" name="project_code" id="project_name" value="<?= $main['project_code'] ?>" class="layui-input mandatory" autocomplete="off" />
        </td>
        <td class="TableContent">价格条款</td>
        <td class="TableData" align="left">
            <div class="layui-input-inline mandatory">
                <select id="price_terms" name="price_terms" lay-filter="price_terms" lay-search>
                    <option value="">请选择</option>
                    <?php foreach ($selectArr['price_terms'] as $val) : ?>
                        <option value="<?= $val['paras_value'] ?>" <?php if ($val['paras_value'] == $main['price_terms']) : ?>selected<?php endif; ?>><?= $val['paras_desc'] ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
        </td>
        <td class="TableContent">货币单位</td>
        <td class="TableData" align="left">
            <div class="layui-input-inline mandatory">
                <select id="currency" name="currency" lay-filter="currency" lay-search>
                    <option value="">请选择</option>
                    <?php foreach ($selectArr['currency'] as $val) : ?>
                        <option value="<?= $val['paras_value'] ?>" <?php if ($val['paras_value'] == $main['currency']) : ?>selected<?php endif; ?>><?= $val['paras_desc'] ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
        </td>
        <td class="TableContent">汇率</td>
        <td class="TableData">
            <input type="text" name="exchange_rate" id="exchange_rate" class="layui-input" value="<?= $main['exchange_rate'] ?>" />
        </td>
    </tr>
    <tr>
        <td class="TableContent">业务归属</td>
        <td class="TableData">
            <input type="text" name="business_belong" id="business_belong" class="layui-input" value="<?= $main['business_belong'] ?>" />
        </td>
        <td class="TableContent">报价时间</td>
        <td class="TableData">
            <input type="text" name="quotation_time" id="quotation_time" class="layui-input" value="<?= $main['valid_days'] ?>" />
        </td>
        <td class="TableContent">报价有效期</td>
        <td class="TableData">
            <input type="text" name="valid_days" id="valid_days" class="layui-input" value="<?= $main['valid_days'] ?>" />
        </td>
        <td class="TableContent">报价单号</td>
        <td class="TableData">
        <input type="text" name="form_id" id="form_id" class="layui-input" lay-verify='formIdReg' value="<?= $main['form_id'] ?>"   />
        </td>
    </tr>
    <tr>
        <td class="TableContent"> 项目概况</td>
        <td class="TableData" colspan="7">
            <textarea  name="project_survey" id="project_survey" class="layui-textarea"><?= $main['project_survey'] ?></textarea>
        </td>
    </tr>
    
</table>