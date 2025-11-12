<table id="tab1" width="100%" style="text-align: center;">
    <tr>
        <td class="TableContent" width="10%">客户名称</td>
        <td class="TableData" width="15%">
            <input type="text" name="customer_name" id="customer_name" class="layui-input" <?if(in_array($form_type,array('02'))):?>readonly="readonly"<?endif;?> />
           
        </td>
        <td class=" TableContent" width="10%">项目代号</td>
        <td class="TableData" width="15%">
            <input type="text" name="project_code" id="project_code" class="layui-input" <?if(in_array($form_type,array('02'))):?>readonly="readonly"<?endif;?> />
        </td>
        <td class=" TableContent" width="10%">项目编号</td>
        <td class="TableData" width="15%">
            <input type="text" name="project_num" id="project_num" class="layui-input" <?if(!in_array($form_type,array('02'))):?>lay-verify="required"<?else:?>readonly="readonly"<?endif;?> />
        </td>
        <td class=" TableContent" width="10%">订单编号</td>
        <td class="TableData">
            <input type="text" name="order_num" id="order_num" class="layui-input" <?if(in_array($form_type,array('02'))):?>readonly="readonly"<?endif;?>/>
            <input type="hidden" name="order_form_id" id="order_form_id" class="layui-input" />
        </td>
    </tr>
    <tr>
        <td class="TableContent">合同相对方</td>
        <td class="TableData">
            <input type="text" name="contract_party" id="contract_party" class="layui-input" <?if(in_array($form_type,array('02'))):?>readonly="readonly"<?endif;?> <?if(in_array($form_type,array('04'))):?>lay-verify="required"<?endif;?>/>
        </td>
        <td class=" TableContent">交货时间</td>
        <td class="TableData">
            <input type="text" name="delivery_time" id="delivery_time" class="layui-input date" />
        </td>
        <td class=" TableContent">申请部门</td>
        <td class="TableData">
            <input type="text" name="apply_dept" id="apply_dept" class="layui-input" value="<?= $userInfo['deptName'] ?>" />
        </td>
        <td class=" TableContent">申请人</td>
        <td class="TableData">
            <input type="text" name="apply_user" id="apply_user" class="layui-input" value="<?= $userInfo['USER_NAME'] ?>" />
        </td>
    </tr>
    <tr>
        <td class="TableContent">生产通知单类型</td>
        <td class="TableData" align="left">
        <input name="form_type" type="hidden" value="<?=$form_type?>">
            <input name="form_type_name" type="text" class="layui-input" value="<?=$form_type_arr[$form_type]?>">
        </td>
        <td class="TableContent">生产通知单单号</td>
        <td class="TableData">
            <!-- <input type="text" name="form_id" id="form_id" class="layui-input" /> -->
        </td>
        <td class="TableContent">进口国清关要求（认证、文件、第三方检验等）</td>
        <td class="TableData">
            <input type="text" name="if_third" id="if_third" class="layui-input" />
        </td>
        <td class=" TableContent">验货类型</td>
        <td class="TableData" colspan="3" align="left">
            <div class="layui-input-inline">
                <select id="inspection_type" name="inspection_type" lay-search>
                    <option value=''>请选择</option>
                    <?php foreach ($selectArr['inspection_type'] as $v) : ?>
                        <option value="<?= $v['paras_value'] ?>"><?= $v['paras_desc'] ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
        </td>
    </tr>
</table>