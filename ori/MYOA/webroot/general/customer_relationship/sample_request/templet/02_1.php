<?php 
$param_templ_data=$param_templ_arr['02'];
?>
<table id="tab10" class="param_templ pt02" width="100%"  <?if($request_main[material_type]!="02"):?>hidden="hidden"<?endif;?>>
    <input name="param_templ_v" type="hidden" value="1">

    <tr>
        <td class="TableHeader" colspan="8" style="text-align:left">一、可选参数(<span style="color:red">如有特殊要求，请重选以下参数要求，否则按默认参数生成打样要求</span>)</td>
    </tr>
    <tr>
        <td class=" TableContent" style="text-align:left">序号</td>
        <td class=" TableContent" style="text-align:left">参数项</td>
        <td class=" TableContent" style="text-align:left">参数配置</td>
        <td class=" TableContent" style="text-align:left">备注</td>
    </tr>
    <tr>
        <td class="TableData" colspan="">
            1
        </td>
        <td class="TableData" colspan="">
        是否带屏蔽
            </td>
        <td class="TableData" colspan="" style="text-align:left">
        <input type="radio" name="param_templ[data2_1_1]" lay-skin="primary" class="class_one" title="不带屏蔽" value="不带屏蔽" checked="checked" >
            <input type="radio" name="param_templ[data2_1_1]" lay-skin="primary" class="class_one" title="内屏蔽" value="内屏蔽" <?if($param_templ_data['data2_1_1']=="内屏蔽"):?>checked="checked"<?endif;?> >
            <input type="radio" name="param_templ[data2_1_1]" lay-skin="primary" class="class_one" title="外屏蔽" value="外屏蔽" <?if($param_templ_data['data2_1_1']=="外屏蔽"):?>checked="checked"<?endif;?> >
        </td>
        <td class="TableData" colspan="" style="text-align:left">
            单选，默认不带屏蔽，有特殊需
        </td>
    </tr>
    <tr>
        <td class="TableData" colspan="">
            2
        </td>
        <td class="TableData" colspan="">
        连接方式
            </td>
        <td class="TableData" colspan="" style="text-align:left">
        <input type="radio" name="param_templ[data2_2_1]" lay-skin="primary" class="class_one" title="软连接" value="软连接" <?if($param_templ_data['data2_2_1']=="软连接"):?>checked="checked"<?endif;?> >
            <input type="radio" name="param_templ[data2_2_1]" lay-skin="primary" class="class_one" title="硬链接" value="硬链接" <?if($param_templ_data['data2_2_1']=="硬链接"||empty($param_templ_data['data2_2_1'])):?>checked="checked"<?endif;?> >
            
        </td>
        <td class="TableData" colspan="" style="text-align:left">
        单选，有任何外带线材都算软连
        </td>
    </tr>
    <tr>
        <td class="TableData" colspan="">
            3
        </td>
        <td class="TableData" colspan="">
        初级
        </td>
        <td class="TableData" colspan="" style="text-align:left">

            <input type="radio" name="param_templ[data2_3_1]" lay-skin="primary" class="class_one" title="无" value="无" <?if($param_templ_data['data2_3_1']=="无"||empty($param_templ_data['data2_3_1'])):?>checked="checked"<?endif;?> lay-filter="other_info">
            <input type="radio" name="param_templ[data2_3_1]" lay-skin="primary" class="class_one" title="铜插片" value="铜插片" <?if($param_templ_data['data2_3_1']=="铜插片"):?>checked="checked"<?endif;?> lay-filter="other_info">
            <input type="radio" name="param_templ[data2_3_1]" lay-skin="primary" class="class_one" title="铜软线" value="其它" <?if($param_templ_data['data2_3_1']=="其它"):?>checked="checked"<?endif;?> lay-filter="other_info">
            <input type="text" class="class_one other_info" name="param_templ[data2_3_2]" id="apply_user" class="layui-input" value="<?=$param_templ_data['data2_3_2']?>"  readonly="readonly"/>圈
        </td>
        <td class="TableData" colspan="" style="text-align:left">
        单选，如果是铜软线需要提供初
        </td>
    </tr>
    <tr>
        <td class="TableData" colspan="">
            4
        </td>
        <td class="TableData" colspan="">
        精度功能
            </td>
        <td class="TableData" colspan="" style="text-align:left">
        <input type="radio" name="param_templ[data2_4_1]" lay-skin="primary" class="class_one" title="计量级" value="计量级" checked="checked" >
            <input type="radio" name="param_templ[data2_4_1]" lay-skin="primary" class="class_one" title="非计量级" value="非计量级" <?if($param_templ_data['data2_4_1']=="非计量级"):?>checked="checked"<?endif;?> >
            
        </td>
        <td class="TableData" colspan="" style="text-align:left">
        单选，默认计量级
        </td>
    </tr>
    <tr>
        <td class="TableData" colspan="">
            5
        </td>
        <td class="TableData" colspan="">
        准确级
        </td>
        <td class="TableData" colspan="" style="text-align:left">
            
            <input type="radio" name="param_templ[data2_5_1]" lay-skin="primary" class="class_one" title="0.2" value="0.2" <?if($param_templ_data['data2_5_1']=="0.2"):?>checked="checked"<?endif;?> lay-filter="other_info">
            <input type="radio" name="param_templ[data2_5_1]" lay-skin="primary" class="class_one" title="0.1" value="0.1" <?if($param_templ_data['data2_5_1']=="0.1" ||empty($param_templ_data['data2_5_1'])):?>checked="checked"<?endif;?> lay-filter="other_info">
            <input type="radio" name="param_templ[data2_5_1]" lay-skin="primary" class="class_one" title="0.05" value="0.05" <?if($param_templ_data['data2_5_1']=="0.05"):?>checked="checked"<?endif;?> lay-filter="other_info">
                <input type="radio" name="param_templ[data2_5_1]" lay-skin="primary" class="class_one" title="其它" value="其它" <?if($param_templ_data['data2_5_1']=="其它"):?>checked="checked"<?endif;?> lay-filter="other_info">
            <input type="text" class="class_one other_info" name="param_templ[data2_5_2]" id="apply_user" class="layui-input" value="<?=$param_templ_data['data2_5_2']?>"  readonly="readonly"/>
        </td>
        <td class="TableData" colspan="" style="text-align:left">
        单选
        </td>
    </tr>
    <tr>
        <td class="TableData" colspan="">
            6
        </td>
        <td class="TableData" colspan="">
        初级电流
        </td>
        <td class="TableData" colspan="" style="text-align:left">
            
            <input type="radio" name="param_templ[data2_6_1]" lay-skin="primary" class="class_one" title="5(100)A" value="5(100)A" <?if($param_templ_data['data2_6_1']=="5(100)A"||empty($param_templ_data['data2_6_1'])):?>checked="checked"<?endif;?> lay-filter="other_info">
            <input type="radio" name="param_templ[data2_6_1]" lay-skin="primary" class="class_one" title="1(10)A" value="1(10)A" <?if($param_templ_data['data2_6_1']=="1(10)A"):?>checked="checked"<?endif;?> lay-filter="other_info">
                <input type="radio" name="param_templ[data2_6_1]" lay-skin="primary" class="class_one" title="5(80)A" value="5(80)A" <?if($param_templ_data['data2_6_1']=="5(80)A"):?>checked="checked"<?endif;?> lay-filter="other_info">
            <input type="radio" name="param_templ[data2_6_1]" lay-skin="primary" class="class_one" title="其它" value="其它" <?if($param_templ_data['data2_6_1']=="其它"):?>checked="checked"<?endif;?> lay-filter="other_info">
            <input type="text" class="class_one other_info" name="param_templ[data2_6_2]" id="apply_user" class="layui-input" value="<?=$param_templ_data['data2_6_2']?>"  readonly="readonly"/>A
        </td>
        <td class="TableData" colspan="" style="text-align:left">
        单选
        </td>
    </tr>
    <tr>
        <td class="TableData" colspan="">
            7
        </td>
        <td class="TableData" colspan="">
        次级额定电流
        </td>
        <td class="TableData" colspan="" style="text-align:left">
            
              
        <input type="radio" name="param_templ[data2_7_1]" lay-skin="primary" class="class_one" title="1mA" value="1mA" <?if($param_templ_data['data2_7_1']=="1mA"):?>checked="checked"<?endif;?> lay-filter="other_info">
            <input type="radio" name="param_templ[data2_7_1]" lay-skin="primary" class="class_one" title="2mA" value="2mA" <?if($param_templ_data['data2_7_1']=="2mA"||empty($param_templ_data['data2_7_1'])):?>checked="checked"<?endif;?> lay-filter="other_info">
                <input type="radio" name="param_templ[data2_7_1]" lay-skin="primary" class="class_one" title="2.5mA" value="2.5mA" <?if($param_templ_data['data2_7_1']=="2.5mA"):?>checked="checked"<?endif;?> lay-filter="other_info">
            <input type="radio" name="param_templ[data2_7_1]" lay-skin="primary" class="class_one" title="其它" value="其它" <?if($param_templ_data['data2_7_1']=="其它"):?>checked="checked"<?endif;?> lay-filter="other_info">
            <input type="text" class="class_one other_info" name="param_templ[data2_7_2]" id="apply_user" class="layui-input" value="<?=$param_templ_data['data2_7_2']?>"  readonly="readonly"/>mA
        </td>
        <td class="TableData" colspan="" style="text-align:left">
        单选
        </td>
    </tr>
    <tr>
        <td class="TableData" colspan="">
            8
        </td>
        <td class="TableData" colspan="">
        电流比
        </td>
        <td class="TableData" colspan="" style="text-align:left">
           
            <input type="radio" name="param_templ[data2_8_1]" lay-skin="primary" class="class_one" title="2000:1" value="2000:1" <?if($param_templ_data['data2_8_1']=="2000:1"):?>checked="checked"<?endif;?> lay-filter="other_info">
            <input type="radio" name="param_templ[data2_8_1]" lay-skin="primary" class="class_one" title="2500:1" value="2500:1" <?if($param_templ_data['data2_8_1']=="2500:1"||empty($param_templ_data['data2_8_1'])):?>checked="checked"<?endif;?> lay-filter="other_info">
            <input type="radio" name="param_templ[data2_8_1]" lay-skin="primary" class="class_one" title="其它" value="其它" <?if($param_templ_data['data2_8_1']=="其它"):?>checked="checked"<?endif;?> lay-filter="other_info">
            <input type="text" class="class_one other_info" name="param_templ[data2_8_2]" id="apply_user" class="layui-input" value="<?=$param_templ_data['data2_8_2']?>"  readonly="readonly"/>
        </td>
        <td class="TableData" colspan="" style="text-align:left">
        单选，初级额定电流*匝数/次级额定电流
        </td>
    </tr>
    <tr>
        <td class="TableData" colspan="">
            9
        </td>
        <td class="TableData" colspan="">
        额定负载
        </td>
        <td class="TableData" colspan="" style="text-align:left">
            
            <input type="radio" name="param_templ[data2_9_1]" lay-skin="primary" class="class_one" title="10Ω" value="10Ω" <?if($param_templ_data['data2_9_1']=="10Ω"||empty($param_templ_data['data2_9_1'])):?>checked="checked"<?endif;?> lay-filter="other_info">
            <input type="radio" name="param_templ[data2_9_1]" lay-skin="primary" class="class_one" title="20Ω" value="20Ω" <?if($param_templ_data['data2_9_1']=="20Ω"):?>checked="checked"<?endif;?> lay-filter="other_info">
        
            <input type="radio" name="param_templ[data2_9_1]" lay-skin="primary" class="class_one" title="其它" value="其它" <?if($param_templ_data['data2_9_1']=="其它"):?>checked="checked"<?endif;?> lay-filter="other_info">
            <input type="text" class="class_one other_info" name="param_templ[data2_9_2]" id="apply_user" class="layui-input" value="<?=$param_templ_data['data2_9_2']?>"  readonly="readonly"/>Ω
        </td>
        <td class="TableData" colspan="" style="text-align:left">
        单选
        </td>
    </tr>
    <tr>
        <td class="TableData" colspan="">
            10
        </td>
        <td class="TableData" colspan="">
        抗直流分量
        </td>
        <td class="TableData" colspan="" style="text-align:left">
           
            <input type="radio" name="param_templ[data2_10_1]" lay-skin="primary" class="class_one" title="无" value="无" <?if($param_templ_data['data2_10_1']=="无"):?>checked="checked"<?endif;?> lay-filter="other_info">
            <input type="radio" name="param_templ[data2_10_1]" lay-skin="primary" class="class_one" title="±3%" value="±3%" <?if($param_templ_data['data2_10_1']=="±3%"||empty($param_templ_data['data2_10_1'])):?>checked="checked"<?endif;?> lay-filter="other_info">
            <input type="radio" name="param_templ[data2_10_1]" lay-skin="primary" class="class_one" title="±2%" value="±2%" <?if($param_templ_data['data2_10_1']=="±2%"):?>checked="checked"<?endif;?> lay-filter="other_info">
                <input type="radio" name="param_templ[data2_10_1]" lay-skin="primary" class="class_one" title="其它" value="其它" <?if($param_templ_data['data2_10_1']=="其它"):?>checked="checked"<?endif;?> lay-filter="other_info">
            <input type="text" class="class_one other_info" name="param_templ[data2_10_2]" id="apply_user" class="layui-input" value="<?=$param_templ_data['data2_10_2']?>"  readonly="readonly"/>%
        </td>
        <td class="TableData" colspan="" style="text-align:left">
        单选，一般定义为Imax/1.41电流条件下的电流精度
        </td>
    </tr>
    <tr>
        <td class="TableData" colspan="">
            11
        </td>
        <td class="TableData" colspan="">
        线材标准(非一次电气线）
        </td>
        <td class="TableData" colspan="" style="text-align:left">
           
            <input type="radio" name="param_templ[data2_11_1]" lay-skin="primary" class="class_one" title="红黑 24AWG UL1569 105℃ 300V" value="01" checked="checked" lay-filter="other_info">
                <input type="radio" name="param_templ[data2_11_1]" lay-skin="primary" class="class_one" title="其它" value="其它" <?if($param_templ_data['data2_11_1']=="其它"):?>checked="checked"<?endif;?> lay-filter="other_info">
            <input type="text" class="class_one other_info" name="param_templ[data2_11_2]" id="apply_user" class="layui-input" value="<?=$param_templ_data['data2_11_2']?>"  readonly="readonly"/>
        </td>
        <td class="TableData" colspan="" style="text-align:left">
        常规选用，正负极要求交替缠绕
        </td>
    </tr>
    <tr>
        <td class="TableHeader" colspan="8" style="text-align:left">二、有害物质管理</td>
    </tr>
    <tr>
        <td class="TableData" colspan="">
            24
        </td>
        <td class="TableData" colspan="">
            有害物质标准
        </td>
        <td class="TableData" colspan="" style="text-align:left">
            <input type="hidden" name="param_templ[data2_14_1]"  value="N">
            <input type="hidden" name="param_templ[data2_14_2]"  value="N">
            <input type="hidden" name="param_templ[data2_14_3]"  value="N">
            <input type="checkbox" name="param_templ[data2_14_1]" lay-skin="primary" class="class_one" title="ROHS" value="Y" checked="checked" >
            <input type="checkbox" name="param_templ[data2_14_2]" lay-skin="primary" class="class_one" title="REACH" value="Y"<?if($param_templ_data['data2_14_2']=="Y"):?>checked="checked"<?endif;?>>
            <input type="checkbox" name="param_templ[data2_14_3]" lay-skin="primary" class="class_one" title="无卤" value="Y" <?if($param_templ_data['data2_14_3']=="Y"):?>checked="checked"<?endif;?>>
        </td>
        <td class="TableData" colspan="" style="text-align:left">
        可多选，ROHS一般要求符合
        </td>
    </tr>
    <tr>
        <td class="TableHeader" colspan="8" style="text-align:left">三、必要条件</td>
    </tr>
    <tr>
        <td class="TableData" colspan="8" >
        <pre>
        1.一次侧灌封面、二次侧黑线默认同名端，如果是硬连接二次侧丝印要标注同名端；
        2.一、二次侧工频耐压要求4.3KVac以上，测试条件:0.5mA/60s；
        3.一、二次侧绝缘阻抗要求≥1000MΩ，测试条件:500Vac/3s；
        4.工作温度范围要求至少-40~85℃；
        5.工作海拔条件要求4000m以上；
        6.电流互感器本体必须灌胶，确保无任何气泡；
        7.漆包线要求要求2UEW-B等级以上(或其它标准同等等级)；
        8.端子焊接必须点锡；
        9.必须提供样品最高工作温度、常温、最低工作温度条件下测试比差、角差；
        <pre>
        </td>
    </tr>
</table>