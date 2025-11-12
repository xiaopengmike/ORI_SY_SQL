<?php 
$param_templ_data=$param_templ_arr['01'];
?>
<table id="tab10" class="param_templ pt01" width="100%"  <?if($request_main[material_type]!="01"):?>hidden="hidden"<?endif;?>>
    <input name="param_templ_v" type="hidden" value="1">
    <tr>
        <td class="TableHeader" colspan="8" style="text-align:left">一、显示模式</td>
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
            液晶材质
        </td>
        <td class="TableData" colspan="" style="text-align:left">
            <input type="hidden" name="param_templ[data_1_1]"  value="N">
            <input type="hidden" name="param_templ[data_1_2]"  value="N">
            <input type="hidden" name="param_templ[data_1_3]"  value="N">
            <input type="checkbox" name="param_templ[data_1_1]" lay-skin="primary" class="class_one" title="HTN" value="Y" <?if($param_templ_data['data_1_1']=="Y"):?>checked="checked"<?endif;?>>
            <input type="checkbox" name="param_templ[data_1_2]" lay-skin="primary" class="class_one" title="FSTN" value="Y" <?if($param_templ_data['data_1_2']=="Y"):?>checked="checked"<?endif;?>>
            <input type="checkbox" name="param_templ[data_1_3]" lay-skin="primary" class="class_one" title="其它" value="Y" <?if($param_templ_data['data_1_3']=="Y"):?>checked="checked"<?endif;?>>
            <input type="text" class="class_one" name="param_templ[data_1_4]" id="apply_user" class="layui-input" value="<?=$param_templ_data['data_1_4']?>" />
        </td>
        <td class="TableData" colspan="" style="text-align:left">
            单选，一般推荐HTN、FSTN
        </td>
    </tr>
    <tr>
        <td class="TableData" colspan="">
            2
        </td>
        <td class="TableData" colspan="">
            显示内容
        </td>
        <td class="TableData" colspan="" style="text-align:left">
            <input type="hidden" name="param_templ[data_2_1]"  value="N">
            <input type="hidden" name="param_templ[data_2_2]"  value="N">
            <input type="hidden" name="param_templ[data_2_3]"  value="N">
            <input type="checkbox" name="param_templ[data_2_1]" lay-skin="primary" class="class_one" title="段码" value="Y" <?if($param_templ_data['data_2_1']=="Y"):?>checked="checked"<?endif;?>>
            <input type="checkbox" name="param_templ[data_2_2]" lay-skin="primary" class="class_one" title="点阵" value="Y"<?if($param_templ_data['data_2_2']=="Y"):?>checked="checked"<?endif;?>>
            <input type="checkbox" name="param_templ[data_2_3]" lay-skin="primary" class="class_one" title="其它" value="Y" <?if($param_templ_data['data_2_3']=="Y"):?>checked="checked"<?endif;?>>
            <input type="text" class="class_one" name="param_templ[data_2_4]" id="" class="layui-input" value="<?=$param_templ_data['data_2_4']?>" />
        </td>
        <td class="TableData" colspan="" style="text-align:left">
            单选，默认段码
        </td>
    </tr>
    <tr>
        <td class="TableData" colspan="">
            3
        </td>
        <td class="TableData" colspan="">
            是否集成芯片
        </td>
        <td class="TableData" colspan="" style="text-align:left">
            <input type="hidden" name="param_templ[data_3_1]"  value="N">
            <input type="hidden" name="param_templ[data_3_2]"  value="N">
            <input type="hidden" name="param_templ[data_3_3]"  value="N">
            <input type="checkbox" name="param_templ[data_3_1]" lay-skin="primary" class="class_one" title="非集成" value="Y" <?if($param_templ_data['data_3_1']=="Y"):?>checked="checked"<?endif;?>>
            <input type="checkbox" name="param_templ[data_3_2]" lay-skin="primary" class="class_one" title="COG" value="Y"<?if($param_templ_data['data_3_2']=="Y"):?>checked="checked"<?endif;?>>
            <input type="checkbox" name="param_templ[data_3_3]" lay-skin="primary" class="class_one" title="其它" value="Y" <?if($param_templ_data['data_3_3']=="Y"):?>checked="checked"<?endif;?>>
            <input type="text" class="class_one" name="param_templ[data_3_4]" id="" class="layui-input" value="<?=$param_templ_data['data_3_4']?>" />
        </td>
        <td class="TableData" colspan="" style="text-align:left">
            单选，默认非集成
        </td>
    </tr>
    <tr>
        <td class="TableData" colspan="">
            4
        </td>
        <td class="TableData" colspan="">
            显示方式
        </td>
        <td class="TableData" colspan="" style="text-align:left">
            <input type="hidden" name="param_templ[data_4_1]"  value="N">
            <input type="hidden" name="param_templ[data_4_2]"  value="N">
            <input type="checkbox" name="param_templ[data_4_1]" lay-skin="primary" class="class_one" title="正性显示" value="Y" <?if($param_templ_data['data_4_1']=="Y"):?>checked="checked"<?endif;?>>
            <input type="checkbox" name="param_templ[data_4_2]" lay-skin="primary" class="class_one" title="其它" value="Y" <?if($param_templ_data['data_4_2']=="Y"):?>checked="checked"<?endif;?>>
            <input type="text" class="class_one" name="param_templ[data_4_3]" id="" class="layui-input" value="<?=$param_templ_data['data_4_3']?>" />
        </td>
        <td class="TableData" colspan="" style="text-align:left">
            单选，默认正性显示
        </td>
    </tr>
    <tr>
        <td class="TableData" colspan="">
            5
        </td>
        <td class="TableData" colspan="">
            采光方式
        </td>
        <td class="TableData" colspan="" style="text-align:left">
        <input type="hidden" name="param_templ[data_5_1]"  value="N">
            <input type="hidden" name="param_templ[data_5_2]"  value="N">
            <input type="hidden" name="param_templ[data_5_3]"  value="N">
            <input type="checkbox" name="param_templ[data_5_1]" lay-skin="primary" class="class_one" title="高半透型" value="Y" <?if($param_templ_data['data_5_1']=="Y"):?>checked="checked"<?endif;?>>
            <input type="checkbox" name="param_templ[data_5_2]" lay-skin="primary" class="class_one" title="全透型" value="Y"<?if($param_templ_data['data_5_2']=="Y"):?>checked="checked"<?endif;?>>
            <input type="checkbox" name="param_templ[data_5_3]" lay-skin="primary" class="class_one" title="反射型" value="Y" <?if($param_templ_data['data_5_3']=="Y"):?>checked="checked"<?endif;?>>
        </td>
        <td class="TableData" colspan="" style="text-align:left">
            单选，默认高半透型
        </td>
    </tr>
    <tr>
        <td class="TableData" colspan="">
            6
        </td>
        <td class="TableData" colspan="">
            主视角方向
        </td>
        <td class="TableData" colspan="" style="text-align:left">
            <input type="hidden" name="param_templ[data_6_1]"  value="N">
            <input type="hidden" name="param_templ[data_6_2]"  value="N">
            <input type="checkbox" name="param_templ[data_6_1]" lay-skin="primary" class="class_one" title="6点钟" value="Y" <?if($param_templ_data['data_6_1']=="Y"):?>checked="checked"<?endif;?>>
            <input type="checkbox" name="param_templ[data_6_2]" lay-skin="primary" class="class_one" title="其它" value="Y" <?if($param_templ_data['data_6_2']=="Y"):?>checked="checked"<?endif;?>>
            <input type="text" class="class_one" name="param_templ[data_6_3]" id="" class="layui-input" value="<?=$param_templ_data['data_6_3']?>" />
        </td>
        <td class="TableData" colspan="" style="text-align:left">
            单选，默认选择6点钟
        </td>
    </tr>
    <tr>
        <td class="TableData" colspan="">
            7
        </td>
        <td class="TableData" colspan="">
            对比度
        </td>
        <td class="TableData" colspan="" style="text-align:left">
        在20℃±5℃条件下对比度不应小于4
        </td>
        <td class="TableData" colspan="" style="text-align:left">
            工作温度范围内对比度不应小于3
        </td>
    </tr>
    <tr>
        <td class="TableData" colspan="">
            8
        </td>
        <td class="TableData" colspan="">
            底色要求
        </td>
        <td class="TableData" colspan="" style="text-align:left">
        <input type="text" class="class_one" name="param_templ[data_8_1]" id="" class="layui-input" value="<?=$param_templ_data['data_8_1']?>" />号样片色/参照<input type="text" class="class_one" name="param_templ[data_8_2]" id="" class="layui-input" value="<?=$param_templ_data['data_8_2']?>" />型号液晶显示器的底色
            
        </td>
        <td class="TableData" colspan="" style="text-align:left">
            参考设计选型规范，建议选择1~2号
        </td>
    </tr>
    <tr>
        <td class="TableData" colspan="">
            9
        </td>
        <td class="TableData" colspan="">
            连接方式
        </td>
        <td class="TableData" colspan="" style="text-align:left">
        <input type="hidden" name="param_templ[data_9_1]"  value="N">
            <input type="hidden" name="param_templ[data_9_2]"  value="N">
            <input type="hidden" name="param_templ[data_9_3]"  value="N">
            <input type="checkbox" name="param_templ[data_9_1]" lay-skin="primary" class="class_one" title="插针" value="Y" <?if($param_templ_data['data_9_1']=="Y"):?>checked="checked"<?endif;?>>
            <input type="checkbox" name="param_templ[data_9_2]" lay-skin="primary" class="class_one" title="导电胶条" value="Y"<?if($param_templ_data['data_9_2']=="Y"):?>checked="checked"<?endif;?>>
            <input type="checkbox" name="param_templ[data_9_3]" lay-skin="primary" class="class_one" title="FPC" value="Y" <?if($param_templ_data['data_9_3']=="Y"):?>checked="checked"<?endif;?>>
        </td>
        <td class="TableData" colspan="" style="text-align:left">
            单选
        </td>
    </tr>
    <tr>
        <td class="TableHeader" colspan="8" style="text-align:left">二、驱动条件</td>
    </tr>
    <tr>
        <td class="TableData" colspan="">
            10
        </td>
        <td class="TableData" colspan="">
            Duty
        </td>
        <td class="TableData" colspan="" style="text-align:left">
            1/<input type="text" class="class_one" name="param_templ[data_10_1]" id="" class="layui-input" value="<?=$param_templ_data['data_10_1']?>" />
        </td>
        <td class="TableData" colspan="" style="text-align:left">
         HTN建议1/4、1/6，FSTN建议1/8
        </td>
    </tr>
    <tr>
        <td class="TableData" colspan="">
            11
        </td>
        <td class="TableData" colspan="">
            Bias
        </td>
        <td class="TableData" colspan="" style="text-align:left">
            1/<input type="text" class="class_one" name="param_templ[data_11_1]" id="" class="layui-input" value="<?=$param_templ_data['data_11_1']?>" />
        </td>
        <td class="TableData" colspan="" style="text-align:left">
            HTN建议1/3，FSTN建议1/4
        </td>
    </tr>
    <tr>
        <td class="TableData" colspan="">
            12
        </td>
        <td class="TableData" colspan="">
            中心驱动电压
        </td>
        <td class="TableData" colspan="" style="text-align:left">
            <input type="text" class="class_one" name="param_templ[data_12_1]" id="" class="layui-input" value="<?=$param_templ_data['data_12_1']?>" />
        </td>
        <td class="TableData" colspan="" style="text-align:left">
        HTN建议3.1V，FSTN建议4.5V、5.0V
        </td>
    </tr>
    <tr>
        <td class="TableData" colspan="">
            13
        </td>
        <td class="TableData" colspan="">
        驱动电压误差
        </td>
        <td class="TableData" colspan="" style="text-align:left">
        <input type="hidden" name="param_templ[data_13_1]"  value="N">
            <input type="hidden" name="param_templ[data_13_2]"  value="N">
            <input type="checkbox" name="param_templ[data_13_1]" lay-skin="primary" class="class_one" title="±0.2V" value="Y" <?if($param_templ_data['data_13_1']=="Y"):?>checked="checked"<?endif;?>>
            <input type="checkbox" name="param_templ[data_13_2]" lay-skin="primary" class="class_one" title="±0.1V" value="Y"<?if($param_templ_data['data_13_2']=="Y"):?>checked="checked"<?endif;?>>
        </td>
        <td class="TableData" colspan="" style="text-align:left">
        单选，默认±0.2V，不能满足时可选±0.1V
        </td>
    </tr>
    <tr>
        <td class="TableData" colspan="">
            14
        </td>
        <td class="TableData" colspan="">
        驱动芯片
        </td>
        <td class="TableData" colspan="" style="text-align:left">
        型号<input type="text" class="class_one" name="param_templ[data_14_1]" id="" class="layui-input" value="<?=$param_templ_data['data_14_1']?>" />    HBM耐压<input type="text" class="class_one" name="param_templ[data_14_2]" id="" class="layui-input" value="<?=$param_templ_data['data_14_2']?>" />
        </td>
        <td class="TableData" colspan="" style="text-align:left">
        COG液晶需要确认
        </td>
    </tr>
    <tr>
        <td class="TableData" colspan="">
            15
        </td>
        <td class="TableData" colspan="">
        驱动频率
        </td>
        <td class="TableData" colspan="" style="text-align:left">
            <input type="text" class="class_one" name="param_templ[data_15_1]" id="" class="layui-input" value="<?=$param_templ_data['data_15_1']?>" />
        </td>
        <td class="TableData" colspan="" style="text-align:left">
        在50~80Hz内选择，建议64Hz
        </td>
    </tr>
    <tr>
        <td class="TableHeader" colspan="8" style="text-align:left">三、工作温度范围</td>
    </tr>
    <tr>
    <td class="TableData" colspan="">
            16
        </td>
        <td class="TableData" colspan="">
        <input type="checkbox" name="param_templ[data_16_1]" lay-skin="primary" class="class_one" title="低温型" value="Y" <?if($param_templ_data['data_16_1']=="Y"):?>checked="checked"<?endif;?>>
        </td>
        <td class="TableData" colspan="" style="text-align:left">
            工作温度范围-40~70℃
        </td>
        <td class="TableData" colspan="" style="text-align:left" rowspan="3">
        单选，一般建议选择低温型或者常温型。有特殊要求需先跟供应商商讨定制。
        </td>
    </tr>
    <tr>
    <td class="TableData" colspan="">
            17
        </td>
        <td class="TableData" colspan="">
        <input type="checkbox" name="param_templ[data_17_1]" lay-skin="primary" class="class_one" title="常温型" value="Y" <?if($param_templ_data['data_17_1']=="Y"):?>checked="checked"<?endif;?>>
        </td>
        <td class="TableData" colspan="" style="text-align:left">
        工作温度范围-25~80℃
        </td>
       
    </tr>
    <tr>
    <td class="TableData" colspan="">
            18
        </td>
        <td class="TableData" colspan="">
        <input type="checkbox" name="param_templ[data_18_1]" lay-skin="primary" class="class_one" title="其它" value="Y" <?if($param_templ_data['data_18_1']=="Y"):?>checked="checked"<?endif;?>>
        </td>
        <td class="TableData" colspan="" style="text-align:left">
        工作温度范围<input type="text" class="class_one" name="param_templ[data_18_2]" id="" class="layui-input" value="<?=$param_templ_data['data_18_2']?>" />~<input type="text" class="class_one" name="param_templ[data_18_3]" id="" class="layui-input" value="<?=$param_templ_data['data_18_3']?>" />℃
        </td>
       
    </tr>
    <tr>
        <td class="TableHeader" colspan="8" style="text-align:left">四、耐久性</td>
    </tr>
    <tr>
    <td class="TableData" colspan="">
            19
        </td>
        <td class="TableData" colspan="">
        是否防UV
        </td>
        <td class="TableData" colspan="" style="text-align:left">
            <input type="hidden" name="param_templ[data_19_1]"  value="N">
            <input type="hidden" name="param_templ[data_19_2]"  value="N">
            <input type="checkbox" name="param_templ[data_19_1]" lay-skin="primary" class="class_one" title="防UV" value="Y" <?if($param_templ_data['data_19_1']=="Y"):?>checked="checked"<?endif;?>>
            <input type="checkbox" name="param_templ[data_19_2]" lay-skin="primary" class="class_one" title="不防UV" value="Y"<?if($param_templ_data['data_19_2']=="Y"):?>checked="checked"<?endif;?>>
           
        </td>
        <td class="TableData" colspan="" style="text-align:left">
            单选，默认非集成
        </td>
    </tr>
    <tr>
    <td class="TableData" colspan="">
            20
        </td>
        <td class="TableData" colspan="">
        <input type="checkbox" name="param_templ[data_20_1]" lay-skin="primary" class="class_one" title="低耐久" value="Y" <?if($param_templ_data['data_20_1']=="Y"):?>checked="checked"<?endif;?>>
        </td>
        <td class="TableData" colspan="" style="text-align:left">
        85℃ 85%RH  240小时
        </td>
        <td class="TableData" colspan="" style="text-align:left" rowspan="4">
        单选，一般默认中耐久，常规条件下等效寿命10年；成本很低时可选低耐久；ALT测试必须选择高耐久以上。
        </td>
    </tr>
    <tr>
    <td class="TableData" colspan="">
            21
        </td>
        <td class="TableData" colspan="">
        <input type="checkbox" name="param_templ[data_21_1]" lay-skin="primary" class="class_one" title="中耐久" value="Y" <?if($param_templ_data['data_21_1']=="Y"):?>checked="checked"<?endif;?>>
        </td>
        <td class="TableData" colspan="" style="text-align:left">
        85℃ 85%RH  500小时
        </td>
    </tr>
    <tr>
    <td class="TableData" colspan="">
            22
        </td>
        <td class="TableData" colspan="">
        <input type="checkbox" name="param_templ[data_22_1]" lay-skin="primary" class="class_one" title="高耐久" value="Y" <?if($param_templ_data['data_22_1']=="Y"):?>checked="checked"<?endif;?>>
        </td>
        <td class="TableData" colspan="" style="text-align:left">
        85℃ 85%RH  1000小时
        </td>
    </tr>
    <tr>
    <td class="TableData" colspan="">
            23
        </td>
        <td class="TableData" colspan="">
        <input type="checkbox" name="param_templ[data_23_1]" lay-skin="primary" class="class_one" title="超高耐久" value="Y" <?if($param_templ_data['data_23_1']=="Y"):?>checked="checked"<?endif;?>>
        </td>
        <td class="TableData" colspan="" style="text-align:left">
        85℃ 85%RH  1200小时
        </td>
        
    </tr>
    <tr>
        <td class="TableHeader" colspan="8" style="text-align:left">五、有害物质管理</td>
    </tr>
    <tr>
        <td class="TableData" colspan="">
            24
        </td>
        <td class="TableData" colspan="">
            有害物质标准
        </td>
        <td class="TableData" colspan="" style="text-align:left">
            <input type="hidden" name="param_templ[data_24_1]"  value="N">
            <input type="hidden" name="param_templ[data_24_2]"  value="N">
            <input type="hidden" name="param_templ[data_24_3]"  value="N">
            <input type="checkbox" name="param_templ[data_24_1]" lay-skin="primary" class="class_one" title="ROHS" value="Y" <?if($param_templ_data['data_24_1']=="Y"):?>checked="checked"<?endif;?>>
            <input type="checkbox" name="param_templ[data_24_2]" lay-skin="primary" class="class_one" title="REACH" value="Y"<?if($param_templ_data['data_24_2']=="Y"):?>checked="checked"<?endif;?>>
            <input type="checkbox" name="param_templ[data_24_3]" lay-skin="primary" class="class_one" title="无卤" value="Y" <?if($param_templ_data['data_24_3']=="Y"):?>checked="checked"<?endif;?>>
        </td>
        <td class="TableData" colspan="" style="text-align:left">
        可多选，ROHS一般要求符合
        </td>
    </tr>
</table>