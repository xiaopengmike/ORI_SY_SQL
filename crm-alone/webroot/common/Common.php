<?php
// include_once("../inc/auth.php");
include_once("../inc/utility_all.php");
/**
 * ����ģ�鹫����������
 * @hr ���������¹�������ģ��
 * @author yisr
 */
// ϵͳĬ�ϳ�ʼ·��MYOA_ROOT_PATH�� D:/MYOA/webroot/

// common�ļ���·��
if (!defined("COMMON_FILE_URL")) {
    if (defined("MYOA_JS_SERVER") && MYOA_JS_SERVER) {
        define("COMMON_FILE_URL", MYOA_JS_SERVER . "/common");
    } else {
        define("COMMON_FILE_URL", "/common");
    }
}

// ��������Ŀ¼·��
// D:\MYOA\webroot\attachment\reportshop\images     ��Ƭλ��
define("IMAGES_URL", MYOA_ROOT_PATH . "attachment/reportshop/images/");

// D:\MYOA\webroot\attachment\reportshop\attachment  ������λ��
define("ATTACH_URL", MYOA_ROOT_PATH . "attachment/reportshop/attachment/");

define("INHE_ATTACH_URL", MYOA_ROOT_PATH . "general/attachment/");

// �б�Ĭ����ʾ��������Ϊ15��
define("PAGE_SIZE", "15");

if (!function_exists('array_column')) {
	function array_column($arr2, $column_key) {
		$data = array();
		foreach ($arr2 as $key => $value) {
			$data[] = $value[$column_key];
		}
		return $data;
	}
}

/**
 * ���������������?
 */
class CommonModel
{
    /**
     * inhe_oa_paras��type����
     * �ѿ�Ͷ��ʹ��
     */
    public static $typeArr = array(
        'account' => '����',
        'company' => '��˾����',
        'contract_period' => '��ͬ��',
        'contract_type' => '��ͬ����',
        'interview_flow' => '���Լ�¼������',
        'kpikh_remind' => 'KPI��������',
        'kpi_remind' => '���������͹���̬�ȿ�������',
        'lzsq' => '��ְ���뵥����',
        'married' => '����״��',
        'nation' => '����',
        'process_status' => '�������״�?,
        'quit_reason' => '��ְԭ��',
        'rsyd' => '�����춯������',
        // δ��������
        'flow_type' => '��������',
        'post_type' => '��λ����',
        'reward_punishment' => '��������',
        'transaction_type' => '�춯����',
        'sex' => '�Ա�',
        'skill_level' => '����ˮƽ',
        'computer_level' => '�����Ӧ��ˮ�?,
        'sort_field' => '�����ֶ�',
        'sort_way' => '����ʽ',
        'quit_type' => '��ְ��ʽ',  // ��ְ    ��ͬ��ֹ    �Զ���ְ	���涨��ǰ��ְ    ��ʱ��ְ
    );

    /**
     * ������ʹ�ã�����ʹ�ã�����
     * ʹ�����?
     * ��δͶ��ʹ��
     */
    public static $typeUnUsed = array(
        // 'native_place' => '����',
        'political_landscape' => '������ò',
        'education_level' => '�Ļ��̶�',
        'account_type' => '��������',
        'application_method' => 'ӦƸ��ʽ',
        'work_status' => '��ҵ״��',
        // 'computer_level' => 'н��',
        // ָ���ʼĵ�ַ

    );

    /**
     * ��������������
     */
    public function getParasList($param)
    {
        $where = " where 1=1 and a.is_used = '1' ";
        if (array_key_exists('type', $param) && $param['type'] != '') {
            $where .= " and a.type = '" . $param['type'] . "' ";
        }
        if (array_key_exists('type_desc', $param) && $param['type_desc'] != '') {
            $where .= " and a.type_desc = '" . $param['type_desc'] . "' ";
        }
        if (array_key_exists('paras_desc', $param) && $param['paras_desc'] != '') {
            $where .= " and a.paras_desc like '%" . $param['paras_desc'] . "%' ";
        }
        if (array_key_exists('paras_value', $param) && $param['paras_value'] != '') {
            $where .= " and a.paras_value = '" . $param['paras_value'] . "' ";
        }

        $sql = "select * from inhe_oa_paras a " . $where;
        $res = exequery(TD::conn(), $sql);
        // ��������ʼ��
        $result = array();
        while ($item = mysql_fetch_assoc($res)) {
            $result[] = $item;
        }

        // �ͷŽ����?
        mysql_free_result($res);

        return $result;
    }

    /**
     * ϵͳ����Ա����
     */
    public function getSystemAdminList($param)
    {
        $where = " where 1=1 ";
        if (array_key_exists('user_id', $param) && $param['user_id'] != '') {
            $where .= " and a.user_id = '" . $param['user_id'] . "' ";
        }
        if (array_key_exists('is_admin', $param) && $param['is_admin'] != '') {
            $where .= " and a.is_admin = '" . $param['is_admin'] . "' ";
        }
        if (array_key_exists('type', $param) && $param['type'] != '') {
            $where .= " and a.type = '" . $param['type'] . "' ";
        }

        $sql = "select * from inhe_system_admin a " . $where;
        $res = exequery(TD::conn(), $sql);
        // ��������ʼ��
        $result = array();
        while ($item = mysql_fetch_assoc($res)) {
            $result[] = $item;
        }

        // �ͷŽ����?
        mysql_free_result($res);

        return $result;
    }

    /**
     * �����乫��ģ��
     * 1. н�ʵ������ݹ���
     * 2. 
     */

    /**
     * ��ȡǰ̨���ݵ�get��post����ֵ
     */
    public function getParamToArray()
    {
        $postParam = eval(" return " . @iconv("utf-8//IGNORE", "gbk", var_export($_POST, true)) . ";");
        $getParam = eval(" return " . @iconv("utf-8//IGNORE", "gbk", var_export($_GET, true)) . ";");
        return array_merge($postParam, $getParam);
    }

    /**
     * ������GBK����������תΪutf-8
     *
     * @param array $arr   ����
     * @param string $in_charset ԭ�ַ�������
     * @param string $out_charset ������ַ�������?
     * @param string $returnType   ����ֵ���ͣ����飻json�ַ���
     * @return array
     */
    public function array_iconv($arr, $in_charset = "gbk", $out_charset = "utf-8", $returnType = 'json')
    {
        $ret = eval('return ' . iconv($in_charset, $out_charset, var_export($arr, true) . ';'));
        // ����ֵ�����ж�
        if ($returnType = "array") {
            return $ret;
        }

        // ����ת��֮��������json,Ĭ�����json�ַ���
        return json_encode($ret);
    }

    /**
     * sms���ŷ���
     * @param $paras ��������
     * @param url      �������ݵĲ鿴�������ӣ���Ӧ������ģ��
     * @param content  ��Ϣ����
     * @param approver �����ˣ�һ��Ϊ�����Ա����ȡuser_id��������,�ŷָ�
     */
    public function sendSms($paras)
    {
        $remindUrl = $paras['url'];
        $smsContent = $paras['content'];
        $approver = $paras['approver'];
        // ������Ϣ����
        send_sms("", $_SESSION["LOGIN_USER_ID"], $approver, 0, $smsContent, $remindUrl);
    }


    /**
     * �������鹹��
     * 
     */
    public function returnData()
    {
        $result = array();
        $result = array(
            'status' => '',
            'code' => '',
            'msg' => '',
        );

        return $result;
    }


    public function setHttpHeaders($statusCode)
    {
        $statusMessage = $this->getHttpStatusMessage($statusCode);
        
    }

    /**
     * HTTP������
     */
    public function getHttpStatusMessage($statusCode)
    {
        $httpStatus = array(
            100 => 'Continue',
            101 => 'Switching Protocols',
            200 => 'OK',
            201 => 'Created',
            202 => 'Accepted',
            203 => 'Non-Authoritative Information',
            204 => 'No Content',
            205 => 'Reset Content',
            206 => 'Partial Content',
            300 => 'Multiple Choices',
            301 => 'Moved Permanently',
            302 => 'Found',
            303 => 'See Other',
            304 => 'Not Modified',
            305 => 'Use Proxy',
            306 => '(Unused)',
            307 => 'Temporary Redirect',
            400 => 'Bad Request',
            401 => 'Unauthorized',
            402 => 'Payment Required',
            403 => 'Forbidden',
            404 => 'Not Found',
            405 => 'Method Not Allowed',
            406 => 'Not Acceptable',
            407 => 'Proxy Authentication Required',
            408 => 'Request Timeout',
            409 => 'Conflict',
            410 => 'Gone',
            411 => 'Length Required',
            412 => 'Precondition Failed',
            413 => 'Request Entity Too Large',
            414 => 'Request-URI Too Long',
            415 => 'Unsupported Media Type',
            416 => 'Requested Range Not Satisfiable',
            417 => 'Expectation Failed',
            500 => 'Internal Server Error',
            501 => 'Not Implemented',
            502 => 'Bad Gateway',
            503 => 'Service Unavailable',
            504 => 'Gateway Timeout',
            505 => 'HTTP Version Not Supported'
        );
        return ($httpStatus[$statusCode]) ? $httpStatus[$statusCode] : $httpStatus[500];
    }

public function attach_link( $ATTACHMENT_ID, $ATTACHMENT_NAME, $SHOW_SIZE = 0, $DOWN_PRIV = 1, $DOWN_PRIV_OFFICE = 1, $EDIT_PRIV = 0, $DELETE_PRIV = 0, $NEW_LINE = 1, $SAVE_FILE = 1, $CREATE_IMAGE = 0, $MODULE = "", $IS_UTF8 = FALSE, $FORMAT = 0 )
{
    if ( $ATTACHMENT_ID == "" )
    {
        return "";
    }
    if ( $MODULE == "" )
    {
        $MODULE = attach_sub_dir( );
    }
    $LANG_ARRAY = array(
        "down" => _( "����" ),
        "read" => _( "�Ķ�" ),
        "edit" => _( "�༭" ),
        "view" => _( "�鿴" ),
        "rename" => _( "������" ),
        "delete" => _( "ɾ��" ),
        "save" => _( "ת��" ),
        "play" => _( "����" ),
        "preview" => _( "����Ԥ��" ),
        "insert" => _( "��������" ),
        "quick_preview" => _( "�����Ķ�" )
    );
    $INDEX_FILE_STYLE = get_sys_para( "INDEX_FILE_STYLE" );
    $INDEX_FILE_STYLE = $INDEX_FILE_STYLE['INDEX_FILE_STYLE'];
    if ( $IS_UTF8 )
    {
        foreach ( $LANG_ARRAY as $KEY => $VALUE )
        {
            $LANG_ARRAY[$KEY] = iconv( MYOA_CHARSET, "utf-8", $VALUE );
        }
    }
    $ATTACHMENT_ID_ARRAY = explode( ",", $ATTACHMENT_ID );
    $ATTACHMENT_NAME_ARRAY = explode( "*", $ATTACHMENT_NAME );
    $ATTACHMENT_NAME_SHOW_ARRAY = explode( "*", $ATTACHMENT_NAME );
    $I = 0;
    for ( ; $I < count( $ATTACHMENT_ID_ARRAY ); ++$I )
    {
        if ( !( $ATTACHMENT_ID_ARRAY[$I] == "" ) )
        {
            if ( $ATTACHMENT_NAME_ARRAY[$I] == "" )
            {
                break;
            }
        }
        else
        {
            continue;
        }
        $PRINT_PRIV_OFFICE = substr( $DOWN_PRIV_OFFICE, 1, 1 ) == "1" ? 1 : 0;
        $DOWN_PRIV_OFFICE_I = substr( $DOWN_PRIV_OFFICE, 0, 1 ) == "1" ? 1 : 0;
        $PRINT_PRIV_OFFICE = $PRINT_PRIV_OFFICE || $DOWN_PRIV_OFFICE_I;
        $OTHER = array(
            "OFFICE" => array(
                "OP" => $DOWN_PRIV_OFFICE_I || $EDIT_PRIV ? "7" : "5",
                "PRINT" => $PRINT_PRIV_OFFICE,
                "OP_PREVIEW" => $DOWN_PRIV_OFFICE_I || $EDIT_PRIV
            )
        );
        $ATTACH_NAME_ZX_ARRAY = explode( ".", $ATTACHMENT_NAME_SHOW_ARRAY[$I] );
        $ATTACHMENT_NAME_I = $ATTACHMENT_NAME_ARRAY[$I];
        $URL_ARRAY = attach_url( $ATTACHMENT_ID_ARRAY[$I], $ATTACHMENT_NAME_ARRAY[$I], $MODULE, $OTHER );
        $ATTACH_IMAGE = image_mimetype( $ATTACHMENT_NAME_ARRAY[$I] );
        $ATTACHMENT_ID_ENCODED = attach_id_encode( $ATTACHMENT_ID_ARRAY[$I], $ATTACHMENT_NAME_ARRAY[$I] );
        $HEX_ID = dechex( $ATTACHMENT_ID_ENCODED );
        $ATTACH_LINK_CLASS = "attach_link";
        if ( $NEW_LINE )
        {
            $ATTACH_LINK_CLASS .= " attach_link_block";
        }
        $ATTACH_LINK .= "<span class='".$ATTACH_LINK_CLASS."' onmouseover='showMenu(this.id);' id=\"attach_{$HEX_ID}\" style='white-space: inherit;'><img src=\"".MYOA_STATIC_SERVER."/static/images/file_type/".$ATTACH_IMAGE."\" align=\"absmiddle\"> ";
        if ( $SHOW_SIZE )
        {
            $ATTACH_SIZE = sprintf( "%u", attach_size( $ATTACHMENT_ID_ARRAY[$I], $ATTACHMENT_NAME_ARRAY[$I], $MODULE ) );
            if ( 0 < floor( $ATTACH_SIZE / 1024 / 1024 ) )
            {
                $ATTACH_SIZE = round( $ATTACH_SIZE / 1024 / 1024, 2 )."MB";
            }
            else if ( 0 < floor( $ATTACH_SIZE / 1024 ) )
            {
                $ATTACH_SIZE = round( $ATTACH_SIZE / 1024, 2 )."KB";
            }
            else
            {
                $ATTACH_SIZE .= "B";
            }
        }
        if ( $DOWN_PRIV )
        {
            if ( $DOWN_PRIV_OFFICE_I || !is_ntko_office( $ATTACHMENT_NAME_ARRAY[$I] ) )
            {
                $ATTACH_LINK .= "<a class=\"attach_name\" href=\"".$URL_ARRAY['down']."\"".( MYOA_ATTACH_OFFICE_OPEN_IN_IE ? " target=\"_blank\"" : "" ).">".td_htmlspecialchars( $ATTACHMENT_NAME_SHOW_ARRAY[$I], "<>" )."</a>";
                if ( $SHOW_SIZE )
                {
                    $ATTACH_LINK .= "(".$ATTACH_SIZE.")";
                }
                $ATTACH_LINK .= "</span>\n";
                $ATTACH_LINK .= "<div id=\"attach_".$HEX_ID."_menu\" class=\"attach_div\" title=\"".$ATTACHMENT_NAME_ARRAY[$I]."\">";
                $ATTACH_LINK .= "<a href=\"".$URL_ARRAY['down']."\"".( MYOA_ATTACH_OFFICE_OPEN_IN_IE ? " target=\"_blank\"" : "" ).">".$LANG_ARRAY['down']."</a>\n";
                if ( is_media( $ATTACHMENT_NAME_ARRAY[$I] ) )
                {
                    if ( is_image( $ATTACHMENT_NAME_ARRAY[$I] ) )
                    {
                        if ( !$DATA_GROUP )
                        {
                            $DATA_GROUP = $HEX_ID;
                        }
                        $ATTACH_LINK .= "<a href=\"javascript:;\" data-group=\"".$DATA_GROUP."\" data-url=\"".urlencode( $URL_ARRAY['down'] )."\" onClick=\"ShowImageGallery(this)\">".$LANG_ARRAY['play']."</a>\n";
                    }
                    else
                    {
                        $ATTACH_LINK .= "<a href=\"javascript:;\" onClick=\"window.open('/module/mediaplayer/index.php?MEDIA_NAME=".urlencode( $ATTACHMENT_NAME_ARRAY[$I] )."&MEDIA_URL=".urlencode( $URL_ARRAY['down'] )."','media".abs( $ATTACHMENT_ID_ENCODED )."','menubar=0,toolbar=0,status=1,scrollbars=1,resizable=1,width=800,height=600');\">".$LANG_ARRAY['play']."</a>\n";
                    }
                }
                if ( $SAVE_FILE && !$IS_UTF8 )
                {
                    $ATTACH_LINK .= "<a href=\"javascript:;\" onClick=\"".$URL_ARRAY['save']."\">".$LANG_ARRAY['save']."</a>\n";
                }
            }
            else
            {
                $ATTACH_LINK .= td_htmlspecialchars( $ATTACHMENT_NAME_ARRAY[$I], "<>" );
                if ( $SHOW_SIZE )
                {
                    $ATTACH_LINK .= "(".$ATTACH_SIZE.")";
                }
                $ATTACH_LINK .= "</span>\n";
                $ATTACH_LINK .= "<div id=\"attach_".$HEX_ID."_menu\" class=\"attach_div\" title=\"".$ATTACHMENT_NAME_ARRAY[$I]."\">";
            }
            if ( is_office( $ATTACHMENT_NAME_ARRAY[$I] ) )
            {
                if ( strtolower( substr( $ATTACHMENT_NAME_ARRAY[$I], -4 ) ) == ".xls" )
                {
                    $ATTACH_LINK .= "<a href=\"javascript:;\" onClick=\"window.open('".$URL_ARRAY['office_preview']."','preview".abs( $ATTACHMENT_ID_ENCODED )."','menubar=0,toolbar=0,status=0,scrollbars=0,resizable=1');\">".$LANG_ARRAY['preview']."</a>\n";
                }
                $ATTACH_LINK .= "<a href=\"javascript:;\" onClick=\"window.open('".$URL_ARRAY['office_read']."','read".abs( $ATTACHMENT_ID_ENCODED )."','menubar=0,toolbar=0,status=1,scrollbars=1,resizable=1');\">".$LANG_ARRAY['read']."</a>\n";
                if ( $EDIT_PRIV )
                {
                    $ATTACH_LINK .= "<a href=\"javascript:;\" onClick=\"window.open('".$URL_ARRAY['office_edit']."','edit".abs( $ATTACHMENT_ID_ENCODED )."','menubar=0,toolbar=0,status=1,scrollbars=1,resizable=1');\">".$LANG_ARRAY['edit']."</a>\n";
                }
            }
            else if ( is_ntko_pdf( $ATTACHMENT_NAME_ARRAY[$I] ) )
            {
                $ATTACH_LINK .= "<a href=\"javascript:;\" onClick=\"window.open('".$URL_ARRAY['office_read']."','read".abs( $ATTACHMENT_ID_ENCODED )."','menubar=0,toolbar=0,status=1,scrollbars=1,resizable=1');\">".$LANG_ARRAY['read']."</a>\n";
            }
            if ( MYOA_IS_UN == 0 && find_id( $INDEX_FILE_STYLE, strtolower( substr( $ATTACHMENT_NAME_ARRAY[$I], strrpos( $ATTACHMENT_NAME_ARRAY[$I], "." ) + 1 ) ) ) )
            {
                $ATTACH_LINK .= "<a href=\"javascript:;\" onClick=\"window.open('".$URL_ARRAY['quick_preview']."','quick_preview".abs( $ATTACHMENT_ID_ENCODED )."','menubar=0,toolbar=0,status=0,scrollbars=1,resizable=1');\">".$LANG_ARRAY['quick_preview']."</a>\n";
            }
            if ( is_aip( $ATTACHMENT_NAME_ARRAY[$I] ) )
            {
                $ATTACH_LINK .= "<a href=\"javascript:;\" onClick=\"window.open('".$URL_ARRAY['office_read']."','read".abs( $ATTACHMENT_ID_ENCODED )."','menubar=0,toolbar=0,status=1,scrollbars=1,resizable=1');\">".$LANG_ARRAY['read']."</a>\n";
                if ( $EDIT_PRIV )
                {
                    $ATTACH_LINK .= "<a href=\"javascript:;\" onClick=\"window.open('".$URL_ARRAY['office_edit']."','edit".abs( $ATTACHMENT_ID_ENCODED )."','menubar=0,toolbar=0,status=1,scrollbars=1,resizable=1');\">".$LANG_ARRAY['edit']."</a>\n";
                }
            }
            if ( $DELETE_PRIV )
            {
                $ATTACH_LINK .= "<a href=\"javascript:".$URL_ARRAY['delete']."\">".$LANG_ARRAY['delete']."</a>\n";
                if ( $MODULE == "file_folder" )
                {
                    $ATTACH_LINK .= "<a href=\"javascript:;\" onClick=\"".$URL_ARRAY['rename']."\">".$LANG_ARRAY['rename']."</a>\n";
                }
            }
            if ( $CREATE_IMAGE && is_image( $ATTACHMENT_NAME_ARRAY[$I] ) )
            {
                $ATTACH_LINK .= "<a id=\"insert_image_link_".$HEX_ID."\" href=\"javascript:InsertImage('".urlencode( $URL_ARRAY['down'] )."');\" title=\"".$ATTACHMENT_NAME_ARRAY[$I]."\">".$LANG_ARRAY['insert']."</a>\n";
            }
            $EXT_NAME = strtolower( substr( strrchr( $ATTACHMENT_NAME_ARRAY[$I], "." ), 1 ) );
            if ( $FORMAT && ( $EXT_NAME == "mht" || $EXT_NAME == "htm" || $EXT_NAME == "html" ) && !$DELETE_PRIV )
            {
                $ATTACH_LINK .= "<a href=\"javascript:;\" onClick=\"mhtFrame.location='".$URL_ARRAY['view']."';\">".$LANG_ARRAY['view']."</a>\n";
            }
            $ATTACH_LINK .= "</div>";
        }
        else
        {
            $ATTACH_LINK .= td_htmlspecialchars( $ATTACHMENT_NAME_ARRAY[$I], "<>" );
            if ( $SHOW_SIZE )
            {
                $ATTACH_LINK .= "(".$ATTACH_SIZE.")";
            }
            $ATTACH_LINK .= "</span>\n";
        }
    }
    return $ATTACH_LINK;
}
}
