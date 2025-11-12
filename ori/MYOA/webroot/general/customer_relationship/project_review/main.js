function removeLine(obj) {
    var table = obj.parentNode.parentNode.parentNode;
    table.removeChild(obj.parentNode.parentNode);
}

function addLine(domId,obj,product_type) {
    var html = "";
    html = createLine(domId,obj,product_type);
}

var detailNum = 0;

function createLine(type,obj,product_type) {
    layui.use(['form', 'attach'], function () {
        var attach = layui.attach,
            form = layui.form;
        var html = '';
        detailNum = $("#" + type + "Count").val();

        if (type == 'addProjectDetail') {
            detailNum++;
            html = '<tr align="center">';
            html += '<input type="hidden" name="type' + detailNum + '" value="'+product_type+'" />';
            html += '<td class="TableData"><input type="text" class="layui-input" id="product" name="product' + detailNum + '" /></td>';
            html += '<td class="TableData"><input type="text" class="layui-input" id="price" name="price' + detailNum + '" /></td>';
            html += '<td class="TableData"><input type="text" class="layui-input" id="first_price" name="first_price' + detailNum + '" /></td>';
            html += '<td class="TableData"><input type="text" class="layui-input" id="standard" name="standard' + detailNum + '" /></td>';
            html += '<td class="TableData"><input type="text" class="layui-input" id="number" lay-verify="checknum" name="number' + detailNum + '" />';
            if($('#user_bu').val()=='INHENERGY'||$('#user_bu').val()=='HKNERGY'){
                html += '<td class="TableData"><input type="text" class="layui-input" id="supplier" name="supplier' + detailNum + '" />';
            }
            html += '<td class="TableData"><input id="project_detail_attach' + detailNum + '" type="button" class="layui-btn layui-bg-gray btn-upload" style="display: inline!important;" value="上传" />';
            html += '<a href="javascript:;" onclick="removeLine(this)">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;删除</a></td>';
            html += '</tr>';
        }
        if (type == 'addProjectMember') {
            detailNum++;
            html = '<tr align="center">';
            html += '<td class="TableData"><input type="text" class="layui-input" id="serial_number" name="serial_number' + detailNum + '" /></td>';
            html += '<td class="TableData"><input type="text" class="layui-input" id="member" name="member' + detailNum + '" /></td>';
            html += '<td class="TableData"><input type="text" class="layui-input" id="position" name="position' + detailNum + '" /></td>';
            html += '<td class="TableData"><input type="text" class="layui-input" id="duty" name="duty' + detailNum + '" /></td>';
            html += '<a href="javascript:;" onclick="removeLine(this)">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;删除</a></td>';
            html += '</tr>';
        }
        $("#" + type + "Count").val(detailNum);
        $(obj).parent().parent().before(html);
        attach.init({
            elem: "#project_detail_attach" + detailNum,
            uploadUrl: "/general/attachment/attach.php?type=project_detail_attach" + detailNum + "&url=common"
        });
        //form.render('');
    });
}

function returnProjectCode(projectCode,temp_project_code) {
    $("#project_code").val(projectCode);
    $("#temp_project_code").val(temp_project_code);
    var url = "action.php?action=Retrieve";
    // 校验系统项目代号
    if (projectCode != '') {
        $.post(url, {
            dataType: 'verifyProjectCode',
            projectCode: projectCode,
            user_bu: $("#user_bu").val()
        }, function(data) {
            var reJson = null;
            reJson = eval('(' + data + ')');
            if (reJson.code == '200') {
                console.log(reJson.data);
                if (reJson.data.code=='200') {
                    layer.msg('项目代号已存在！');
                    $("#project_code").val('');
                    $("#temp_project_code").val('');
                }
            }
        });
    }
}