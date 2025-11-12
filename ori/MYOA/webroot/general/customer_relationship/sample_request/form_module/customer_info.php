
<table id="tab1" width="100%" style="text-align: center;">
    <tr>
        <td class=" TableContent">申请部门</td>
        <td class="TableData" colspan="">
            <input type="text" name="apply_dept" id="apply_dept" class="layui-input" value="<?= $userInfo['deptName'] ?>" readonly/>
        </td>
        <td class=" TableContent">申请人</td>
        <td class="TableData" colspan="">
            <input type="text" name="apply_user" id="apply_user" class="layui-input" value="<?= $userInfo['USER_NAME'] ?>" readonly/>
        </td>
        
    </tr>
    <tr>
    <td class=" TableContent">申请时间</td>
        <td class="TableData" colspan="">
            <input type="text" name="create_date" id="create_date" class="layui-input" value="<?=date('Y-m-d H:i:s')?>" readonly/>
        </td>
        <td class=" TableContent">流程类别</td>
        <td class="TableData" align="left">
            <div class="layui-input-inline">
                <select id="material_flow" name="material_flow" lay-filter="material_flow" lay-search lay-verify="required">
                    <option value="">请选择</option>
                    <?php foreach ($selectArr['sample_request_material_flow'] as $val) : ?>
                        <option value="<?= $val['paras_value'] ?>"><?= $val['paras_desc'] ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
        </td>
    </tr>
</table>