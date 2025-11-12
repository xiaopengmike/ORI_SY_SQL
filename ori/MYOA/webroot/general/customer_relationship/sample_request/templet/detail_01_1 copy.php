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
            <?if($param_templ_data['data_1_1']=="Y"):?>HTN<?endif;?>
            <?if($param_templ_data['data_1_2']=="Y"):?>FSTN<?endif;?>
            <?if($param_templ_data['data_1_3']=="Y"):?> <?=$param_templ_data['data_1_4']?><?endif;?>  
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
            <?if($param_templ_data['data_2_1']=="Y"):?>段码<?endif;?>
            <?if($param_templ_data['data_2_2']=="Y"):?>点阵<?endif;?>
            <?if($param_templ_data['data_2_3']=="Y"):?><?=$param_templ_data['data_2_4']?><?endif;?>
            
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

            <?if($param_templ_data['data_3_1']=="Y"):?>非集成<?endif;?>
            <?if($param_templ_data['data_3_2']=="Y"):?>COG<?endif;?>
            <?if($param_templ_data['data_3_3']=="Y"):?><?=$param_templ_data['data_3_4']?><?endif;?>
            
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

            <?if($param_templ_data['data_4_1']=="Y"):?>正性显示<?endif;?>
            <?if($param_templ_data['data_4_2']=="Y"):?><?=$param_templ_data['data_4_3']?><?endif;?>
            
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
            <?if($param_templ_data['data_5_1']=="Y"):?>高半透型<?endif;?>
            <?if($param_templ_data['data_5_2']=="Y"):?>全透型<?endif;?>
            <?if($param_templ_data['data_5_3']=="Y"):?>反射型<?endif;?>
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
            
            <?if($param_templ_data['data_6_1']=="Y"):?>6点钟<?endif;?>
            <?if($param_templ_data['data_6_2']=="Y"):?> <?=$param_templ_data['data_6_3']?><?endif;?>
            
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
       <?=$param_templ_data['data_8_1']?>号样片色/参照<?=$param_templ_data['data_8_2']?>型号液晶显示器的底色
            
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
       
            <?if($param_templ_data['data_9_1']=="Y"):?>插针<?endif;?>
            <?if($param_templ_data['data_9_2']=="Y"):?>导电胶条<?endif;?>
            <?if($param_templ_data['data_9_3']=="Y"):?>FPC<?endif;?>
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
            1/<?=$param_templ_data['data_10_1']?>
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
            1/<?=$param_templ_data['data_11_1']?>
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
            <?=$param_templ_data['data_12_1']?>
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
             <?if($param_templ_data['data_13_1']=="Y"):?>±0.2V<?endif;?>
            <?if($param_templ_data['data_13_2']=="Y"):?>±0.1V<?endif;?>
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
        型号<?=$param_templ_data['data_14_1']?>    HBM耐压<?=$param_templ_data['data_14_2']?>
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
            <?=$param_templ_data['data_15_1']?>
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
        <?if($param_templ_data['data_16_1']=="Y"):?>
        <td class="TableData" colspan="">
         <?if($param_templ_data['data_16_1']=="Y"):?>低温型<?endif;?>
        </td>
        <td class="TableData" colspan="" style="text-align:left">
            工作温度范围-40~70℃
        </td>
        <?elseif($param_templ_data['data_17_1']=="Y"):?>

            <td class="TableData" colspan="">
            <?if($param_templ_data['data_17_1']=="Y"):?>常温型<?endif;?>
            </td>
            <td class="TableData" colspan="" style="text-align:left">
            工作温度范围-25~80℃
            </td>

            <?elseif($param_templ_data['data_18_1']=="Y"):?>
                <td class="TableData" colspan="">
                <?if($param_templ_data['data_18_1']=="Y"):?><?endif;?>
                </td>
                <td class="TableData" colspan="" style="text-align:left">
                工作温度范围<?=$param_templ_data['data_18_2']?>~<?=$param_templ_data['data_18_3']?>℃
                </td>
                <?endif;?>
        <td class="TableData" colspan="" style="text-align:left" >
        单选，一般建议选择低温型或者常温型。有特殊要求需先跟供应商商讨定制。
        </td>
    </tr>
    <!-- <tr>
    <td class="TableData" colspan="">
            17
        </td>
        <td class="TableData" colspan="">
        <?if($param_templ_data['data_17_1']=="Y"):?>常温型<?endif;?>
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
        <?if($param_templ_data['data_18_1']=="Y"):?>其它<?endif;?>
        </td>
        <td class="TableData" colspan="" style="text-align:left">
        工作温度范围<?=$param_templ_data['data_18_2']?>~<?=$param_templ_data['data_18_3']?>℃
        </td>
       
    </tr> -->
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
            <?if($param_templ_data['data_19_1']=="Y"):?>防UV<?endif;?>
            <?if($param_templ_data['data_19_2']=="Y"):?>不防UV<?endif;?>
           
        </td>
        <td class="TableData" colspan="" style="text-align:left">
            单选，默认非集成
        </td>
    </tr>
    <tr>
    <td class="TableData" colspan="">
            20
        </td>
        <?if($param_templ_data['data_20_1']=="Y"):?>
            <td class="TableData" colspan="">
            <?if($param_templ_data['data_20_1']=="Y"):?>低耐久<?endif;?>
            </td>
            <td class="TableData" colspan="" style="text-align:left">
            85℃ 85%RH  240小时
            </td>
        <?elseif($param_templ_data['data_21_1']=="Y"):?>
            <td class="TableData" colspan="">
            <?if($param_templ_data['data_21_1']=="Y"):?>中耐久<?endif;?>
            </td>
            <td class="TableData" colspan="" style="text-align:left">
            85℃ 85%RH  500小时
            </td>
            <?elseif($param_templ_data['data_22_1']=="Y"):?>
                <td class="TableData" colspan="">
                <?if($param_templ_data['data_22_1']=="Y"):?>高耐久<?endif;?>
                </td>
                <td class="TableData" colspan="" style="text-align:left">
                85℃ 85%RH  1000小时
                </td>
                <?elseif($param_templ_data['data_23_1']=="Y"):?>
                    <td class="TableData" colspan="">
                    <?if($param_templ_data['data_23_1']=="Y"):?>超高耐久<?endif;?>
                    </td>
                    <td class="TableData" colspan="" style="text-align:left">
                    85℃ 85%RH  1200小时
                    </td>
                    <?endif;?>

        <td class="TableData" colspan="" style="text-align:left" >
        单选，一般默认中耐久，常规条件下等效寿命10年；成本很低时可选低耐久；ALT测试必须选择高耐久以上。
        </td>
    </tr>
    <!-- <tr>
    <td class="TableData" colspan="">
            21
        </td>
        <td class="TableData" colspan="">
        <?if($param_templ_data['data_21_1']=="Y"):?>中耐久<?endif;?>
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
        <?if($param_templ_data['data_22_1']=="Y"):?>高耐久<?endif;?>
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
        <?if($param_templ_data['data_23_1']=="Y"):?>超高耐久<?endif;?>
        </td>
        <td class="TableData" colspan="" style="text-align:left">
        85℃ 85%RH  1200小时
        </td>
        
    </tr> -->
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
            <?if($param_templ_data['data_24_1']=="Y"):?>ROHS<?endif;?>
            <?if($param_templ_data['data_24_2']=="Y"):?>REACH<?endif;?>
            <?if($param_templ_data['data_24_3']=="Y"):?>无卤<?endif;?>
        </td>
        <td class="TableData" colspan="" style="text-align:left">
        可多选，ROHS一般要求符合
        </td>
    </tr>
</table>