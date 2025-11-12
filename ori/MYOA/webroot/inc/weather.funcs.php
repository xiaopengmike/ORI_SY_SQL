<?php
function add_request_params( $a = array( ) )
{
		if ( !is_array( $a ) && empty( $a ) )
		{
				return;
		}
		$param_strs = "";
		while ( list( $key, $value ) = each( &$a ) )
		{
				$param_strs .= $key."=".$value."&";
		}
		$param_strs = substr( $param_strs, 0, -1 );
		return $param_strs;
}

function get_from_official( $CITY_ID )
{
		$SYS_VERSION = TD::get_cache( "SYS_VERSION" );
		$time = time( );
		$PARAMS = array(
				"type" => "forecast3d",
				"date" => $time,
				"sys_version" => $SYS_VERSION['VER'],
				"token" => urlencode( td_authcode( $time, "ENCODE", "tdweatherservices" ) ),
				"api_ver" => "2.0",
				"city_name" => urlencode( td_iconv( $CITY_ID, MYOA_CHARSET, "utf-8" ) ),
				"stype" => "oa"
		);
		$REQUEST_STR = add_request_params( $PARAMS );
		if ( $REQUEST_STR != "" )
		{
				$WEATHER_URL = WEATHER_API_URL."?".$REQUEST_STR;
		}
		$HTML = @file_get_contents( $WEATHER_URL );
		if ( $HTML === FALSE )
		{
				return _( "下载天气数据失败(01)" );
		}
		$ARR_HTML = json_decode( $HTML, TRUE );
		if ( !is_array( $ARR_HTML ) )
		{
				return _( "解析天气数据失败(02)" );
		}
		return $ARR_HTML;
}

function get_from_tongda( $CITY_ID )
{
		$SYS_VERSION = TD::get_cache( "SYS_VERSION" );
		$HTML = @file_get_contents( $WEATHER_URL );
		if ( $HTML === FALSE )
		{
				return _( "下载天气数据失败" );
		}
		$WEATHER_ARRAY = @unserialize( $HTML );
		if ( $WEATHER_ARRAY === FALSE )
		{
				return sprintf( _( "解析天气数据失败(%s)" ), $HTML );
		}
		if ( count( $WEATHER_ARRAY ) == 0 )
		{
				return _( "该城市天气信息为空" );
		}
		return $WEATHER_ARRAY;
}

function temp_fix( $num )
{
		if ( !$num )
		{
				return;
		}
		if ( substr( $num, 0, 1 ) == "0" )
		{
				return substr( $num, 1 );
		}
		return $num;
}

function cache_weather( $WEATHER_CITY )
{
		include( "inc/weather.inc.php" );
		if ( strpos( $WEATHER_CITY, "_" ) === FALSE )
		{
				return _( "无该城市的天气数据" );
		}
		$WEATHER_ARRAY = get_from_official( $WEATHER_CITY );
		if ( !is_array( $WEATHER_ARRAY ) || !is_array( $WEATHER_ARRAY ) )
		{
				$WEATHER_ARRAY = get_from_tongda( $WEATHER_CITY );
		}
		if ( !is_array( $WEATHER_ARRAY ) )
		{
				return $WEATHER_ARRAY;
		}
		while ( list( $KEY, $VALUE ) = each( &$WEATHER_ARRAY ) )
		{
				$WEATHER_ARRAY[$KEY] = td_iconv( $VALUE, "utf-8", MYOA_CHARSET );
		}
		$flag = substr( $WEATHER_ARRAY['time'], 0, 8 ) < date( "Ymd", time( ) );
		if ( $flag )
		{
				$TEMPERATURE = $WEATHER_ARRAY['temp2'];
				$WEATHER = $WEATHER_ARRAY['weather2'];
				$WIND = $WEATHER_ARRAY['wind2_d'];
				$IMG1 = $WEATHER_ARRAY['weather2_d'];
				$IMG2 = $WEATHER_ARRAY['weather2_n'];
		}
		else
		{
				$TEMPERATURE = $WEATHER_ARRAY['temp1'];
				$WEATHER = $WEATHER_ARRAY['weather1'];
				$WIND = $WEATHER_ARRAY['wind1_d'];
				$IMG1 = $WEATHER_ARRAY['weather1_d'];
				$IMG2 = $WEATHER_ARRAY['weather1_n'];
		}
		if ( $WEATHER == "" || $IMG1 == "" || $TEMPERATURE == "" )
		{
				return _( "天气数据不完整" );
		}
		$WEATHER_HTML = "<span class=\"city\" title=\""._( "点击更改城市" )."\" onclick=\"\$('area_select').style.display='block';\$('weather').style.display='none';\">".$WEATHER_ARRAY['city']."</span> ";
		if ( $IMG1 != "" && $IMG1 != "99" )
		{
				$WEATHER_HTML .= "<img style=\"width:20px;height:20px;\" src=\"/static/images/weather/png/day/".$IMG1.".png\" align=\"absMiddle\"/> ";
		}
		if ( $IMG2 != "" && $IMG2 != "99" )
		{
				$WEATHER_HTML .= "<img style=\"width:20px;height:20px;\" src=\"/static/images/weather/png/night/".$IMG2.".png\" align=\"absMiddle\"/> ";
		}
		$WEATHER_HTML .= " <span class=\"weather\">".$WEATHER."</span><span class=\"temperature\" title=\"".$WIND."\">".$TEMPERATURE."</span>";
		$WEATHER_HTML3 = "<b>".$WEATHER_ARRAY['city']."</b><br>";
		if ( $IMG1 != "" && $IMG1 != "99" )
		{
				$WEATHER_HTML3 .= "<img style=\"width:60px;height:60px;\" src=\"/static/images/weather/png/day/".$IMG1.".png\">";
		}
		if ( $IMG2 != "" && $IMG2 != "99" )
		{
				$WEATHER_HTML3 .= "<img style=\"width:60px;height:60px;\" src=\"/static/images/weather/png/night/".$IMG2.".png\">";
		}
		$WEATHER_HTML3 .= "<br>".$WEATHER."<br>{$TEMPERATURE}<br>{$WIND}<br>";
		$WEATHER_HTML4 = sprintf( "<?xml version=\"1.0\" encoding=\"%s\"?><city name=\"%s\">", MYOA_CHARSET, $WEATHER_ARRAY['city'] );
		$WEATHER_ISPIRIT = array( );
		$WEATHER_HTML2 = "<div class=\"fut_weather\" id=\"fut_weather\">";
		$START = $flag ? 2 : 1;
		$COUNT = 0;
		$I = $START;
		for ( ;	$I <= 5;	++$I	)
		{
				if ( !( $COUNT == 4 ) )
				{
						$TIME = time( ) + 86400 * $COUNT;
						$IMG1 = $WEATHER_ARRAY["weather".$I."_d"];
						$IMG2 = $WEATHER_ARRAY["weather".$I."_n"];
						$WEATHER = $WEATHER_ARRAY["weather".$I];
						$TEMPERATURE = $WEATHER_ARRAY["temp".$I];
						$WIND = $WEATHER_ARRAY["wind".$I."_p"];
						$WEATHER_HTML2 .= "<div class=\"fut_weatherbox7\">";
						$WEATHER_HTML2 .= "   <h3>".date( _( "Y年m月d日" ), $TIME )."</h3>";
						$WEATHER_HTML2 .= "   <p>";
						if ( $IMG1 != "" && $IMG1 != "99" )
						{
								$WEATHER_HTML2 .= "<img style=\"width:60px;height:60px;\" src=\"/static/images/weather/png/day/".$IMG1.".png\" />&nbsp;&nbsp;";
						}
						if ( $IMG2 != "" && $IMG2 != "99" )
						{
								$WEATHER_HTML2 .= "<img style=\"width:60px;height:60px;\" src=\"/static/images/weather/png/night/".$IMG2.".png\" />";
						}
						$WEATHER_HTML2 .= "</p>";
						$WEATHER_HTML2 .= "   <div class=\"temp00_dn\">".$WEATHER."</div>";
						$WEATHER_HTML2 .= "   <div class=\"temp01_dn\">".$TEMPERATURE."</div>";
						$WEATHER_HTML2 .= "   <div class=\"temp03_dn\">".$WIND."</div>";
						$WEATHER_HTML2 .= "</div>";
						$WEATHER_HTML4 .= sprintf( "<weather date=\"%s\" img1=\"%s\" img2=\"%s\" weather=\"%s\" temperature=\"%s\" wind=\"%s\" />", date( _( "n月j日" ), $TIME ), $IMG1, $IMG2, htmlspecialchars( $WEATHER ), htmlspecialchars( $TEMPERATURE ), htmlspecialchars( $WIND ) );
						$WEATHER_ISPIRIT[] = array(
								"date" => iconv( MYOA_CHARSET, "utf-8", date( _( "n月j日" ), $TIME ) ),
								"img1" => temp_fix( $IMG1 ),
								"img2" => temp_fix( $IMG2 ),
								"weather" => iconv( MYOA_CHARSET, "utf-8", $WEATHER ),
								"temperature" => iconv( MYOA_CHARSET, "utf-8", $TEMPERATURE ),
								"wind" => iconv( MYOA_CHARSET, "utf-8", $WIND )
						);
						$_WEATHER_MOBILE = array( );
						if ( $COUNT == 0 )
						{
								$_WEATHER_MOBILE['date'] = iconv( MYOA_CHARSET, "utf-8", date( _( "n月j日" ), $TIME ) );
						}
						else if ( $COUNT == 1 )
						{
								$_WEATHER_MOBILE['date'] = iconv( MYOA_CHARSET, "utf-8", _( "明天" ) );
						}
						else
						{
								$_WEATHER_MOBILE['date'] = iconv( MYOA_CHARSET, "utf-8", get_week_day( $TIME ) );
						}
						$_WEATHER_MOBILE['img'] = $WEATHER_ICON_MOBILE[$IMG1]['s'];
						$_WEATHER_MOBILE['weather'] = iconv( MYOA_CHARSET, "utf-8", $WEATHER );
						$_WEATHER_MOBILE['temperature'] = iconv( MYOA_CHARSET, "utf-8", $TEMPERATURE );
						$_WEATHER_MOBILE['wind'] = iconv( MYOA_CHARSET, "utf-8", $WIND );
						if ( $COUNT == 0 )
						{
								$_WEATHER_MOBILE['bg'] = $WEATHER_ICON_MOBILE[$IMG1]['b'];
						}
						$WEATHER_MOBILE[] = $_WEATHER_MOBILE;
						++$COUNT;
				}
		}
		$WEATHER_HTML2 .= "</div>";
		$WEATHER_HTML4 .= "</city>";
		$WEATHER_CACHE = array(
				"a" => $WEATHER_HTML,
				"b" => $WEATHER_HTML2,
				"c" => $WEATHER_HTML3,
				"d" => $WEATHER_HTML4,
				"e" => json_encode( $WEATHER_ISPIRIT ),
				"f" => $WEATHER_MOBILE
		);
		TD::set_cache( "WEATHER_CACHE_".bin2hex( $WEATHER_CITY ), $WEATHER_CACHE, $WEATHER_ARRAY['cache_expire_time'] );
		return TRUE;
}

function revert_temp( $temp, $replace = TRUE )
{
		$arrTemp = explode( "~", $temp );
		if ( $replace )
		{
				return str_replace( array( "℃", "~" ), array( "°", "/" ), $arrTemp[1]."~".$arrTemp[0] );
		}
		return $arrTemp[0]."~".$arrTemp[1];
}

function get_week_day( $time )
{
		$week = array( "1" => "星期一", "2" => "星期二", "3" => "星期三", "4" => "星期四", "5" => "星期五", "6" => "星期六", "7" => "星期日" );
		return $week[date( "N", $time )];
}

function get_daily_weather_current( $WEATHER_CITY )
{
		$WEATHER_ARRAY = array( );
		$WEATHER_ARRAY = TD::get_cache( "WEATHER_CACHE_".bin2hex( $WEATHER_CITY )."_O" );
		if ( is_array( $WEATHER_ARRAY ) && $WEATHER_ARRAY['temp'] != "" )
		{
				return $WEATHER_ARRAY;
		}
		$SYS_VERSION = TD::get_cache( "SYS_VERSION" );
		$time = time( );
		$PARAMS = array(
				"type" => "observe",
				"date" => $time,
				"sys_version" => $SYS_VERSION['VER'],
				"token" => urlencode( td_authcode( $time, "ENCODE", "tdweatherservices" ) ),
				"api_ver" => "2.0",
				"city_name" => urlencode( td_iconv( $WEATHER_CITY, MYOA_CHARSET, "utf-8" ) ),
				"stype" => "oa"
		);
		$REQUEST_STR = add_request_params( $PARAMS );
		if ( $REQUEST_STR != "" )
		{
				$WEATHER_URL = WEATHER_API_URL."?".$REQUEST_STR;
		}
		$HTML = @file_get_contents( $WEATHER_URL );
		if ( $HTML === FALSE )
		{
				return _( "下载实时天气数据失败(01)" );
		}
		$ARR_HTML = json_decode( $HTML, TRUE );
		if ( !is_array( $ARR_HTML ) )
		{
				return _( "解析实时天气数据失败(02)" );
		}
		$key_array = array( "cache_time", "cache_expire_time", "temp", "sd", "ws", "wd", "time", "day" );
		while ( list( $key, $value ) = each( &$ARR_HTML ) )
		{
				if ( !in_array( $key, $key_array ) )
				{
				}
				else
				{
						$WEATHER_ARRAY[$key] = iconv( "utf-8", MYOA_CHARSET, $value );
				}
		}
		if ( count( $WEATHER_ARRAY ) == 0 )
		{
				return _( "该城市天气实时信息为空(03)" );
		}
		TD::set_cache( "WEATHER_CACHE_".bin2hex( $WEATHER_CITY )."_O", $WEATHER_ARRAY, $WEATHER_ARRAY['cache_expire_time'] );
		return $WEATHER_ARRAY;
}

define( WEATHER_API_URL, "http://115.29.13.92/weather/api.php" );
include_once( "inc/utility_all.php" );
?>
