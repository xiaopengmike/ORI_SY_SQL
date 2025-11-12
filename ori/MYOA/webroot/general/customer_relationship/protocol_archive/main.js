function getCustomerSelect(obj) {
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
            content: '../customer_data/list.php?domId=' + obj.id,
            moveOut: true
        });
        form.render();
    });
}