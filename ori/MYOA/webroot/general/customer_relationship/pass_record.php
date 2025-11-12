<div id="pass_record">
<table id="pass_tab" name="pass_tab" width="100%" style="text-align: left;">
    <tr>
        <td class="TableHeader" colspan="2" style="color: red;text-align:center;">审批通过记录
        <a id="hide_btn" class="call_up" href="javascript:;" title="显示/隐藏"></a>
    </td>

    </tr>
    <?php $passNum = 0;
    foreach ($passRecord as $fv) : $passNum++; ?>
        <tr class="record_text" style="display: none;">
            <td class="TableContent" width="20%" align="center" rowspan="4"><?= $fv['work_flow'] ?></td>
        </tr>
        <tr class="record_text" style="display: none;">
            <td class="TableData">
                &nbsp;&nbsp;&nbsp;&nbsp;
                <input lay-ignore disabled type="radio" value="01" <?php if ($fv['if_agree'] == "01") : ?>checked<?php endif; ?>><label>
                    <font size=2>同意</font>
                </label>
                &nbsp;&nbsp;&nbsp;&nbsp;
                <input lay-ignore disabled type="radio" value="02" <?php if ($fv['if_agree'] == "02") : ?>checked<?php endif; ?>><label>
                    <font size=2>不同意</font>
                </label>
            </td>
        </tr>
        <tr class="record_text" style="display: none;">
            <td class="TableData">
                <textarea class="layui-textarea" id="pass_opinion<?= $passNum ?>" name="pass_opinion<?= $passNum ?>" disabled><?= $fv['opinion'] ?></textarea>
            </td>
        </tr>
        <tr class="record_text" style="display: none;">
            <td class="TableData MinHeight">
                <?= $fv['signature'] ?>
            </td>
        </tr>
    <?php endforeach; ?>
</table>
</div>
<script>
    $(function(){
        layui.use(['form', 'layer'], function() {
                var layer = layui.layer,
                    form = layui.form;
                    $("#hide_btn").click(function(){
                        if($("#hide_btn").hasClass('down')){
                            $("#hide_btn").removeClass('down');
                            $(".record_text").hide();
                        }else{
                            $("#hide_btn").addClass('down');
                            $(".record_text").show();
                            $.initPage('detail');
                        }
                    });
                form.render();
            });
       
    })
</script>