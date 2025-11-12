<?php 
$param_templ_data=$param_templ_arr['03'];
?>
<table id="tab10" class="param_templ pt03" width="100%"  <?if($request_main[material_type]!="03"):?>hidden="hidden"<?endif;?>>
    <input name="param_templ_v" type="hidden" value="1">

    <tr>
        <td class="TableHeader" colspan="8" style="text-align:center">参数配置</td>
    </tr>
    
    <tr>
        <td class="TableData" colspan="" style="text-align:left" colspan="8">

            <?=$param_templ_data['data3_1_1']?>,

            <?if($param_templ_data['data3_2_1']):?>
                <?=$param_templ_data['data3_2_1']?>,
            <?endif;?>

            <?=$param_templ_data['data3_3_1']?>组,


            <?if($param_templ_data['data3_4_1']=="其它"):?><?=$param_templ_data['data3_4_2']?>
                <?else:?>
                    <?=$param_templ_data['data3_4_1']?>
                <?endif;?>,

                <?if($param_templ_data['data3_5_1']=="其它"):?><?=$param_templ_data['data3_5_2']?>
                <?else:?>
                    <?=$param_templ_data['data3_5_1']?>
                <?endif;?>,

                <?=$param_templ_data['data3_6_1']?>,

                <?if($param_templ_data['data3_7_1']=="其它"):?><?=$param_templ_data['data3_7_2']?>mA
                <?else:?>
                    <?=$param_templ_data['data3_7_1']?>
                <?endif;?>,

                <?if($param_templ_data['data3_8_1']=="其它"):?><?=$param_templ_data['data3_8_2']?>
                <?else:?>
                    <?=$param_templ_data['data3_8_1']?>
                <?endif;?>,

                <?=$param_templ_data['data3_9_1']?>,

            <?if($param_templ_data['data3_10_1']):?>
                    <?=$param_templ_data['data3_10_1']?>,
            <?endif;?>
            <?if($param_templ_data['data3_11_1']=="其它"):?><?=$param_templ_data['data3_11_2']?>uΩ±<?=$param_templ_data['data3_11_3']?>%,
                <?else:?>
                    <?=$param_templ_data['data3_11_1']?>
                <?endif;?>,
            
            
            <?if($param_templ_data['data3_12_1']):?>红黑 24AWG UL1569 105℃ 300V <?endif;?>
            <?if($param_templ_data['data3_13_1']):?>白色  UL3266 125℃ 300V 穿过锰铜<?endif;?>
            <?if($param_templ_data['data3_14_1']):?><?=$param_templ_data['data3_14_2']?><?endif;?>
<pre>
<p></p>
1.参考国网标准，磁保持继电器最大接触阻抗1.5mΩ；
2.参考国网标准，单相表用继电器最大闭合/断开时间20mS，三相表用继电器最大闭合/断开时间30mS；
3.参考国网标准，单相表用继电器最小驱动脉冲宽度50mS，单相表用继电器最小驱动脉冲宽度120mS；
4.额定条件继电器电气开合寿命不少于5000次；
5.触点开闸条件下触点间工频耐压2KVac以上，触点线圈间工频耐压4.3KVac以上，测试条件:最大漏电流1mA/60s；
6.触点间、触点线圈间绝缘阻抗≥1000MΩ，测试条件:500Vac/3s；
7.脉冲耐压测试要求:6KV/1.2/50uS波形/正负各5次/间隔1Min；
8.工作温度范围要求至少-40~85℃；
9.工作海拔条件要求4000m以上；
10.MID认证要求，塑料绝缘(漏电起痕指数)满足 IEC60112-2009标准；
11.MID认证要求，外壳塑料阻燃满足 UL94 V0等级；
12.MID认证要求，其它塑料阻燃满足 UL94 V2等级；
13.漆包线要求要求2UEW-B等级以上(或其它标准同等等级)；
14.锰铜功率要求≥2*额定电流2*锰铜阻抗；
15.端子焊接必须点锡；
<pre>
        </td>
    </tr>
    
</table>