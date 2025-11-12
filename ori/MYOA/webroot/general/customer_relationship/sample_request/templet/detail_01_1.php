<?php 
$param_templ_data=$param_templ_arr['01'];
?>
<table id="tab10" class="param_templ pt01" width="100%"  <?if($request_main[material_type]!="01"):?>hidden="hidden"<?endif;?>>
    <input name="param_templ_v" type="hidden" value="1">
    <tr>
        <td class="TableHeader" colspan="8" style="text-align:center">参数配置</td>
    </tr>
    
    <tr>
        <td class="TableData" colspan="" style="text-align:left" colspan="8">
            <?if($param_templ_data['data_1_1']=="其它"):?><?=$param_templ_data['data_1_2']?>
            <?else:?>
                <?=$param_templ_data['data_1_1']?>
            <?endif;?>,

            <?if($param_templ_data['data_2_1']=="其它"):?><?=$param_templ_data['data_2_2']?>
            <?else:?>
                <?=$param_templ_data['data_2_1']?>
            <?endif;?>,

            <?if($param_templ_data['data_3_1']=="其它"):?><?=$param_templ_data['data_3_2']?>
            <?else:?>
                <?=$param_templ_data['data_3_1']?>
            <?endif;?>,

            <?if($param_templ_data['data_4_1']=="其它"):?><?=$param_templ_data['data_4_2']?>
            <?else:?>
                <?=$param_templ_data['data_4_1']?>
            <?endif;?>,

            <?=$param_templ_data['data_5_1']?>,

            <?if($param_templ_data['data_6_1']=="其它"):?><?=$param_templ_data['data_6_2']?>
            <?else:?>
                <?=$param_templ_data['data_6_1']?>
            <?endif;?>,

            <?=$param_templ_data['data_9_1']?>,

            Duty=1/<?=$param_templ_data['data_10_1']?>,

            Duty=1/<?=$param_templ_data['data_11_1']?>,

            <?=$param_templ_data['data_12_1']?>

            <?=$param_templ_data['data_13_1']?>,

            <?if($param_templ_data['data_3_1']=="COG"):?>
            型号<?=$param_templ_data['data_14_1']?>    HBM耐压<?=$param_templ_data['data_14_2']?>,
            <?endif;?>

            <?=$param_templ_data['data_15_1']?>,

            <?if($param_templ_data['data_16_1']):?>
                <?if($param_templ_data['data_16_1']=="其它"):?>
                    工作温度范围<?=$param_templ_data['data_18_2']?>~<?=$param_templ_data['data_18_3']?>℃
                <?elseif($param_templ_data['data_16_1']=="低温型"):?>
                    工作温度范围-40~70℃
                <?elseif($param_templ_data['data_16_1']=="常温型"):?>
                    工作温度范围-25~80℃
                <?endif;?>,
            <?endif;?>

            <?=$param_templ_data['data_19_1']?>,

            <?if($param_templ_data['data_20_1']=="低耐久"):?>
                    85℃ 85%RH  240小时
                <?elseif($param_templ_data['data_20_1']=="高耐久"):?>
                    85℃ 85%RH  1000小时
                <?elseif($param_templ_data['data_20_1']=="超高耐久"):?>
                    85℃ 85%RH  1200小时
                <?else:?>
                    85℃ 85%RH  500小时
                <?endif;?>,

            ROHS
            <?if($param_templ_data['data_24_2']=="Y"):?>、REACH<?endif;?>
            <?if($param_templ_data['data_24_3']=="Y"):?>、无卤<?endif;?>,

                在20℃±5℃条件下对比度不应小于4,

            <?=$param_templ_data['data_8_1']?>号样片色/参照<?=$param_templ_data['data_8_2']?>型号液晶显示器的底色
        </td>
    </tr>
</table>