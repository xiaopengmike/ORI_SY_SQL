<?php
require_once("inc/auth.inc.php");
require_once("inc/utility_all.php");
require_once("inc/utility_file.php");
require_once("inc/utility_field.php");
require_once("inc/utility_sms1.php");
require_once("inc/utility_sms2.php");
require_once("ResponseCode.php");

/**
 * 公共操作类
 * @author ysr
 */
class CommonMethodModel
{
    /**
     * 获取前台传递的get和post参数值
     */
    public function getParamToArray()
    {
        $postParam = eval(" return " . @iconv("utf-8//IGNORE", "gbk//IGNORE", var_export($_POST, true)) . ";");
        $getParam = eval(" return " . @iconv("utf-8//IGNORE", "gbk//IGNORE", var_export($_GET, true)) . ";");
        return array_merge($postParam, $getParam);
    }

    /**
     * 将含有GBK的中文数组转为utf-8
     *
     * @param array $arr   数组
     * @param string $in_charset 原字符串编码
     * @param string $out_charset 输出的字符串编码
     * @param string $returnType   返回值类型：数组；json字符串
     * @return array
     */
    public function array_iconv($arr, $in_charset = "gbk", $out_charset = "utf-8", $returnType = 'json')
    {
        $ret = eval('return ' . iconv($in_charset, $out_charset, var_export($arr, true) . ';'));
        // 返回值类型判断
        if ($returnType = "array") {
            return $ret;
        }

        // 这里转码之后可以输出json,默认输出json字符串
        return json_encode($ret);
    }

    /**
     * sms短信发送提醒
     * @param $param 参数数组
     */
    public function sendSms($param)
    {
        $remindUrl = $param['url'];
        $smsContent = $param['content'];
        $approver = $param['approver'];
        // 发送消息提醒
        send_sms("", $_SESSION["LOGIN_USER_ID"], $approver, 0, $smsContent, $remindUrl);
    }

    /**
     * 邮件发送功能
     * @param array $param 
     * 参数说明：subject  fromId  sendTo  ccTo  content
     */
    public function newMailData($param)
    {
        $SEND_TIME = time();
        $SUBJECT = $param['subject'];  // 邮件主题
        $fromId = $param['fromId'];  // 银河电力集团HR02（常用）
        $SEND_TO = $param['sendTo'];  // 发送员工
        $CC_TO = $param['ccTo'];  // 抄送人员
        $CONTENT = $param['content'];  //邮件内容
        $result = array();

        $sql = "insert into MAIL (SUBJECT, FROM_ID, SEND_TO, CC_TO, SEND_TIME, REPLY_TIME) values"
            . "('$SUBJECT', '$fromId', '" . $SEND_TO . "', '" . $CC_TO . "', '$SEND_TIME','$SEND_TIME');";
        exequery(TD::conn(), $sql);
        $MAIL_ID = mysql_insert_id();

        if ($MAIL_ID == 0) {
            $result = array(
                'msg' => '错误，写入数据库失败(MAIL)',
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
                'msg' => '错误，写入数据库失败(MAIL_BODY)',
                'status' => 'failed'
            );
            return $result;
        }

        $TO_ID_STR = $SEND_TO . $CC_TO;
        $TO_ID_STR .= find_id($TO_ID_STR, $fromId) ? "" : $fromId . ",";
        $sql = "";
        $TO_ARRAY = explode(",", $TO_ID_STR);
        // 发送人员数组数据去重
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
                'msg' => '邮件发送失败',
                'status' => 'failed'
            );
            return $result;
        }
        $SMS_CONTENT = "请查收我的邮件！\n主题：$SUBJECT";
        $TO_ID_STR = check_id($fromId, $SEND_TO . $CC_TO, false);
        send_sms("", $fromId, $TO_ID_STR, 2, $SMS_CONTENT, "1:mail/my/read.php?MAIL_ID=$MAIL_ID");

        $result = array(
            'msg' => '邮件发送成功',
            'status' => 'success'
        );

        return $result;
    }

    /**
     * 翻页控件构造
     * @param $current_page 当前页；$total_items 总数；$page_size 每页显示条数，默认10条；$script_href 查询函数，默认为loadList()；$show_total 是否显示总数
     * 参考范例： <?= $model->page_bar($param['page'], $count, $param['page_size'], 'loadList(\'%s\')', '1') ?>
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
            "if(isNaN(page)||page<1||page>" . $num_pages . "){alert(\"" . sprintf(_("页数必须为1-%s"), $num_pages) . "\");return false;}" .
            sprintf(str_replace("'", "", $script_href), "page") . ";} " .
            "function input_page_no(){if(event.keyCode==13) goto_page();if(event.keyCode<47||event.keyCode>57) event.returnValue=false;}</script>";
        if ($show_total == 1) {
            $result_str .= "<div id=\"pageArea\" class=\"pageArea\" style=\"width:350px\">\n";
            $result_str .= sprintf(_("共%s条") . "&nbsp;", "<span id=\"pageNumber\" class=\"pageNumber\">" . $total_items . "</span>");
        } else {
            $result_str .= "<div id=\"pageArea\" class=\"pageArea\">\n";
        }
        $result_str .= sprintf(_("第%s页"), "<span id=\"pageNumber\" class=\"pageNumber\">" . $cur_page . "/" . $num_pages . "</span>");
        if ($cur_page <= 1) {
            $result_str .= "<a href=\"javascript:;\" id=\"pageFirst\" class=\"pageFirstDisable\" title=\"" . _("首页") . "\"></a>\r\n  " .
                "<a href=\"javascript:;\" id=\"pagePrevious\" class=\"pagePreviousDisable\" title=\"" . _("上一页") . "\"></a>";
        } else {
            $result_str .= "<a href=\"javascript:void(0);\" onclick=\"" . sprintf($script_href, '1') . ";return false;\" id=\"pageFirst\" class=\"pageFirst\" title=\"" . _("首页") . "\"></a>\r\n  " .
                "<a href=\"javascript:void(0);\" onclick=\"" . sprintf($script_href, $cur_page - 1) . ";return false;\" id=\"pagePrevious\" class=\"pagePrevious\" title=\"" . _("上一页") . "\"></a>";
        }
        if ($num_pages <= $cur_page) {
            $result_str .= "<a href=\"javascript:;\" id=\"pageNext\" class=\"pageNextDisable\" title=\"" . _("下一页") . "\"></a>\r\n  " .
                "<a href=\"javascript:;\" id=\"pageLast\" class=\"pageLastDisable\" title=\"" . _("末页") . "\"></a>";
        } else {
            $result_str .= "<a href=\"javascript:void(0);\" onclick=\"" . sprintf($script_href, $cur_page + 1) . ";return false;\" id=\"pageNext\" class=\"pageNext\" title=\"" . _("下一页") . "\"></a>\r\n  " .
                "<a href=\"javascript:void(0);\" id=\"pageLast\" onclick=\"" . sprintf($script_href, $page_total) . ";return false;\" class=\"pageLast\" title=\"" . _("末页") . "\"></a>";
        }
        $result_str .= sprintf(_("转到 第 %s 页 "), "<input type=\"text\" size=\"3\" class=\"SmallInput\" name=\"page\" id=\"page\" " .
            "onkeypress=\"input_page_no()\" style='text-align:center;'>") .
            "<a href=\"javascript:goto_page();\" id=\"pageGoto\" class=\"pageGoto\" title=\"" . _("转到") . "\"></a>";
        $result_str .= "</div>";


        return $result_str;
    }

    /**
     * php 特殊字符转义，实现字符串安全存入数据库
     * 特殊字符过滤
     *
     * @param  [type] $content [description]
     * @return [type]          [description]
     */
    function text_filter($content)
    {
        // 将特殊字符转换为 HTML 实体
        $content = htmlspecialchars($content);
        // 转义元字符集
        $content = quotemeta($content);
        // 自定义过滤字符串,可以根据业务需求进行扩展
        $content = preg_replace('/\'/', "\'", $content);
        // . 不进行转换
        $content = preg_replace('/\\\./', ".", $content);

        return $content;
    }

    function crm_help($type='customer_data'){
        $crm_help_html='';
        switch($type){
            case 'customer_data':
                $crm_help_html='
                <div class="dropdown">
                    <button class="layui-btn layui-btn-oa layui-btn-sm drop-btn">帮助</button>
                    <div class="dropdown-content" align="center">
                    <a class="help" href="../crm_help.html#_Toc17876" target="_blank" title="帮助文档">文档</a>
                    
                    </div>
                </div>';
                break;
            case 'product_order':
                $crm_help_html='
                <div class="dropdown">
                    <button class="layui-btn layui-btn-oa layui-btn-sm drop-btn">帮助</button>
                    <div class="dropdown-content" align="center">
                    <a class="help" href="../crm_help.html#_Toc17418" target="_blank" title="帮助文档">文档</a>
                    
                    </div>
                </div>';
                break;
            case 'project_code':
                $crm_help_html='
                <div class="dropdown">
                    <button class="layui-btn layui-btn-oa layui-btn-sm drop-btn">帮助</button>
                    <div class="dropdown-content" align="center">
                    <a class="help" href="../crm_help.html#_Toc17635" target="_blank" title="帮助文档">文档</a>
                    
                    </div>
                </div>';
                break;
            case 'project_number_participant':
                $crm_help_html='
                <div class="dropdown">
                    <button class="layui-btn layui-btn-oa layui-btn-sm drop-btn">帮助</button>
                    <div class="dropdown-content" align="center">
                    <a class="help" href="../crm_help.html#_Toc21861" target="_blank" title="帮助文档">文档</a>
                    
                    </div>
                </div>';
                break;
            case 'project_participant':
                $crm_help_html='
                <div class="dropdown">
                    <button class="layui-btn layui-btn-oa layui-btn-sm drop-btn">帮助</button>
                    <div class="dropdown-content" align="center">
                    <a class="help" href="../crm_help.html#_Toc17432" target="_blank" title="帮助文档">文档</a>
                    
                    </div>
                </div>';
                break;
            case 'project_review':
                $crm_help_html='
                <div class="dropdown">
                    <button class="layui-btn layui-btn-oa layui-btn-sm drop-btn">帮助</button>
                    <div class="dropdown-content" align="center">
                    <a class="help" href="../crm_help.html#_Toc18399" target="_blank" title="帮助文档">文档</a>
                    
                    </div>
                </div>';
                break;
            case 'sales_contract_review':
                $crm_help_html='
                <div class="dropdown">
                    <button class="layui-btn layui-btn-oa layui-btn-sm drop-btn">帮助</button>
                    <div class="dropdown-content" align="center">
                    <a class="help" href="../crm_help.html#_Toc28683" target="_blank" title="帮助文档">文档</a>
                    
                    </div>
                </div>';
                break;
            case 'sales_receipt':
                $crm_help_html='
                <div class="dropdown">
                    <button class="layui-btn layui-btn-oa layui-btn-sm drop-btn">帮助</button>
                    <div class="dropdown-content" align="center">
                    <a class="help" href="../crm_help.html#_Toc10793" target="_blank" title="帮助文档">文档</a>
            
                    </div>
                </div>';
                break;
            case 'shipping_notice':
                $crm_help_html='
                <div class="dropdown">
                    <button class="layui-btn layui-btn-oa layui-btn-sm drop-btn">帮助</button>
                    <div class="dropdown-content" align="center">
                    <a class="help" href="../crm_help.html#_Toc7222" target="_blank" title="帮助文档">文档</a>
                    
                    </div>
                </div>';
                break;
        }
        return $crm_help_html;
    }
}