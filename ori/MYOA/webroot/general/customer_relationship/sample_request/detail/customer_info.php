<table id="tab1" width="100%" style="text-align: center;">
    <tr>
        <td class=" TableContent">申请部门</td>
        <td class="TableData">
            <?= $main['deptName'] ?>
        </td>
        <td class=" TableContent">申请人</td>
        <td class="TableData">
            <?= $main['createUserName'] ?>
        </td>
    </tr>
    <tr>
        <td class=" TableContent">申请时间</td>
        <td class="TableData" >
            <?= $main['write_time'] ?>
        </td>
        <td class=" TableContent">流程类别</td>
        <td class="TableData">
            <?= $sample_request_material_flow[$main[material_flow]] ?>
        </td>
    </tr>
</table>