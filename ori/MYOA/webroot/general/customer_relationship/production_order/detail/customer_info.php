<table id="tab1" width="100%" style="text-align: center;">
    <tr>
        <td class="TableContent" width="11%">客户名称</td>
        <td class="TableData" width="20%">
            <?= $isHide&&$isHide2!=true?'':$main['customer_name'] ?>
            <!-- <input type="text" name="customer_name" id="customer_name" class="layui-input" value="<?= $main['customer_name'] ?>" /> -->
        </td>
        <td class=" TableContent" width="10%">项目代号</td>
        <td class="TableData" width="14%">
            <?= $main['project_code'] ?>
            <!-- <input type="text" name="project_code" id="project_code" class="layui-input" value="<?= $main['project_code'] ?>" /> -->
        </td>
        <td class=" TableContent" width="10%">项目编号</td>
        <td class="TableData" width="14%">
            <?= $main['project_num'] ?>
            <!-- <input type="text" name="project_num" id="project_num" class="layui-input" value="<?= $main['project_num'] ?>" /> -->
        </td>
        <td class=" TableContent" width="10%">订单编号</td>
        <td class="TableData">
            <?= $main['order_num'] ?>
            <!-- <input type="text" name="order_num" id="order_num" class="layui-input" value="<?= $main['order_num'] ?>" /> -->
        </td>
    </tr>
    <tr>
        <td class="TableContent">合同相对方</td>
        <td class="TableData">
            <?= $isHide&&$isHide2!=true?'':$main['contract_party'] ?>
            <!-- <input type="text" name="contract_party" id="contract_party" class="layui-input" value="<?= $main['contract_party'] ?>" /> -->
        </td>
        <td class=" TableContent">交货时间</td>
        <td class="TableData">
            <?= $main['delivery_time'] ?>
            <!-- <input type="text" name="delivery_time" id="delivery_time" class="layui-input" value="<?= $main['delivery_time'] ?>" /> -->
        </td>
        <td class=" TableContent">申请部门</td>
        <td class="TableData">
            <?= $main['apply_dept'] ?>
            <!-- <input type="text" name="apply_dept" id="apply_dept" class="layui-input" value="<?= $userInfo['create'] ?>" /> -->
        </td>
        <td class=" TableContent">申请人</td>
        <td class="TableData">
            <?= $main['apply_user'] ?>
            <!-- <input type="text" name="apply_user" id="apply_user" class="layui-input" value="<?= $userInfo['apply_user'] ?>" /> -->
        </td>
    </tr>
    <tr>
    
        <td class="TableContent">进口国清关要求（认证、文件、第三方检验等）</td>
        <td class="TableData">
            <?= $main['third_inspection'] ?>
            <!-- <input type="text" name="third_inspection" id="third_inspection" class="layui-input" value="<?= $main['third_inspection'] ?>" /> -->
        </td>
        <td class=" TableContent">生产通知单单号</td>
        <td class="TableData">
            <?= $main['form_id'] ?>
            <!-- <input type="text" name="form_id" id="form_id" class="layui-input" value="<?= $main['form_id'] ?>" /> -->
        </td>
        <td class=" TableContent">验货类型</td>
        <td class="TableData" >
            <?= $inspection_type[$main['inspect_type']] ?>
            <!-- <input type="text" name="inspect_type" id="inspect_type" class="layui-input" value="<?= $main['inspect_type'] ?>" /> -->
        </td>
        <td class=" TableContent" >销售合同编号</td>
        <td class="TableData">
            <?= $main['sale_order_no'] ?>
        </td>
    </tr>
    <tr>
    <?php if (!empty($main['form_type'])) : ?>
        <td class="TableContent">生产通知单类型</td>
        <td class="TableData" >
        <?php foreach ($selectArr['form_type'] as $v) : ?>
            <?php if ($v['paras_value'] == $main['form_type']) : ?><?= $v['paras_desc'] ?><?php endif; ?>
         <?php endforeach; ?>
        
        </td>
    <?php endif; ?>
        <td class="TableContent">项目星级</td>
        <td class="TableData">
            <font style="color: red;font-size:20px"><?= $psArr[$main['project_star']]['paras_desc'] ?></font>
            <!-- <input type="text" name="project_star" id="project_star" class="layui-input" value="<?= $psArr[$main['project_star']]['paras_desc'] ?>" /> -->
        </td>
        <td class=" TableContent">星级图标</td>
        <td class="TableData">
            <div data-type="star_icon">
                <font style="color: red;font-size:20px"><?= $psArr[$main['project_star']]['extra'] ?></font>
            </div>
        </td>
        <td class=" TableContent">合同归属公司</td>
        <td class="TableData" <? if (empty($main['form_type'])) : ?>colspan="3"<?endif;?>>
            <b>
                <div data-type="star_icon">
                    <font style="color: red;font-size:15px"><?= $main['contract_belong'] ?></font>
                </div>
            </b>
        </td>
    </tr>
</table>