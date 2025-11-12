function selectUserByDeptId(deptId, number, defaultVal) {
    layui.use(['form', 'layer'], function () {
        var layer = layui.layer,
            form = layui.form;
        if (deptId != '') {
            $.post('user_info.php?deptId=' + deptId, {}, function (data) {
                var obj = eval('(' + data + ')');

                $("#user_name" + number).empty();
                $("#user_name" + number).append("<option value=''>请选择</option>");
                var selected = "";
                for (i = 0; i < obj.length; i++) {
                    if (defaultVal == obj[i].user_id) {
                        selected = " selected ";
                    }
                    $("#user_name" + number).append("<option value='" + obj[i].user_id + "' title='" + obj[i].rzsj + "' " + selected + ">" + obj[i].user_name + "</option>");
                }
            });
        }
        form.render();
    });
}

function removeLine(obj) {
    var table = obj.parentNode.parentNode.parentNode;
    table.removeChild(obj.parentNode.parentNode);
}

function getUserSelect(obj) {
    layui.use(['form', 'layer'], function () {
        var layer = layui.layer,
            form = layui.form;

        layer.open({
            title: '参与人员选择',
            type: 2,
            skin: 'layui-layer-rim',
            area: ['75%', '80%'],
            fixed: false,
            maxmin: true,
            offset: '50px',
            content: 'type_list.php?domId=' + obj.id,
            moveOut: true
        });
        form.render();
    });
}

function returnUser(userName, userId, domId) {
    $("#" + domId).val(userName);
    $("#" + domId).parent().find("[data-type='user_id']").val(userId);  //取userId隐藏栏位赋值
}

function numVerify(obj) {

}

// 校验表单数据
function CheckForm() {

    return true;
}

function insertData(obj) {
    if (!CheckForm()) {
        return false;
    }
    // 更新项目比例数目
    var length = $("input[name='data_line']").length;
    $("#data_count").val(length);

    var sub = obj.value;
    var url = "action.php?action=Add&sub=" + encodeURIComponent(sub);
    $.post(url, $("#form1").serializeArray(), function (data) {
        var result = eval('(' + data + ')');
        if (result.status == "success") {
            alert(sub + "成功");
            parent.location.reload();
            closeModel();
        } else {
            alert(sub + "失败");
            return false;
        }
    });
    return false;
}

function updateData(obj) {
    if (!CheckForm()) {
        return false;
    }
    // 更新项目比例数目
    var length = $("input[name='data_line']").length;
    $("#data_count").val(length);

    var sub = obj.value;
    var url = "action.php?action=Modify&sub=" + encodeURIComponent(sub);
    $.post(url, $("#form1").serializeArray(), function (data) {
        var result = eval('(' + data + ')');
        if (result.status == "success") {
            alert(sub + "成功");
            parent.location.reload();
            closeModel();
        } else {
            alert(sub + "失败");
            return false;
        }
    });
    return false;
}

function approveDate(obj) {
    if (!CheckForm()) {
        return false;
    }
    var sub = obj.value;
    var url = "action.php?action=Approve&sub=" + encodeURIComponent(sub);
    $.post(url, $("#form1").serializeArray(), function (data) {
        var result = eval('(' + data + ')');
        if (result.status == "success") {
            alert(sub + "成功");
            parent.location.reload();
            closeModel();
        } else {
            alert(sub + "失败");
            return false;
        }
    });
    return false;
}