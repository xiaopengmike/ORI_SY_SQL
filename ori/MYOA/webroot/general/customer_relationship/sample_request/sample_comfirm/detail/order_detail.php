<table id="tab4" width="100%" style="text-align: left;">
<tr>
        <td width="17%" class="TableContent">附件</td>
        <td class="TableData" colspan="3"> 
            <input id="spec_attch" type="button" class="layui-btn layui-bg-gray btn-upload" style="display: none;" value="上传" readonly/>
           
        </td>
    </tr>
    <tr>
        <td class="TableContent">其它需要说明的事项</td>
        <td class="TableData" colspan="3" align="left">
            <textarea class="layui-textarea TextDetails" id="other_explain" name="other_explain"><?= $main['other_explain'] ?></textarea>
        </td>
    </tr>
</table>
<script>
    // $(function(){

    //     $('#other_explain,#spare_requirement').each(function() {
    //         //$(this).css("display","none");
    //         if($(this).val()!=''){
    //             $(this).parent().html($(this).val().replace( /\n|\r|(\r\n)|(\u0085)|(\u2028)|(\u2029)/g,"<br>"));
    //         }
            

    //     });
    // })
</script>