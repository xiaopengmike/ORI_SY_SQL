<?php
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
include_once("inc/utility_file.php");
include_once("inc/utility_field.php");
include_once("../common/CommonControl.php");  // 公共类

// 65	sctz2020081001	meter_one	2	1	serial_number	2
// 1	sctz2015121410	2	电表	12	3000						0	0		0

// serial_number
// product_name
// connection_mode
// production_mode
// time_zone
// dst_option
// meter_no_range
// enclosure_requirement
// logo
// panel
// power_plug_model
// card_matching_text
// factory_settings_file
$sql = " select * from crs_tabledata696 ";

$res = exequery(TD::conn(), $sql);
while($item = mysql_fetch_assoc($res)){
    $result[] = $item;
}

foreach($result as $val){
    $insert = " insert into inhe_contract_settle(
    form_id,
    item_id,
    content,
    attach_name
    ) values ('$val[col12862]','12','$val[col24736]','')";
    exequery(TD::conn(), $insert);
}
var_export('success');exit;

// form_id
// item_id
// content
// attach_name
// user_bu

// 1	INHESC2020081101	1	否		INHE
// col12862

// 1	1、单价中是否包含保费和运费？如包含，分别是多少？  col19271
// 2	2、价格术语：  col19264
// 3	3、结算方式（信用政策）：  col19270
// 4	4、发货条件：  col19265
// 5	5、产品质保期限：  col19266
// 6	6、是否赊销？  col19267
// 7	7、是否需要向中信保投保？  col19268
// 8	8、交期延迟时，是否产生罚金或违约金？  col19269
// 9	9、销售模式：   col19449
// 10	10、是否为第三方支付：   col19959     col19960
// 11	11、是否需要支付佣金？     col24735
// 12	12、佣金计算方法？   col24736

	
