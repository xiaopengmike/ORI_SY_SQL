<table id="tab1" width="100%" style="text-align: center;">
    <tr>
        <td class="TableContent">客户名称</td>
        <td class="TableData">
            <input type="text" name="customer_name" id="customer_name" class="layui-input" />
        </td>
        <td class=" TableContent">项目代号</td>
        <td class="TableData">
            <input type="text" name="project_code" id="project_code" class="layui-input" />
        </td>
        <td class=" TableContent">项目编号</td>
        <td class="TableData">
            <input type="text" name="project_num" id="project_num" class="layui-input" />
        </td>
        <td class=" TableContent">订单编号</td>
        <td class="TableData">
            <input type="text" name="order_num" id="order_num" class="layui-input" />
        </td>
    </tr>
    <tr>
        <td class="TableContent">合同相对方</td>
        <td class="TableData">
            <input type="text" name="contract_party" id="contract_party" class="layui-input" />
        </td>
        <td class=" TableContent">交货时间</td>
        <td class="TableData">
            <input type="text" name="delivery_time" id="delivery_time" class="layui-input date" />
        </td>
        <td class=" TableContent">申请部门</td>
        <td class="TableData">
            <input type="text" name="apply_dept" id="apply_dept" class="layui-input" value="<?= $main['apply_dept'] ?>" />
        </td>
        <td class=" TableContent">申请人</td>
        <td class="TableData">
            <input type="text" name="apply_user" id="apply_user" class="layui-input" value="<?= $main['apply_user'] ?>" />
        </td>
    </tr>
    <tr>
        <td class="TableContent">是否第三方检验</td>
        <td class="TableData">
            <input type="text" name="name1" id="name1" class="layui-input" />
        </td>
        <td class=" TableContent">协议存档管理单号</td>
        <td class="TableData">
            <input type="text" name="customer_id1" id="customer_id1" class="layui-input" />
        </td>
        <td class=" TableContent">验货类型</td>
        <td class="TableData" colspan="3">
            <input type="text" name="country1" id="country1" class="layui-input" />
        </td>
    </tr>
    <tr>
        <td class="TableContent">项目星级</td>
        <td class="TableData">
            <input type="text" name="name1" id="name1" class="layui-input" />
        </td>
        <td class=" TableContent">星级图标</td>
        <td class="TableData">
            <input type="text" name="customer_id1" id="customer_id1" class="layui-input" />
        </td>
        <td class=" TableContent">合同归属公司</td>
        <td class="TableData" colspan="3">
            <input type="text" name="country1" id="country1" class="layui-input" />
        </td>
    </tr>
</table>