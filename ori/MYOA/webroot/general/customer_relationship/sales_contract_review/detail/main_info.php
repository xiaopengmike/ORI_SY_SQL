<table id="tab_main" width="100%" style="text-align: center;">
    <tr>
        <td class="TableContent" width="10%">客户名称</td>
        <td class="TableData" width="15%">
            <input type="text" name="customer_name" id="customer_name" class="layui-input" value="<?= $isHide&&$isHide2!=true?'':str_replace("\"","&quot;", $main['customer_name']) ?>" />
        </td>
        <td class="TableContent" width="10%">合同相对方</td>
        <td class="TableData" width="15%">
            <input type="text" name="contract_party" id="contract_party" class="layui-input" value="<?= $isHide&&$isHide2!=true?'':str_replace("\"","&quot;", $main['contract_party']) ?>" />
        </td>
        <td class="TableContent" width="10%">合同归属公司</td>
        <td class="TableData" width="15%">
            <b>
                <div data-type="star_icon">
                    <font style="color: red;font-size:15px"><?= $main['contract_belong'] ?></font>
                </div>
            </b>
        </td>
        <td class="TableContent" width="10%"><?=$main['user_bu']=='INHENERGY'||$main['user_bu']=='HKNERGY'?'销售合同编号':'编号'?></td>
        <td class="TableData">
            <?php if ($main['related_id']) {
                echo $main['related_id'];
            } else {
                echo $main['form_id'];
            } ?>
        </td>
    </tr>
    <tr>
        <td class="TableContent">
            <b>
                <font style="color: red;">项目名称</font>
            </b>
        </td>
        <td class="TableData">
            <input type="text" name="project_name" id="project_name" class="layui-input" value="<?= $main['project_name'] ?>" />
        </td>
        <td class="TableContent">项目编号</td>
        <td class="TableData">
            <input type="text" name="project_number" id="project_number" class="layui-input" value="<?= $main['project_number'] ?>" />
        </td>
        <td class="TableContent">币种</td>
        <td class="TableData">
            <?= $currency[$main['currency']]['paras_desc'] ?>
            <!-- <input type="text" name="currency" id="currency" class="layui-input" value="<?= $main['currency'] ?>" /> -->
        </td>
        <td class="TableContent">交货时间</td>
        <td class="TableData">
            <?= $main['delivery_time'] ?>
            <!-- <input type="text" name="delivery_time" id="delivery_time" class="layui-input date" value="<?= $main['delivery_time'] ?>" /> -->
        </td>
    </tr>
    <tr>
        <td class="TableContent">项目星级</td>
        <td class="TableData">
            <input type="text" name="project_star" id="project_star" class="layui-input" value="<?= $psArr[$main['project_star']]['paras_desc'] ?>" />
        </td>
        <?if($main['user_bu']=='INHENERGY'||$main['user_bu']=='HKNERGY'):?>
            <td class="TableContent">星级图标</td>
            <td class="TableData" align="left">
                <div data-type="star_icon">
                    <font style="color: red;font-size:20px"><?= $psArr[$main['project_star']]['extra'] ?></font>
                </div>
            </td>
            <td class="TableContent">合同类型</td>
            <td class="TableData" >
                <?=$main['contract_type']==1?"批量合同":"样机合同"?> 
            </td>
            <td class="TableContent">客户采购订单编号</td>
            <td class="TableData" >
                <?=$main['customer_order_no']?>
            </td>
        <?else:?>
            <td class="TableContent">星级图标</td>
            <td class="TableData" colspan="3" align="left">
                <div data-type="star_icon">
                    <font style="color: red;font-size:20px"><?= $psArr[$main['project_star']]['extra'] ?></font>
                </div>
            </td>
            <td class="TableContent">销售合同编号</td>
            <td class="TableData" >
                <?=$main['customer_order_no']?>
            </td>
        <?endif;?>
        
    </tr>
</table>