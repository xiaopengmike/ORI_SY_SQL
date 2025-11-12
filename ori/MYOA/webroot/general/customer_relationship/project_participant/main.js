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

function closeIframe() {
    layui.use(['form', 'layer'], function () {
        var layer = layui.layer,
            form = layui.form;

        window.close();
        parent.layer.closeAll();

        form.render();
    });
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
            content: '../common/list/single_type_list.php?domId=' + obj.id,
            moveOut: true
        });
        form.render();
    });
}

function returnUser(userName, userId, domId) {
    $("#" + domId).val(userName);
    $("#" + domId).parent().find("[data-type='user_id']").val(userId); //取userId隐藏栏位赋值
}

function sumProportion() {
    var length = $("[data-type='proportion']").length;
    var i = 0;
    var total = 0;
    var proportion = 0;
    for (; i < length; i++) {
        proportion = $($("[data-type='proportion']")[i]).val();
        if (ifEmpty(proportion)) proportion = 0;
        total = parseInt(total) + parseInt(proportion);
        if (total > 100) {
            alert("总比例不能超过100");
            $($("[data-type='proportion']")[i]).val(0);
        }
    }
}

