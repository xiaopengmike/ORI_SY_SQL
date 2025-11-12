<?php
function login_check( $USERNAME, $PASSWORD, $KEY_DIGEST = "", $KEY_SN = "", $KEY_USER = "", $CLIENT = 0 )
{
	$USERNAME = strip_tags( $USERNAME );
	if ( $USERNAME != $PASSWORD || $PASSWORD != "[TDCORE_REGCHECK]" && $PASSWORD != "[TDCORE_ADDUSER]" && $PASSWORD != "[TDCORE_REGREG]" && $PASSWORD != "[TDCORE_OPTIONAL]" && $PASSWORD != "[TDCORE_VIEWUSER]" && $PASSWORD != "[TDCORE_REGCHECK_AUTO]" )	
	{
		if ( $USERNAME == "" && ( $KEY_USER == "" || $KEY_USER == "-1" ) )
		{
			return _( "用户名不能为空!" );
		}
		if ( strstr( $USERNAME, "'" ) || strstr( $KEY_USER, "'" ) || !preg_match( "/^[0-9a-f\\-]*\$/i", $KEY_SN ) && !preg_match( "/^[0-9a-f\\-]*\$/i", $KEY_DIGEST ) )
		{
			return _( "用户名等信息包含非法字符" );
		}
		session_start( );
		ob_start( );
		$KEY_RANDOMDATA = $_SESSION['KEY_RANDOMDATA'];
		unset( $_SESSION['KEY_RANDOMDATA'] );
		session_regenerate_id( TRUE );
		if ( $USERNAME != "admin" && !check_time_range( MYOA_LOGIN_TIME_RANGE ) )
		{
			return _( "当前时间禁止登录" );
		}
		$USER_IP = get_client_ip( );
		$PARA_ARRAY = get_sys_para( "SEC_PASS_FLAG,SEC_PASS_TIME,SEC_RETRY_BAN,SEC_RETRY_TIMES,SEC_BAN_TIME,SEC_USER_MEM,SEC_KEY_USER,LOGIN_KEY,SEC_ON_STATUS,SEC_INIT_PASS,LOGIN_SECURE_KEY,LOGIN_USE_DOMAIN,DOMAIN_SYNC_CONFIG,ONE_USER_MUL_LOGIN,IS_CPDA_BYIP,USE_DISCUZ,OA_URL,DEFAULT_ATTACH_PATH,MOBILE_PC_OPTION" );
		while ( list( $PARA_NAME, $PARA_VALUE ) = each( &$PARA_ARRAY ) )
		{
			$$PARA_NAME = $PARA_VALUE;
		}
		if ( $SEC_RETRY_BAN == "1" )
		{
			$query = ( "SELECT count(*) from SYS_LOG where (TYPE='2' or TYPE='9' or TYPE='10') and USER_ID='".$USERNAME."' and IP='{$USER_IP}' and UNIX_TIMESTAMP()-UNIX_TIMESTAMP(TIME)<'".$SEC_BAN_TIME * 60 )."'";
			$cursor = exequery( TD::conn( ), $query );
			if ( $ROW = mysql_fetch_array( $cursor ) )
			{
				$LOGIN_RETRY_COUNT = $ROW[0];
			}
			if ( $SEC_RETRY_TIMES <= $LOGIN_RETRY_COUNT )
			{
				return sprintf( _( "用户名或密码错误超过 %s 次，请等待 %s 分钟后重试!" ), $SEC_RETRY_TIMES, $SEC_BAN_TIME );
			}
		}
		if ( $LOGIN_KEY )
		{
			if ( $SEC_KEY_USER == "0" && $USERNAME == "" )
			{
				$USERNAME = $KEY_USER;
			}
			if ( $USERNAME == "" )
			{
				return _( "用户名不能为空!" );
			}
			if ( $KEY_USER == "" || $KEY_USER == "-1" )
			{
				$query = "SELECT * from USER where USER_ID='".$USERNAME."' or BYNAME='{$USERNAME}'";
				$cursor = exequery( TD::conn( ), $query );
				if ( !( $ROW = mysql_fetch_array( $cursor ) ) )
				{
					add_log( 10, "USERNAME=".$USERNAME, $USERNAME );
					return sprintf( _( "输入的用户名[%s]不存在" ), $USERNAME );
				}
			}
			else
			{
				$query = "SELECT * from USER where USER_ID='".$KEY_USER."' and (USER_ID='{$USERNAME}' or BYNAME='{$USERNAME}')";
				$cursor = exequery( TD::conn( ), $query );
				if ( !( $ROW = mysql_fetch_array( $cursor ) ) )
				{
					add_log( 10, "USERNAME=".$USERNAME, $USERNAME );
					return sprintf( _( "输入的用户名[%s]和USB Key绑定的用户名[%s]不一致" ), $USERNAME, $KEY_USER );
				}
			}
		}
		else
		{
			if ( $USERNAME == "" )
			{
				return _( "用户名不能为空!" );
			}
			$query = "SELECT * from USER where USER_ID='".$USERNAME."' or BYNAME='{$USERNAME}'";
			$cursor = exequery( TD::conn( ), $query );
			if ( !( $ROW = mysql_fetch_array( $cursor ) ) )
			{
				add_log( 10, "USERNAME=".$USERNAME, $USERNAME );
				return sprintf( _( "输入的用户名[%s]不存在" ), $USERNAME );
			}
		}
		$UID = $ROW['UID'];
		$USER_ID = $ROW['USER_ID'];
		$BYNAME = $ROW['BYNAME'];
		$USER_NAME = $ROW['USER_NAME'];
		$BIND_IP = $ROW['BIND_IP'];
		$USEING_KEY = $ROW['USEING_KEY'];
		$SECURE_KEY_SN = $ROW['SECURE_KEY_SN'];
		$ON_STATUS = $ROW['ON_STATUS'];
		$PWD = $ROW['PASSWORD'];
		$KEY_PASSWORD = md5( $PWD );
		$NOT_LOGIN = $ROW['NOT_LOGIN'];
		if ( $NOT_LOGIN )
		{
			return sprintf( _( "用户 %s 被设定为禁止登录！" ), $USERNAME );
		}
		if ( $LOGIN_SECURE_KEY == "1" && $SECURE_KEY_SN != "" )
		{
			$SECURE_PASS = substr( $PASSWORD, -6 );
			$PASSWORD = substr( $PASSWORD, 0, -6 );
		}
		$USER_GUID = "";
		if ( $LOGIN_USE_DOMAIN == "1" )
		{
			$query = "select * from USER_MAP where USER_ID='".$USER_ID."'";
			$cursor1 = exequery( TD::conn( ), $query );
			if ( $ROW1 = mysql_fetch_array( $cursor1 ) )
			{
				$USER_GUID = $ROW1['USER_GUID'];
			}
		}
		if ( $USER_GUID == "" )
		{
			
				if ( crypt( $PASSWORD, $PWD ) != $PWD )
				{
					$ERROR_PWD = maskstr( $PASSWORD, 2, 1 );
					add_log( 2, $ERROR_PWD, $USER_ID );
					return _( "密码错误，注意大小写!" );
				}
		}else{
				if ( $PASSWORD == "" )
				{
					return _( "绑定的域用户密码不能为空" );
				}
				include_once( "inc/utility_user.php" );
				include_once( "inc/ldap/adLDAP.php" );
				$result = FALSE;
				try
				{
					$SYNC_CONFIG = unserialize( $DOMAIN_SYNC_CONFIG );
					$option = get_ldap_option( $SYNC_CONFIG );
					
					$adldap = new adLDAP( $option );
					if ( !$adldap )
					{
						return _( "初始化域验证失败" );
					}
					if ( !$adldap->authenticate( $SYNC_CONFIG['AD_USER'], $SYNC_CONFIG['AD_PWD'] ) )
					{
						return sprintf( _( "域相关参数设置有误(%s)" ), $adldap->get_last_error( ) );
					}
					$user_info = $adldap->user_info( $USER_GUID, array( "samaccountname" ), TRUE );
					if ( $user_info === FALSE )
					{
						return sprintf( _( "获取用户[%s]的域用户名出错(%s)" ), $USER_ID, $adldap->get_last_error( ) );
					}
					$user_info = $user_info[0];
					if ( !is_array( $user_info ) && !is_array( $user_info['samaccountname'] ) && $user_info['samaccountname'][0] == "" )
					{
						return sprintf( _( "查询不到用户[%s]对应的域用户" ), $USER_ID );
					}
					$DOMAIN_USER = $user_info['samaccountname'][0];
					
					$adldap = new adLDAP( $option );
					$result = $adldap->authenticate( $DOMAIN_USER, iconv( MYOA_CHARSET, "utf-8", $PASSWORD ) );
					if ( !$result )
					{
						return sprintf( _( "用户[%s]域验证失败(%s)" ), $USER_ID, $adldap->get_last_error( ) );
					}
				}
				catch ( adLDAPException $e )
				{
					return var_export( $e, TRUE );
				}
			}

		if ( $LOGIN_KEY && $USEING_KEY && substr( $_SERVER['SCRIPT_NAME'], 0, 5 ) != "/pda/" && substr( $_SERVER['SCRIPT_NAME'], 0, 8 ) != "/mobile/" )
		{
			$USERKEY_SN = $ROW['KEY_SN'];
			include_once( "inc/key_check.php" );
			include_once( "inc/utility_var.php" );
			if ( strtoupper( substr( $KEY_SN, 0, 8 ) ) != $KEY_TD_SIGN || $KEY_SN != $USERKEY_SN || !digestcomp( $KEY_DIGEST, $KEY_RANDOMDATA, $KEY_PASSWORD ) )
			{
				add_log( 21, _( "用户USB Key验证失败" ), $USER_ID );
				if ( strtoupper( substr( $KEY_SN, 0, 8 ) ) != $KEY_TD_SIGN )
				{
					return sprintf( _( "用户USB Key[%s]验证失败，请插入合法的USB Key!" ), strtoupper( $KEY_SN ) );
				}
				if ( $KEY_SN == "" || $KEY_DIGEST == "" )
				{
					return _( "用户USB Key验证失败，没有插入USB Key或登录界面模板不正确!" );
				}
				if ( $KEY_SN != $USERKEY_SN )
				{
					return sprintf( _( "用户USB Key验证失败，用户[%s]和USB Key没有绑定!" ), $USER_ID );
				}
				return _( "用户USB Key验证失败，数据校验错误!" );
			}
		}
		if ( $LOGIN_SECURE_KEY == "1" && $SECURE_KEY_SN != "" )
		{
			if ( !preg_match( "/^[0-9]{6}\$/", $SECURE_PASS ) )
			{
				add_log( 21, _( "动态密码长度或格式错误" ), $USER_ID );
				return _( "动态密码应为6位的数字" );
			}
			$SECURE_KEY_INFO = "";
			$query = "select * from SECURE_KEY where KEY_SN='".$SECURE_KEY_SN."'";
			$cursor1 = exequery( TD::conn( ), $query );
			if ( $ROW1 = mysql_fetch_array( $cursor1 ) )
			{
				$SECURE_KEY_INFO = $ROW1['KEY_INFO'];
			}
			if ( $SECURE_KEY_INFO == "" )
			{
				add_log( 21, _( "动态密码信息为空" ), $USER_ID );
				return _( "动态密码卡绑定错误，请联系系统管理员。" );
			}
			include_once( "inc/seamoonapi.php" );
			$COM_OBJ = new seamoonapi( );
			if ( !is_object( $COM_OBJ ) )
			{
				add_log( 21, _( "创建验证动态密码对象失败" ), $USER_ID );
				return _( "创建验证动态密码对象失败，请联系系统管理员。" );
			}
			$NEW_SECURE_KEY_INFO = $COM_OBJ->checkpassword( $SECURE_KEY_INFO, $SECURE_PASS );
			if ( $NEW_SECURE_KEY_INFO == "0" )
			{
				add_log( 21, _( "输入的动态密码错误" ), $USER_ID );
				return _( "您输入的动态密码错误" );
			}
			if ( $NEW_SECURE_KEY_INFO == "-2" )
			{
				add_log( 21, _( "绑定的动态密码信息错误" ), $USER_ID );
				return _( "绑定的动态密码信息错误，请联系系统管理员。" );
			}
			$query = "update SECURE_KEY set KEY_INFO='".$NEW_SECURE_KEY_INFO."' where KEY_SN='{$SECURE_KEY_SN}'";
			exequery( TD::conn( ), $query );
		}
		if ( ( $CLIENT == 0 || $CLIENT == 2 ) && $USER_ID != "admin" && !check_ip( $USER_IP, "0", $USER_ID ) )
		{
			add_log( 9, "USERNAME=".$USERNAME, $USERNAME );
			return sprintf( _( "您无权限从该IP(%s)登录!" ), $USER_IP );
		}
		if ( ( $CLIENT == 0 || $CLIENT == 2 ) && $USER_ID != "admin" && !check_ip( $USER_IP, "2", $USER_ID ) )
		{
			add_log( 9, "USERNAME=".$USERNAME, $USERNAME );
			return sprintf( _( "您无权限从该IP(%s)登录!" ), $USER_IP );
		}
		if ( $CLIENT != 0 && $CLIENT != 2 && $USER_ID != "admin" && !check_ip( $USER_IP, "0", $USER_ID ) || $IS_CPDA_BYIP == "1" )
		{
			add_log( 9, "USERNAME=".$USERNAME, $USERNAME );
			return sprintf( _( "您无权限从该IP(%s)登录!" ), $USER_IP );
		}
		if ( $CLIENT != 0 && $CLIENT != 2 && $USER_ID != "admin" && !check_ip( $USER_IP, "2", $USER_ID ) || $IS_CPDA_BYIP == "1" )
		{
			add_log( 9, "USERNAME=".$USERNAME, $USERNAME );
			return sprintf( _( "您无权限从该IP(%s)登录!" ), $USER_IP );
		}
		if ( ( $CLIENT == 0 || $CLIENT != 2 ) && $BIND_IP != "" || $CLIENT == 0 && $CLIENT == 2 && $IS_CPDA_BYIP != "1" && $BIND_IP == "" )
		{
			$NET_MATCH = FALSE;
			$IP_ARRAY = explode( ",", $BIND_IP );
			foreach ( $IP_ARRAY as $NETWORK )
			{
				if ( !netmatch( $NETWORK, $USER_IP ) )
				{
					continue;
				}
				$NET_MATCH = TRUE;
				break;
			}
			if ( !$NET_MATCH )
			{
				return sprintf( _( "用户 %s 不允许从该IP(%s)登录！" ), $USERNAME, $USER_IP );
			}
		}
		$LAST_VISIT_IP = $ROW['LAST_VISIT_IP'];
		if ( !isset( $ONE_USER_MUL_LOGIN ) )
		{
			$ONE_USER_MUL_LOGIN = 1;
		}
		if ( $ONE_USER_MUL_LOGIN == 0 )
		{
			$CLIENT_ARR = array(
				0 => _( "WEB端" ),
				5 => _( "苹果端" ),
				6 => _( "安卓端" )
			);
			$query = "select * from USER_ONLINE where UID='".$UID."'";
			$cursor = exequery( TD::conn( ), $query );
			if ( $ROW1 = mysql_fetch_array( $cursor ) )
			{
				$LOGIN_CLIENT = $ROW1['CLIENT'];
				$TIME = $ROW1['TIME'];
				$SID = $ROW1['SID'];
				if ( time( ) < $TIME + MYOA_ONLINE_REF_SEC + 5 && dechex( crc32( $SID ) ) != $_COOKIE["SID_".$UID] )
				{
					if ( $MOBILE_PC_OPTION == 1 )
					{
						$query = "select * from USER_ONLINE where CLIENT = '".$CLIENT."' AND UID='".$UID."'";
						$cursor = exequery( TD::conn( ), $query );
						if ( $ROW1 = mysql_fetch_array( $cursor ) )
						{
							return sprintf( _( "同一帐号已在%s登录,登录IP为%s" ), $CLIENT_ARR[$CLIENT], $LAST_VISIT_IP );
						}
					}
					if ( $MOBILE_PC_OPTION == 0 )
					{
						return sprintf( _( "同一帐号已在%s登录,登录IP为%s" ), $CLIENT_ARR[$ROW1['CLIENT']], $LAST_VISIT_IP );
					}
				}
			}
		}
		$LOGIN_USER_PRIV = $ROW['USER_PRIV'];
		$USER_PRIV_OTHER = $ROW['USER_PRIV_OTHER'];
		$LOGIN_AVATAR = $ROW['AVATAR'];
		$LOGIN_DEPT_ID = $ROW['DEPT_ID'];
		$LOGIN_DEPT_ID_OTHER = $ROW['DEPT_ID_OTHER'];
		$LAST_PASS_TIME = $ROW['LAST_PASS_TIME'];
		$LOGIN_THEME = $ROW['THEME'];
		$LOGIN_NOT_VIEW_USER = $ROW['NOT_VIEW_USER'];
		$LAST_VISIT_TIME = $ROW['LAST_VISIT_TIME'];
		$LOGIN_USER_SEX = $ROW['SEX'];
		$USER_EMAIL = $ROW['EMAIL'];
		if ( $LOGIN_THEME == "" )
		{
			$LOGIN_THEME = "1";
		}
		if ( !find_id( $USER_PRIV_OTHER, $LOGIN_USER_PRIV ) )
		{
			$USER_PRIV_OTHER = $LOGIN_USER_PRIV.",".$USER_PRIV_OTHER;
		}
		$LOGIN_FUNC_STR = "";
		$USER_PRIV_OTHER = td_trim( $USER_PRIV_OTHER );
		if ( $USER_PRIV_OTHER != "" )
		{
			$query1 = "SELECT FUNC_ID_STR from USER_PRIV where USER_PRIV in (".$USER_PRIV_OTHER.")";
			$cursor1 = exequery( TD::conn( ), $query1 );
			while ( $ROW = mysql_fetch_array( $cursor1 ) )
			{
				$FUNC_STR = $ROW['FUNC_ID_STR'];
				$MY_ARRAY = explode( ",", $FUNC_STR );
				$ARRAY_COUNT = sizeof( $MY_ARRAY );
				if ( $MY_ARRAY[$ARRAY_COUNT - 1] == "" )
				{
					--$ARRAY_COUNT;
				}
				$I = 0;
				for ( ;	$I < $ARRAY_COUNT;	++$I	)
				{
					if ( !find_id( $LOGIN_FUNC_STR, $MY_ARRAY[$I] ) )
					{
						$LOGIN_FUNC_STR .= $MY_ARRAY[$I].",";
					}
				}
			}
		}
		$SYS_INTERFACE = TD::get_cache( "SYS_INTERFACE" );
		$THEME_SELECT = $SYS_INTERFACE['THEME_SELECT'];
		$THEME = $SYS_INTERFACE['THEME'];
		if ( $THEME_SELECT == "0" )
		{
			$LOGIN_THEME = $THEME;
		}
		include_once( "inc/utility_org.php" );
		$LOGIN_UID = $UID;
		$LOGIN_USER_ID = $USER_ID;
		$LOGIN_BYNAME = $BYNAME;
		$LOGIN_USER_NAME = $USER_NAME;
		$LOGIN_ANOTHER = "0";
		$LOGIN_USER_PRIV_OTHER = $USER_PRIV_OTHER;
		$LOGIN_DEPT_ID_JUNIOR = getunionsetofchilddeptid( $LOGIN_DEPT_ID.",".$LOGIN_DEPT_ID_OTHER );
		$LOGIN_CLIENT = $CLIENT;
		$_SESSION['LOGIN_UID'] = $LOGIN_UID;
		$_SESSION['LOGIN_USER_ID'] = $LOGIN_USER_ID;
		$_SESSION['LOGIN_BYNAME'] = $LOGIN_BYNAME;
		$_SESSION['LOGIN_USER_NAME'] = $LOGIN_USER_NAME;
		$_SESSION['LOGIN_USER_PRIV'] = $LOGIN_USER_PRIV;
		$_SESSION['LOGIN_USER_PRIV_OTHER'] = $LOGIN_USER_PRIV_OTHER;
		$_SESSION['LOGIN_SYS_ADMIN'] = $LOGIN_USER_PRIV == "1" || find_id( $LOGIN_USER_PRIV_OTHER, "1" ) ? 1 : 0;
		$_SESSION['LOGIN_DEPT_ID'] = $LOGIN_DEPT_ID;
		$_SESSION['LOGIN_DEPT_ID_OTHER'] = $LOGIN_DEPT_ID_OTHER;
		$_SESSION['LOGIN_AVATAR'] = $LOGIN_AVATAR;
		$_SESSION['LOGIN_THEME'] = $LOGIN_THEME;
		$_SESSION['LOGIN_FUNC_STR'] = $LOGIN_FUNC_STR;
		$_SESSION['LOGIN_NOT_VIEW_USER'] = $LOGIN_NOT_VIEW_USER;
		$_SESSION['LOGIN_ANOTHER'] = $LOGIN_ANOTHER;
		$_SESSION['LOGIN_DEPT_ID_JUNIOR'] = $LOGIN_DEPT_ID_JUNIOR;
		$_SESSION['LOGIN_CLIENT'] = $LOGIN_CLIENT;
		$_SESSION['LOGIN_USER_SEX'] = $LOGIN_USER_SEX;
		$OA_URL = trim( $OA_URL );
		if ( $OA_URL != "" && ( $LOGIN_USER_PRIV == "1" || find_id( $LOGIN_USER_PRIV_OTHER, "1" ) ) )
		{
			$PARA_ARRAY = get_sys_para( "OA_URL,DEFAULT_ATTACH_PATH", FALSE );
			$OA_URL = trim( $PARA_ARRAY['OA_URL'] );
			$DEFAULT_ATTACH_PATH = $PARA_ARRAY['DEFAULT_ATTACH_PATH'];
		}
		$RESET_PARA_ARRAY = array( );
		if ( $OA_URL == "" )
		{
			$RESET_PARA_ARRAY['OA_URL'] = "http://".$_SERVER['HTTP_HOST']."/";
		}
		if ( stristr( PHP_OS, "winnt" ) && strtolower( realpath( $DEFAULT_ATTACH_PATH ) ) != strtolower( realpath( MYOA_ATTACH_PATH2 ) ) )
		{
			$RESET_PARA_ARRAY['DEFAULT_ATTACH_PATH'] = mysql_escape_string( realpath( MYOA_ATTACH_PATH2 ) );
		}
		if ( 0 < count( $RESET_PARA_ARRAY ) )
		{
			set_sys_para( $RESET_PARA_ARRAY );
		}
		update_my_online_status( 1, $CLIENT );
		clear_online_status( );
		if ( $_POST['LANGUAGE'] != "" )
		{
			setcookie( "LANG_COOKIE", $_POST['LANGUAGE'], time( ) + 86400000, "/" );
		}
		if ( $SEC_USER_MEM == 1 )
		{
			setcookie( "USER_NAME_COOKIE", $USERNAME, time( ) + 86400000 );
		}
		else
		{
			setcookie( "USER_NAME_COOKIE", "", time( ) + 86400000 );
		}
		setcookie( "OA_USER_ID", $LOGIN_USER_ID );
		setcookie( "SID_".$UID, dechex( crc32( session_id( ) ) ), time( ) + 86400000, "/" );
		if ( $SEC_PASS_FLAG == "1" && $SEC_PASS_TIME * 24 * 3600 <= time( ) - strtotime( $LAST_PASS_TIME ) )
		{
			header( "location: /general/pass.php" );
			exit( );
		}
		if ( $SEC_INIT_PASS == "1" && ( $LAST_PASS_TIME == "" || $LAST_PASS_TIME == "0000-00-00 00:00:00" ) )
		{
			header( "location: /general/pass.php" );
			exit( );
		}
		if ( $SEC_ON_STATUS != "1" && $ON_STATUS != "1" )
		{
			$update_str .= ",ON_STATUS='1'";
		}
		$query = "update USER set LAST_VISIT_IP='".$USER_IP."',LAST_VISIT_TIME='".date( "Y-m-d H:i:s" )."'".$update_str.( " where UID='".$LOGIN_UID."'" );
		exequery( TD::conn( ), $query );
		add_log( 1, "", $LOGIN_USER_ID );
		affair_sms( );
		send_birth_card( );
		if ( file_exists( "MYOA_ROOT_PATH/inc/login_ok.php" ) )
		{
			include_once( "inc/login_ok.php" );
		}
		$PARA_ARRAY = get_sys_para( "DUTY_MACHINE" );
		$DUTY_MACHINE = $PARA_ARRAY['DUTY_MACHINE'];
		if ( $DUTY_MACHINE == 2 )
		{
			my_duty( );
		}
		log_on_remind( );
		if ( 0 < $USE_DISCUZ && ( $CLIENT == 0 || $CLIENT == 2 ) )
		{
			include_once( "inc/uc_client/config.inc.php" );
			if ( defined( "UC_APPID" ) )
			{
				include_once( "inc/uc_client/client.php" );
				switch ( $USE_DISCUZ )
				{
				case 1 :
					$UC_USER_NAME = $LOGIN_USER_NAME;
					break;
				case 2 :
					$UC_USER_NAME = $LOGIN_USER_ID;
					break;
				case 3 :
					$UC_USER_NAME = $LOGIN_BYNAME;
					break;
				default :
					$UC_USER_NAME = $LOGIN_USER_NAME;
				}
				if ( $uc_data = uc_get_user( $UC_USER_NAME ) )
				{
					list( $uid, $username, $email ) = $uc_data;
				}
				else
				{
					$EMAIL = $USER_EMAIL != "" ? $USER_EMAIL : "noname@noname.com";
					$uid = uc_user_register( $UC_USER_NAME, $PASSWORD, $EMAIL );
					if ( 0 < $uid )
					{
						$table_pre = substr( UC_DBTABLEPRE, 0, stripos( UC_DBTABLEPRE, "ucenter_" ) );
						$query = "SELECT uid, username, password,email FROM ".$table_pre.( "ucenter_members WHERE uid='".$uid."'" );
						$cursor = exequery( TD::conn( ), $query );
						if ( $member = mysql_fetch_array( $cursor ) )
						{
							$password = $member['password'];
							$email = $member['email'];
							$username = $member['username'];
							$ip = get_client_ip( );
							$time = time( );
						}
						$userdata = array(
							"uid" => $uid,
							"username" => $username,
							"password" => $password,
							"email" => $email,
							"adminid" => 0,
							"groupid" => 10,
							"regdate" => $time,
							"credits" => 0,
							"timeoffset" => 9999
						);
						$insert_sql = "insert into ".$table_pre."common_member";
						$key_str = $val_str = "";
						foreach ( $userdata as $key => $value )
						{
							$key_str .= $key.",";
							$val_str .= "'".$value."',";
						}
						$key_str = rtrim( $key_str, "," );
						$val_str = rtrim( $val_str, "," );
						$insert_sql .= "(".$key_str.") values (".$val_str.")";
						exequery( TD::conn( ), $insert_sql );
						$status_data = array(
							"uid" => $uid,
							"regip" => $ip,
							"lastip" => $ip,
							"lastvisit" => $time,
							"lastactivity" => $time,
							"lastpost" => 0,
							"lastsendmail" => 0
						);
						$insert_sql = "insert into ".$table_pre."common_member_status";
						$key_str = $val_str = "";
						foreach ( $status_data as $key => $value )
						{
							$key_str .= $key.",";
							$val_str .= "'".$value."',";
						}
						$key_str = rtrim( $key_str, "," );
						$val_str = rtrim( $val_str, "," );
						$insert_sql .= "(".$key_str.") values (".$val_str.")";
						exequery( TD::conn( ), $insert_sql );
						$insert_sql = "insert into ".$table_pre.( "common_member_profile (uid) values ('".$uid."')" );
						exequery( TD::conn( ), $insert_sql );
						$insert_sql = "insert into ".$table_pre.( "common_member_field_forum (uid) values ('".$uid."')" );
						exequery( TD::conn( ), $insert_sql );
						$insert_sql = "insert into ".$table_pre.( "common_member_field_home (uid) values ('".$uid."')" );
						exequery( TD::conn( ), $insert_sql );
						$insert_sql = "insert into ".$table_pre.( "common_member_count (uid) values ('".$uid."')" );
						exequery( TD::conn( ), $insert_sql );
						$update_sql = "update ".$table_pre.( "common_setting set svalue='".$username."' where skey='lastmember'" );
						exequery( TD::conn( ), $update_sql );
					}
				}
				global $uc_synclogin_script;
				$uc_synclogin_script = uc_user_synlogin( $uid );
			}
		}
		return "1";
	}
include( "inc/oa_type.php" );
global $VERSION_INFO;
global $VERSION_EA;
global $TD_UNIT_INFO;
global $TD_SN_INFO;
global $TD_CODE_INFO;
global $TD_TRAIL_EXPIRE;
global $TD_USER_LIMIT;
global $TD_IM_USER_LIMIT;
global $TD_ORG_LIMIT;
global $TD_OPTIONAL;
global $TD_CORE_USER_LIMIT;
global $TD_CORE_IM_USER_LIMIT;
global $TD_INTERCONN_LIMIT;
global $TD_EXCHANGE_LIMIT;
$REG_INFO = "";
$RESULT = get_reg_info( $TD_CODE_INFO, $REG_INFO );
if ( $RESULT === TRUE )
{
	$REG_ARRAY = explode( "*", $REG_INFO );
	if ( $REG_INFO == "" || !is_array( $REG_ARRAY ) || count( $REG_ARRAY ) < 7 || $TD_SN_INFO != $REG_ARRAY[1] || $TD_UNIT_INFO != $REG_ARRAY[2] )
	{
		$TD_SN_INFO = "";
		$TD_USER_LIMIT = $TD_CORE_USER_LIMIT;
		$TD_IM_USER_LIMIT = $TD_CORE_IM_USER_LIMIT;
		$TD_OPTIONAL = "";
		$TD_ORG_LIMIT = 5;
		$TD_INTERCONN_LIMIT = 0;
		$TD_EXCHANGE_LIMIT = 0;
	}
	else
	{
		$TD_USER_LIMIT = $REG_ARRAY[3];
		$TD_IM_USER_LIMIT = $REG_ARRAY[4];
		$TD_ORG_LIMIT = intval( $REG_ARRAY[5] );
		$TD_OPTIONAL = $REG_ARRAY[6];
		$TD_INTERCONN_LIMIT = intval( $REG_ARRAY[7] );
		$TD_EXCHANGE_LIMIT = intval( $REG_ARRAY[8] );
	}
}
else
{
	$TD_SN_INFO = "";
	$TD_USER_LIMIT = $TD_CORE_USER_LIMIT;
	$TD_IM_USER_LIMIT = $TD_CORE_IM_USER_LIMIT;
	$TD_OPTIONAL = "";
	$TD_ORG_LIMIT = 5;
	$TD_INTERCONN_LIMIT = 0;
	$TD_EXCHANGE_LIMIT = 0;
}
$REG_FLAG = 0;
if ( preg_match( "/^[0-9]{8}-[0-9]{4}$/", substr( $TD_SN_INFO, 6 ) ) && 6 < strlen( $TD_CODE_INFO ) )
{
	$REG_FLAG = 1;
}
if ( !$REG_FLAG && $PASSWORD != "[TDCORE_REGCHECK]" )
{
	global $TD_CORE_TIME_LIMIT;
	$SETUP_TIME = filectime( $ROOT_PATH."inc/td_core.php" );
	if ( strtotime( $TD_TRAIL_EXPIRE ) < time( ) || time( ) < $SETUP_TIME || strtotime( $TD_TRAIL_EXPIRE ) <= $SETUP_TIME )
	{
		header( "location: /inc/expired.php" );
	}
}
if ( 0 < $TD_USER_LIMIT && $PASSWORD != "[TDCORE_REGCHECK]" && $PASSWORD != "[TDCORE_REGREG]" )
{
	$query = "SELECT count(*) from USER where NOT_LOGIN!='1'";
	$cursor = exequery( $connection, $query );
	if ( $ROW = mysql_fetch_array( $cursor ) )
	{
		$USER_COUNT = $ROW[0];
	}
	if ( $TD_USER_LIMIT < $USER_COUNT )
	{
		header( "location: /inc/expired.php" );
	}
}
if ( $PASSWORD == "[TDCORE_ADDUSER]" && $TD_USER_LIMIT != 0 && $TD_USER_LIMIT <= $USER_COUNT )
{
	message( "提示", sprintf( "已经达到系统的最大授权用户数(%s)，不能再增加允许登录OA用户", $TD_USER_LIMIT ) );
	button_back( );
	exit( );
}
}

function tdrsa_get_public_key_from_file( $FILE, $DAT_KEY, &$KEY )
{
	$DAT = @file_get_contents( $FILE );
	if ( $DAT === FALSE )
	{
		return "读取数据文件错误";
	}
	$DAT = td_authcode( $DAT, "DECODE", $DAT_KEY );
	if ( strlen( $DAT ) < 3000 )
	{
		return "数据文件无效";
	}
	if ( function_exists( "openssl_pkey_get_public" ) )
	{
		return "请联系管理员安装openssl扩展库";
	}
	$KEY = openssl_pkey_get_public( $DAT );
	if ( $KEY === FALSE )
	{
		return "解析数据文件失败";
	}
	return TRUE;
}

function tdrsa_public_encrypt( $DATA, &$ENCRYPTED_DATA, $KEY )
{
	if ( is_resource( $KEY ) )
	{
		return FALSE;
	}
	$KEY_DETAIL = openssl_pkey_get_details( $KEY );
	if ( !is_array( $KEY_DETAIL ) || intval( $KEY_DETAIL['bits'] ) < 64 )
	{
		return FALSE;
	}
	$KEN_LEN = floor( intval( $KEY_DETAIL['bits'] ) / 4 ) - 22;
	$DATA = bin2hex( $DATA );
	$I = 0;
	for ( ;	$I < strlen( $DATA );	$I += $KEN_LEN	)
	{
		$DATA_I = pack( "H*", substr( $DATA, $I, $KEN_LEN ) );
		$RESULT = openssl_public_encrypt( $DATA_I, $ENCRYPTED_DATA_I, $KEY );
		if ( $RESULT )
		{
			return FALSE;
		}
		$ENCRYPTED_DATA .= $ENCRYPTED_DATA_I;
	}
	return TRUE;
}

function tdrsa_public_decrypt( $DATA, &$DECRYPTED_DATA, $KEY )
{
	if ( is_resource( $KEY ) )
	{
		return FALSE;
	}
	$KEY_DETAIL = openssl_pkey_get_details( $KEY );
	if ( !is_array( $KEY_DETAIL ) || intval( $KEY_DETAIL['bits'] ) < 64 )
	{
		return FALSE;
	}
	$KEN_LEN = floor( intval( $KEY_DETAIL['bits'] ) / 4 );
	$DATA = bin2hex( $DATA );
	$I = 0;
	for ( ;	$I < strlen( $DATA );	$I += $KEN_LEN	)
	{
		$DATA_I = pack( "H*", substr( $DATA, $I, $KEN_LEN ) );
		$RESULT = openssl_public_decrypt( $DATA_I, $DECRYPTED_DATA_I, $KEY );
		if ( $RESULT )
		{
			return FALSE;
		}
		$DECRYPTED_DATA .= $DECRYPTED_DATA_I;
	}
	return TRUE;
}

function get_reg_info( $REG_CODE, &$REG_INFO )
{
	global $connection;
	global $VERSION_INFO;
	global $TD_UNIT_INFO;
	global $TD_SN_INFO;
	global $TD_CODE_INFO;
	global $TD_USER_LIMIT;
	global $TD_IM_USER_LIMIT;
	global $TD_ORG_LIMIT;
	global $TD_OPTIONAL;
	global $TD_CORE_USER_LIMIT;
	global $TD_CORE_IM_USER_LIMIT;
	global $TD_INTERCONN_LIMIT;
	global $TD_EXCHANGE_LIMIT;
	global $TD_RP_LIMIT;
	$SN_INFO = "TD20F-26779039-7498";
	$TD_USER_LIMIT = 0;
	$TD_IM_USER_LIMIT = 0;
	$TD_ORG_LIMIT = 999;
	$TD_OPTIONAL = "189abcdefg";
	$TD_INTERCONN_LIMIT = 999;
	$TD_EXCHANGE_LIMIT = 999;
	$TD_RP_LIMIT = 999;
	$REG_ARRAY = array( $VERSION_INFO, $SN_INFO, $TD_UNIT_INFO, $TD_USER_LIMIT, $TD_IM_USER_LIMIT, $TD_ORG_LIMIT, $TD_OPTIONAL, $TD_INTERCONN_LIMIT, $TD_EXCHANGE_LIMIT, $TD_RP_LIMIT);
	$TD_CODE_INFO = implode( "*", $REG_ARRAY );
	if ( $TD_SN_INFO != $SN_INFO )
	{
		$TD_SN_INFO = $SN_INFO;
		/**$query = "update VERSION set SN ='".$TD_SN_INFO."';";
		exequery( $connection, $query );**/
	}
	if ( $REG_CODE != $TD_CODE_INFO )
	{
		$REG_CODE = $TD_CODE_INFO;
		/**$query = "update VERSION set CODE ='".$TD_CODE_INFO."';";
		exequery( $connection, $query );**/
		cache_version( );
	}
	$REG_INFO = $REG_CODE;
	return TRUE;
}

function tdoa_check_reg( )
{
	global $TD_SN_INFO;
	global $TD_CODE_INFO;
	global $TD_UNIT_INFO;
	login_check( "[TDCORE_REGCHECK]", "[TDCORE_REGCHECK]" );
	if ( !preg_match( "/^[0-9]{8}-[0-9]{4}$/", substr( $TD_SN_INFO, 6 ) ) || strlen( $TD_CODE_INFO ) < 30 )
	{
		return FALSE;
	}
	$SN = explode( "-", $TD_SN_INFO );
	$SN_HEAD = $SN[0];
	$SN_CODE = $SN[1];
	$SN_TAIL = $SN[2];
	if ( !stristr( $TD_SN_INFO, "TD20" ) || strlen( $SN_HEAD ) != 5 || strlen( $SN_CODE ) != 8 || strlen( $SN_TAIL ) != 4 || !is_numeric( $SN_CODE ) || !is_numeric( $SN_TAIL ) || ( $SN_CODE - 1 ) % 11 != 0 && ( $SN_CODE - 2 ) % 11 != 0 && ( $SN_CODE - 3 ) % 11 != 0 )
	{
		return FALSE;
	}
	return TRUE;
}

function tdoa_check_sn( )
{
	global $TD_SN_INFO;
	if ( $TD_SN_INFO == "" )
	{
		login_check( "[TDCORE_REGCHECK]", "[TDCORE_REGCHECK]" );
	}
	if ( preg_match( "/^[0-9]{8}-[0-9]{4}$/", substr( $TD_SN_INFO, 6 ) ) )
	{
		return FALSE;
	}
	$SN = explode( "-", $TD_SN_INFO );
	$SN_HEAD = $SN[0];
	$SN_CODE = $SN[1];
	$SN_TAIL = $SN[2];
	if ( !stristr( $TD_SN_INFO, "TD20" ) || strlen( $SN_HEAD ) != 5 || strlen( $SN_CODE ) != 8 || strlen( $SN_TAIL ) != 4 || !is_numeric( $SN_CODE ) || !is_numeric( $SN_TAIL ) || ( $SN_CODE - 1 ) % 11 != 0 && ( $SN_CODE - 2 ) % 11 != 0 && ( $SN_CODE - 3 ) % 11 != 0 )
	{
		return FALSE;
	}
	return TRUE;
}

function tdoa_sn_version( )
{
	global $TD_SN_INFO;
	if ( $TD_SN_INFO == "" )
	{
		login_check( "[TDCORE_REGCHECK]", "[TDCORE_REGCHECK]" );
	}
	return strtoupper( substr( $TD_SN_INFO, 4, 1 ) );
}

function tdoa_check_experience( )
{
	$REG_INFO = itask( array( "GET_REG_INFO" ) );
	if ( !$REG_INFO || !is_array( $REG_INFO ) || count( $REG_INFO ) == 0 )
	{
		return 0;
	}
	$REG_INFO = $REG_INFO[0];
	$REG_INFO = explode( "*", $REG_INFO );
	if ( count( $REG_INFO ) < 6 )
	{
		return 0;
	}
	$SN = $REG_INFO[1];
	$EXPIRED_DATE = $REG_INFO[5];
	if ( $SN != "TD20X-12345677-7890" )
	{
		return 0;
	}
	$EXPIRED_TIME = strtotime( $EXPIRED_DATE );
	if ( $EXPIRED_TIME === FALSE )
	{
		return 999999;
	}
	$DAYS_LEFT = floor( ( $EXPIRED_TIME - strtotime( date( "Y-m-d" ) ) ) / 86400 );
	if ( 0 < $DAYS_LEFT )
	{
		return $DAYS_LEFT;
	}
	return 0;
}

function tdoa_mcode( )
{
	$M_CODE = md5( sprintf( "%.0f", disk_total_space( dirname( __FILE__ ) ) ) );
	$M_CODE = hexdec( substr( $M_CODE, 0, 8 ) ) ^ hexdec( substr( $M_CODE, 8, 8 ) ) ^ hexdec( substr( $M_CODE, 16, 8 ) ) ^ hexdec( substr( $M_CODE, 24, 8 ) );
	return sprintf( "%08X", $M_CODE );
}

function optional_code( $OPT_NAME )
{
	switch ( $OPT_NAME )
	{
		case "SMS" :
			$OPT_NAME = "1";
			return $OPT_NAME;
		case "TDEA" :
			$OPT_NAME = "8";
			return $OPT_NAME;
		case "TDFIS" :
			$OPT_NAME = "9";
			return $OPT_NAME;
		case "REPORT" :
			$OPT_NAME = "a";
			return $OPT_NAME;
		case "TDIM" :
			$OPT_NAME = "b";
			return $OPT_NAME;
		case "TDIMMSG" :
			$OPT_NAME = "c";
			return $OPT_NAME;
		case "GRP_C" :
			$OPT_NAME = "d";
			return $OPT_NAME;
		case "ATTACH_ENC" :
			$OPT_NAME = "e";
			return $OPT_NAME;
		case "SEC_RULE" :
			$OPT_NAME = "f";
			return $OPT_NAME;
		case "MOBILE_SEAL" :
			$OPT_NAME = "g";
		default :
			return $OPT_NAME;
	}
}

function optional_name( $OPT_CODE )
{
	switch ( $OPT_CODE )
	{
		case "1" :
			return "手机短信";
		case "8" :
			return "EA进销存";
		case "9" :
			return "财务管理";
		case "a" :
			return "报表";
		case "b" :
			return "即时通讯";
		case "c" :
			return "通讯监控";
		case "d" :
			return "互联互通组件";
		case "e" :
			return "附件加密组件";
		case "f" :
			return "三员安全管理组件";
		case "g" :
			return "手机签章组件";
		default :
			return "";
	}
}

function tdoa_optional_check( $OPTIONAL, $OPT_NAME )
{
	$OPT_NAME = optional_code( $OPT_NAME );
	if ( stristr( $OPTIONAL, $OPT_NAME ) )
	{
		return 1;
	}
	if ( $OPT_NAME == "OA_OPTION_LIST" )
	{
		$OA_OPTION = "";
		$I = 0;
		for ( ;	$I < strlen( $OPTIONAL );	++$I	)
		{
			$OPTION_NAME = optional_name( substr( $OPTIONAL, $I, 1 ) );
			if ( $OPTION_NAME != "" )
			{
				$OA_OPTION .= $OPTION_NAME."，";
			}
		}
		return trim( $OA_OPTION, "，" );
	}
	return 0;
}

/**三员管理破解**/
function dongle_optional( $OPT_NAME )
{
	$result = itask( array( "GET_REG_INFO" ) );
	$REG_INFO = !$result === FALSE || !is_array( $result ) ? "" : $result[0];
	$REG_ARRAY = explode( "*", $REG_INFO );
	$OPTIONAL = !is_array( $REG_ARRAY ) && count( $REG_ARRAY ) < 6 ? "" : $REG_ARRAY[4];
	return tdoa_optional_check( $OPTIONAL, $OPT_NAME );
}

function tdoa_optional( $OPT_NAME )
{
	global $TD_OPTIONAL;
	if ( tdoa_check_reg( ) )
	{
		return tdoa_optional_check( $TD_OPTIONAL, $OPT_NAME );
	}
	return 0;
}
/**OA用户数限制**/
function tdoa_user_limit( )
{
	global $TD_USER_LIMIT;
	global $TD_IM_USER_LIMIT;
	global $TD_ORG_LIMIT;
	global $TD_CORE_USER_LIMIT;
	global $TD_CORE_IM_USER_LIMIT;
	global $TD_INTERCONN_LIMIT;
	global $TD_EXCHANGE_LIMIT;
	global $TD_RP_LIMIT;
	if ( tdoa_check_reg( ) )
	{
		return $TD_USER_LIMIT." ".$TD_IM_USER_LIMIT." ".$TD_ORG_LIMIT." ".$TD_INTERCONN_LIMIT." ".$TD_EXCHANGE_LIMIT." ".$TD_RP_LIMIT;
	}
	return $TD_CORE_USER_LIMIT." ".$TD_CORE_IM_USER_LIMIT." ".$TD_ORG_LIMIT." ".$TD_INTERCONN_LIMIT." ".$TD_EXCHANGE_LIMIT." ".$TD_RP_LIMIT;
}
/**OA机构数限制**/
function tdoa_options_limit( $OPTION )
{
	global $TD_INTERCONN_LIMIT;
	global $TD_EXCHANGE_LIMIT;
	if ( tdoa_check_reg( ) )
	{
		$TD_INTERCONN_LIMIT = 0;
		$TD_EXCHANGE_LIMIT = 0;
	}
	switch ( $OPTION )
	{
		case 3 :
			return $TD_INTERCONN_LIMIT;
		case 4 :
			return $TD_EXCHANGE_LIMIT;
		default :
			return 0;
	}
}

function tdoa_weather( $WEATHER_CITY, $VIEW = "" )
{
	global $td_cache;
	global $ATTACH_PATH;
	include_once( "inc/cache/cache.php" );
	if ( $VIEW == "" )
	{
		$VIEW = "a";
	}
	$WEATHER_CACHE = TD::get_cache( "WEATHER_CACHE_".$WEATHER_CITY );
	if ( !is_array( $WEATHER_CACHE ) && !isset( $WEATHER_CACHE[$VIEW] ) )
	{
		include_once( "inc/weather.funcs.php" );
		include( "inc/weather.inc.php" );
		$RESULT = cache_weather( $WEATHER_CITY );
		if ( $RESULT !== TRUE )
		{
			return "error:".$RESULT;
		}
		$WEATHER_CACHE = TD::get_cache( "WEATHER_CACHE_".$WEATHER_CITY );
	}
	return $WEATHER_CACHE[$VIEW];
}

/**翻页显示**/
function pages( $P, $PAGE, $USERKEY )
{
	$PRE_I = $PAGE - 1;
	if ( $PAGE != 1 )
	{
		$PBAR_STR .= "[&nbsp;<a href=\"muser.php?PAGE=1&USERKEY=".urlencode( $USERKEY )."\">首页</a>&nbsp;]&nbsp;&nbsp;";
		$PBAR_STR .= "[&nbsp;<a href=\"muser.php?PAGE=".$PRE_I."&USERKEY=".urlencode( $USERKEY )."\">上一页</a>&nbsp;]&nbsp;&nbsp;";
	}
	$SHOW_COUNT = 0;
	$I = 1;
	for ( ;	$I <= $P;++$I	)
	{
		if ( $I == $PAGE )
		{
			++$SHOW_COUNT;
			$PBAR_STR .= "&nbsp;".$I."&nbsp;&nbsp;&nbsp;";
		}
		else if ( $P - 10 < $PAGE && $P - 10 < $I )
		{
			++$SHOW_COUNT;
			if ( 10 < $SHOW_COUNT )
			{
				break;
			}
			else
			{
				$PBAR_STR .= "[&nbsp;<a href=\"muser.php?PAGE=".$I."&USERKEY=".urlencode( $USERKEY )."\">".$I."</a>&nbsp;]&nbsp;&nbsp;";
			}
		}
		else if ( $PAGE - 5 < $I )
		{
			++$SHOW_COUNT;
			if ( 10 < $SHOW_COUNT )
			{
				break;
			}
			$PBAR_STR .= "[&nbsp;<a href=\"muser.php?PAGE=".$I."&USERKEY=".urlencode( $USERKEY )."\">".$I."</a>&nbsp;]&nbsp;&nbsp;";
		}
	}
	$NEXT_I = $PAGE + 1;
	if ( $PAGE < $P )
	{
		$PBAR_STR .= "[&nbsp;<a href=\"muser.php?PAGE=".$NEXT_I."&USERKEY=".urlencode( $USERKEY )."\">下一页</a>&nbsp;]&nbsp;&nbsp;";
		$PBAR_STR .= "[&nbsp;<a href=\"muser.php?PAGE=".$P."&USERKEY=".urlencode( $USERKEY )."\">末页</a>&nbsp;]&nbsp;&nbsp;";
	}
	return $PBAR_STR;
}

function log_on_remind( )
{
	$SMS_CONTENT = "";
	$TO_ID = "";
	$ONLINE_USER_ARRAY = TD::get_cache( "SYS_ONLINE_USER" );
	$query = "select UID, USER_ID from USER_EXT where find_in_set('".$LOGIN_USER_ID."', CONCERN_USER)";
	$cursor = exequery( TD::conn( ), $query );
	while ( $ROW = mysql_fetch_array( $cursor ) )
	{
		$UID = $ROW['UID'];
		$USER_ID = $ROW['USER_ID'];
		if ( array_key_exists( $UID, $ONLINE_USER_ARRAY ) )
		{
			$TO_ID .= $USER_ID.",";
		}
	}
	$SMS_CONTENT = "{$LOGIN_USER_NAME}";
	if ( $LOGIN_DEPT_ID )
	{
		$LOGIN_DEPT_NAME = getdeptnamebyid( $LOGIN_DEPT_ID );
		if ( substr( $LOGIN_DEPT_NAME, strlen( $LOGIN_DEPT_NAME ) - 1, 1 ) == "," )
		{
			$LOGIN_DEPT_NAME = substr( $LOGIN_DEPT_NAME, 0, -1 );
		}
		$SMS_CONTENT .= "(".$LOGIN_DEPT_NAME.")";
	}
	$SMS_CONTENT .= "已经上线！";
	if ( $TO_ID != "" )
	{
		send_sms( "", $LOGIN_USER_ID, $TO_ID, 24, $SMS_CONTENT, "" );
	}
}

function my_duty( )
{
	global $connection;
	global $LOGIN_USER_ID;
	global $LOGIN_UID;
	$CUR_DATE = date( "Y-m-d" );
	$CUR_TIME = date( "Y-m-d H:i:s" );
	$query = "select * from ATTEND_DUTY where USER_ID='".$LOGIN_USER_ID."' and REGISTER_TYPE='1' and to_days(REGISTER_TIME)=to_days('{$CUR_DATE}')";
	$cursor = exequery( TD::conn( ), $query );
	if ( 0 < mysql_num_rows( $cursor ) )
	{
	}
	else
	{
		$USER_IP = get_client_ip( );
		if ( check_ip( $USER_IP, "1", $LOGIN_USER_ID ) )
		{
		}
		else
		{
			$query = "select * from ATTEND_HOLIDAY where BEGIN_DATE <='".$CUR_DATE.( "' and END_DATE>='".$CUR_DATE."'" );
	$cursor = exequery( TD::conn( ), $query );
			if ( 0 < mysql_num_rows( $cursor ) )
			{
			}
			else
			{
				$query = "select * from ATTEND_LEAVE where USER_ID='".$LOGIN_USER_ID.( "' and ALLOW='1' and LEAVE_DATE1<='".$CUR_TIME."' and LEAVE_DATE2>='{$CUR_TIME}'" );
                         	$cursor = exequery( TD::conn( ), $query );
				if ( 0 < mysql_num_rows( $cursor ) )
				{
				}
				else
				{
					$query = "select DUTY_TYPE from USER_EXT where UID='".$LOGIN_UID."'";
                                 	$cursor = exequery( TD::conn( ), $query );
					if ( $ROW = mysql_fetch_array( $cursor ) )
					{
						$DUTY_TYPE = $ROW['DUTY_TYPE'];
					}
					$query = "SELECT * from ATTEND_CONFIG where DUTY_TYPE=".intval( $DUTY_TYPE );
                    	                $cursor = exequery( TD::conn( ), $query );
					if ( $ROW = mysql_fetch_array( $cursor ) )
					{
						$DUTY_TIME = $ROW['DUTY_TIME1'];
						$DUTY_TYPE = $ROW['DUTY_TYPE1'];
					}
					$DUTY_INTERVAL_BEFORE = "DUTY_INTERVAL_BEFORE".$DUTY_TYPE;
					$DUTY_INTERVAL_AFTER = "DUTY_INTERVAL_AFTER".$DUTY_TYPE;
					$PARA_ARRAY = get_sys_para( "{$DUTY_INTERVAL_BEFORE},{$DUTY_INTERVAL_AFTER}" );
					while ( list($PARA_NAME,$PARA_VALUE) = each( &$PARA_ARRAY ) )
					{
						$$PARA_NAME = $PARA_VALUE;
					}
					$REGISTER_TIME = $CUR_DATE." ".$DUTY_TIME;
					if ( $DUTY_INTERVAL_BEFORE1 * 60 < strtotime( $REGISTER_TIME ) - strtotime( $CUR_TIME ) || $DUTY_INTERVAL_AFTER1 * 60 < strtotime( $CUR_TIME ) - strtotime( $REGISTER_TIME ) )
					{
					}
					else
					{
						$query = "insert into ATTEND_DUTY(USER_ID,REGISTER_TYPE,REGISTER_TIME,REGISTER_IP,REMARK) values ('".$LOGIN_USER_ID.( "','1','".$CUR_TIME."','" ).get_client_ip( )."','')";
						exequery( $connection, $query );
					}
				}
			}
		}
	}
}

include_once( "inc/session.php" );
include_once( "inc/utility_all.php" );
include_once( "inc/itask/itask.php" );
define( TD_MYOA_COMPANY_NAME, "通达信科" );
define( TD_MYOA_PRODUCT_NAME, "Office Anywhere 2013 增强版" );
define( TD_MYOA_VERSION_MAJOR, 7 );
define( TD_MYOA_VERSION_BUILD, 23 );
define( TD_MYOA_VERSION_DATE, "140828" );
define( TD_MYOA_VERSION, sprintf( "%s.%s.%s", TD_MYOA_VERSION_MAJOR, TD_MYOA_VERSION_BUILD, TD_MYOA_VERSION_DATE ) );
define( TD_MYOA_WEB_SITE, MYOA_IS_UN == 0 ? "www.tongda2000.com" : "un.tongda2000.com" );
define( TD_MYOA_WEB_SALE, "/general/sms/msg_center/new.php?TO_ID=admin&TO_NAME=系统管理员" );
define( TD_MYOA_WEB_AD, "" );
if ( MYOA_IS_UN )
{
	define( TD_MYOA_WEB_SP_URL, "http://un.tongda2000.com/sp2013adv.php" );
}
else
{
	define( TD_MYOA_WEB_SP_URL, "http://www.tongda2000.com/download/sp2013adv.php" );
}
define( TD_CORE_USER_LIMIT, 100 );
define( TD_CORE_IM_USER_LIMIT, 50 );
if ( $KEY == "yh" )
{
	echo TD_MYOA_COMPANY_NAME.TD_MYOA_PRODUCT_NAME.TD_MYOA_WEB_SITE."中国兵器工业信息中心版权所有";
}
if ( rand( 0, 1000 ) <= 50 && !stristr( $SCRIPT_NAME, "expired.php" ) && !stristr( $SCRIPT_NAME, "reg.php" ) && !stristr( $SCRIPT_NAME, "reg_submit.php" ) )
{
	login_check( "[TDCORE_REGCHECK_AUTO]", "[TDCORE_REGCHECK_AUTO]" );
}
?>
