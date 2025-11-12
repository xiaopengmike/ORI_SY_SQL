<table id="tab1" width="100%" style="text-align: center;">
    <tr>
        <td class=" TableContent">申请部门</td>
        <td class="TableData">
            <input type="text" name="apply_dept" id="apply_dept" class="layui-input" value="<?= $userInfo['deptName'] ?>" />
        </td>
        <td class=" TableContent">申请人</td>
        <td class="TableData">
            <input type="text" name="apply_user" id="apply_user" class="layui-input" value="<?= $userInfo['USER_NAME'] ?>" />
        </td>
        <td class=" TableContent">申请时间</td>
        <td class="TableData" colspan="3">
            <input type="text" name="create_date" id="create_date" class="layui-input" value="<?=date('Y-m-d H:i:s')?>" readonly/>
        </td>
    </tr>
</table>