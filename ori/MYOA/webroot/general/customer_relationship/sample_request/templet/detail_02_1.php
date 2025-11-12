<?php 
$param_templ_data=$param_templ_arr['02'];
?>
<table id="tab10" class="param_templ pt02" width="100%"  <?if($request_main[material_type]!="02"):?>hidden="hidden"<?endif;?>>
    <input name="param_templ_v" type="hidden" value="1">

    <tr>
        <td class="TableHeader" colspan="8" style="text-align:center">参数配置</td>
    </tr>
    
    <tr>
        <td class="TableData" colspan="" style="text-align:left" colspan="8">

            <?=$param_templ_data['data2_1_1']?>,

            <?if($param_templ_data['data2_2_1']):?>
                <?=$param_templ_data['data2_2_1']?>,
            <?endif;?>

            <?if($param_templ_data['data2_3_1']):?>
                <?if($param_templ_data['data2_3_1']=="其它"):?>铜软线<?=$param_templ_data['data2_3_2']?>圈
                <?else:?>
                    <?=$param_templ_data['data2_3_1']?>
                <?endif;?>,
            <?endif;?>

            <?=$param_templ_data['data2_4_1']?>,

            <?if($param_templ_data['data2_5_1']):?>
                <?if($param_templ_data['data2_5_1']=="其它"):?><?=$param_templ_data['data2_3_2']?>
                <?else:?>
                    <?=$param_templ_data['data2_5_1']?>
                <?endif;?>,
            <?endif;?>

            <?if($param_templ_data['data2_6_1']):?>
                <?if($param_templ_data['data2_6_1']=="其它"):?><?=$param_templ_data['data2_6_2']?>A
                <?else:?>
                    <?=$param_templ_data['data2_6_1']?>
                <?endif;?>,
            <?endif;?>

            <?if($param_templ_data['data2_7_1']):?>
                <?if($param_templ_data['data2_7_1']=="其它"):?><?=$param_templ_data['data2_7_2']?>mA
                <?else:?>
                    <?=$param_templ_data['data2_7_1']?>
                <?endif;?>,
            <?endif;?>

            <?if($param_templ_data['data2_8_1']):?>
                <?if($param_templ_data['data2_8_1']=="其它"):?><?=$param_templ_data['data2_8_2']?>
                <?else:?>
                    <?=$param_templ_data['data2_8_1']?>
                <?endif;?>,
            <?endif;?>

            <?if($param_templ_data['data2_9_1']):?>
                <?if($param_templ_data['data2_9_1']=="其它"):?><?=$param_templ_data['data2_9_2']?>Ω
                <?else:?>
                    <?=$param_templ_data['data2_9_1']?>
                <?endif;?>,
            <?endif;?>

            <?if($param_templ_data['data2_10_1']):?>
                <?if($param_templ_data['data2_10_1']=="其它"):?><?=$param_templ_data['data2_10_2']?>%
                <?else:?>
                    <?=$param_templ_data['data2_10_1']?>
                <?endif;?>,
            <?endif;?>

            <?if($param_templ_data['data2_11_1']):?>
                <?if($param_templ_data['data2_11_1']=="其它"):?><?=$param_templ_data['data2_11_2']?>mA
                <?else:?>
                    红黑 24AWG UL1569 105℃ 300V
                <?endif;?>,
            <?endif;?>
            ROHS
            <?if($param_templ_data['data2_14_2']=="Y"):?>、REACH<?endif;?>
            <?if($param_templ_data['data2_14_3']=="Y"):?>、无卤<?endif;?>
<pre>
<p></p>
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