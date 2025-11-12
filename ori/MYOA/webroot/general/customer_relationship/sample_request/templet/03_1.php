<?php 
$param_templ_data=$param_templ_arr['03'];
?>
<table id="tab10" class="param_templ pt03" width="100%"  <?if($request_main[material_type]!="03"):?>hidden="hidden"<?endif;?>>
    <input name="param_templ_v" type="hidden" value="1">

    <tr>
        <td class="TableHeader" colspan="8" style="text-align:left">一、可选参数（<span style="color:red">如有特殊要求，请重选以下参数要求，否则按默认参数生成打样要求</span>）</td>
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
        <input type="radio" name="param_templ[data3_1_1]" lay-skin="primary" class="class_one" title="不带屏蔽" value="不带屏蔽" checked="checked" >
            <input type="radio" name="param_templ[data3_1_1]" lay-skin="primary" class="class_one" title="内屏蔽" value="内屏蔽" <?if($param_templ_data['data3_1_1']=="内屏蔽"):?>checked="checked"<?endif;?> >
            <input type="radio" name="param_templ[data3_1_1]" lay-skin="primary" class="class_one" title="外屏蔽" value="外屏蔽" <?if($param_templ_data['data3_1_1']=="外屏蔽"):?>checked="checked"<?endif;?> >
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
        <input type="radio" name="param_templ[data3_2_1]" lay-skin="primary" class="class_one" title="软连接" value="软连接" <?if($param_templ_data['data3_2_1']=="软连接"):?>checked="checked"<?endif;?> >
            <input type="radio" name="param_templ[data3_2_1]" lay-skin="primary" class="class_one" title="硬链接" value="硬链接" <?if($param_templ_data['data3_2_1']=="硬链接"):?>checked="checked"<?endif;?> >
            
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
        驱动组数
        </td>
        <td class="TableData" colspan="" style="text-align:left">

            <input type="text" class="class_one " name="param_templ[data3_3_1]" id="apply_user" class="layui-input" value="<?=$param_templ_data['data3_3_1']?$param_templ_data['data3_3_1']:1?>"  />组
        </td>
        <td class="TableData" colspan="" style="text-align:left">
        数字表示，控制路数
        </td>
    </tr>
   
    <tr>
        <td class="TableData" colspan="">
            4
        </td>
        <td class="TableData" colspan="">
        触点形式
        </td>
        <td class="TableData" colspan="" style="text-align:left">
            
            <input type="radio" name="param_templ[data3_4_1]" lay-skin="primary" class="class_one" title="单触点" value="单触点" checked="checked" lay-filter="other_info">
            <input type="radio" name="param_templ[data3_4_1]" lay-skin="primary" class="class_one" title="双触点" value="双触点" <?if($param_templ_data['data3_4_1']=="双触点"):?>checked="checked"<?endif;?> lay-filter="other_info">
        
                <input type="radio" name="param_templ[data3_4_1]" lay-skin="primary" class="class_one" title="其它" value="其它" <?if($param_templ_data['data3_4_1']=="其它"):?>checked="checked"<?endif;?> lay-filter="other_info">
            <input type="text" class="class_one other_info" name="param_templ[data3_4_2]" id="apply_user" class="layui-input" value="<?=$param_templ_data['data3_4_2']?>"  readonly="readonly"/>
        </td>
        <td class="TableData" colspan="" style="text-align:left">
        单选，默认单触点，有其它形式
        </td>
    </tr>
    <tr>
        <td class="TableData" colspan="">
            5
        </td>
        <td class="TableData" colspan="">
        额定负载
        </td>
        <td class="TableData" colspan="" style="text-align:left">
            
            <input type="radio" name="param_templ[data3_5_1]" lay-skin="primary" class="class_one" title="120A/250Vac" value="120A/250Vac" checked="checked" lay-filter="other_info">
            <input type="radio" name="param_templ[data3_5_1]" lay-skin="primary" class="class_one" title="90A/250Vac" value="90A/250Vac" <?if($param_templ_data['data3_5_1']=="90A/250Vac"):?>checked="checked"<?endif;?> lay-filter="other_info">
                <input type="radio" name="param_templ[data3_5_1]" lay-skin="primary" class="class_one" title="60A/250Vac" value="60A/250Vac" <?if($param_templ_data['data3_5_1']=="60A/250Vac"):?>checked="checked"<?endif;?> lay-filter="other_info">
            <input type="radio" name="param_templ[data3_5_1]" lay-skin="primary" class="class_one" title="其它" value="其它" <?if($param_templ_data['data3_5_1']=="其它"):?>checked="checked"<?endif;?> lay-filter="other_info">
            <input type="text" class="class_one other_info" name="param_templ[data3_5_2]" id="apply_user" class="layui-input" value="<?=$param_templ_data['data3_5_2']?>"  readonly="readonly"/>
        </td>
        <td class="TableData" colspan="" style="text-align:left">
        继电器本体额定负载电流、电压
        </td>
    </tr>
    <tr>
        <td class="TableData" colspan="">
            6
        </td>
        <td class="TableData" colspan="">
        UC等级
        </td>
        <td class="TableData" colspan="" style="text-align:left">
            
            <input type="radio" name="param_templ[data3_6_1]" lay-skin="primary" class="class_one" title="无" value="无" checked="checked" lay-filter="other_info">
            <input type="radio" name="param_templ[data3_6_1]" lay-skin="primary" class="class_one" title="1" value="1" <?if($param_templ_data['data3_6_1']=="1"):?>checked="checked"<?endif;?> lay-filter="other_info">
                <input type="radio" name="param_templ[data3_6_1]" lay-skin="primary" class="class_one" title="2" value="2" <?if($param_templ_data['data3_6_1']=="2"):?>checked="checked"<?endif;?> lay-filter="other_info">
            <input type="radio" name="param_templ[data3_6_1]" lay-skin="primary" class="class_one" title="3" value="3" <?if($param_templ_data['data3_6_1']=="3"):?>checked="checked"<?endif;?> lay-filter="other_info">
        </td>
        <td class="TableData" colspan="" style="text-align:left">
        单选，标准参考IEC6205
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
            
        <input type="radio" name="param_templ[data3_7_1]" lay-skin="primary" class="class_one" title="12V" value="12V" checked="checked" lay-filter="other_info">
        <input type="radio" name="param_templ[data3_7_1]" lay-skin="primary" class="class_one" title="其它" value="其它" <?if($param_templ_data['data3_7_1']=="其它"):?>checked="checked"<?endif;?> lay-filter="other_info">
        <input type="text" class="class_one other_info" name="param_templ[data3_7_2]" id="apply_user" class="layui-input" value="<?=$param_templ_data['data3_7_2']?>"  readonly="readonly"/>
        </td>
        <td class="TableData" colspan="" style="text-align:left">
        驱动电压范围在额定驱动电压的70%~150%；默认12V，有其它选型提出
        </td>
    </tr>
    <tr>
        <td class="TableData" colspan="">
            8
        </td>
        <td class="TableData" colspan="">
        驱动闭合方式
        </td>
        <td class="TableData" colspan="" style="text-align:left">
           
            <input type="radio" name="param_templ[data3_8_1]" lay-skin="primary" class="class_one" title="正极性" value="正极性" checked="checked" lay-filter="other_info">
            <input type="radio" name="param_templ[data3_8_1]" lay-skin="primary" class="class_one" title="负极性" value="负极性" <?if($param_templ_data['data3_8_1']=="负极性"):?>checked="checked"<?endif;?> lay-filter="other_info">
            <input type="radio" name="param_templ[data3_8_1]" lay-skin="primary" class="class_one" title="其它" value="其它" <?if($param_templ_data['data3_8_1']=="其它"):?>checked="checked"<?endif;?> lay-filter="other_info">
            <input type="text" class="class_one other_info" name="param_templ[data3_8_2]" id="apply_user" class="layui-input" value="<?=$param_templ_data['data3_8_2']?>"  readonly="readonly"/>
        </td>
        <td class="TableData" colspan="" style="text-align:left">
        单选，以负极为参考，断开驱动方式默认相反；硬连接需要用丝印“*”标注驱动负极
        </td>
    </tr>
    <tr>
        <td class="TableData" colspan="">
            9
        </td>
        <td class="TableData" colspan="">
        线圈组数
        </td>
        <td class="TableData" colspan="" style="text-align:left">
            
            <input type="radio" name="param_templ[data3_9_1]" lay-skin="primary" class="class_one" title="单线圈" value="单线圈" checked="checked" lay-filter="other_info">
            <input type="radio" name="param_templ[data3_9_1]" lay-skin="primary" class="class_one" title="双线圈" value="双线圈" <?if($param_templ_data['data3_9_1']=="双线圈"):?>checked="checked"<?endif;?> lay-filter="other_info">
    
        </td>
        <td class="TableData" colspan="" style="text-align:left">
        单选，默认单线圈
        </td>
    </tr>
    <tr>
        <td class="TableData" colspan="">
            10
        </td>
        <td class="TableData" colspan="">
        铜排额定电流
        </td>
        <td class="TableData" colspan="" style="text-align:left">

            <input type="text" class="class_one " lay-verify="param_required" name="param_templ[data3_10_1]" id="data3_10_1" class="layui-input" value="<?=$param_templ_data['data3_10_1']?>"  />A
        </td>
        <td class="TableData" colspan="" style="text-align:left">
        
        </td>
    </tr>
    <tr>
        <td class="TableData" colspan="">
            11
        </td>
        <td class="TableData" colspan="">
        锰铜阻抗
        </td>
        <td class="TableData" colspan="" style="text-align:left">
        
        <input type="radio" name="param_templ[data3_11_1]" lay-skin="primary" class="class_one" title="无锰铜" value="无锰铜" <?if($param_templ_data['data3_11_1']=="无锰铜"):?>checked="checked"<?endif;?> lay-filter="other_info">
            <input type="radio" name="param_templ[data3_11_1]" lay-skin="primary" class="class_one" title="其它" value="其它" <?if($param_templ_data['data3_11_1']=="其它"):?>checked="checked"<?endif;?> lay-filter="other_info">
            <input type="text" class="class_one other_info" name="param_templ[data3_11_2]" id="apply_user" class="layui-input" value="<?=$param_templ_data['data3_11_2']?>"  />uΩ±
            <input type="text" class="class_one other_info" name="param_templ[data3_11_3]" id="apply_user" class="layui-input" value="<?=$param_templ_data['data3_11_3']?$param_templ_data['data3_11_2']:5?>"  />%
        </td>
        <td class="TableData" colspan="" style="text-align:left">
        精度要求≤5%，全温度范围内温漂＜50ppm
        </td>
    </tr>
    <tr>
        <td class="TableData" colspan="">
            12
        </td>
        <td class="TableData" colspan="" rowspan="3">
        线材标准
        </td>
        <td class="TableData" colspan="" style="text-align:left">
        <input type="hidden" name="param_templ[data3_12_1]"  value="N">
          
            <input type="checkbox" name="param_templ[data3_12_1]" lay-skin="primary" class="class_one" title="红黑 24AWG UL1569 105℃ 300V" value="Y" checked="checked" >
         
        </td>
        <td class="TableData" colspan="" style="text-align:left">
        常规选用，正负极要求交替缠绕
        </td>
    </tr>
    <tr>
        <td class="TableData" colspan="">
            13
        </td>
      
        <td class="TableData" colspan="" style="text-align:left">
            <input type="hidden" name="param_templ[data3_13_1]"  value="N">
            <input type="checkbox" name="param_templ[data3_13_1]" lay-skin="primary" class="class_one" title="白色  UL3266 125℃ 300V 穿过锰铜" value="Y" checked="checked" >
        </td>
        <td class="TableData" colspan="" style="text-align:left">
        UL3266大部分不能满足安规阻燃VW-1认证要求
        </td>
    </tr>
    <tr>
        <td class="TableData" colspan="">
            14
        </td>
       
        <td class="TableData" colspan="" style="text-align:left">
        <input type="hidden" name="param_templ[data3_14_1]"  value="N">
        <input type="checkbox" name="param_templ[data3_14_1]" lay-skin="primary" class="class_one" title="其它" value="Y" <?if($param_templ_data['data3_14_1']=="Y"):?>checked="checked"<?endif;?>>
            <input type="text" class="class_one other_info" name="param_templ[data3_14_2]" id="apply_user" class="layui-input" value="<?=$param_templ_data['data3_14_2']?>"  />
        </td>
        <td class="TableData" colspan="" style="text-align:left">
        
        </td>
    </tr>
    <tr>
        <td class="TableHeader" colspan="8" style="text-align:left">二、必要条件</td>
    </tr>
    <tr>
        <td class="TableData" colspan="8" >
        <pre>
        1.参考国网标准，磁保持继电器最大接触阻抗1.5mΩ；
        2.参考国网标准，单相表用继电器最大闭合/断开时间20mS，三相表用继电器最大闭合/断开时间30mS；
        3.参考国网标准，单相表用继电器最小驱动脉冲宽度50mS，三相表用继电器最小驱动脉冲宽度120mS；
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