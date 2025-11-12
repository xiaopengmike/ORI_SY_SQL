<table id="tab1" width="100%" style="text-align: center;">
    <tr>
    <td class=" TableContent" width="10%">订单编号</td>
        <td class="TableData">
            <input readonly type="text" name="order_num" id="order_num" class="layui-input" value="<?= $main['order_num'] ?>" />
            <input type="hidden" name="order_form_id" id="order_form_id" class="layui-input" value="<?= $main['order_form_id'] ?>"/>
        </td>
        <td class="TableContent" width="11%">客户名称</td>
        <td class="TableData" width="18%">
            <input type="text" name="customer_name" id="customer_name" class="layui-input" value="<?= $main['customer_name'] ?>" />
        </td>
        <td class=" TableContent" width="10%">项目代号</td>
        <td class="TableData" width="14%">
            <input readonly type="text" name="project_code" id="project_code" class="layui-input" value="<?= $main['project_code'] ?>" />
        </td>
        <td class=" TableContent" width="10%">项目编号</td>
        <td class="TableData" width="14%">
            <input readonly type="text" name="project_num" id="project_num" class="layui-input" value="<?= $main['project_num'] ?>" <?if(!in_array($company,array('IMCINHE'))):?>lay-verify="required"<?endif;?> />
        </td>
       
    </tr>
    <tr>
    <td class=" TableContent" >销售合同编号</td>
        <td class="TableData">
            <input type="text" name="sale_order_no" id="sale_order_no" class="layui-input" value="<?= $main['sale_order_no'] ?>" readonly />
        </td>
        <td class="TableContent">合同相对方</td>
        <td class="TableData">
            <input type="text" name="contract_party" id="contract_party" class="layui-input" value="<?= $main['contract_party'] ?>" <?if(!in_array($company,array('IMCINHE'))):?>lay-verify="required"<?endif;?> />
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
        <td class="TableContent">项目星级</td>
        <td class="TableData" align="left">
            <input type="hidden" name="project_star" value="<?= $main['project_star'] ?>" />
            <div id="project_star">
                <font style="color: red;font-size:20px"><?= $project_star[$main['project_star']]['paras_desc'] ?></font>
            </div>
            <!-- <div class="layui-input-inline">
                <select id="project_star" name="project_star" lay-filter="project_level" lay-search>
                    <option value="">请选择</option>
                    <?php foreach ($selectArr['project_star'] as $v) : ?>
                        <option value="<?= $v['paras_value'] ?>" <?php if ($v['paras_value'] == $main['project_star']) : ?>selected<?php endif; ?>><?= $v['paras_desc'] ?></option>
                    <?php endforeach; ?>
                </select>
            </div> -->
        </td>
        <td class=" TableContent">星级图标</td>
        <td class="TableData" align="left">
            <div id="star_icon" data-type="star_icon">
                <font style="color: red;font-size:20px"><?= $project_star[$main['project_star']]['extra'] ?></font>
            </div>
        </td>
        <td class=" TableContent">合同归属公司</td>
        <td class="TableData" colspan="3" align="left">
            <input readonly type="text" name="contract_belong" id="contract_belong" class="layui-input" value="<?= $main['contract_belong'] ?>" />
        </td>
    </tr>
    <tr>
    <td class=" TableContent">生产通知单单号</td>
        <td class="TableData" align="left">
            <?= $main['form_id'] ?>
            <!-- <input readonly type="text" name="form_id" id="form_id" class="layui-input" value="<?= $main['form_id'] ?>" /> -->
        </td>
        <td class=" TableContent">交货时间</td>
        <td class="TableData">
            <input type="text" name="delivery_time" id="delivery_time" class="layui-input date" value="<?= $main['delivery_time'] ?>" />
        </td>
        <td class="TableContent">进口国清关要求（认证、文件、第三方检验等）</td>
        <td class="TableData">
            <input type="text" name="third_inspection" id="third_inspection" class="layui-input" value="<?= $main['third_inspection'] ?>" />
        </td>
        
        <td class=" TableContent">验货类型</td>
        <td class="TableData" align="left">
            <div class="layui-input-inline">
                <select id="inspection_type" name="inspection_type" lay-search>
                    <option value=''>请选择</option>
                    <?php foreach ($selectArr['inspection_type'] as $v) : ?>
                        <option value="<?= $v['paras_value'] ?>" <?php if ($v['paras_value'] == $main['inspect_type']) : ?>selected<?php endif; ?>><?= $v['paras_desc'] ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
        </td>
    </tr>
   
</table>