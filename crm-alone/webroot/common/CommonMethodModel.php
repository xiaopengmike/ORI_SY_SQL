<?php
require_once("../inc/auth.php");
require_once("../inc/utility_all.php");
require_once("../inc/utility_file.php");
require_once("../inc/utility_field.php");
require_once("../inc/utility_sms1.php");
require_once("../inc/utility_sms2.php");
require_once("ResponseCode.php");

/**
 * ����������
 * @author ysr
 */
class CommonMethodModel
{
    /**
     * ��ȡǰ̨���ݵ�get��post����ֵ
     */
    public function getParamToArray()
    {
        $postParam = eval(" return " . @iconv("utf-8//IGNORE", "gbk//IGNORE", var_export($_POST, true)) . ";");
        $getParam = eval(" return " . @iconv("utf-8//IGNORE", "gbk//IGNORE", var_export($_GET, true)) . ";");
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
     * sms���ŷ�������
     * @param $param ��������
     */
    public function sendSms($param)
    {
        $remindUrl = $param['url'];
        $smsContent = $param['content'];
        $approver = $param['approver'];
        // ������Ϣ����
        send_sms("", $_SESSION["LOGIN_USER_ID"], $approver, 0, $smsContent, $remindUrl);
    }

    /**
     * �ʼ����͹���
     * @param array $param 
     * ����˵����subject  fromId  sendTo  ccTo  content
     */
    public function newMailData($param)
    {
        $SEND_TIME = time();
        $SUBJECT = $param['subject'];  // �ʼ�����
        $fromId = $param['fromId'];  // ���ӵ�������HR02�����ã�
        $SEND_TO = $param['sendTo'];  // ����Ա��
        $CC_TO = $param['ccTo'];  // ������Ա
        $CONTENT = $param['content'];  //�ʼ�����
        $result = array();

        $sql = "insert into MAIL (SUBJECT, FROM_ID, SEND_TO, CC_TO, SEND_TIME, REPLY_TIME) values"
            . "('$SUBJECT', '$fromId', '" . $SEND_TO . "', '" . $CC_TO . "', '$SEND_TIME','$SEND_TIME');";
        exequery(TD::conn(), $sql);
        $MAIL_ID = mysql_insert_id();

        if ($MAIL_ID == 0) {
            $result = array(
                'msg' => '����д�����ݿ�ʧ��(MAIL)',
                'status' => 'failed'
            );
            return $result;
        }

        $sql = "insert into MAIL_BODY (MAIL_ID, FIRST, AUTHOR, REPLY_TIME, SUBJECT, CONTENT) values"
            . "('$MAIL_ID', '1', '$fromId', '$SEND_TIME', '$SUBJECT', '" . $CONTENT . "');";
        exequery(TD::conn(), $sql);
        $BODY_ID = mysql_insert_id();

        if ($BODY_ID == 0) {
            $result = array(
                'msg' => '����д�����ݿ�ʧ��(MAIL_BODY)',
                'status' => 'failed'
            );
            return $result;
        }

        $TO_ID_STR = $SEND_TO . $CC_TO;
        $TO_ID_STR .= find_id($TO_ID_STR, $fromId) ? "" : $fromId . ",";
        $sql = "";
        $TO_ARRAY = explode(",", $TO_ID_STR);
        // ������Ա��������ȥ��
        $TO_ARRAY = array_flip($TO_ARRAY);
        $TO_ARRAY = array_flip($TO_ARRAY);

        foreach ($TO_ARRAY as $TO_ID) {
            if ($TO_ID == "") {
                continue;
            }
            $sql .= "('$MAIL_ID', '$TO_ID', '0', '0', '0', '0'),";
        }
        $sql = "insert into MAIL_TO (MAIL_ID, TO_ID, READ_FLAG, READ_COUNT, FIRST_READ, LAST_READ) values " . td_trim($sql);
        exequery(TD::conn(), $sql);
        if (mysql_affected_rows() < 0) {
            $result = array(
                'msg' => '�ʼ�����ʧ��',
                'status' => 'failed'
            );
            return $result;
        }
        $SMS_CONTENT = "������ҵ��ʼ���\n���⣺$SUBJECT";
        $TO_ID_STR = check_id($fromId, $SEND_TO . $CC_TO, false);
        send_sms("", $fromId, $TO_ID_STR, 2, $SMS_CONTENT, "1:mail/my/read.php?MAIL_ID=$MAIL_ID");

        $result = array(
            'msg' => '�ʼ����ͳɹ�',
            'status' => 'success'
        );

        return $result;
    }

    /**
     * ��ҳ�ؼ�����
     * @param $current_page ��ǰҳ��$total_items ������$page_size ÿҳ��ʾ������Ĭ��10����$script_href ��ѯ������Ĭ��ΪloadList()��$show_total �Ƿ���ʾ����
     * �ο������� <?= $model->page_bar($param['page'], $count, $param['page_size'], 'loadList(\'%s\')', '1') ?>
     */
    public function page_bar($current_page, $total_items, $page_size = 10, $script_href = NULL, $show_total = 0)
    {
        $current_start_item =  ($current_page - 1) * $page_size;
        $page_total = ceil($total_items / $page_size);
        if ($current_start_item < 0 || $total_items < $current_start_item) {
            $current_start_item = 0;
        }
        $num_pages = ceil($total_items / $page_size);
        $cur_page = ceil($current_start_item / $page_size) + 1;
        $result_str = '';
        $result_str .= "<script>function goto_page(){var page=parseInt(document.getElementById('page').value);" .
            "if(isNaN(page)||page<1||page>" . $num_pages . "){alert(\"" . sprintf(_("ҳ������Ϊ1-%s"), $num_pages) . "\");return false;}" .
            sprintf(str_replace("'", "", $script_href), "page") . ";} " .
            "function input_page_no(){if(event.keyCode==13) goto_page();if(event.keyCode<47||event.keyCode>57) event.returnValue=false;}</script>";
        if ($show_total == 1) {
            $result_str .= "<div id=\"pageArea\" class=\"pageArea\" style=\"width:350px\">\n";
            $result_str .= sprintf(_("��%s��") . "&nbsp;", "<span id=\"pageNumber\" class=\"pageNumber\">" . $total_items . "</span>");
        } else {
            $result_str .= "<div id=\"pageArea\" class=\"pageArea\">\n";
        }
        $result_str .= sprintf(_("��%sҳ"), "<span id=\"pageNumber\" class=\"pageNumber\">" . $cur_page . "/" . $num_pages . "</span>");
        if ($cur_page <= 1) {
            $result_str .= "<a href=\"javascript:;\" id=\"pageFirst\" class=\"pageFirstDisable\" title=\"" . _("��ҳ") . "\"></a>\r\n  " .
                "<a href=\"javascript:;\" id=\"pagePrevious\" class=\"pagePreviousDisable\" title=\"" . _("��һҳ") . "\"></a>";
        } else {
            $result_str .= "<a href=\"javascript:void(0);\" onclick=\"" . sprintf($script_href, '1') . ";return false;\" id=\"pageFirst\" class=\"pageFirst\" title=\"" . _("��ҳ") . "\"></a>\r\n  " .
                "<a href=\"javascript:void(0);\" onclick=\"" . sprintf($script_href, $cur_page - 1) . ";return false;\" id=\"pagePrevious\" class=\"pagePrevious\" title=\"" . _("��һҳ") . "\"></a>";
        }
        if ($num_pages <= $cur_page) {
            $result_str .= "<a href=\"javascript:;\" id=\"pageNext\" class=\"pageNextDisable\" title=\"" . _("��һҳ") . "\"></a>\r\n  " .
                "<a href=\"javascript:;\" id=\"pageLast\" class=\"pageLastDisable\" title=\"" . _("ĩҳ") . "\"></a>";
        } else {
            $result_str .= "<a href=\"javascript:void(0);\" onclick=\"" . sprintf($script_href, $cur_page + 1) . ";return false;\" id=\"pageNext\" class=\"pageNext\" title=\"" . _("��һҳ") . "\"></a>\r\n  " .
                "<a href=\"javascript:void(0);\" id=\"pageLast\" onclick=\"" . sprintf($script_href, $page_total) . ";return false;\" class=\"pageLast\" title=\"" . _("ĩҳ") . "\"></a>";
        }
        $result_str .= sprintf(_("ת�� �� %s ҳ "), "<input type=\"text\" size=\"3\" class=\"SmallInput\" name=\"page\" id=\"page\" " .
            "onkeypress=\"input_page_no()\" style='text-align:center;'>") .
            "<a href=\"javascript:goto_page();\" id=\"pageGoto\" class=\"pageGoto\" title=\"" . _("ת��") . "\"></a>";
        $result_str .= "</div>";


        return $result_str;
    }

    /**
     * php �����ַ�ת�壬ʵ���ַ�����ȫ�������ݿ�
     * �����ַ�����
     *
     * @param  [type] $content [description]
     * @return [type]          [description]
     */
    function text_filter($content)
    {
        // �������ַ�ת��Ϊ HTML ʵ��
        $content = htmlspecialchars($content);
        // ת��Ԫ�ַ���
        $content = quotemeta($content);
        // �Զ�������ַ���?���Ը���ҵ������������?
        $content = preg_replace('/\'/', "\'", $content);
        // . ������ת��
        $content = preg_replace('/\\\./', ".", $content);

        return $content;
    }

    function crm_help($type='customer_data'){
        $crm_help_html='';
        switch($type){
            case 'customer_data':
                $crm_help_html='
                <div class="dropdown">
                    <button class="layui-btn layui-btn-oa layui-btn-sm drop-btn">����</button>
                    <div class="dropdown-content" align="center">
                    <a class="help" href="../crm_help.html#_Toc17876" target="_blank" title="�����ĵ�">�ĵ�</a>
                    
                    </div>
                </div>';
                break;
            case 'product_order':
                $crm_help_html='
                <div class="dropdown">
                    <button class="layui-btn layui-btn-oa layui-btn-sm drop-btn">����</button>
                    <div class="dropdown-content" align="center">
                    <a class="help" href="../crm_help.html#_Toc17418" target="_blank" title="�����ĵ�">�ĵ�</a>
                    
                    </div>
                </div>';
                break;
            case 'project_code':
                $crm_help_html='
                <div class="dropdown">
                    <button class="layui-btn layui-btn-oa layui-btn-sm drop-btn">����</button>
                    <div class="dropdown-content" align="center">
                    <a class="help" href="../crm_help.html#_Toc17635" target="_blank" title="�����ĵ�">�ĵ�</a>
                    
                    </div>
                </div>';
                break;
            case 'project_number_participant':
                $crm_help_html='
                <div class="dropdown">
                    <button class="layui-btn layui-btn-oa layui-btn-sm drop-btn">����</button>
                    <div class="dropdown-content" align="center">
                    <a class="help" href="../crm_help.html#_Toc21861" target="_blank" title="�����ĵ�">�ĵ�</a>
                    
                    </div>
                </div>';
                break;
            case 'project_participant':
                $crm_help_html='
                <div class="dropdown">
                    <button class="layui-btn layui-btn-oa layui-btn-sm drop-btn">����</button>
                    <div class="dropdown-content" align="center">
                    <a class="help" href="../crm_help.html#_Toc17432" target="_blank" title="�����ĵ�">�ĵ�</a>
                    
                    </div>
                </div>';
                break;
            case 'project_review':
                $crm_help_html='
                <div class="dropdown">
                    <button class="layui-btn layui-btn-oa layui-btn-sm drop-btn">����</button>
                    <div class="dropdown-content" align="center">
                    <a class="help" href="../crm_help.html#_Toc18399" target="_blank" title="�����ĵ�">�ĵ�</a>
                    
                    </div>
                </div>';
                break;
            case 'sales_contract_review':
                $crm_help_html='
                <div class="dropdown">
                    <button class="layui-btn layui-btn-oa layui-btn-sm drop-btn">����</button>
                    <div class="dropdown-content" align="center">
                    <a class="help" href="../crm_help.html#_Toc28683" target="_blank" title="�����ĵ�">�ĵ�</a>
                    
                    </div>
                </div>';
                break;
            case 'sales_receipt':
                $crm_help_html='
                <div class="dropdown">
                    <button class="layui-btn layui-btn-oa layui-btn-sm drop-btn">����</button>
                    <div class="dropdown-content" align="center">
                    <a class="help" href="../crm_help.html#_Toc10793" target="_blank" title="�����ĵ�">�ĵ�</a>
            
                    </div>
                </div>';
                break;
            case 'shipping_notice':
                $crm_help_html='
                <div class="dropdown">
                    <button class="layui-btn layui-btn-oa layui-btn-sm drop-btn">����</button>
                    <div class="dropdown-content" align="center">
                    <a class="help" href="../crm_help.html#_Toc7222" target="_blank" title="�����ĵ�">�ĵ�</a>
                    
                    </div>
                </div>';
                break;
        }
        return $crm_help_html;
    }
}