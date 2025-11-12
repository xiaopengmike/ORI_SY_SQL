<?php
require_once 'inc/auth.inc.php';
require_once('/MYOA/webroot/general/customer_relationship/common/CommonMethodModel.php');
$model = new CommonMethodModel();

// $url = "https://hq.sinajs.cn/list=";
// $text=file_get_contents($url);
// preg_match('/<div[^>]*class="quote-container"[^>]*>(.*?) </div>/si',$text,$match);
// print($match[0]);
// exit;

$sql = " select code from fund_season_stock  limit 92,92 "; // where price is null  
$res = exequery(TD::conn(), $sql);
$codeArr = array();
while ($item = mysql_fetch_assoc($res)) {
    $codeArr[] = $item['code'];
}
foreach ($codeArr as $code) {
    // $code = "601318";
    $url = "https://m.wayougou.com/api/ratings/stock?code=" . $code . "&name=%E5%90%8C%E8%A1%8C%E4%B8%9A";
    // $url = "https://hq.sinajs.cn/list=" . $code;
    // $url - "https://xueqiu.com/S/SZ000001";
    // "ROE稳定性":0.1481,"代码":"601318","分类":"保险","合理价值":3261356410355.5312,"安全边际评级":"3.0",
    // "市净率PB":2.1864,"市息率":106.5079,"市现率":8.4849,"市盈率PE":12.6271,"市销率":0,"总市值":155162689.08808,"总股本":18280241410.0,"总评级":7.5167,
    // "总评级排名":1.0,"护城河评级":"5.5","排名":1.0,"收盘价":84.88,"简称":"中国平安","股息率":0.0094

    // "ROE稳定性":1.0212,"index":59,"代码":"002030","分类":"医疗器械","合理价值":14664006214.65,"好价格评级":0,"好公司评级":0,"安全边际评级":"1.0",
    // "市净率":10.9251,"市息率":1841.4,"市现率":35.6233,"市盈率":23.695,"市销率":9.4365,"总市值":3670888.52745,"总股本":877153770.0,"总评级":4.1167,
    // "总评级排名":60.0,"护城河评级":"0.5","排名":60.0,"收盘价":41.85,"简称":"达安基因","股息率":0.0005
    // $url = "https://m.wayougou.com/api/ranks/stock?name=%E6%9C%80%E4%BD%B3%E8%82%A1%E7%A5%A8%E6%A6%9C&page=1&page_size=400&market=A%E8%82%A1";
    // $tempArr = array(
    //     'ROE稳定性' => 0.2528, 'index' => 1, '代码' => '600009', '分类' => '机场', '合理价值' => 44315715220.7,
    //     '好价格评级' => 0, '好公司评级' => 0, '安全边际评级' => '1.0', '市净率' => 4.0088, '市息率' => 78.3291, '市现率' => 346.3248,
    //     '市盈率' => 399.7873, '市销率' => 19.3109, '总市值' => 11924018.8762, '总股本' => 1926958448, '总评级' => 4.05,
    //     '总评级排名' => 2, '护城河评级' => '3.0', '排名' => 2, '收盘价' => 61.88, '简称' => '上海机场', '股息率' => 0.0128, '行业内排名' => 0,
    // );
    $lines_array = file($url);
    // $lines_string = file_get_contents($url);
    // var_export($lines_string);exit;
    $lines_string = json_decode($lines_array[0], true);
    // $data = $lines_string['results']['body'];
    $data = $lines_string[0];
    // var_export($model->array_iconv($data, 'utf-8', 'gbk'));exit;
    $data = array_values($data);
    // var_export($data);exit;
    // $temp = array(
    //     0 => 1.0212, 2 => '002030', 3 => 'type', 4 => 14664006214.6, 7 => 'safe1.0', 8 => 10.9251,
    //     9 => 1841.4, 10 => 35.6233, 11 => 23.695, 12 => 9.4365, 13 => 3670888.52745,
    //     14 => 877153770, 15 => 4.1167, 16 => 60, 17 => '0.5', 18 => 60, 19 => 41.85, 20 => 'name', 21 => 0.0005,
    // array ( 0 => 0.2455, 1 => 30, 2 => '000001', 3 => '閾惰?', 4 => 831052475538, 5 => 0, 6 => 0, 7 => '3.0', 
    // 8 => 1.1394, 9 => 98.0734, 10 => -25.6728, 11 => 14.3425, 12 => 0, 13 => 41489853.1073, 14 => 19405918198, 
    // 15 => 5.45, 16 => 31, 17 => '2.5', 18 => 31, 19 => 21.38, 20 => '骞冲畨閾惰?', 21 => 0.0102, 22 => 0, )
    // );
    // array ( 'ROE稳定性' => 0.2455, '代码' => '000001', '分类' => '银行', '安全边际评级' => '3.0', '市净率PB' => 1.1394, 
    //   '市盈率PE' => 14.3425, '总市值' => 41489853.1073, '总股本' => 19405918198, 
    //  '总评级' => 5.45, '总评级排名' => 31, '护城河评级' => '2.5', '排名' => 31, '收盘价' => 21.38, '股息率' => 0.0102 );
    $type = iconv('utf-8', 'gbk', var_export($data[3], true));
    $name = iconv('utf-8', 'gbk', var_export($data[20], true));
    // var_export($name);exit;
    // name = $name , price = '$data[19]' tsize = '$data[13]',roe = '$data[0]' 
    $sql = "update fund_season_stock "
        . " set price = '$data[19]' "
        . " where code = '" . $code . "'";
    exequery(TD::conn(), $sql);
}
// foreach ($data as $val) {
//     // 3-2 rise 4-1 company_rank 4-2 price_rank 6-1 min 6-2 max 6-3 price
//     $rise = array_values($val[3]);
//     $rank = array_values($val[4]);
//     $price = array_values($val[6]);
//     $c_rise = $rise[1];
//     $company_rank = $rank[0];
//     $price_rank = $rank[1];
//     $min = $price[0];
//     $max = $price[1];
//     $price = $price[2];
//     $sql = "insert into stock_history(rise, company_rank, price_rank, min, max, price) values " .
//         "('$c_rise', '$company_rank', '$price_rank', '$min', '$max', '$price')";
//     exequery(TD::conn(), $sql);
// }

// $url = "https://xueqiu.com/service/screener/screen?category=CN&exchange=sh_sz&order_by=symbol&order=desc&page=1&size=30&only_count=0";
// $time = time();
// $select = " select id, price, rise from stock where code = '' or code is null limit 0,1 ";
// $res = exequery(TD::conn(), $select);
// while ($item = mysql_fetch_assoc($res)) {
//     $result[] = $item;
// }
// foreach ($codeArr as $arr) {
//     $url - "https://xueqiu.com/S/SZ000001";
//     // $url = "https://xueqiu.com/service/screener/screen?category=CN&exchange=sh_sz&order_by=symbol&order=desc&page=1&size=30&only_count=0";
//     // $price = $arr['price'];
//     // $rise = $arr['rise'];
//     // $url = $url . "&amp;current" . "=$price" . "_" . "$price&pct=$rise" . "_" . "$rise&_=$time";
//     // $url = str_replace('||','current',$url);  amp;
//     // var_export($url . '</br>');
//     // exit;
//     $lines_array = file_get_contents($url);
//     var_export($lines_array);exit;
//     $lines_string = json_decode($lines_array[0], true);
//     $data = $lines_string['data'];
//     $count = $data['count'];
//     $list = $data['list'][0];
//     // array ( 
//     //     'pct' => 3.25,  // rise
//     //     'symbol' => 'SH603587',  // code
//     //     'current' => 18.09,  // price
//     //     'name' => '鍦扮礌鏃跺皻',   // name
//     //     'exchange' => 'sh_sz',  
//     //     'type' => 11, 
//     //     'tick_size' => 0.01, 
//     //     'has_follow' => false, 
//     // );
//     if ($count == 1) {
//         $name = iconv('utf-8', 'gbk', var_export($list['name'], true));
//         $updateSql = "update stock set name = $name, code ='$list[symbol]' where id = '$arr[id]' ";
//         exequery(TD::conn(), $updateSql);
//     }
// }


// var_export($data[0]);exit;

// $sql = " select code from my_fund_select limit 2000,500 ";
// $res = exequery(TD::conn(), $sql);
// while ($item = mysql_fetch_assoc($res)) {
//     // $result[] = $item;
//     $code = trim($item['code']);
//     $url = "https://danjuanapp.com/djapi/fund/derived/" . $code;
//     $lines_array = file($url);
//     $lines_string = json_decode($lines_array[0], true);
//     $data = $lines_string['data'];
//     $updateSql = " update my_fund_select set last_day = '$data[nav_grtd]', " .
//         " week = '$data[nav_grl1w]', month = '$data[nav_grl1m]', three_months = '$data[nav_grl3m]', " .
//         " half_year = '$data[nav_grl6m]', this_year = '$data[nav_grlty]', one_year = '$data[nav_grl1y]', " .
//         " two_years = '$data[nav_grl2y]', three_years = '$data[nav_grl3y]' " .
//         " where code = '" . $item['code'] . "' ";
//     // var_export($updateSql);exit;
//     // exequery(TD::conn(), $updateSql);
// }

var_export('success');
exit;

// $url = "https://danjuanapp.com/djapi/fund/derived/004997";
// $lines_array = file($url);
// // $lines_string = iconv('utf-8', 'gbk', var_export($lines_string, true) . ';');
// $lines_string = json_decode($lines_array[0], true);
// $data = $lines_string['data'];

// var_export($lines_string);exit;
// $data = array(
//     'fd_code' => '004997',
//     'end_date' => '2020-11-24',
//     'unit_nav' => '2.5954',
//     'unit_acc_nav' => '2.5954',
//     'nav_grtd' => '-0.0077',  // last_day
//     'nav_grl1w' => '2.4635',  // week
//     'nav_grl1m' => '16.9204',  // month
//     'nav_grl3m' => '20.75',  // three_months
//     'nav_grl6m' => '88.6329', // half_year
//     'nav_grlty' => '129.5392', // this_year
//     'nav_grl1y' => '155.5282',  // one_year
//     'nav_grl2y' => '266.2715', // two_years
//     'nav_grl3y' => '142.7422', // three_years
//     'nav_grbase' => '159.514', // begin_end
//     'srank_l1m' => '18/925',
//     'srank_l3m' => '22/907',
//     'srank_l6m' => '1/859',
//     'srank_lty' => '1/934',
//     'srank_l1y' => '1/757',
//     'srank_l3y' => '14/490',
//     'srank_base' => '92/933',
//     'aip_grl1y' => '72.2700',
//     'aip_grl2y' => '139.1000',
//     'aip_grl3y' => '155.8500',
//     'itg_aip_grl1y' => '114.1200',
//     'itg_aip_grl2y' => '195.3200',
//     'itg_aip_grl3y' => '204.7000',
//     'yield_history' => array(
//         0 => array('yield' => '20.75', 'name' => '近3个月'),
//         1 => array('yield' => '88.63', 'name' => '近6个月'),
//         2 => array('yield' => '155.53', 'name' => '近一年'),
//         3 => array('yield' => '266.27', 'name' => '近两年'),
//         4 => array('yield' => '142.74', 'name' => '近三年'),
//         5 => array('name' => '近五年'),
//         6 => array('yield' => '159.51', 'name' => '成立以来')
//     )
// );
