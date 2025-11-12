<table id="tab1" width="100%" style="text-align: center;">

    <tr>

        <td class=" TableContent">申请部门</td>
        <td class="TableData">
            <input type="text" name="apply_dept" id="apply_dept" class="layui-input" value="<?= $userInfo['deptName'] ?>" readonly/>
        </td>
        <td class=" TableContent">申请人</td>
        <td class="TableData">
            <input type="text" name="apply_user" id="apply_user" class="layui-input" value="<?= $userInfo['USER_NAME'] ?>" readonly/>
        </td>
       
    </tr>
    <tr>
    <td class=" TableContent">申请时间</td>
    <td class="TableData" >
        <input type="text" name="create_date" id="create_date" class="layui-input" value="<?=$main[write_time]?>" readonly/>
    </td>
    <td class=" TableContent">所用项目</td>
    <td class="TableData" align="left">
            <div class="layui-input-inline">
                <select id="material_flow" name="material_flow" lay-filter="material_flow" lay-search lay-verify="required">
                    <option value="">请选择</option>
                    <?php foreach ($selectArr['sample_request_material_flow'] as $val) : ?>
                        <option value="<?= $val['paras_value'] ?>" <?if($val['paras_value']==$main[material_flow]):?>selected="selected"<?endif;?>><?= $val['paras_desc'] ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
        </td>
    </tr>
</table>